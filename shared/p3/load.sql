DROP TABLE IF EXISTS Affiliation;
DROP TABLE IF EXISTS Awarded;
DROP TABLE IF EXISTS Laureate;

CREATE TABLE Laureate(
	id INT PRIMARY KEY,
	givenName VARCHAR(30),
	familyName VARCHAR(40),
	gender VARCHAR(10),
	orgName VARCHAR(75),
    	dob DATE,
    	city VARCHAR(90),
    	country VARCHAR(60)
);
CREATE TABLE Awarded(
	id INT NOT NULL,
    	awardYear INT NOT NULL,
    	category VARCHAR(25) NOT NULL,
    	sortOrder INT,
    	portion VARCHAR(5),
	dateAwarded DATE,
    	prizeStatus VARCHAR(10),
    	motivation VARCHAR(250),
	prizeAmount INT,
    	FOREIGN KEY(id) REFERENCES Laureate(id),
    	PRIMARY KEY(id, awardYear, category)
);
CREATE TABLE Affiliation(
	id INT NOT NULL,
    	awardYear INT NOT NULL,
    	category VARCHAR(25) NOT NULL,
    	affiliationName VARCHAR(150) NOT NULL,
    	city VARCHAR(90),
    	country VARCHAR(60),
    	FOREIGN KEY(id, awardYear, category) REFERENCES Awarded(id, awardYear, category),
    	PRIMARY KEY(id, awardYear, category, affiliationName)
);

LOAD DATA LOCAL INFILE './Laureates.del' INTO TABLE Laureate 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Awarded.del' INTO TABLE Awarded 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Affiliations.del' INTO TABLE Affiliation 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
