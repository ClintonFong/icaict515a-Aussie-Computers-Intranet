-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2014 at 03:01 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `icaict515a`
--

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_categories`
--

CREATE TABLE IF NOT EXISTS `icaict515a_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `icaict515a_categories`
--

INSERT INTO `icaict515a_categories` (`category_id`, `name`) VALUES
(1, 'Food'),
(2, 'Stationery'),
(3, 'New Equipment'),
(4, 'Repairs'),
(5, 'Furniture');

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_category_items`
--

CREATE TABLE IF NOT EXISTS `icaict515a_category_items` (
  `category_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `price` double DEFAULT NULL,
  PRIMARY KEY (`category_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `icaict515a_category_items`
--

INSERT INTO `icaict515a_category_items` (`category_item_id`, `category_id`, `name`, `price`) VALUES
(1, 1, 'Biscuits - Assorted', 3),
(2, 1, 'Biscuits - Chocolate Mint', 3.5),
(3, 1, 'Biscuits - Chocolate Chip', 3.5),
(4, 1, 'Chips - Plain', 3),
(5, 1, 'Chips - Chicken', 3),
(6, 1, 'Chips - Barbeque', 3),
(7, 1, 'Chips - Sour Cream & Chives', 3),
(8, 1, 'Cake', 5),
(9, 2, 'Pen - blue(50)', 12.95),
(10, 2, 'Pen - black(50)', 12.95),
(11, 2, 'Pen - red(50)', 12.95),
(12, 2, 'Notepad(pages 50)', 2.35),
(13, 2, 'Post-it Notes (pages 100)', 11.6),
(14, 2, 'Clipboard', 4.15),
(15, 2, 'Folder - Manilla(100)', 16),
(16, 2, 'Folder - Lever Arch', 6),
(17, 3, 'Monitor', 0),
(18, 3, 'Printer', 0),
(19, 3, 'Hard Disk (2T)', 94),
(20, 3, 'Mouse', 10),
(21, 3, 'Keyboard', 15),
(22, 3, 'Laptop', 0),
(23, 3, 'Computer', 0),
(24, 3, 'Phone', 0),
(25, 3, 'Tablet', 0),
(26, 4, 'Monitor', 0),
(27, 4, 'Printer', 0),
(28, 4, 'Laptop', 0),
(29, 4, 'Phone', 0),
(30, 4, 'Tablet', 0),
(31, 5, 'Table', 0),
(32, 5, 'Chair', 0),
(33, 5, 'Shelf', 0),
(34, 5, 'Filing Cabinet', 0);

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_departments`
--

CREATE TABLE IF NOT EXISTS `icaict515a_departments` (
  `department_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `manager_id` int(11) unsigned DEFAULT NULL COMMENT 'foreign_key to employee_id',
  `budget` double DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `icaict515a_departments`
--

INSERT INTO `icaict515a_departments` (`department_id`, `name`, `manager_id`, `budget`) VALUES
(1, 'Computer', 3, 20008.77),
(2, 'Computer Research', 5, 10000),
(3, 'Admin', 3, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_employees`
--

CREATE TABLE IF NOT EXISTS `icaict515a_employees` (
  `employee_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(11) unsigned NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `access_level` int(2) DEFAULT NULL COMMENT '0: Employee, 1: Procurement member, 5: Manager, 9: Aministrator',
  `logged_in` tinyint(1) NOT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `icaict515a_employees`
--

INSERT INTO `icaict515a_employees` (`employee_id`, `department_id`, `firstname`, `lastname`, `email`, `password`, `phone`, `access_level`, `logged_in`) VALUES
(1, 0, 'Clinton', 'Fong', 'info@clintonfong.com', '7c5c97d7f5975f0619edacb9a115c809bd25a245c91738012e14e6c0f5856914', '1111', 9, 0),
(2, 0, 'Tom', 'Jones', 'tom@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '1111', 0, 1),
(3, 1, 'Oliver', 'Queen', 'oliver@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '9900', 5, 0),
(4, 2, 'Peter', 'Marks', 'peter@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '22122', 0, 0),
(5, 0, 'John', 'Kane', 'kane@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '222', 5, 0),
(6, 2, 'Phil', 'Rogers', 'phil@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '4444', 0, 0),
(7, 3, 'Administrator', 'Controller', 'admin@gmail.com', '874d72a526ddc08bd967495b1787d5f4fc26ffbdbc47da46df03bea8d73325c7', '9999999999', 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_orders`
--

CREATE TABLE IF NOT EXISTS `icaict515a_orders` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `manager_id` int(11) unsigned NOT NULL COMMENT 'manager_id is employee_id with manager_level > 0',
  `category_id` int(11) DEFAULT NULL COMMENT 'for Food, Computer Repairs, New Equipment, Stationery, Other Office Item',
  `description` varchar(256) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `order_status` int(2) DEFAULT NULL COMMENT '0: submitted, 1:approved, -1: rejected, 2: order processed by procurement team, -2: saved',
  `revision` int(2) unsigned NOT NULL,
  `datetime_submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `icaict515a_orders`
--

INSERT INTO `icaict515a_orders` (`order_id`, `employee_id`, `manager_id`, `category_id`, `description`, `amount`, `order_status`, `revision`, `datetime_submitted`) VALUES
(1, 4, 5, 1, 'Â first item', 6.5, 0, 0, '2014-08-27 13:16:27'),
(2, 4, 5, 1, 'Â Steak', 10, -1, 0, '2014-08-27 13:18:54'),
(3, 4, 5, 3, 'Â New Equipment', 3403, 1, 0, '2014-08-28 06:53:59'),
(4, 4, 3, 3, 'Â New equipment for office', 1594, 0, 0, '2014-08-28 07:20:23'),
(5, 4, 5, 1, 'Â assorted items', 21, 0, 0, '2014-08-29 02:20:18'),
(6, 4, 5, 1, 'Â food', 13, -1, 0, '2014-08-29 02:24:02'),
(7, 4, 5, 3, 'Â ', 1288, -1, 0, '2014-08-29 02:54:05'),
(8, 6, 5, 1, 'Â item', 6.5, -1, 0, '2014-08-29 03:56:19'),
(9, 6, 5, 1, 'Â test', 18, 1, 0, '2014-08-29 04:59:58'),
(10, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:29:17'),
(11, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:30:06'),
(12, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:32:18'),
(13, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:32:58'),
(14, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:35:55'),
(15, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:36:22'),
(16, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:36:56'),
(17, 6, 5, 1, 'Â ', 9.5, 0, 0, '2014-08-29 10:37:20'),
(18, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:38:14'),
(19, 6, 5, 1, 'Â ', 9.5, -1, 0, '2014-08-29 10:38:49'),
(20, 6, 5, 1, 'Â ', 9.5, -1, 0, '2014-08-29 10:39:14'),
(21, 6, 5, 1, 'Â ', 9.5, -1, 0, '2014-08-29 10:39:37'),
(22, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:39:46'),
(23, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:40:06'),
(24, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:40:23'),
(25, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:40:54'),
(26, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:41:14'),
(27, 6, 5, 1, 'Â ', 9.5, 1, 0, '2014-08-29 10:41:28'),
(28, 6, 5, 4, 'Â ', 1000, 1, 0, '2014-08-29 10:42:18'),
(29, 6, 5, 2, 'Â ', 41.2, 1, 0, '2014-08-29 10:48:36'),
(30, 6, 5, 2, 'Â ', 41.2, 1, 0, '2014-08-29 10:50:06'),
(31, 6, 5, 2, 'Â ', 41.2, 1, 0, '2014-08-29 10:52:13'),
(32, 6, 5, 1, 'Â ', 3, 1, 0, '2014-08-29 10:53:44'),
(33, 6, 5, 1, 'Â ', 3, 1, 0, '2014-08-29 10:54:08'),
(34, 6, 5, 2, 'Â ', 24.55, 1, 0, '2014-08-29 11:05:51'),
(35, 6, 5, 1, 'Â ', 3, 1, 0, '2014-08-29 11:06:32'),
(36, 6, 5, 1, 'Â ', 3.5, 1, 0, '2014-08-29 11:07:21'),
(37, 6, 5, 2, 'Â ', 12.95, 1, 0, '2014-08-29 11:08:52'),
(38, 6, 5, 1, 'Â ', 3, 1, 0, '2014-08-29 11:12:00'),
(39, 6, 5, 1, 'Â ', 3, -1, 0, '2014-08-29 15:36:58'),
(40, 6, 5, 1, 'Â test of size of number of items', 24, 0, 0, '2014-08-30 12:00:04'),
(41, 6, 5, 1, 'Â test of order with 10 order items', 30, 0, 0, '2014-08-30 12:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_order_items`
--

CREATE TABLE IF NOT EXISTS `icaict515a_order_items` (
  `order_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `quantity` int(5) NOT NULL,
  `amount` double NOT NULL,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`order_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=112 ;

--
-- Dumping data for table `icaict515a_order_items`
--

INSERT INTO `icaict515a_order_items` (`order_item_id`, `order_id`, `name`, `quantity`, `amount`, `revision`) VALUES
(1, 1, 'Biscuits - Assorted', 1, 3, 0),
(2, 1, 'Biscuits - Chocolate Mint', 1, 3.5, 0),
(3, 2, 'Other - Steak', 1, 10, 0),
(4, 3, 'Monitor - $200 Quote', 1, 200, 0),
(5, 3, 'Keyboard', 1, 15, 0),
(6, 3, 'Hard Disk (2T)', 2, 188, 0),
(7, 3, 'Computer - $3000 Quote', 1, 3000, 0),
(8, 4, 'Monitor - $1000 Quote', 1, 1000, 0),
(9, 4, 'Hard Disk (2T)', 1, 94, 0),
(10, 4, 'Printer - $500 Quote', 1, 500, 0),
(11, 5, 'Biscuits - Assorted', 1, 3, 0),
(12, 5, 'Biscuits - Assorted', 1, 3, 0),
(13, 5, 'Other - steak', 1, 12, 0),
(14, 5, 'Chips - Plain', 1, 3, 0),
(15, 6, 'Chips - Plain', 1, 3, 0),
(16, 6, 'Other - steak', 1, 10, 0),
(17, 7, 'Hard Disk (2T)', 2, 188, 0),
(18, 7, 'Monitor - $100 Quote', 1, 100, 0),
(19, 7, 'Computer - $1000 Quote', 1, 1000, 0),
(20, 8, 'Biscuits - Assorted', 1, 3, 0),
(21, 8, 'Biscuits - Chocolate Mint', 1, 3.5, 0),
(22, 9, 'Biscuits - Assorted', 1, 3, 0),
(23, 9, 'Other - bread', 3, 15, 0),
(24, 10, 'Biscuits - Assorted', 1, 3, 0),
(25, 10, 'Biscuits - Assorted', 1, 3, 0),
(26, 10, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(27, 11, 'Biscuits - Assorted', 1, 3, 0),
(28, 11, 'Biscuits - Assorted', 1, 3, 0),
(29, 11, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(30, 12, 'Biscuits - Assorted', 1, 3, 0),
(31, 12, 'Biscuits - Assorted', 1, 3, 0),
(32, 12, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(33, 13, 'Biscuits - Assorted', 1, 3, 0),
(34, 13, 'Biscuits - Assorted', 1, 3, 0),
(35, 13, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(36, 14, 'Biscuits - Assorted', 1, 3, 0),
(37, 14, 'Biscuits - Assorted', 1, 3, 0),
(38, 14, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(39, 15, 'Biscuits - Assorted', 1, 3, 0),
(40, 15, 'Biscuits - Assorted', 1, 3, 0),
(41, 15, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(42, 16, 'Biscuits - Assorted', 1, 3, 0),
(43, 16, 'Biscuits - Assorted', 1, 3, 0),
(44, 16, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(45, 17, 'Biscuits - Assorted', 1, 3, 0),
(46, 17, 'Biscuits - Assorted', 1, 3, 0),
(47, 17, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(48, 18, 'Biscuits - Assorted', 1, 3, 0),
(49, 18, 'Biscuits - Assorted', 1, 3, 0),
(50, 18, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(51, 19, 'Biscuits - Assorted', 1, 3, 0),
(52, 19, 'Biscuits - Assorted', 1, 3, 0),
(53, 19, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(54, 20, 'Biscuits - Assorted', 1, 3, 0),
(55, 20, 'Biscuits - Assorted', 1, 3, 0),
(56, 20, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(57, 21, 'Biscuits - Assorted', 1, 3, 0),
(58, 21, 'Biscuits - Assorted', 1, 3, 0),
(59, 21, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(60, 22, 'Biscuits - Assorted', 1, 3, 0),
(61, 22, 'Biscuits - Assorted', 1, 3, 0),
(62, 22, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(63, 23, 'Biscuits - Assorted', 1, 3, 0),
(64, 23, 'Biscuits - Assorted', 1, 3, 0),
(65, 23, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(66, 24, 'Biscuits - Assorted', 1, 3, 0),
(67, 24, 'Biscuits - Assorted', 1, 3, 0),
(68, 24, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(69, 25, 'Biscuits - Assorted', 1, 3, 0),
(70, 25, 'Biscuits - Assorted', 1, 3, 0),
(71, 25, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(72, 26, 'Biscuits - Assorted', 1, 3, 0),
(73, 26, 'Biscuits - Assorted', 1, 3, 0),
(74, 26, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(75, 27, 'Biscuits - Assorted', 1, 3, 0),
(76, 27, 'Biscuits - Assorted', 1, 3, 0),
(77, 27, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(78, 28, 'Tablet - $1000 Quote', 1, 1000, 0),
(79, 29, 'Notepad(pages 50)', 1, 2.35, 0),
(80, 29, 'Pen - red(50)', 3, 38.85, 0),
(81, 30, 'Notepad(pages 50)', 1, 2.35, 0),
(82, 30, 'Pen - red(50)', 3, 38.85, 0),
(83, 31, 'Notepad(pages 50)', 1, 2.35, 0),
(84, 31, 'Pen - red(50)', 3, 38.85, 0),
(85, 32, 'Biscuits - Assorted', 1, 3, 0),
(86, 33, 'Biscuits - Assorted', 1, 3, 0),
(87, 34, 'Post-it Notes (pages 100)', 1, 11.6, 0),
(88, 34, 'Pen - blue(50)', 1, 12.95, 0),
(89, 35, 'Biscuits - Assorted', 1, 3, 0),
(90, 36, 'Biscuits - Chocolate Chip', 1, 3.5, 0),
(91, 37, 'Pen - blue(50)', 1, 12.95, 0),
(92, 38, 'Biscuits - Assorted', 1, 3, 0),
(93, 39, 'Biscuits - Assorted', 1, 3, 0),
(94, 40, 'Biscuits - Assorted', 1, 3, 0),
(95, 40, 'Biscuits - Assorted', 1, 3, 0),
(96, 40, 'Biscuits - Assorted', 1, 3, 0),
(97, 40, 'Biscuits - Assorted', 1, 3, 0),
(98, 40, 'Biscuits - Assorted', 1, 3, 0),
(99, 40, 'Biscuits - Assorted', 1, 3, 0),
(100, 40, 'Biscuits - Assorted', 1, 3, 0),
(101, 40, 'Biscuits - Assorted', 1, 3, 0),
(102, 41, 'Biscuits - Assorted', 1, 3, 0),
(103, 41, 'Biscuits - Assorted', 1, 3, 0),
(104, 41, 'Biscuits - Assorted', 1, 3, 0),
(105, 41, 'Biscuits - Assorted', 1, 3, 0),
(106, 41, 'Biscuits - Assorted', 1, 3, 0),
(107, 41, 'Biscuits - Assorted', 1, 3, 0),
(108, 41, 'Biscuits - Assorted', 1, 3, 0),
(109, 41, 'Biscuits - Assorted', 1, 3, 0),
(110, 41, 'Biscuits - Assorted', 1, 3, 0),
(111, 41, 'Biscuits - Assorted', 1, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `icaict515a_order_notes`
--

CREATE TABLE IF NOT EXISTS `icaict515a_order_notes` (
  `order_note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `note` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`order_note_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `icaict515a_order_notes`
--

INSERT INTO `icaict515a_order_notes` (`order_note_id`, `order_id`, `note`) VALUES
(1, 1, 'Â this is a note'),
(2, 3, 'Â this is a note 22222'),
(3, 2, 'Â this is the third note'),
(4, 1, 'Â '),
(5, 2, 'We do not eat steak during work hours.... :-)'),
(6, 6, 'we don\\''t order steak'),
(7, 7, 'no because...'),
(8, 8, 'too expensice'),
(9, 9, 'fddfggh'),
(10, 20, 'This is a test'),
(11, 19, 'this is a test...'),
(12, 18, 'Approved');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
