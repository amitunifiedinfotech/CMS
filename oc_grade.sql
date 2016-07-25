-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2015 at 07:05 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `team13_expectation`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_grade`
--

CREATE TABLE IF NOT EXISTS `oc_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_meta` varchar(1024) NOT NULL,
  `grade_value` varchar(1024) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `oc_grade`
--

INSERT INTO `oc_grade` (`id`, `grade_meta`, `grade_value`, `status`) VALUES
(1, 'MINT (MT)', '9.9-10.0', 1),
(2, 'NEAR MINT/MINT (NM/MT)', '9.8', 1),
(3, 'NEAR MINT (NM)', '9.2-9.7', 1),
(4, 'VERY FINE/NEAR MINT (VF/NM)', '9.0', 1),
(5, 'VERY FINE (VF)', '7.5-8.5', 1),
(6, 'FINE/VERY FINE (FN/VF)', '7.0', 1),
(7, 'FINE (FN)', '5.5-6.5', 1),
(8, 'VERY GOOD/FINE (VG/FN)', '5.0', 1),
(9, 'VERY GOOD (VG)', '3.5-4.5', 1),
(10, 'GOOD/VERY GOOD (GD/VG)', '3.0', 1),
(11, 'GOOD (GD)', '1.8-2.5', 1),
(12, 'FAIR/GOOD (FR/GD)', '1.5', 1),
(13, 'FAIR (FR)', '1.0', 1),
(14, 'POOR (PR)', '0.5', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
