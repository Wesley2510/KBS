SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `Textbug`.`Klant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Klant` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Klant` (
  `klant` INT NOT NULL ,
  `voornaam` VARCHAR(45) NOT NULL ,
  `achternaam` VARCHAR(45) NOT NULL ,
  `postcode` VARCHAR(7) NOT NULL ,
  `huisnummer` INT NOT NULL ,
  `telefoon` VARCHAR(11) NULL ,
  `mobiel` VARCHAR(11) NULL ,
  `woonplaats` VARCHAR(45) NOT NULL ,
  `adres` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`klant`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`User` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`User` (
  `username` VARCHAR(20) NOT NULL ,
  `klant` INT NOT NULL ,
  `wachtwoord` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`username`, `klant`) ,
  INDEX `fk_User_Klant_idx` (`klant` ASC) ,
  CONSTRAINT `fk_User_Klant`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`Klant` (`klant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`Admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Admin` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Admin` (
  `admin` VARCHAR(20) NOT NULL ,
  `voornaam` VARCHAR(45) NULL ,
  `achternaam` VARCHAR(45) NULL ,
  `emailadres` VARCHAR(45) NULL ,
  `wachtwoord` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`admin`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`notitie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`notitie` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`notitie` (
  `klant` INT NOT NULL ,
  `plaatser` VARCHAR(20) NOT NULL ,
  `notitie` TEXT NOT NULL ,
  `datum` DATE NULL ,
  PRIMARY KEY (`klant`, `plaatser`) ,
  INDEX `fk_notitie_Admin1_idx` (`plaatser` ASC) ,
  CONSTRAINT `fk_notitie_Klant1`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`Klant` (`klant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notitie_Admin1`
    FOREIGN KEY (`plaatser` )
    REFERENCES `Textbug`.`Admin` (`admin` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`Factuur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Factuur` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Factuur` (
  `factuur` INT NOT NULL ,
  `klant` INT NOT NULL ,
  `service` VARCHAR(128) NOT NULL ,
  `prijs` INT NOT NULL ,
  `betaald` TINYINT(1) NULL ,
  `papierenfactuur` VARCHAR(45) NULL ,
  PRIMARY KEY (`factuur`, `klant`) ,
  INDEX `fk_Factuur_Klant1_idx` (`klant` ASC) ,
  CONSTRAINT `fk_Factuur_Klant1`
    FOREIGN KEY (`klant` )
    REFERENCES `Textbug`.`Klant` (`klant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`Pagina`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Pagina` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Pagina` (
  `pagina` INT NOT NULL ,
  `naam` VARCHAR(45) NULL ,
  PRIMARY KEY (`pagina`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`Bericht`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Bericht` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Bericht` (
  `berichtid` INT NOT NULL ,
  `bericht` TEXT NOT NULL ,
  `datum` DATE NULL ,
  `pagina` INT NOT NULL ,
  PRIMARY KEY (`berichtid`) ,
  INDEX `fk_Bericht_Pagina1` (`pagina` ASC) ,
  CONSTRAINT `fk_Bericht_Pagina1`
    FOREIGN KEY (`pagina` )
    REFERENCES `Textbug`.`Pagina` (`pagina` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Textbug`.`Menuitem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Textbug`.`Menuitem` ;

CREATE  TABLE IF NOT EXISTS `Textbug`.`Menuitem` (
  `menuitem` INT NOT NULL ,
  `text` TEXT NULL ,
  `pagina` INT NOT NULL ,
  PRIMARY KEY (`menuitem`) ,
  INDEX `fk_Menuitem_Pagina1` (`pagina` ASC) ,
  CONSTRAINT `fk_Menuitem_Pagina1`
    FOREIGN KEY (`pagina` )
    REFERENCES `Textbug`.`Pagina` (`pagina` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
