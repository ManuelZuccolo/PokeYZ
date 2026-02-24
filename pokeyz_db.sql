-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 24, 2026 alle 06:35
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokeyz_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `abilita`
--

CREATE TABLE `abilita` (
  `id_abilita` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `effetto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `abilita`
--

INSERT INTO `abilita` (`id_abilita`, `nome`, `effetto`) VALUES
(1, 'Blaze', 'up Fire-type moves when the Pokémon\'s HP is low.');

-- --------------------------------------------------------

--
-- Struttura della tabella `abilita_pokemon`
--

CREATE TABLE `abilita_pokemon` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `id_abilita` int(11) NOT NULL,
  `segreta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `abilita_pokemon`
--

INSERT INTO `abilita_pokemon` (`cod`, `sec_form`, `id_abilita`, `segreta`) VALUES
(6, 'BASE', 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `battaglia`
--

CREATE TABLE `battaglia` (
  `id_battaglia` int(11) NOT NULL,
  `id_player1` int(11) NOT NULL,
  `id_player2` int(11) NOT NULL,
  `esito` tinyint(1) DEFAULT NULL,
  `pm` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `effetto_mossa`
--

CREATE TABLE `effetto_mossa` (
  `id_effetto` int(11) NOT NULL,
  `id_mossa` int(11) NOT NULL,
  `tipo_effetto` varchar(30) DEFAULT NULL,
  `valore_effetto` int(11) DEFAULT NULL,
  `bersaglio` varchar(30) DEFAULT NULL,
  `probabilita` int(11) DEFAULT NULL,
  `durata` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `mossa`
--

CREATE TABLE `mossa` (
  `id_mossa` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `danno` int(11) DEFAULT NULL,
  `categoria` varchar(10) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `accuratezza` int(11) DEFAULT NULL,
  `priorita` int(11) DEFAULT 0,
  `pp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mossa`
--

INSERT INTO `mossa` (`id_mossa`, `nome`, `descrizione`, `danno`, `categoria`, `tipo`, `accuratezza`, `priorita`, `pp`) VALUES
(53, 'Flamethrower', 'A powerful stream of fire. May burn the target.', 90, 'special', 'Fire', 100, 0, 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `mossa_x_pokemon`
--

CREATE TABLE `mossa_x_pokemon` (
  `id_mossa` int(11) NOT NULL,
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mossa_x_pokemon`
--

INSERT INTO `mossa_x_pokemon` (`id_mossa`, `cod`, `sec_form`) VALUES
(53, 6, 'BASE');

-- --------------------------------------------------------

--
-- Struttura della tabella `pokemon`
--

CREATE TABLE `pokemon` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL DEFAULT 'BASE',
  `nome` varchar(15) NOT NULL,
  `tipo1` varchar(15) NOT NULL,
  `tipo2` varchar(15) DEFAULT NULL,
  `regione` int(11) NOT NULL,
  `uovo1` varchar(15) NOT NULL,
  `uovo2` varchar(15) DEFAULT NULL,
  `grado` varchar(20) NOT NULL,
  `originale` tinyint(1) NOT NULL,
  `HP` int(11) NOT NULL,
  `ATK` int(11) NOT NULL,
  `DEF` int(11) NOT NULL,
  `SP_ATK` int(11) NOT NULL,
  `SP_DEF` int(11) NOT NULL,
  `SPE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pokemon`
--

INSERT INTO `pokemon` (`cod`, `sec_form`, `nome`, `tipo1`, `tipo2`, `regione`, `uovo1`, `uovo2`, `grado`, `originale`, `HP`, `ATK`, `DEF`, `SP_ATK`, `SP_DEF`, `SPE`) VALUES
(1, 'BASE', 'bulbasaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 45, 49, 49, 65, 65, 45),
(2, 'BASE', 'ivysaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 60, 62, 63, 80, 80, 60),
(3, 'BASE', 'venusaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 80, 82, 83, 100, 100, 80),
(4, 'BASE', 'charmander', 'fire', NULL, 1, 'monster', 'dragon', 'normale', 1, 39, 52, 43, 60, 50, 65),
(5, 'BASE', 'charmeleon', 'fire', NULL, 1, 'monster', 'dragon', 'normale', 1, 58, 64, 58, 80, 65, 80),
(6, 'BASE', 'charizard', 'fire', 'flying', 1, 'monster', 'dragon', 'normale', 1, 78, 84, 78, 109, 85, 100),
(7, 'BASE', 'squirtle', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 44, 48, 65, 50, 64, 43),
(8, 'BASE', 'wartortle', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 59, 63, 80, 65, 80, 58),
(9, 'BASE', 'blastoise', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 79, 83, 100, 85, 105, 78),
(10, 'BASE', 'caterpie', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 45, 30, 35, 20, 20, 45),
(11, 'BASE', 'metapod', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 50, 20, 55, 25, 25, 30),
(12, 'BASE', 'butterfree', 'bug', 'flying', 1, 'bug', NULL, 'normale', 1, 60, 45, 50, 90, 80, 70),
(13, 'BASE', 'weedle', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 40, 35, 30, 20, 20, 50),
(14, 'BASE', 'kakuna', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 45, 25, 50, 25, 25, 35),
(15, 'BASE', 'beedrill', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 65, 90, 40, 45, 80, 75),
(16, 'BASE', 'pidgey', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 45, 40, 35, 35, 56),
(17, 'BASE', 'pidgeotto', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 63, 60, 55, 50, 50, 71),
(18, 'BASE', 'pidgeot', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 83, 80, 75, 70, 70, 101),
(19, 'BASE', 'rattata', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 30, 56, 35, 25, 35, 72),
(20, 'BASE', 'raticate', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 55, 81, 60, 50, 70, 97),
(21, 'BASE', 'spearow', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 60, 30, 31, 31, 70),
(22, 'BASE', 'fearow', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 65, 90, 65, 61, 61, 100),
(23, 'BASE', 'ekans', 'poison', NULL, 1, 'field', 'dragon', 'normale', 1, 35, 60, 44, 40, 54, 55),
(24, 'BASE', 'arbok', 'poison', NULL, 1, 'field', 'dragon', 'normale', 1, 60, 95, 69, 65, 79, 80),
(25, 'BASE', 'pikachu', 'electric', NULL, 1, 'field', 'fairy', 'normale', 1, 35, 55, 40, 50, 50, 90),
(26, 'BASE', 'raichu', 'electric', NULL, 1, 'field', 'fairy', 'normale', 1, 60, 90, 55, 90, 80, 110),
(27, 'BASE', 'sandshrew', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 50, 75, 85, 20, 30, 40),
(28, 'BASE', 'sandslash', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 75, 100, 110, 45, 55, 65),
(29, 'BASE', 'nidoran_f', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 55, 47, 52, 40, 40, 41),
(30, 'BASE', 'nidorina', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 70, 62, 67, 55, 55, 56),
(31, 'BASE', 'nidoqueen', 'poison', 'ground', 1, 'monster', 'field', 'normale', 1, 90, 92, 87, 75, 85, 76),
(32, 'BASE', 'nidoran_m', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 46, 57, 40, 40, 40, 50),
(33, 'BASE', 'nidorino', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 61, 72, 57, 55, 55, 65),
(34, 'BASE', 'nidoking', 'poison', 'ground', 1, 'monster', 'field', 'normale', 1, 81, 102, 77, 85, 75, 85),
(35, 'BASE', 'clefairy', 'fairy', NULL, 1, 'fairy', NULL, 'normale', 1, 70, 45, 48, 60, 65, 35),
(36, 'BASE', 'clefable', 'fairy', NULL, 1, 'fairy', NULL, 'normale', 1, 95, 70, 73, 95, 90, 60),
(37, 'BASE', 'vulpix', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 38, 41, 40, 50, 65, 65),
(38, 'BASE', 'ninetales', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 73, 76, 75, 81, 100, 100),
(39, 'BASE', 'jigglypuff', 'normal', 'fairy', 1, 'fairy', NULL, 'normale', 1, 115, 45, 20, 45, 25, 20),
(40, 'BASE', 'wigglytuff', 'normal', 'fairy', 1, 'fairy', NULL, 'normale', 1, 140, 70, 45, 85, 50, 45),
(41, 'BASE', 'zubat', 'poison', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 45, 35, 30, 40, 55),
(42, 'BASE', 'golbat', 'poison', 'flying', 1, 'flying', NULL, 'normale', 1, 75, 80, 70, 65, 75, 90),
(43, 'BASE', 'oddish', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 45, 50, 55, 75, 65, 30),
(44, 'BASE', 'gloom', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 60, 65, 70, 85, 75, 40),
(45, 'BASE', 'vileplume', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 75, 80, 85, 110, 90, 50),
(46, 'BASE', 'paras', 'bug', 'grass', 1, 'bug', 'grass', 'normale', 1, 35, 70, 55, 45, 55, 25),
(47, 'BASE', 'parasect', 'bug', 'grass', 1, 'bug', 'grass', 'normale', 1, 60, 95, 80, 60, 80, 30),
(48, 'BASE', 'venonat', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 60, 55, 50, 40, 55, 45),
(49, 'BASE', 'venomoth', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 70, 65, 60, 90, 75, 90),
(50, 'BASE', 'diglett', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 10, 55, 25, 35, 45, 95),
(51, 'BASE', 'dugtrio', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 35, 100, 50, 50, 70, 120),
(52, 'BASE', 'meowth', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 40, 45, 35, 40, 40, 90),
(53, 'BASE', 'persian', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 65, 70, 60, 65, 65, 115),
(54, 'BASE', 'psyduck', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 50, 52, 48, 65, 50, 55),
(55, 'BASE', 'golduck', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 80, 82, 78, 95, 80, 85),
(56, 'BASE', 'mankey', 'fighting', NULL, 1, 'field', NULL, 'normale', 1, 40, 80, 35, 35, 45, 70),
(57, 'BASE', 'primeape', 'fighting', NULL, 1, 'field', NULL, 'normale', 1, 65, 105, 60, 60, 70, 95),
(58, 'BASE', 'growlithe', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 55, 70, 45, 70, 50, 60),
(59, 'BASE', 'arcanine', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 90, 110, 80, 100, 80, 95),
(60, 'BASE', 'poliwag', 'water', NULL, 1, 'water1', NULL, 'normale', 1, 40, 50, 40, 40, 40, 90),
(61, 'BASE', 'poliwhirl', 'water', NULL, 1, 'water1', NULL, 'normale', 1, 65, 65, 65, 50, 50, 90),
(62, 'BASE', 'poliwrath', 'water', 'fighting', 1, 'water1', NULL, 'normale', 1, 90, 95, 95, 70, 90, 70),
(63, 'BASE', 'abra', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 25, 20, 15, 105, 55, 90),
(64, 'BASE', 'kadabra', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 40, 35, 30, 120, 70, 105),
(65, 'BASE', 'alakazam', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 55, 50, 45, 135, 95, 120),
(66, 'BASE', 'machop', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 70, 80, 50, 35, 35, 35),
(67, 'BASE', 'machoke', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 80, 100, 70, 50, 60, 45),
(68, 'BASE', 'machamp', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 90, 130, 80, 65, 85, 55),
(69, 'BASE', 'bellsprout', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 50, 75, 35, 70, 30, 40),
(70, 'BASE', 'weepinbell', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 65, 90, 50, 85, 45, 55),
(71, 'BASE', 'victreebel', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 80, 105, 65, 100, 70, 70),
(72, 'BASE', 'tentacool', 'water', 'poison', 1, 'water3', NULL, 'normale', 1, 40, 40, 35, 50, 100, 70),
(73, 'BASE', 'tentacruel', 'water', 'poison', 1, 'water3', NULL, 'normale', 1, 80, 70, 65, 80, 120, 100),
(74, 'BASE', 'geodude', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 40, 80, 100, 30, 30, 20),
(75, 'BASE', 'graveler', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 55, 95, 115, 45, 45, 35),
(76, 'BASE', 'golem', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 80, 120, 130, 55, 65, 45),
(77, 'BASE', 'ponyta', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 50, 85, 55, 65, 65, 90),
(78, 'BASE', 'rapidash', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 65, 100, 70, 80, 80, 105),
(79, 'BASE', 'slowpoke', 'water', 'psychic', 1, 'monster', 'water1', 'normale', 1, 90, 65, 65, 40, 40, 15),
(80, 'BASE', 'slowbro', 'water', 'psychic', 1, 'monster', 'water1', 'normale', 1, 95, 75, 110, 100, 80, 30),
(81, 'BASE', 'magnemite', 'electric', 'steel', 1, 'mineral', NULL, 'normale', 1, 25, 35, 70, 95, 55, 45),
(82, 'BASE', 'magneton', 'electric', 'steel', 1, 'mineral', NULL, 'normale', 1, 50, 60, 95, 120, 70, 70),
(83, 'BASE', 'farfetchd', 'normal', 'flying', 1, 'flying', 'field', 'normale', 1, 52, 90, 55, 58, 62, 60),
(84, 'BASE', 'doduo', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 35, 85, 45, 35, 35, 75),
(85, 'BASE', 'dodrio', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 60, 110, 70, 60, 60, 100),
(86, 'BASE', 'seel', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 65, 45, 55, 45, 70, 45),
(87, 'BASE', 'dewgong', 'water', 'ice', 1, 'water1', 'field', 'normale', 1, 90, 70, 80, 70, 95, 70),
(88, 'BASE', 'grimer', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 80, 80, 50, 40, 50, 25),
(89, 'BASE', 'muk', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 105, 105, 75, 65, 100, 50),
(90, 'BASE', 'shellder', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 65, 100, 45, 25, 40),
(91, 'BASE', 'cloyster', 'water', 'ice', 1, 'water3', NULL, 'normale', 1, 50, 95, 180, 85, 45, 70),
(92, 'BASE', 'gastly', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 30, 35, 30, 100, 35, 80),
(93, 'BASE', 'haunter', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 45, 50, 45, 115, 55, 95),
(94, 'BASE', 'gengar', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 60, 65, 60, 130, 75, 110),
(95, 'BASE', 'onix', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 35, 45, 160, 30, 45, 70),
(96, 'BASE', 'drowzee', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 60, 48, 45, 43, 90, 42),
(97, 'BASE', 'hypno', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 85, 73, 70, 73, 115, 67),
(98, 'BASE', 'krabby', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 105, 90, 25, 25, 50),
(99, 'BASE', 'kingler', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 55, 130, 115, 50, 50, 75),
(100, 'BASE', 'voltorb', 'electric', NULL, 1, 'mineral', NULL, 'normale', 1, 40, 30, 50, 55, 55, 100),
(101, 'BASE', 'electrode', 'electric', NULL, 1, 'mineral', NULL, 'normale', 1, 60, 50, 70, 80, 80, 150),
(102, 'BASE', 'exeggcute', 'grass', 'psychic', 1, 'grass', NULL, 'normale', 1, 60, 40, 80, 60, 45, 40),
(103, 'BASE', 'exeggutor', 'grass', 'psychic', 1, 'grass', NULL, 'normale', 1, 95, 95, 85, 125, 75, 55),
(104, 'BASE', 'cubone', 'ground', NULL, 1, 'monster', NULL, 'normale', 1, 50, 50, 95, 40, 50, 35),
(105, 'BASE', 'marowak', 'ground', NULL, 1, 'monster', NULL, 'normale', 1, 60, 80, 110, 50, 80, 45),
(106, 'BASE', 'hitmonlee', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 50, 120, 53, 35, 110, 87),
(107, 'BASE', 'hitmonchan', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 50, 105, 79, 35, 110, 76),
(108, 'BASE', 'lickitung', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 90, 55, 75, 60, 75, 30),
(109, 'BASE', 'koffing', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 40, 65, 95, 60, 45, 35),
(110, 'BASE', 'weezing', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 65, 90, 120, 85, 70, 60),
(111, 'BASE', 'rhyhorn', 'ground', 'rock', 1, 'monster', 'field', 'normale', 1, 80, 85, 95, 30, 30, 25),
(112, 'BASE', 'rhydon', 'ground', 'rock', 1, 'monster', 'field', 'normale', 1, 105, 130, 120, 45, 45, 40),
(113, 'BASE', 'chansey', 'normal', NULL, 1, 'fairy', NULL, 'normale', 1, 250, 5, 5, 35, 105, 50),
(114, 'BASE', 'tangela', 'grass', NULL, 1, 'grass', NULL, 'normale', 1, 65, 55, 115, 100, 40, 60),
(115, 'BASE', 'kangaskhan', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 105, 95, 80, 40, 80, 90),
(116, 'BASE', 'horsea', 'water', NULL, 1, 'water1', 'dragon', 'normale', 1, 30, 40, 70, 70, 25, 60),
(117, 'BASE', 'seadra', 'water', NULL, 1, 'water1', 'dragon', 'normale', 1, 55, 65, 95, 95, 45, 85),
(118, 'BASE', 'goldeen', 'water', NULL, 1, 'water2', NULL, 'normale', 1, 45, 67, 60, 35, 50, 63),
(119, 'BASE', 'seaking', 'water', NULL, 1, 'water2', NULL, 'normale', 1, 80, 92, 65, 65, 80, 68),
(120, 'BASE', 'staryu', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 45, 55, 70, 55, 85),
(121, 'BASE', 'starmie', 'water', 'psychic', 1, 'water3', NULL, 'normale', 1, 60, 75, 85, 100, 85, 115),
(122, 'BASE', 'mr_mime', 'psychic', 'fairy', 1, 'humanlike', NULL, 'normale', 1, 40, 45, 65, 100, 120, 90),
(123, 'BASE', 'scyther', 'bug', 'flying', 1, 'bug', NULL, 'normale', 1, 70, 110, 80, 55, 80, 105),
(124, 'BASE', 'jynx', 'ice', 'psychic', 1, 'humanlike', NULL, 'normale', 1, 65, 50, 35, 115, 95, 95),
(125, 'BASE', 'electabuzz', 'electric', NULL, 1, 'humanlike', NULL, 'normale', 1, 65, 83, 57, 95, 85, 105),
(126, 'BASE', 'magmar', 'fire', NULL, 1, 'humanlike', NULL, 'normale', 1, 65, 95, 57, 100, 85, 93),
(127, 'BASE', 'pinsir', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 65, 125, 100, 55, 70, 85),
(128, 'BASE', 'tauros', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 75, 100, 95, 40, 70, 110),
(129, 'BASE', 'magikarp', 'water', NULL, 1, 'water2', 'dragon', 'normale', 1, 20, 10, 55, 15, 20, 80),
(130, 'BASE', 'gyarados', 'water', 'flying', 1, 'water2', 'dragon', 'normale', 1, 95, 125, 79, 60, 100, 81),
(131, 'BASE', 'lapras', 'water', 'ice', 1, 'monster', 'water1', 'normale', 1, 130, 85, 80, 85, 95, 60),
(132, 'BASE', 'ditto', 'normal', NULL, 1, 'ditto', NULL, 'normale', 1, 48, 48, 48, 48, 48, 48),
(133, 'BASE', 'eevee', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 55, 55, 50, 45, 65, 55),
(134, 'BASE', 'vaporeon', 'water', NULL, 1, 'field', NULL, 'normale', 1, 130, 65, 60, 110, 95, 65),
(135, 'BASE', 'jolteon', 'electric', NULL, 1, 'field', NULL, 'normale', 1, 65, 65, 60, 110, 95, 130),
(136, 'BASE', 'flareon', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 65, 130, 60, 95, 110, 65),
(137, 'BASE', 'porygon', 'normal', NULL, 1, 'mineral', NULL, 'normale', 1, 65, 60, 70, 85, 75, 40),
(138, 'BASE', 'omanyte', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 35, 40, 100, 90, 55, 35),
(139, 'BASE', 'omastar', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 70, 60, 125, 115, 70, 55),
(140, 'BASE', 'kabuto', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 30, 80, 90, 55, 45, 55),
(141, 'BASE', 'kabutops', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 60, 115, 105, 65, 70, 80),
(142, 'BASE', 'aerodactyl', 'rock', 'flying', 1, 'flying', NULL, 'normale', 1, 80, 105, 65, 60, 75, 130),
(143, 'BASE', 'snorlax', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 160, 110, 65, 65, 110, 30),
(144, 'BASE', 'articuno', 'ice', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 85, 100, 95, 125, 85),
(145, 'BASE', 'zapdos', 'electric', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 90, 85, 125, 90, 100),
(146, 'BASE', 'moltres', 'fire', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 100, 90, 125, 85, 90),
(147, 'BASE', 'dratini', 'dragon', NULL, 1, 'water1', 'dragon', 'normale', 1, 41, 64, 45, 50, 50, 50),
(148, 'BASE', 'dragonair', 'dragon', NULL, 1, 'water1', 'dragon', 'normale', 1, 61, 84, 65, 70, 70, 70),
(149, 'BASE', 'dragonite', 'dragon', 'flying', 1, 'water1', 'dragon', 'normale', 1, 91, 134, 95, 100, 100, 80),
(150, 'BASE', 'mewtwo', 'psychic', NULL, 1, 'undiscovered', NULL, 'leggendario', 1, 106, 110, 90, 154, 90, 130),
(151, 'BASE', 'mew', 'psychic', NULL, 1, 'undiscovered', NULL, 'mitico', 1, 100, 100, 100, 100, 100, 100);

-- --------------------------------------------------------

--
-- Struttura della tabella `pokemon_utente`
--

CREATE TABLE `pokemon_utente` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `id_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `id_utente` int(11) NOT NULL,
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `regione`
--

CREATE TABLE `regione` (
  `generazione` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `regione`
--

INSERT INTO `regione` (`generazione`, `nome`, `descrizione`) VALUES
(1, 'kanto', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

CREATE TABLE `squadra` (
  `id_squadra` int(11) NOT NULL,
  `codice_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`id_squadra`, `codice_utente`) VALUES
(7, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra_pokemon`
--

CREATE TABLE `squadra_pokemon` (
  `id_squadra` int(11) NOT NULL,
  `slot` int(11) NOT NULL CHECK (`slot` between 1 and 6),
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `mossa1` int(11) NOT NULL,
  `mossa2` int(11) DEFAULT NULL,
  `mossa3` int(11) DEFAULT NULL,
  `mossa4` int(11) DEFAULT NULL,
  `abilita_scelta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `squadra_pokemon`
--

INSERT INTO `squadra_pokemon` (`id_squadra`, `slot`, `cod`, `sec_form`, `mossa1`, `mossa2`, `mossa3`, `mossa4`, `abilita_scelta`) VALUES
(7, 1, 6, 'BASE', 53, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo`
--

CREATE TABLE `tipo` (
  `id_t` int(11) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipo`
--

INSERT INTO `tipo` (`id_t`, `nome`, `descrizione`) VALUES
(1, 'normal', ''),
(2, 'fire', ''),
(3, 'water', ''),
(4, 'electric', ''),
(5, 'grass', ''),
(6, 'ice', ''),
(7, 'fighting', ''),
(8, 'poison', ''),
(9, 'ground', ''),
(10, 'flying', ''),
(11, 'psychic', ''),
(12, 'bug', ''),
(13, 'rock', ''),
(14, 'ghost', ''),
(15, 'dragon', ''),
(16, 'dark', ''),
(17, 'steel', ''),
(18, 'fairy', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `codice` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`codice`, `password`, `nome`) VALUES
(7, '$2y$10$lbUA0PeQGW/4swnZG3rqH.ZR10u08q37sCsOqEHUP8JBxw7MCdbQi', 'prova');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abilita`
--
ALTER TABLE `abilita`
  ADD PRIMARY KEY (`id_abilita`);

--
-- Indici per le tabelle `abilita_pokemon`
--
ALTER TABLE `abilita_pokemon`
  ADD PRIMARY KEY (`cod`,`sec_form`,`id_abilita`),
  ADD KEY `id_abilita` (`id_abilita`);

--
-- Indici per le tabelle `battaglia`
--
ALTER TABLE `battaglia`
  ADD PRIMARY KEY (`id_battaglia`),
  ADD KEY `id_player1` (`id_player1`),
  ADD KEY `id_player2` (`id_player2`);

--
-- Indici per le tabelle `effetto_mossa`
--
ALTER TABLE `effetto_mossa`
  ADD PRIMARY KEY (`id_effetto`),
  ADD KEY `id_mossa` (`id_mossa`);

--
-- Indici per le tabelle `mossa`
--
ALTER TABLE `mossa`
  ADD PRIMARY KEY (`id_mossa`),
  ADD KEY `tipo` (`tipo`);

--
-- Indici per le tabelle `mossa_x_pokemon`
--
ALTER TABLE `mossa_x_pokemon`
  ADD PRIMARY KEY (`id_mossa`,`cod`,`sec_form`),
  ADD KEY `cod` (`cod`,`sec_form`);

--
-- Indici per le tabelle `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`cod`,`sec_form`),
  ADD KEY `tipo1` (`tipo1`),
  ADD KEY `tipo2` (`tipo2`),
  ADD KEY `regione` (`regione`);

--
-- Indici per le tabelle `pokemon_utente`
--
ALTER TABLE `pokemon_utente`
  ADD PRIMARY KEY (`cod`,`sec_form`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`id_utente`,`cod`,`sec_form`),
  ADD KEY `cod` (`cod`,`sec_form`);

--
-- Indici per le tabelle `regione`
--
ALTER TABLE `regione`
  ADD PRIMARY KEY (`generazione`);

--
-- Indici per le tabelle `squadra`
--
ALTER TABLE `squadra`
  ADD PRIMARY KEY (`id_squadra`),
  ADD KEY `codice_utente` (`codice_utente`);

--
-- Indici per le tabelle `squadra_pokemon`
--
ALTER TABLE `squadra_pokemon`
  ADD PRIMARY KEY (`id_squadra`,`slot`),
  ADD KEY `cod` (`cod`,`sec_form`),
  ADD KEY `mossa1` (`mossa1`),
  ADD KEY `mossa2` (`mossa2`),
  ADD KEY `mossa3` (`mossa3`),
  ADD KEY `mossa4` (`mossa4`),
  ADD KEY `abilita_scelta` (`abilita_scelta`);

--
-- Indici per le tabelle `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id_t`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`codice`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `abilita`
--
ALTER TABLE `abilita`
  MODIFY `id_abilita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `codice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abilita_pokemon`
--
ALTER TABLE `abilita_pokemon`
  ADD CONSTRAINT `abilita_pokemon_ibfk_1` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `abilita_pokemon_ibfk_2` FOREIGN KEY (`id_abilita`) REFERENCES `abilita` (`id_abilita`);

--
-- Limiti per la tabella `battaglia`
--
ALTER TABLE `battaglia`
  ADD CONSTRAINT `battaglia_ibfk_1` FOREIGN KEY (`id_player1`) REFERENCES `utente` (`codice`),
  ADD CONSTRAINT `battaglia_ibfk_2` FOREIGN KEY (`id_player2`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `effetto_mossa`
--
ALTER TABLE `effetto_mossa`
  ADD CONSTRAINT `effetto_mossa_ibfk_1` FOREIGN KEY (`id_mossa`) REFERENCES `mossa` (`id_mossa`);

--
-- Limiti per la tabella `mossa`
--
ALTER TABLE `mossa`
  ADD CONSTRAINT `mossa_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tipo` (`nome`);

--
-- Limiti per la tabella `mossa_x_pokemon`
--
ALTER TABLE `mossa_x_pokemon`
  ADD CONSTRAINT `mossa_x_pokemon_ibfk_1` FOREIGN KEY (`id_mossa`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `mossa_x_pokemon_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`);

--
-- Limiti per la tabella `pokemon`
--
ALTER TABLE `pokemon`
  ADD CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`tipo1`) REFERENCES `tipo` (`nome`),
  ADD CONSTRAINT `pokemon_ibfk_2` FOREIGN KEY (`tipo2`) REFERENCES `tipo` (`nome`),
  ADD CONSTRAINT `pokemon_ibfk_3` FOREIGN KEY (`regione`) REFERENCES `regione` (`generazione`);

--
-- Limiti per la tabella `pokemon_utente`
--
ALTER TABLE `pokemon_utente`
  ADD CONSTRAINT `pokemon_utente_ibfk_1` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `pokemon_utente_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`codice`),
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`);

--
-- Limiti per la tabella `squadra`
--
ALTER TABLE `squadra`
  ADD CONSTRAINT `squadra_ibfk_1` FOREIGN KEY (`codice_utente`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `squadra_pokemon`
--
ALTER TABLE `squadra_pokemon`
  ADD CONSTRAINT `squadra_pokemon_ibfk_1` FOREIGN KEY (`id_squadra`) REFERENCES `squadra` (`id_squadra`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_3` FOREIGN KEY (`mossa1`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_4` FOREIGN KEY (`mossa2`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_5` FOREIGN KEY (`mossa3`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_6` FOREIGN KEY (`mossa4`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_7` FOREIGN KEY (`abilita_scelta`) REFERENCES `abilita` (`id_abilita`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
