-- create database AMS;
USE AMS;
SELECT database();

DROP TRIGGER IF EXISTS item_check_insert;
DROP TRIGGER IF EXISTS item_check_update;

DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS returnitem;
DROP TABLE IF EXISTS returns;   -- changing name of Return table to Returns
DROP TABLE IF EXISTS purchaseitem;
DROP TABLE IF EXISTS purchase; -- changing name of Order table to Purchase
DROP TABLE IF EXISTS hassong;
DROP TABLE IF EXISTS leadsinger;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS users;

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
    lsname 	VARCHAR(255) NOT NULL,
    PRIMARY KEY (upc, lsname),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE hassong
	(upc 		CHAR(11) NOT NULL,
    songtitle 	VARCHAR(50) NOT NULL, 
    PRIMARY KEY (upc, songtitle),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE users
    (uid        VARCHAR(11) NOT NULL,
    upassword   VARCHAR(30) NOT NULL,
    uname       VARCHAR(30) NOT NULL,
    PRIMARY KEY (uid));

CREATE TABLE customer
	(cid 		VARCHAR(11) NOT NULL,
    address 	VARCHAR(30) NULL,
    phone		CHAR(11) NULL,
    PRIMARY KEY (cid),
    FOREIGN KEY (cid) REFERENCES users (uid));

CREATE TABLE purchase
	(receiptid		INT NOT NULL AUTO_INCREMENT,
    pdate 			DATE NOT NULL,
    cid 			CHAR(11) NOT NULL,
    cardnumber		CHAR(16) NOT NULL,
    expirydate		CHAR(7) NOT NULL,
    expecteddate 	DATE NOT NULL,
    delivereddate	DATE NULL,
    PRIMARY KEY (receiptid),
    FOREIGN KEY (cid) REFERENCES customer (cid));

CREATE TABLE purchaseitem
	(receiptid 		INT NOT NULL,
    upc				CHAR(11) NOT NULL,
    quantity 		SMALLINT NOT NULL,
    PRIMARY KEY (receiptid, upc),
    FOREIGN KEY (receiptid) REFERENCES purchase (receiptid),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE returns
	(retid		INT NOT NULL AUTO_INCREMENT,
    rdate 		DATE NOT NULL,
    receiptid 	INT NOT NULL,
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

-- Following triggeres were initially used to limit the user types for employees     
-- CREATE TRIGGER `employee_check_insert` BEFORE INSERT ON `employee`
-- FOR EACH ROW
-- BEGIN
-- 	IF NEW.etype != 'manager' AND NEW.etype != 'clerk' THEN
-- 		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in employee type must be 'clerk' or 'manager'";
-- 	END IF;
-- END$$
-- 
-- CREATE TRIGGER `employee_check_update` BEFORE UPDATE ON `employee`
-- FOR EACH ROW
-- BEGIN
-- 	IF NEW.etype != 'manager' AND NEW.etype != 'clerk' THEN
-- 		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = "violation in employee type must be 'clerk' or 'manager'";
-- 	END IF;
-- END$$

DELIMITER ; 
-- ------------------------------------------------------------------------------------
-- Item Definitions 
-- ------------------------------------------------------------------------------------

INSERT INTO item
VALUES ('8VKFHIIVCQV', '1989 (Deluxe Edition)', 'CD', 'pop', 'Big Machine Records, LLC.', 2014, 14.99, 45);

INSERT INTO leadsinger
VALUES ('8VKFHIIVCQV', 'Taylor Swift');

INSERT INTO hassong
VALUES ('8VKFHIIVCQV', 'Welcome To New York'),
('8VKFHIIVCQV', 'Blank Space'),
('8VKFHIIVCQV', 'Style'),
('8VKFHIIVCQV', 'Out of the Woods'),
('8VKFHIIVCQV', 'All You Had To Do Was Stay'),
('8VKFHIIVCQV', 'Shake It Off'),
('8VKFHIIVCQV', 'I Wish You Would'),
('8VKFHIIVCQV', 'Bad Blood'),
('8VKFHIIVCQV', 'Wildest Dreams'),
('8VKFHIIVCQV', 'How You Get The Girl'),
('8VKFHIIVCQV', 'This Love'),
('8VKFHIIVCQV', 'I Know Places'),
('8VKFHIIVCQV', 'Clean'),
('8VKFHIIVCQV', 'Wonderland'),
('8VKFHIIVCQV', 'New Romantics');

INSERT INTO item
VALUES ('O8TA2SKT0S2', 'V (Deluxe Version)', 'CD', 'pop', 'Interscope Records', 2014, 6.99, 25);

INSERT INTO leadsinger
VALUES ('O8TA2SKT0S2', 'Maroon 5'),
('O8TA2SKT0S2', 'Adam Levine');

INSERT INTO hassong
VALUES ('O8TA2SKT0S2',''),
('O8TA2SKT0S2','Maps'),
('O8TA2SKT0S2','Animals'),
('O8TA2SKT0S2','It Was Always You'),
('O8TA2SKT0S2','Unkiss Me'),
('O8TA2SKT0S2','Sugar'),
('O8TA2SKT0S2','Leaving California'),
('O8TA2SKT0S2','In Your Pocket'),
('O8TA2SKT0S2','New Love'),
('O8TA2SKT0S2','Coming Back For You'),
('O8TA2SKT0S2','Feelings'),
('O8TA2SKT0S2','My Heart Is Open [Feat. Gwen Stefani]'),
('O8TA2SKT0S2','Shoot Love'),
('O8TA2SKT0S2','Sex and Candy'),
('O8TA2SKT0S2','Lost Stars');

-- ------------------------------------------------------------------------------------
-- User Definitions
-- ------------------------------------------------------------------------------------

INSERT INTO users
VALUES ('customer1', 'password', 'Customer One');

INSERT INTO customer
VALUES ('customer1', '123 vancouver', '123 456');

INSERT INTO users
VALUES ('customer2', 'password', 'Customer Two');

INSERT INTO customer
VALUES ('customer2', '123 vancouver', '123 456');

INSERT INTO users
VALUES ('employee1', 'password', 'Clerk One');

INSERT INTO users
VALUES ('employee2', 'password', 'Clerk Two');

INSERT INTO users
VALUES ('manager1', 'password', 'Manager One');

-- ------------------------------------------------------------------------------------
-- Purchase Definitions
-- ------------------------------------------------------------------------------------

-- INSERT INTO purchase
-- VALUES ('1', '2014-11-03', 'cust1','123','456','2014-12-03',null);
-- INSERT INTO purchaseitem
-- VALUES ('1','1','1');
-- INSERT INTO purchaseitem
-- VALUES ('1','8','2');

COMMIT;
    
    


    
    
