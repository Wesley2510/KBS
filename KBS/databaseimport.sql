SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `Textbug` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Textbug` ;

DROP TABLE IF EXISTS `Textbug`.`klant` ;
DROP TABLE IF EXISTS `Textbug`.`resetcode` ;
DROP TABLE IF EXISTS `Textbug`.`admin` ;
DROP TABLE IF EXISTS `Textbug`.`factuur` ;
DROP TABLE IF EXISTS `Textbug`.`notitie` ;
DROP TABLE IF EXISTS `Textbug`.`pagina` ;
DROP TABLE IF EXISTS `Textbug`.`bericht` ;
-- -----------------------------------------------------
-- Table `Textbug`.`klant`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`klant` (
  `klantID` INT NOT NULL AUTO_INCREMENT,
  `wachtwoord` VARCHAR(45) NOT NULL ,
  `voornaam` VARCHAR(45) NOT NULL ,
  `achternaam` VARCHAR(45) NOT NULL ,
  `emailadres` VARCHAR(45) NOT NULL UNIQUE,
  `postcode` VARCHAR(7) NULL ,
  `huisnummer` INT NULL ,
  `telefoon` VARCHAR(11) NULL ,
  `mobiel` VARCHAR(11) NULL ,
  `woonplaats` VARCHAR(45) NULL ,
  `adres` VARCHAR(45) NULL ,
  `admin` TINYINT(1) DEFAULT 0,
  `actief` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`klantID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`resetcode`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Textbug`.`resetcode` (
  `resetID` INT NOT NULL AUTO_INCREMENT,
  `user` INT NOT NULL ,
  `datumreset` DATETIME NULL ,
  `code` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`resetID`),
  INDEX `fk_reset_User1_idx` (`user` ASC) ,
  CONSTRAINT `fk_Reset_User1`
    FOREIGN KEY (`user` )
    REFERENCES `Textbug`.`klant` (`klantID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Textbug`.`notitie`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`notitie` (
  `klant` INT NOT NULL ,
  `plaatser` INT NOT NULL ,
  `notitie` TEXT NOT NULL ,
  `datum` DATETIME NOT NULL ,
  PRIMARY KEY (`klant`, `plaatser`) ,
  INDEX `fk_notitie_Admin1_idx` (`plaatser` ASC) ,
  CONSTRAINT `fk_notitie_Klant1`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`klant` (`klantID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notitie_Admin1`
    FOREIGN KEY (`plaatser` )
    REFERENCES `Textbug`.`admin` (`adminID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`factuur`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`factuur` (
  `factuurID` INT NOT NULL AUTO_INCREMENT,
  `klant` INT NOT NULL ,
  `service` VARCHAR(128) NOT NULL ,
  `prijs` FLOAT NOT NULL ,
  `betaald` TINYINT(1) NULL ,
  `papierenfactuur` TINYINT(1) NULL ,
  PRIMARY KEY (`factuurID`, `klant`) ,
  INDEX `fk_Factuur_Klant1_idx` (`klant` ASC) ,
  CONSTRAINT `fk_Factuur_Klant1`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`klant` (`klantID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`pagina`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`pagina` (
  `paginaID` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `naam` VARCHAR(45) NOT NULL ,
  `positie` TINYINT(4) NOT NULL ,
  PRIMARY KEY (`paginaID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`bericht`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`bericht` (
  `berichtID` INT NOT NULL AUTO_INCREMENT,
  `inhoud` TEXT NOT NULL ,
  `datum` DATETIME NULL ,
  `pagina` TINYINT(4) NOT NULL ,
  PRIMARY KEY (`berichtID`) ,
  INDEX `fk_Bericht_Pagina1` (`pagina` ASC) ,
  CONSTRAINT `fk_Bericht_Pagina1`
    FOREIGN KEY (`pagina` )
    REFERENCES `Textbug`.`pagina` (`paginaID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;





-- Inserts

-- Paginas
INSERT INTO `Textbug`.`pagina` (`naam`, `positie`) VALUES ('Nieuws', 1);
INSERT INTO `Textbug`.`pagina` (`naam`, `positie`) VALUES ('Contact', 2);
INSERT INTO `Textbug`.`pagina` (`naam`, `positie`) VALUES ('Info', 3);

-- Berichten
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (1,'Test1','2014-11-23 12:30:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (2,'Test2','2014-11-23 13:00:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (3,'Dit is ons contactformulier','2014-11-23 00:00:00',2);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (4,'Dit is info','2014-11-23 00:00:00',3);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (5,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/FeHf0fx.jpg" alt="onzin" /></p>','2014-11-25 13:30:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (6,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/kfQy1OT.jpg" alt="onzin" /></p>','2014-11-25 13:40:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (7,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/bnqPpl5.jpg" alt="onzin" /></p>','2014-11-25 13:50:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (8,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/aLZzedG.jpg" alt="onzin" /></p>','2014-11-25 13:55:00',1);
INSERT INTO `bericht` (`berichtID`,`inhoud`,`datum`,`pagina`) VALUES (9,'<h1 style="text-align: center;">HI THERE!</h1>
<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/xXwXOf2.jpg" alt="Guyz" width="631" height="631" /></p>','2014-12-12 17:38:44',1);


INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `admin`)
VALUES("password", "admin", "", "admin", 1);
INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `postcode`, `huisnummer`, `telefoon`, `mobiel`, `woonplaats`, `adres`, `admin`)
VALUES("wachtwoord", "Test", "User", "test@user.com", "1234AB", 1, "1234-567890", "0612345678", "Amsterdam", "Rondweg 1", 1);