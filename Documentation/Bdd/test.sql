CREATE SCHEMA IF NOT EXISTS `scubarecords` DEFAULT CHARACTER SET utf8 ;
USE `scubarecords` ;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Countries` (
  `idCountries` INT NOT NULL,
  `Name` VARCHAR(45) NULL,
  PRIMARY KEY (`idCountries`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Location` (
  `idLocation` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Country` INT NOT NULL,
  `GPS` VARCHAR(45) NULL,
  PRIMARY KEY (`idLocation`),
  INDEX `country_idx` (`Country` ASC),
  CONSTRAINT `fk_Location_Country1`
    FOREIGN KEY (`Country`)
    REFERENCES `scubarecords`.`Countries` (`idCountries`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`User` (
  `idUser` INT NOT NULL,
  `FirstName` VARCHAR(45) NULL,
  `LastName` VARCHAR(45) NULL,
  `Email` VARCHAR(45) NULL,
  `Password` VARCHAR(45) NULL,
  `location` INT NULL,
  PRIMARY KEY (`idUser`),
  INDEX `location_idx` (`location` ASC),
  CONSTRAINT `fk_User_Location1`
    FOREIGN KEY (`location`)
    REFERENCES `scubarecords`.`Location` (`idLocation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Club` (
  `idClub` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Description` LONGTEXT NULL,
  `Location` INT NOT NULL,
  `CreatedBy` INT NOT NULL,
  `Master` INT NOT NULL,
  `StartDateTime` DATETIME NOT NULL,
  `EndDateTime` DATETIME NULL,
  PRIMARY KEY (`idClub`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC),
  INDEX `user_idx` (`Master` ASC),
  INDEX `location_idx` (`Location` ASC),
  INDEX `fk_Club_User2_idx` (`CreatedBy` ASC),
  CONSTRAINT `fk_Club_User1`
    FOREIGN KEY (`Master`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_Location1`
    FOREIGN KEY (`Location`)
    REFERENCES `scubarecords`.`Location` (`idLocation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_User2`
    FOREIGN KEY (`CreatedBy`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Language` (
  `idLanguage` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idLanguage`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`ClubLanguage` (
  `Club` INT NOT NULL,
  `Language` INT NOT NULL,
  PRIMARY KEY (`Club`, `Language`),
  INDEX `language_idx` (`Language` ASC),
  CONSTRAINT `fk_ClubLanguage_Club1`
    FOREIGN KEY (`Club`)
    REFERENCES `scubarecords`.`Club` (`idClub`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ClubLanguage_Language1`
    FOREIGN KEY (`Language`)
    REFERENCES `scubarecords`.`Language` (`idLanguage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`DiveSite` (
  `idDiveSite` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Description` LONGTEXT NULL,
  `Location` INT NOT NULL,
  `StartDateTime` DATETIME NULL,
  `EndDateTime` DATETIME NULL,
  `difficulty` VARCHAR(45) NULL,
  `depthMin` INT NULL,
  `depthMax` INT NULL,
  `compas` TINYINT NULL,
  PRIMARY KEY (`idDiveSite`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC),
  INDEX `location_idx` (`Location` ASC),
  CONSTRAINT `fk_DiveSite_Location1`
    FOREIGN KEY (`Location`)
    REFERENCES `scubarecords`.`Location` (`idLocation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`DiveSiteLanguage` (
  `diveSite` INT NOT NULL,
  `Language` INT NOT NULL,
  PRIMARY KEY (`diveSite`, `Language`),
  INDEX `language_idx` (`Language` ASC),
  CONSTRAINT `fk_Dsl_DiveSite1`
    FOREIGN KEY (`diveSite`)
    REFERENCES `scubarecords`.`DiveSite` (`idDiveSite`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dsl_Language1`
    FOREIGN KEY (`Language`)
    REFERENCES `scubarecords`.`Language` (`idLanguage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Member` (
  `user` INT NOT NULL,
  `club` INT NOT NULL,
  INDEX `user_idx` (`user` ASC),
  INDEX `club_idx` (`club` ASC),
  PRIMARY KEY (`user`, `club`),
  CONSTRAINT `fk_Member_User1`
    FOREIGN KEY (`user`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Member_Club1`
    FOREIGN KEY (`club`)
    REFERENCES `scubarecords`.`Club` (`idClub`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`BookmarksUserDivesite` (
  `User` INT NOT NULL,
  `DiveSite` INT NOT NULL,
  PRIMARY KEY (`User`, `DiveSite`),
  INDEX `diveSite_idx` (`DiveSite` ASC),
  CONSTRAINT `fk_Bud_User1`
    FOREIGN KEY (`User`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bud_DiveSite1`
    FOREIGN KEY (`DiveSite`)
    REFERENCES `scubarecords`.`DiveSite` (`idDiveSite`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Dive` (
  `idDive` INT NOT NULL,
  `diveSite` INT NULL,
  `boat` VARCHAR(45) NULL,
  `weather` VARCHAR(45) NULL,
  `weight` VARCHAR(45) NULL COMMENT 'weight that the diver has to add to be able to dive.',
  `description` LONGTEXT NULL,
  `location` INT NULL,
  `pressionInit` DOUBLE NULL COMMENT 'pression inside the tank after filling it up.',
  `diver` INT NOT NULL,
  `public` TINYINT NOT NULL,
  PRIMARY KEY (`idDive`),
  INDEX `location_idx` (`location` ASC),
  INDEX `diver_idx` (`diver` ASC),
  INDEX `divesite_idx` (`diveSite` ASC),
  CONSTRAINT `fk_Dive_Location1`
    FOREIGN KEY (`location`)
    REFERENCES `scubarecords`.`Location` (`idLocation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dive_User1`
    FOREIGN KEY (`diver`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dive_DiveSite1`
    FOREIGN KEY (`diveSite`)
    REFERENCES `scubarecords`.`DiveSite` (`idDiveSite`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Certification` (
  `idCertification` INT NOT NULL,
  `user` INT NULL,
  `name` VARCHAR(45) NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`idCertification`),
  INDEX `user_idx` (`user` ASC),
  CONSTRAINT `fk_Certification_User1`
    FOREIGN KEY (`user`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Event` (
  `idEvent` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `startDate` DATETIME NOT NULL,
  `endDate` DATETIME NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  `location` VARCHAR(45) NOT NULL,
  `privacity` TINYINT NOT NULL,
  `club` INT NOT NULL,
  PRIMARY KEY (`idEvent`),
  INDEX `club_idx` (`club` ASC),
  CONSTRAINT `fk_Event_Club1`
    FOREIGN KEY (`club`)
    REFERENCES `scubarecords`.`Club` (`idClub`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Attend` (
  `Attendees` INT NOT NULL,
  `event` INT NOT NULL,
  PRIMARY KEY (`Attendees`, `event`),
  INDEX `fk_Attend_Event1_idx` (`event` ASC),
  CONSTRAINT `fk_Attend_User1`
    FOREIGN KEY (`Attendees`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Attend_Event1`
    FOREIGN KEY (`event`)
    REFERENCES `scubarecords`.`Event` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Following` (
  `follower` INT NOT NULL,
  `followed` INT NOT NULL,
  PRIMARY KEY (`follower`, `followed`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Tag` (
  `idTag` INT NOT NULL AUTO_INCREMENT,
  `nmTag` VARCHAR(45) NOT NULL,
  `typeTag` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idTag`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Dive_Tag` (
  `idDive` INT NOT NULL,
  `idTag` INT NOT NULL,
  `txtValue` VARCHAR(45) NOT NULL,
  INDEX `fk_Dive_Tag_Dive1_idx` (`idDive` ASC),
  INDEX `fk_Dive_Tag_Tag1_idx` (`idTag` ASC),
  PRIMARY KEY (`idDive`, `idTag`),
  CONSTRAINT `fk_Dive_Tag_Dive1`
    FOREIGN KEY (`idDive`)
    REFERENCES `scubarecords`.`Dive` (`idDive`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dive_Tag_Tag1`
    FOREIGN KEY (`idTag`)
    REFERENCES `scubarecords`.`Tag` (`idTag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`Media` (
  `idMedia` INT NOT NULL AUTO_INCREMENT,
  `dttmMedia` DATETIME NOT NULL,
  `pathMedia` VARCHAR(100) NOT NULL,
  `Dive_idDive` INT NOT NULL,
  PRIMARY KEY (`idMedia`),
  INDEX `fk_Picture_Dive1_idx` (`Dive_idDive` ASC),
  CONSTRAINT `fk_Media_Dive1`
    FOREIGN KEY (`Dive_idDive`)
    REFERENCES `scubarecords`.`Dive` (`idDive`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `scubarecords`.`DiveBuddy` (
  `Dive_idDive` INT NOT NULL,
  `User_idUser` INT NOT NULL,
  PRIMARY KEY (`Dive_idDive`, `User_idUser`),
  INDEX `fk_diveBuddy_User1_idx` (`User_idUser` ASC),
  CONSTRAINT `fk_diveBuddy_Dive1`
    FOREIGN KEY (`Dive_idDive`)
    REFERENCES `scubarecords`.`Dive` (`idDive`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_diveBuddy_User1`
    FOREIGN KEY (`User_idUser`)
    REFERENCES `scubarecords`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


INSERT INTO `countries` (`idCountries`, `Name`) VALUES
(2, 'France'),
(1, 'Suisse');

INSERT INTO `location` (`idLocation`, `Name`, `Country`, `GPS`) VALUES
(1, 'Herrmance', 1, '123456'),
(2, 'Paris', 2, '34563456');

INSERT INTO `user` (`idUser`, `FirstName`, `LastName`, `Email`, `Password`, `location`) VALUES
(1, 'tom', 'ryser', 'tom.rsr@eduge.ch', 'Super2020', 1),
(2, 'Max', 'Boublil', 'Boubli.max@gmail.com', 'Super2020', 2);

INSERT INTO `tag` (`idTag`, `nmTag`, `typeTag`) VALUES
(1, 'computer_name', 'string'),
(2, 'computer_model', 'string'),
(3, 'mix_o2', 'double'),
(4, 'mix_n2', 'double'),
(5, 'mix_he', 'double'),
(6, 'mix_ar', 'double'),
(7, 'mix_h2', 'double'),
(8, 'datetime_start', 'datetime'),
(9, 'tank_volume', 'double'),
(10, 'tank_press_start', 'double'),
(11, 'consumption', 'double'),
(12, 'lead_quantity', 'int'),
(13, 'dive_duration', 'int'),
(14, 'datetime_end', 'datetime');

INSERT INTO `language` (`idLanguage`, `Name`) VALUES
(1, 'Français'),
(2, 'Anglais'),
(3, 'Portugese');

INSERT INTO `certification` (`idCertification`, `user`, `name`, `date`) VALUES
(1, 1, 'CMAS *', '2019-08-14 13:23:08');

INSERT INTO `club` (`idClub`, `Name`, `Description`, `Location`, `CreatedBy`, `Master`, `StartDateTime`, `EndDateTime`) VALUES
(1, 'CSO', 'Ce ci est la description du Club de plongée subaquatic d\'Onex.', 1, 1, 1, '2020-04-08 13:17:21', NULL);

INSERT INTO `divesite` (`idDiveSite`, `Name`, `Description`, `Location`, `StartDateTime`, `EndDateTime`, `difficulty`, `depthMin`, `depthMax`, `compas`) VALUES
(1, 'Herrmance plage', 'Herrmance est une plage à proximité de la france sur le lac de Genève', 1, '2020-04-09 13:24:01', NULL, 'Facile', 5, 50, 1);

INSERT INTO `dive` (`idDive`, `diveSite`, `boat`, `weather`, `weight`, `description`, `location`, `pressionInit`, `diver`, `public`) VALUES
(1, 1, 'Magnificent', 'Ensoleillé', '10', 'Première plongée disponible sur le site', 1, 250, 1, 1);

INSERT INTO `dive_tag` (`idDive`, `idTag`, `txtValue`) VALUES
(1, 1, 'Shearwater Predator'),
(1, 2, 'Shearwater Predator'),
(1, 3, '0.210'),
(1, 4, '0.790'),
(1, 5, '0.000'),
(1, 6, '0.000'),
(1, 7, '0.000'),
(1, 8, '2006-04-28T15:49'),
(1, 9, '0.015'),
(1, 10, '20000000.0'),
(1, 11, '0.0002'),
(1, 12, '0'),
(1, 13, '4900'),
(1, 14, '');

INSERT INTO `event` (`idEvent`, `name`, `startDate`, `endDate`, `description`, `location`, `privacity`, `club`) VALUES
(1, 'Bienvenue pour les est de l\'api', '2020-04-23 13:36:30', '2020-04-23 16:36:30', 'Mon premier événement', 'Piscine de lancy, 1212 Grand-lancy', 1, 1);

INSERT INTO `following` (`follower`, `followed`) VALUES
(1, 2),
(2, 1);

INSERT INTO `media` (`idMedia`, `dttmMedia`, `pathMedia`, `Dive_idDive`) VALUES
(1, '2020-04-15 13:44:05', 'ceci/est/le/chemin/du/media', 1);

INSERT INTO `member` (`user`, `club`) VALUES
(1, 1),
(2, 1);

INSERT INTO `divebuddy` (`Dive_idDive`, `User_idUser`) VALUES
(1, 2);

INSERT INTO `divesitelanguage` (`diveSite`, `Language`) VALUES
(1, 1),
(1, 2);

INSERT INTO `bookmarksuserdivesite` (`User`, `DiveSite`) VALUES
(1, 1);

INSERT INTO `attend` (`Attendees`, `event`) VALUES ('1', '1'), ('2', '1');

INSERT INTO `clublanguage` (`Club`, `Language`) VALUES ('1', '1');

COMMIT;
