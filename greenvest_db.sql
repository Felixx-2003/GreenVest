-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 08:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenvest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `investment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL CHECK (`amount` > 0),
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`investment_id`, `user_id`, `project_id`, `amount`, `date`) VALUES
(1, 1, 'carbon-credits', 10.00, '2025-03-11 10:25:54'),
(2, 1, 'carbon-credits', 200.00, '2025-03-11 10:29:43'),
(3, 1, 'biogas-projects', 50.00, '2025-03-11 10:31:57'),
(4, 1, 'eco-real-estate', 240.00, '2025-03-11 10:34:08'),
(5, 2, 'community-solar', 1.00, '2025-03-11 11:06:16'),
(6, 2, 'eco-real-estate', 50.00, '2025-03-11 11:07:40'),
(7, 2, 'community-solar', 50.00, '2025-03-11 11:07:48'),
(8, 2, 'community-solar', 49.00, '2025-03-11 11:08:10'),
(9, 2, 'eco-real-estate', 50.00, '2025-03-11 11:08:19'),
(10, 2, 'community-solar', 20.00, '2025-03-11 11:12:32'),
(11, 2, 'community-solar', 50.00, '2025-03-11 11:15:49'),
(12, 2, 'community-solar', 50.00, '2025-03-11 11:17:17'),
(13, 2, 'community-solar', 10.00, '2025-03-11 11:17:56'),
(14, 2, 'community-solar', 10.00, '2025-03-11 11:18:04'),
(15, 2, 'community-solar', 10.00, '2025-03-11 11:19:25'),
(16, 2, 'community-solar', 10.00, '2025-03-11 11:19:32'),
(17, 2, 'community-solar', 10.00, '2025-03-11 11:22:22'),
(18, 2, 'community-solar', 50.00, '2025-03-11 11:52:59'),
(19, 2, 'community-solar', 10.00, '2025-03-11 11:53:19'),
(20, 2, 'community-solar', 50.00, '2025-03-11 11:54:31'),
(21, 2, 'community-solar', 10.00, '2025-03-11 11:54:56'),
(22, 2, 'community-solar', 10.00, '2025-03-11 11:57:53'),
(23, 2, 'carbon-credits', 10.00, '2025-03-11 12:04:39'),
(24, 2, 'carbon-credits', 10.00, '2025-03-11 12:05:00'),
(25, 2, 'carbon-credits', 5.00, '2025-03-11 12:15:34'),
(26, 2, 'community-solar', 10.00, '2025-03-11 12:36:10'),
(27, 2, 'urban-farming', 50.00, '2025-03-11 12:36:21'),
(28, 2, 'wind-projects', 50.00, '2025-03-11 12:36:48'),
(29, 2, 'sustainable-agriculture', 10.00, '2025-03-11 12:37:37'),
(30, 2, 'wind-projects', 10.00, '2025-03-11 12:37:55'),
(31, 2, 'carbon-credits', 0.40, '2025-03-11 13:07:27'),
(32, 2, 'community-solar', 0.40, '2025-03-11 13:07:27'),
(33, 2, 'community-solar', 2000.00, '2025-03-11 18:54:49'),
(34, 2, 'community-solar', 2000.00, '2025-03-11 18:55:00'),
(35, 2, 'community-solar', 2000.00, '2025-03-11 18:55:12'),
(36, 1, 'carbon-credits', 200.00, '2025-03-11 19:13:47'),
(37, 1, 'carbon-credits', 1000.00, '2025-03-11 19:14:20'),
(38, 1, 'wind-projects', 2000.00, '2025-03-11 19:32:46'),
(39, 3, 'wind-projects', 1000.00, '2025-03-11 19:33:57'),
(40, 3, 'sustainable-agriculture', 50.00, '2025-03-11 19:34:12'),
(41, 1, 'carbon-credits', 50.00, '2025-03-12 03:53:18'),
(42, 1, 'sustainable-agriculture', 1000.00, '2025-03-12 03:55:05'),
(43, 4, 'wind-projects', 5000.00, '2025-03-12 04:01:23'),
(44, 4, 'green-bonds', 100.00, '2025-03-12 04:35:14'),
(45, 3, 'urban-farming', 100.00, '2025-03-14 05:34:36'),
(55, 11, 'electric-vehicle-charging', 100.00, '2025-03-14 07:45:39'),
(56, 11, 'biogas-projects', 50.00, '2025-03-14 07:46:26'),
(57, 11, 'green-bonds', 350.00, '2025-03-14 07:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `merchant` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `round_up` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `merchant`, `amount`, `round_up`, `date`) VALUES
(1, 2, 'tnb', 199.20, 0.80, '2025-03-11 13:07:27'),
(2, 2, 'tnb', 29.10, 0.90, '2025-03-11 13:09:48'),
(3, 2, 'tnb', 19.10, 0.90, '2025-03-11 13:10:44'),
(4, 2, 'tnb', 19.20, 0.80, '2025-03-11 13:11:04'),
(5, 2, 'tnb', 19.20, 0.80, '2025-03-11 13:12:35'),
(6, 2, 'tnb', 19.20, 0.80, '2025-03-11 13:14:11'),
(7, 2, 'tnb', 19.20, 0.80, '2025-03-11 13:15:00'),
(8, 3, 'tnb', 13.20, 0.80, '2025-03-11 19:36:09'),
(9, 1, 'tnb', 29.10, 0.90, '2025-03-12 03:53:39'),
(10, 4, 'tnb', 19.10, 0.90, '2025-03-12 04:02:15'),
(11, 4, 'tnb', 19.20, 0.80, '2025-03-12 04:02:36'),
(12, 4, 'tnb', 19.20, 0.80, '2025-03-12 04:35:55'),
(13, 3, 'tnb', 19.20, 0.80, '2025-03-14 05:35:29'),
(15, 11, 'time wifi', 19.20, 0.80, '2025-03-14 07:11:09'),
(16, 11, 'tnb', 19.20, 0.80, '2025-03-14 07:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `goal` text NOT NULL,
  `timeline` varchar(100) NOT NULL,
  `roi` varchar(50) NOT NULL,
  `benefits` text NOT NULL,
  `impact` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `goal`, `timeline`, `roi`, `benefits`, `impact`, `category`, `image`) VALUES
('biogas-projects', 'Biogas Energy Initiative', 'Convert waste into renewable energy.', '9 months', '4-6% annually', 'Carbon reduction credits available', 'Each RM100 funds biogas systems, reducing methane.', 'ESG Fund', 'biogas.png'),
('carbon-credits', 'Carbon Credit Program', 'Invest in carbon offset projects.', '4 months', '3-5% annually', 'Tradeable carbon credits', 'Each RM100 offsets 40 kg of CO2 emissions', 'Education', 'carbon-credit.png'),
('community-solar', 'Community Solar Power', 'Fund community solar projects.', '8 months', '5-7% annually', 'Reduced energy costs for residents', 'Each RM100 helps install solar panels for communities.', 'Community Impact', 'community-solar.png'),
('eco-real-estate', 'Eco-Friendly Real Estate', 'Develop green-certified housing projects.', '12 months', '5-8% annually', 'High demand for sustainable homes', 'Each RM100 supports 1 sqm of green building construction', 'Community Impact', 'eco-home.png'),
('education-green-schools', 'Green Schools Initiative', 'Support eco-friendly school infrastructure.', '9 months', '4-6% annually', 'Government incentives & community benefits', 'Each RM100 supports eco-friendly school materials.', 'Education', 'green-school.png'),
('electric-vehicle-charging', 'EV Charging Stations Expansion', 'Expand EV charging infrastructure in urban areas.', '6 months', '5-7% annually', 'Government incentives & increasing EV adoption', 'Each RM100 helps install an EV charging station, promoting green mobility.', 'Green Project', 'ev-charging.png'),
('green-bonds', 'Green Bonds', 'Fund sustainable projects with low-risk investments', '5 months', '3-5% annually', 'Government-backed with tax exemptions', 'Each RM100 reduces 30 kg CO2 emissions', 'Green Project', 'green-bond.png'),
('hydropower-dams', 'Hydropower for Clean Energy', 'Develop sustainable hydropower energy.', '12 months', '5-7% annually', 'Long-term stable returns', 'Each RM100 generates 100 kWh of clean energy.', 'Green Project', 'hydropower.png'),
('solar-funds', 'Solar Energy for Rural Homes', 'Provide affordable and clean solar energy', '6 months', '4-6% annually', 'Government tax incentives & energy credits', 'Each RM100 funds a solar panel, reducing 50 kg CO2 emissions', 'ESG Fund', 'solar-panel.png'),
('sustainable-agriculture', 'Organic Farming Expansion', 'Support sustainable farming projects.', '6 months', '4-6% annually', 'Stable returns from organic produce', 'Each RM100 supports 10 sqm of organic farmland', 'Sustainable Agriculture', 'sustainable.png'),
('urban-farming', 'Urban Vertical Farming', 'Support vertical farming in cities.', '4 months', '3-5% annually', 'Sustainable food production', 'Each RM100 supports hydroponic farming.', 'Sustainable Agriculture', 'urban-farm.png'),
('wind-projects', 'Wind Projects', 'Support wind energy development', '9 months', '4-6% annually', 'Stable returns with government support', 'Each RM100 generates 60 kWh of clean energy', 'ESG Fund', 'wind.png');

-- --------------------------------------------------------

--
-- Table structure for table `project_contributions`
--

CREATE TABLE `project_contributions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `project_id` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_contributions`
--

INSERT INTO `project_contributions` (`id`, `user_id`, `project_id`, `amount`, `date`) VALUES
(1, 2, 'carbon-credits', 0.45, '2025-03-11 13:09:48'),
(2, 2, 'community-solar', 0.45, '2025-03-11 13:09:48'),
(3, 2, 'community-solar', 0.30, '2025-03-11 13:10:44'),
(4, 2, 'carbon-credits', 0.30, '2025-03-11 13:10:44'),
(5, 2, 'green-bonds', 0.30, '2025-03-11 13:10:44'),
(6, 2, 'community-solar', 0.27, '2025-03-11 13:11:04'),
(7, 2, 'carbon-credits', 0.27, '2025-03-11 13:11:04'),
(8, 2, 'green-bonds', 0.27, '2025-03-11 13:11:04'),
(9, 2, 'community-solar', 0.26, '2025-03-11 13:12:35'),
(10, 2, 'carbon-credits', 0.26, '2025-03-11 13:12:35'),
(11, 2, 'green-bonds', 0.28, '2025-03-11 13:12:35'),
(12, 2, 'community-solar', 0.27, '2025-03-11 13:14:11'),
(13, 2, 'carbon-credits', 0.27, '2025-03-11 13:14:11'),
(14, 2, 'green-bonds', 0.27, '2025-03-11 13:14:11'),
(15, 2, 'community-solar', 0.27, '2025-03-11 13:15:00'),
(16, 2, 'carbon-credits', 0.27, '2025-03-11 13:15:00'),
(17, 2, 'green-bonds', 0.26, '2025-03-11 13:15:00'),
(18, 2, 'community-solar', 0.27, '2025-03-11 13:40:27'),
(19, 2, 'carbon-credits', 0.27, '2025-03-11 13:40:27'),
(20, 2, 'green-bonds', 0.26, '2025-03-11 13:40:27'),
(21, 2, 'community-solar', 0.27, '2025-03-11 13:41:07'),
(22, 2, 'carbon-credits', 0.27, '2025-03-11 13:41:07'),
(23, 2, 'green-bonds', 0.26, '2025-03-11 13:41:07'),
(24, 2, 'community-solar', 0.27, '2025-03-11 13:42:15'),
(25, 2, 'carbon-credits', 0.27, '2025-03-11 13:42:15'),
(26, 2, 'green-bonds', 0.26, '2025-03-11 13:42:15'),
(27, 2, 'community-solar', 0.27, '2025-03-11 13:42:40'),
(28, 2, 'carbon-credits', 0.27, '2025-03-11 13:42:40'),
(29, 2, 'green-bonds', 0.26, '2025-03-11 13:42:40'),
(30, 2, 'community-solar', 0.27, '2025-03-11 13:47:00'),
(31, 2, 'carbon-credits', 0.27, '2025-03-11 13:47:00'),
(32, 2, 'green-bonds', 0.26, '2025-03-11 13:47:00'),
(33, 2, 'community-solar', 0.27, '2025-03-11 13:47:08'),
(34, 2, 'carbon-credits', 0.27, '2025-03-11 13:47:08'),
(35, 2, 'green-bonds', 0.26, '2025-03-11 13:47:08'),
(36, 2, 'community-solar', 0.27, '2025-03-11 14:04:11'),
(37, 2, 'carbon-credits', 0.27, '2025-03-11 14:04:11'),
(38, 2, 'green-bonds', 0.26, '2025-03-11 14:04:11'),
(39, 2, 'community-solar', 0.27, '2025-03-11 14:22:45'),
(40, 2, 'carbon-credits', 0.27, '2025-03-11 14:22:45'),
(41, 2, 'green-bonds', 0.26, '2025-03-11 14:22:45'),
(42, 2, 'community-solar', 0.27, '2025-03-11 14:23:27'),
(43, 2, 'carbon-credits', 0.27, '2025-03-11 14:23:27'),
(44, 2, 'green-bonds', 0.26, '2025-03-11 14:23:27'),
(45, 2, 'community-solar', 0.27, '2025-03-11 14:25:33'),
(46, 2, 'carbon-credits', 0.27, '2025-03-11 14:25:33'),
(47, 2, 'green-bonds', 0.26, '2025-03-11 14:25:33'),
(48, 2, 'community-solar', 0.27, '2025-03-11 14:27:21'),
(49, 2, 'carbon-credits', 0.27, '2025-03-11 14:27:21'),
(50, 2, 'green-bonds', 0.26, '2025-03-11 14:27:21'),
(51, 2, 'community-solar', 0.04, '2025-03-11 14:28:00'),
(52, 2, 'carbon-credits', 0.03, '2025-03-11 14:28:00'),
(53, 2, 'green-bonds', 0.03, '2025-03-11 14:28:00'),
(54, 2, 'community-solar', 0.30, '2025-03-11 18:43:11'),
(55, 2, 'carbon-credits', 0.30, '2025-03-11 18:43:11'),
(56, 2, 'green-bonds', 0.30, '2025-03-11 18:43:11'),
(57, 3, 'sustainable-agriculture', 0.90, '2025-03-11 19:35:56'),
(58, 3, 'sustainable-agriculture', 0.80, '2025-03-11 19:36:09'),
(59, 3, 'sustainable-agriculture', 0.40, '2025-03-11 19:36:44'),
(60, 3, 'urban-farming', 0.40, '2025-03-11 19:36:44'),
(61, 3, 'sustainable-agriculture', 0.30, '2025-03-11 19:42:18'),
(62, 3, 'urban-farming', 0.30, '2025-03-11 19:42:18'),
(63, 3, 'solar-funds', 0.30, '2025-03-11 19:42:18'),
(64, 1, 'carbon-credits', 0.90, '2025-03-12 03:53:39'),
(65, 1, 'carbon-credits', 0.10, '2025-03-12 03:54:08'),
(66, 1, 'eco-real-estate', 0.10, '2025-03-12 03:54:08'),
(67, 4, 'wind-projects', 0.90, '2025-03-12 04:02:15'),
(68, 4, 'community-solar', 0.40, '2025-03-12 04:02:36'),
(69, 4, 'wind-projects', 0.40, '2025-03-12 04:02:36'),
(70, 4, 'community-solar', 0.27, '2025-03-12 04:35:55'),
(71, 4, 'green-bonds', 0.27, '2025-03-12 04:35:55'),
(72, 4, 'wind-projects', 0.26, '2025-03-12 04:35:55'),
(73, 3, 'solar-funds', 0.27, '2025-03-14 05:35:29'),
(74, 3, 'sustainable-agriculture', 0.27, '2025-03-14 05:35:29'),
(75, 3, 'urban-farming', 0.26, '2025-03-14 05:35:29'),
(76, 3, 'solar-funds', 0.30, '2025-03-14 05:41:48'),
(77, 3, 'sustainable-agriculture', 0.30, '2025-03-14 05:41:48'),
(78, 3, 'urban-farming', 0.30, '2025-03-14 05:41:48'),
(80, 11, 'biogas-projects', 0.40, '2025-03-14 07:11:09'),
(81, 11, 'carbon-credits', 0.40, '2025-03-14 07:11:09'),
(82, 11, 'biogas-projects', 0.40, '2025-03-14 07:47:34'),
(83, 11, 'carbon-credits', 0.40, '2025-03-14 07:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `reward_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `min_investment` decimal(10,2) NOT NULL,
  `partner` varchar(255) NOT NULL,
  `reward_type` varchar(50) NOT NULL,
  `tier` enum('Bronze','Silver','Gold','Diamond') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `reward_name`, `description`, `min_investment`, `partner`, `reward_type`, `tier`) VALUES
