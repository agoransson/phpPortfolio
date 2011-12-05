CREATE TABLE cv_main (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL,
  password varchar(64) NOT NULL,
  salt varchar(3) NOT NULL,
  name varchar(45) DEFAULT NULL,
  street varchar(45) DEFAULT NULL,
  city varchar(45) DEFAULT NULL,
  country varchar(45) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  ambitions longtext,
  email varchar(45) DEFAULT NULL,
  PRIMARY KEY (id)
);
CREATE TABLE cv_other (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL,
  password varchar(64) NOT NULL,
  salt varchar(3) NOT NULL,
  name varchar(45) DEFAULT NULL,
  street varchar(45) DEFAULT NULL,
  city varchar(45) DEFAULT NULL,
  country varchar(45) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  ambitions longtext,
  email varchar(45) DEFAULT NULL,
  PRIMARY KEY (id)
);
CREATE TABLE cv_three (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL,
  password varchar(64) NOT NULL,
  salt varchar(3) NOT NULL,
  name varchar(45) DEFAULT NULL,
  street varchar(45) DEFAULT NULL,
  city varchar(45) DEFAULT NULL,
  country varchar(45) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  ambitions longtext,
  email varchar(45) DEFAULT NULL,
  PRIMARY KEY (id)
);
