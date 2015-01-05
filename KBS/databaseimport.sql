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
  `wachtwoord` VARCHAR(255) NOT NULL ,
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
-- Table `Textbug`.`admindata`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `Textbug`.`admindata` (
    `adminID` INT NOT NULL,
    `adminText` TEXT,
    PRIMARY KEY (`adminID`),
    INDEX `fk_admindata_adminID1_idx` (`adminID` ASC) ,
    CONSTRAINT `fk_admindata_AdminID1`
      FOREIGN KEY (`adminID` )
      REFERENCES `Textbug`.`klant` (`klantID` )
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
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
  CONSTRAINT `fk_notitie_Klant2`
    FOREIGN KEY (`plaatser` )
    REFERENCES `Textbug`.`klant` (`klantID` )
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
  `plaatser` INT NOT NULL,
  `titel` VARCHAR(100) NULL,
  `inhoud` TEXT NOT NULL ,
  `datum` DATETIME NULL ,
  `pagina` TINYINT(4) NOT NULL ,
  `datumzichtbaar` TINYINT(1) DEFAULT 1 ,
  `plaatserzichtbaar` TINYINT(1) DEFAULT 1 ,
  PRIMARY KEY (`berichtID`) ,
  INDEX `fk_Bericht_Pagina1` (`pagina` ASC) ,
  CONSTRAINT `fk_Bericht_Pagina1`
    FOREIGN KEY (`pagina` )
    REFERENCES `Textbug`.`pagina` (`paginaID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bericht_Klant1`
    FOREIGN KEY (`plaatser` )
    REFERENCES `Textbug`.`klant` (`klantID` )
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

INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `admin`)
VALUES("$2y$10$MlgXr3mvapN2LKcjq9Sv2utiDBuvCrN2CFfMgbZcD5jKBHTqRdTQm", "admin", "", "admin", 1);
INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `admin`)
VALUES("$2y$10$SKnEJ/SUc9Hn7t.tk9v4E.1incx7Bks9pUAovNH8276sh4/YYiT3O", "Test", "Admin", "test@admin.com", 1);
INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `postcode`, `huisnummer`, `telefoon`, `mobiel`, `woonplaats`, `adres`, `admin`)
VALUES("$2y$10$2vbysuV3v14Httd9gaq.G.Z.1yBXTgfTWVrPOScC/YJYQxs1VnL5a", "Test", "User", "test@user.com", "1234AB", 1, "1234-567890", "0612345678", "Amsterdam", "Rondweg 1", 0);
INSERT INTO `Textbug`.`klant` (`wachtwoord`, `voornaam`, `achternaam`, `emailadres`, `postcode`, `huisnummer`, `telefoon`, `mobiel`, `woonplaats`, `adres`, `admin`)
VALUES("$2y$10$NSatPCiosXkvMPoOdAfx1.7rrV21/pJHNKAZeftLKddWT5B0k6uMS", "Test", "User2", "test@user2.com", "1234AB", 1, "1234-567890", "0612345678", "Amsterdam", "Rondweg 1", 0);


INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (1, 1,'Test1','2014-11-23 12:30:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (2, 1,'Test2','2014-11-23 13:00:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`titel`,`inhoud`,`datum`,`pagina`) VALUES (3, 1, 'Contactinformatie','%3Cp%20style%3D%22text-align%3A%20center%3B%22%3EDit%20is%20ons%20contactformulier%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20center%3B%22%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%3Ctable%20style%3D%22height%3A%20206px%3B%20margin-left%3A%20auto%3B%20margin-right%3A%20auto%3B%22%20width%3D%22245%22%3E%0D%0A%3Ctbody%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3EAdres%3C%2Ftd%3E%0D%0A%3Ctd%3EStraatje%201%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3E%26nbsp%3Bfdgdfgdfgdfgdfghgjghjghj%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3Efghhfgfghfhfghhhfdgdfgdfgfdg%20dfg%20df%20gdf%20gdfg%20df%20gdfg%20dfgdfgfg%20fgfdgdfgdfgdf%20df%20dgf%20gg%20df%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3Efdg%20dfg%20dfg%20dfg%20dfg%20dfg%20dfg%20dgfg%20dfg%20dtyert%26nbsp%3B%20utr%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20center%3B%22%3E%3Cimg%20style%3D%22float%3A%20left%3B%22%20src%3D%22filemanager%2Fdata%2Fuploads%2Fphp-cheat-sheet-v2.png%22%20alt%3D%22php-cheat-sheet-v2%22%20%2F%3E%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3Ey%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3Etry%20rty%26nbsp%3B%20rtyrtytry%20ryty%20rty%20rty%20rty%20rty%20rty%20rty%20rty%20rt%20y%3C%2Fp%3E%0D%0A%3Cp%20style%3D%22text-align%3A%20left%3B%22%3Etyry%20rty%20rtyrty%20rdy%20rt%26nbsp%3B%20b%20n%20nghchjghjhgjgh%3C%2Fp%3E','2014-11-23 00:00:00',2);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (4, 1,'%3Cp%3EDit%20is%20info%3C%2Fp%3E','2014-11-23 00:00:00',3);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (5, 1,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/FeHf0fx.jpg" alt="onzin" /></p>','2014-11-25 13:30:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (6, 1,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/kfQy1OT.jpg" alt="onzin" /></p>','2014-11-25 13:40:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (7, 1,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/bnqPpl5.jpg" alt="onzin" /></p>','2014-11-25 13:50:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`inhoud`,`datum`,`pagina`) VALUES (8, 1,'<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://i.imgur.com/aLZzedG.jpg" alt="onzin" /></p>','2014-11-25 13:55:00',1);
INSERT INTO `bericht` (`berichtID`,`plaatser`,`titel`, `inhoud`,`datum`,`pagina`) VALUES (9, 1,'HI%20THERE%21%21%21%21','%3Cp%3E%3Cimg%20style%3D%22display%3A%20block%3B%20margin-left%3A%20auto%3B%20margin-right%3A%20auto%3B%22%20src%3D%22http%3A%2F%2Fi.imgur.com%2FxXwXOf2.jpg%22%20alt%3D%22Guyz%22%20width%3D%22631%22%20height%3D%22631%22%20%2F%3E%3C%2Fp%3E','2014-12-12 17:38:44',1);
