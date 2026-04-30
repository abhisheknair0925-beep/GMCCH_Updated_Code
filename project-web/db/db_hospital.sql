-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2021 at 01:31 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `tbl_booking_id` int(11) NOT NULL,
  `tbl_booking_crno` text NOT NULL,
  `tbl_booking_name` text NOT NULL,
  `tbl_booking_unit` varchar(20) NOT NULL,
  `tbl_booking_date` date NOT NULL,
  `tbl_booking_token` int(11) NOT NULL,
  `tbl_booking_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_booking`
--

INSERT INTO `tbl_booking` (`tbl_booking_id`, `tbl_booking_crno`, `tbl_booking_name`, `tbl_booking_unit`, `tbl_booking_date`, `tbl_booking_token`, `tbl_booking_status`) VALUES
(1, '1400001', 'csabdkab', '1', '2021-01-24', 1, 'Visited'),
(3, '1400002', 'csabdkab', '1', '2021-01-24', 2, 'Visited'),
(4, '1400005', 'dsfdsfe', '1', '2021-01-24', 3, 'Visited'),
(6, '1400001', 'KANAKAMANI', '2', '2021-01-27', 1, 'Requested'),
(7, '1400032', 'KRISHNAN', '1', '2021-01-28', 1, 'Visited'),
(8, '1400087', 'SETHU MADHAVAN', '1', '2021-01-28', 2, 'Visited'),
(9, '1400092', 'RUKKIYA', '1', '2021-01-28', 3, 'Visited'),
(10, '1400031', 'JANAKI', '1', '2021-01-28', 4, 'Visited'),
(11, '1400022', 'MUSTHAFA', '2', '2021-01-29', 1, 'Visited'),
(12, '1400005', 'KOCHUMON', '2', '2021-01-29', 2, 'Approved'),
(13, '1400004', 'SHOBHA', '2', '2021-01-29', 3, 'Approved'),
(14, '1400078', 'DOMINI', '2', '2021-01-29', 4, 'Requested'),
(15, '1400082', 'SREEDHARAN', '2', '2021-01-29', 6, 'Requested'),
(16, '1400055', 'SADANANDAN', '2', '2021-01-29', 7, 'Requested'),
(17, '1400088', 'SOMASUNDARAN', '2', '2021-01-29', 8, 'Requested'),
(18, '1400099', 'SANTHA', '2', '2021-01-29', 9, 'Requested'),
(19, '1400003', 'MANOJ', '2', '2021-01-29', 21, 'Requested'),
(21, '1400028', 'RAMAN NAIR', '2', '2021-01-29', 23, 'Requested'),
(22, '1400100', 'RAVI', '2', '2021-01-29', 24, 'Requested'),
(23, '1400001', 'KANAKAMANI', '2', '2021-01-29', 26, 'Requested'),
(24, '1400002', 'SANTHA', '2', '2021-01-29', 27, 'Requested'),
(25, '1400010', 'RAMACHANDRAN', '2', '2021-01-29', 28, 'Requested'),
(26, '1400020', 'MADHAVAN', '3', '2021-01-30', 1, 'Requested'),
(28, '1400001', 'KANAKAMANI', '2', '2021-02-02', 1, 'Requested'),
(33, '1400021', 'SASI KUMAR', '2', '2021-02-02', 7, 'Requested'),
(34, '1400098', 'SANTHA', '2', '2021-02-02', 8, 'Requested'),
(35, '1400099', 'SANTHA', '2', '2021-02-02', 9, 'Requested'),
(36, '1400002', 'SANTHA', '2', '2021-02-02', 21, 'Requested'),
(37, '1400006', 'HASEENA', '2', '2021-02-02', 22, 'Requested'),
(38, '1400043', 'MAYAN', '2', '2021-02-02', 23, 'Requested'),
(41, '1400002', 'SANTHA', '1', '2021-02-25', 2, 'Visited'),
(42, '1400004', 'SHOBHA', '1', '2021-02-25', 3, 'Visited'),
(43, '1400014', 'MARY JOSE', '1', '2021-02-25', 4, 'Visited'),
(45, '1400099', 'SANTHA', '1', '2021-02-25', 7, 'Requested'),
(46, '1400055', 'SADANANDAN', '1', '2021-02-25', 8, 'Requested'),
(47, '1400034', 'SUBAIDA', '1', '2021-02-25', 9, 'Requested'),
(48, '1400011', 'GOPALAKRISHNAN', '1', '2021-02-25', 21, 'Requested'),
(49, '1400067', 'RAMACHANDRAN', '1', '2021-02-25', 22, 'Requested'),
(50, '1400075', 'PRABHAKARAN', '1', '2021-02-25', 23, 'Requested'),
(51, '1400041', 'HARIHARAN', '1', '2021-02-25', 24, 'Requested'),
(52, '1400093', 'VELAYUDHAN', '1', '2021-02-25', 26, 'Requested'),
(54, '1400092', 'RUKKIYA', '1', '2021-02-25', 27, 'Requested'),
(55, '1400007', 'SIDHIQUE', '1', '2021-02-25', 28, 'Requested'),
(56, '1400008', 'SHAHARBAN', '1', '2021-02-25', 29, 'Requested'),
(57, '1400009', 'VIJAYAN', '1', '2021-02-25', 41, 'Requested'),
(58, '1400001', 'KANAKAMANI', '1', '2021-02-25', 42, 'Requested'),
(59, '1400005', 'KOCHUMON', '1', '2021-02-25', 43, 'Requested'),
(60, '1400001', 'KANAKAMANI', '2', '2021-02-26', 1, 'Requested'),
(61, '1400003', 'MANOJ', '2', '2021-02-26', 2, 'Requested'),
(62, '1400009', 'VIJAYAN', '2', '2021-02-26', 3, 'Requested');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctor`
--

CREATE TABLE `tbl_doctor` (
  `tbl_doctor_id` int(11) NOT NULL,
  `tbl_doctor_name` varchar(200) NOT NULL,
  `tbl_doctor_qualification` varchar(200) NOT NULL,
  `tbl_doctor_unit_id` int(11) NOT NULL,
  `tbl_doctor_phn` bigint(20) NOT NULL,
  `tbl_doctor_department` varchar(500) NOT NULL,
  `tbl_doctor_gender` varchar(20) NOT NULL,
  `tbl_doctor_regno` int(11) NOT NULL,
  `tbl_doctor_pass` text NOT NULL,
  `tbl_doctor_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hospital`
--

CREATE TABLE `tbl_hospital` (
  `tbl_hospital_id` int(11) NOT NULL,
  `tbl_hospital_email` varchar(250) NOT NULL,
  `tbl_hospital_password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_hospital`
--

INSERT INTO `tbl_hospital` (`tbl_hospital_id`, `tbl_hospital_email`, `tbl_hospital_password`) VALUES
(1, 'hospital@gmail.com', 'hospital');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_units`
--

