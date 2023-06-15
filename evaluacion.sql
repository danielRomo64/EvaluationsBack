-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2023 at 06:26 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluacion`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `description`, `status`) VALUES
(1, 'Tecnología', 0),
(2, 'Deportes', 0),
(3, 'Moda', 0),
(4, 'Salud', 0),
(5, 'Analista', 1),
(6, 'nueva categoria', 0),
(8, 'lololololo', 0),
(9, 'lololololo', 1),
(10, 'lolo5345lololo', 0),
(11, 'lolo5345lolosadlo', 0),
(12, 'lolasdfsao5345lolosadlo', 0),
(13, 'lollosadlo', 0),
(14, 'analista2', 0),
(15, 'analista2', 0),
(16, 'analista2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `status`, `description`) VALUES
(1, 1, 'Samtel'),
(2, 1, 'Falabella'),
(3, 0, 'ACH'),
(4, 1, 'Ecopetrol');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_logs`
--

CREATE TABLE `evaluation_logs` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `evaluator_id` int(11) NOT NULL,
  `evaluated_range` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `status`, `description`) VALUES
(1, 1, 'Administrador'),
(2, 1, 'Evaluador'),
(3, 1, 'Evaluador externo'),
(4, 1, 'Colaborador'),
(5, 1, 'Analista');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT 0,
  `state_type` int(11) DEFAULT 0,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `minimum` int(11) DEFAULT NULL,
  `maximum` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `category_id`, `state_type`, `title`, `description`, `minimum`, `maximum`, `type`) VALUES
(5, 1, 1, 'T1', 'P1', 1, 5, ''),
(6, 2, 1, 'T2', 'P2', 1, 10, ''),
(7, 2, 1, 'T3', 'P2', 1, 10, ''),
(8, 2, 0, 'T4', 'P4', 0, 10, ''),
(9, 5, 0, 'T5', 'Pregunta de cultura general', 0, 0, ''),
(10, 2, 1, 'T7', '¿Es bueno en el futbol? 1-malo, 2-regular,  3-bueno, 4-excelnete', 1, 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `ranges`
--

CREATE TABLE `ranges` (
  `id` int(11) NOT NULL,
  `minimum` int(11) NOT NULL,
  `maximum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `description`, `status`) VALUES
(0, 'inactivo', 1),
(1, 'activo', 1),
(6, 'Pausado', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `user_login` varchar(60) DEFAULT NULL,
  `user_pass` varchar(255) DEFAULT NULL,
  `user_email` varchar(128) DEFAULT NULL,
  `user_registered` datetime DEFAULT NULL,
  `user_status` int(10) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `user_profile` int(10) DEFAULT NULL,
  `user_evaluation_date` datetime DEFAULT NULL,
  `user_job` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `user_login`, `user_pass`, `user_email`, `user_registered`, `user_status`, `first_name`, `last_name`, `user_profile`, `user_evaluation_date`, `user_job`) VALUES
(1, 'corys90@hotmail.com', 'corys90.09', 'corys90@hotmail.com', '2023-06-09 00:00:00', 1, 'Cristian', 'Cortes Sarmiento', 2, '2024-06-09 00:00:00', 1),
(2, 'cristian.cortes@samtel.co', 'cristian.cortes@samtel.co', 'cristian.cortes@samtel.co', '2023-06-09 00:00:00', 1, 'Juan David emilio', 'Hernandez Cortes', 4, '2023-06-09 00:00:00', 3),
(3, 'cristian.cortes@samtel.com', 'cristian.cortes@samtel.com', 'cristian.cortes@samtel.com', '2023-06-10 00:00:00', 1, 'Luis Alberto', 'velez', 2, '2023-06-10 00:00:00', 5),
(5, 'elisabeths@samtel.co', 'elisabeths@samtel.co', 'elisabeths@samtel.co', '2023-06-08 00:00:00', 1, 'Elisabeth', 'Becerra', 4, '2023-06-09 00:00:00', 2),
(7, 'hugo@hotmail.co', 'hugo@hotmail.co', 'hugo@hotmail.co', '2023-06-08 00:00:00', 0, 'Estefani Paola', 'Mendoza Cortes', 4, '2024-06-09 00:00:00', 1),
(9, 'lida@hotmail.cm', 'lida@hotmail.cm', 'lida@hotmail.cm', '2023-06-06 00:00:00', 1, 'lida cecilia', 'Mora Rangel', 4, '2023-06-09 00:00:00', 5),
(10, 'corys90@jose.com', 'corys90@jose.com', 'corys90@jose.com', '2023-06-09 00:00:00', 1, 'jose', 'Cortes Sarmiento', 5, '2023-06-09 00:00:00', 2),
(11, 'daniel@samtel.co', 'daniel@samtel.co', 'daniel@samtel.co', '2023-06-13 00:00:00', 1, 'Daniel', 'Romo', 4, '2023-06-09 00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_job`
--

CREATE TABLE `user_job` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_job`
--

INSERT INTO `user_job` (`id`, `status`, `description`) VALUES
(1, 1, 'analista it'),
(2, 1, 'analista dos'),
(3, 1, 'analista tres'),
(4, 1, 'analista cuatro'),
(5, 1, 'nuevo update'),
(6, 1, 'analista cinco'),
(7, 1, 'analista cinco0');

-- --------------------------------------------------------

--
-- Table structure for table `user_relations`
--

CREATE TABLE `user_relations` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_evaluator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_relations`
--

INSERT INTO `user_relations` (`id`, `id_user`, `id_client`, `id_evaluator`) VALUES
(8, 1, 1, 0),
(9, 2, 2, 3),
(10, 3, 1, 0),
(11, 4, 0, 0),
(12, 5, 0, 0),
(13, 6, 0, 0),
(14, 7, 1, 1),
(15, 8, 0, 0),
(16, 9, 1, 1),
(17, 10, 1, 1),
(18, 11, 2, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_logs`
--
ALTER TABLE `evaluation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evaluation_logs_questions` (`question_id`),
  ADD KEY `fk_evaluation_logs_users` (`user_id`),
  ADD KEY `fk_evaluation_logs_evaluator` (`evaluator_id`),
  ADD KEY `fk_evaluation_logs_ranges` (`evaluated_range`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD KEY `id` (`id`);

--
-- Indexes for table `ranges`
--
ALTER TABLE `ranges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user_relations`
--
ALTER TABLE `user_relations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluation_logs`
--
ALTER TABLE `evaluation_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ranges`
--
ALTER TABLE `ranges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_relations`
--
ALTER TABLE `user_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluation_logs`
--
ALTER TABLE `evaluation_logs`
  ADD CONSTRAINT `fk_evaluation_logs_evaluator` FOREIGN KEY (`evaluator_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluation_logs_questions` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluation_logs_ranges` FOREIGN KEY (`evaluated_range`) REFERENCES `ranges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluation_logs_users` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
