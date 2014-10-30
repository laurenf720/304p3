-- create database AMS;
-- USE AMS;
-- SELECT database();

DROP TABLE IF EXISTS returnitem;
DROP TABLE IF EXISTS returns;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS purchaseitem;
DROP TABLE IF EXISTS purchase; -- changing name of order table to purchase
DROP TABLE IF EXISTS hassong;
DROP TABLE IF EXISTS leadsinger;
DROP TABLE IF EXISTS item;

CREATE TABLE item 
	(upc 		CHAR(11) NOT NULL,
    title 		CHAR(11) NOT NULL,
    itype 		CHAR(3) NOT NULL, 	-- type is a keyword so i put 'i' before it
    category 	CHAR(12) NOT NULL, 	-- based on the length of longest allowed category
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
    
COMMIT;
    
    


    
    
