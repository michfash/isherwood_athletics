-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2014 at 03:15 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isherwood_athletics`
--
CREATE DATABASE `isherwood_athletics` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isherwood_athletics`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cartID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `cartStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' - > active, ''9'' - > deleted',
  `dateCreated` int(11) NOT NULL,
  PRIMARY KEY (`cartID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartID`, `userID`, `cartStatus`, `dateCreated`) VALUES
(1, 2, '1', 1395189091),
(2, 0, '1', 1395242511);

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE IF NOT EXISTS `cartitem` (
  `cartItemID` int(11) NOT NULL AUTO_INCREMENT,
  `cartID` int(11) NOT NULL,
  `variationID` int(11) NOT NULL,
  `cartItemQty` int(11) NOT NULL,
  `cartItemPrice` decimal(10,2) NOT NULL,
  `cartItemStatus` enum('0','1') NOT NULL COMMENT '''0'' -> off, ''1'' - > on',
  PRIMARY KEY (`cartItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cartitem`
--

INSERT INTO `cartitem` (`cartItemID`, `cartID`, `variationID`, `cartItemQty`, `cartItemPrice`, `cartItemStatus`) VALUES
(1, 1, 6, 1, 120.00, '1'),
(2, 2, 10, 1, 49.99, '1');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `catID` int(11) NOT NULL AUTO_INCREMENT,
  `catDescription` varchar(50) NOT NULL,
  `categoryPicture` varchar(100) DEFAULT NULL,
  `catStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' -> active, ''9'' -> deleted',
  PRIMARY KEY (`catID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`catID`, `catDescription`, `categoryPicture`, `catStatus`) VALUES
(1, 'Archery', 'archery_bow.jpg', '1'),
(2, 'Basketball', 'basketball.jpg', '1'),
(3, 'Football', 'Football.jpg', '1'),
(4, 'Rugby', '', '1'),
(5, 'Boxing', 'boxing-gloves.png', '1'),
(6, 'Tennis', 'Tennis-clip-art1.jpg', '1'),
(7, 'Swimming', '', '1'),
(8, 'Baseball', 'baseball.jpg', '1'),
(9, 'Golf', 'golf.jpg', '1');

-- --------------------------------------------------------

--
-- Table structure for table `colours`
--

CREATE TABLE IF NOT EXISTS `colours` (
  `colourID` int(11) NOT NULL AUTO_INCREMENT,
  `colourDescription` varchar(255) NOT NULL,
  `colourStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' - > active, ''9'' -> deleted',
  PRIMARY KEY (`colourID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `colours`
--

INSERT INTO `colours` (`colourID`, `colourDescription`, `colourStatus`) VALUES
(1, 'blue', '1'),
(2, 'green', '1'),
(3, 'white', '1'),
(4, 'Purple', '1'),
(5, 'Grey', '1'),
(6, 'Red', '1'),
(7, 'Pink', '1'),
(8, 'Black', '1'),
(9, 'Brown', '1'),
(10, 'Silver', '1');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `orderDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `variationID` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  PRIMARY KEY (`orderDetailID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`orderDetailID`, `variationID`, `quantity`, `amount`, `orderID`) VALUES
(1, 6, 1, '120', 1),
(2, 10, 2, '99.98', 1),
(3, 6, 1, '120', 2),
(4, 10, 2, '99.98', 3),
(5, 8, 2, '60', 3),
(6, 8, 3, '90', 4),
(7, 3, 3, '60', 4),
(8, 6, 1, '120', 5),
(9, 8, 2, '60', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `cc` bigint(16) NOT NULL,
  `total` varchar(11) NOT NULL,
  `paymentDate` int(11) NOT NULL,
  `shippingDate` int(11) DEFAULT NULL,
  `deliveryDate` int(11) DEFAULT NULL,
  `returnDate` int(11) DEFAULT NULL,
  `orderStatus` enum('p','s','d','r') NOT NULL COMMENT 'p->processing, s->shipped, d->delivered, r->returned',
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `cc`, `total`, `paymentDate`, `shippingDate`, `deliveryDate`, `returnDate`, `orderStatus`) VALUES
(1, 1, 4111111111111111, '263.976', 1395348783, NULL, NULL, NULL, 'p'),
(2, 2, 4111111111111111, '144', 1395349171, NULL, NULL, NULL, 'p'),
(3, 3, 4111111111111111, '191.976', 1395833039, 1395833584, NULL, NULL, 's'),
(4, 2, 4111111111111111, '180', 1395834913, 1395834969, 1395866215, NULL, 'd'),
(5, 1, 4111111111111111, '216.00', 1395865643, NULL, NULL, NULL, 'p');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prodVarId` int(11) NOT NULL,
  `picturePath` varchar(200) NOT NULL,
  `pictureType` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pictures`
--


-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE IF NOT EXISTS `product_variations` (
  `variationID` int(11) NOT NULL AUTO_INCREMENT,
  `productID` int(11) NOT NULL,
  `sizeID` int(11) NOT NULL,
  `colourID` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `dateCreated` int(11) NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `dateUpdated` int(11) DEFAULT NULL,
  `variationStatus` enum('0','1','9') NOT NULL COMMENT '''0'' => inactive, ''1'' => active, ''9'' => deleted',
  PRIMARY KEY (`variationID`),
  UNIQUE KEY `productID` (`productID`,`sizeID`,`colourID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`variationID`, `productID`, `sizeID`, `colourID`, `stock`, `price`, `dateCreated`, `updatedBy`, `dateUpdated`, `variationStatus`) VALUES
(1, 1, 3, 2, 10, 20.00, 1314123431, 1, 13123232, '1'),
(2, 1, 7, 5, 4, 20.00, 1314123431, 1, 13123232, '1'),
(3, 2, 2, 2, 7, 20.00, 1314123431, 1, 13123232, '1'),
(4, 1, 2, 1, 1, 20.00, 1314123431, 1, 1314123431, '1'),
(5, 2, 7, 4, 12, 50.00, 1395026615, NULL, NULL, '1'),
(6, 3, 5, 8, 0, 120.00, 1395099357, NULL, NULL, '1'),
(7, 4, 6, 1, 5, 30.00, 1395177083, NULL, NULL, '1'),
(8, 4, 2, 6, 3, 30.00, 1395177272, NULL, NULL, '1'),
(9, 5, 6, 8, 15, 37.00, 1395177644, NULL, NULL, '1'),
(10, 6, 2, 9, 4, 49.99, 1395177866, NULL, NULL, '1'),
(11, 7, 6, 10, 5, 149.95, 1397219433, NULL, NULL, '1'),
(12, 7, 2, 8, 4, 165.95, 1397219544, NULL, NULL, '1'),
(13, 8, 5, 7, 6, 64.50, 1397219796, NULL, NULL, '1'),
(14, 8, 5, 8, 10, 59.95, 1397219823, NULL, NULL, '1'),
(15, 9, 0, 0, 0, 0.00, 1397219896, NULL, NULL, '9'),
(16, 9, 8, 8, 4, 56.95, 1397219968, NULL, NULL, '1'),
(17, 10, 0, 0, 0, 0.00, 1397220026, NULL, NULL, '9'),
(18, 10, 2, 3, 20, 30.00, 1397220062, NULL, NULL, '1'),
(19, 11, 2, 0, 6, 30.00, 1398719403, NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL,
  `subCatID` int(11) NOT NULL,
  `productName` varchar(200) NOT NULL,
  `productDescription` varchar(500) NOT NULL,
  `productPicture` varchar(255) DEFAULT NULL,
  `productStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' - > active, ''9'' -> deleted',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `catID`, `subCatID`, `productName`, `productDescription`, `productPicture`, `productStatus`) VALUES
(1, 1, 2, 'Recurve Bow', 'Recurved Bow...', 'recurve-bow.jpg', '1'),
(2, 1, 1, 'Steel Bow', 'Archery bow made of steel...', 'archery_bow.jpg', '1'),
(3, 2, 2, 'Ball''n Black/Blue', 'Nice Nike black/blue basketball shoes', 'ball''n-black-blue.jpg', '1'),
(4, 5, 2, 'Everlast Boxing gloves', 'Everlast Boxing gloves...', 'boxing-gloves-evalast.jpg', '1'),
(5, 6, 1, 'Prince Warrior 100 ESP', 'Prince Warrior 100 ESP Tennis Rack...', 'Warrior100ESP_800.jpg', '1'),
(6, 2, 2, 'Molten GPS', 'Molten GPS...', 'molten-gps.jpg', '1'),
(7, 9, 1, 'TopFlite Tour Edition', 'TopFlite Tour Edition 16 ...', 'golf.jpg', '1'),
(8, 3, 2, 'Nike Stylish boots size 10" Black', 'Stylish boots size 10" black color...', 'football boots-nike.jpg', '1'),
(9, 3, 3, 'Stylish boots', 'Stylish boots....', 'stylish-boots.jpg', '1'),
(10, 3, 2, 'Football Balls White', 'Football Balls...', 'Football.jpg', '1'),
(11, 4, 1, 'Rugby ball', 'Rugby ball...', 'rugby ball.jpg', '1');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE IF NOT EXISTS `sizes` (
  `sizeID` int(11) NOT NULL AUTO_INCREMENT,
  `sizeDescription` varchar(255) NOT NULL,
  `sizeStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' -> active, ''9'' -> deleted',
  PRIMARY KEY (`sizeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`sizeID`, `sizeDescription`, `sizeStatus`) VALUES
(1, '5"', '1'),
(2, '6"', '1'),
(3, 'US size 10', '1'),
(4, '9 feet', '1'),
(5, 'UK size 10', '1'),
(6, '7', '1'),
(7, 'UK size 45', '1'),
(8, 'UK size 9', '1'),
(9, 'Size 5', '1');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE IF NOT EXISTS `sub_categories` (
  `subCatID` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL,
  `subCatDescription` varchar(255) NOT NULL,
  `subCatStatus` enum('0','1','9') NOT NULL COMMENT '''0'' -> inactive, ''1'' -> active, ''9'' -> deleted',
  PRIMARY KEY (`subCatID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`subCatID`, `catID`, `subCatDescription`, `subCatStatus`) VALUES
(1, 0, 'General', '1'),
(2, 0, 'Men', '1'),
(3, 0, 'women', '1'),
(4, 0, 'kids', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('0','1') NOT NULL COMMENT '0->admin, 1->consumer',
  `lastLoginDate` int(11) DEFAULT NULL,
  `dateRegistered` int(11) NOT NULL,
  `lastUpdated` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `userStatus` enum('0','1','2','9') NOT NULL COMMENT '''0'' -> inactive,''1'' -> active,''2'' - > suspended,''9'' - > deleted',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `firstName`, `lastName`, `email`, `phone`, `address`, `password`, `role`, `lastLoginDate`, `dateRegistered`, `lastUpdated`, `updatedBy`, `userStatus`) VALUES
(1, 'Michael', 'FASIPE', 'fasipemichael@yahoo.com', '07438354219', '75, Chorley old road. Bolton', 'e10adc3949ba59abbe56e057f20f883e', '1', 1395933561, 1394836429, 1394851478, 1, '1'),
(2, 'Admin', 'ADMINISTRATOR', 'admin@yahoo.com', '07450174134', '75, Chorley old road', 'e10adc3949ba59abbe56e057f20f883e', '0', 1398718898, 1394837337, NULL, NULL, '1'),
(3, 'si', 'JOLLEY', 'sj1amt@bolton.ac.uk', '', 'england', '8ee2027983915ec78acc45027d874316', '1', 1395832900, 1395832304, 1395832340, 3, '1'),
(4, 'Lisa', 'BURRELL', 'lisa@lisaburrell.com', '', 'Flixton', 'e10adc3949ba59abbe56e057f20f883e', '1', 1396044742, 1396044115, NULL, NULL, '1'),
(5, 'lisa', 'BURRELL', 'lisaburrell@inbox.com', '07530017778', '5 lulworth avenue', '891d9c299fe2d18606614bee75a99725', '1', 1398719005, 1398717770, NULL, NULL, '1');
