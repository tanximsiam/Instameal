-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 04:35 PM
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
-- Database: `instameal`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `calories` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `unit`, `calories`) VALUES
(1, 'pasta', 'grams', 200.00),
(2, 'egg', 'piece', 70.00),
(3, 'cheese', 'grams', 100.00),
(4, 'bacon', 'grams', 150.00),
(5, 'bread', 'slice', 80.00),
(6, 'butter', 'grams', 50.00),
(7, 'lettuce', 'grams', 15.00),
(8, 'tomato', 'grams', 22.00),
(9, 'veggie patty', 'piece', 120.00),
(10, 'pizza dough', 'grams', 250.00),
(11, 'mozzarella', 'grams', 80.00),
(12, 'basil', 'grams', 5.00),
(13, 'mayonnaise', 'grams', 40.00),
(14, 'mustard', 'grams', 15.00),
(15, 'croutons', 'grams', 30.00),
(16, 'parmesan', 'grams', 40.00),
(17, 'Caesar dressing', 'ml', 20.00),
(18, 'beef patty', 'piece', 200.00),
(19, 'turkey', 'grams', 150.00),
(20, 'chicken patty', 'piece', 180.00),
(21, 'mushrooms', 'grams', 25.00),
(22, 'bell pepper', 'grams', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cuisine` varchar(100) DEFAULT NULL,
  `approval` tinyint(1) DEFAULT 0,
  `approval_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `description`, `cuisine`, `approval`, `approval_date`) VALUES
