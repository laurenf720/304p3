-- create database AMS;
USE AMS;
SELECT database();

DROP TRIGGER IF EXISTS item_check_insert;
DROP TRIGGER IF EXISTS item_check_update;
DROP TRIGGER IF EXISTS employee_check_insert;
DROP TRIGGER IF EXISTS employee_check_update;

DROP TABLE IF EXISTS returnitem;
DROP TABLE IF EXISTS returns;
DROP TABLE IF EXISTS purchaseitem;
DROP TABLE IF EXISTS purchase; -- changing name of order table to purchase
DROP TABLE IF EXISTS hassong;
DROP TABLE IF EXISTS leadsinger;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS employee;

CREATE TABLE item 
	(upc 		CHAR(11) NOT NULL,
    title 		VARCHAR(50) NOT NULL,
    itype 		VARCHAR(3) NOT NULL, 	-- type is a keyword so i put 'i' before it
    category 	VARCHAR(12) NOT NULL, 	-- based on the length of longest allowed category
    company 	VARCHAR(50) NOT NULL,
    iyear 		SMALLINT NOT NULL,
    price		DOUBLE(5,2) NOT NULL, 
    stock		SMALLINT,
    PRIMARY KEY (upc));
    
CREATE TABLE leadsinger
	(upc 	CHAR(11) NOT NULL,
    lsname 	VARCHAR(50) NOT NULL,
    PRIMARY KEY (upc, lsname),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE hassong
	(upc 		CHAR(11) NOT NULL,
    songtitle 	VARCHAR(50) NOT NULL, -- how long should we allow song titles? I remember some pretty long ones back in the day
    PRIMARY KEY (upc, songtitle),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE employee
    (eid        VARCHAR(11) NOT NULL,
    epassword   VARCHAR(30) NOT NULL,
    etype       VARCHAR(7) NOT NULL, -- either clerk or manager
    ename       VARCHAR(30) NOT NULL,
    PRIMARY KEY (eid));

CREATE TABLE customer
	(cid 		CHAR(11) NOT NULL,
    cpassword	VARCHAR(30) NULL,
    cname 		VARCHAR(30) NOT NULL,
    address 	VARCHAR(30) NULL,
    phone		CHAR(11) NULL,
    PRIMARY KEY (cid));

CREATE TABLE purchase
	(receiptid		CHAR(11) NOT NULL,
    pdate 			CHAR(20) NOT NULL,
    cid 			CHAR(11) NOT NULL,
    cardnumber		CHAR(16) NULL,
    expirydate		CHAR(20) NULL,
    expecteddate 	CHAR(20) NULL,
    delivereddate	CHAR(20) NULL,
    PRIMARY KEY (receiptid),
    FOREIGN KEY (cid) REFERENCES customer (cid));

CREATE TABLE purchaseitem
	(receiptid 		CHAR(11) NOT NULL,
    upc				CHAR(11) NOT NULL,
    quantity 		SMALLINT NOT NULL,
    PRIMARY KEY (receiptid, upc),
    FOREIGN KEY (receiptid) REFERENCES purchase (receiptid),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE returns
	(retid		INT NOT NULL AUTO_INCREMENT,
    rdate 		CHAR(20) NOT NULL,
    receiptid 	CHAR(11) NOT NULL,
    PRIMARY KEY (retid),
    FOREIGN KEY (receiptid) REFERENCES purchase (receiptid));

CREATE TABLE returnitem
	(retid		INT NOT NULL AUTO_INCREMENT,
    upc 		CHAR(11) NOT NULL,
    quantity	SMALLINT NOT NULL,
    PRIMARY KEY (retid, upc),
    FOREIGN KEY (upc) REFERENCES item (upc),
    FOREIGN KEY (retid) REFERENCES returns (retid));
    
CREATE TABLE cart 
	(cid CHAR(11) not null,
    upc CHAR(11) not null,
    quantity smallint not null,
    foreign key (cid) references customer (cid),
    foreign key (upc) references item(upc),
    primary key (cid, upc));


-- The following are triggers defined to check domain of item type, item category, year, price, and stock
-- If a violation occurs, the insertion or update is aborted
DELIMITER $$

CREATE TRIGGER `item_check_insert` BEFORE INSERT ON `item`
FOR EACH ROW
BEGIN
    IF NEW.itype != 'CD' AND NEW.itype != 'DVD' THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in item type";
    END IF;
    IF NEW.category != 'rock' AND NEW.category != 'pop' AND NEW.category != 'rap' AND NEW.category != 'country' 
		AND NEW.category != 'classical' AND NEW.category != 'new age' AND NEW.category != 'instrumental' THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in item category";
	END IF;
    IF NEW.iyear <= 0 OR NEW.price <0 OR NEW.stock <0 THEN
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in one of item year price or stock";
	END IF;
END$$

CREATE TRIGGER `item_check_update` BEFORE UPDATE ON `item`
FOR EACH ROW
BEGIN
    IF NEW.itype != 'CD' AND NEW.itype != 'DVD' THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in item type";
    END IF;
    IF NEW.category != 'rock' AND NEW.category != 'pop' AND NEW.category != 'rap' AND NEW.category != 'country' 
		AND NEW.category != 'classical' AND NEW.category != 'new age' AND NEW.category != 'instrumental' THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in item category";
	END IF;
    IF NEW.iyear <= 0 OR NEW.price <0 OR NEW.stock <0 THEN
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in one of item year price or stock";
	END IF;
END $$
    
CREATE TRIGGER `employee_check_insert` BEFORE INSERT ON `employee`
FOR EACH ROW
BEGIN
	IF NEW.etype != 'manager' AND NEW.etype != 'clerk' THEN
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in employee type must be 'clerk' or 'manager'";
	END IF;
END$$

CREATE TRIGGER `employee_check_update` BEFORE UPDATE ON `employee`
FOR EACH ROW
BEGIN
	IF NEW.etype != 'manager' AND NEW.etype != 'clerk' THEN
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in employee type must be 'clerk' or 'manager'";
	END IF;
END$$

DELIMITER ; 

INSERT INTO item
VALUES ('1', 'Spice', 'CD', 'pop', 'Virgin', 1996, 10.00, 26);
INSERT INTO item
VALUES ('7', 'Spice World', 'CD', 'pop', 'Virgin', 1997, 9.00, 26);

INSERT INTO item 
VALUES ('2', 'Sleep Sounds', 'CD', 'instrumental', 'Relaxation Music Relaxing 101', 2012, 9.99, 10);

INSERT INTO item
VALUES ('3', 'Night Visions', 'CD', 'rock', 'Interscope Records', 2012, 9.99, 30);

INSERT INTO item
VALUES ('4', 'Recovery (Deluxe Edition)', 'CD', 'rap', 'Aftermath Records', 2010, 14.99, 15);

INSERT INTO item
VALUES ('5', '1989 (Deluxe Edition)', 'CD', 'country', 'Big Machine Records, LLC.', 2014, 14.99, 35);

INSERT INTO item
VALUES ('6', '111 Classical Masterpieces', 'CD', 'classical', 'Menuetto Classics', 2009, 9.99, 14);

INSERT INTO item
VALUES ('8', '9.99 test', 'CD', 'classical', 'Menuetto Classics', 2009, 19.99, 14);
    
INSERT INTO employee
VALUES ('manager1', 'password', 'manager', 'man1');

INSERT INTO customer
VALUES ('cust1', 'password', 'lauren fung', '123 vancouver', '123 456');

INSERT INTO employee
VALUES ('employee1', 'password', 'clerk', 'clerk1');

INSERT INTO purchase
VALUES ('1', '2014-11-3', 'cust1','123','456','789','1234');
INSERT INTO purchaseitem
VALUES ('1','1','4');

INSERT INTO purchase
VALUES ('2', '2014-10-3', 'cust1','123','456','789','1234');
INSERT INTO purchaseitem
VALUES ('2','1','4');
INSERT INTO purchaseitem
VALUES ('2','2','3');

INSERT INTO leadsinger
VALUES ('4', 'Eminem');

INSERT INTO leadsinger
VALUES ('1', 'Spice Girls');

INSERT INTO leadsinger
VALUES ('7', 'Spice Girls');
INSERT INTO leadsinger
VALUES ('7', 'test multiple');
INSERT INTO leadsinger
VALUES ('5', 'Taylor Swift');
INSERT INTO leadsinger
VALUES ('3', 'Imagine Dragons');

INSERT INTO leadsinger
VALUES ('2', 'Random People');

COMMIT;
    
    


    
    
