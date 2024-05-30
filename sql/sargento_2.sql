-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2024 at 04:32 AM
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
-- Database: `sargento_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int NOT NULL,
  `user_id` int NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `address`) VALUES
(1, 21, '123 Rizal, Taguig City'),
(7, 22, '1123 Rizal Taguig City'),
(9, 21, 'Phase 1, Taguig City'),
(10, 21, 'Maya St. Rizal Taguig City'),
(14, 24, '123 Rizal St. Brgy Rizal Taguig City'),
(15, 25, '123 Rizal, Makati City'),
(16, 25, 'Blk 12 Agila, Taguig city'),
(17, 25, '123 Lawin St.');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `message` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `sender_id`, `customer_id`, `message`, `timestamp`, `is_read`) VALUES
(1, 22, 22, 'imissyou', '2024-05-21 04:43:35', 0),
(2, 22, 22, 'mwa', '2024-05-21 04:43:36', 0),
(3, 23, 22, 'imissyou2bbk', '2024-05-21 04:56:33', 0),
(4, 22, 22, 'jkjkjk', '2024-05-21 04:57:20', 0),
(5, 22, 22, 'l,l,l', '2024-05-21 04:57:23', 0),
(6, 23, 22, 'justine joven casiano\r\n', '2024-05-21 04:58:17', 0),
(7, 23, 22, 'oyoy', '2024-05-21 05:00:56', 0),
(8, 22, 22, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2024-05-21 05:02:26', 0),
(9, 22, 22, 'putanginamo', '2024-05-21 07:09:38', 0),
(10, 21, 22, 'fasdfasf', '2024-05-22 11:28:02', 0),
(11, 21, 21, 'hello', '2024-05-23 00:13:15', 0),
(12, 21, 21, 'fadfa', '2024-05-23 00:13:21', 0),
(13, 21, 22, 'dfafa', '2024-05-23 00:13:39', 0),
(14, 21, 22, 'fasf', '2024-05-23 17:17:01', 0),
(15, 24, 22, 'sfhahfjaf', '2024-05-24 03:10:40', 0),
(16, 24, 22, 'a', '2024-05-24 04:24:37', 0),
(17, 24, 22, 'hello', '2024-05-24 04:26:44', 0),
(18, 24, 22, 'hi', '2024-05-24 04:26:55', 0),
(19, 25, 25, 'hello', '2024-05-27 22:24:58', 0),
(20, 24, 25, 'a', '2024-05-27 22:25:09', 1),
(21, 24, 25, 'hello', '2024-05-27 22:25:14', 1),
(22, 25, 25, 'hi', '2024-05-27 22:25:21', 0),
(23, 24, 25, 'ano po kailangan nyo', '2024-05-27 22:25:42', 1),
(24, 24, 25, 'anno', '2024-05-27 22:25:46', 1),
(25, 25, 25, 'saf', '2024-05-28 01:03:26', 0),
(26, 25, 25, 'fsda', '2024-05-28 01:03:34', 0),
(27, 25, 25, 'd', '2024-05-28 01:03:36', 0),
(28, 25, 25, 'DSD', '2024-05-28 01:04:05', 0),
(29, 25, 25, 'asd', '2024-05-28 03:08:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `content_id` varchar(255) NOT NULL,
  `page` varchar(50) NOT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `content_text` text,
  `image` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`content_id`, `page`, `content_type`, `content_text`, `image`) VALUES
('ABOUT_INTRO_DESC', 'about_us', 'INTRO_DESC', 'The skilled artisans at Sargento Upholstery have dedicated their lives to perfecting the craft of creating your dream furniture. Known throughout the metro for their expertise, the team includes talented female workers who contribute significantly to the business\'s success. With tireless dedication, Mr. Eddie Murphy Sargento and Mrs. Journa Joy Sargento continue the family legacy. Together, they ensure Sargento Upholstery delivers the highest quality furniture to meet your every need.', NULL),
('ABOUT_INTRO_IMG', 'about_us', 'INTRO_IMG', NULL, '/websiteimages/banner-images/about-us.png'),
('ABOUT_INTRO_TITLE', 'about_us', 'INTRO_TITLE', 'Meet The Team', NULL),
('ABOUTHISTORYTEXT', 'about_us', NULL, 'Established by Eddielberto Sargento in Negros Occidental, Sargento Upholstery has quietly thrived in the furniture industry for nearly three and a half decades, specializing in custom-made furniture. Known for its commitment to quality craftsmanship and flexibility in adapting to changing market trends, the business has managed to sustain itself over the years. In 1997, a strategic decision was made to relocate the business to Taguig, a move aimed at expanding its reach and meeting the evolving demands of the market. This relocation, though not widely recognized, played a crucial role in the continued success of Sargento Upholstery.', NULL),
('ABOUTHISTORYTITLE', 'about_us', NULL, 'History of Sargento Upholstery', NULL),
('ABOUTVALUESSUBHEADING1', 'about_us', NULL, 'Quality Materials', NULL),
('ABOUTVALUESSUBHEADING2', 'about_us', NULL, 'Craftsmanship Excellence', NULL),
('ABOUTVALUESSUBHEADING3', 'about_us', NULL, 'Heritage and Innovationa', NULL),
('ABOUTVALUESSUBHEADING4', 'about_us', NULL, 'Customer-Centric', NULL),
('ABOUTVALUESSUBTEXT1', 'about_us', NULL, 'Emphasizing the importance of using high-grade materials in our products.', NULL),
('ABOUTVALUESSUBTEXT2', 'about_us', NULL, 'Focusing on the artistry and skill involved in creating our goods or services.', NULL),
('ABOUTVALUESSUBTEXT3', 'about_us', NULL, 'Balancing tradition with a forward-thinking approach, acknowledging both history and modern advancements.', NULL),
('ABOUTVALUESSUBTEXT4', 'about_us', NULL, 'Prioritizing our customers needs and satisfaction at the core of our business practices.', NULL),
('ABOUTVALUESTITLE', 'about_us', NULL, 'Our Values & Philosophy', NULL),
('BUSIADD', 'contact_us', NULL, 'Blk 68 Lot 41 Lapu-Lapu St., Upper Bicutan, Taguig City', NULL),
('BUSIHRS', 'contact_us', NULL, '8:00 AM - 5:00 PM', NULL),
('CONTACT_INTRO_DESC', 'contact_us', 'INTRO_DESC', 'Feel free to reach out us for any inquiries or concerns. Our contact details such as mailing address and business location are available below.', NULL),
('CONTACT_INTRO_IMG', 'contact_us', 'INTRO_IMG', NULL, '/websiteimages/banner-images/contact-us.png'),
('CONTACT_INTRO_TITLE', 'contact_us', 'INTRO_TITLE', 'Get In Touch With Us', NULL),
('CPNNUM', 'contact_us', NULL, '0999 - 406 - 8816', NULL),
('CRAFTS_INTRO_DESC', 'services_craftsmanship', 'INTRO_DESC', 'At Sargento Upholstery, our commitment to excellence is evident in every step of our meticulous craftsmanship. We take pride in the fact that our products are a masterpiece of quality, designed to provide both aesthetics and durability.', NULL),
('CRAFTS_INTRO_IMG', 'services_craftsmanship', 'INTRO_IMG', NULL, '/websiteimages/banner-images/craftsmanship.png'),
('CRAFTS_INTRO_TITLE', 'services_craftsmanship', 'INTRO_TITLE', 'A Commitment to Craftsmanship Excellence', NULL),
('CRAFTS_OUTRO_CTALINK', 'services_craftsmanship', 'OUTRO_CTALINK', '/services_works.php', NULL),
('CRAFTS_OUTRO_CTATEXT', 'services_craftsmanship', 'OUTRO_CTATEXT', 'View Works', NULL),
('CRAFTS_OUTRO_IMG', 'services_craftsmanship', 'OUTRO_IMG', NULL, '/websiteimages/banner-images/works.png'),
('CRAFTS_OUTRO_TITLE', 'services_craftsmanship', 'OUTRO_TITLE', 'Explore Our Masterpieces', NULL),
('CRAFTSCARDTEXT1', 'services_craftsmanship', NULL, 'Our journey into crafting superior furniture begins with the careful selection of materials, ensuring that only the finest components are used. For the sturdy frame and foundation of our pieces, we rely on treated palochina wood, a choice renowned for its remarkable durability and exceptional robustness. This wood is not just any wood; it\\\'s a testament to our dedication to creating furniture that is built to last.', NULL),
('CRAFTSCARDTEXT2', 'services_craftsmanship', NULL, 'Moving on to the heart of our creations, we employ the luxuriously comfortable uratex foam. This premium foam is carefully chosen to provide a plush and supportive seating experience, allowing you to sink into your furniture while still enjoying the firm support that ensures your comfort and satisfaction for years to come.fasdfasaf', NULL),
('CRAFTSCARDTEXT3', 'services_craftsmanship', NULL, 'But it doesnt end there. Our commitment to quality extends to the fabrics we use. We insist on utilizing only the best, such as RGC fabric and German leather, both celebrated for their exceptional texture, enduring beauty, and ability to withstand wear and tear gracefully. These materials not only add to the aesthetic appeal of our pieces but also ensure that your furniture retains its allure and resilience even after years of use.', NULL),
('CRAFTSCARDTITLE1', 'services_craftsmanship', NULL, 'The Foundation of Durability', NULL),
('CRAFTSCARDTITLE2', 'services_craftsmanship', NULL, 'Luxurious Comfort', NULL),
('CRAFTSCARDTITLE3', 'services_craftsmanship', NULL, 'Exceptional Fabrics', NULL),
('CRAFTSFOOTERTEXT', 'services_craftsmanship', NULL, 'This meticulous and selective process of material sourcing and crafting guarantees that each creation that bears the Sargento Upholstery name is not just a piece of furniture; it is a work of art, meticulously built with a devotion to quality, beauty, and longevity. We take great pride in creating furniture that not only fulfills its purpose but enriches the spaces and lives it graces, turning every room into a masterpiece of comfort and elegance.', NULL),
('HOMEABOUTTEXT', 'home', NULL, 'Established by Eddielberto Sargento in Negros Occidental, Sargento Upholstery has quietly thrived in the furniture industry for nearly three and a half decades, specializing in custom-made furniture. Known for its commitment to quality craftsmanship and flexibility in adapting to changing market trends, the business has managed to sustain itself over the years. In 1997, a strategic decision was made to relocate the business to Taguig, a move aimed at expanding its reach and meeting the evolving demands of the market. This relocation, though not widely recognized, played a crucial role in the continued success of Sargento Upholstery. The combination of personalized craftsmanship, an understanding of local markets, and a strategic relocation has contributed to the business\\\'s resilience in a competitive industry.', NULL),
('HOMEABOUTTITLE', 'home', NULL, 'The Sargento Family Business', NULL),
('HOMECRAFTSTEXT', 'home', NULL, 'Take a tour of our successes as we consider the turning points and contributions that have created our story in the Upholstery industry', NULL),
('HOMECRAFTSTITLE', 'home', NULL, 'Our Previous Crafts', NULL),
('HOMETEAMPICTURE', 'home', NULL, NULL, '/websiteimages/teampicture.png'),
('HOMETESTIMONIALAUTHOR1', 'home', NULL, '-JB von Kampfer', NULL),
('HOMETESTIMONIALAUTHOR2', 'home', NULL, '-Albert Mendoza', NULL),
('HOMETESTIMONIALCOMMENT1', 'home', NULL, 'Very responsive, Good and trustworthy. Plus the quality of their work is pretty good! Two-thumbs up for Sargento Upholstery.', NULL),
('HOMETESTIMONIALCOMMENT2', 'home', NULL, 'Quality at pangmatagalan talaga ang gawa ng Sargento Upholstery. Kudos!!', NULL),
('HOMETESTIMONIALTITLE', 'home', NULL, 'Our Clients\' Testimonials', NULL),
('MAILADD', 'contact_us', NULL, 'sargentoupholstery@gmail.com', NULL),
('QUOTE_INTRO_DESC', 'quote', 'INTRO_DESC', ' ', NULL),
('QUOTE_INTRO_IMG', 'quote', 'INTRO_IMG', NULL, '/websiteimages/banner-images/order.png'),
('QUOTE_INTRO_TITLE', 'quote', 'INTRO_TITLE', 'Design, Craft, Quote - All in One Place', ''),
('QUOTE_OUTRO_CTALINK', 'quote', 'OUTRO_CTALINK', '/testimonials.php', NULL),
('QUOTE_OUTRO_CTATEXT', 'quote', 'OUTRO_CTATEXT', 'See Reviews', NULL),
('QUOTE_OUTRO_IMG', 'quote', 'OUTRO_IMG', NULL, '/websiteimages/banner-images/testimonials.png'),
('QUOTE_OUTRO_TITLE', 'quote', 'OUTRO_TITLE', 'See What Our Clients Say', NULL),
('TESTIMONIALS_INTRO_DESC', 'testimonials', 'INTRO_DESC', 'At Sargento Upholstery, our commitment to excellence is evident in every step of our meticulous craftsmanship. We take pride in the fact that each and every one of our products is a masterpiece of quality, designed to provide both aesthetics and durability that can withstand the test of time.', NULL),
('TESTIMONIALS_INTRO_IMG', 'testimonials', 'INTRO_IMG', NULL, '/websiteimages/banner-images/testimonials.png'),
('TESTIMONIALS_INTRO_TITLE', 'testimonials', 'INTRO_TITLE', 'Real Reviews, Real Satisfaction', NULL),
('TESTIMONIALS_OUTRO_CTALINK', 'testimonials', 'OUTRO_CTALINK', '/services_works.php', ''),
('TESTIMONIALS_OUTRO_CTATEXT', 'testimonials', 'OUTRO_CTATEXT', 'See More', ''),
('TESTIMONIALS_OUTRO_IMG', 'testimonials', 'OUTRO_IMG', NULL, '/websiteimages/banner-images/works.png'),
('TESTIMONIALS_OUTRO_TITLE', 'testimonials', 'OUTRO_TITLE', 'Explore Our Masterpieces', NULL),
('WORKS_INTRO_DESC', 'services_works', 'INTRO_DESC', 'At Sargento Upholstery, our rich history of crafting timeless furniture pieces reflects our dedication to quality craftsmanship and attention to detail. From elegant sofas to custom-designed commercial furniture, each piece tells a story of artistry and passion.', NULL),
('WORKS_INTRO_IMG', 'services_works', 'INTRO_IMG', NULL, '/websiteimages/banner-images/works.png'),
('WORKS_INTRO_TITLE', 'services_works', 'INTRO_TITLE', 'Past Creations: A Showcase of Artisan Furniture', NULL),
('WORKS_OUTRO_CTALINK', 'services_works', 'OUTRO_CTALINK', '/services_craftsmanship.php', NULL),
('WORKS_OUTRO_CTATEXT', 'services_works', 'OUTRO_CTATEXT', 'See More', NULL),
('WORKS_OUTRO_IMG', 'services_works', 'OUTRO_IMG', NULL, '/websiteimages/banner-images/craftsmanship.png'),
('WORKS_OUTRO_TITLE', 'services_works', 'OUTRO_TITLE', 'Discover Our Artistry', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customs`
--

