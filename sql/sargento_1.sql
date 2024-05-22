-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 22, 2024 at 10:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sargento_1`
--
CREATE DATABASE IF NOT EXISTS `sargento_1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `sargento_1`;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `address`) VALUES
(1, 21, '123 Rizal, Taguig City'),
(7, 22, '1123 Rizal Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `chat_id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `message` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`chat_id`),
  KEY `chat_sender_id_fk` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `sender_id`, `customer_id`, `message`, `timestamp`) VALUES
(1, 22, 22, 'imissyou', '2024-05-21 04:43:35'),
(2, 22, 22, 'mwa', '2024-05-21 04:43:36'),
(3, 23, 22, 'imissyou2bbk', '2024-05-21 04:56:33'),
(4, 22, 22, 'jkjkjk', '2024-05-21 04:57:20'),
(5, 22, 22, 'l,l,l', '2024-05-21 04:57:23'),
(6, 23, 22, 'justine joven casiano\r\n', '2024-05-21 04:58:17'),
(7, 23, 22, 'oyoy', '2024-05-21 05:00:56'),
(8, 22, 22, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2024-05-21 05:02:26'),
(9, 22, 22, 'putanginamo', '2024-05-21 07:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `content_id` varchar(255) NOT NULL,
  `page` varchar(50) NOT NULL,
  `content_text` text,
  `image` text,
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`content_id`, `page`, `content_text`, `image`) VALUES
('ABOUTHEADERTEXT', 'about_us', 'The proud workers of sargento that handfully perfected their craft in making your dream furniture into reality. They\\\'ve spend their entire lives practicing, flourishing each individual furniture known for them to be known in the metro. The team also consists of female workers helps the business afloat. With all of the sleepless nights, they\\\'ve strive for sucess by burning the midnight oil. Mr. Eddie Murphy Sargento, keeping the family business alive with Mrs. Journa Joy Sargento, they\\\'ve been working and doing the best they can for their family. With these individuals around, Sargento Upholstery will be able to provide the highest quality furniture to suit your every needs.', NULL),
('ABOUTHEADERTITLE', 'about_us', 'Meet the team', NULL),
('ABOUTHISTORYTEXT', 'about_us', 'Established by Eddielberto Sargento in Negros Occidental, Sargento Upholstery has quietly thrived in the furniture industry for nearly three and a half decades, specializing in custom-made furniture. Known for its commitment to quality craftsmanship and flexibility in adapting to changing market trends, the business has managed to sustain itself over the years. In 1997, a strategic decision was made to relocate the business to Taguig, a move aimed at expanding its reach and meeting the evolving demands of the market. This relocation, though not widely recognized, played a crucial role in the continued success of Sargento Upholstery.', NULL),
('ABOUTHISTORYTITLE', 'about_us', 'History of Sargento Upholstery', NULL),
('ABOUTVALUESSUBHEADING1', 'about_us', 'Quality Materials', NULL),
('ABOUTVALUESSUBHEADING2', 'about_us', 'Craftsmanship Excellence', NULL),
('ABOUTVALUESSUBHEADING3', 'about_us', 'Heritage and Innovationa', NULL),
('ABOUTVALUESSUBHEADING4', 'about_us', 'Customer-Centric', NULL),
('ABOUTVALUESSUBTEXT1', 'about_us', 'Emphasizing the importance of using high-grade materials in our products.', NULL),
('ABOUTVALUESSUBTEXT2', 'about_us', 'Focusing on the artistry and skill involved in creating our goods or services.', NULL),
('ABOUTVALUESSUBTEXT3', 'about_us', 'Balancing tradition with a forward-thinking approach, acknowledging both history and modern advancements.', NULL),
('ABOUTVALUESSUBTEXT4', 'about_us', 'Prioritizing our customers needs and satisfaction at the core of our business practices.', NULL),
('ABOUTVALUESTITLE', 'about_us', 'Our Values & Philosophy', NULL),
('BUSIADD', 'contact_us', 'Blk 68 Lot 41 Lapu-Lapu St., Upper Bicutan, Taguig City', NULL),
('BUSIHRS', 'contact_us', '8:00 AM - 5:00 PM', NULL),
('CPNNUM', 'contact_us', '0999 - 406 - 8816', NULL),
('CRAFTSCARDTEXT1', 'services_craftsmanship', 'Our journey into crafting superior furniture begins with the careful selection of materials, ensuring that only the finest components are used. For the sturdy frame and foundation of our pieces, we rely on treated palochina wood, a choice renowned for its remarkable durability and exceptional robustness. This wood is not just any wood; it\'s a testament to our dedication to creating furniture that is built to last.', NULL),
('CRAFTSCARDTEXT2', 'services_craftsmanship', 'Moving on to the heart of our creations, we employ the luxuriously comfortable uratex foam. This premium foam is carefully chosen to provide a plush and supportive seating experience, allowing you to sink into your furniture while still enjoying the firm support that ensures your comfort and satisfaction for years to come.fasdfasaf', NULL),
('CRAFTSCARDTEXT3', 'services_craftsmanship', 'But it doesnt end there. Our commitment to quality extends to the fabrics we use. We insist on utilizing only the best, such as RGC fabric and German leather, both celebrated for their exceptional texture, enduring beauty, and ability to withstand wear and tear gracefully. These materials not only add to the aesthetic appeal of our pieces but also ensure that your furniture retains its allure and resilience even after years of use.', NULL),
('CRAFTSCARDTITLE1', 'services_craftsmanship', 'The Foundation of Durability', NULL),
('CRAFTSCARDTITLE2', 'services_craftsmanship', 'Luxurious Comfort', NULL),
('CRAFTSCARDTITLE3', 'services_craftsmanship', 'Exceptional Fabrics', NULL),
('CRAFTSFOOTERTEXT', 'services_craftsmanship', 'This meticulous and selective process of material sourcing and crafting guarantees that each creation that bears the Sargento Upholstery name is not just a piece of furniture; it is a work of art, meticulously built with a devotion to quality, beauty, and longevity. We take great pride in creating furniture that not only fulfills its purpose but enriches the spaces and lives it graces, turning every room into a masterpiece of comfort and elegance.', NULL),
('CRAFTSHEADERTEXT', 'services_craftsmanship', 'At Sargento Upholstery, our commitment to excellence is evident in every step of our meticulous craftsmanship. We take pride in the fact that each and every one of our products is a masterpiece of quality, designed to provide both aesthetics and durability that can withstand the test of time.', NULL),
('CRAFTSHEADERTITLE', 'services_craftsmanship', 'A Commitment to Craftsmanship Excellence', NULL),
('HOMEABOUTTEXT', 'home', 'Established by Eddielberto Sargento in Negros Occidental, Sargento Upholstery has quietly thrived in the furniture industry for nearly three and a half decades, specializing in custom-made furniture. Known for its commitment to quality craftsmanship and flexibility in adapting to changing market trends, the business has managed to sustain itself over the years. In 1997, a strategic decision was made to relocate the business to Taguig, a move aimed at expanding its reach and meeting the evolving demands of the market. This relocation, though not widely recognized, played a crucial role in the continued success of Sargento Upholstery. The combination of personalized craftsmanship, an understanding of local markets, and a strategic relocation has contributed to the business\'s resilience in a competitive industry.', NULL),
('HOMEABOUTTITLE', 'home', 'The Sargento Family Business', NULL),
('HOMECRAFTSTEXT', 'home', 'Take a tour of our successes as we consider the turning points and contributions that have created our story in the Upholstery industry', NULL),
('HOMECRAFTSTITLE', 'home', 'Our Previous Crafts', NULL),
('MAILADD', 'contact_us', 'sargentoupholstery@gmail.com', NULL),
('WORKSHEADERTEXT', 'services_works', 'At Sargento Upholstery, we take pride in our rich history of crafting exquisite furniture pieces that stand the test of time. Over the years, we have had the privilege of working on a diverse range of projects, from elegant sofas and armchairs to custom-designed furniture for commercial spaces. Our portfolio showcases our dedication to quality craftsmanship, attention to detail, and timeless design. Each piece tells a story of artistry and passion, reflecting our commitment to creating furniture that enhances the beauty and comfort of every space. Explore our past works and be inspired by the legacy of Sargento Upholstery.', NULL),
('WORKSHEADERTITLE', 'services_works', 'Past Creations: A Showcase of Artisan Furniture', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `faq_id` int NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question`, `answer`) VALUES
(1, 'How does the ordering process work?', 'Our ordering process is simple and convenient. Visit our Order webpage and select . Once we receive your submission, our team will review it and get in touch with you to discuss the specifics.'),
(2, 'What kind of orders can I place?', 'We specialize in repair worn-down furniture fabrics as well as crafting unique, made-to-order furniture pieces. Whether you\'re looking for a custom-sized sofa, a personalized dining table, or a unique cleopatra sofa, we can bring your ideas to life. Feel free to share your design preferences in the order form.'),
(3, 'Can I customize the materials used in my furniture?', 'Yes, you can customize the materials used in your furniture. We offer a variety of materials to choose from. You can specify your material preferences in the order form.'),
(4, 'What information should I provide in the order form?', 'The order form is designed to capture all the necessary details for your custom furniture. Please provide information such as dimensions, preferred materials, color preferences, and any specific design elements you have in mind. The more details you provide, the better we can tailor the furniture to your liking.'),
(5, 'How long does it take to receive my custom furniture?', 'The production time for custom furniture varies based on the complexity of the design and the materials chosen. Our team will provide you with a timeline once we review your order. Rest assured, we strive to complete orders in a timely manner without compromising on quality.'),
(6, 'Is there a deposit required for custom orders?', 'Yes, a deposit is required to initiate the production of your custom furniture. The exact amount will be communicated to you once we review your order. The remaining balance will be due upon completion, prior to delivery or pickup.'),
(7, 'Can I make changes to my order after submission?', 'We understand that preferences may evolve. If you need to make changes to your order, please contact us as soon as possible. We will do our best to accommodate changes, although some modifications may affect the timeline and cost.'),
(8, 'Do you offer delivery services?', 'Yes, we offer delivery services for your convenience. The delivery cost will be calculated based on your location. Alternatively, you can arrange to pick up your custom furniture directly from our workshop.'),
(9, 'What is your return policy for custom orders?', 'Since each piece is made to order based on your specific requirements, we do not accept returns on custom furniture. However, we are committed to ensuring your satisfaction, and we will address any issues or concerns to the best of our ability.'),
(10, 'How can I contact customer support for further assistance?', 'If you have any questions or need assistance, please reach out to us via the contact information provided on our website. We\'re here to help you throughout the custom furniture ordering process.');

-- --------------------------------------------------------

--
-- Table structure for table `notifs`
--

CREATE TABLE IF NOT EXISTS `notifs` (
  `notif_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `notif_msg` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  `redirect_link` tinytext,
  PRIMARY KEY (`notif_id`),
  KEY `notifs_user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifs`
--

INSERT INTO `notifs` (`notif_id`, `user_id`, `notif_msg`, `created_at`, `is_read`, `redirect_link`) VALUES
(1, 23, 'Test message -- hello!', '2024-05-20 02:26:34', 1, NULL),
(2, 22, 'New quotation form submitted by: Joaquin Luis Guevarra', '2024-05-20 10:10:30', 1, NULL),
(3, 22, 'New quotation form submitted by: Joaquin Luis Guevarra', '2024-05-21 07:09:19', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `furniture_type` varchar(32) NOT NULL,
  `order_type` enum('repair','mto') NOT NULL,
  `order_status` enum('new_order','pending_downpayment','ready_for_pickup','in_production','pending_fullpayment','out_for_delivery','received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'new_order',
  `ref_img_path` tinytext,
  `del_method` enum('third_party','self') NOT NULL,
  `del_address` text NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `quoted_price` float DEFAULT NULL,
  `is_accepted` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `refusal_reason` text,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `furniture_type`, `order_type`, `order_status`, `ref_img_path`, `del_method`, `del_address`, `notes`, `quoted_price`, `is_accepted`, `refusal_reason`, `last_updated`) VALUES
(1, 1, 'Table', 'repair', 'pending_downpayment', NULL, 'third_party', '123 Main St.', 'Needs repair for broken leg', 1230, 'accepted', NULL, '2024-05-14 03:53:45'),
(2, 2, 'Chair', 'repair', 'new_order', NULL, 'self', '456 Elm St.', 'Seat needs reupholstering', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(3, 3, 'Cabinet', 'repair', 'new_order', NULL, 'third_party', '789 Oak St.', 'Door hinge needs fixing', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(4, 4, 'Shelf', 'repair', 'new_order', NULL, 'self', '101 Pine St.', 'Cracked wood needs repair', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(5, 5, 'Bed', 'repair', 'new_order', NULL, 'third_party', '111 Cedar St.', 'Frame needs strengthening', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(6, 6, 'Desk', 'repair', 'new_order', NULL, 'self', '222 Maple St.', 'Drawer is stuck', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(7, 7, 'Table', 'repair', 'new_order', NULL, 'third_party', '333 Birch St.', 'Legs are wobbly', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(8, 8, 'Chair', 'repair', 'new_order', NULL, 'self', '444 Walnut St.', 'Backrest needs repair', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(9, 9, 'Cabinet', 'repair', 'new_order', NULL, 'third_party', '555 Ash St.', 'Drawer is off track', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(10, 10, 'Shelf', 'repair', 'new_order', NULL, 'self', '666 Sycamore St.', 'Shelves are sagging', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(11, 11, 'Bed', 'repair', 'new_order', NULL, 'third_party', '777 Cedar St.', 'Headboard needs repair', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(12, 12, 'Desk', 'repair', 'new_order', NULL, 'self', '888 Pine St.', 'Surface has scratches', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(13, 13, 'Table', 'repair', 'new_order', NULL, 'third_party', '999 Elm St.', 'Needs refinishing', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(14, 14, 'Chair', 'repair', 'new_order', NULL, 'self', '1010 Maple St.', 'Legs are uneven', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(15, 15, 'Cabinet', 'repair', 'new_order', NULL, 'third_party', '1111 Birch St.', 'Handle needs replacing', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(16, 16, 'Shelf', 'repair', 'new_order', NULL, 'self', '1212 Walnut St.', 'Bracket is loose', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(17, 17, 'Bed', 'repair', 'new_order', NULL, 'third_party', '1313 Ash St.', 'Side rail needs repair', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(18, 18, 'Desk', 'repair', 'new_order', NULL, 'self', '1414 Sycamore St.', 'Drawer is missing', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(19, 19, 'Table', 'repair', 'new_order', NULL, 'third_party', '1515 Cedar St.', 'Top needs refinishing', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(20, 20, 'Chair', 'repair', 'new_order', NULL, 'self', '1616 Pine St.', 'Seat is cracked', NULL, 'pending', NULL, '2024-05-14 00:49:52'),
(21, 1, 'Table', 'mto', 'new_order', NULL, 'third_party', '123 Main St.', 'Custom table design', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(22, 2, 'Chair', 'mto', 'new_order', NULL, 'self', '456 Elm St.', 'Custom chair color: blue', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(23, 3, 'Cabinet', 'mto', 'new_order', NULL, 'third_party', '789 Oak St.', 'Extra shelf required', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(24, 4, 'Shelf', 'mto', 'new_order', NULL, 'self', '101 Pine St.', 'Custom shelf design', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(25, 5, 'Bed', 'mto', 'new_order', NULL, 'third_party', '111 Cedar St.', 'Custom bed headboard', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(26, 6, 'Desk', 'mto', 'new_order', NULL, 'self', '222 Maple St.', 'Custom desk size', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(27, 7, 'Table', 'mto', 'new_order', NULL, 'third_party', '333 Birch St.', 'Custom table material: hardwood', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(28, 8, 'Chair', 'mto', 'new_order', NULL, 'self', '444 Walnut St.', 'Custom chair upholstery: leather', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(29, 9, 'Cabinet', 'mto', 'new_order', NULL, 'third_party', '555 Ash St.', 'Custom cabinet size', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(30, 10, 'Shelf', 'mto', 'new_order', NULL, 'self', '666 Sycamore St.', 'Custom shelf color: white', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(31, 11, 'Bed', 'mto', 'new_order', NULL, 'third_party', '777 Cedar St.', 'Custom bed storage: drawers', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(32, 12, 'Desk', 'mto', 'new_order', NULL, 'self', '888 Pine St.', 'Custom desk material: glass top', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(33, 13, 'Table', 'mto', 'new_order', NULL, 'third_party', '999 Elm St.', 'Custom table design: round', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(34, 14, 'Chair', 'mto', 'new_order', NULL, 'self', '1010 Maple St.', 'Custom chair height: bar stool', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(35, 15, 'Cabinet', 'mto', 'new_order', NULL, 'third_party', '1111 Birch St.', 'Custom cabinet color: black', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(36, 16, 'Shelf', 'mto', 'new_order', NULL, 'self', '1212 Walnut St.', 'Custom shelf size: 3 shelves', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(37, 17, 'Bed', 'mto', 'new_order', NULL, 'third_party', '1313 Ash St.', 'Custom bed headboard design', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(38, 18, 'Desk', 'mto', 'new_order', NULL, 'self', '1414 Sycamore St.', 'Custom desk size: 5ft x 2.5ft', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(39, 19, 'Table', 'mto', 'new_order', NULL, 'third_party', '1515 Cedar St.', 'Custom table material: marble top', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(40, 20, 'Chair', 'mto', 'new_order', NULL, 'self', '1616 Pine St.', 'Custom chair upholstery: fabric', NULL, 'pending', NULL, '2024-05-14 00:52:14'),
(41, 22, 'sofa', 'repair', 'new_order', 'uploadedImages/images.jpg', 'third_party', '456 Ipsum Blvd.', 'back right leg is broken, right armrest banister is broken', NULL, 'pending', NULL, '2024-05-19 09:55:45'),
(42, 22, 'sofa', 'repair', 'new_order', 'uploadedImages/images.jpg', 'third_party', '456 Ipsum Blvd.', 'back right leg is broken, right armrest banister is broken', NULL, 'pending', NULL, '2024-05-19 09:57:07'),
(43, 22, 'Sofa', 'repair', 'new_order', 'uploadedImages/images.jpg', 'third_party', '456 ipsum st.', 'wasak na siya pre', NULL, 'pending', NULL, '2024-05-20 02:43:59'),
(44, 22, 'table', 'repair', 'new_order', 'uploadedImages/abandoned-broken-furniture-outside-a-storeroom-EFAN2A.jpg', 'third_party', '573 Colorado St.', 'minor scratch', NULL, 'pending', NULL, '2024-05-20 02:46:29'),
(45, 22, 'Broken table ', 'repair', 'new_order', 'uploadedImages/abandoned-broken-furniture-outside-a-storeroom-EFAN2A.jpg', 'third_party', '7990 united states of ipsum', 'Legs are broken', NULL, 'pending', NULL, '2024-05-21 07:09:01');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `insert_est_date_after_acceptance` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.is_accepted = 'accepted' THEN
        UPDATE order_date
        SET est_completion_date = DATE_ADD(NOW(), INTERVAL 2 WEEK)
        WHERE order_id = NEW.order_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_payment_and_order_date` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE p_date DATE;
    SET p_date = CURRENT_DATE; -- Assuming placement date is current date

    INSERT INTO payment (order_id)
    VALUES (NEW.order_id); -- Assuming the initial amount is 0

    INSERT INTO order_date (order_id, placement_date)
    VALUES (NEW.order_id, p_date);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_date`
--

CREATE TABLE IF NOT EXISTS `order_date` (
  `order_id` int NOT NULL,
  `placement_date` date NOT NULL,
  `est_completion_date` date NOT NULL,
  KEY `order_id_fk_order_date` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_date`
--

INSERT INTO `order_date` (`order_id`, `placement_date`, `est_completion_date`) VALUES
(1, '2024-05-14', '2024-05-28'),
(2, '2024-05-14', '0000-00-00'),
(3, '2024-05-14', '0000-00-00'),
(4, '2024-05-14', '0000-00-00'),
(5, '2024-05-14', '0000-00-00'),
(6, '2024-05-14', '0000-00-00'),
(7, '2024-05-14', '0000-00-00'),
(8, '2024-05-14', '0000-00-00'),
(9, '2024-05-14', '0000-00-00'),
(10, '2024-05-14', '0000-00-00'),
(11, '2024-05-14', '0000-00-00'),
(12, '2024-05-14', '0000-00-00'),
(13, '2024-05-14', '0000-00-00'),
(14, '2024-05-14', '0000-00-00'),
(15, '2024-05-14', '0000-00-00'),
(16, '2024-05-14', '0000-00-00'),
(17, '2024-05-14', '0000-00-00'),
(18, '2024-05-14', '0000-00-00'),
(19, '2024-05-14', '0000-00-00'),
(20, '2024-05-14', '0000-00-00'),
(21, '2024-05-14', '0000-00-00'),
(22, '2024-05-14', '0000-00-00'),
(23, '2024-05-14', '0000-00-00'),
(24, '2024-05-14', '0000-00-00'),
(25, '2024-05-14', '0000-00-00'),
(26, '2024-05-14', '0000-00-00'),
(27, '2024-05-14', '0000-00-00'),
(28, '2024-05-14', '0000-00-00'),
(29, '2024-05-14', '0000-00-00'),
(30, '2024-05-14', '0000-00-00'),
(31, '2024-05-14', '0000-00-00'),
(32, '2024-05-14', '0000-00-00'),
(33, '2024-05-14', '0000-00-00'),
(34, '2024-05-14', '0000-00-00'),
(35, '2024-05-14', '0000-00-00'),
(36, '2024-05-14', '0000-00-00'),
(37, '2024-05-14', '0000-00-00'),
(38, '2024-05-14', '0000-00-00'),
(39, '2024-05-14', '0000-00-00'),
(40, '2024-05-14', '0000-00-00'),
(41, '2024-05-19', '0000-00-00'),
(42, '2024-05-19', '0000-00-00'),
(43, '2024-05-20', '0000-00-00'),
(44, '2024-05-20', '0000-00-00'),
(45, '2024-05-21', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `order_id` int NOT NULL,
  `payment_status` enum('unpaid','partially_paid','fully_paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'unpaid',
  `downpayment_method` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpayment_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `fullpayment_method` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fullpayment_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  KEY `order_id_fk_payment` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`order_id`, `payment_status`, `downpayment_method`, `downpayment_img`, `fullpayment_method`, `fullpayment_img`) VALUES
(1, 'unpaid', NULL, NULL, NULL, NULL),
(2, 'unpaid', NULL, NULL, NULL, NULL),
(3, 'unpaid', NULL, NULL, NULL, NULL),
(4, 'unpaid', NULL, NULL, NULL, NULL),
(5, 'unpaid', NULL, NULL, NULL, NULL),
(6, 'unpaid', NULL, NULL, NULL, NULL),
(7, 'unpaid', NULL, NULL, NULL, NULL),
(8, 'unpaid', NULL, NULL, NULL, NULL),
(9, 'unpaid', NULL, NULL, NULL, NULL),
(10, 'unpaid', NULL, NULL, NULL, NULL),
(11, 'unpaid', NULL, NULL, NULL, NULL),
(12, 'unpaid', NULL, NULL, NULL, NULL),
(13, 'unpaid', NULL, NULL, NULL, NULL),
(14, 'unpaid', NULL, NULL, NULL, NULL),
(15, 'unpaid', NULL, NULL, NULL, NULL),
(16, 'unpaid', NULL, NULL, NULL, NULL),
(17, 'unpaid', NULL, NULL, NULL, NULL),
(18, 'unpaid', NULL, NULL, NULL, NULL),
(19, 'unpaid', NULL, NULL, NULL, NULL),
(20, 'unpaid', NULL, NULL, NULL, NULL),
(21, 'unpaid', NULL, NULL, NULL, NULL),
(22, 'unpaid', NULL, NULL, NULL, NULL),
(23, 'unpaid', NULL, NULL, NULL, NULL),
(24, 'unpaid', NULL, NULL, NULL, NULL),
(25, 'unpaid', NULL, NULL, NULL, NULL),
(26, 'unpaid', NULL, NULL, NULL, NULL),
(27, 'unpaid', NULL, NULL, NULL, NULL),
(28, 'unpaid', NULL, NULL, NULL, NULL),
(29, 'unpaid', NULL, NULL, NULL, NULL),
(30, 'unpaid', NULL, NULL, NULL, NULL),
(31, 'unpaid', NULL, NULL, NULL, NULL),
(32, 'unpaid', NULL, NULL, NULL, NULL),
(33, 'unpaid', NULL, NULL, NULL, NULL),
(34, 'unpaid', NULL, NULL, NULL, NULL),
(35, 'unpaid', NULL, NULL, NULL, NULL),
(36, 'unpaid', NULL, NULL, NULL, NULL),
(37, 'unpaid', NULL, NULL, NULL, NULL),
(38, 'unpaid', NULL, NULL, NULL, NULL),
(39, 'unpaid', NULL, NULL, NULL, NULL),
(40, 'unpaid', NULL, NULL, NULL, NULL),
(41, 'unpaid', NULL, NULL, NULL, NULL),
(42, 'unpaid', NULL, NULL, NULL, NULL),
(43, 'unpaid', NULL, NULL, NULL, NULL),
(44, 'unpaid', NULL, NULL, NULL, NULL),
(45, 'unpaid', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pickup`
--

CREATE TABLE IF NOT EXISTS `pickup` (
  `order_id` int NOT NULL,
  `pickup_method` enum('third_party','self') NOT NULL,
  `pickup_address` text NOT NULL,
  KEY `order_id_fk_repair` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pickup`
--

INSERT INTO `pickup` (`order_id`, `pickup_method`, `pickup_address`) VALUES
(1, 'third_party', '123 Main Street'),
(2, 'third_party', '456 Elm Street'),
(3, 'self', '789 Oak Avenue'),
(4, 'third_party', '321 Pine Road'),
(5, 'self', '654 Maple Lane'),
(6, 'third_party', '987 Cedar Court'),
(7, 'self', '654 Birch Street'),
(8, 'third_party', '321 Willow Avenue'),
(9, 'third_party', '987 Spruce Lane'),
(10, 'self', '123 Fir Road'),
(11, 'third_party', '123 Main Street'),
(12, 'third_party', '456 Elm Street'),
(13, 'self', '789 Oak Avenue'),
(14, 'third_party', '321 Pine Road'),
(15, 'self', '654 Maple Lane'),
(16, 'third_party', '987 Cedar Court'),
(17, 'self', '654 Birch Street'),
(18, 'third_party', '321 Willow Avenue'),
(19, 'third_party', '987 Spruce Lane'),
(20, 'self', '123 Fir Road'),
(42, 'third_party', '123 Lorem St.'),
(43, 'third_party', '123 lorem st.'),
(44, 'third_party', '1928 McKinley Drv.'),
(45, 'third_party', '123 lorem st.');

-- --------------------------------------------------------

--
-- Table structure for table `reset_tokens`
--

CREATE TABLE IF NOT EXISTS `reset_tokens` (
  `email` varchar(50) DEFAULT NULL,
  `token_hash` varchar(64) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reset_tokens`
--

INSERT INTO `reset_tokens` (`email`, `token_hash`, `expires_at`) VALUES
('trishchanjimenez01@gmail.com', 'c4a852181bc05cea5314de6d365ba60fe86c2fefb323a0e6532a34ea3140b597', '2024-05-14 09:16:59'),
('trishchanjimenez06@gmail.com', '9c60c2620e6614cb8c372e4745d31461ae0ccedb9cfd728cccff2eac8d7d22ba', '2024-05-14 09:52:23'),
('trishchanjimenez01@gmail.com', 'eecc50628fb7d52c8ff71441070943110dcd176c672c18c9233e4488e93b867a', '2024-05-15 02:25:15'),
('trishchanjimenez01@gmail.com', '57f4e38202b655d5cbd8271b8935348f995f7d5d33839c531ff5850aeee03bd2', '2024-05-16 10:56:25'),
('trishchanjimenez01@gmail.com', 'a96f20700301030dc219d814ce47b9d02184ee893babc99d61406f108390fb8d', '2024-05-16 11:09:52'),
('trishchanjimenez01@gmail.com', 'a2a9a633eaae6213f4d62a216e2a164e01b2233c6978e17e710f6946ecd5d831', '2024-05-16 19:21:51'),
('trishchanjimenez01@gmail.com', '768bedfe04a41b5cb076e18a6272679c2cb8a61b754a05d746853c6637b776d4', '2024-05-16 20:09:30'),
('', '651d22a2c87f7582137d8fb0ff1e794f65f46b1368f1f4746eebd937b8e132a2', '2024-05-16 23:28:21'),
('', '1325da0d509185843266301a45411f9e2c6ad673f4565c74445b15e0eb4bae77', '2024-05-16 23:28:47'),
('trishchanjimenez01@gmail.com', '16a69176a03deaef932d2d2037e7523e21bc99f42a9ed0b2ae0ddf84669d077c', '2024-05-16 23:29:05'),
('trishchanjimenez01@gmail.com', 'fca74f9c325f9e9ed9409ba228e80d23bc76b062b60f5b41308595c6c98f9cbf', '2024-05-19 10:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply` text,
  `reply_date` datetime DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `order_review_fk` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `order_id`, `rating`, `comment`, `date`, `reply`, `reply_date`) VALUES
(1, 21, 4, 'Good service', '2024-05-14 10:11:28', NULL, NULL),
(2, 22, 5, 'Excellent workmanship', '2024-05-14 10:11:28', NULL, NULL),
(3, 23, 3, 'Satisfactory', '2024-05-14 10:11:28', NULL, NULL),
(4, 24, 4, 'Fast delivery', '2024-05-14 10:11:28', NULL, NULL),
(5, 25, 2, 'Poor quality', '2024-05-14 10:11:28', NULL, NULL),
(6, 26, 5, 'Highly recommended', '2024-05-14 10:11:28', NULL, NULL),
(7, 27, 4, 'Great communication', '2024-05-14 10:11:28', NULL, NULL),
(8, 28, 3, 'Average service', '2024-05-14 10:11:28', NULL, NULL),
(9, 29, 5, 'Very satisfied', '2024-05-14 10:11:28', NULL, NULL),
(10, 30, 1, 'Terrible experience', '2024-05-14 10:11:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE IF NOT EXISTS `review_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `review_id` int NOT NULL,
  `path` text NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `image_review_fk` (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_type` enum('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_address` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `user_type`, `user_address`, `contact_number`) VALUES
(1, 'Juan dela Cruz', 'juan@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', NULL, '09123456789'),
(2, 'Maria Clara', 'maria@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', NULL, '09234567890'),
(3, 'Pedro Penduko', 'pedro@example.com', '$2y$10$Y/mvA3bZ.96vZ67MK2OlFe2X7t66MOli5A0tQBKQsglvIJeHahFue', 'customer', NULL, '09345678901'),
(4, 'Teresa Magtanggol', 'teresa@example.com', '$2y$10$Mtu42SKTqeZGDfUwhlSMkewZE2ALM1IT.CtgjmZ8MvWwgq5Ti9TZm', 'customer', NULL, '09456789012'),
(5, 'Diego Silang', 'diego@example.com', '$2y$10$X4RJKuRPNu9PpdfiyVL0WuRBjdcKCGBnC4kkHGPVkctJ12ephksNO', 'customer', NULL, '09567890123'),
(6, 'Gabriela Silang', 'gabriela@example.com', '$2y$10$A2tGF2PXqruSu.tgkFl5vuWZJdCkpkyyWlSshUQwNexeL9V8VwdPy', 'customer', NULL, '09678901234'),
(7, 'Jose Rizal', 'jose@example.com', '$2y$10$CqR18V44JZRie/tfC1RwtOPK0LO04MgHOCfO5zAnW.v/p8QH3ux/6', 'customer', NULL, '09789012345'),
(8, 'Andres Bonifacio', 'andres@example.com', '$2y$10$V566ca4gSFvijfm9ep8xu.ZL0P7HJJjc8AOjdY4vNMQqA7kU1ib1C', 'customer', NULL, '09890123456'),
(9, 'Emilio Aguinaldo', 'emilio@example.com', '$2y$10$IEqX6y3iRqtSbp5Er9E52uaDF7DIkFaGw9CLz0rP7T4/1NVnSHQH6', 'customer', NULL, '09901234567'),
(10, 'Melchora Aquino', 'melchora@example.com', '$2y$10$VG8UE5zZsmbfZZWq1aNvOOeojnmUiISbsnaxeMR/w3s32BptpPWAK', 'customer', NULL, '09112345678'),
(11, 'Antonio Luna', 'antonio@example.com', '$2y$10$sIG5f6pQVOUp6Z7hPCBRse5Nye2u5XKvJu1zw.hFnL.e/LcRW0ta2', 'customer', NULL, '09223456789'),
(12, 'Gregoria de Jesus', 'gregoria@example.com', '$2y$10$GQaVWs.Cr/wa.iUR6E6EROJ4DY5K3pQAzr0uMUuLvUAKMogoih64O', 'customer', NULL, '09334567890'),
(13, 'Apolinario Mabini', 'apolinario@example.com', '$2y$10$St9Q3wIs0rRufASswms.5uTEzE2y7Fczr.FinVvw5YI3FESsE5sN2', 'customer', NULL, '09445678901'),
(14, 'Heneral Luna', 'heneral@example.com', '$2y$10$dx//Mn82CZ0IOr5hZGjt8.xsv3p.ajqGgD6TR8/5ThQUb1pVEHgnq', 'customer', NULL, '09556789012'),
(15, 'Andres Bonifacio', 'andresb@example.com', '$2y$10$2X/7K1h6mnE2UBBkLaW2AOSnBpVEVXXmpdrtgz6kcvz/xajhMmU.q', 'customer', NULL, '09667890123'),
(16, 'Emilio Jacinto', 'emilioj@example.com', '$2y$10$ijuW6lg57WDdqlrGCyRQp.v51P.NMC/ub9JHYQ04tbLGw07itjnS2', 'customer', NULL, '09778901234'),
(17, 'Lapu-Lapu', 'lapu-lapu@example.com', '$2y$10$PeQ4eyYKKWVCiYtvk2pE/ehmGGjJTKZ5V9.1TDjPEB.jzohkkflvi', 'customer', NULL, '09889012345'),
(18, 'Heneral del Pilar', 'heneralp@example.com', '$2y$10$GSRixKMthdbMtlKnw0LhAOTBlUyAGe7IygRc.zdBA7DA2bUwuaZye', 'customer', NULL, '09990123456'),
(19, 'Gabriela Silang', 'gabrielas@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', NULL, '09101234567'),
(20, 'Diego Silang', 'diegos@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', NULL, '09212345678'),
(21, 'Sargento Upholstery', 'sargento@gmail.com', '$2y$10$WhGORuLG.Fw6yO1ib7GhX.PK2xkNB7GDSI/5HaCbXsOycNlMLNgCy', 'admin', NULL, '09123412014'),
(22, 'Joaquin Luis Guevarra', 'joaquinguevarra177@gmail.com', '$2y$10$73WniKD5wNWwenDCH93TGuDWbmfm5WtzmfV/vmQQm9d892x0uD112', 'customer', NULL, '09052669619'),
(23, 'Sargento Upholstery 2', 'sargentoadmin@gmail.com', '$2y$10$N4HNoV6MtBGyB7Xu58Lmn.NIAMyzgVA/wbK/yWI8DAp0O..drS7iy', 'admin', NULL, '09123456789');

-- --------------------------------------------------------

--
-- Table structure for table `works`
--

CREATE TABLE IF NOT EXISTS `works` (
  `works_id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`works_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `works`
--

INSERT INTO `works` (`works_id`, `category`, `color`, `img_path`) VALUES
(5, 'armchair', 'purple', '/websiteimages/galleryimages/armchair2.png'),
(6, 'armchair', 'black', '/websiteimages/galleryimages/armchair3.png'),
(7, 'armchair', 'gray', '/websiteimages/galleryimages/armchair4.png'),
(8, 'armchair', 'black', '/websiteimages/galleryimages/armchair5.png'),
(9, 'armchair', 'black', '/websiteimages/galleryimages/armchair6.png'),
(10, 'armchair', 'gray', '/websiteimages/galleryimages/armchair7.png'),
(11, 'armchair', 'black', '/websiteimages/galleryimages/armchair8.png'),
(12, 'armchair', 'pink', '/websiteimages/galleryimages/armchair9.png'),
(13, 'armchair', 'brown', '/websiteimages/galleryimages/armchair10.jpg'),
(14, 'armchair', 'gray', '/websiteimages/galleryimages/armchair11.jpg'),
(15, 'armchair', 'brown', '/websiteimages/galleryimages/armchair12.jpg'),
(16, 'bed', 'brown', '/websiteimages/galleryimages/bed1.jpg'),
(17, 'chair', 'red', '/websiteimages/galleryimages/chair1.png'),
(18, 'chair', 'white', '/websiteimages/galleryimages/chair2.png'),
(19, 'cleopatra', 'red', '/websiteimages/galleryimages/cleopatra1.png'),
(20, 'cleopatra', 'cyan', '/websiteimages/galleryimages/cleopatra2.png'),
(21, 'cleopatra', 'white', '/websiteimages/galleryimages/cleopatra3.png'),
(22, 'cleopatra', 'red', '/websiteimages/galleryimages/cleopatra4.png'),
(23, 'cleopatra', 'pink', '/websiteimages/galleryimages/cleopatra5.png'),
(24, 'cleopatra', 'white', '/websiteimages/galleryimages/cleopatra6.png'),
(25, 'custom', 'brown', '/websiteimages/galleryimages/custom1.jpg'),
(26, 'custom', 'white', '/websiteimages/galleryimages/custom2.jpg'),
(27, 'custom', 'brown', '/websiteimages/galleryimages/custom3.jpg'),
(28, 'custom', 'black', '/websiteimages/galleryimages/custom4.jpg'),
(29, 'custom', 'brown', '/websiteimages/galleryimages/custom5.jpg'),
(30, 'custom', 'yellow', '/websiteimages/galleryimages/custom6.jpg'),
(31, 'custom', 'black', '/websiteimages/galleryimages/custom7.jpg'),
(32, 'custom', 'gray', '/websiteimages/galleryimages/custom8.jpg'),
(33, 'custom', 'white', '/websiteimages/galleryimages/custom9.jpg'),
(34, 'custom', 'blue', '/websiteimages/galleryimages/custom10.jpg'),
(35, 'loveseat', 'red', '/websiteimages/galleryimages/loveseat1.png'),
(36, 'loveseat', 'gray', '/websiteimages/galleryimages/loveseat2.png'),
(37, 'loveseat', 'red', '/websiteimages/galleryimages/loveseat3.png'),
(38, 'loveseat', 'white', '/websiteimages/galleryimages/loveseat4.png'),
(39, 'loveseat', 'brown', '/websiteimages/galleryimages/loveseat5.jpg'),
(40, 'loveseat', 'blue', '/websiteimages/galleryimages/loveseat6.jpg'),
(41, 'sofa', 'gray', '/websiteimages/galleryimages/sofa1.png'),
(42, 'sofa', 'orange', '/websiteimages/galleryimages/sofa2.png'),
(43, 'sofa', 'white', '/websiteimages/galleryimages/sofa3.png'),
(44, 'sofa', 'blue', '/websiteimages/galleryimages/sofa4.png'),
(45, 'sofa', 'white', '/websiteimages/galleryimages/sofa5.png'),
(46, 'sofa', 'blue', '/websiteimages/galleryimages/sofa6.png'),
(47, 'sofa', 'red', '/websiteimages/galleryimages/sofa7.png'),
(48, 'sofa', 'blue', '/websiteimages/galleryimages/sofa8.png'),
(49, 'sofa', 'purple', '/websiteimages/galleryimages/sofa9.png'),
(50, 'sofa', 'white', '/websiteimages/galleryimages/sofa10.png'),
(51, 'sofa', 'cyan', '/websiteimages/galleryimages/sofa11.png'),
(52, 'sofa', 'white', '/websiteimages/galleryimages/sofa12.png'),
(53, 'sofa', 'orange', '/websiteimages/galleryimages/sofa13.png'),
(54, 'sofa', 'black', '/websiteimages/galleryimages/sofa14.png'),
(55, 'sofa', 'blue', '/websiteimages/galleryimages/sofa15.png'),
(56, 'sofa', 'white', '/websiteimages/galleryimages/sofa16.png'),
(57, 'sofa', 'cyan', '/websiteimages/galleryimages/sofa17.png'),
(58, 'sofa', 'brown', '/websiteimages/galleryimages/sofa18.jpg'),
(59, 'sofa', 'red', '/websiteimages/galleryimages/sofa19.jpg'),
(60, 'sofa', 'black', '/websiteimages/galleryimages/sofa20.jpg'),
(66, 'Sofa', 'Blue', '/websiteimages/galleryimages/repimg6.jpg');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chat_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifs`
--
ALTER TABLE `notifs`
  ADD CONSTRAINT `notifs_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `order_date`
--
ALTER TABLE `order_date`
  ADD CONSTRAINT `order_id_fk_order_date` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `order_id_fk_payment` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `pickup`
--
ALTER TABLE `pickup`
  ADD CONSTRAINT `order_id_fk_repair` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `order_review_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `image_review_fk` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`review_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
