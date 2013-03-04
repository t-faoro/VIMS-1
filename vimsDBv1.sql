SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `vims` ;
CREATE SCHEMA IF NOT EXISTS `vims` DEFAULT CHARACTER SET utf8 ;
USE `vims` ;

-- -----------------------------------------------------
-- Table `vims`.`Region`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Region` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Region` (
  `REG_ID` INT UNSIGNED NOT NULL ,
  `REG_Name` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`REG_ID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`User` ;

CREATE  TABLE IF NOT EXISTS `vims`.`User` (
  `USE_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `USE_Name` VARCHAR(25) NOT NULL ,
  `USE_Passwd` CHAR(32) NOT NULL ,
  `USE_Fname` VARCHAR(45) NOT NULL ,
  `USE_Lname` VARCHAR(45) NOT NULL ,
  `USE_Creator` INT UNSIGNED NOT NULL ,
  `USE_Date_Created` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`USE_ID`) ,
  UNIQUE INDEX `USE_ID_UNIQUE` (`USE_ID` ASC) ,
  UNIQUE INDEX `USE_Name_UNIQUE` (`USE_Name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1000;


-- -----------------------------------------------------
-- Table `vims`.`News`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`News` ;

CREATE  TABLE IF NOT EXISTS `vims`.`News` (
  `NEW_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `NEW_Date` DATETIME NOT NULL ,
  `NEW_Content` MEDIUMTEXT NOT NULL ,
  `NEW_Type` TINYINT NOT NULL DEFAULT 0 ,
  `NEW_Reason_for_Del` TEXT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`NEW_ID`) ,
  INDEX `fk_News_User_idx` (`User_USE_ID` ASC) ,
  CONSTRAINT `fk_News_User`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Auth_Level_Lookup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Auth_Level_Lookup` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Auth_Level_Lookup` (
  `AUT_Level` SMALLINT UNSIGNED NOT NULL ,
  `AUT_Def` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`AUT_Level`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Venue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Venue` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Venue` (
  `VEN_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `VEN_Name` VARCHAR(45) NOT NULL ,
  `VEN_Unit_Addr` VARCHAR(10) NULL ,
  `VEN_St_Addr` VARCHAR(45) NULL ,
  `VEN_City` VARCHAR(25) NULL ,
  `VEN_Pcode` CHAR(7) NULL ,
  `VEN_Phone` CHAR(12) NULL ,
  `VEN_Liason` VARCHAR(45) NULL ,
  `VEN_Status` TINYINT UNSIGNED NOT NULL DEFAULT 1 ,
  `VEN_Status_Chg` DATETIME NULL ,
  `VEN_Can_Make_Owner` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  `Region_REG_ID` INT UNSIGNED NOT NULL ,
  `VEN_Date_Created` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`VEN_ID`) ,
  INDEX `fk_Venue_Region1_idx` (`Region_REG_ID` ASC) ,
  CONSTRAINT `fk_Venue_Region1`
    FOREIGN KEY (`Region_REG_ID` )
    REFERENCES `vims`.`Region` (`REG_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`News_Region_Assc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`News_Region_Assc` ;

CREATE  TABLE IF NOT EXISTS `vims`.`News_Region_Assc` (
  `News_NEW_ID` INT UNSIGNED NOT NULL ,
  `Region_REG_ID` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`News_NEW_ID`, `Region_REG_ID`) ,
  INDEX `fk_News_Region_Assc_News1_idx` (`News_NEW_ID` ASC) ,
  INDEX `fk_News_Region_Assc_Region1_idx` (`Region_REG_ID` ASC) ,
  CONSTRAINT `fk_News_Region_Assc_News1`
    FOREIGN KEY (`News_NEW_ID` )
    REFERENCES `vims`.`News` (`NEW_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_News_Region_Assc_Region1`
    FOREIGN KEY (`Region_REG_ID` )
    REFERENCES `vims`.`Region` (`REG_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Venue_User_Assc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Venue_User_Assc` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Venue_User_Assc` (
  `Venue_VEN_ID` INT UNSIGNED NOT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  `VUA_Sys_Status` TINYINT UNSIGNED NOT NULL DEFAULT 1 ,
  `VUA_Last_Logon` DATETIME NULL ,
  `VUA_Status_Chg` DATETIME NULL ,
  `VUA_Status_Mod` INT UNSIGNED NULL ,
  `Auth_Level_Lookup_AUT_Level` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Venue_VEN_ID`, `User_USE_ID`) ,
  INDEX `fk_Venue_User_Assc_Venue1_idx` (`Venue_VEN_ID` ASC) ,
  INDEX `fk_Venue_User_Assc_User1_idx` (`User_USE_ID` ASC) ,
  INDEX `fk_Venue_User_Assc_Auth_Level_Lookup1_idx` (`Auth_Level_Lookup_AUT_Level` ASC) ,
  CONSTRAINT `fk_Venue_User_Assc_Venue1`
    FOREIGN KEY (`Venue_VEN_ID` )
    REFERENCES `vims`.`Venue` (`VEN_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Venue_User_Assc_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Venue_User_Assc_Auth_Level_Lookup1`
    FOREIGN KEY (`Auth_Level_Lookup_AUT_Level` )
    REFERENCES `vims`.`Auth_Level_Lookup` (`AUT_Level` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Var`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Var` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Var` (
  `VAR_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `VAR_Date` DATETIME NOT NULL ,
  `VAR_Attend` INT UNSIGNED NOT NULL ,
  `VAR_Sec_Chklst` TINYINT UNSIGNED NOT NULL DEFAULT 1 ,
  `VAR_Supervisor` VARCHAR(45) NOT NULL ,
  `VAR_Event` VARCHAR(45) NOT NULL ,
  `VAR_Reason_for_Del` TEXT NULL ,
  `Venue_VEN_ID` INT UNSIGNED NOT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`VAR_ID`) ,
  INDEX `fk_Var_Venue1_idx` (`Venue_VEN_ID` ASC) ,
  INDEX `fk_Var_User1_idx` (`User_USE_ID` ASC) ,
  CONSTRAINT `fk_Var_Venue1`
    FOREIGN KEY (`Venue_VEN_ID` )
    REFERENCES `vims`.`Venue` (`VEN_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Var_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Modification_Var`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Modification_Var` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Modification_Var` (
  `MOD_Timestamp` TIMESTAMP NOT NULL ,
  `Var_VAR_ID` INT UNSIGNED NOT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  `MOD_Action` VARCHAR(45) NULL ,
  PRIMARY KEY (`MOD_Timestamp`, `Var_VAR_ID`, `User_USE_ID`) ,
  INDEX `fk_Modification_Var_Var1_idx` (`Var_VAR_ID` ASC) ,
  INDEX `fk_Modification_Var_User1_idx` (`User_USE_ID` ASC) ,
  CONSTRAINT `fk_Modification_Var_Var1`
    FOREIGN KEY (`Var_VAR_ID` )
    REFERENCES `vims`.`Var` (`VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Modification_Var_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Incident_Level_Lookup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Incident_Level_Lookup` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Incident_Level_Lookup` (
  `ILL_Level` SMALLINT NOT NULL ,
  `ILL_Def` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`ILL_Level`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Incident_Entry`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Incident_Entry` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Incident_Entry` (
  `INE_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Var_VAR_ID` INT UNSIGNED NOT NULL ,
  `INE_Time` TIME NOT NULL ,
  `INE_Police` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  `INE_Content` MEDIUMTEXT NOT NULL ,
  `INE_Damages` MEDIUMTEXT NULL ,
  `INE_Reason_for_Del` TEXT NULL ,
  `Incident_Level_Lookup_ILL_Level` SMALLINT NOT NULL ,
  PRIMARY KEY (`INE_ID`, `Var_VAR_ID`) ,
  INDEX `fk_Incident_Entry_Incident_Level_Lookup1_idx` (`Incident_Level_Lookup_ILL_Level` ASC) ,
  INDEX `fk_Incident_Entry_Var1_idx` (`Var_VAR_ID` ASC) ,
  CONSTRAINT `fk_Incident_Entry_Incident_Level_Lookup1`
    FOREIGN KEY (`Incident_Level_Lookup_ILL_Level` )
    REFERENCES `vims`.`Incident_Level_Lookup` (`ILL_Level` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Incident_Entry_Var1`
    FOREIGN KEY (`Var_VAR_ID` )
    REFERENCES `vims`.`Var` (`VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Modification_Ine`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Modification_Ine` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Modification_Ine` (
  `MOD_Timestamp` TIMESTAMP NOT NULL ,
  `Incident_Entry_INE_ID` INT UNSIGNED NOT NULL ,
  `Incident_Entry_Var_VAR_ID` INT UNSIGNED NOT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  `MOD_Action` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`MOD_Timestamp`, `Incident_Entry_INE_ID`, `Incident_Entry_Var_VAR_ID`, `User_USE_ID`) ,
  INDEX `fk_Modification_Ine_Incident_Entry1_idx` (`Incident_Entry_INE_ID` ASC, `Incident_Entry_Var_VAR_ID` ASC) ,
  INDEX `fk_Modification_Ine_User1_idx` (`User_USE_ID` ASC) ,
  CONSTRAINT `fk_Modification_Ine_Incident_Entry1`
    FOREIGN KEY (`Incident_Entry_INE_ID` , `Incident_Entry_Var_VAR_ID` )
    REFERENCES `vims`.`Incident_Entry` (`INE_ID` , `Var_VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Modification_Ine_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Images` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Images` (
  `IMG_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `IMG_Filename` VARCHAR(45) NOT NULL ,
  `IMG_Desc` TEXT NULL ,
  `IMG_Archived` DATETIME NULL ,
  PRIMARY KEY (`IMG_ID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Ine_Images_Assc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Ine_Images_Assc` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Ine_Images_Assc` (
  `Incident_Entry_INE_ID` INT UNSIGNED NOT NULL ,
  `Incident_Entry_Var_VAR_ID` INT UNSIGNED NOT NULL ,
  `Images_IMG_ID` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`Incident_Entry_INE_ID`, `Incident_Entry_Var_VAR_ID`, `Images_IMG_ID`) ,
  INDEX `fk_Ine_Images_Assc_Images1_idx` (`Images_IMG_ID` ASC) ,
  CONSTRAINT `fk_Ine_Images_Assc_Incident_Entry1`
    FOREIGN KEY (`Incident_Entry_INE_ID` , `Incident_Entry_Var_VAR_ID` )
    REFERENCES `vims`.`Incident_Entry` (`INE_ID` , `Var_VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ine_Images_Assc_Images1`
    FOREIGN KEY (`Images_IMG_ID` )
    REFERENCES `vims`.`Images` (`IMG_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Ine_User_Assc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Ine_User_Assc` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Ine_User_Assc` (
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  `Incident_Entry_INE_ID` INT UNSIGNED NOT NULL ,
  `Incident_Entry_Var_VAR_ID` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`User_USE_ID`, `Incident_Entry_INE_ID`, `Incident_Entry_Var_VAR_ID`) ,
  INDEX `fk_Ine_User_Assc_Incident_Entry1_idx` (`Incident_Entry_INE_ID` ASC, `Incident_Entry_Var_VAR_ID` ASC) ,
  CONSTRAINT `fk_Ine_User_Assc_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ine_User_Assc_Incident_Entry1`
    FOREIGN KEY (`Incident_Entry_INE_ID` , `Incident_Entry_Var_VAR_ID` )
    REFERENCES `vims`.`Incident_Entry` (`INE_ID` , `Var_VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Involvement_Lookup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Involvement_Lookup` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Involvement_Lookup` (
  `INV_Level` SMALLINT UNSIGNED NOT NULL ,
  `INV_Def` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`INV_Level`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Person_of_Record`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Person_of_Record` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Person_of_Record` (
  `POR_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Incident_Entry_INE_ID` INT UNSIGNED NOT NULL ,
  `Incident_Entry_Var_VAR_ID` INT UNSIGNED NOT NULL ,
  `POR_Name` VARCHAR(45) NULL ,
  `POR_Phone` CHAR(10) NULL ,
  `POR_Licence` CHAR(10) NULL ,
  `POR_Notes` MEDIUMTEXT NOT NULL ,
  `POR_Reason_for_Del` TEXT NULL ,
  `Involvement_Lookup_INV_Level` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`POR_ID`, `Incident_Entry_INE_ID`, `Incident_Entry_Var_VAR_ID`) ,
  INDEX `fk_Person_of_Record_Involvement_Lookup1_idx` (`Involvement_Lookup_INV_Level` ASC) ,
  INDEX `fk_Person_of_Record_Incident_Entry1_idx` (`Incident_Entry_INE_ID` ASC, `Incident_Entry_Var_VAR_ID` ASC) ,
  CONSTRAINT `fk_Person_of_Record_Involvement_Lookup1`
    FOREIGN KEY (`Involvement_Lookup_INV_Level` )
    REFERENCES `vims`.`Involvement_Lookup` (`INV_Level` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Person_of_Record_Incident_Entry1`
    FOREIGN KEY (`Incident_Entry_INE_ID` , `Incident_Entry_Var_VAR_ID` )
    REFERENCES `vims`.`Incident_Entry` (`INE_ID` , `Var_VAR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `vims`.`Modification_Por`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vims`.`Modification_Por` ;

CREATE  TABLE IF NOT EXISTS `vims`.`Modification_Por` (
  `MOD_Timestamp` TIMESTAMP NOT NULL ,
  `User_USE_ID` INT UNSIGNED NOT NULL ,
  `Person_of_Record_POR_ID` INT UNSIGNED NOT NULL ,
  `MOD_Action` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`MOD_Timestamp`, `User_USE_ID`, `Person_of_Record_POR_ID`) ,
  INDEX `fk_Modification_Por_User1_idx` (`User_USE_ID` ASC) ,
  INDEX `fk_Modification_Por_Person_of_Record1_idx` (`Person_of_Record_POR_ID` ASC) ,
  CONSTRAINT `fk_Modification_Por_User1`
    FOREIGN KEY (`User_USE_ID` )
    REFERENCES `vims`.`User` (`USE_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Modification_Por_Person_of_Record1`
    FOREIGN KEY (`Person_of_Record_POR_ID` )
    REFERENCES `vims`.`Person_of_Record` (`POR_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
