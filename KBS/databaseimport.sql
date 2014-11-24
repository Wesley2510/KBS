SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `Textbug` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Textbug` ;

-- -----------------------------------------------------
-- Table `Textbug`.`klant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`klant` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`klant` (
  `klantID` INT NOT NULL AUTO_INCREMENT,
  `voornaam` VARCHAR(45) NOT NULL ,
  `achternaam` VARCHAR(45) NOT NULL ,
  `postcode` VARCHAR(7) NOT NULL ,
  `huisnummer` INT NOT NULL ,
  `telefoon` VARCHAR(11) NULL ,
  `mobiel` VARCHAR(11) NULL ,
  `woonplaats` VARCHAR(45) NOT NULL ,
  `adres` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`klantID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`user` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`user` (
  `username` VARCHAR(20) NOT NULL ,
  `klant` INT NULL ,
  `wachtwoord` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`username`) ,
  INDEX `fk_User_Klant_idx` (`klant` ASC) ,
  CONSTRAINT `fk_User_Klant`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`klant` (`klantID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`admin` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`admin` (
  `adminID` VARCHAR(20) NOT NULL,
  `voornaam` VARCHAR(45) NOT NULL ,
  `achternaam` VARCHAR(45) NOT NULL ,
  `emailadres` VARCHAR(45) NULL ,
  `wachtwoord` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`adminID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`notitie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`notitie` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`notitie` (
  `klant` INT NOT NULL ,
  `plaatser` VARCHAR(20) NOT NULL ,
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
DROP TABLE IF EXISTS `Textbug`.`factuur` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`factuur` (
  `factuurID` INT NOT NULL AUTO_INCREMENT,
  `klant` INT NOT NULL ,
  `service` VARCHAR(128) NOT NULL ,
  `prijs` INT NOT NULL ,
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
DROP TABLE IF EXISTS `Textbug`.`pagina` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`pagina` (
  `paginaID` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `naam` VARCHAR(45) NOT NULL ,
  `positie` TINYINT(4) NULL ,
  PRIMARY KEY (`paginaID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`bericht`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`bericht` ;

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
INSERT INTO `Textbug`.`bericht` (`inhoud`, `datum`, `pagina`) VALUES ('Test1', '2014-11-23', 1);
INSERT INTO `Textbug`.`bericht` (`inhoud`, `datum`, `pagina`) VALUES ('Test2', '2014-11-23', 1);
INSERT INTO `Textbug`.`bericht` (`inhoud`, `datum`, `pagina`) VALUES ('Dit is ons contactformulier', '2014-11-23', 2);
INSERT INTO `Textbug`.`bericht` (`inhoud`, `datum`, `pagina`) VALUES ('Dit is info', '2014-11-23', 3);





