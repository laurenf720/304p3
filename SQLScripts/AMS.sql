-- create database AMS;
-- USE AMS;
-- SELECT database();

DROP TABLE IF EXISTS returnitem;
DROP TABLE IF EXISTS returns;
DROP TABLE IF EXISTS purchaseitem;
DROP TABLE IF EXISTS purchase; -- changing name of order table to purchase
DROP TABLE IF EXISTS hassong;
DROP TABLE IF EXISTS leadsinger;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS customer;

CREATE TABLE item 
	(upc 		CHAR(11) NOT NULL,
    title 		CHAR(11) NOT NULL,
    itype 		VARCHAR(3) NOT NULL, 	-- type is a keyword so i put 'i' before it
    category 	VARCHAR(12) NOT NULL, 	-- based on the length of longest allowed category
    company 	CHAR(15) NOT NULL,
    iyear 		SMALLINT NOT NULL,
    price		DOUBLE(5,2) NOT NULL, 
    stock		SMALLINT,
    PRIMARY KEY (upc));
    
CREATE TABLE leadsinger
	(upc 	CHAR(11) NOT NULL,
    lsname 	CHAR(15) NOT NULL,
    PRIMARY KEY (upc, lsname),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE hassong
	(upc 		CHAR(11) NOT NULL,
    title 		CHAR(50) NOT NULL, -- how long should we allow song titles? I remember some pretty long ones back in the day
    PRIMARY KEY (upc, title),
    FOREIGN KEY (upc) REFERENCES item (upc));

CREATE TABLE customer
	(cid 		CHAR(11) NOT NULL,
    cpassword	CHAR(30) NULL,
    cname 		CHAR(15) NOT NULL,
    address 	CHAR(30) NULL,
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
	(retid		CHAR(11) NOT NULL,
    rdate 		CHAR(20) NOT NULL,
    receiptid 	CHAR(11) NOT NULL,
    PRIMARY KEY (retid),
    FOREIGN KEY (receiptid) REFERENCES purchase (receiptid));

CREATE TABLE returnitem
	(retid		CHAR(11) NOT NULL,
    upc 		CHAR(11) NOT NULL,
    quantity	SMALLINT NOT NULL,
    PRIMARY KEY (retid, upc),
    FOREIGN KEY (upc) REFERENCES item (upc),
    FOREIGN KEY (retid) REFERENCES returns (retid));


-- The following are triggers defined to check domain of item type and item category
-- If a violation occurs, the insertion or update is aborted
DELIMITER $$

CREATE TRIGGER `item_check_insert` BEFORE INSERT ON `item`
FOR EACH ROW
BEGIN
    IF NEW.itype != 'CD' AND NEW.itype != 'DVD' THEN
        SIGNAL sqlstate '45001' set message_text = "violation in item type";
    END IF;
    IF NEW.category != 'rock' AND NEW.category != 'pop' AND NEW.category != 'rap' AND NEW.category != 'country' 
		AND NEW.category != 'classical' AND NEW.category != 'new age' AND NEW.category != 'instrumental' THEN
        SIGNAL sqlstate '45001' set message_text = "violation in item category";
	END IF;
END$$

CREATE TRIGGER `item_check_update` BEFORE UPDATE ON `item`
FOR EACH ROW
BEGIN
    IF NEW.itype != 'CD' AND NEW.itype != 'DVD' THEN
        SIGNAL sqlstate '45001' set message_text = "violation in item type";
    END IF;
    IF NEW.category != 'rock' AND NEW.category != 'pop' AND NEW.category != 'rap' AND NEW.category != 'country' 
		AND NEW.category != 'classical' AND NEW.category != 'new age' AND NEW.category != 'instrumental' THEN
        SIGNAL sqlstate '45001' set message_text = "violation in item category";
	END IF;
END$$

DELIMITER ; 

    
COMMIT;
    
    


    
    
