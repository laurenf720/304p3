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

INSERT INTO item
VALUES ('Q9BSWFRVT3W', 'Night Visions', 'CD', 'rock', 'KIDinaKORNER/Interscope Records', 2013, 6.99, 30);
INSERT INTO leadsinger
VALUES ('Q9BSWFRVT3W', 'Imagine Dragons');
INSERT INTO hassong
VALUES ('Q9BSWFRVT3W',''),
('Q9BSWFRVT3W','Radioactive'),
('Q9BSWFRVT3W','Tiptoe'),
('Q9BSWFRVT3W','It\'s Time'),
('Q9BSWFRVT3W','Demons'),
('Q9BSWFRVT3W','On Top of the World'),
('Q9BSWFRVT3W','Amsterdam'),
('Q9BSWFRVT3W','Hears Me'),
('Q9BSWFRVT3W','Every Night'),
('Q9BSWFRVT3W','Bleeding Out'),
('Q9BSWFRVT3W','Underdog'),
('Q9BSWFRVT3W','Nothing Left to Say/Rocks (Medley)'),
('Q9BSWFRVT3W','My Fault'),
('Q9BSWFRVT3W','Round and Round'),
('Q9BSWFRVT3W','The River'),
('Q9BSWFRVT3W','America'),
('Q9BSWFRVT3W','Selene'),
('Q9BSWFRVT3W','Cover Up'),
('Q9BSWFRVT3W','I Don\'t Mind');

INSERT INTO item
VALUES ('WH8SMJ9AOOM', 'This Is War', 'CD', 'rock', 'Virgin Records America, Inc.', 2009, 9.99, 15);
INSERT INTO leadsinger
VALUES ('WH8SMJ9AOOM', '30 Seconds to Mars');
INSERT INTO hassong
VALUES ('WH8SMJ9AOOM',''),
('WH8SMJ9AOOM','Escape'),
('WH8SMJ9AOOM','Night of the Hunter'),
('WH8SMJ9AOOM','Kings and Queens'),
('WH8SMJ9AOOM','This Is War'),
('WH8SMJ9AOOM','100 Suns'),
('WH8SMJ9AOOM','Hurricane'),
('WH8SMJ9AOOM','Closer to the Edge'),
('WH8SMJ9AOOM','Vox Populi'),
('WH8SMJ9AOOM','Search and Destroy'),
('WH8SMJ9AOOM','Alibi'),
('WH8SMJ9AOOM','Stranger In a Strange Land'),
('WH8SMJ9AOOM','L490'),
('WH8SMJ9AOOM','Kings and Queens (Remix)');

INSERT INTO item
VALUES ('YA3DSN131J3', 'Out of the Box', 'CD', 'instrumental', 'Conditional Use Records', 2014, 9.99, 10);
INSERT INTO leadsinger
VALUES ('YA3DSN131J3', 'Roy Bittan');
INSERT INTO hassong
VALUES ('YA3DSN131J3',''),
('YA3DSN131J3','The Dance'),
('YA3DSN131J3','Love Will Find a Way'),
('YA3DSN131J3','Jammin With Myself'),
('YA3DSN131J3','Riders in the Night'),
('YA3DSN131J3','Clouds'),
('YA3DSN131J3','Into the Blue'),
('YA3DSN131J3','Like the Sea'),
('YA3DSN131J3','Santiago\'s Dream'),
('YA3DSN131J3','Q\'s Groove');

INSERT INTO item
VALUES ('5679RIVE0DQ', 'Lullaby', 'CD', 'new age', 'Chopin', 2009, 9.99, 20);
INSERT INTO leadsinger
VALUES ('5679RIVE0DQ', 'Chopin');
INSERT INTO hassong
VALUES ('5679RIVE0DQ', ''),
('5679RIVE0DQ', 'Relaxation'),
('5679RIVE0DQ', 'Calm'),
('5679RIVE0DQ', 'Soft Music'),
('5679RIVE0DQ', 'Quiet Time'),
('5679RIVE0DQ', 'Free Time'),
('5679RIVE0DQ', 'Lounge'),
('5679RIVE0DQ', 'Rest Time'),
('5679RIVE0DQ', 'Quietude'),
('5679RIVE0DQ', 'Lullaby'),
('5679RIVE0DQ', 'Air No.3 in A Major, Op.8'),
('5679RIVE0DQ', 'Ballade No.1 in A Minor, Op.8');

INSERT INTO item
VALUES ('CZMCR3EQVNB', '111 Chopin Masterpieces', 'CD', 'classical', 'Menuetto Classics', 2009, 11.99, 20);
INSERT INTO leadsinger
VALUES ('CZMCR3EQVNB', 'Frédèric Chopin');
INSERT INTO hassong
VALUES ('CZMCR3EQVNB', 'Impromptu In C-Sharp Major'),
('CZMCR3EQVNB', 'Waltzes, Op.34 No.1'),
('CZMCR3EQVNB', 'Waltzes, Op.34 No.2'),
('CZMCR3EQVNB', 'Waltzes, Op.34 No.3'),
('CZMCR3EQVNB', 'Piano Sonata No.2'),
('CZMCR3EQVNB', 'Walts In E-Flat Major'),
('CZMCR3EQVNB', 'Waltzes, Op.42'),
('CZMCR3EQVNB', 'Waltzes, Op. 64: No.1'),
('CZMCR3EQVNB', 'Waltzes, Op. 64: No.2'),
('CZMCR3EQVNB', 'Waltzes, Op. 64: No.3'),
('CZMCR3EQVNB', 'Waltzes, Op. 70: No.1');

