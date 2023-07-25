-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20230721.c3c729da0b
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2023 at 10:06 AM
-- Server version: 11.1.1-MariaDB-1:11.1.1+maria~deb11
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elaborato`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenti`
--

CREATE TABLE `agenti` (
  `Nome` varchar(30) NOT NULL,
  `PuntiSalute` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenti`
--

INSERT INTO `agenti` (`Nome`, `PuntiSalute`) VALUES
('Astra', 195),
('Breach', 210),
('Jett', 120),
('Killjoy', 180),
('Phoenix', 200),
('Reyna', 220),
('Viper', 200),
('Yoru', 150);

-- --------------------------------------------------------

--
-- Table structure for table `armi`
--

CREATE TABLE `armi` (
  `Nome` varchar(30) NOT NULL,
  `Tipologia` set('Primaria','Secondaria') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `armi`
--

INSERT INTO `armi` (`Nome`, `Tipologia`) VALUES
('Ghost', 'Secondaria'),
('Odin', 'Primaria'),
('Operator', 'Primaria'),
('Phantom', 'Primaria'),
('Sheriff', 'Secondaria'),
('Shorty', 'Secondaria'),
('Vandal', 'Primaria');

-- --------------------------------------------------------

--
-- Table structure for table `azioni`
--

CREATE TABLE `azioni` (
  `CodicePartitaRound` int(11) NOT NULL,
  `NumeroRound` int(11) NOT NULL,
  `Tipo` set('Innesco','Disinnesco') NOT NULL,
  `CodiceGiocatore` int(11) NOT NULL,
  `Sito` set('A','B','C') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giocatori`
--

CREATE TABLE `giocatori` (
  `Codice` int(11) NOT NULL,
  `UsernameUtente` varchar(30) NOT NULL,
  `CodicePartita` int(11) NOT NULL,
  `NomeAgente` varchar(30) NOT NULL,
  `NomeSquadra` set('Alpha','Beta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mappe`
--

CREATE TABLE `mappe` (
  `Nome` varchar(30) NOT NULL,
  `NumeroSiti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mappe`
--

INSERT INTO `mappe` (`Nome`, `NumeroSiti`) VALUES
('Ascent', 2),
('Bind', 2),
('Fracture', 2),
('Haven', 3),
('Icebox', 2);

-- --------------------------------------------------------

--
-- Table structure for table `partite`
--

CREATE TABLE `partite` (
  `Codice` int(11) NOT NULL,
  `Data` datetime NOT NULL,
  `NomeMappa` varchar(30) NOT NULL,
  `DurataTotale` time DEFAULT NULL,
  `SquadraVincente` set('Alpha','Beta') DEFAULT NULL,
  `RoundTotali` int(11) DEFAULT NULL,
  `RoundVinti` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `partite_utente`
-- (See below for the actual view)
--
CREATE TABLE `partite_utente` (
`CodiceG` int(11)
,`UsernameUtente` varchar(30)
,`NomeSquadra` set('Alpha','Beta')
,`CodicePartita` int(11)
,`Codice` int(11)
,`Data` datetime
,`NomeMappa` varchar(30)
,`DurataTotale` time
,`SquadraVincente` set('Alpha','Beta')
,`RoundTotali` int(11)
,`RoundVinti` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `possessi`
--

CREATE TABLE `possessi` (
  `CodicePartitaRound` int(11) NOT NULL,
  `NumeroRound` int(11) NOT NULL,
  `NomeArma` varchar(30) NOT NULL,
  `CodiceGiocatore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `round`
--

CREATE TABLE `round` (
  `CodicePartita` int(11) NOT NULL,
  `Numero` int(11) NOT NULL,
  `Durata` time DEFAULT NULL,
  `SquadraVincente` set('Alpha','Beta') DEFAULT NULL,
  `RuoloVincente` set('Attacco','Difesa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uccisioni`
--

CREATE TABLE `uccisioni` (
  `CodicePartitaRound` int(11) NOT NULL,
  `NumeroRound` int(11) NOT NULL,
  `CodiceGiocatoreS` int(11) NOT NULL,
  `CodiceGiocatoreC` int(11) NOT NULL,
  `Istante` time NOT NULL,
  `NomeArma` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `Username` varchar(30) NOT NULL,
  `Password` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`Username`, `Password`) VALUES
('user1', 'user1.p'),
('user10', 'user10.p'),
('user11', 'user11.p'),
('user12', 'user12.p'),
('user2', 'user2.p'),
('user3', 'user3.p'),
('user4', 'user4.p'),
('user5', 'user5.p'),
('user6', 'user6.p'),
('user7', 'user7.p'),
('user8', 'user8.p'),
('user9', 'user9.p');

-- --------------------------------------------------------

--
-- Structure for view `partite_utente`
--
DROP TABLE IF EXISTS `partite_utente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `partite_utente`  AS SELECT `g`.`Codice` AS `CodiceG`, `g`.`UsernameUtente` AS `UsernameUtente`, `g`.`NomeSquadra` AS `NomeSquadra`, `g`.`CodicePartita` AS `CodicePartita`, `p`.`Codice` AS `Codice`, `p`.`Data` AS `Data`, `p`.`NomeMappa` AS `NomeMappa`, `p`.`DurataTotale` AS `DurataTotale`, `p`.`SquadraVincente` AS `SquadraVincente`, `p`.`RoundTotali` AS `RoundTotali`, `p`.`RoundVinti` AS `RoundVinti` FROM (`partite` `p` join `giocatori` `g`) WHERE `p`.`Codice` = `g`.`CodicePartita` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenti`
--
ALTER TABLE `agenti`
  ADD PRIMARY KEY (`Nome`);

--
-- Indexes for table `armi`
--
ALTER TABLE `armi`
  ADD PRIMARY KEY (`Nome`);

--
-- Indexes for table `azioni`
--
ALTER TABLE `azioni`
  ADD PRIMARY KEY (`CodicePartitaRound`,`NumeroRound`,`Tipo`),
  ADD KEY `fk_azione_round_n` (`NumeroRound`),
  ADD KEY `fk_azione_giocatore` (`CodiceGiocatore`);

--
-- Indexes for table `giocatori`
--
ALTER TABLE `giocatori`
  ADD PRIMARY KEY (`Codice`),
  ADD UNIQUE KEY `unique_utente_partita` (`UsernameUtente`,`CodicePartita`),
  ADD KEY `fk_giocatore_agente` (`NomeAgente`),
  ADD KEY `fk_giocatore_partita` (`CodicePartita`);

--
-- Indexes for table `mappe`
--
ALTER TABLE `mappe`
  ADD PRIMARY KEY (`Nome`);

--
-- Indexes for table `partite`
--
ALTER TABLE `partite`
  ADD PRIMARY KEY (`Codice`),
  ADD KEY `fk_partita_mappa` (`NomeMappa`);

--
-- Indexes for table `possessi`
--
ALTER TABLE `possessi`
  ADD PRIMARY KEY (`CodicePartitaRound`,`NumeroRound`,`NomeArma`,`CodiceGiocatore`),
  ADD KEY `fk_possesso_round_n` (`NumeroRound`),
  ADD KEY `fk_possesso_arma` (`NomeArma`),
  ADD KEY `fk_possesso_giocatore` (`CodiceGiocatore`);

--
-- Indexes for table `round`
--
ALTER TABLE `round`
  ADD PRIMARY KEY (`Numero`,`CodicePartita`),
  ADD KEY `fk_round_partita` (`CodicePartita`);

--
-- Indexes for table `uccisioni`
--
ALTER TABLE `uccisioni`
  ADD PRIMARY KEY (`CodicePartitaRound`,`NumeroRound`,`CodiceGiocatoreS`,`CodiceGiocatoreC`),
  ADD KEY `fk_uccisione_round_n` (`NumeroRound`),
  ADD KEY `fk_uccisione_arma` (`NomeArma`),
  ADD KEY `fk_uccisione_giocatore_s` (`CodiceGiocatoreS`),
  ADD KEY `fk_uccisione_giocatore_c` (`CodiceGiocatoreC`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `giocatori`
--
ALTER TABLE `giocatori`
  MODIFY `Codice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partite`
--
ALTER TABLE `partite`
  MODIFY `Codice` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `azioni`
--
ALTER TABLE `azioni`
  ADD CONSTRAINT `fk_azione_giocatore` FOREIGN KEY (`CodiceGiocatore`) REFERENCES `giocatori` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_azione_round_n` FOREIGN KEY (`NumeroRound`) REFERENCES `round` (`Numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_azione_round_p` FOREIGN KEY (`CodicePartitaRound`) REFERENCES `round` (`CodicePartita`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `giocatori`
--
ALTER TABLE `giocatori`
  ADD CONSTRAINT `fk_giocatore_agente` FOREIGN KEY (`NomeAgente`) REFERENCES `agenti` (`Nome`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_giocatore_partita` FOREIGN KEY (`CodicePartita`) REFERENCES `partite` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_giocatore_utente` FOREIGN KEY (`UsernameUtente`) REFERENCES `utenti` (`Username`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `partite`
--
ALTER TABLE `partite`
  ADD CONSTRAINT `fk_mappa` FOREIGN KEY (`NomeMappa`) REFERENCES `mappe` (`Nome`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `possessi`
--
ALTER TABLE `possessi`
  ADD CONSTRAINT `fk_possesso_arma` FOREIGN KEY (`NomeArma`) REFERENCES `armi` (`Nome`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_possesso_giocatore` FOREIGN KEY (`CodiceGiocatore`) REFERENCES `giocatori` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_possesso_round_n` FOREIGN KEY (`NumeroRound`) REFERENCES `round` (`Numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_possesso_round_p` FOREIGN KEY (`CodicePartitaRound`) REFERENCES `round` (`CodicePartita`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `round`
--
ALTER TABLE `round`
  ADD CONSTRAINT `fk_round_partita` FOREIGN KEY (`CodicePartita`) REFERENCES `partite` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `uccisioni`
--
ALTER TABLE `uccisioni`
  ADD CONSTRAINT `fk_uccisione_arma` FOREIGN KEY (`NomeArma`) REFERENCES `armi` (`Nome`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uccisione_giocatore_c` FOREIGN KEY (`CodiceGiocatoreC`) REFERENCES `giocatori` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uccisione_giocatore_s` FOREIGN KEY (`CodiceGiocatoreS`) REFERENCES `giocatori` (`Codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uccisione_round_n` FOREIGN KEY (`NumeroRound`) REFERENCES `round` (`Numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uccisione_round_p` FOREIGN KEY (`CodicePartitaRound`) REFERENCES `round` (`CodicePartita`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
