/*
 * CS 133: Final project
 *
 * Authors: Bruce Yan, Angela Zhou
 * E-mail: byan@hmc.edu, azhou@hmc.edu
 *
 * To connect to SQL database, follow the steps below:
 *     1. SSH into yaddazonkey@claremontbooks.com, navigate to sql folder
 *     2. mysql -u byaz -p -h mysql.claremontbooks.com zonkey
 *     3. Enter your root password first (if prompted)
 *     4. Enter byaz's password, which is: Zonkey9387!$
 *     5. load "source script1.sql"
 *
*/


# Explicitly use database zonkey
USE zonkey;

# Lost Table


# Mudder Bike data - DO NOT RUN THE FOLLOWING, all good.
/*
DROP TABLE IF EXISTS mudderbikedata;
CREATE TABLE mudderbikedata (
	bikeid INTEGER NOT NULL AUTO_INCREMENT,
	available VARCHAR(64) NOT NULL,
	notes VARCHAR(256),
	dateofbirth DATE,
	dateofdeath DATE,
	PRIMARY KEY (bikeid)
);
*/

/* Property owners' table:
 *
 */

 DROP TABLE IF EXISTS owners;
 CREATE TABLE owners (
 	name VARCHAR(256) NOT NULL,
 	ownerid INT NOT NULL,
 	description VARCHAR(256),
 	contactName VARCHAR(256),
 	contactNum VARCHAR(64),
 	contactEmail VARCHAR(256),
 	PRIMARY KEY (ownerid)
 );

 DROP TABLE IF EXISTS lost;
 CREATE TABLE lost (
 	name VARCHAR(256) NOT NULL,
 	dateLost VARCHAR(64),
 	dateReturned VARCHAR(64),
 	ownerName VARCHAR(256) NOT NULL,
 	ownerContact VARCHAR(64),
 	found INTEGER,
 	description VARCHAR(256),
 	);

 DROP TABLE IF EXISTS found;
 CREATE TABLE found (
 	name VARCHAR(256) NOT NULL,
 	dateLost VARCHAR(64),
 	dateReturned VARCHAR(64) NOT NULL,
 	returnedTo VARCHAR(256),
 	found INTEGER,
 	description VARCHAR(256),
 	);

DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms (
	rid int NOT NULL,
	name VARCHAR(256),
	status VARCHAR(256), /* NONE, inuse, reserved, returnedn, late */
	reservDate VARCHAR(256) NOT NULL,
	duration VARCHAR(256) NOT NULL,
	contactName VARCHAR(256) NOT NULL,
	description VARCHAR(256),
);

/* Equipment Data */
DROP TABLE IF EXISTS equipmentdata;
CREATE TABLE equipmentdata (
	equipmentid INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	qtyleft INT DEFAULT 999,
	notes VARCHAR(256),
	ownerid INT DEFAULT 0,
	PRIMARY KEY (equipmentid)
);

/* Sample Equipment data */
INSERT INTO equipmentdata (name) VALUES ("Xbox controller");
INSERT INTO equipmentdata (name) VALUES ("Ping pong paddle");
INSERT INTO equipmentdata (name) VALUES ("billiards supplies");
INSERT INTO equipmentdata (name) VALUES ("basketball");
INSERT INTO equipmentdata (name) VALUES ("soccerball");
INSERT INTO equipmentdata (name) VALUES ("pool");
INSERT INTO equipmentdata (name) VALUES ("ping pong paddles");

/* Equipment Rentals */
DROP TABLE IF EXISTS equipmentrentals;
CREATE TABLE equipmentrentals (
	rentid INT NOT NULL AUTO_INCREMENT,
	equipmentid INT NOT NULL,
	sname VARCHAR(64) NOT NULL,
	sid INT NOT NULL,
	dateout DATE NOT NULL,
	datein DATE,
	school VARCHAR(64) NOT NULL,
	timeout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	timein TIMESTAMP,
	PRIMARY KEY (rentid)
);

/* Sample Equipment Rental data */
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (0, "Bruce Yan", 40114398, "2014-05-06", "HMC");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (2, "Bruce Yan", 40114398, "2014-05-06", "HMC");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (1, "John Paul", 40111234, "2014-05-06", "CMC");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (1, "Angela Zhou", 40156876, "2014-05-05", "HMC");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (3, "Robin Star", 40131461, "2014-05-03", "CGU");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (4, "Eva Woods", 40159987, "2014-05-02", "POM");
INSERT INTO equipmentrentals (equipmentid, sname, sid, dateout, school)
	VALUES (6, "Kiwi Fruit", 40163526, "2014-05-04", "PTZ");