INSERT INTO item
VALUES ('U0TUTLFVRVC', 'Recovery', 'CD', 'rap', 'Aftermath Records', 2010, 6.99, 30);
INSERT INTO leadsinger
VALUES ('U0TUTLFVRVC', 'Eminem');
INSERT INTO hassong
VALUES ('U0TUTLFVRVC', ''),
('U0TUTLFVRVC', 'Cold Wind Blows'),
('U0TUTLFVRVC', 'Talkin\' 2 Myself'),
('U0TUTLFVRVC', 'On Fire'),
('U0TUTLFVRVC', 'Won\'t Back Down'),
('U0TUTLFVRVC', 'W.T.P.'),
('U0TUTLFVRVC', 'Going Through Changes'),
('U0TUTLFVRVC', 'Not Afraid'),
('U0TUTLFVRVC', 'No Love'),
('U0TUTLFVRVC', 'Space Bound'),
('U0TUTLFVRVC', 'Cinderella Man'),
('U0TUTLFVRVC', '25 to Life'),
('U0TUTLFVRVC', 'So Bad'),
('U0TUTLFVRVC', 'Almost Famous'),
('U0TUTLFVRVC', 'Love the Way You Lie');

INSERT INTO item
VALUES ('CBEMHGHVGE7', 'Fuse', 'CD', 'country', 'Hit Red Records', 2013, 6.99, 30);
INSERT INTO leadsinger
VALUES ('CBEMHGHVGE7', 'Keith Urban');
INSERT INTO hassong
VALUES ('CBEMHGHVGE7',''),
('CBEMHGHVGE7','Somewhere In My Car'),
('CBEMHGHVGE7','Even the Stars Fall 4 U'),
('CBEMHGHVGE7','Cop Car'),
('CBEMHGHVGE7','Shame'),
('CBEMHGHVGE7','Good Thing'),
('CBEMHGHVGE7','We Were Us'),
('CBEMHGHVGE7','Love\'s Poster Child'),
('CBEMHGHVGE7','She\'s My 11'),
('CBEMHGHVGE7','Come Back To Me'),
('CBEMHGHVGE7','Red Camaro'),
('CBEMHGHVGE7','Little Bit of Everything'),
('CBEMHGHVGE7','Heart Like Mine');

INSERT INTO item
VALUES ('FYZGRM7KGDI', 'Blown Away', 'CD', 'country', 'Recordings Limited', 2012, 11.99, 50);
INSERT INTO leadsinger
VALUES ('FYZGRM7KGDI', 'Carrie Underwood');
INSERT INTO hassong
VALUES ('FYZGRM7KGDI', ''),
('FYZGRM7KGDI', 'Good Girl'),
('FYZGRM7KGDI', 'Blown Away'),
('FYZGRM7KGDI', 'Two Black Cadilacs'),
('FYZGRM7KGDI', 'See You Again'),
('FYZGRM7KGDI', 'Do You Think About Me'),
('FYZGRM7KGDI', 'Forever Changed'),
('FYZGRM7KGDI', 'Nobody Ever Told You'),
('FYZGRM7KGDI', 'One Way Ticket'),
('FYZGRM7KGDI', 'Thank God for Hometowns'),
('FYZGRM7KGDI', 'Good in Goodbye'),
('FYZGRM7KGDI', 'Leave Love Alone'),
('FYZGRM7KGDI', 'Cupid\'s Got a Shotgun'),
('FYZGRM7KGDI', 'Wine After Whiskey');

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

INSERT INTO purchase
VALUES ('1', '2014-11-03', 'customer1','123456','01/15','2014-12-03',null);
INSERT INTO purchaseitem
VALUES ('1','8VKFHIIVCQV','2'),
('1','O8TA2SKT0S2','1');

INSERT INTO purchase
VALUES ('2', '2014-11-14', 'customer2','123456789','01/15','2014-11-13',null);
INSERT INTO purchaseitem
VALUES ('2','8VKFHIIVCQV','3'),
('2','O8TA2SKT0S2','2');

INSERT INTO purchase
VALUES ('3', '2014-11-15', 'customer2','123456789','01/15','2014-11-23',null);
INSERT INTO purchaseitem
VALUES ('3','5679RIVE0DQ','4'),
('3','CZMCR3EQVNB','1');

INSERT INTO purchase
VALUES ('4', '2014-11-16', 'customer1','123456','01/15','2014-11-23',null);
INSERT INTO purchaseitem
VALUES ('4','CBEMHGHVGE7','4'),
('4','YA3DSN131J3','1');

COMMIT;
    
    


    
    
