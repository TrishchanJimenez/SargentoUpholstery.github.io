-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2024 at 05:42 AM
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
(14, 24, '123 Rizal St. Brgy Rizal Taguig City');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `message` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(9, 22, 22, 'putanginamo', '2024-05-21 07:09:38'),
(10, 21, 22, 'fasdfasf', '2024-05-22 11:28:02'),
(11, 21, 21, 'hello', '2024-05-23 00:13:15'),
(12, 21, 21, 'fadfa', '2024-05-23 00:13:21'),
(13, 21, 22, 'dfafa', '2024-05-23 00:13:39'),
(14, 21, 22, 'fasf', '2024-05-23 17:17:01'),
(15, 24, 22, 'sfhahfjaf', '2024-05-24 03:10:40'),
(16, 24, 22, 'a', '2024-05-24 04:24:37'),
(17, 24, 22, 'hello', '2024-05-24 04:26:44'),
(18, 24, 22, 'hi', '2024-05-24 04:26:55');

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
(27, 22, 'You have successfully placed a quote request. Please await confirmation of order.', '2024-05-26 23:40:43', 0, '/my/user_orders.php');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quote_id` int NOT NULL,
  `order_status` enum('new_order','pending_downpayment','ready_for_pickup','in_production','pending_fullpayment','out_for_delivery','received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'new_order',
  `del_method` enum('third_party','self') NOT NULL,
  `del_address_id` int NOT NULL,
  `quoted_price` float DEFAULT NULL,
  `is_accepted` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `is_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `refusal_reason` text,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `quote_id`, `order_status`, `del_method`, `del_address_id`, `quoted_price`, `is_accepted`, `is_cancelled`, `refusal_reason`, `last_updated`) VALUES
