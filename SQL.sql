-- Can remove username and go for simply first/ last name. Can show it as first name, last initial
-- ie Justin O
CREATE TABLE Users (
	uid INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(64) NOT NULL,
    first_name VARCHAR (32) NOT NULL,
    last_name VARCHAR (32) NOT NULL,
	email VARCHAR (255) NOT NULL,
	password VARCHAR(24) NOT NULL,
	DOB DATE NOT NULL,
    city VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
	avatar VARCHAR (255) NOT NULL,
	PRIMARY KEY (uid)
) engine = "innoDB";

-- Author removed to put in seperate table. Will need to join them
CREATE TABLE Books(

    isbn_10 INT NOT NULL,
    isbn_13 BIGINT NOT NULL,    
    title VARCHAR(256) NOT NULL,
    subtitle VARCHAR(256) NOT NULL,
    publisher VARCHAR(64) NOT NULL,
    description VARCHAR(1024) NOT NULL,
    edition VARCHAR(16),
    PRIMARY KEY(isbn_13)

) engine = "innoDB";

-- image table to allow listings to have multiple photos
CREATE TABLE Images(
    iid INT NOT NULL AUTO_INCREMENT,
    image VARCHAR(256) NOT NULL,
    lid INT NOT NULL,
    PRIMARY KEY(iid),
    FOREIGN KEY(lid) REFERENCES Listings(lid)
);

CREATE TABLE Authors(
    aid INT NOT NULL AUTO_INCREMENT,
    isbn_13 BIGINT NOT NULL,
    first_name VARCHAR (32) NOT NULL,
    last_name VARCHAR (32) NOT NULL,
    PRIMARY KEY(aid),
    FOREIGN KEY(isbn_13) REFERENCES Books(isbn_13)

) engine = "innoDB";

CREATE TABLE Listings(

	lid INT NOT NULL AUTO_INCREMENT,
    isbn_10 INT NOT NULL,
    isbn_13 BIGINT NOT NULL,
    uid INT NOT NULL,   
    book_condition VARCHAR(20) NOT NULL,
    price DECIMAL(6,2),
	list_date DATE NOT NULL,
    active BOOLEAN NOT NULL,
    PRIMARY KEY(lid)

) engine = "innoDB";

CREATE TABLE Chats(

    cid INT NOT NULL AUTO_INCREMENT,
    lid INT NOT NULL,
    uid_buyer INT NOT NULL,
	chat_open DATETIME NOT NULL,
    active BOOLEAN NOT NULL,
	last_message DATETIME NOT NULL,
    PRIMARY KEY(cid),
    FOREIGN KEY(lid) REFERENCES Listings(lid),
    FOREIGN KEY(uid_buyer) REFERENCES Users(uid)

) engine = "innoDB";

CREATE TABLE Messages(

    mid INT NOT NULL AUTO_INCREMENT,
    uid_sender INT NOT NULL,
    cid INT NOT NULL,
    message VARCHAR (2056),
	time_sent DATETIME NOT NULL,
    PRIMARY KEY(mid),
    FOREIGN KEY(cid) REFERENCES Chats(cid),
    FOREIGN KEY(uid_sender) REFERENCES Users(uid)

) engine = "innoDB";