CREATE TABLE `customs` (
  `custom_id` int NOT NULL,
  `dimensions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `materials` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fabric` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customs`
--

INSERT INTO `customs` (`custom_id`, `dimensions`, `materials`, `fabric`, `color`) VALUES
(1, 'test', 'test', 'test', 'test'),
(2, '1m x 1m x 2.5m', 'metal', 'leather', 'black'),
(3, '1m x 1m x 2.5m', 'metal', 'leather', 'black'),
(4, 'test', 'test', 'test', 'test'),
(5, 'test', 'test', 'test', 'test'),
(6, '0.5m x 2.5m x 1.5m', 'galvanized square steel beams, eco-friendly wood veneers', '', 'sky blue'),
(7, '', 'galvanized square steel beams, eco-friendly wood veneer', 'linen from auntie&#039;s house', 'light brown'),
(8, '', '', 'leather', 'orange'),
(9, '12x12x12', '', 'Linen', ''),
(10, '12x12x12', '', 'Linen', 'black'),
(11, '12x12x12', '', 'Linen', 'black'),
(12, '2x2x2', 'wood', '', ''),
(13, '12x12x12', '', 'Linen', 'black'),
(14, '12x6x2', 'wood', 'silk', 'black'),
(15, 'asdfa', 'asdf', '', ''),
(16, '', '', 'adfa', ''),
(17, '12x12x12', 'wood', 'Linen', 'black'),
(18, '12x12x12', 'Uratex Foam', 'Linen', 'black'),
(19, '0.001m^3', 'galvanized square steel, eco-friendly wood veneer', '', 'White paint'),
(20, '', 'Metal', 'Leather', 'Orange'),
(21, '12x12x12', '', 'cotton', ''),
(22, '', 'wood', '', ''),
(23, '', '', '', 'black'),
(24, 'sadfasdf', 'asfdasdf', 'asdfasd', 'asdfadsf'),
(25, 'asdfasdf', 'asdfasdf', '', ''),
(26, '', '', '', ''),
(27, '1x2x3', 'wood', '', 'blue'),
(28, '1x2x3', 'metal', 'leather', 'blue'),
(29, '2x4x5', 'wood', 'linen', 'orange');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `delivery_method` enum('third_party','self') DEFAULT NULL,
  `delivery_address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `order_id`, `delivery_method`, `delivery_address`) VALUES
