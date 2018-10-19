-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2018 at 06:05 PM
-- Server version: 10.2.12-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id6155041_bankdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `acc_no` char(10) NOT NULL,
  `holder` decimal(8,0) NOT NULL,
  `balance` decimal(13,2) DEFAULT NULL,
  `status` enum('frozen','closed','unapproved','active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`acc_no`, `holder`, `balance`, `status`) VALUES
('1122334455', 45454545, 45000.00, 'active'),
('1213415412', 12345679, 19500.00, 'active'),
('1231230987', 12345680, 55500.00, 'unapproved'),
('1234567788', 12345678, 54000.35, 'active'),
('1234567878', 12345678, 19000.00, 'active'),
('1728394466', 99988776, 18000.00, 'active'),
('2323454577', 45454545, 12000.00, 'frozen'),
('5588446622', 12345679, NULL, 'unapproved'),
('6767332211', 33334444, 30000.00, 'closed');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `addr_line1` text NOT NULL,
  `addr_line2` text DEFAULT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `pincode` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `addr_line1`, `addr_line2`, `city`, `state`, `pincode`) VALUES
(4, '201, kalpyog hsg. soc.', 'sahkar nagar, opp. to mane gas', 'thane', 'maharashtra', '400606'),
(6, '402, Nilkantha heights', 'devdaya nagar', 'Thane', 'Maharashtra', '400607'),
(8, '304, sky view', 'gokhle road', 'Pune', 'Maharashtra', '400612'),
(9, '203, nilkanth heights, ', '', 'Mumbai', 'Maharashtra', '400602'),
(11, 'tandala', '', 'Mumbai', 'maharashtra', '400022'),
(12, 'sion,pratiksha nagar', '', 'Mumbai', 'maharashtra', '400022'),
(13, '2/33 Madhavi Society', 'Mogal Lane, Mahim(W)', 'Mumbai', 'Maharashtra', '400016'),
(14, 'wadala(E)', '', 'Pune', 'maharashtra', '400033'),
(16, 'Home', 'Address', 'Thane', 'Maharashtra', '622722');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` decimal(8,0) NOT NULL,
  `f_name` varchar(30) NOT NULL,
  `m_name` varchar(30) DEFAULT NULL,
  `l_name` varchar(30) NOT NULL,
  `perm_addr` bigint(20) UNSIGNED NOT NULL,
  `cors_addr` bigint(20) UNSIGNED DEFAULT NULL,
  `mobile` char(10) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `f_name`, `m_name`, `l_name`, `perm_addr`, `cors_addr`, `mobile`, `email`, `gender`, `dob`) VALUES
(12345671, 'Jayesh', 'Manik', 'Mandlik', 16, 16, '9920013150', 'jayesh.mandlik@vit.edu.in', 'male', '1999-08-18'),
(12345678, 'Akshat', 'Girdhari', 'Raut', 4, 4, '7715887414', 'aks13raut@gmail.com', 'male', '1999-06-13'),
(12345679, 'manish', 'ramarao', 'pethe', 6, 6, '9861387811', 'manish@outllook.com', 'male', '1970-11-21'),
(12345680, 'saurabh', 'suchivrat', 'daware', 8, 8, '9029455879', 'saudawre@gmail.com', 'male', '1998-04-22'),
(12345682, 'RAJ', 'malhotra', 'war', 11, 11, '9898972872', 'amsksjdjas@gmail.com', 'male', '1997-02-25'),
(33334444, 'SHUBHAM', 'RAVINDRA', 'DASARWAR', 12, 12, '9876563412', 'shubham123@gmail.com', 'male', '1997-12-02'),
(45454545, 'ABHIJEET', 'MADHAVRAO', 'JADHAV', 14, 14, '9845672311', 'abhi@gmail.com', 'male', '1999-01-03'),
(99988776, 'Mohit', 'Sachchidanand', 'Kambli', 13, 13, '9820935639', 'mohitkambli8@gmail.com', 'male', '1998-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `tid` bigint(20) UNSIGNED ZEROFILL NOT NULL,
  `from_acc` char(10) NOT NULL,
  `to_acc` char(10) NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`tid`, `from_acc`, `to_acc`, `amount`, `date_time`) VALUES
(00000000000000000001, '1231230987', '1213415412', 20000.00, '2018-10-08 20:12:39'),
(00000000000000000002, '1728394466', '1234567878', 5000.00, '2018-10-09 07:07:31'),
(00000000000000000003, '2323454577', '1234567788', 356.45, '2018-10-01 02:04:18'),
(00000000000000000004, '6767332211', '1728394466', 1200.00, '2018-10-08 09:04:01'),
(00000000000000000005, '1213415412', '1231230987', 500.00, '2018-10-18 16:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pswd` varbinary(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `pswd`) VALUES
(0, 'admin', 0x34e0d10dc7378011d50aa0fa2f81e7c2),
(5, 'aks13raut@gmail.com', 0x9aed13e7efe479ba5fbf227221cefeb9),
(8, 'manish@outllook.com', 0x05d1b860173c177c54e316663c3ac670),
(11, 'saudawre@gmail.com', 0x4c0c4a5b56eca7e7c0d38e409b165252),
(12, 'ashwini@email.com', 0x587cca51142976a8400f12d58d8b087f),
(15, 'amsksjdjas@gmail.com', 0x6d7f30d28ebe698ee9adf20db0a1ff73),
(16, 'shubham123@gmail.com', 0x31fd98eaf91567119a41a33e8e4733c0),
(17, 'mohitkambli8@gmail.com', 0xcf27492db14aaf5465e82ca913b342ed),
(18, 'abhi@gmail.com', 0xc5fbb349270b60559cb78a92ea9e76f1),
(21, 'jayesh.mandlik@vit.edu.in', 0x0057469274a3ac686a1fc4fc82bcb4d9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`acc_no`),
  ADD KEY `holder` (`holder`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `customer_ibfk_3` (`perm_addr`),
  ADD KEY `customer_ibfk_2` (`cors_addr`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD UNIQUE KEY `tid` (`tid`),
  ADD KEY `from_acc` (`from_acc`),
  ADD KEY `to_acc` (`to_acc`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `tid` bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`holder`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`cors_addr`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_3` FOREIGN KEY (`perm_addr`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`from_acc`) REFERENCES `account` (`acc_no`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`to_acc`) REFERENCES `account` (`acc_no`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
