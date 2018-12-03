-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Pon 03. pro 2018, 17:58
-- Verze serveru: 5.6.40
-- Verze PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `xcurda02`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `auditorium`
--

CREATE TABLE IF NOT EXISTS `auditorium` (
  `auditorium_id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `fk_cinema` int(11) NOT NULL,
  PRIMARY KEY (`auditorium_id`),
  KEY `auditorium__cinema` (`fk_cinema`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=7 ;

--
-- Vypisuji data pro tabulku `auditorium`
--

INSERT INTO `auditorium` (`auditorium_id`, `number`, `capacity`, `fk_cinema`) VALUES
(1, 1, 200, 1),
(2, 2, 50, 1),
(3, 1, 220, 2),
(4, 2, 280, 2),
(5, 1, 150, 3),
(6, 2, 500, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `cinema`
--

CREATE TABLE IF NOT EXISTS `cinema` (
  `cinema_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  `address` varchar(60) COLLATE latin2_czech_cs NOT NULL,
  `phone` varchar(9) COLLATE latin2_czech_cs NOT NULL,
  `web` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`cinema_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `cinema`
--

INSERT INTO `cinema` (`cinema_id`, `name`, `address`, `phone`, `web`) VALUES
(1, 'Kino Brno Cejl', 'Cejl 777, Brno', '787885113', 'www.kinocejl.cz'),
(2, 'Kino Brno Křenová', 'Křenová 78, Brno', '787882147', 'www.kinokrenova.cz'),
(3, 'Kino Brno Husitská', 'Husitská 717, Brno', '787481111', 'www.kinohusitska.cz');

-- --------------------------------------------------------

--
-- Struktura tabulky `movie`
--

CREATE TABLE IF NOT EXISTS `movie` (
  `movie_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin2_czech_cs NOT NULL,
  `director` varchar(60) COLLATE latin2_czech_cs NOT NULL,
  `release_date` int(11) NOT NULL,
  `genre` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `movie`
--

INSERT INTO `movie` (`movie_id`, `name`, `director`, `release_date`, `genre`, `price`) VALUES
(1, 'Pulp Fiction', 'Tarantino', 1994, 'Drama', 100),
(2, 'Fight Club', 'Fincher', 1999, 'Drama', 120),
(3, 'Inception', 'Nolan', 2010, 'Sci-Fi', 140);

-- --------------------------------------------------------

--
-- Struktura tabulky `projection`
--

CREATE TABLE IF NOT EXISTS `projection` (
  `projection_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `income` int(11) DEFAULT NULL,
  `fk_movie` int(11) NOT NULL,
  `fk_auditorium` int(11) NOT NULL,
  PRIMARY KEY (`projection_id`),
  KEY `projection__auditorium` (`fk_auditorium`),
  KEY `projection__movie` (`fk_movie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `projection`
--

INSERT INTO `projection` (`projection_id`, `date`, `income`, `fk_movie`, `fk_auditorium`) VALUES
(1, '2018-12-24 14:00:00', 58523, 1, 1),
(2, '2019-01-22 16:00:00', 58891, 1, 3),
(3, '2019-02-14 18:00:00', 49902, 2, 5),
(4, '2019-02-25 19:00:00', 45461, 3, 6);

-- --------------------------------------------------------

--
-- Struktura tabulky `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `seat` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cash_advance` tinyint(1) DEFAULT NULL,
  `agegroup` enum('adult','senior','child') COLLATE latin2_czech_cs DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `fk_projection` int(11) NOT NULL,
  PRIMARY KEY (`ticket_id`),
  KEY `ticket_user` (`fk_user`),
  KEY `ticket_projection` (`fk_projection`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=185 ;

--
-- Vypisuji data pro tabulku `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `seat`, `price`, `cash_advance`, `agegroup`, `fk_user`, `fk_projection`) VALUES
(6, 71, 100, 0, 'adult', NULL, 3),
(7, 11, 100, 0, 'adult', NULL, 4),
(152, 89, 50, 1, 'child', 17, 3),
(153, 45, 50, 1, 'adult', 17, 3),
(154, 12, 50, 1, 'senior', 17, 3),
(155, 34, 50, 1, 'child', 17, 3),
(156, 15, 50, 1, 'senior', 17, 3),
(170, 122, 140, 0, 'adult', 13, 4),
(173, 34, 140, 0, 'adult', 13, 4);

--
-- Spouště `ticket`
--
DROP TRIGGER IF EXISTS `ticket_delete_income`;
DELIMITER //
CREATE TRIGGER `ticket_delete_income` BEFORE DELETE ON `ticket`
 FOR EACH ROW BEGIN
    UPDATE projection
    SET income = income - (OLD.price  * 0.5)
    WHERE projection_id = OLD.fk_projection;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ticket_income`;
DELIMITER //
CREATE TRIGGER `ticket_income` BEFORE INSERT ON `ticket`
 FOR EACH ROW BEGIN
    UPDATE projection
    SET income = income + NEW.price
    WHERE projection_id = NEW.fk_projection;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ticket_update_income`;
DELIMITER //
CREATE TRIGGER `ticket_update_income` BEFORE UPDATE ON `ticket`
 FOR EACH ROW BEGIN
    UPDATE projection
    SET income = income + (NEW.price - OLD.price)
    WHERE projection_id = NEW.fk_projection;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  `surname` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  `login` varchar(30) COLLATE latin2_czech_cs NOT NULL,
  `password` varchar(255) COLLATE latin2_czech_cs NOT NULL,
  `email` varchar(50) COLLATE latin2_czech_cs NOT NULL,
  `phone` varchar(9) COLLATE latin2_czech_cs DEFAULT NULL,
  `usergroup` enum('admin','seller','customer') COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=18 ;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`user_id`, `name`, `surname`, `login`, `password`, `email`, `phone`, `usergroup`) VALUES
(4, 'Admin', 'Adminovsky', 'admin', '$2y$10$o/PlCPYK669tTHLat1qoC.sFA79MEBrYihFJem4VlcQHfl30TNBAu', 'admin@admin.cz', NULL, 'admin'),
(7, 'aa', 'aa', 'admin2', '$2y$10$KsHYynKxmmo0WzJqetfv9Oelde8g40/73dEFF/n9BBshMXZTEjQHS', 'aa@ad.ca', NULL, 'admin'),
(11, 'AAAA', 'aaa', 'prodavac', '$2y$10$H4fpUkjBca9rvjZMR1HZAOSzi1rI6thsyc/cyClEM4ErMIEHrh4YW', 'asdas@asd.aa', '798795466', 'seller'),
(13, 'zz', 'aaa', 'zakaznik', '$2y$10$8YiILJCkS9lhlkb6VTzmBOMMoYVpmriJieSHGvEcimSBSbHg2vkm.', 'asdsadasd@sadas.aa', NULL, 'customer'),
(17, 'michal', 'bbbbb', 'michal', '$2y$10$orbC3E1ZDnmrbzQuHqsTkeoS1aylgnAOYXGFLLaAM1lqEIWxZI.PS', 'blackstar35@email.com', NULL, 'customer');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `auditorium`
--
ALTER TABLE `auditorium`
  ADD CONSTRAINT `auditorium__cinema` FOREIGN KEY (`fk_cinema`) REFERENCES `cinema` (`cinema_id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `projection`
--
ALTER TABLE `projection`
  ADD CONSTRAINT `projection__auditorium` FOREIGN KEY (`fk_auditorium`) REFERENCES `auditorium` (`auditorium_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projection__movie` FOREIGN KEY (`fk_movie`) REFERENCES `movie` (`movie_id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_projection` FOREIGN KEY (`fk_projection`) REFERENCES `projection` (`projection_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_user` FOREIGN KEY (`fk_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