(1, 4, 'third_party', '1123 Rizal Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `downpayment`
--

CREATE TABLE `downpayment` (
  `downpayment_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `downpay_method` enum('gcash','paymaya','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpay_img_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `downpay_account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpay_amount` float DEFAULT NULL,
  `downpay_ref_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpay_verification_status` enum('waiting_for_verification','needs_reverification','verified') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `fullpayment`
--

CREATE TABLE `fullpayment` (
  `fullpay_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `fullpay_method` enum('gcash','paymaya','cash') DEFAULT NULL,
  `fullpay_img_path` text,
  `fullpay_account_name` varchar(255) DEFAULT NULL,
  `fullpay_amount` float DEFAULT NULL,
  `fullpay_ref_no` varchar(255) DEFAULT NULL,
  `fullpay_verification_status` enum('waiting_for_verification','needs_reverification','verified') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `quote_id` int DEFAULT NULL,
  `custom_id` int DEFAULT NULL,
  `furniture` varchar(255) DEFAULT NULL,
  `description` text,
  `item_ref_img` text,
  `quantity` int UNSIGNED DEFAULT NULL,
  `item_price` float DEFAULT NULL,
  `item_status` enum('pending','approved','modified','rejected','accepted','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `quote_id`, `custom_id`, `furniture`, `description`, `item_ref_img`, `quantity`, `item_price`, `item_status`) VALUES
(1, 3, NULL, 'Sofa', 'sira ung foam', NULL, 1, NULL, 'pending'),
(2, 7, NULL, 'sofa', 'asdfasd', NULL, 3, NULL, 'pending'),
(3, 8, NULL, 'sofa', 'sadaf', NULL, 1, NULL, 'pending'),
(4, 8, NULL, 'dining seat', 'asdfasd', NULL, 4, NULL, 'pending'),
(5, 9, NULL, 'Sofa', 'Malaki', NULL, 1, NULL, 'pending'),
(6, 10, NULL, 'sadfa', 'adfas', NULL, 1, NULL, 'pending'),
(7, 11, NULL, 'sdfasfa', 'asdfasdf', NULL, 1, NULL, 'pending'),
(8, 12, NULL, 'asdfasdf', 'asdfasf', NULL, 1, NULL, 'pending'),
(9, 13, NULL, 'asdfasdf', 'dsafsaf', NULL, 1, 1233, 'pending'),
(10, 14, NULL, 'asfasdf', 'asdfasdf', NULL, 1, 12334, 'pending'),
(11, 15, NULL, 'afasd', 'asdfasdf', NULL, 1, 2000, 'pending'),
(12, 16, NULL, 'asfasd', 'adfasdf', 'uploadedImages/referenceImages/testimg1.jpg', 1, 1231, 'pending'),
(13, 17, NULL, 'asdfasd', 'afsasdfasd', '', 1, 1231, 'pending'),
(14, 17, NULL, 'fasdfas', 'dsafsdfasdfasdf', 'uploadedImages/referenceImages/table.png', 1, 1133, 'pending'),
(15, 18, NULL, 'safsdf', 'asdfasdfa', NULL, 1, 1442, 'pending'),
(16, 18, NULL, 'adsfasdf', 'asdfasdf', 'uploadedImages/referenceImages/prog2.png', 1, 1590, 'pending'),
(17, 19, NULL, 'asdfasf', 'asfas', 'uploadedImages/referenceImages/cor.png', 1, 1231, 'pending'),
(18, 19, NULL, 'asdfasdf', 'asdfasdf', NULL, 1, 13123, 'pending'),
(19, 19, NULL, 'asdfa', 'asdfa', 'uploadedImages/referenceImages/cor.png', 1, 3124, 'pending'),
(20, 20, NULL, 'Test furniture', 'Test description', NULL, 2, NULL, 'pending'),
(21, 21, NULL, 'Experiment furniture', 'Experiment desc', 'uploadedImages/referenceImages/61F8CTCqVVL._AC_SL1001_.jpg', 3, NULL, 'pending'),
(22, 22, 28, 'Pilot furniture', 'pilot desc', 'uploadedImages/referenceImages/Stool.jpg', 6, NULL, 'pending'),
(23, 22, 29, 'Example furniture', 'example desc', 'uploadedImages/referenceImages/images.jpg', 2, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `notifs`
--

CREATE TABLE `notifs` (
  `notif_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `notif_msg` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  `redirect_link` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifs`
--

INSERT INTO `notifs` (`notif_id`, `user_id`, `notif_msg`, `created_at`, `is_read`, `redirect_link`) VALUES
(1, 23, 'Test message -- hello!', '2024-05-20 02:26:34', 1, NULL),
(2, 22, 'New quotation form submitted by: Joaquin Luis Guevarra', '2024-05-20 10:10:30', 1, NULL),
(3, 22, 'New quotation form submitted by: Joaquin Luis Guevarra', '2024-05-21 07:09:19', 1, NULL),
(4, 21, 'New quotation form submitted by: Sargento Upholstery', '2024-05-23 13:53:07', 0, NULL),
(5, 21, 'New quotation form submitted by: Sargento Upholstery', '2024-05-23 14:07:43', 0, NULL),
(6, 21, 'New quotation form submitted by: Sargento Upholstery', '2024-05-23 14:25:30', 0, NULL),
(7, 24, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-23 22:14:44', 0, '/my/orders.php'),
(8, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 09:29:11', 0, '/my/user_orders.php'),
(9, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 09:41:50', 0, '/my/user_orders.php'),
(10, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 09:46:07', 0, '/my/user_orders.php'),
(11, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 09:47:47', 0, '/my/user_orders.php'),
(12, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 09:49:31', 0, '/my/user_orders.php'),
(13, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:02:48', 0, '/my/user_orders.php'),
(14, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:04:20', 0, '/my/user_orders.php'),
(15, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:05:11', 0, '/my/user_orders.php'),
(16, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:08:11', 0, '/my/user_orders.php'),
(17, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:10:57', 0, '/my/user_orders.php'),
(18, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:15:24', 0, '/my/user_orders.php'),
(19, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:16:04', 0, '/my/user_orders.php'),
(20, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:18:18', 0, '/my/user_orders.php'),
(21, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:18:37', 0, '/my/user_orders.php'),
(22, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:21:57', 0, '/my/user_orders.php'),
(23, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 10:22:36', 0, '/my/user_orders.php'),
(24, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 13:49:20', 1, '/my/user_orders.php'),
(25, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 14:25:09', 0, '/my/user_orders.php'),
(26, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 23:39:06', 0, '/my/user_orders.php'),
(27, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 23:40:43', 0, '/my/user_orders.php'),
(28, 22, 'Your request for quotation has been evaluated', '2024-05-27 09:56:17', 0, '/my/user_order_details.php?quote-id=2'),
(29, 22, 'Your request for quotation has been evaluated', '2024-05-27 09:57:55', 0, '/my/user_order_details.php?quote-id=3'),
(30, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-27 12:19:32', 0, '/my/user_orders.php'),
(31, 25, 'Your request for quotation has been evaluated', '2024-05-27 12:23:02', 0, '/my/user_order_details.php?quote-id=4'),
(32, 25, 'Your request for quotation has been evaluated', '2024-05-27 23:07:23', 0, '/my/user_order_details.php?quote-id=9'),
(33, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 00:15:21', 0, '/my/user_orders.php'),
(34, 24, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 01:08:00', 0, '/my/user_orders.php'),
(35, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 01:35:39', 0, '/my/user_orders.php'),
(36, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 01:36:30', 0, '/my/user_orders.php'),
(37, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 01:39:12', 0, '/my/user_orders.php'),
(38, 25, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 01:41:29', 0, '/my/user_orders.php'),
(39, 25, 'Your request for quotation has been evaluated', '2024-05-28 02:50:41', 0, '/my/user_order_details.php?quote-id=17'),
(40, 25, 'Your request for quotation has been evaluated', '2024-05-28 03:56:56', 0, '/my/user_order_details.php?quote-id=16'),
(41, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-28 07:45:54', 0, '/my/user_orders.php'),
(42, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:06:03', 0, '/my/user_orders.php'),
(43, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:09:58', 0, '/my/user_orders.php'),
(44, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:11:35', 0, '/my/user_orders.php'),
(45, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:15:02', 0, '/my/user_orders.php'),
(46, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:16:50', 0, '/my/user_orders.php'),
(47, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:18:55', 0, '/my/user_orders.php'),
(48, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:19:24', 0, '/my/user_orders.php'),
(49, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:20:06', 0, '/my/user_orders.php'),
(50, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:20:26', 0, '/my/user_orders.php'),
(51, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:21:39', 0, '/my/user_orders.php'),
(52, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:25:02', 0, '/my/user_orders.php'),
(53, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:26:29', 0, '/my/user_orders.php'),
(54, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:28:02', 0, '/my/user_orders.php'),
(55, 26, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 03:28:52', 0, '/my/user_orders.php'),
(56, 22, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 21:40:17', 0, '/my/user_orders.php'),
(57, 22, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 21:41:26', 0, '/my/user_orders.php'),
(58, 22, 'You have successfully placed a quote request. Please wait for us to evaluate your request', '2024-05-29 23:13:54', 0, '/my/user_orders.php');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `quote_id` int DEFAULT NULL,
  `order_phase` enum('pending_downpayment','awaiting_furniture','in_production','pending_fullpayment','out_for_delivery','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending_downpayment',
  `payment_phase` enum('unpaid','partially_paid','fully_paid') NOT NULL DEFAULT 'unpaid',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `est_completion_date` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `quote_id`, `order_phase`, `payment_phase`, `created_at`, `est_completion_date`, `updated_at`) VALUES
(2, 18, 'pending_downpayment', 'unpaid', '2024-05-29 20:49:23', '2024-06-12', '2024-05-29 12:49:23'),
(3, 19, 'pending_downpayment', 'unpaid', '2024-05-29 20:50:50', '2024-06-12', '2024-05-29 12:50:50'),
(4, 22, 'pending_downpayment', 'unpaid', '2024-05-30 07:27:58', '2024-06-13', '2024-05-29 23:27:58');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `set_est_completion_date` BEFORE INSERT ON `orders` FOR EACH ROW SET NEW.est_completion_date = DATE_ADD(NOW(), INTERVAL 2 WEEK)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pickup`
--

CREATE TABLE `pickup` (
  `pickup_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `pickup_method` enum('third_party','self') DEFAULT NULL,
  `pickup_address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pickup`
--

INSERT INTO `pickup` (`pickup_id`, `order_id`, `pickup_method`, `pickup_address`) VALUES
(1, 4, 'third_party', '1123 Rizal Taguig City'),
(2, 4, 'third_party', '1123 Rizal Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `quote_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `service_type` enum('','repair','mto') DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `quote_status` enum('pending','approved','modified','rejected','accepted','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`quote_id`, `customer_id`, `service_type`, `total_price`, `quote_status`, `created_at`, `updated_at`) VALUES
(3, 26, 'repair', NULL, 'pending', '2024-05-29 03:06:03', '2024-05-29 05:52:35'),
(7, 26, 'mto', NULL, 'pending', '2024-05-29 03:09:58', '2024-05-29 05:52:35'),
(8, 26, 'mto', NULL, 'pending', '2024-05-29 03:11:35', '2024-05-29 05:52:35'),
(9, 26, 'repair', NULL, 'pending', '2024-05-29 03:15:02', '2024-05-29 05:52:35'),
(10, 26, 'repair', NULL, 'pending', '2024-05-29 03:16:50', '2024-05-29 05:52:35'),
(11, 26, 'repair', NULL, 'pending', '2024-05-29 03:18:55', '2024-05-29 05:52:35'),
(12, 26, 'repair', NULL, 'pending', '2024-05-29 03:19:23', '2024-05-29 05:52:35'),
(13, 26, 'repair', 1233, 'approved', '2024-05-29 03:20:06', '2024-05-29 10:41:35'),
(14, 26, 'repair', 12334, 'approved', '2024-05-29 03:20:26', '2024-05-29 10:41:27'),
(15, 26, 'repair', NULL, 'pending', '2024-05-29 03:21:38', '2024-05-29 05:52:35'),
(16, 26, 'repair', 1231, 'approved', '2024-05-29 03:25:02', '2024-05-29 10:32:44'),
(17, 26, 'mto', 2364, 'approved', '2024-05-29 03:26:28', '2024-05-29 10:32:24'),
(18, 26, 'repair', 3032, 'accepted', '2024-05-29 03:28:01', '2024-05-29 12:49:23'),
(19, 26, 'mto', 17478, 'accepted', '2024-05-29 03:28:51', '2024-05-29 12:50:50'),
(20, 22, 'repair', NULL, 'cancelled', '2024-05-29 21:40:17', '2024-05-30 01:38:09'),
(21, 22, 'mto', NULL, 'approved', '2024-05-29 21:41:26', '2024-05-29 23:23:55'),
(22, 22, 'repair', NULL, 'approved', '2024-05-29 23:13:53', '2024-05-30 03:38:35');

--
-- Triggers `quotes`
--
DELIMITER $$
CREATE TRIGGER `insert_order_after_quote_accept` AFTER UPDATE ON `quotes` FOR EACH ROW IF NEW.quote_status = 'accepted' AND OLD.quote_status != 'accepted' THEN
        INSERT INTO orders (quote_id)
        VALUES (NEW.quote_id);
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `order_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply` text,
  `reply_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(10, 30, 1, 'Terrible experience', '2024-05-14 10:11:28', 'hello', '2024-05-23 20:37:45'),
(12, 49, 3, 'It was a good service', '2024-05-24 04:03:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `image_id` int NOT NULL,
  `review_id` int NOT NULL,
  `path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `review_images`
--

INSERT INTO `review_images` (`image_id`, `review_id`, `path`) VALUES
(1, 12, '/uploadedImages/reviewImages/repimg3.jpg'),
(2, 12, '/uploadedImages/reviewImages/repimg2.jpg'),
(3, 12, '/uploadedImages/reviewImages/repimg1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_type` enum('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token_hash` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `user_type`, `contact_number`, `token_hash`, `token_expiry`) VALUES
(1, 'Juan dela Cruz', 'juan@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', '09123456789', NULL, NULL),
(2, 'Maria Clara', 'maria@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', '09234567890', NULL, NULL),
(3, 'Pedro Penduko', 'pedro@example.com', '$2y$10$Y/mvA3bZ.96vZ67MK2OlFe2X7t66MOli5A0tQBKQsglvIJeHahFue', 'customer', '09345678901', NULL, NULL),
(4, 'Teresa Magtanggol', 'teresa@example.com', '$2y$10$Mtu42SKTqeZGDfUwhlSMkewZE2ALM1IT.CtgjmZ8MvWwgq5Ti9TZm', 'customer', '09456789012', NULL, NULL),
(5, 'Diego Silang', 'diego@example.com', '$2y$10$X4RJKuRPNu9PpdfiyVL0WuRBjdcKCGBnC4kkHGPVkctJ12ephksNO', 'customer', '09567890123', NULL, NULL),
(6, 'Gabriela Silang', 'gabriela@example.com', '$2y$10$A2tGF2PXqruSu.tgkFl5vuWZJdCkpkyyWlSshUQwNexeL9V8VwdPy', 'customer', '09678901234', NULL, NULL),
(7, 'Jose Rizal', 'jose@example.com', '$2y$10$CqR18V44JZRie/tfC1RwtOPK0LO04MgHOCfO5zAnW.v/p8QH3ux/6', 'customer', '09789012345', NULL, NULL),
(8, 'Andres Bonifacio', 'andres@example.com', '$2y$10$V566ca4gSFvijfm9ep8xu.ZL0P7HJJjc8AOjdY4vNMQqA7kU1ib1C', 'customer', '09890123456', NULL, NULL),
(9, 'Emilio Aguinaldo', 'emilio@example.com', '$2y$10$IEqX6y3iRqtSbp5Er9E52uaDF7DIkFaGw9CLz0rP7T4/1NVnSHQH6', 'customer', '09901234567', NULL, NULL),
(10, 'Melchora Aquino', 'melchora@example.com', '$2y$10$VG8UE5zZsmbfZZWq1aNvOOeojnmUiISbsnaxeMR/w3s32BptpPWAK', 'customer', '09112345678', NULL, NULL),
(11, 'Antonio Luna', 'antonio@example.com', '$2y$10$sIG5f6pQVOUp6Z7hPCBRse5Nye2u5XKvJu1zw.hFnL.e/LcRW0ta2', 'customer', '09223456789', NULL, NULL),
(12, 'Gregoria de Jesus', 'gregoria@example.com', '$2y$10$GQaVWs.Cr/wa.iUR6E6EROJ4DY5K3pQAzr0uMUuLvUAKMogoih64O', 'customer', '09334567890', NULL, NULL),
(13, 'Apolinario Mabini', 'apolinario@example.com', '$2y$10$St9Q3wIs0rRufASswms.5uTEzE2y7Fczr.FinVvw5YI3FESsE5sN2', 'customer', '09445678901', NULL, NULL),
(14, 'Heneral Luna', 'heneral@example.com', '$2y$10$dx//Mn82CZ0IOr5hZGjt8.xsv3p.ajqGgD6TR8/5ThQUb1pVEHgnq', 'customer', '09556789012', NULL, NULL),
(15, 'Andres Bonifacio', 'andresb@example.com', '$2y$10$2X/7K1h6mnE2UBBkLaW2AOSnBpVEVXXmpdrtgz6kcvz/xajhMmU.q', 'customer', '09667890123', NULL, NULL),
(16, 'Emilio Jacinto', 'emilioj@example.com', '$2y$10$ijuW6lg57WDdqlrGCyRQp.v51P.NMC/ub9JHYQ04tbLGw07itjnS2', 'customer', '09778901234', NULL, NULL),
(17, 'Lapu-Lapu', 'lapu-lapu@example.com', '$2y$10$PeQ4eyYKKWVCiYtvk2pE/ehmGGjJTKZ5V9.1TDjPEB.jzohkkflvi', 'customer', '09889012345', NULL, NULL),
(18, 'Heneral del Pilar', 'heneralp@example.com', '$2y$10$GSRixKMthdbMtlKnw0LhAOTBlUyAGe7IygRc.zdBA7DA2bUwuaZye', 'customer', '09990123456', NULL, NULL),
(19, 'Gabriela Silang', 'gabrielas@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', '09101234567', NULL, NULL),
(20, 'Diego Silang', 'diegos@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', '09212345678', NULL, NULL),
(21, 'Sargento Upholstery', 'sargento@gmail.com', '$2y$10$WhGORuLG.Fw6yO1ib7GhX.PK2xkNB7GDSI/5HaCbXsOycNlMLNgCy', 'admin', '09123412014', NULL, NULL),
(22, 'Joaquin Luis Guevarra', 'joaquinguevarra177@gmail.com', '$2y$10$73WniKD5wNWwenDCH93TGuDWbmfm5WtzmfV/vmQQm9d892x0uD112', 'customer', '09052669619', NULL, NULL),
(23, 'Sargento Upholstery 2', 'sargentoadmin@gmail.com', '$2y$10$N4HNoV6MtBGyB7Xu58Lmn.NIAMyzgVA/wbK/yWI8DAp0O..drS7iy', 'admin', '09123456789', NULL, NULL),
(24, 'Lance Jimenez', 'trishchan@gmail.com', '$2y$10$YEHSUssJkiVGzdmnzbyxwuaWrCtxVV7BegI6/I80StyNAtEi0rapG', 'admin', '09791406736', NULL, NULL),
(25, 'Chris Paul', 'chispaul@gmail.com', '$2y$10$dXItxFQz3d2n.9KS0MF9GOWDqBhUS8k28HEEDz3a/NkRwSBOGnLAa', 'customer', '09312818482', NULL, NULL),
(26, 'Trishchan Earl Jimenez', 'trishchanjimenez01@gmail.com', '$2y$10$pSzx6DILyGRIQ/tv/fWUs.gcvaEiHogf7fi/BkuIDifbJ7KNKoIkG', 'customer', '09122142423', '6247c42f633ec36257c91afebd2b04d8a745efe5a4ecce47d8e9e6d9e7e42d58', '2024-05-29 12:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `works`
--

CREATE TABLE `works` (
  `works_id` int NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(60, 'sofa', 'black', '/websiteimages/galleryimages/sofa20.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `chat_sender_id_fk` (`sender_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `customs`
--
ALTER TABLE `customs`
  ADD PRIMARY KEY (`custom_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `delivery_order_id_fk` (`order_id`);

--
-- Indexes for table `downpayment`
--
ALTER TABLE `downpayment`
  ADD PRIMARY KEY (`downpayment_id`),
  ADD KEY `downpayment_order_id_fk` (`order_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `fullpayment`
--
ALTER TABLE `fullpayment`
  ADD PRIMARY KEY (`fullpay_id`),
  ADD KEY `fullpayment_order_id_fk` (`order_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `items_quote_id_fk` (`quote_id`),
  ADD KEY `items_custom_id_fk` (`custom_id`);

--
-- Indexes for table `notifs`
--
ALTER TABLE `notifs`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `notifs_user_id_fk` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_quote_id_fk` (`quote_id`);

--
-- Indexes for table `pickup`
--
ALTER TABLE `pickup`
  ADD PRIMARY KEY (`pickup_id`),
  ADD KEY `pickup_order_id_fk` (`order_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`quote_id`),
  ADD KEY `quotes_customer_id_fk` (`customer_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `order_review_fk` (`order_id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `image_review_fk` (`review_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`works_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `customs`
--
ALTER TABLE `customs`
  MODIFY `custom_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `downpayment`
--
ALTER TABLE `downpayment`
  MODIFY `downpayment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fullpayment`
--
ALTER TABLE `fullpayment`
  MODIFY `fullpay_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifs`
--
ALTER TABLE `notifs`
  MODIFY `notif_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pickup`
--
ALTER TABLE `pickup`
  MODIFY `pickup_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `quote_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
  MODIFY `works_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chat_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `downpayment`
--
ALTER TABLE `downpayment`
  ADD CONSTRAINT `downpayment_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fullpayment`
--
ALTER TABLE `fullpayment`
  ADD CONSTRAINT `fullpayment_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_custom_id_fk` FOREIGN KEY (`custom_id`) REFERENCES `customs` (`custom_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `items_quote_id_fk` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifs`
--
ALTER TABLE `notifs`
  ADD CONSTRAINT `notifs_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_quote_id_fk` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pickup`
--
ALTER TABLE `pickup`
  ADD CONSTRAINT `pickup_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `image_review_fk` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`review_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