(1, 'Spaghetti Carbonara', 'Classic Italian pasta with egg and cheese', 'Italian', 1, '2025-03-01 00:00:00'),
(2, 'Carbonara', 'Creamy pasta with egg and cheese', 'Italian', 1, '2025-03-02 00:00:00'),
(3, 'Bacon and Egg Sandwich', 'A simple breakfast sandwich with bacon and egg', 'American', 1, '2025-03-03 00:00:00'),
(4, 'Egg and Cheese Sandwich', 'A tasty sandwich with egg and cheese', 'American', 1, '2025-03-04 00:00:00'),
(5, 'Grilled Cheese', 'A classic grilled cheese sandwich', 'American', 1, '2025-03-05 00:00:00'),
(6, 'Toasted Cheese', 'A simple toasted sandwich with cheese', 'American', 1, '2025-03-06 00:00:00'),
(7, 'Veggie Burger', 'A healthy veggie burger with essential ingredients', 'American', 1, '2025-03-07 00:00:00'),
(8, 'Veggie Patty Sandwich', 'A sandwich made with a veggie patty and fresh veggies', 'American', 1, '2025-03-08 00:00:00'),
(9, 'Margherita Pizza', 'Simple pizza with mozzarella, basil, and tomato', 'Italian', 1, '2025-03-09 00:00:00'),
(10, 'Mozzarella Pizza', 'A pizza topped with mozzarella, basil, and tomato', 'Italian', 1, '2025-03-10 00:00:00'),
(11, 'Chicken Caesar Salad', 'A healthy Caesar salad with grilled chicken', 'American', 1, '2025-03-11 00:00:00'),
(12, 'Caesar Salad', 'Classic Caesar salad with romaine lettuce and parmesan', 'American', 1, '2025-03-12 00:00:00'),
(13, 'Cheeseburger', 'A classic burger with cheese, lettuce, and tomato', 'American', 1, '2025-03-13 00:00:00'),
(14, 'Classic Burger', 'A classic beef burger with cheese, lettuce, and tomato', 'American', 1, '2025-03-14 00:00:00'),
(15, 'BLT Sandwich', 'Bacon, lettuce, and tomato sandwich', 'American', 1, '2025-03-15 00:00:00'),
(16, 'Club Sandwich', 'A triple-decker sandwich with bacon, turkey, and veggies', 'American', 1, '2025-03-16 00:00:00'),
(17, 'Veggie Pizza', 'Vegetarian pizza with tomato, mozzarella, and mushrooms', 'Italian', 1, '2025-03-17 00:00:00'),
(18, 'Margherita Pizza (V2)', 'Authentic pizza with fresh mozzarella and basil', 'Italian', 1, '2025-03-18 00:00:00'),
(19, 'Bacon Cheeseburger', 'A burger with crispy bacon and melted cheese', 'American', 1, '2025-03-19 00:00:00'),
(20, 'Chicken Burger', 'A grilled chicken sandwich with cheese and veggies', 'American', 1, '2025-03-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `quantity`) VALUES
(1, 1, 100.00),
(1, 2, 2.00),
(1, 3, 50.00),
(1, 4, 60.00),
(2, 1, 100.00),
(2, 2, 2.00),
(2, 3, 50.00),
(3, 2, 2.00),
(3, 4, 60.00),
(3, 5, 2.00),
(4, 2, 2.00),
(4, 3, 40.00),
(4, 5, 2.00),
(5, 3, 40.00),
(5, 5, 2.00),
(5, 6, 20.00),
(6, 3, 40.00),
(6, 5, 2.00),
(6, 6, 20.00),
(7, 3, 40.00),
(7, 5, 2.00),
(7, 7, 30.00),
(7, 8, 50.00),
(7, 9, 1.00),
(8, 3, 40.00),
(8, 5, 2.00),
(8, 7, 30.00),
(8, 8, 50.00),
(8, 9, 1.00),
(9, 3, 40.00),
(9, 10, 1.00),
(9, 11, 50.00),
(9, 12, 5.00),
(10, 3, 40.00),
(10, 10, 1.00),
(10, 11, 50.00),
(10, 12, 5.00),
(11, 2, 2.00),
(11, 7, 30.00),
(11, 15, 30.00),
(11, 16, 40.00),
(11, 17, 15.00),
(12, 2, 2.00),
(12, 7, 30.00),
(12, 15, 30.00),
(12, 16, 40.00),
(12, 17, 15.00),
(13, 3, 40.00),
(13, 5, 2.00),
(13, 7, 30.00),
(13, 8, 50.00),
(13, 18, 1.00),
(14, 3, 40.00),
(14, 5, 2.00),
(14, 7, 30.00),
(14, 8, 50.00),
(14, 18, 1.00),
(15, 4, 60.00),
(15, 5, 2.00),
(15, 7, 30.00),
(15, 8, 50.00),
(16, 3, 40.00),
(16, 4, 60.00),
(16, 5, 2.00),
(16, 7, 30.00),
(16, 8, 50.00),
(16, 19, 150.00),
(17, 3, 40.00),
(17, 10, 1.00),
(17, 11, 50.00),
(17, 21, 20.00),
(17, 22, 20.00),
(18, 3, 40.00),
(18, 10, 1.00),
(18, 11, 50.00),
(18, 12, 5.00),
(19, 3, 40.00),
(19, 4, 60.00),
(19, 5, 2.00),
(19, 7, 30.00),
(19, 8, 50.00),
(19, 18, 1.00),
(20, 3, 40.00),
(20, 5, 2.00),
(20, 7, 30.00),
(20, 8, 50.00),
(20, 20, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`) VALUES
(1, 'alice', 'pass1234', 'alice@example.com', 'general'),
(2, 'bob', 'qwerty789', 'bob@example.com', 'admin'),
(3, 'charlie', 'letmein22', 'charlie@example.com', 'general'),
(4, 'diana', 'securepwd', 'diana@example.com', 'general'),
(5, 'edward', 'ed@123456', 'edward@example.com', 'admin'),
(6, 'fiona', 'fionapass', 'fiona@example.com', 'general'),
(7, 'george', 'geopass01', 'george@example.com', 'general'),
(8, 'hannah', 'hanHan88', 'hannah@example.com', 'general'),
(9, 'ian', 'iansecure', 'ian@example.com', 'admin'),
(10, 'julia', 'julespass', 'julia@example.com', 'general');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`,`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`),
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
