-- create database AMS;
-- USE AMS;
-- SELECT database();

drop table if exists item;

create table item 
	(upc 		CHAR(11) not null,
    title 		CHAR(11) not null,
    itype 		CHAR(3) not null, 	-- type is a keyword so i put 'i' before it
    category 	CHAR(12) not null, 	-- based on the length of longest allowed category
    company 	CHAR(15) not null,
    iyear 		SMALLINT not null,
    price		DOUBLE(5,2) not null, 
    stock		SMALLINT,
    PRIMARY KEY (upc));
    
drop table if exists leadsinger;

create table leadsinger
	(upc 		CHAR(11) not null,
    lsname 	CHAR(15) not null,
    PRIMARY KEY (upc, lsname)
    -- FOREIGN KEY (upc) REFERENCES item);
    );
    
drop table if exists hassong;

create table hassong
	(upc 		CHAR(11) not null,
    title 		CHAR(50) not null, -- how long should we allow song titles? I remember some pretty long ones back in the day
    PRIMARY KEY (upc, title));
    -- FOREIGN KEY (upc) REFERENCES item);

drop table if exists purchase; -- changing name of order table to purchase

create table purchase
	(receiptid		CHAR(11) not null,
    pdate 			CHAR(20) not null,
    cid 			CHAR(11) not null,
    cardnumber		CHAR(16) null,
    expirydate		CHAR(20) null,
    expecteddate 	CHAR(20) null,
    delivereddate	CHAR(20) null,
    PRIMARY KEY (receiptid));
    -- FOREIGN KEY (cid) REFERENCES customer);

drop table if exists purchaseitem;

create table purchaseitem
	(receiptid 		CHAR(11) not null,
    upc				CHAR(11) not null,
    quantity 		SMALLINT not NULL,
    PRIMARY KEY (receiptid, upc));
    -- FOREIGN KEY (receiptid) REFERENCES purchase,
    -- FOREIGN KEY (upc) REFERENCES item);

drop table if exists customer;

create table customer
	(cid 		CHAR(11) not null,
    cpassword	CHAR(30) null,
    cname 		CHAR(15) not null,
    address 	CHAR(30) null,
    phone		char(11) null,
    PRIMARY KEY (cid));

drop table if exists returns;

create table returns
	(retid		CHAR(11) not null,
    rdate 		CHAR(20) not null,
    receiptid 	CHAR(11) not null,
    PRIMARY KEY (retid));
    -- FOREIGN KEY (receiptid) references purchase);

drop table if exists returnitem;

create table returnitem
	(retid		CHAR(11) not null,
    upc 		CHAR(11) not null,
    quantity	smallint not null,
    PRIMARY KEY (retid, upc));
    -- FOREIGN KEY (upc) references item),
    -- FOREIGN KEY (retid) references returns);
    
commit;
    
    


    
    
