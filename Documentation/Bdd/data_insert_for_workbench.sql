INSERT INTO `countries` (`idCountries`, `name`) VALUES
(2, 'France'),
(1, 'Suisse');

INSERT INTO `locations` (`idLocation`, `name`, `country`, `gps`) VALUES
(1, 'Herrmance', 1, '123456'),
(2, 'Paris', 2, '34563456');

INSERT INTO `users` (`idUser`, `firstName`, `lastName`, `email`, `password`, `location`) VALUES
(1, 'tom', 'ryser', 'tom.rsr@eduge.ch', 'Super2020', 1),
(2, 'Max', 'Boublil', 'Boubli.max@gmail.com', 'Super2020', 2);

INSERT INTO `tags` (`idTag`, `nmTag`, `typeTag`) VALUES
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

INSERT INTO `languages` (`idLanguage`, `name`) VALUES
(1, 'Français'),
(2, 'Anglais'),
(3, 'Portugese');

INSERT INTO `certifications` (`idCertification`, `user`, `name`, `date`) VALUES
(1, 1, 'CMAS *', '2019-08-14 13:23:08');

INSERT INTO `clubs` (`idClub`, `name`, `description`, `location`, `createdBy`, `master`, `startDateTime`, `endDateTime`) VALUES
(1, 'CSO', 'Ce ci est la description du Club de plongée subaquatic d\'Onex.', 1, 1, 1, '2020-04-08 13:17:21', NULL);

INSERT INTO `divesites` (`idDiveSite`, `name`, `description`, `location`, `startDateTime`, `endDateTime`, `difficulty`, `depthMin`, `depthMax`, `compas`) VALUES
(1, 'Herrmance plage', 'Herrmance est une plage à proximité de la france sur le lac de Genève', 1, '2020-04-09 13:24:01', NULL, 'Facile', 5, 50, 1);

INSERT INTO `dives` (`idDive`, `diveSite`, `boat`, `weather`, `weight`, `description`, `location`, `pressionInit`, `diver`, `public`) VALUES
(1, 1, 'Magnificent', 'Ensoleillé', '10', 'Première plongée disponible sur le site', 1, 250, 1, 1);

INSERT INTO `dive_tags` (`idDive`, `idTag`, `txtValue`) VALUES
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

INSERT INTO `events` (`idEvent`, `name`, `startDate`, `endDate`, `description`, `location`, `privacity`, `club`) VALUES
(1, 'Bienvenue pour les est de l\'api', '2020-04-23 13:36:30', '2020-04-23 16:36:30', 'Mon premier événement', 'Piscine de lancy, 1212 Grand-lancy', 1, 1);

INSERT INTO `following` (`idFollower`, `idFollowed`) VALUES
(1, 2),
(2, 1);

INSERT INTO `media` (`idMedia`, `dttmMedia`, `pathMedia`, `Dive_idDive`) VALUES
(1, '2020-04-15 13:44:05', 'ceci/est/le/chemin/du/media', 1);

INSERT INTO `members` (`idUser`, `idClub`) VALUES (1, 1), (2, 1);

INSERT INTO `divebuddies` (`dive_idDive`, `user_idUser`) VALUES (1, 2);

INSERT INTO `speak` (`idDiveSite`, `idLanguage`) VALUES (1, 1), (1, 2);

INSERT INTO `speak` (`idClub`, `idLanguage`) VALUES ('1', '1');

INSERT INTO `has_bookmark` (`idUser`, `idDiveSite`) VALUES (1, 1);

INSERT INTO `attend` (`idUser`, `idEvent`) VALUES ('1', '1'), ('2', '1');



COMMIT;
