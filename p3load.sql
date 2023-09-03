DROP TABLE IF EXISTS Affiliation;
DROP TABLE IF EXISTS Awarded;
DROP TABLE IF EXISTS Laureate;
DROP TABLE IF EXISTS NobelPrize;
DROP TABLE IF EXISTS Institution;

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
CREATE TABLE NobelPrize(
	awardYear INT NOT NULL,
    category VARCHAR(25) NOT NULL,
    dateAwarded DATE,
    prizeAmount INT,
    PRIMARY KEY(awardYear, category)
);
CREATE TABLE Institution(
	affiliationName VARCHAR(150) NOT NULL,
    city VARCHAR(90) NOT NULL,
    country VARCHAR(60) NOT NULL,
    PRIMARY KEY(affiliationName, city, country)
);
CREATE TABLE Awarded(
	id INT NOT NULL,
    awardYear INT NOT NULL,
    category VARCHAR(25) NOT NULL,
    sortOrder INT,
    portion VARCHAR(5),
    prizeStatus VARCHAR(10),
    motivation VARCHAR(250),
    FOREIGN KEY(id) REFERENCES Laureate(id),
    FOREIGN KEY(awardYear, category) REFERENCES NobelPrize(awardYear, category),
    PRIMARY KEY(id, awardYear, category)
);
CREATE TABLE Affiliation(
	id INT NOT NULL,
    awardYear INT NOT NULL,
    category VARCHAR(25) NOT NULL,
    affiliationName VARCHAR(150) NOT NULL,
    city VARCHAR(90) NOT NULL,
    country VARCHAR(60) NOT NULL,
    FOREIGN KEY(id) REFERENCES Laureate(id),
    FOREIGN KEY(awardYear, category) REFERENCES NobelPrize(awardYear, category),
    FOREIGN KEY(affiliationName, city, country) REFERENCES Institution(affiliationName, city, country),
    PRIMARY KEY(id, awardYear, category, affiliationName)
);

LOAD DATA LOCAL INFILE './Laureates.del' INTO TABLE Laureate 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './NobelPrizes.del' INTO TABLE NobelPrize 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Institutions.del' INTO TABLE Institution 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Awarded.del' INTO TABLE Awarded 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './Affiliations.del' INTO TABLE Affiliation 
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';