CREATE TABLE `tbl_units` (
  `tbl_unit_id` int(11) NOT NULL,
  `tbl_unit_name` varchar(100) NOT NULL,
  `tbl_unit_time` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_units`
--

INSERT INTO `tbl_units` (`tbl_unit_id`, `tbl_unit_name`, `tbl_unit_time`) VALUES
(1, 'UNIT 1', 'Monday, Thursday'),
(2, 'UNIT 2', 'Tuesday, Friday'),
(3, 'UNIT 3', 'Wednesday, Saturday');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) NOT NULL,
  `tbl_user_crno` varchar(20) NOT NULL,
  `tbl_user_name` varchar(100) NOT NULL,
  `tbl_user_age` int(11) NOT NULL,
  `tbl_user_gender` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `tbl_user_crno`, `tbl_user_name`, `tbl_user_age`, `tbl_user_gender`) VALUES
(1, '1400001', 'KANAKAMANI', 53, 'F'),
(2, '1400002', 'SANTHA', 48, 'F'),
(3, '1400003', 'MANOJ', 32, 'M'),
(4, '1400004', 'SHOBHA', 47, 'F'),
(5, '1400005', 'KOCHUMON', 65, 'M'),
(6, '1400006', 'HASEENA', 26, 'F'),
(7, '1400007', 'SIDHIQUE', 45, 'M'),
(8, '1400008', 'SHAHARBAN', 34, 'F'),
(9, '1400009', 'VIJAYAN', 67, 'M'),
(10, '1400010', 'RAMACHANDRAN', 48, 'M'),
(11, '1400011', 'GOPALAKRISHNAN', 67, 'M'),
(12, '1400012', 'RAJAPPAN M.K', 58, 'M'),
(13, '1400013', 'KABEER', 42, 'M'),
(14, '1400014', 'MARY JOSE', 68, 'F'),
(15, '1400015', 'JESSY', 50, 'F'),
(16, '1400016', 'MARY SUNNY', 50, 'F'),
(17, '1400017', 'AYISHA', 42, 'F'),
(18, '1400018', 'ALI', 85, 'M'),
(19, '1400019', 'POULOSE', 58, 'M'),
(20, '1400020', 'MADHAVAN', 60, 'M'),
(21, '1400021', 'SASI KUMAR', 52, 'M'),
(22, '1400022', 'MUSTHAFA', 42, 'M'),
(23, '1400023', 'KUPPAMMA', 65, 'F'),
(24, '1400024', 'MERY THOMAS', 70, 'F'),
(25, '1400025', 'VIMALA', 62, 'F'),
(26, '1400026', 'SUNDARAN', 61, 'M'),
(27, '1400027', 'MANI', 49, 'M'),
(28, '1400028', 'RAMAN NAIR', 55, 'M'),
(29, '1400029', 'RAJAMMA', 57, 'F'),
(30, '1400030', 'NALINI', 54, 'F'),
(31, '1400031', 'JANAKI', 47, 'F'),
(32, '1400032', 'KRISHNAN', 58, 'M'),
(33, '1400033', 'SETHU', 38, 'M'),
(34, '1400034', 'SUBAIDA', 46, 'F'),
(35, '1400035', 'MAYILSAMI', 75, 'M'),
(36, '1400036', 'MOHANDAS', 44, 'M'),
(37, '1400037', 'SUBAIDA', 61, 'F'),
(38, '1400038', 'BHARGAVI', 60, 'F'),
(39, '1400039', 'SIVASANKARAN', 74, 'M'),
(40, '1400040', 'RAMAN', 61, 'M'),
(41, '1400041', 'HARIHARAN', 57, 'M'),
(42, '1400042', 'JANAKI', 43, 'F'),
(43, '1400043', 'MAYAN', 65, 'M'),
(44, '1400044', 'NARAYANAN', 70, 'M'),
(45, '1400045', 'RAMAKRISHNAN', 61, 'M'),
(46, '1400046', 'KARAPPAN', 70, 'M'),
(47, '1400047', 'THANKAMMA', 47, 'F'),
(48, '1400048', 'NARAYANAN', 60, 'M'),
(49, '1400049', 'ANIE JOSEPH', 74, 'F'),
(50, '1400050', 'RADHAKRISHNAN', 67, 'M'),
(51, '1400051', 'SREEDHARAN', 71, 'F'),
(52, '1400052', 'THANKAM', 65, 'M'),
(53, '1400053', 'MADHAVAN', 73, 'M'),
(54, '1400054', 'THANKAPPAN', 64, 'M'),
(55, '1400055', 'SADANANDAN', 45, 'M'),
(56, '1400056', 'K.V RAVEENDRAN', 70, 'F'),
(57, '1400057', 'AYISUMMA', 68, 'M'),
(58, '1400058', 'BABU', 65, 'M'),
(59, '1400059', 'JAYARAMAN', 78, 'F'),
(60, '1400060', 'MANI', 46, 'M'),
(61, '1400061', 'HAMSA', 62, 'M'),
(62, '1400062', 'MUHAMMED ALI', 59, 'F'),
(63, '1400063', 'MOHANKUMAR', 53, 'M'),
(64, '1400064', 'ROSY', 72, 'M'),
(65, '1400065', 'LALITHA', 59, 'M'),
(66, '1400066', 'NARAYANAN', 74, 'M'),
(67, '1400067', 'RAMACHANDRAN', 58, 'M'),
(68, '1400068', 'CHAMI', 80, 'M'),
(69, '1400069', 'BHASKARAN', 70, 'F'),
(70, '1400070', 'CHITHAMBARAM', 48, 'M'),
(71, '1400071', 'CHINNA', 63, 'M'),
(72, '1400072', 'SANKARAN', 67, 'M'),
(73, '1400073', 'PADMANABHAN', 65, 'M'),
(74, '1400074', 'CHANDRAN C.C', 54, 'F'),
(75, '1400075', 'PRABHAKARAN', 65, 'F'),
(76, '1400076', 'KUTTAN', 55, 'F'),
(77, '1400077', 'KASIM', 58, 'M'),
(78, '1400078', 'DOMINI', 66, 'M'),
(79, '1400079', 'MANI', 60, 'F'),
(80, '1400080', 'KARTHYAYANI', 69, 'M'),
(82, '1400082', 'SREEDHARAN', 63, 'M'),
(83, '1400083', 'RAJAN', 59, 'M'),
(84, '1400084', 'LEENA BABU', 43, 'F'),
(85, '1400085', 'ABDUL HAMEED', 46, 'M'),
(86, '1400086', 'MADHAVAN', 65, 'F'),
(87, '1400087', 'SETHU MADHAVAN', 67, 'M'),
(88, '1400088', 'SOMASUNDARAN', 53, 'M'),
(89, '1400089', 'KOUSALYA', 55, 'F'),
(90, '1400090', 'RAGHAVAN', 61, 'F'),
(91, '1400091', 'USHA', 49, 'F'),
(92, '1400092', 'RUKKIYA', 39, 'M'),
(93, '1400093', 'VELAYUDHAN', 64, 'F'),
(94, '1400094', 'GOURI', 51, 'M'),
(95, '1400095', 'BABU', 57, 'M'),
(96, '1400096', 'SUJITH', 37, 'F'),
(97, '1400097', 'MADHAVI', 68, 'M'),
(98, '1400098', 'SANTHA', 66, 'F'),
(99, '1400099', 'SANTHA', 60, 'F'),
(100, '1400100', 'RAVI', 65, 'M');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`tbl_booking_id`);

--
-- Indexes for table `tbl_doctor`
--
ALTER TABLE `tbl_doctor`
  ADD PRIMARY KEY (`tbl_doctor_id`);

--
-- Indexes for table `tbl_hospital`
--
ALTER TABLE `tbl_hospital`
  ADD PRIMARY KEY (`tbl_hospital_id`);

--
-- Indexes for table `tbl_units`
--
ALTER TABLE `tbl_units`
  ADD PRIMARY KEY (`tbl_unit_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `tbl_booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `tbl_doctor`
--
ALTER TABLE `tbl_doctor`
  MODIFY `tbl_doctor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_hospital`
--
ALTER TABLE `tbl_hospital`
  MODIFY `tbl_hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_units`
--
ALTER TABLE `tbl_units`
  MODIFY `tbl_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
