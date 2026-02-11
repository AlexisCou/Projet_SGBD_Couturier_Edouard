SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 1;

-- Table tabl
DROP TABLE IF EXISTS `tabl`;
CREATE TABLE `tabl` (
  `numtab` int(11) NOT NULL AUTO_INCREMENT,
  `nbplace` int(2),
  PRIMARY KEY (`numtab`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `tabl` values(10,4);
insert into `tabl` values(11,6);
insert into `tabl` values(12,8);
insert into `tabl` values(13,4);
insert into `tabl` values(14,6);
insert into `tabl` values(15,4);
insert into `tabl` values(16,4);
insert into `tabl` values(17,6);
insert into `tabl` values(18,2);
insert into `tabl` values(19,4);

-- Table plat
DROP TABLE IF EXISTS `plat`;
CREATE TABLE `plat` (
  `numplat` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(40),
  `type` varchar(15),
  `prixunit` decimal(6,2) DEFAULT NULL,
  `qteservie` int(4),
  PRIMARY KEY (`numplat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertions (identiques)
insert into `plat` values(1,'assiette de crudités','Entrée',90,25);
insert into `plat` values(2,'tarte de saison','Dessert',90,25);
insert into `plat` values(3,'sorbet mirabelle','Dessert',90,35);
insert into `plat` values(4,'filet de boeuf','Viande',90,62);
insert into `plat` values(5,'salade verte','Entrée',90,15);
insert into `plat` values(6,'chevre chaud','Entrée',90,21);
insert into `plat` values(7,'pate lorrain','Entrée',90,25);
insert into `plat` values(8,'saumon fumé','Entrée',90,30);
insert into `plat` values(9,'entrecote printaniere','Viande',90,58);
insert into `plat` values(10,'gratin dauphinois','Plat',90,42);
insert into `plat` values(11,'brochet à l''oseille','Poisson',90,68);
insert into `plat` values(12,'gigot d''agneau','Viande',90,56);
insert into `plat` values(13,'crème caramel','Dessert',90,15);
insert into `plat` values(14,'munster au cumin','Fromage',90,18);
insert into `plat` values(15,'filet de sole au beurre','Poisson',90,70);
insert into `plat` values(16,'fois gras de lorraine','Entrée',90,61);

-- table serveur
DROP TABLE IF EXISTS `serveur`;
CREATE TABLE `serveur` (
  `id_serv` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(35) NOT NULL,
  PRIMARY KEY (`id_serv`),
  UNIQUE KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `serveur` values(1, 'Paul_2026', 'paul.janv.2026', 'Paul');
insert into `serveur` values(2, 'Albert_2026', 'albert.janv.2026', 'Albert');
insert into `serveur` values(3, 'Xavier_2026', 'xavier.janv.2026', 'Xavier');
insert into `serveur` values(4, 'Beatrice_2026', 'beatrice.janv.2026', 'Beatrice');
insert into `serveur` values(5, 'Lola_2026', 'lola.janv.2026', 'Lola');
insert into `serveur` values(6, 'Lola_1_2026', 'lola.fevr.2026', 'Lola1');
insert into `serveur` values(7, 'Paul_1_2026', 'paul.fevr.2026', 'Paul1');

-- Table reservation
DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation` (
  `numres` int(11) NOT NULL AUTO_INCREMENT,
  `numtab` int(11),
  `id_serv` int(11),
  `datres` DATETIME, 
  `nbpers` int(2),
  `datpaie` DATETIME,
  `modpaie` varchar(15),
  `montcom` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`numres`),
  CONSTRAINT `fk_res_tab` FOREIGN KEY (`numtab`) REFERENCES `tabl` (`numtab`),
  CONSTRAINT `fk_res_serv` FOREIGN KEY (`id_serv`) REFERENCES `serveur` (`id_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tuples de reservation
insert into `reservation` values(100,10,1, str_to_date('10/09/2021 19:00','%d/%m/%Y %H:%i'),2,str_to_date('10/09/2021 20:50','%d/%m/%Y %H:%i'),'Carte',null);
insert into `reservation` values(101,11,2, str_to_date('10/09/2021 20:00','%d/%m/%Y %H:%i'),4,str_to_date('10/09/2021 21:20','%d/%m/%Y %H:%i'),'Chéque',null);
insert into `reservation` values(102,17,3, str_to_date('10/09/2021 18:00','%d/%m/%Y %H:%i'),2,str_to_date('10/09/2021 20:55','%d/%m/%Y %H:%i'),'Carte',null);
insert into `reservation` values(103,12,4, str_to_date('10/09/2021 19:00','%d/%m/%Y %H:%i'),2,str_to_date('10/09/2021 21:10','%d/%m/%Y %H:%i'),'Espèces',null);
insert into `reservation` values(104,18,5, str_to_date('10/09/2021 19:00','%d/%m/%Y %H:%i'),1,str_to_date('10/09/2021 21:00','%d/%m/%Y %H:%i'),'Chéque',null);
insert into `reservation` values(105,10,6, str_to_date('10/09/2021 19:00','%d/%m/%Y %H:%i'),2,str_to_date('10/09/2021 20:45','%d/%m/%Y %H:%i'),'Carte',null);
insert into `reservation` values(106,14,7, str_to_date('11/10/2021 19:00','%d/%m/%Y %H:%i'),2,str_to_date('11/10/2021 22:45','%d/%m/%Y %H:%i'),'Carte',null);

-- Table commande
DROP TABLE IF EXISTS `commande`;
CREATE TABLE `commande` (
  `numres` int(11) NOT NULL,
  `numplat` int(11) NOT NULL,
  `quantite` int(2),
  PRIMARY KEY (`numres`, `numplat`),
  CONSTRAINT `fk_com_res` FOREIGN KEY (`numres`) REFERENCES `reservation` (`numres`),
  CONSTRAINT `fk_com_plat` FOREIGN KEY (`numplat`) REFERENCES `plat` (`numplat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertions (identiques)
insert into `commande` values(100,4,2);
insert into `commande` values(100,5,2);
insert into `commande` values(100,13,1);
insert into `commande` values(100,3,1);
insert into `commande` values(101,7,2);
insert into `commande` values(101,16,2);
insert into `commande` values(101,12,2);
insert into `commande` values(101,15,2);
insert into `commande` values(101,2,2);
insert into `commande` values(101,3,2);
insert into `commande` values(102,1,2);
insert into `commande` values(102,10,2);
insert into `commande` values(102,14,2);
insert into `commande` values(102,2,1);
insert into `commande` values(102,3,1);
insert into `commande` values(103,9,2);
insert into `commande` values(103,14,2);
insert into `commande` values(103,2,1);
insert into `commande` values(103,3,1);
insert into `commande` values(104,7,1);
insert into `commande` values(104,11,1);
insert into `commande` values(104,14,1);
insert into `commande` values(104,3,1);
insert into `commande` values(105,3,2);
insert into `commande` values(106,3,2);