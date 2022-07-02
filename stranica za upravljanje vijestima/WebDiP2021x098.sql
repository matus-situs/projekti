-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2022 at 11:56 PM
-- Server version: 5.5.62-0+deb8u1
-- PHP Version: 7.2.25-1+0~20191128.32+debian8~1.gbp108445

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `WebDiP2021x098`
--
CREATE DATABASE IF NOT EXISTS `WebDiP2021x098` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `WebDiP2021x098`;

-- --------------------------------------------------------

--
-- Table structure for table `DZ4_dnevnik`
--

CREATE TABLE `DZ4_dnevnik` (
  `id` int(11) NOT NULL,
  `putanja` text,
  `datum` datetime DEFAULT NULL,
  `DZ4_korisnici_id` int(11) NOT NULL,
  `DZ4_uloge_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DZ4_korisnici`
--

CREATE TABLE `DZ4_korisnici` (
  `id` int(11) NOT NULL,
  `imePrezime` varchar(45) DEFAULT NULL,
  `datumRodenja` date DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `korime` varchar(45) DEFAULT NULL,
  `lozinka` varchar(45) DEFAULT NULL,
  `kriptiranaLozinka` varchar(45) DEFAULT NULL,
  `kolacici` varchar(45) DEFAULT NULL,
  `DZ4_uloge_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'aktiviran'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `DZ4_korisnici`
--

INSERT INTO `DZ4_korisnici` (`id`, `imePrezime`, `datumRodenja`, `email`, `korime`, `lozinka`, `kriptiranaLozinka`, `kolacici`, `DZ4_uloge_id`, `status`) VALUES
(1, 'Pero Peric', '2000-11-20', 'pperic@foi.hr', 'pperic', 'pperic', NULL, NULL, 4, 'aktiviran'),
(8, 'Sandra Sanci', '1990-01-01', 'ssanci@foi.hr', 'SGlnyLO7FF', 'lozinka', '1e0d217ffb4ca5cad9becee9735c6400a4e4db9e51186', 'nužni', 2, 'aktiviran'),
(17, 'Marta Maric', '2000-12-28', 'mmaric@foi.hr', 'mmaric', 'mmaric', 'heararbbzastrsht', NULL, 3, 'aktiviran');

-- --------------------------------------------------------

--
-- Table structure for table `DZ4_uloge`
--

CREATE TABLE `DZ4_uloge` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `DZ4_uloge`
--

INSERT INTO `DZ4_uloge` (`id`, `naziv`) VALUES
(1, 'neregistrirani korisnik'),
(2, 'registrirani korisnik'),
(3, 'moderator'),
(4, 'administrator');

-- --------------------------------------------------------

--
-- Table structure for table `blokirani_u_kategoriji`
--

CREATE TABLE `blokirani_u_kategoriji` (
  `kategorija_id` int(11) NOT NULL,
  `blokiran_korisnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dnevnik_rada`
--

CREATE TABLE `dnevnik_rada` (
  `id` int(11) NOT NULL,
  `vrijeme` datetime DEFAULT NULL,
  `radnja` text,
  `korisnici_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dnevnik_rada`
--

INSERT INTO `dnevnik_rada` (`id`, `vrijeme`, `radnja`, `korisnici_id`) VALUES
(2, '2022-06-03 14:06:22', 'Registriran je ra?un i poslan aktivacijski email.', 25),
(3, '2022-06-03 14:06:14', 'Aktiviran je korisni?ki ra?un.', 25),
(5, '2022-06-03 14:06:48', 'Korisnik se prijavio na racun.', 2),
(7, '2022-06-03 14:06:23', 'Korisnik se prijavio na racun.', 2),
(9, '2022-06-03 14:06:54', 'Korisnik se prijavio na racun.', 2),
(10, '2022-06-03 14:06:57', 'Korisnik se odjavio sa racuna.', 2),
(11, '2022-06-03 14:06:44', 'Korisnik se prijavio na racun.', 2),
(12, '2022-06-03 14:06:13', 'Korisnik se odjavio sa racuna.', 2),
(13, '2022-06-03 14:06:15', 'Korisnik se prijavio na racun.', 2),
(14, '2022-06-03 14:44:43', 'Korisnik se odjavio sa racuna.', 2),
(15, '2022-06-03 14:44:45', 'Korisnik se prijavio na racun.', 2),
(16, '2022-06-03 15:08:55', 'Korisnik se odjavio sa racuna.', 2),
(17, '2022-06-03 19:16:24', 'Korisnik se prijavio na racun.', 2),
(19, '2022-06-03 23:02:21', 'Korisnik se prijavio na racun.', 2),
(20, '2022-06-04 00:48:08', 'Korisnik se odjavio sa racuna.', 2),
(21, '2022-06-04 12:13:53', 'Korisnik se prijavio na racun.', 2),
(22, '2022-06-04 12:19:36', 'Korisnik se odjavio sa racuna.', 2),
(23, '2022-06-04 12:19:38', 'Korisnik se prijavio na racun.', 2),
(24, '2022-06-04 14:03:50', 'Korisnik se prijavio na racun.', 2),
(25, '2022-06-04 14:15:59', 'Korisnik se odjavio sa racuna.', 2),
(26, '2022-06-04 14:17:18', 'Registriran je racun i poslan aktivacijski email.', 26),
(27, '2022-06-04 14:17:37', 'Aktiviran je korisnicki racun.', 26),
(28, '2022-06-04 14:17:42', 'Korisnik se odjavio sa racuna.', 2),
(29, '2022-06-04 14:17:44', 'Korisnik se prijavio na racun.', 2),
(30, '2022-06-04 14:17:57', 'Korisnik se prijavio na racun.', 2),
(42, '2022-06-04 15:14:08', 'Unesena je kategorija <zu>.', 2),
(43, '2022-06-04 15:14:24', 'Unesena je kategorija <z>.', 2),
(44, '2022-06-04 15:14:24', 'Unesena je kategorija .', 2),
(45, '2022-06-04 15:18:15', 'Obrisana je kategorija __', 2),
(46, '2022-06-04 15:18:16', 'Obrisana je kategorija __', 2),
(47, '2022-06-04 15:18:20', 'Unesena je kategorija _ljhhflj_', 2),
(48, '2022-06-04 15:19:30', 'Obrisana je kategorija __', 2),
(49, '2022-06-04 15:19:34', 'Unesena je kategorija _uiztrer_', 2),
(50, '2022-06-04 15:20:24', 'Unesena je kategorija _po_', 2),
(51, '2022-06-04 15:20:27', 'Obrisana je kategorija _po_', 2),
(52, '2022-06-05 22:39:27', 'Korisnik se prijavio na racun.', 2),
(56, '2022-06-06 00:52:04', 'Korisnik se prijavio na racun.', 2),
(73, '2022-06-06 02:20:08', 'Korisnik se prijavio na racun.', 2),
(74, '2022-06-06 10:12:39', 'Korisnik se prijavio na racun.', 2),
(75, '2022-06-06 10:34:40', 'Korisnik se odjavio sa racuna.', 2),
(76, '2022-06-06 10:35:38', 'Registriran je racun i poslan aktivacijski email.', 27),
(77, '2022-06-06 10:35:57', 'Aktiviran je korisnicki racun.', 27),
(78, '2022-06-06 10:39:08', 'Korisnik se odjavio sa racuna.', 2),
(79, '2022-06-06 10:39:11', 'Korisnik se prijavio na racun.', 2),
(80, '2022-06-06 11:05:04', 'Korisnik se odjavio sa racuna.', 2),
(81, '2022-06-06 11:11:13', 'Registriran je racun i poslan aktivacijski email.', 28),
(82, '2022-06-06 11:11:32', 'Aktiviran je korisnicki racun.', 28),
(83, '2022-06-06 11:11:52', 'Korisnik se odjavio sa racuna.', 2),
(84, '2022-06-14 11:57:02', 'Korisnik se prijavio na racun.', 2),
(86, '2022-06-14 12:56:52', 'Korisnik se prijavio na racun.', 2),
(87, '2022-06-14 13:03:15', 'Korisnik se prijavio na racun.', 2),
(88, '2022-06-14 13:03:37', 'Obrisana je kategorija _18_', 2),
(89, '2022-06-14 14:24:08', 'Dodan je korisnik a u kategoriju _a_', 2),
(90, '2022-06-14 14:24:37', 'Dodan je korisnik a u kategoriju _a_', 2),
(91, '2022-06-14 14:27:11', 'Dodan je korisnik a u kategoriju _a_', 2),
(92, '2022-06-14 14:27:32', 'Dodan je korisnik a u kategoriju _a_', 2),
(93, '2022-06-14 14:27:37', 'Dodan je korisnik a u kategoriju _a_', 2),
(94, '2022-06-14 14:33:33', 'Dodan je korisnik a u kategoriju _a_', 2),
(95, '2022-06-14 14:35:14', 'Dodan je korisnik b u kategoriju _a_', 2),
(96, '2022-06-14 14:35:17', 'Dodan je korisnik b u kategoriju _a_', 2),
(97, '2022-06-14 14:36:40', 'Unesena je kategorija _kategorija_', 2),
(98, '2022-06-14 14:36:45', 'Obrisana je kategorija _15_', 2),
(99, '2022-06-14 14:37:25', 'Unesena je kategorija _a kat_', 2),
(100, '2022-06-14 14:37:31', 'Dodan je korisnik b u kategoriju _a_', 2),
(101, '2022-06-14 14:37:58', 'Obrisana je kategorija _35_', 2),
(102, '2022-06-14 14:41:34', 'Unesena je kategorija _m_', 2),
(103, '2022-06-14 14:41:36', 'Obrisana je kategorija _m_', 2),
(104, '2022-06-15 14:05:22', 'Korisnik se prijavio na racun.', 2),
(105, '2022-06-15 15:30:45', 'Korisniku w je dodjeljeno pravo _Moderator_', 2),
(106, '2022-06-15 15:30:46', 'Korisniku w je dodjeljeno pravo _Administrator_', 2),
(107, '2022-06-15 15:30:47', 'Korisniku  je oduzeto pravo __', 2),
(108, '2022-06-15 15:30:48', 'Korisniku  je oduzeto pravo __', 2),
(109, '2022-06-15 15:31:50', 'Korisniku w je dodjeljeno pravo _Moderator_', 2),
(110, '2022-06-15 15:31:51', 'Korisniku w je oduzeto pravo _Moderator_', 2),
(111, '2022-06-15 16:15:58', 'Korisniku w je dodjeljeno pravo _Moderator_', 2),
(112, '2022-06-15 16:16:09', 'Dodjeljeno je korisniku w kategorija _asd_', 2),
(113, '2022-06-15 21:11:00', 'Korisnik se prijavio na racun.', 2),
(114, '2022-06-15 21:12:35', 'Korisnik se odjavio sa racuna.', 2),
(115, '2022-06-15 21:12:41', 'Korisnik se prijavio na racun.', 27),
(116, '2022-06-15 21:27:12', 'Korisnik se odjavio sa racuna.', 27),
(117, '2022-06-15 21:27:14', 'Korisnik se prijavio na racun.', 27),
(118, '2022-06-15 21:42:06', 'Korisnik se odjavio sa racuna.', 27),
(119, '2022-06-15 21:42:31', 'Korisnik se prijavio na racun.', 2),
(120, '2022-06-15 21:46:02', 'Korisnik se odjavio sa racuna.', 2),
(121, '2022-06-15 21:46:03', 'Korisnik se prijavio na racun.', 27),
(122, '2022-06-16 12:57:44', 'Korisnik se prijavio na racun.', 27),
(123, '2022-06-16 17:22:58', 'Korisnik se prijavio na racun.', 27),
(124, '2022-06-16 20:46:19', 'Korisnik se prijavio na racun.', 27),
(125, '2022-06-16 21:12:03', 'Dodana je vijest _Lorem ipsum_', 27),
(126, '2022-06-16 21:18:24', 'Dodana je vijest _Lorem ipsum_', 27),
(127, '2022-06-16 21:19:46', 'Dodana je vijest _Lorem ipsum_', 27),
(128, '2022-06-16 21:22:23', 'Dodana je vijest _Lorem ipsum_', 27),
(129, '2022-06-16 21:22:27', 'Dodana je vijest _Lorem ipsum_', 27),
(130, '2022-06-16 21:24:19', 'Dodana je vijest _Lorem ipsum_', 27),
(131, '2022-06-16 21:29:11', 'Dodana je vijest _Lorem ipsum_', 27),
(132, '2022-06-16 21:33:01', 'Dodana je vijest _Naslov_', 27),
(133, '2022-06-16 21:39:02', 'Dodana je vijest _Prvi uspjeh?_', 27),
(134, '2022-06-16 22:25:05', 'Korisnik se odjavio sa racuna.', 27),
(135, '2022-06-16 22:25:07', 'Korisnik se prijavio na racun.', 27),
(136, '2022-06-16 22:43:54', 'Korisnik se prijavio na racun.', 27),
(137, '2022-06-17 11:54:48', 'Korisnik se prijavio na racun.', 27),
(138, '2022-06-17 12:36:24', 'Korisnik se prijavio na racun.', 27),
(139, '2022-06-17 12:38:12', 'Korisnik se odjavio sa racuna.', 27),
(140, '2022-06-17 12:38:16', 'Korisnik se prijavio na racun.', 27),
(141, '2022-06-17 12:40:14', 'Korisnik se odjavio sa racuna.', 27),
(142, '2022-06-17 12:47:46', 'Korisnik se odjavio sa racuna.', 27),
(143, '2022-06-17 12:47:53', 'Korisnik se prijavio na racun.', 2),
(144, '2022-06-17 13:16:54', 'Korisnik se odjavio sa racuna.', 2),
(145, '2022-06-17 13:16:56', 'Korisnik se prijavio na racun.', 2),
(146, '2022-06-17 13:23:35', 'Dodjeljeno je recenzentu w vijest __', 2),
(147, '2022-06-17 13:39:15', 'Dodjeljeno je recenzentu w vijest __', 2),
(148, '2022-06-17 13:39:45', 'Dodjeljeno je recenzentu w vijest __', 2),
(149, '2022-06-17 13:41:53', 'Korisniku a je dodjeljeno pravo _Moderator_', 2),
(150, '2022-06-17 13:41:59', 'Korisnik se odjavio sa racuna.', 2),
(151, '2022-06-17 13:42:04', 'Korisnik se prijavio na racun.', 27),
(152, '2022-06-17 13:42:51', 'Korisnik se odjavio sa racuna.', 27),
(153, '2022-06-17 13:42:53', 'Korisnik se prijavio na racun.', 2),
(154, '2022-06-17 13:50:46', 'Dodjeljeno je korisniku w kategorija _kategorija_', 2),
(155, '2022-06-17 13:53:25', 'Dodjeljeno je korisniku w kategorija _kategorija_', 2),
(156, '2022-06-17 13:54:15', 'Dodjeljeno je recenzentu w vijest __', 2),
(157, '2022-06-17 13:58:56', 'Korisnik se odjavio sa racuna.', 2),
(158, '2022-06-17 13:59:05', 'Korisnik se prijavio na racun.', 2),
(159, '2022-06-17 13:59:08', 'Korisniku a je oduzeto pravo _Moderator_', 2),
(160, '2022-06-17 13:59:14', 'Korisniku m je dodjeljeno pravo _Moderator_', 2),
(161, '2022-06-17 13:59:22', 'Korisnik se odjavio sa racuna.', 2),
(162, '2022-06-17 13:59:25', 'Korisnik se prijavio na racun.', 27),
(163, '2022-06-17 13:59:29', 'Korisnik se odjavio sa racuna.', 27),
(164, '2022-06-17 13:59:36', 'Korisnik se prijavio na racun.', 23),
(165, '2022-06-17 16:31:50', 'Dodana/ažurirana je recenzija za vijest __', 23),
(166, '2022-06-17 16:35:26', 'Dodana/ažurirana je recenzija za vijest __', 23),
(167, '2022-06-17 16:35:36', 'Korisnik se odjavio sa racuna.', 23),
(168, '2022-06-17 16:35:38', 'Korisnik se prijavio na racun.', 2),
(169, '2022-06-17 16:36:10', 'Korisnik se odjavio sa racuna.', 2),
(170, '2022-06-17 16:36:15', 'Korisnik se prijavio na racun.', 23),
(171, '2022-06-17 16:36:33', 'Dodana/ažurirana je recenzija za vijest __', 23),
(172, '2022-06-17 16:39:12', 'Dodana/ažurirana je recenzija za vijest __', 23),
(173, '2022-06-17 16:40:07', 'Dodana/ažurirana je recenzija za vijest __', 23),
(174, '2022-06-17 16:40:29', 'Dodana/ažurirana je recenzija za vijest __', 23),
(175, '2022-06-17 16:40:32', 'Dodana/ažurirana je recenzija za vijest __', 23),
(176, '2022-06-17 16:41:49', 'Dodana/ažurirana je recenzija za vijest _Lorem ipsum_', 23),
(177, '2022-06-17 16:46:32', 'Dodana/ažurirana je recenzija za vijest _Lorem ipsum_', 23),
(178, '2022-06-17 16:48:13', 'Korisnik se odjavio sa racuna.', 23),
(179, '2022-06-17 16:48:19', 'Korisnik se prijavio na racun.', 27),
(180, '2022-06-17 17:30:08', 'Dodana je vijest _Lorem ipsum_', 27),
(181, '2022-06-17 17:31:33', 'Dodana je vijest _Lorem ipsum_', 27),
(182, '2022-06-17 17:32:29', 'Dodana je vijest _Lorem ipsum_', 27),
(183, '2022-06-17 17:32:59', 'Dodana je vijest _Lorem ipsum_', 27),
(184, '2022-06-17 17:37:48', 'Ažurirana je vijest _Lorem ipsum_', 27),
(185, '2022-06-17 17:38:41', 'Korisnik se odjavio sa racuna.', 27),
(186, '2022-06-17 17:38:45', 'Korisnik se prijavio na racun.', 23),
(187, '2022-06-17 17:38:52', 'Korisnik se odjavio sa racuna.', 23),
(188, '2022-06-17 17:38:54', 'Korisnik se prijavio na racun.', 2),
(189, '2022-06-17 17:39:05', 'Dodjeljeno je recenzentu w vijest __', 2),
(190, '2022-06-17 17:39:07', 'Korisnik se odjavio sa racuna.', 2),
(191, '2022-06-17 17:39:10', 'Korisnik se prijavio na racun.', 23),
(192, '2022-06-17 17:39:32', 'Dodana/ažurirana je recenzija za vijest _Lorem ipsum_', 23),
(193, '2022-06-17 17:39:33', 'Korisnik se odjavio sa racuna.', 23),
(194, '2022-06-17 17:46:49', 'Korisnik se prijavio na racun.', 23),
(195, '2022-06-17 17:49:10', 'Korisnik se odjavio sa racuna.', 23),
(196, '2022-06-17 17:49:14', 'Korisnik se prijavio na racun.', 27),
(197, '2022-06-17 17:58:03', 'Korisnik se odjavio sa racuna.', 27),
(198, '2022-06-17 17:58:10', 'Korisnik se prijavio na racun.', 23),
(199, '2022-06-17 18:33:03', 'Korisnik se odjavio sa racuna.', 23),
(200, '2022-06-17 18:33:06', 'Korisnik se prijavio na racun.', 27),
(201, '2022-06-17 18:34:47', 'Korisnik se odjavio sa racuna.', 27),
(202, '2022-06-17 18:41:24', 'Korisnik se prijavio na racun.', 23),
(203, '2022-06-17 18:41:42', 'Korisnik se odjavio sa racuna.', 23),
(204, '2022-06-17 18:41:46', 'Korisnik se prijavio na racun.', 27),
(205, '2022-06-17 18:42:14', 'Ažurirana je vijest _vijest kategorije a_', 27),
(206, '2022-06-17 18:42:36', 'Korisnik se odjavio sa racuna.', 27),
(207, '2022-06-17 18:42:38', 'Korisnik se prijavio na racun.', 23),
(208, '2022-06-17 18:43:15', 'Korisnik se odjavio sa racuna.', 23),
(209, '2022-06-17 18:43:22', 'Korisnik se prijavio na racun.', 2),
(210, '2022-06-17 18:46:51', 'Korisnik se odjavio sa racuna.', 2),
(211, '2022-06-17 18:46:54', 'Korisnik se prijavio na racun.', 23),
(212, '2022-06-17 18:47:02', 'Korisnik se prijavio na racun.', 23),
(213, '2022-06-17 18:47:22', 'Korisnik se odjavio sa racuna.', 23),
(214, '2022-06-17 18:47:28', 'Korisnik se prijavio na racun.', 2),
(215, '2022-06-17 18:47:42', 'Korisnik se odjavio sa racuna.', 2),
(216, '2022-06-17 18:47:45', 'Korisnik se prijavio na racun.', 27),
(217, '2022-06-17 18:47:59', 'Ažurirana je vijest _asd_', 27),
(218, '2022-06-17 18:49:52', 'Dodana je vijest _asd_', 27),
(219, '2022-06-17 18:50:18', 'Ažurirana je vijest _Lorem ipsum_', 27),
(220, '2022-06-17 18:50:36', 'Korisnik se odjavio sa racuna.', 27),
(221, '2022-06-17 20:27:55', 'Korisnik se prijavio na racun.', 23),
(222, '2022-06-17 23:17:57', 'Korisnik se prijavio na racun.', 23),
(223, '2022-06-17 23:18:09', 'Korisnik se odjavio sa racuna.', 23),
(224, '2022-06-17 23:18:15', 'Korisnik se prijavio na racun.', 2),
(225, '2022-06-17 23:33:56', 'Korisnik a nije više blokiran u kategoriji _uz_', 2),
(226, '2022-06-17 23:34:31', 'Korisnik a nije više blokiran u kategoriji _uz_', 2),
(227, '2022-06-17 23:35:39', 'Korisnik a nije više blokiran u kategoriji _kategorija_', 2),
(228, '2022-06-17 23:37:25', 'Korisnik a nije više blokiran u kategoriji __', 2),
(229, '2022-06-17 23:37:43', 'Korisnik  nije više blokiran u kategoriji __', 2),
(230, '2022-06-17 23:43:07', 'Korisnik a nije više blokiran u kategoriji _uz_', 2),
(231, '2022-06-17 23:44:32', 'Korisnik se odjavio sa racuna.', 2),
(232, '2022-06-17 23:44:33', 'Korisnik se prijavio na racun.', 23),
(233, '2022-06-17 23:44:47', 'Dodana/ažurirana je recenzija za vijest _Lorem ipsum_', 23),
(234, '2022-06-17 23:54:06', 'Korisnik a je blokiran u kategoriji _kategorija_', 23),
(235, '2022-06-18 00:05:22', 'Korisnik se odjavio sa racuna.', 23),
(236, '2022-06-18 12:24:54', 'Korisnik se prijavio na racun.', 2),
(237, '2022-06-18 15:50:52', 'Korisnik se odjavio sa racuna.', 2),
(238, '2022-06-18 15:57:05', 'Registriran je racun i poslan aktivacijski email.', 29),
(239, '2022-06-18 15:57:35', 'Aktiviran je korisnicki racun.', 29),
(240, '2022-06-18 16:05:16', 'Korisnik se odjavio sa racuna.', 2),
(241, '2022-06-18 16:05:23', 'Korisnik se prijavio na racun.', 2),
(242, '2022-06-18 16:06:59', 'Korisnik se odjavio sa racuna.', 2),
(243, '2022-06-18 16:07:06', 'Korisnik se prijavio na racun.', 29),
(244, '2022-06-18 16:07:08', 'Korisnik se odjavio sa racuna.', 29),
(245, '2022-06-18 16:07:13', 'Korisnik se prijavio na racun.', 2),
(246, '2022-06-18 16:11:27', 'Korisnik se odjavio sa racuna.', 2),
(247, '2022-06-19 15:13:37', 'Korisnik se prijavio na racun.', 2),
(248, '2022-06-19 15:39:36', 'Dodjeljeno je korisniku w kategorija _uz_', 2),
(249, '2022-06-19 15:41:33', 'Korisniku anto123 je dodjeljeno pravo _Moderator_', 2),
(250, '2022-06-19 15:41:35', 'Korisniku anto123 je oduzeto pravo _Moderator_', 2),
(251, '2022-06-19 14:57:55', 'Dodjeljeno je recenzentu w vijest __', 2),
(252, '2022-06-19 14:57:59', 'Dodjeljeno je recenzentu w vijest __', 2),
(253, '2022-06-19 14:58:12', 'Korisnik se odjavio sa racuna.', 2),
(254, '2022-06-19 15:00:25', 'Korisnik se prijavio na racun.', 23),
(255, '2022-06-19 15:17:29', 'Korisnik se prijavio na racun.', 23),
(256, '2022-06-19 15:21:05', 'Korisnik se prijavio na racun.', 23),
(257, '2022-06-19 15:21:58', 'Korisnik se prijavio na racun.', 23),
(258, '2022-06-19 15:27:59', 'Korisnik a je blokiran u kategoriji _kategorija_', 23),
(259, '2022-06-19 15:48:13', 'Korisnik se odjavio sa racuna.', 23),
(260, '2022-06-19 15:48:19', 'Korisnik se prijavio na racun.', 2),
(261, '2022-06-19 15:48:50', 'Korisnik a nije više blokiran u kategoriji _kategorija_', 2),
(262, '2022-06-19 15:51:32', 'Korisnik se odjavio sa racuna.', 2),
(263, '2022-06-19 15:51:35', 'Korisnik se prijavio na racun.', 23),
(264, '2022-06-19 16:08:16', 'Korisnik a je blokiran u kategoriji _kategorija_', 23),
(265, '2022-06-19 17:43:51', 'Korisnik se odjavio sa racuna.', 23),
(266, '2022-06-19 17:43:58', 'Korisnik se prijavio na racun.', 29),
(267, '2022-06-19 18:02:50', 'Dodana je vijest _CSS_', 29),
(268, '2022-06-19 18:30:36', 'Korisnik se odjavio sa racuna.', 29),
(269, '2022-06-19 18:30:44', 'Korisnik se prijavio na racun.', 2),
(270, '2022-06-19 18:33:53', 'Korisnik se odjavio sa racuna.', 2),
(271, '2022-06-19 18:33:56', 'Korisnik se prijavio na racun.', 23),
(272, '2022-06-19 18:53:11', 'Korisnik se odjavio sa racuna.', 23),
(273, '2022-06-19 18:53:15', 'Korisnik se prijavio na racun.', 2),
(274, '2022-06-19 18:53:24', 'Unesena je kategorija _nova_', 2),
(275, '2022-06-19 18:53:31', 'Obrisana je kategorija _nova_', 2),
(276, '2022-06-19 18:56:43', 'Dodjeljeno je recenzentu w vijest __', 2),
(278, '2022-06-19 21:58:45', 'Registriran je racun i poslan aktivacijski email.', 30),
(279, '2022-06-19 21:58:58', 'Aktiviran je korisnicki racun.', 30),
(280, '2022-06-19 21:59:10', 'Korisnik se prijavio na racun.', 30),
(281, '2022-06-19 21:59:30', 'Korisnik se odjavio sa racuna.', 30),
(282, '2022-06-19 22:01:06', 'Registriran je racun i poslan aktivacijski email.', 31),
(283, '2022-06-19 22:01:45', 'Aktiviran je korisnicki racun.', 31),
(284, '2022-06-19 22:01:57', 'Korisnik se prijavio na racun.', 31),
(285, '2022-06-19 22:02:03', 'Korisnik se odjavio sa racuna.', 31),
(286, '2022-06-19 22:02:56', 'Registriran je racun i poslan aktivacijski email.', 32),
(287, '2022-06-19 22:03:09', 'Aktiviran je korisnicki racun.', 32),
(288, '2022-06-19 22:03:25', 'Korisnik se prijavio na racun.', 32),
(289, '2022-06-19 22:03:26', 'Korisnik se odjavio sa racuna.', 32),
(290, '2022-06-19 22:03:42', 'Korisnik se prijavio na racun.', 2),
(291, '2022-06-19 22:05:44', 'Korisnik se odjavio sa racuna.', 2),
(292, '2022-06-19 22:06:45', 'Registriran je racun i poslan aktivacijski email.', 33),
(293, '2022-06-19 22:07:02', 'Aktiviran je korisnicki racun.', 33),
(294, '2022-06-19 22:09:06', 'Korisnik se prijavio na racun.', 2),
(295, '2022-06-19 22:09:19', 'Korisniku neaktiviran je dodjeljeno pravo _Registrirani korisnik_', 2),
(296, '2022-06-19 22:09:38', 'Korisniku neaktiviran je dodjeljeno pravo _Moderator_', 2),
(297, '2022-06-19 22:09:54', 'Korisniku neaktiviran je oduzeto pravo _Moderator_', 2),
(298, '2022-06-19 22:12:29', 'Korisniku neaktiviran je dodjeljeno pravo _Moderator_', 2),
(299, '2022-06-19 22:12:33', 'Korisniku neaktiviran je oduzeto pravo _Moderator_', 2),
(300, '2022-06-19 22:14:39', 'Korisniku mod je dodjeljeno pravo _Moderator_', 2),
(301, '2022-06-19 22:15:03', 'Korisnik se odjavio sa racuna.', 2),
(302, '2022-06-19 22:15:12', 'Korisnik se prijavio na racun.', 31),
(303, '2022-06-19 22:15:22', 'Korisnik se odjavio sa racuna.', 31),
(304, '2022-06-19 22:15:27', 'Korisnik se prijavio na racun.', 2),
(305, '2022-06-19 22:15:34', 'Korisniku admin je dodjeljeno pravo _Moderator_', 2),
(306, '2022-06-19 22:15:40', 'Korisniku admin je dodjeljeno pravo _Administrator_', 2),
(307, '2022-06-19 22:18:16', 'Unesena je kategorija _sport_', 2),
(308, '2022-06-19 22:18:23', 'Unesena je kategorija _vijesti_', 2),
(309, '2022-06-19 22:18:28', 'Unesena je kategorija _hrana_', 2),
(310, '2022-06-19 22:18:39', 'Unesena je kategorija _astrologija_', 2),
(311, '2022-06-19 22:18:46', 'Unesena je kategorija _svijet_', 2),
(312, '2022-06-19 22:19:02', 'Dodjeljeno je korisniku mod kategorija _hrana_', 2),
(313, '2022-06-19 22:19:19', 'Korisnik se odjavio sa racuna.', 2),
(314, '2022-06-19 22:19:34', 'Korisnik se prijavio na racun.', 31),
(315, '2022-06-19 22:20:29', 'Dodana je vijest _Hrana_', 31),
(316, '2022-06-19 22:22:07', 'Dodana je vijest _Sport_', 31),
(317, '2022-06-19 22:28:19', 'Korisnik se odjavio sa racuna.', 31),
(318, '2022-06-19 22:28:24', 'Korisnik se prijavio na racun.', 30),
(319, '2022-06-19 22:28:40', 'Dodjeljeno je recenzentu registrirani vijest __', 30),
(320, '2022-06-19 22:28:57', 'Dodjeljeno je recenzentu mod vijest __', 30),
(321, '2022-06-19 22:29:02', 'Dodjeljeno je recenzentu mod vijest __', 30),
(322, '2022-06-19 22:29:11', 'Dodjeljeno je recenzentu mod vijest __', 30),
(323, '2022-06-19 22:29:16', 'Korisnik se odjavio sa racuna.', 30),
(324, '2022-06-19 22:29:21', 'Korisnik se prijavio na racun.', 32),
(325, '2022-06-19 22:30:06', 'Dodana/ažurirana je recenzija za vijest _Hrana_', 32),
(326, '2022-06-19 22:30:08', 'Korisnik se odjavio sa racuna.', 32),
(327, '2022-06-19 22:30:16', 'Korisnik se prijavio na racun.', 31),
(328, '2022-06-19 22:51:47', 'Ažurirana je vijest _Hrana_', 31),
(329, '2022-06-19 22:51:55', 'Korisnik se odjavio sa racuna.', 31),
(330, '2022-06-19 22:52:00', 'Korisnik se prijavio na racun.', 32),
(331, '2022-06-19 22:52:20', 'Dodana/ažurirana je recenzija za vijest _Hrana_', 32),
(332, '2022-06-19 22:52:22', 'Korisnik se odjavio sa racuna.', 32),
(333, '2022-06-19 22:52:30', 'Korisnik se prijavio na racun.', 31),
(334, '2022-06-19 22:56:20', 'Korisnik se odjavio sa racuna.', 31),
(335, '2022-06-19 22:58:44', 'Korisnik se prijavio na racun.', 31),
(336, '2022-06-19 23:00:37', 'Korisnik se odjavio sa racuna.', 31),
(337, '2022-06-19 23:00:49', 'Korisnik se prijavio na racun.', 31),
(338, '2022-06-19 23:00:52', 'Korisnik se odjavio sa racuna.', 31),
(339, '2022-06-19 23:01:00', 'Korisnik se prijavio na racun.', 31),
(340, '2022-06-19 23:01:07', 'Korisnik se odjavio sa racuna.', 31),
(341, '2022-06-19 23:01:11', 'Korisnik se prijavio na racun.', 32),
(342, '2022-06-19 23:01:36', 'Dodana/ažurirana je recenzija za vijest _Sport_', 32),
(343, '2022-06-19 23:01:58', 'Korisnik registriran je blokiran u kategoriji _sport_', 32),
(344, '2022-06-19 23:02:11', 'Korisnik registriran je blokiran u kategoriji _sport_', 32),
(345, '2022-06-19 23:02:13', 'Korisnik se odjavio sa racuna.', 32),
(346, '2022-06-19 23:02:16', 'Korisnik se prijavio na racun.', 31),
(347, '2022-06-19 23:02:24', 'Korisnik se odjavio sa racuna.', 31),
(348, '2022-06-19 23:02:28', 'Korisnik se prijavio na racun.', 32),
(349, '2022-06-19 23:28:47', 'Korisnik a je blokiran u kategoriji _kategorija_', 32),
(350, '2022-06-19 23:30:22', 'Korisnik se odjavio sa racuna.', 32),
(351, '2022-06-19 23:30:28', 'Korisnik se prijavio na racun.', 30),
(352, '2022-06-19 23:32:42', 'Korisnik je izmjenio postavke sustava', 30),
(353, '2022-06-19 23:33:56', 'Korisnik se odjavio sa racuna.', 30),
(354, '2022-06-19 23:42:31', 'Korisnik se prijavio na racun.', 2),
(355, '2022-06-19 23:42:31', 'Korisnik se odjavio sa racuna.', 2),
(356, '2022-06-19 23:46:18', 'Korisnik je blokiran zbog neuspješnih prijava.', 23),
(357, '2022-06-20 00:05:09', 'Korisnik se prijavio na racun.', 32),
(358, '2022-06-20 00:05:15', 'Korisnik se odjavio sa racuna.', 32),
(359, '2022-06-20 00:05:27', 'Korisnik se prijavio na racun.', 31),
(360, '2022-06-20 00:09:28', 'Korisnik se odjavio sa racuna.', 31),
(361, '2022-06-20 00:10:48', 'Korisnik se prijavio na racun.', 31),
(362, '2022-06-20 00:24:56', 'Korisnik se odjavio sa racuna.', 31),
(363, '2022-06-20 00:25:01', 'Korisnik se prijavio na racun.', 32),
(364, '2022-06-20 00:26:15', 'Korisnik se odjavio sa racuna.', 32),
(365, '2022-06-20 00:26:18', 'Korisnik se prijavio na racun.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `kategorija`
--

CREATE TABLE `kategorija` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategorija`
--

INSERT INTO `kategorija` (`id`, `naziv`) VALUES
(23, 'a'),
(24, 'asd'),
(31, 'asdf'),
(42, 'astrologija'),
(41, 'hrana'),
(34, 'kategorija'),
(39, 'sport'),
(43, 'svijet'),
(21, 'uiztrer'),
(19, 'uz'),
(40, 'vijesti'),
(25, 'z');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `ime` varchar(45) DEFAULT NULL,
  `prezime` varchar(45) DEFAULT NULL,
  `kor_ime` varchar(45) NOT NULL,
  `lozinka` text NOT NULL,
  `lozinka_hash` text NOT NULL,
  `datum_kreiranja` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(45) NOT NULL,
  `uloga_id` int(11) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `uvijeti_koristenja` tinyint(1) DEFAULT '0',
  `broj_neuspjesnih_pokusaja` int(11) DEFAULT NULL,
  `aktivacijski_kod` varchar(6) DEFAULT NULL,
  `vrijeme_slanja` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `ime`, `prezime`, `kor_ime`, `lozinka`, `lozinka_hash`, `datum_kreiranja`, `email`, `uloga_id`, `status`, `uvijeti_koristenja`, `broj_neuspjesnih_pokusaja`, `aktivacijski_kod`, `vrijeme_slanja`) VALUES
(2, 'Matej', 'Sitaric', 'root', 'root', '6ffdb5721d5b2a8f0e9306967b309aab8a7cbfc54e05803c93eca14c5712398f', '2022-06-19 21:26:18', 'msitaric@foi.hr', 1, 'aktiviran', 1, 0, '3BVdjw', '0000-00-00 00:00:00'),
(23, 'w', 'w', 'w', 'w', '364ed073a6f56e48def9003aae610cc3080bd5ace21b9d2a4dbe10743a032d66', '2022-06-19 20:46:18', 'yipape1830@krunsea.com', 2, 'blokiran', 1, 3, 'WrgSqq', '2022-06-03 11:06:59'),
(25, 'm', 'm', 'm', 'm', '7f40bbc4c9b30e10bdd8ae0b171e07d80fce0380918966f6bbf60db32c316fa6', '2022-06-17 11:59:14', 'xeqiwosy@forexnews.bg', 2, 'aktiviran', 1, 0, 'W62LGV', '2022-06-03 14:06:22'),
(26, 'k', 'k', 'k', 'k', '72bc37215d7f6368959121bee9709167437651efa3b40ab4dd614ba0eb5afaae', '2022-06-04 12:17:36', 'cepsevekku@vusra.com', 3, 'aktiviran', 1, 0, '4XfnZD', '2022-06-04 14:06:18'),
(27, 'a', 'a', 'a', 'a', 'cb7e810a87d8d361494d0245f9d1a7a55a6409cb9a0543bb09a8c0d863d608d4', '2022-06-19 20:44:53', 'tipsacaspi@vusra.com', 3, 'aktiviran', 1, 1, 'rNAiBp', '2022-06-06 10:06:38'),
(28, 'b', 'b', 'b', 'b', 'e2719db0ded82753baddd246c22302ae56311f3548615ac2c00f32cc0bbdc2c6', '2022-06-06 09:11:32', 'gestuhudra@vusra.com', 3, 'aktiviran', 1, 0, 'jF9VzJ', '2022-06-06 11:06:13'),
(29, 'Ante', 'Andric', 'anto123', 'anto123', 'e692ca4967d52009adb433cee24952141af8d98268d0bc56bde04620e897f538', '2022-06-19 11:41:35', 'mitesa9402@runqx.com', 3, 'aktiviran', 1, 0, 'uaJqbm', '2022-06-18 15:57:05'),
(30, 'pero', 'peric', 'admin', 'admin', 'eb91baf5184e34cf516f17b7bcc8790bf9dcfdfc92da25122fe2dbeef9d78ced', '2022-06-19 19:15:36', 'medatel153@tagbert.com', 1, 'aktiviran', 1, 0, 'jPj3rx', '2022-06-19 21:58:45'),
(31, 'marko', 'matic', 'registriran', 'registriran', 'cd959a6f4bbe5333184964038c73792f5c515e0908591da4e8ce4e71271bc82e', '2022-06-19 19:01:45', 'taltupistu@vusra.com', 3, 'aktiviran', 1, 0, 'S3pjAT', '2022-06-19 22:01:06'),
(32, 'ranko', 'radic', 'mod', 'mod', 'f2af9dd8c247fbdd547ef9db8abe50d69a4b201f54a403350cb4f83dadaa2ec3', '2022-06-19 19:14:39', 'voknumugne@vusra.com', 2, 'aktiviran', 1, 0, 'gWnF8Q', '2022-06-19 22:02:56'),
(33, 'andro', 'ankic', 'neaktiviran', 'aktiviran', '09df6247a319d20b4caa420eac564fbcafe3b37cfbcef2f926dff8e17c3f30b3', '2022-06-19 19:12:33', 'dedrizugnu@vusra.com', 3, 'aktiviran', 1, 0, '0V9Kg8', '2022-06-19 22:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici_has_kategorija`
--

CREATE TABLE `korisnici_has_kategorija` (
  `korisnici_id` int(11) NOT NULL,
  `kategorija_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnici_has_kategorija`
--

INSERT INTO `korisnici_has_kategorija` (`korisnici_id`, `kategorija_id`) VALUES
(23, 19),
(23, 23),
(25, 23),
(27, 23),
(23, 24),
(25, 24),
(28, 24),
(23, 34),
(32, 41);

-- --------------------------------------------------------

--
-- Table structure for table `odbijeno`
--

CREATE TABLE `odbijeno` (
  `id` int(11) NOT NULL,
  `blokirani_korisnik` int(11) NOT NULL DEFAULT '0',
  `razlog` text,
  `datum` timestamp NULL DEFAULT NULL,
  `vijest` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recenzija`
--

CREATE TABLE `recenzija` (
  `id` int(11) NOT NULL,
  `nedostatci` text,
  `komentar` text,
  `cinjenicne_pogreske` text,
  `gramaticke_pogreske` text,
  `nedostatak_materijala` tinyint(4) DEFAULT NULL,
  `nedostatak_izvora` tinyint(4) DEFAULT NULL,
  `vijest` int(11) NOT NULL,
  `recenzent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recenzija`
--

INSERT INTO `recenzija` (`id`, `nedostatci`, `komentar`, `cinjenicne_pogreske`, `gramaticke_pogreske`, `nedostatak_materijala`, `nedostatak_izvora`, `vijest`, `recenzent`) VALUES
(1, 'a', 'a', 'a', 'a', 1, 1, 3, 23),
(2, 'asd', 'kom', 'asd', 'asd', 1, 1, 6, 23),
(3, 'sve', 'odbijeno', '10', '10', 1, 0, 9, 23),
(4, 'asd', 'ide na doradu', 'asd', 'asd', 0, 0, 4, 23),
(5, 'asd', 'jkl', 'asd', 'asd', 0, 0, 5, 23),
(6, NULL, NULL, NULL, NULL, NULL, NULL, 11, 23),
(7, NULL, NULL, NULL, NULL, NULL, NULL, 12, 23),
(8, NULL, NULL, NULL, NULL, NULL, NULL, 18, 23),
(9, '', 'super', '-', '-', 0, 0, 19, 32),
(10, '', 'ne svi?aš mi se', '', '', 1, 1, 20, 32),
(11, NULL, NULL, NULL, NULL, NULL, NULL, 7, 32);

-- --------------------------------------------------------

--
-- Table structure for table `status_vijesti`
--

CREATE TABLE `status_vijesti` (
  `id` int(11) NOT NULL,
  `naziv` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status_vijesti`
--

INSERT INTO `status_vijesti` (`id`, `naziv`) VALUES
(1, 'prihvacena'),
(2, 'odbijena'),
(3, 'recenzija'),
(4, 'idenadoradu');

-- --------------------------------------------------------

--
-- Table structure for table `uloga`
--

CREATE TABLE `uloga` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `opis` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uloga`
--

INSERT INTO `uloga` (`id`, `naziv`, `opis`) VALUES
(1, 'Administrator', 'Kreira/Pregledava/Ažurira kategorije vijesti (politika, sport, hrana, …) i dodjeljuje\r\nmoderatore kategoriji vijesti.\r\n\r\nVidi popis vijesti u statusu recenzija i definira recenzenta za vijest. Može odabrati samo\r\nrecenzenta iz popisa moderatora koji su zaduženi za kategoriju u kojoj je vijest. Može\r\npromjeniti recenzenta sve dok vijest nije prihva?ena ili odbijena.'),
(2, 'Moderator', 'Vidi popis vijesti u statusu recenzija koje su mu dodijeljene i može kreirati recenziju. Sva\r\npolja odabrane vijesti su unaprijed popunjena. U recenziji ozna?ava što nedostaje:\r\n?injeni?ne pogreške, gramati?ke pogreške, nedostatak materijala (video/audio),\r\nnedostatak izvora (referenci). Unosi svoj komentar te odre?uje status da li je vijest\r\nprihva?ena, odbijena, recenzija ili ide na doradu. Ažuriranje može raditi dok je status\r\nrecenzija. Za jednu vijest uvijek je samo jedna recenzija.\r\n? Vidi popis vijesti koje su u statusu odbijeno i može blokirati korisnika koji je napisao tu\r\nvijest sa unosom razloga i datuma i vremena do kada je blokiran. Time korisnik ne može\r\npisati vijesti u toj kategoriji. Može promjeniti razlog i datum do kada je blokiran.\r\n\r\nMože tagirati vijesti sa jednom ili više klju?nih rije?i odvojeni znakom “;”\r\n\r\nVidi popis korisnika koji imaju zabranu objavljivanja i može oti?i na ažuriranje zabrane.\r\n\r\nVidi statistiku broja prihva?enih/odbijenih vijesti po autorima vijesti.\r\n'),
(3, 'Registrirani korisnik', 'Pregledava vijesti koje je kreirao. Može kreirati/ažurirati vijest pri ?emu bira kategoriju,\r\nunosi naslov, autora/e, tekst ?lanka, URL do izvora (opcionalno), datum i vrijeme objave\r\n(automatski se unosi), te prilaže sliku, a opcionalno video/audio. Verziju vijesti se\r\nautomatski pove?ava za jedan kod svakog ažuriranja. Odmah pri kreiranju ili ažuriranju\r\nstatus vijesti postaje “recenzija”. Ažuriranje može raditi dok je status dorada. Ukoliko ima\r\nzabranu ne može objaviti novu vijest sve dok moderator ne ukine zabranu.\r\n\r\nVidi popis kategorija u kojima je blokiran za pisanje vijesti sa informacijom do kada.\r\n\r\nVidi sve svoje recenzije s informacijom na koju vijest se vežu i statusom same vijesti te može\r\noti?i na ažuriranje vijesti ako je vijest u statusu dorada. Posebno su ozna?ene recenzije vijesti\r\nkoje su u statusu dorada.\r\n\r\nPregledava statistiku broja pregleda svojih vijesti.\r\n'),
(4, 'Neregistrirani korisnik', 'Vidi rang listu vijesti prema broju pregleda u vremenskom razdoblju (od-do).\r\n\r\nVidi prihva?ene vijesti u obliku galerije slika uz mogu?nost sortiranja po kategoriji vijesti ili\r\nbroju pregleda i može filtrirati po tagovima.');

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

CREATE TABLE `vijesti` (
  `id` int(11) NOT NULL,
  `naslov` varchar(45) NOT NULL,
  `sadrzaj` text NOT NULL,
  `datum_kreiranja` timestamp NULL DEFAULT NULL,
  `izvor` text NOT NULL,
  `kategorija` int(11) NOT NULL DEFAULT '0',
  `autor` int(11) NOT NULL,
  `slika` text NOT NULL,
  `verzija` int(11) DEFAULT '1',
  `tagiranje` text,
  `audio` text,
  `video` text,
  `status_vijesti` int(11) NOT NULL,
  `broj_pregleda` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `naslov`, `sadrzaj`, `datum_kreiranja`, `izvor`, `kategorija`, `autor`, `slika`, `verzija`, `tagiranje`, `audio`, `video`, `status_vijesti`, `broj_pregleda`) VALUES
(3, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsumLorem.png16.06.2022.', 1, 'lorem;ipsum;tag1', '', '', 1, 5),
(4, 'Lorem ipsum', '                    a', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/17.06.2022.Lorem.png', 2, 'lorem;ipsum;tag1', '', '', 3, 0),
(5, 'Lorem ipsum', '                    a', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/17.06.2022.Lorem.png', 2, 'lorem;ipsum;tag1', '', '', 2, 0),
(6, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsum/Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 1, 0),
(7, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsum/Lorem.png16.06.2022.', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(8, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsum/Lorem.png16.06.2022.', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(9, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsum/16.06.2022.Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 2, 0),
(10, 'Lorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-16 18:46:20', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 16.06.2022. - Lorem ipsum/16.06.2022.Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(11, 'Naslov', 'sadržaj', '2022-06-16 19:32:16', 'izvor', 25, 27, '../materijali/vijest 16.06.2022. - Naslov/Lorem.png', 1, 'tag2;test', '', '', 3, 0),
(12, 'Prvi uspjeh?', 'Nadam se da ?e ova vijest biti prva koja ?e sve uspješno upisati.', '2022-06-16 19:38:04', 'Moj izvor', 19, 27, '../materijali/vijest 16.06.2022. - Prvi uspjeh?/Lorem.png', 1, 'uspjeh;nada;pokusaj', '../materijali/vijest 16.06.2022. - Prvi uspjeh?/testni zvuk.mp3', '../materijali/vijest 16.06.2022. - Prvi uspjeh?/testni video.mp4', 3, 10),
(13, 'Lorem ipsum', '                    azurirano', '2022-06-17 15:29:43', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(14, 'Lorem ipsum', '                    azurirano', '2022-06-17 15:31:13', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/17.06.2022.Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(15, 'Lorem ipsum', '                    az', '2022-06-17 15:32:11', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/17.06.2022.Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(16, 'Lorem ipsum', '                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2022-06-17 15:32:51', 'https://www.lipsum.com/', 34, 27, '../materijali/vijest 17.06.2022. - Lorem ipsum/17.06.2022.Lorem.png', 1, 'lorem;ipsum;tag1', '', '', 3, 0),
(17, 'asd', '                    asd', '2022-06-17 16:49:40', '', 23, 27, '../materijali/vijest 17.06.2022. - asd/17.06.2022.Lorem.png', 1, '', '', '', 3, 0),
(18, 'CSS', 'saržaj css-a', '2022-06-19 15:59:05', 'nema ga', 21, 29, '../materijali/vijest 19.06.2022. - CSS/Lorem.png', 1, 'css;webdip', '../materijali/vijest 19.06.2022. - CSS/testni zvuk.mp3', '../materijali/vijest 19.06.2022. - CSS/testni video.mp4', 3, 0),
(19, 'Hrana', 'Hrana je bilo koja tvar koja apsorpcijom u ljudskom organizmu doprinosi o?uvanju homeostaze istog.\r\nNova re?enica', '2022-06-19 20:19:37', 'https://hr.wikipedia.org/wiki/Hrana', 41, 31, '../materijali/vijest 19.06.2022. - Hrana/19.06.2022.Lorem.png', 2, 'hrana;wiki;wikipedia', '../materijali/vijest 19.06.2022. - Hrana/19.06.2022.testni zvuk.mp3', '../materijali/vijest 19.06.2022. - Hrana/19.06.2022.testni video.mp4', 1, 4),
(20, 'Sport', 'Sportski sport', '2022-06-19 20:20:43', '', 39, 31, '../materijali/vijest 19.06.2022. - Sport/Lorem.png', 1, 'sport', '', '', 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DZ4_dnevnik`
--
ALTER TABLE `DZ4_dnevnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_DZ4_dne_DZ4_korisnici1_idx` (`DZ4_korisnici_id`),
  ADD KEY `fk_DZ4_dne_DZ4_uloge1_idx` (`DZ4_uloge_id`);

--
-- Indexes for table `DZ4_korisnici`
--
ALTER TABLE `DZ4_korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_DZ4_korisnici_DZ4_uloge_idx` (`DZ4_uloge_id`);

--
-- Indexes for table `DZ4_uloge`
--
ALTER TABLE `DZ4_uloge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `blokirani_u_kategoriji`
--
ALTER TABLE `blokirani_u_kategoriji`
  ADD PRIMARY KEY (`kategorija_id`,`blokiran_korisnik_id`),
  ADD KEY `fk_kategorija_has_korisnici_korisnici1_idx` (`blokiran_korisnik_id`),
  ADD KEY `fk_kategorija_has_korisnici_kategorija1_idx` (`kategorija_id`);

--
-- Indexes for table `dnevnik_rada`
--
ALTER TABLE `dnevnik_rada`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_dnevnik_rada_korisnici1_idx` (`korisnici_id`);

--
-- Indexes for table `kategorija`
--
ALTER TABLE `kategorija`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `naziv` (`naziv`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kor_ime_UNIQUE` (`kor_ime`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_korisnici_uloga_idx` (`uloga_id`);

--
-- Indexes for table `korisnici_has_kategorija`
--
ALTER TABLE `korisnici_has_kategorija`
  ADD PRIMARY KEY (`korisnici_id`,`kategorija_id`),
  ADD KEY `fk_korisnici_has_kategorija_kategorija1_idx` (`kategorija_id`),
  ADD KEY `fk_korisnici_has_kategorija_korisnici1_idx` (`korisnici_id`);

--
-- Indexes for table `odbijeno`
--
ALTER TABLE `odbijeno`
  ADD PRIMARY KEY (`id`,`blokirani_korisnik`,`vijest`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_odbijeno_korisnici1_idx` (`blokirani_korisnik`),
  ADD KEY `fk_odbijeno_vijesti1_idx` (`vijest`);

--
-- Indexes for table `recenzija`
--
ALTER TABLE `recenzija`
  ADD PRIMARY KEY (`id`,`vijest`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_recenzija_vijesti1_idx` (`vijest`),
  ADD KEY `fk_recenzija_korisnici1_idx` (`recenzent`);

--
-- Indexes for table `status_vijesti`
--
ALTER TABLE `status_vijesti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `uloga`
--
ALTER TABLE `uloga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `naziv_UNIQUE` (`naziv`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`,`kategorija`,`autor`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_vijesti_kategorija1_idx` (`kategorija`),
  ADD KEY `fk_vijesti_korisnici1_idx` (`autor`),
  ADD KEY `fk_vijesti_status_vijesti1_idx` (`status_vijesti`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DZ4_dnevnik`
--
ALTER TABLE `DZ4_dnevnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DZ4_korisnici`
--
ALTER TABLE `DZ4_korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `DZ4_uloge`
--
ALTER TABLE `DZ4_uloge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `dnevnik_rada`
--
ALTER TABLE `dnevnik_rada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;
--
-- AUTO_INCREMENT for table `kategorija`
--
ALTER TABLE `kategorija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `odbijeno`
--
ALTER TABLE `odbijeno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `recenzija`
--
ALTER TABLE `recenzija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `status_vijesti`
--
ALTER TABLE `status_vijesti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `uloga`
--
ALTER TABLE `uloga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `DZ4_dnevnik`
--
ALTER TABLE `DZ4_dnevnik`
  ADD CONSTRAINT `fk_DZ4_dne_DZ4_korisnici1` FOREIGN KEY (`DZ4_korisnici_id`) REFERENCES `DZ4_korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DZ4_dne_DZ4_uloge1` FOREIGN KEY (`DZ4_uloge_id`) REFERENCES `DZ4_uloge` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DZ4_korisnici`
--
ALTER TABLE `DZ4_korisnici`
  ADD CONSTRAINT `fk_DZ4_korisnici_DZ4_uloge` FOREIGN KEY (`DZ4_uloge_id`) REFERENCES `DZ4_uloge` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `blokirani_u_kategoriji`
--
ALTER TABLE `blokirani_u_kategoriji`
  ADD CONSTRAINT `fk_kategorija_has_korisnici_kategorija1` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_kategorija_has_korisnici_korisnici1` FOREIGN KEY (`blokiran_korisnik_id`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `dnevnik_rada`
--
ALTER TABLE `dnevnik_rada`
  ADD CONSTRAINT `fk_dnevnik_rada_korisnici1` FOREIGN KEY (`korisnici_id`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD CONSTRAINT `fk_korisnici_uloga` FOREIGN KEY (`uloga_id`) REFERENCES `uloga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `korisnici_has_kategorija`
--
ALTER TABLE `korisnici_has_kategorija`
  ADD CONSTRAINT `fk_korisnici_has_kategorija_korisnici1` FOREIGN KEY (`korisnici_id`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_korisnici_has_kategorija_kategorija1` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `odbijeno`
--
ALTER TABLE `odbijeno`
  ADD CONSTRAINT `fk_odbijeno_korisnici1` FOREIGN KEY (`blokirani_korisnik`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_odbijeno_vijesti1` FOREIGN KEY (`vijest`) REFERENCES `vijesti` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `recenzija`
--
ALTER TABLE `recenzija`
  ADD CONSTRAINT `fk_recenzija_korisnici1` FOREIGN KEY (`recenzent`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_recenzija_vijesti1` FOREIGN KEY (`vijest`) REFERENCES `vijesti` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD CONSTRAINT `fk_vijesti_kategorija1` FOREIGN KEY (`kategorija`) REFERENCES `kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vijesti_korisnici1` FOREIGN KEY (`autor`) REFERENCES `korisnici` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vijesti_status_vijesti1` FOREIGN KEY (`status_vijesti`) REFERENCES `status_vijesti` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