(14, 'Jaya Grocery RM10 Voucher', 'Get RM10 off on groceries', 100.00, 'Jaya Grocery', 'Voucher', 'Bronze'),
(15, 'Public Transport Cashback', 'Get RM5 cashback for LRT/MRT', 100.00, 'RapidKL', 'Cashback', 'Bronze'),
(16, 'Cinema Ticket Discount', 'Get 10% off your next movie', 100.00, 'GSC Cinemas', 'Discount', 'Bronze'),
(17, 'Jaya Grocery RM30 Voucher', 'RM30 discount for groceries', 500.00, 'Jaya Grocery', 'Voucher', 'Silver'),
(18, 'Sunway Lagoon 20% Off', '20% discount on tickets', 500.00, 'Sunway Lagoon', 'Discount', 'Silver'),
(19, 'Electricity Bill Rebate (RM20)', 'RM20 rebate on TNB bill', 500.00, 'TNB', 'Discount', 'Silver'),
(20, 'Jaya Grocery RM50 Voucher', 'RM50 grocery voucher', 1000.00, 'Jaya Grocery', 'Voucher', 'Gold'),
(21, 'Sunway Lagoon Free Entry', 'Free Sunway Lagoon ticket', 1000.00, 'Sunway Lagoon', 'Free Ticket', 'Gold'),
(22, 'Electricity Bill Rebate (RM50)', 'RM50 rebate on TNB bill', 1000.00, 'TNB', 'Discount', 'Gold'),
(23, 'Hotel Stay Discount', '20% discount on Sunway Hotels', 1000.00, 'Sunway Hotels', 'Discount', 'Gold'),
(24, 'Legoland Free Ticket', 'Free Legoland entry ticket', 5000.00, 'Legoland', 'Free Ticket', 'Diamond'),
(25, 'Sunway Lagoon Free VIP Pass', 'Free VIP entry & express lane', 5000.00, 'Sunway Lagoon', 'Free Ticket', 'Diamond'),
(26, 'Luxury Hotel Staycation', 'One night free at Sunway Hotel', 5000.00, 'Sunway Hotels', '', 'Diamond');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `round_up` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `sender_id`, `recipient_id`, `amount`, `round_up`, `date`) VALUES
(13, 2, NULL, 19.20, 0.80, '2025-03-11 14:04:11'),
(14, 2, NULL, 19.20, 0.80, '2025-03-11 14:22:45'),
(15, 2, NULL, 19.20, 0.80, '2025-03-11 14:23:27'),
(16, 2, NULL, 19.20, 0.80, '2025-03-11 14:25:33'),
(18, 2, NULL, 19.20, 0.80, '2025-03-11 14:27:21'),
(19, 2, NULL, 20.90, 0.10, '2025-03-11 14:28:00'),
(20, 2, NULL, 309.10, 0.90, '2025-03-11 18:43:11'),
(21, 3, NULL, 201.00, 0.00, '2025-03-11 19:35:15'),
(22, 3, NULL, 19.10, 0.90, '2025-03-11 19:35:33'),
(23, 3, 1, 19.10, 0.90, '2025-03-11 19:35:56'),
(24, 3, 1, 3.20, 0.80, '2025-03-11 19:36:44'),
(25, 3, 1, 2.10, 0.90, '2025-03-11 19:42:18'),
(26, 1, 3, 18.80, 0.20, '2025-03-12 03:54:08'),
(27, 3, 1, 208.10, 0.90, '2025-03-14 05:41:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `balance`, `created_at`, `profile_image`) VALUES
(1, 'Alice Green', 'alice@example.com', '$2y$10$0Jmr.NqMogCjf12I9Zsrxut5r.0imawA1pdNFaTSmIy9yFyvumx7y', 8108.20, '2025-03-11 06:20:31', 'girl.png'),
(2, 'Bob Smith', 'bob@example.com', '$2y$10$sO8A/at..cCvYHLN3yExweb3RaZ6oKRlEvk6lS8BNG/SnN47ZRWmS', 3000.00, '2025-03-11 06:20:31', 'boy.png'),
(3, 'Jia Hong', 'jiahong@example.com', '$2y$10$aFKUvIideRhQ3e.z0bwpPem9HkhZNBDuDI44ro4q1FIM1H5kf204y', 3377.80, '2025-03-11 19:03:33', 'boy.png'),
(4, 'Dhieban', 'dhieban@example.com', '$2y$10$KkiFXMro/rEFxJ3bT4/TRuZ67UoMwOOa87Jeb87Q5ffAiQQwEbJvW', 4840.00, '2025-03-12 03:56:56', 'boy.png'),
(5, 'testuser', 'testuser@example.com', '$2y$10$N3PG.LYdRfhZSSR.PHqBeOrzeIDD01RIf4M/12bgUULljKzAA8jdG', 5000.00, '2025-03-14 05:27:30', 'boy.png'),
(11, 'test', 'test@example.com', '$2y$10$tN.eIOyOh9Crthcygxd4LuDOqoMqXDGQv1inyS0z3N0zZpnVLcWCC', 4460.00, '2025-03-14 07:10:50', 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_project_settings`
--

CREATE TABLE `user_project_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` varchar(255) NOT NULL,
  `auto_round_up` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_project_settings`
--

INSERT INTO `user_project_settings` (`id`, `user_id`, `project_id`, `auto_round_up`) VALUES
(1, 2, 'community-solar', 1),
(8, 2, 'carbon-credits', 1),
(13, 2, 'green-bonds', 1),
(14, 3, 'sustainable-agriculture', 1),
(15, 3, 'urban-farming', 1),
(16, 3, 'solar-funds', 1),
(17, 1, 'carbon-credits', 1),
(18, 1, 'eco-real-estate', 1),
(19, 4, 'wind-projects', 1),
(20, 4, 'community-solar', 1),
(21, 4, 'green-bonds', 1),
(26, 11, 'carbon-credits', 1),
(27, 11, 'biogas-projects', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`investment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_contributions`
--
ALTER TABLE `project_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `transfers_ibfk_2` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_project_settings`
--
ALTER TABLE `user_project_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `investment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project_contributions`
--
ALTER TABLE `project_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_project_settings`
--
ALTER TABLE `user_project_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investments_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `project_contributions`
--
ALTER TABLE `project_contributions`
  ADD CONSTRAINT `project_contributions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_project_settings`
--
ALTER TABLE `user_project_settings`
  ADD CONSTRAINT `user_project_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_project_settings_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
