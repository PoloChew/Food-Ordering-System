-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 07:14 AM
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
-- Database: `foodorderingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `SessionID` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `CartItemID` int(11) NOT NULL,
  `CartID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`, `Description`) VALUES
(1, 'Seafood', 'Fresh seafood dishes such as crab, prawns, fish, and shellfish.'),
(2, 'Japanese Cuisine', 'Japanese dishes including sushi, sashimi, ramen, and bento meals.'),
(3, 'Beverages', 'Drinks including juices, tea, coffee, and soft drinks.'),
(4, 'Beer', 'Premium Japanese beers including craft, seasonal, and luxury brews.'),
(5, 'Chocolate', 'Premium chocolate selections including Japanese White Lover cookies');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `CustomerName` varchar(100) NOT NULL,
  `TableNumber` varchar(50) NOT NULL,
  `Pax` int(11) NOT NULL DEFAULT 1,
  `PaymentMethod` varchar(50) NOT NULL,
  `Status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Name`, `Description`, `Price`, `ImageURL`, `CategoryID`) VALUES
(1, 'Caramel Macchiato', 'Rich espresso with steamed milk, vanilla syrup, and caramel drizzle.', 15.90, 'caramel_macchiato.jpg', 3),
(2, 'Caffe Latte', 'Smooth blend of espresso and steamed milk, lightly topped with foam.', 13.50, 'caffe_latte.jpg', 3),
(3, 'Mocha Frappuccino', 'Coffee, chocolate and ice blended into a creamy frozen drink.', 16.90, 'mocha_frappuccino.jpg', 3),
(4, 'Green Tea Latte', 'Matcha green tea blended with creamy steamed milk.', 14.90, 'green_tea_latte.jpg', 3),
(5, 'Java Chip Frappuccino', 'Coffee blended with chocolate chips and topped with whipped cream.', 17.50, 'java_chip_frappuccino.jpg', 3),
(6, 'Vanilla Sweet Cream Cold Brew', 'Cold brew coffee topped with house-made vanilla sweet cream.', 14.90, 'vanilla_coldbrew.jpg', 3),
(7, 'Strawberry Acai Refresher', 'Refreshing blend of strawberry, passion fruit, and acai with ice.', 12.90, 'strawberry_acai.jpg', 3),
(8, 'Caramel Frappuccino', 'Coffee blended with caramel syrup and topped with whipped cream.', 16.50, 'caramel_frappuccino.jpg', 3),
(9, 'Americano', 'Bold espresso shots topped with hot water for a rich coffee flavor.', 10.90, 'americano.jpg', 3),
(10, 'Signature Hot Chocolate', 'Creamy steamed milk blended with rich chocolate.', 13.90, 'hot_chocolate.jpg', 3),
(11, 'Salmon Sushi Set', 'Fresh Norwegian salmon sushi served with soy sauce and wasabi.', 18.90, 'salmon_sushi.jpg', 2),
(12, 'Tuna Sashimi', 'Premium maguro tuna sashimi slices, chilled and fresh.', 24.50, 'tuna_sashimi.jpg', 2),
(13, 'Beef Ramen', 'Rich tonkotsu broth ramen topped with sliced beef, egg, and seaweed.', 21.90, 'beef_ramen.jpg', 2),
(14, 'Shoyu Ramen', 'Classic Japanese soy sauce ramen with char siu, bamboo shoots and egg.', 19.90, 'shoyu_ramen.jpg', 2),
(15, 'Tempura Udon', 'Hot udon soup served with crispy shrimp tempura.', 17.50, 'tempura_udon.jpg', 2),
(16, 'Chicken Katsu Don', 'Crispy chicken cutlet served over rice with egg and special sauce.', 16.90, 'chicken_katsu_don.jpg', 2),
(17, 'Unagi Bento', 'Japanese style grilled eel bento with rice, tamago and vegetables.', 32.90, 'unagi_bento.jpg', 2),
(18, 'Ebi Tempura', 'Golden fried shrimp tempura served with dipping sauce.', 14.90, 'ebi_tempura.jpg', 2),
(19, 'California Roll', 'Crabstick, cucumber, avocado rolled in sesame rice.', 12.50, 'california_roll.jpg', 2),
(20, 'Miso Soup', 'Traditional Japanese miso soup with tofu and seaweed.', 4.90, 'miso_soup.jpg', 2),
(21, 'Fugu Sashimi', 'Premium Japanese pufferfish sashimi prepared by licensed chef.', 198.00, 'fugu_sashimi.jpg', 1),
(22, 'King Crab Legs 6kg above', 'Fresh king crab legs steamed and served with butter sauce.', 1268.00, 'king_crab_legs.jpg', 1),
(23, 'Grilled Lobster', 'Whole lobster grilled with garlic herb butter.', 188.00, 'grilled_lobster.jpg', 1),
(24, 'Bluefin Tuna Sashimi', 'High-grade otoro bluefin tuna belly sashimi.', 220.00, 'bluefin_otoro.jpg', 1),
(25, 'Hokkaido Scallops', 'Seared Hokkaido hotate scallops with citrus sauce.', 98.00, 'hokkaido_scallops.jpg', 1),
(26, 'Abalone Deluxe Plate', 'Premium whole abalone braised in Japanese style sauce.', 168.00, 'abalone_deluxe.jpg', 1),
(27, 'Snow Crab (Kani) 4kg above', 'Freshly steamed Japanese snow crab served with ponzu.', 558.00, 'snow_crab.jpg', 1),
(28, 'Fresh Oysters 6pcs', 'Imported giant oysters served with lemon and mignonette.', 88.00, 'fresh_oysters.jpg', 1),
(29, 'Tiger Prawns Grill', 'Grilled tiger prawns brushed with house special sauce.', 58.00, 'tiger_prawns.jpg', 1),
(30, 'Sea Urchin (Uni) Bowl', 'Hokkaido uni over sushi rice topped with wasabi.', 128.00, 'uni_bowl.jpg', 1),
(31, 'Salmon Belly Grill', 'Flame-grilled salmon belly with sweet soy glaze.', 52.00, 'salmon_belly_grill.jpg', 1),
(32, 'Black Cod Miso', 'Premium black cod marinated in Saikyo miso, oven grilled.', 98.00, 'black_cod_miso.jpg', 1),
(33, 'Suntory Premium Malts', 'A high-quality pilsner with rich aroma and smooth texture.', 18.90, 'suntory_premium_malts.jpg', 4),
(34, 'Sapporo Yebisu Premium', 'Luxury beer brewed with 100% malt for deep and elegant flavor.', 22.50, 'yebisu_premium.jpg', 4),
(35, 'Asahi Super Dry Black', 'Dark premium lager with a crisp dry finish.', 16.90, 'asahi_super_dry_black.jpg', 4),
(36, 'Kirin Ichiban Shibori', 'Brewed using the rare first-press method for pure taste.', 14.90, 'kirin_ichiban.jpg', 4),
(37, 'Orion Draft Beer', 'Famous Okinawan beer with light and refreshing taste.', 13.50, 'orion_draft.jpg', 4),
(38, 'Coedo Shikkoku Black Lager', 'Luxury craft black lager with hints of roasted malt.', 23.90, 'coedo_shikkoku.jpg', 4),
(39, 'Coedo Beniaka', 'Sweet potato amber beer with deep aroma and premium finish.', 25.90, 'coedo_beniaka.jpg', 4),
(40, 'Hitachino Nest White Ale', 'World-famous craft beer with fruity aroma and spices.', 28.90, 'hitachino_white_ale.jpg', 4),
(41, 'Echigo Koshihikari Premium', 'Craft lager made with premium Niigata Koshihikari rice.', 24.50, 'echigo_koshihikari.jpg', 4),
(42, 'Sapporo Reserve Gold', 'Limited edition rich malt beer with golden flavor.', 26.00, 'sapporo_reserve_gold.jpg', 4),
(43, 'Shiroi Koibito White Chocolate (12 pcs)', 'A premium Hokkaido-only white chocolate langue de chat cookie. Light, crispy, and creamy.', 45.00, 'shiroi_white_12.jpg', 5),
(44, 'Shiroi Koibito White Chocolate (24 pcs)', 'The iconic Japanese gift. Smooth white chocolate sandwiched between buttery cookies.', 88.00, 'shiroi_white_24.jpg', 5),
(45, 'Shiroi Koibito Mix (White + Dark) 27 pcs', 'A premium mixed assortment of Shiroi Koibito white and dark chocolate cookies.', 120.00, 'shiroi_mix_27.jpg', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`CartItemID`),
  ADD KEY `CartID` (`CartID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `CartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