(1, 1, 1, 'pending_downpayment', 'third_party', 1, 1230, 'accepted', 1, NULL, '2024-05-27 01:54:03'),
(2, 2, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(3, 3, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(4, 4, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(5, 5, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(6, 6, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(7, 7, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(8, 8, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(9, 9, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(10, 10, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(11, 11, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(12, 12, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(13, 13, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(14, 14, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(15, 15, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(16, 16, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(17, 17, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(18, 18, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(19, 19, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(20, 20, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(21, 1, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(22, 2, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(23, 3, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(24, 4, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(25, 5, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(26, 6, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(27, 7, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(28, 8, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(29, 9, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(30, 10, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(31, 11, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(32, 12, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(33, 13, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(34, 14, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(35, 15, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(36, 16, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(37, 17, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(38, 18, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(39, 19, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(40, 20, 1, 'new_order', 'self', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(41, 22, 1, 'received', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(42, 22, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(43, 22, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(44, 22, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(45, 22, 1, 'new_order', 'third_party', 1, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(46, 21, 1, 'new_order', 'third_party', 12, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(47, 21, 1, 'new_order', 'third_party', 13, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(48, 21, 1, 'new_order', 'third_party', 10, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(49, 21, 1, 'new_order', 'third_party', 10, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03'),
(50, 24, 1, 'received', 'third_party', 14, NULL, 'pending', 0, NULL, '2024-05-27 01:54:03');

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

CREATE TABLE `order_date` (
  `order_id` int NOT NULL,
  `placement_date` date NOT NULL,
  `est_completion_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_date`
--

INSERT INTO `order_date` (`order_id`, `placement_date`, `est_completion_date`) VALUES
(1, '2024-05-14', '2024-06-10'),
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
(45, '2024-05-21', '0000-00-00'),
(46, '2024-05-23', '0000-00-00'),
(47, '2024-05-23', '0000-00-00'),
(48, '2024-05-23', '0000-00-00'),
(49, '2024-05-23', '0000-00-00'),
(50, '2024-05-24', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `order_id` int NOT NULL,
  `payment_status` enum('unpaid','partially_paid','fully_paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'unpaid',
  `downpayment_method` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpayment_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `downpayment_verification_status` enum('waiting_for_verification',' needs_reverification',' verified') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fullpayment_method` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fullpayment_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `fullpayment_verification_status` enum('waiting_for_verification',' needs_reverification',' verified') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`order_id`, `payment_status`, `downpayment_method`, `downpayment_img`, `downpayment_verification_status`, `fullpayment_method`, `fullpayment_img`, `fullpayment_verification_status`) VALUES
(1, 'unpaid', 'gcash', '/uploadedImages/paymentImages/proof1.jpg', 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(2, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(3, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(4, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(5, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(6, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(7, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(8, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(9, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(10, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(11, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(12, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(13, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(14, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(15, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(16, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(17, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(18, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(19, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(20, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(21, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(22, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(23, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(24, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(25, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(26, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(27, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(28, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(29, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(30, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(31, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(32, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(33, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(34, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(35, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(36, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(37, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(38, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(39, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(40, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(41, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(42, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(43, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(44, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(45, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(46, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(47, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(48, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(49, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification'),
(50, 'unpaid', NULL, NULL, 'waiting_for_verification', NULL, NULL, 'waiting_for_verification');

-- --------------------------------------------------------

--
-- Table structure for table `pickup`
--

CREATE TABLE `pickup` (
  `order_id` int NOT NULL,
  `pickup_method` enum('third_party','self') NOT NULL,
  `pickup_address_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pickup`
--

INSERT INTO `pickup` (`order_id`, `pickup_method`, `pickup_address_id`) VALUES
(1, 'third_party', 1),
(2, 'third_party', 1),
(3, 'self', 1),
(4, 'third_party', 1),
(5, 'self', 1),
(6, 'third_party', 1),
(7, 'self', 1),
(8, 'third_party', 1),
(9, 'third_party', 1),
(10, 'self', 1),
(11, 'third_party', 1),
(12, 'third_party', 1),
(13, 'self', 1),
(14, 'third_party', 1),
(15, 'self', 1),
(16, 'third_party', 1),
(17, 'self', 1),
(18, 'third_party', 1),
(19, 'third_party', 1),
(20, 'self', 1),
(42, 'third_party', 1),
(43, 'third_party', 1),
(44, 'third_party', 1),
(45, 'third_party', 1),
(49, 'third_party', 10),
(50, 'third_party', 14);

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `quote_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `furniture_type` varchar(50) DEFAULT NULL,
  `service_type` enum('repair','mto') DEFAULT NULL,
  `description` text,
  `ref_img_path` text,
  `quantity` int DEFAULT NULL,
  `custom_id` int DEFAULT NULL,
  `quote_status` enum('pending','approved','accepted','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`quote_id`, `customer_id`, `furniture_type`, `service_type`, `description`, `ref_img_path`, `quantity`, `custom_id`, `quote_status`, `created_at`, `updated_at`) VALUES
(1, 22, 'Bookshelf', 'mto', 'My wife and I want to have a bookshelf made for our daughter.', '/uploadedImages/referenceImages/61F8CTCqVVL._AC_SL1001_.jpg', 1, 6, 'cancelled', '2024-05-26 14:25:09', '2024-05-27 01:09:27'),
(2, 22, 'Bed', 'repair', 'Little John broke our bed.', NULL, 1, 7, 'pending', '2024-05-26 23:39:06', '2024-05-26 23:39:06'),
(3, 22, 'Sofa', 'repair', 'Minor issues', NULL, 1, 8, 'pending', '2024-05-26 23:40:43', '2024-05-26 23:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `quote_customs`
--

CREATE TABLE `quote_customs` (
  `custom_id` int NOT NULL,
  `dimensions` varchar(50) DEFAULT NULL,
  `materials` varchar(150) DEFAULT NULL,
  `fabric` varchar(150) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quote_customs`
--

INSERT INTO `quote_customs` (`custom_id`, `dimensions`, `materials`, `fabric`, `color`) VALUES
(1, 'test', 'test', 'test', 'test'),
(2, '1m x 1m x 2.5m', 'metal', 'leather', 'black'),
(3, '1m x 1m x 2.5m', 'metal', 'leather', 'black'),
(4, 'test', 'test', 'test', 'test'),
(5, 'test', 'test', 'test', 'test'),
(6, '0.5m x 2.5m x 1.5m', 'galvanized square steel beams, eco-friendly wood veneers', '', 'sky blue'),
(7, '', 'galvanized square steel beams, eco-friendly wood veneer', 'linen from auntie&#039;s house', 'light brown'),
(8, '', '', 'leather', 'orange');

-- --------------------------------------------------------

--
-- Table structure for table `reset_tokens`
--

CREATE TABLE `reset_tokens` (
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
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `user_type`, `contact_number`) VALUES
(1, 'Juan dela Cruz', 'juan@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', '09123456789'),
(2, 'Maria Clara', 'maria@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', '09234567890'),
(3, 'Pedro Penduko', 'pedro@example.com', '$2y$10$Y/mvA3bZ.96vZ67MK2OlFe2X7t66MOli5A0tQBKQsglvIJeHahFue', 'customer', '09345678901'),
(4, 'Teresa Magtanggol', 'teresa@example.com', '$2y$10$Mtu42SKTqeZGDfUwhlSMkewZE2ALM1IT.CtgjmZ8MvWwgq5Ti9TZm', 'customer', '09456789012'),
(5, 'Diego Silang', 'diego@example.com', '$2y$10$X4RJKuRPNu9PpdfiyVL0WuRBjdcKCGBnC4kkHGPVkctJ12ephksNO', 'customer', '09567890123'),
(6, 'Gabriela Silang', 'gabriela@example.com', '$2y$10$A2tGF2PXqruSu.tgkFl5vuWZJdCkpkyyWlSshUQwNexeL9V8VwdPy', 'customer', '09678901234'),
(7, 'Jose Rizal', 'jose@example.com', '$2y$10$CqR18V44JZRie/tfC1RwtOPK0LO04MgHOCfO5zAnW.v/p8QH3ux/6', 'customer', '09789012345'),
(8, 'Andres Bonifacio', 'andres@example.com', '$2y$10$V566ca4gSFvijfm9ep8xu.ZL0P7HJJjc8AOjdY4vNMQqA7kU1ib1C', 'customer', '09890123456'),
(9, 'Emilio Aguinaldo', 'emilio@example.com', '$2y$10$IEqX6y3iRqtSbp5Er9E52uaDF7DIkFaGw9CLz0rP7T4/1NVnSHQH6', 'customer', '09901234567'),
(10, 'Melchora Aquino', 'melchora@example.com', '$2y$10$VG8UE5zZsmbfZZWq1aNvOOeojnmUiISbsnaxeMR/w3s32BptpPWAK', 'customer', '09112345678'),
(11, 'Antonio Luna', 'antonio@example.com', '$2y$10$sIG5f6pQVOUp6Z7hPCBRse5Nye2u5XKvJu1zw.hFnL.e/LcRW0ta2', 'customer', '09223456789'),
(12, 'Gregoria de Jesus', 'gregoria@example.com', '$2y$10$GQaVWs.Cr/wa.iUR6E6EROJ4DY5K3pQAzr0uMUuLvUAKMogoih64O', 'customer', '09334567890'),
(13, 'Apolinario Mabini', 'apolinario@example.com', '$2y$10$St9Q3wIs0rRufASswms.5uTEzE2y7Fczr.FinVvw5YI3FESsE5sN2', 'customer', '09445678901'),
(14, 'Heneral Luna', 'heneral@example.com', '$2y$10$dx//Mn82CZ0IOr5hZGjt8.xsv3p.ajqGgD6TR8/5ThQUb1pVEHgnq', 'customer', '09556789012'),
(15, 'Andres Bonifacio', 'andresb@example.com', '$2y$10$2X/7K1h6mnE2UBBkLaW2AOSnBpVEVXXmpdrtgz6kcvz/xajhMmU.q', 'customer', '09667890123'),
(16, 'Emilio Jacinto', 'emilioj@example.com', '$2y$10$ijuW6lg57WDdqlrGCyRQp.v51P.NMC/ub9JHYQ04tbLGw07itjnS2', 'customer', '09778901234'),
(17, 'Lapu-Lapu', 'lapu-lapu@example.com', '$2y$10$PeQ4eyYKKWVCiYtvk2pE/ehmGGjJTKZ5V9.1TDjPEB.jzohkkflvi', 'customer', '09889012345'),
(18, 'Heneral del Pilar', 'heneralp@example.com', '$2y$10$GSRixKMthdbMtlKnw0LhAOTBlUyAGe7IygRc.zdBA7DA2bUwuaZye', 'customer', '09990123456'),
(19, 'Gabriela Silang', 'gabrielas@example.com', '$2y$10$XX4Cu6WK3Ysu8TW2pA6TbuUk.vRXRtOHoZ0hf/N3fBsI4mr23YQVO', 'customer', '09101234567'),
(20, 'Diego Silang', 'diegos@example.com', '$2y$10$Hq.6m2ep1oCPqd7MTB7ujOmCMjL6b5Rv5rloJvbhLKSx.gseh7pYu', 'customer', '09212345678'),
(21, 'Sargento Upholstery', 'sargento@gmail.com', '$2y$10$WhGORuLG.Fw6yO1ib7GhX.PK2xkNB7GDSI/5HaCbXsOycNlMLNgCy', 'admin', '09123412014'),
(22, 'Joaquin Luis Guevarra', 'joaquinguevarra177@gmail.com', '$2y$10$73WniKD5wNWwenDCH93TGuDWbmfm5WtzmfV/vmQQm9d892x0uD112', 'customer', '09052669619'),
(23, 'Sargento Upholstery 2', 'sargentoadmin@gmail.com', '$2y$10$N4HNoV6MtBGyB7Xu58Lmn.NIAMyzgVA/wbK/yWI8DAp0O..drS7iy', 'admin', '09123456789'),
(24, 'Lance Jimenez', 'trishchan@gmail.com', '$2y$10$YEHSUssJkiVGzdmnzbyxwuaWrCtxVV7BegI6/I80StyNAtEi0rapG', 'admin', '09791406736');

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
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

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
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `order_quote_id_fk` (`quote_id`);

--
-- Indexes for table `order_date`
--
ALTER TABLE `order_date`
  ADD KEY `order_id_fk_order_date` (`order_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD KEY `order_id_fk_payment` (`order_id`);

--
-- Indexes for table `pickup`
--
ALTER TABLE `pickup`
  ADD KEY `order_id_fk_repair` (`order_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`quote_id`),
  ADD KEY `quote_customer_id_fk` (`customer_id`),
  ADD KEY `quote_custom_id_fk` (`custom_id`);

--
-- Indexes for table `quote_customs`
--
ALTER TABLE `quote_customs`
  ADD PRIMARY KEY (`custom_id`);

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
  MODIFY `address_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifs`
--
ALTER TABLE `notifs`
  MODIFY `notif_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `quote_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quote_customs`
--
ALTER TABLE `quote_customs`
  MODIFY `custom_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Constraints for table `notifs`
--
ALTER TABLE `notifs`
  ADD CONSTRAINT `notifs_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_address_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `order_quote_id_fk` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

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
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quote_custom_id_fk` FOREIGN KEY (`custom_id`) REFERENCES `quote_customs` (`custom_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `quote_customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
