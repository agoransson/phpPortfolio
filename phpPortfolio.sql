CREATE DATABASE IF NOT EXISTS phpportfolio;

USE phpportfolio;

DROP TABLE IF EXISTS `cv_main`;
CREATE TABLE `cv_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(3) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `street` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `ambitions` longtext,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_education`;
CREATE TABLE `cv_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school` varchar(45) DEFAULT NULL,
  `start` varchar(45) DEFAULT NULL,
  `end` varchar(45) DEFAULT NULL,
  `degree` varchar(90) DEFAULT NULL,
  `thesisname` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_experience`;
CREATE TABLE `cv_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `institution` varchar(45) DEFAULT NULL,
  `organization` varchar(45) DEFAULT NULL,
  `start` varchar(45) DEFAULT NULL,
  `stop` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_other`;
CREATE TABLE `cv_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `employer` varchar(45) DEFAULT NULL,
  `year` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_skills`;
CREATE TABLE `cv_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_works`;
CREATE TABLE `cv_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher` varchar(128) DEFAULT NULL,
  `institution` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cv_projects`;
CREATE TABLE `cv_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT 'lipsum',
  `description` longtext NOT NULL,
  `target` varchar(45) DEFAULT NULL,
  `date` int(11) NOT NULL,
  `tags` varchar(45) DEFAULT NULL,
  `gallery` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;