/*
 *
 * OLD STUFF
 *	(Not needed)
 */
/*
# Products: relation that gives the mfg, model number, type of products
CREATE TABLE Products (
	model INTEGER NOT NULL,
	maker VARCHAR(256),
	type VARCHAR(64) NOT NULL,
	PRIMARY KEY (model)
);

# PCs: relation gives for each model number that is a PC the speed, amount of
# ram, amount of HD, and price
CREATE TABLE PCs (
	model INTEGER NOT NULL,
	speed REAL,
	ram REAL,
	hd INTEGER,
	price REAL,
	PRIMARY KEY (model),
	FOREIGN KEY (model) REFERENCES Products (model)
);

# Laptops: relation gives for each model number that is a laptop the speed,
# amount of ram, amount of HD, price and screen size
CREATE TABLE Laptops (
	model INTEGER NOT NULL,
	speed REAL,
	ram REAL,
	hd INTEGER,
	screen REAL,
	price REAL,
	PRIMARY KEY (model),
	FOREIGN KEY (model) REFERENCES Products (model)
);

# Printers: elation records for each printer model whether the printer produces
# color output (true, if so), the process type (laser or ink-jet, typically),
# and the price.
CREATE TABLE Printers (
	model INTEGER NOT NULL,
	color BOOLEAN NOT NULL,
	printertype VARCHAR(16),
	price REAL,
	PRIMARY KEY (model),
	FOREIGN KEY (model) REFERENCES Products (model)
);

# Load some sample Products
INSERT INTO Products (maker, model, type)
	VALUES ("HP", 1001, "PC");
INSERT INTO Products (maker, model, type)
	VALUES ("Lenovo", 1002, "PC");
INSERT INTO Products (maker, model, type)
	VALUES ("Sony", 1003, "PC");
INSERT INTO Products (maker, model, type)
	VALUES ("HP", 1004, "Laptop");
INSERT INTO Products (maker, model, type)
	VALUES ("IBM", 1005, "Laptop");
INSERT INTO Products (maker, model, type)
	VALUES ("Lenovo", 1006, "Laptop");
INSERT INTO Products (maker, model, type)
	VALUES ("Sony", 1007, "Laptop");
INSERT INTO Products (maker, model, type)
	VALUES ("Dell", 1008, "Laptop");
INSERT INTO Products (maker, model, type)
	VALUES ("Brother", 1009, "Printer");
INSERT INTO Products (maker, model, type)
	VALUES ("Konica Minolta", 1010, "Printer");
INSERT INTO Products (maker, model, type)
	VALUES ("HP", 1011, "Printer");


# Load some sample PCs
INSERT INTO PCs (model, speed, ram, hd, price)
	VALUES (1001, 3.0, 8.0, 320, 1500);
INSERT INTO PCs (model, speed, ram, hd, price)
	VALUES (1002, 2.8, 8.0, 500, 1690);
INSERT INTO PCs (model, speed, ram, hd, price)
	VALUES (1003, 2.4, 16.0, 500, 1999);

# Load some sample Laptops
INSERT INTO Laptops (model, speed, ram, hd, screen, price)
	VALUES (1004, 1.8, 4.0, 120, 13, 1100);
INSERT INTO Laptops (model, speed, ram, hd, screen, price)
	VALUES (1005, 1.7, 4.0, 120, 11, 1000);
INSERT INTO Laptops (model, speed, ram, hd, screen, price)
	VALUES (1006, 2.0, 8.0, 250, 15, 1900);
INSERT INTO Laptops (model, speed, ram, hd, screen, price)
	VALUES (1007, 2.4, 8.0, 250, 15, 2400);
INSERT INTO Laptops (model, speed, ram, hd, screen, price)
	VALUES (1008, 2.8, 16.0, 500, 17, 3200);

# Load some sample Printers
INSERT INTO Printers (model, color, printertype, price)
	VALUES (1009, FALSE, "Laser", 99);
INSERT INTO Printers (model, color, printertype, price)
	VALUES (1010, FALSE, "Laser", 125);
INSERT INTO Printers (model, color, printertype, price)
	VALUES (1011, TRUE, "Ink-Jet", 450);

# Sample Queries
SELECT * FROM Products;
SELECT * FROM PCs;
SELECT * FROM Laptops;
SELECT * FROM Printers;
*/
