-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 12:29 PM
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
-- Database: `core-mining`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','closed') NOT NULL DEFAULT 'pending',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `guest_name`, `guest_email`, `status`, `assigned_to`, `started_at`, `closed_at`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, NULL, 'closed', NULL, '2026-01-27 18:26:20', '2026-01-28 21:38:22', '2026-01-27 18:26:20', '2026-01-28 21:38:22'),
(2, 4, NULL, NULL, 'closed', 3, '2026-01-27 18:26:35', '2026-01-28 21:38:12', '2026-01-27 18:26:35', '2026-01-28 21:38:12'),
(3, 5, NULL, NULL, 'pending', NULL, '2026-01-27 18:37:03', NULL, '2026-01-27 18:37:03', '2026-01-27 18:37:03'),
(4, NULL, 'tayyab', 'tayyab123@gmail.com', 'active', 3, '2026-01-27 18:37:49', NULL, '2026-01-27 18:37:49', '2026-01-28 23:30:36'),
(5, NULL, 'tayyab', 'tayyab123@gmail.com', 'pending', NULL, '2026-01-27 18:37:49', NULL, '2026-01-27 18:37:49', '2026-01-27 18:37:49'),
(6, NULL, 'Development', 'wapetyxag@mailinator.com', 'active', 3, '2026-01-27 18:41:19', NULL, '2026-01-27 18:41:19', '2026-01-28 23:27:34'),
(7, 4, NULL, NULL, 'closed', 3, '2026-01-28 21:38:23', '2026-01-29 09:34:29', '2026-01-28 21:38:23', '2026-01-29 09:34:29'),
(8, 4, NULL, NULL, 'active', NULL, '2026-01-29 09:34:37', NULL, '2026-01-29 09:34:37', '2026-01-29 09:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `message` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `sender_id`, `sender_type`, `message`, `image_path`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'user', 'hi', NULL, 1, '2026-01-28 21:49:46', '2026-01-27 18:26:20', '2026-01-28 21:49:46'),
(2, 2, 4, 'user', 'hi', NULL, 1, '2026-01-27 18:27:06', '2026-01-27 18:26:35', '2026-01-27 18:27:06'),
(3, 2, 3, 'admin', 'how ca i help you', NULL, 1, '2026-01-27 19:01:21', '2026-01-27 18:27:14', '2026-01-27 19:01:21'),
(4, 2, 4, 'user', 'hello g', NULL, 1, '2026-01-27 18:27:58', '2026-01-27 18:27:35', '2026-01-27 18:27:58'),
(5, 2, 3, 'admin', 'nah gggg', NULL, 1, '2026-01-27 19:01:21', '2026-01-27 18:28:22', '2026-01-27 19:01:21'),
(6, 3, 5, 'user', 'hi', NULL, 0, NULL, '2026-01-27 18:37:03', '2026-01-27 18:37:03'),
(7, 4, NULL, 'user', 'hi', NULL, 1, '2026-01-28 23:30:29', '2026-01-27 18:37:49', '2026-01-28 23:30:29'),
(8, 5, NULL, 'user', 'hi', NULL, 0, NULL, '2026-01-27 18:37:49', '2026-01-27 18:37:49'),
(9, 6, NULL, 'user', 'hi', NULL, 1, '2026-01-28 23:21:57', '2026-01-27 18:41:19', '2026-01-28 23:21:57'),
(10, 2, 4, 'user', 'hi', NULL, 1, '2026-01-27 18:47:07', '2026-01-27 18:46:58', '2026-01-27 18:47:07'),
(11, 2, 3, 'admin', 'yes', NULL, 1, '2026-01-27 19:01:21', '2026-01-27 18:47:16', '2026-01-27 19:01:21'),
(12, 2, 3, 'admin', 'okay i help you', NULL, 1, '2026-01-27 19:01:21', '2026-01-27 18:54:12', '2026-01-27 19:01:21'),
(13, 2, 3, 'admin', 'ok ok', NULL, 1, '2026-01-27 19:01:21', '2026-01-27 19:00:53', '2026-01-27 19:01:21'),
(14, 2, 3, 'admin', 'ok', NULL, 1, '2026-01-27 19:44:34', '2026-01-27 19:01:30', '2026-01-27 19:44:34'),
(15, 2, 3, 'admin', 'hnji', NULL, 1, '2026-01-27 19:44:34', '2026-01-27 19:05:06', '2026-01-27 19:44:34'),
(16, 2, 4, 'user', 'hnji', NULL, 0, NULL, '2026-01-27 19:44:40', '2026-01-27 19:44:40'),
(17, 2, 4, 'user', 'hnji', NULL, 0, NULL, '2026-01-27 19:44:42', '2026-01-27 19:44:42'),
(18, 7, 4, 'user', 'hi', NULL, 1, '2026-01-28 21:50:08', '2026-01-28 21:38:23', '2026-01-28 21:50:08'),
(19, 7, 4, 'user', '', 'chat-images/chat_7_1769608117_697a13b5d67cc.jpg', 1, '2026-01-28 21:50:08', '2026-01-28 21:48:37', '2026-01-28 21:50:08'),
(20, 7, 3, 'admin', '', 'chat-images/chat_7_1769608375_697a14b70a771.jpg', 1, '2026-01-28 22:38:41', '2026-01-28 21:52:55', '2026-01-28 22:38:41'),
(21, 6, 3, 'admin', 'yes', NULL, 0, NULL, '2026-01-28 23:27:35', '2026-01-28 23:27:35'),
(22, 4, 3, 'admin', 'yes', NULL, 0, NULL, '2026-01-28 23:30:36', '2026-01-28 23:30:36'),
(23, 8, 4, 'user', 'hi', NULL, 0, NULL, '2026-01-29 09:34:37', '2026-01-29 09:34:37'),
(24, 8, 4, 'user', 'hi', NULL, 0, NULL, '2026-01-29 09:34:55', '2026-01-29 09:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `crypto_wallets`
--

CREATE TABLE `crypto_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `network` enum('bnb_smart_chain','tron') NOT NULL,
  `network_display_name` varchar(255) NOT NULL,
  `wallet_address` varchar(255) NOT NULL,
  `qr_code_image` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL DEFAULT 'USDT',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_for_deposit` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_for_withdrawal` tinyint(1) NOT NULL DEFAULT 1,
  `minimum_deposit` decimal(15,2) DEFAULT NULL,
  `maximum_deposit` decimal(15,2) DEFAULT NULL,
  `minimum_withdrawal` decimal(15,2) DEFAULT NULL,
  `maximum_withdrawal` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crypto_wallets`
--

INSERT INTO `crypto_wallets` (`id`, `network`, `network_display_name`, `wallet_address`, `qr_code_image`, `token`, `is_active`, `allowed_for_deposit`, `allowed_for_withdrawal`, `minimum_deposit`, `maximum_deposit`, `minimum_withdrawal`, `maximum_withdrawal`, `created_at`, `updated_at`) VALUES
(1, 'bnb_smart_chain', 'BNB Smart Chain', 'TCe6zfypPevALjoo5FiQNEN5k34dRMk3uD', 'assets/admin/images/crypto-wallets/w3LXMKg4urTciLYvZ5NafJBtaOAJo0TxRDO3qUxi.jpeg', 'USDT', 1, 1, 1, 5.00, 100.00, 5.00, 100.00, '2026-01-25 19:25:35', '2026-01-25 19:25:35'),
(2, 'tron', 'TRON', '0xcf7393C2eDea75F99C988926C9038Af27cC733b1', 'assets/admin/images/crypto-wallets/528v2G8gIHLfnmOF5HSxDArQydXRLjuch2f9faVp.jpeg', 'USDT', 1, 1, 1, 5.00, 100.00, 5.00, 100.00, '2026-01-25 19:26:38', '2026-01-25 19:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversions`
--

CREATE TABLE `currency_conversions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(15,4) NOT NULL COMMENT '1 USD = X PKR',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_conversions`
--

INSERT INTO `currency_conversions` (`id`, `rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 280.0000, 1, '2026-01-25 09:44:29', '2026-01-25 09:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deposit_payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `crypto_wallet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `pkr_amount` decimal(15,2) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `user_wallet_address` varchar(255) DEFAULT NULL,
  `crypto_network` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `user_id`, `deposit_payment_method_id`, `crypto_wallet_id`, `amount`, `pkr_amount`, `transaction_id`, `account_number`, `account_holder_name`, `user_wallet_address`, `crypto_network`, `payment_proof`, `status`, `admin_notes`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(3, 5, 3, NULL, 1000.00, 280000.00, '3239292', '03262853600', 'Rameez Nazar', NULL, NULL, 'assets/deposits/payment-proofs/eZuU4X4iSkg4a5UsY9NQw4OvW7uDqdawCuPGzg0P.jpg', 'approved', NULL, 3, '2026-01-27 17:30:08', '2026-01-27 17:29:47', '2026-01-27 17:30:08'),
(4, 4, 3, NULL, 1000.00, 280000.00, '63837944', '03262853600', 'Rameez', NULL, NULL, 'assets/deposits/payment-proofs/CXMkKYhbJXyGezVnMEzD8Ee5860qUCIX3FjQRBnM.jpg', 'approved', NULL, 3, '2026-01-27 17:32:07', '2026-01-27 17:31:43', '2026-01-27 17:32:07'),
(5, 3, 3, NULL, 5.00, 1400.00, '63771923', '0326285364800', 'Ali Murtaza', NULL, NULL, 'assets/deposits/payment-proofs/6ED9RY2MXt4P9DIzQvcjCdr53Y8Wn9QsiJCNsBKg.jpg', 'pending', NULL, NULL, NULL, '2026-01-28 23:29:07', '2026-01-28 23:29:07');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_payment_methods`
--

CREATE TABLE `deposit_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'rast',
  `account_type` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_for_deposit` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_for_withdrawal` tinyint(1) NOT NULL DEFAULT 0,
  `minimum_deposit` decimal(15,2) DEFAULT NULL,
  `maximum_deposit` decimal(15,2) DEFAULT NULL,
  `minimum_withdrawal_amount` decimal(15,2) DEFAULT NULL,
  `maximum_withdrawal_amount` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposit_payment_methods`
--

INSERT INTO `deposit_payment_methods` (`id`, `image`, `type`, `account_type`, `account_name`, `bank_name`, `account_number`, `is_active`, `allowed_for_deposit`, `allowed_for_withdrawal`, `minimum_deposit`, `maximum_deposit`, `minimum_withdrawal_amount`, `maximum_withdrawal_amount`, `created_at`, `updated_at`) VALUES
(3, 'assets/admin/images/payment-method/6ujvJiRDDyh1MAw4tJro8tEPA2zdLFzna8UTI603.png', 'rast', 'Jazzcash', 'Rameez Nazar', NULL, '03262853600', 1, 1, 1, 1.00, 10000.00, 1.00, 100.00, '2026-01-25 09:40:39', '2026-01-27 17:29:05'),
(4, 'assets/admin/images/payment-method/ebj1euDAr9DF3vFWng1nYHHaZicovgAIxRoG2cX3.png', 'bank', 'Bank', 'Rameez Nazar', 'HBL', 'PK12ABCD1234567890123456', 1, 1, 1, 1.00, 100.00, 1.00, 100.00, '2026-01-25 09:43:45', '2026-01-27 14:45:10'),
(6, 'assets/admin/images/payment-method/zPwIDW2fgmM3U6rpT93rhO7WG8QeHYT4Rrhg4uqn.png', 'crypto', 'Crypto Wallet', NULL, NULL, '03262853600', 1, 1, 1, 5.00, 100.00, NULL, NULL, '2026-01-25 19:33:54', '2026-01-25 19:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `earning_commission_structures`
--

CREATE TABLE `earning_commission_structures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) DEFAULT NULL,
  `level_name` varchar(255) DEFAULT NULL,
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `mining_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `earning_commission_structures`
--

INSERT INTO `earning_commission_structures` (`id`, `level`, `level_name`, `commission_rate`, `is_active`, `mining_plan_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Direct Referral', 6.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(2, 2, 'Second Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(3, 3, 'Third Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(4, 4, 'Fourth Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(5, 5, 'Fifth Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mining_plan_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `source_balance` enum('fund_wallet','earning_balance') NOT NULL,
  `hourly_rate` decimal(5,2) NOT NULL,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `last_profit_calculated_at` timestamp NULL DEFAULT NULL,
  `total_profit_earned` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unclaimed_profit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `last_claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `mining_plan_id`, `amount`, `source_balance`, `hourly_rate`, `status`, `last_profit_calculated_at`, `total_profit_earned`, `unclaimed_profit`, `last_claimed_at`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 500.00, 'fund_wallet', 0.02, 'active', '2026-01-28 21:33:16', 2.79, 2.76, '2026-01-27 17:53:55', '2026-01-27 17:34:09', '2026-01-28 21:33:16'),
(2, 4, 1, 200.00, 'fund_wallet', 0.02, 'active', '2026-01-29 09:40:14', 1.57, 1.53, '2026-01-27 18:48:01', '2026-01-27 17:36:19', '2026-01-29 09:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `investment_commission_structures`
--

CREATE TABLE `investment_commission_structures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) DEFAULT NULL,
  `level_name` varchar(255) DEFAULT NULL,
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `mining_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_commission_structures`
--

INSERT INTO `investment_commission_structures` (`id`, `level`, `level_name`, `commission_rate`, `is_active`, `mining_plan_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Direct Referral', 6.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(2, 2, 'Second Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(3, 3, 'Third Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(4, 4, 'Fourth Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(5, 5, 'Fifth Level', 3.00, 1, NULL, '2026-01-27 17:33:19', '2026-01-27 17:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2026_01_14_203415_create_investment_commission_structures_table', 1),
(4, '2026_01_14_213555_create_earning_commission_structures_table', 1),
(5, '2026_01_14_214357_create_mining_plans_table', 1),
(6, '2026_01_14_222753_create_reward_levels_table', 1),
(7, '2026_01_15_070426_create_deposit_payment_methods_table', 1),
(8, '2026_01_15_082112_create_currency_conversions_table', 1),
(9, '2026_01_15_091543_add_wallet_fields_to_users_table', 1),
(10, '2026_01_15_091606_create_deposits_table', 1),
(11, '2026_01_16_000000_add_total_invested_to_users_table', 1),
(12, '2026_01_16_002116_create_investments_table', 1),
(13, '2026_01_16_235948_add_account_name_to_deposit_payment_methods_table', 1),
(14, '2026_01_17_040334_add_claim_fields_to_investments_table', 1),
(15, '2026_01_17_070111_add_account_fields_to_deposits_table', 1),
(16, '2026_01_17_074233_add_usage_fields_to_deposit_payment_methods_table', 1),
(17, '2026_01_17_080534_add_withdrawal_limits_to_deposit_payment_methods_table', 1),
(18, '2026_01_17_080549_create_withdrawals_table', 1),
(19, '2026_01_18_000000_create_notifications_table', 1),
(20, '2026_01_19_000000_create_user_reward_levels_table', 1),
(21, '2026_01_19_000636_add_mining_plan_id_to_investment_commission_structures_table', 1),
(22, '2026_01_19_000645_create_pending_referral_commissions_table', 1),
(23, '2026_01_19_230734_create_password_reset_tokens_table', 1),
(24, '2026_01_20_000000_create_transactions_table', 1),
(25, '2026_01_20_090133_add_profile_photo_to_users_table', 1),
(26, '2026_01_20_121437_create_pending_earning_commissions_table', 1),
(27, '2026_01_21_212626_add_claim_fields_to_user_reward_levels_table', 1),
(28, '2026_01_22_112709_add_remember_token_to_users_table', 1),
(29, '2026_01_22_144917_add_mining_plan_id_to_earning_commission_structures_table', 1),
(30, '2026_01_25_011234_add_type_and_bank_name_to_deposit_payment_methods_table', 1),
(31, '2026_01_25_012021_make_account_type_nullable_in_deposit_payment_methods_table', 2),
(32, '2026_01_25_110758_create_crypto_wallets_table', 3),
(33, '2026_01_25_110803_add_crypto_fields_to_deposits_table', 3),
(34, '2026_01_25_110807_add_crypto_fields_to_withdrawals_table', 3),
(35, '2026_01_27_062901_add_bank_name_to_withdrawals_table', 4),
(36, '2026_01_27_065728_add_deduction_tracking_to_withdrawals_table', 5),
(37, '2026_01_27_074452_make_account_fields_nullable_in_withdrawals_table', 5),
(38, '2026_01_27_101436_create_chats_table', 6),
(39, '2026_01_27_101441_create_chat_messages_table', 6),
(40, '2026_01_28_134251_add_image_path_to_chat_messages_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `mining_plans`
--

CREATE TABLE `mining_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `min_investment` decimal(15,2) DEFAULT NULL,
  `max_investment` decimal(15,2) DEFAULT NULL,
  `daily_roi_min` decimal(5,2) DEFAULT NULL,
  `daily_roi_max` decimal(5,2) DEFAULT NULL,
  `hourly_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mining_plans`
--

INSERT INTO `mining_plans` (`id`, `name`, `tagline`, `subtitle`, `icon_class`, `min_investment`, `max_investment`, `daily_roi_min`, `daily_roi_max`, `hourly_rate`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lithium', 'Advanced Mining Plan for Maximum Returns', 'Earn through lithium mining', 'fas fa-gem', 2.00, 100000.00, 3.00, 4.00, 0.02, 1, 1, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(2, 'Platinum', 'Premium Mining Plan for High Returns', 'Earn through platinum mining', 'fas fa-gem', 1000.00, 50000.00, 4.00, 5.00, 0.02, 2, 1, '2026-01-27 17:33:19', '2026-01-27 17:33:19'),
(3, 'Diamond', 'Elite Mining Plan for Maximum Profits', 'Earn through diamond mining', 'fas fa-crown', 5000.00, 200000.00, 5.00, 6.00, 0.02, 3, 1, '2026-01-27 17:33:20', '2026-01-27 17:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `read_at`, `related_id`, `related_type`, `created_at`, `updated_at`) VALUES
(2, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fake. The amount has been refunded to your wallet.', 1, '2026-01-27 15:02:56', 1, 'App\\Models\\Withdrawal', '2026-01-27 15:02:46', '2026-01-27 15:02:56'),
(3, 3, 'withdrawal_approved', 'Withdrawal Approved', 'Hi Admin, Your withdrawal request of $5.00 has been approved. Proof has been uploaded.', 1, '2026-01-29 08:11:08', 2, 'App\\Models\\Withdrawal', '2026-01-27 15:47:53', '2026-01-29 08:11:08'),
(4, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fake. The amount has been refunded to your wallet.', 1, '2026-01-29 08:11:08', 3, 'App\\Models\\Withdrawal', '2026-01-27 15:49:08', '2026-01-29 08:11:08'),
(5, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fake. The amount has been refunded to your wallet.', 1, '2026-01-29 08:11:08', 4, 'App\\Models\\Withdrawal', '2026-01-27 16:04:55', '2026-01-29 08:11:08'),
(6, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fke. The amount has been refunded to your wallet.', 1, '2026-01-29 08:11:08', 5, 'App\\Models\\Withdrawal', '2026-01-27 16:49:53', '2026-01-29 08:11:08'),
(7, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fake. The amount has been refunded to your wallet.', 1, '2026-01-29 08:11:08', 6, 'App\\Models\\Withdrawal', '2026-01-27 16:51:05', '2026-01-29 08:11:08'),
(8, 5, 'deposit_approved', 'Deposit Approved', 'Hi Tayyab Nazar, Your deposit of $1,000.00 has been approved and added to your fund wallet.', 0, NULL, 3, 'App\\Models\\Deposit', '2026-01-27 17:30:08', '2026-01-27 17:30:08'),
(9, 4, 'deposit_approved', 'Deposit Approved', 'Hi Rameez Nazar, Your deposit of $1,000.00 has been approved and added to your fund wallet.', 1, '2026-01-27 18:48:32', 4, 'App\\Models\\Deposit', '2026-01-27 17:32:07', '2026-01-27 18:48:32'),
(10, 4, 'reward_level_completed', 'Reward Level Completed', 'Hi Rameez Nazar, Congratulations! You have completed the Team Builder level. Your total referral investment has reached $10.00. You can now claim your reward of $2.00.', 1, '2026-01-27 18:48:32', 1, 'App\\Models\\RewardLevel', '2026-01-27 17:34:09', '2026-01-27 18:48:32'),
(11, 4, 'reward_level_completed', 'Reward Level Completed', 'Hi Rameez Nazar, Congratulations! You have completed the Team Leader level. Your total referral investment has reached $40.00. You can now claim your reward of $5.00.', 1, '2026-01-27 18:48:32', 2, 'App\\Models\\RewardLevel', '2026-01-27 17:34:09', '2026-01-27 18:48:32'),
(12, 4, 'reward_level_completed', 'Reward Level Completed', 'Hi Rameez Nazar, Congratulations! You have completed the Team Director level. Your total referral investment has reached $120.00. You can now claim your reward of $8.00.', 1, '2026-01-27 18:48:32', 3, 'App\\Models\\RewardLevel', '2026-01-27 17:34:09', '2026-01-27 18:48:32'),
(13, 4, 'reward_level_completed', 'Reward Level Completed', 'Hi Rameez Nazar, Congratulations! You have completed the Team Master level. Your total referral investment has reached $200.00. You can now claim your reward of $16.00.', 1, '2026-01-27 18:48:32', 4, 'App\\Models\\RewardLevel', '2026-01-27 17:34:09', '2026-01-27 18:48:32'),
(14, 3, 'reward_level_completed', 'Reward Level Completed', 'Hi Admin, Congratulations! You have completed the Team Builder level. Your total referral investment has reached $10.00. You can now claim your reward of $2.00.', 1, '2026-01-29 08:11:08', 1, 'App\\Models\\RewardLevel', '2026-01-27 17:36:20', '2026-01-29 08:11:08'),
(15, 3, 'reward_level_completed', 'Reward Level Completed', 'Hi Admin, Congratulations! You have completed the Team Leader level. Your total referral investment has reached $40.00. You can now claim your reward of $5.00.', 1, '2026-01-29 08:11:08', 2, 'App\\Models\\RewardLevel', '2026-01-27 17:36:20', '2026-01-29 08:11:08'),
(16, 3, 'reward_level_completed', 'Reward Level Completed', 'Hi Admin, Congratulations! You have completed the Team Director level. Your total referral investment has reached $120.00. You can now claim your reward of $8.00.', 1, '2026-01-29 08:11:08', 3, 'App\\Models\\RewardLevel', '2026-01-27 17:36:20', '2026-01-29 08:11:08'),
(17, 3, 'reward_level_completed', 'Reward Level Completed', 'Hi Admin, Congratulations! You have completed the Team Master level. Your total referral investment has reached $200.00. You can now claim your reward of $16.00.', 1, '2026-01-29 08:11:08', 4, 'App\\Models\\RewardLevel', '2026-01-27 17:36:20', '2026-01-29 08:11:08'),
(18, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Rameez Nazar. Please check the chat management page.', 1, '2026-01-29 08:11:08', 1, 'App\\Models\\Chat', '2026-01-27 18:26:20', '2026-01-29 08:11:08'),
(19, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Rameez Nazar. Please check the chat management page.', 1, '2026-01-29 08:11:08', 2, 'App\\Models\\Chat', '2026-01-27 18:26:35', '2026-01-29 08:11:08'),
(20, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-27 18:48:32', 2, 'App\\Models\\Chat', '2026-01-27 18:27:15', '2026-01-27 18:48:32'),
(21, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-27 18:48:32', 2, 'App\\Models\\Chat', '2026-01-27 18:28:22', '2026-01-27 18:48:32'),
(22, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Tayyab Nazar. Please check the chat management page.', 1, '2026-01-29 08:11:08', 3, 'App\\Models\\Chat', '2026-01-27 18:37:03', '2026-01-29 08:11:08'),
(23, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by tayyab. Please check the chat management page.', 1, '2026-01-29 08:11:08', 4, 'App\\Models\\Chat', '2026-01-27 18:37:49', '2026-01-29 08:11:08'),
(24, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by tayyab. Please check the chat management page.', 1, '2026-01-29 08:11:08', 5, 'App\\Models\\Chat', '2026-01-27 18:37:50', '2026-01-29 08:11:08'),
(25, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Development. Please check the chat management page.', 1, '2026-01-29 08:11:08', 6, 'App\\Models\\Chat', '2026-01-27 18:41:19', '2026-01-29 08:11:08'),
(26, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-27 18:48:32', 2, 'App\\Models\\Chat', '2026-01-27 18:47:16', '2026-01-27 18:48:32'),
(27, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-27 18:54:30', 2, 'App\\Models\\Chat', '2026-01-27 18:54:12', '2026-01-27 18:54:30'),
(28, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-29 08:29:25', 2, 'App\\Models\\Chat', '2026-01-27 19:00:53', '2026-01-29 08:29:25'),
(29, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-29 08:29:25', 2, 'App\\Models\\Chat', '2026-01-27 19:01:30', '2026-01-29 08:29:25'),
(30, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-29 08:29:25', 2, 'App\\Models\\Chat', '2026-01-27 19:05:07', '2026-01-29 08:29:25'),
(31, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Rameez Nazar. Please check the chat management page.', 1, '2026-01-29 08:11:08', 7, 'App\\Models\\Chat', '2026-01-28 21:38:23', '2026-01-29 08:11:08'),
(32, 4, 'chat_reply', 'New Reply in Chat', 'You have received a new reply in your chat. Click to view the conversation.', 1, '2026-01-29 08:29:25', 7, 'App\\Models\\Chat', '2026-01-28 21:52:55', '2026-01-29 08:29:25'),
(33, 3, 'withdrawal_rejected', 'Withdrawal Rejected', 'Hi Admin, Your withdrawal request of $5.00 has been rejected. Reason: fake. The amount has been refunded to your wallet.', 1, '2026-01-29 08:11:08', 7, 'App\\Models\\Withdrawal', '2026-01-28 23:31:05', '2026-01-29 08:11:08'),
(34, 4, 'admin_targeted', 'deposit', 'hi', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:25:34', '2026-01-29 08:29:25'),
(35, 4, 'admin_targeted', 'hi', 'hi', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:25:47', '2026-01-29 08:29:25'),
(36, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:16', '2026-01-29 08:29:25'),
(37, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:16', '2026-01-29 08:27:16'),
(38, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:23', '2026-01-29 08:29:25'),
(39, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:23', '2026-01-29 08:27:23'),
(40, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:30', '2026-01-29 08:29:25'),
(41, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:30', '2026-01-29 08:27:30'),
(42, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:38', '2026-01-29 08:29:25'),
(43, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:38', '2026-01-29 08:27:38'),
(44, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:45', '2026-01-29 08:29:25'),
(45, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:45', '2026-01-29 08:27:45'),
(46, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:51', '2026-01-29 08:29:25'),
(47, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:51', '2026-01-29 08:27:51'),
(48, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:27:58', '2026-01-29 08:29:25'),
(49, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:27:58', '2026-01-29 08:27:58'),
(50, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:04', '2026-01-29 08:29:25'),
(51, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:04', '2026-01-29 08:28:04'),
(52, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:12', '2026-01-29 08:29:25'),
(53, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:12', '2026-01-29 08:28:12'),
(54, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:18', '2026-01-29 08:29:25'),
(55, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:18', '2026-01-29 08:28:18'),
(56, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:26', '2026-01-29 08:29:25'),
(57, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:26', '2026-01-29 08:28:26'),
(58, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:34', '2026-01-29 08:29:25'),
(59, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:34', '2026-01-29 08:28:34'),
(60, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:51', '2026-01-29 08:29:25'),
(61, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:51', '2026-01-29 08:28:51'),
(62, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:28:58', '2026-01-29 08:29:25'),
(63, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:28:58', '2026-01-29 08:28:58'),
(64, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:29:04', '2026-01-29 08:29:25'),
(65, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:29:04', '2026-01-29 08:29:04'),
(66, 4, 'admin_broadcast', 'deposit', 'deposit', 1, '2026-01-29 08:29:25', NULL, NULL, '2026-01-29 08:29:11', '2026-01-29 08:29:25'),
(67, 5, 'admin_broadcast', 'deposit', 'deposit', 0, NULL, NULL, NULL, '2026-01-29 08:29:11', '2026-01-29 08:29:11'),
(68, 4, 'withdrawal_approved', 'Withdrawal Approved', 'Hi Rameez Nazar, Your withdrawal request of $5.00 has been approved. Proof has been uploaded.', 0, NULL, 8, 'App\\Models\\Withdrawal', '2026-01-29 09:32:54', '2026-01-29 09:32:54'),
(69, 3, 'chat_started', 'New Chat Started', 'A new chat has been started by Rameez Nazar. Please check the chat management page.', 0, NULL, 8, 'App\\Models\\Chat', '2026-01-29 09:34:38', '2026-01-29 09:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_earning_commissions`
--

CREATE TABLE `pending_earning_commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `mining_plan_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `earning_amount` decimal(15,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(15,2) NOT NULL,
  `is_claimed` tinyint(1) NOT NULL DEFAULT 0,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pending_earning_commissions`
--

INSERT INTO `pending_earning_commissions` (`id`, `referrer_id`, `investor_id`, `investment_id`, `mining_plan_id`, `level`, `earning_amount`, `commission_rate`, `commission_amount`, `is_claimed`, `claimed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 1, 1, 1, 0.03, 6.00, 0.00, 0, NULL, '2026-01-27 17:53:55', '2026-01-27 17:53:55'),
(2, 3, 5, 1, 1, 2, 0.03, 3.00, 0.00, 0, NULL, '2026-01-27 17:53:55', '2026-01-27 17:53:55'),
(3, 3, 4, 2, 1, 1, 0.04, 6.00, 0.00, 0, NULL, '2026-01-27 18:48:01', '2026-01-27 18:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `pending_referral_commissions`
--

CREATE TABLE `pending_referral_commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `mining_plan_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `investment_amount` decimal(15,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(15,2) NOT NULL,
  `is_claimed` tinyint(1) NOT NULL DEFAULT 0,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pending_referral_commissions`
--

INSERT INTO `pending_referral_commissions` (`id`, `referrer_id`, `investor_id`, `investment_id`, `mining_plan_id`, `level`, `investment_amount`, `commission_rate`, `commission_amount`, `is_claimed`, `claimed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 1, 1, 1, 500.00, 6.00, 30.00, 1, '2026-01-27 17:34:44', '2026-01-27 17:34:09', '2026-01-27 17:34:44'),
(2, 3, 5, 1, 1, 2, 500.00, 3.00, 15.00, 0, NULL, '2026-01-27 17:34:09', '2026-01-27 17:34:09'),
(3, 3, 4, 2, 1, 1, 200.00, 6.00, 12.00, 0, NULL, '2026-01-27 17:36:20', '2026-01-27 17:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_levels`
--

CREATE TABLE `reward_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) DEFAULT NULL,
  `level_name` varchar(255) DEFAULT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `icon_color` varchar(255) DEFAULT NULL,
  `investment_required` decimal(15,2) DEFAULT NULL,
  `reward_amount` decimal(15,2) DEFAULT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reward_levels`
--

INSERT INTO `reward_levels` (`id`, `level`, `level_name`, `icon_class`, `icon_color`, `investment_required`, `reward_amount`, `is_premium`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Team Builder', 'fas fa-user-tie', 'gold', 10.00, 2.00, 0, 1, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(2, 2, 'Team Leader', 'fas fa-user-graduate', 'gold', 40.00, 5.00, 0, 2, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(3, 3, 'Team Director', 'fas fa-briefcase', 'gold', 120.00, 8.00, 0, 3, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(4, 4, 'Team Master', 'fas fa-medal', 'gold', 200.00, 16.00, 0, 4, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(5, 5, 'Team Chief', 'fas fa-award', 'silver', 600.00, 50.00, 0, 5, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(6, 6, 'Team Executive', 'fas fa-gem', 'purple', 1000.00, 170.00, 0, 6, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(7, 7, 'Team Captain', 'fas fa-star', 'red', 2500.00, 500.00, 0, 7, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(8, 8, 'Team Commander', 'fas fa-chess-king', 'red', 8000.00, 2000.00, 0, 8, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(9, 9, 'Team Head', 'fas fa-chess-queen', 'red', 15000.00, 4500.00, 0, 9, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21'),
(10, 10, 'Team President', 'fas fa-crown', 'red', 25000.00, 8000.00, 1, 10, 1, '2026-01-27 17:33:21', '2026-01-27 17:33:21');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `description`, `reference_id`, `reference_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'withdrawal', 5.00, 'Withdrawal via ', 2, 'App\\Models\\Withdrawal', 'completed', '2026-01-27 15:47:53', '2026-01-27 15:47:53'),
(2, 5, 'deposit', 1000.00, 'Deposit via ', 3, 'App\\Models\\Deposit', 'completed', '2026-01-27 17:30:08', '2026-01-27 17:30:08'),
(3, 4, 'deposit', 1000.00, 'Deposit via ', 4, 'App\\Models\\Deposit', 'completed', '2026-01-27 17:32:07', '2026-01-27 17:32:07'),
(4, 4, 'referral_earning', 2.00, 'Reward claimed for completing Team Builder', 1, 'App\\Models\\RewardLevel', 'completed', '2026-01-27 17:35:37', '2026-01-27 17:35:37'),
(5, 4, 'withdrawal', 5.00, 'Withdrawal via ', 8, 'App\\Models\\Withdrawal', 'completed', '2026-01-29 09:32:54', '2026-01-29 09:32:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `refer_code` varchar(255) DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `fund_wallet` decimal(15,2) NOT NULL DEFAULT 0.00,
  `mining_earning` decimal(15,2) NOT NULL DEFAULT 0.00,
  `referral_earning` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_invested` decimal(15,2) NOT NULL DEFAULT 0.00,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone`, `profile_photo`, `role`, `refer_code`, `referred_by`, `fund_wallet`, `mining_earning`, `referral_earning`, `net_balance`, `total_invested`, `password`, `created_at`, `updated_at`, `remember_token`) VALUES
(3, 'Admin', 'admin@gmail.com', 'admin', NULL, NULL, 'admin', 'Admin03', NULL, 0.00, 6.00, 0.00, 6.00, 0.00, '$2y$12$E7CwQeVtDysFVxwbCzWzxetpaQhqAqJGmywHHnaTN.o49eT6b4boy', '2026-01-26 19:45:42', '2026-01-28 23:31:05', NULL),
(4, 'Rameez Nazar', 'ramiz@gmail.com', 'ramiz1234', '03262853600', NULL, 'user', 'RameezNazar04', 'Admin03', 800.00, 0.00, 27.04, 27.04, 200.00, '$2y$12$kpsT3.VCOgGYxCWaPeIvwulEgZvd/RM408P8RtmmyOk5tVsw3oOGO', '2026-01-27 17:27:19', '2026-01-29 09:29:24', NULL),
(5, 'Tayyab Nazar', 'tayyab@gmail.com', 'tayyab123', '03262853600', NULL, 'user', 'TayyabNazar05', 'RameezNazar04', 500.00, 0.03, 0.00, 0.03, 500.00, '$2y$12$68eF0NnFzhfCz.ANxX1Qjuar97NRGPuEmrF60R9R.NVnbEItvpvoO', '2026-01-27 17:27:47', '2026-01-27 17:53:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_reward_levels`
--

CREATE TABLE `user_reward_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reward_level_id` bigint(20) UNSIGNED NOT NULL,
  `achieved_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reward_amount_credited` decimal(15,2) NOT NULL,
  `is_claimed` tinyint(1) NOT NULL DEFAULT 0,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_reward_levels`
--

INSERT INTO `user_reward_levels` (`id`, `user_id`, `reward_level_id`, `achieved_at`, `reward_amount_credited`, `is_claimed`, `claimed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2026-01-27 09:35:37', 2.00, 1, '2026-01-27 17:35:37', '2026-01-27 17:34:09', '2026-01-27 17:35:37'),
(2, 4, 2, '2026-01-27 17:34:09', 5.00, 0, NULL, '2026-01-27 17:34:09', '2026-01-27 17:34:09'),
(3, 4, 3, '2026-01-27 17:34:09', 8.00, 0, NULL, '2026-01-27 17:34:09', '2026-01-27 17:34:09'),
(4, 4, 4, '2026-01-27 17:34:09', 16.00, 0, NULL, '2026-01-27 17:34:09', '2026-01-27 17:34:09'),
(5, 3, 1, '2026-01-27 17:36:20', 2.00, 0, NULL, '2026-01-27 17:36:20', '2026-01-27 17:36:20'),
(6, 3, 2, '2026-01-27 17:36:20', 5.00, 0, NULL, '2026-01-27 17:36:20', '2026-01-27 17:36:20'),
(7, 3, 3, '2026-01-27 17:36:20', 8.00, 0, NULL, '2026-01-27 17:36:20', '2026-01-27 17:36:20'),
(8, 3, 4, '2026-01-27 17:36:20', 16.00, 0, NULL, '2026-01-27 17:36:20', '2026-01-27 17:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deposit_payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `crypto_wallet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `deducted_from_mining` decimal(15,2) DEFAULT NULL,
  `deducted_from_referral` decimal(15,2) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `user_wallet_address` varchar(255) DEFAULT NULL,
  `crypto_network` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `admin_proof_image` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `deposit_payment_method_id`, `crypto_wallet_id`, `amount`, `deducted_from_mining`, `deducted_from_referral`, `account_holder_name`, `account_number`, `bank_name`, `user_wallet_address`, `crypto_network`, `status`, `admin_notes`, `admin_proof_image`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 3, 4, NULL, 5.00, NULL, NULL, 'Rameez Nazar', '03262853600', 'hbl', NULL, NULL, 'rejected', 'fake', NULL, 3, '2026-01-27 15:02:46', '2026-01-27 14:48:02', '2026-01-27 15:02:46'),
(2, 3, 6, 1, 5.00, 6.00, 0.00, NULL, NULL, NULL, '0xcf7393C2eDea75F99C988926C9038Af27cC733b1', 'bnb_smart_chain', 'approved', NULL, 'assets/withdrawals/admin-proofs/fx8F1yHOhPLenJZ9zo9XEkuAc3bqSGT9fPzhLf4E.jfif', 3, '2026-01-27 15:47:53', '2026-01-27 15:47:06', '2026-01-27 15:47:53'),
(3, 3, 6, 1, 5.00, 6.00, 0.00, NULL, NULL, NULL, '0xcf7393C2eDea75F99C988926C9038Af27cC733b1', 'bnb_smart_chain', 'rejected', 'fake', NULL, 3, '2026-01-27 15:49:08', '2026-01-27 15:48:53', '2026-01-27 15:49:08'),
(4, 3, 6, 1, 5.00, 6.00, 0.00, NULL, NULL, NULL, '0xcf7393C2eDea75F99C988926C9038Af27cC733b1', 'bnb_smart_chain', 'rejected', 'fake', NULL, 3, '2026-01-27 16:04:55', '2026-01-27 16:04:23', '2026-01-27 16:04:55'),
(5, 3, 3, NULL, 5.00, 5.00, 0.00, 'Rameez Nazar Zahida', '03262853600', NULL, NULL, NULL, 'rejected', 'fke', NULL, 3, '2026-01-27 16:49:53', '2026-01-27 16:47:20', '2026-01-27 16:49:53'),
(6, 3, 4, NULL, 5.00, 5.00, 0.00, 'Rameez Nazar Zahida', '03262853600', 'hbl', NULL, NULL, 'rejected', 'fake', NULL, 3, '2026-01-27 16:51:05', '2026-01-27 16:50:50', '2026-01-27 16:51:05'),
(7, 3, 3, NULL, 5.00, 5.00, 0.00, 'Rameez Nazar Zahida', '03262853600', NULL, NULL, NULL, 'rejected', 'fake', NULL, 3, '2026-01-28 23:31:05', '2026-01-28 23:29:47', '2026-01-28 23:31:05'),
(8, 4, 3, NULL, 5.00, 0.04, 4.96, 'Rameez Nazar Zahida', '03262853600', NULL, NULL, NULL, 'approved', NULL, 'assets/withdrawals/admin-proofs/FUwZZ7VpAdmLqejsyhh7hbRjGRhyCnQnNF0AxlxD.jpg', 3, '2026-01-29 09:32:53', '2026-01-29 09:29:24', '2026-01-29 09:32:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_user_id_index` (`user_id`),
  ADD KEY `chats_status_index` (`status`),
  ADD KEY `chats_assigned_to_index` (`assigned_to`),
  ADD KEY `chats_created_at_index` (`created_at`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_chat_id_index` (`chat_id`),
  ADD KEY `chat_messages_sender_id_index` (`sender_id`),
  ADD KEY `chat_messages_is_read_index` (`is_read`),
  ADD KEY `chat_messages_created_at_index` (`created_at`);

--
-- Indexes for table `crypto_wallets`
--
ALTER TABLE `crypto_wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `crypto_wallets_network_unique` (`network`);

--
-- Indexes for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposits_deposit_payment_method_id_foreign` (`deposit_payment_method_id`),
  ADD KEY `deposits_approved_by_foreign` (`approved_by`),
  ADD KEY `deposits_user_id_index` (`user_id`),
  ADD KEY `deposits_status_index` (`status`),
  ADD KEY `deposits_created_at_index` (`created_at`),
  ADD KEY `deposits_crypto_wallet_id_foreign` (`crypto_wallet_id`);

--
-- Indexes for table `deposit_payment_methods`
--
ALTER TABLE `deposit_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earning_commission_structures`
--
ALTER TABLE `earning_commission_structures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `earning_commission_structures_level_mining_plan_id_unique` (`level`,`mining_plan_id`),
  ADD KEY `earning_commission_structures_mining_plan_id_foreign` (`mining_plan_id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_user_id_index` (`user_id`),
  ADD KEY `investments_mining_plan_id_index` (`mining_plan_id`),
  ADD KEY `investments_status_index` (`status`),
  ADD KEY `investments_last_profit_calculated_at_index` (`last_profit_calculated_at`);

--
-- Indexes for table `investment_commission_structures`
--
ALTER TABLE `investment_commission_structures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investment_commission_structures_level_mining_plan_id_unique` (`level`,`mining_plan_id`),
  ADD KEY `investment_commission_structures_mining_plan_id_foreign` (`mining_plan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mining_plans`
--
ALTER TABLE `mining_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `notifications_created_at_index` (`created_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pending_earning_commissions`
--
ALTER TABLE `pending_earning_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pending_earning_commissions_mining_plan_id_foreign` (`mining_plan_id`),
  ADD KEY `pending_earning_commissions_referrer_id_index` (`referrer_id`),
  ADD KEY `pending_earning_commissions_investor_id_index` (`investor_id`),
  ADD KEY `pending_earning_commissions_investment_id_index` (`investment_id`),
  ADD KEY `pending_earning_commissions_is_claimed_index` (`is_claimed`),
  ADD KEY `pending_earning_commissions_referrer_id_is_claimed_index` (`referrer_id`,`is_claimed`);

--
-- Indexes for table `pending_referral_commissions`
--
ALTER TABLE `pending_referral_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pending_referral_commissions_mining_plan_id_foreign` (`mining_plan_id`),
  ADD KEY `pending_referral_commissions_referrer_id_index` (`referrer_id`),
  ADD KEY `pending_referral_commissions_investor_id_index` (`investor_id`),
  ADD KEY `pending_referral_commissions_investment_id_index` (`investment_id`),
  ADD KEY `pending_referral_commissions_is_claimed_index` (`is_claimed`),
  ADD KEY `pending_referral_commissions_referrer_id_is_claimed_index` (`referrer_id`,`is_claimed`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reward_levels`
--
ALTER TABLE `reward_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reward_levels_level_unique` (`level`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_index` (`user_id`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_status_index` (`status`),
  ADD KEY `transactions_created_at_index` (`created_at`),
  ADD KEY `transactions_reference_id_reference_type_index` (`reference_id`,`reference_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_refer_code_unique` (`refer_code`);

--
-- Indexes for table `user_reward_levels`
--
ALTER TABLE `user_reward_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_reward_levels_user_id_reward_level_id_unique` (`user_id`,`reward_level_id`),
  ADD KEY `user_reward_levels_user_id_index` (`user_id`),
  ADD KEY `user_reward_levels_reward_level_id_index` (`reward_level_id`),
  ADD KEY `user_reward_levels_achieved_at_index` (`achieved_at`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_deposit_payment_method_id_foreign` (`deposit_payment_method_id`),
  ADD KEY `withdrawals_approved_by_foreign` (`approved_by`),
  ADD KEY `withdrawals_user_id_index` (`user_id`),
  ADD KEY `withdrawals_status_index` (`status`),
  ADD KEY `withdrawals_created_at_index` (`created_at`),
  ADD KEY `withdrawals_crypto_wallet_id_foreign` (`crypto_wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `crypto_wallets`
--
ALTER TABLE `crypto_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deposit_payment_methods`
--
ALTER TABLE `deposit_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `earning_commission_structures`
--
ALTER TABLE `earning_commission_structures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `investment_commission_structures`
--
ALTER TABLE `investment_commission_structures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `mining_plans`
--
ALTER TABLE `mining_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `pending_earning_commissions`
--
ALTER TABLE `pending_earning_commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pending_referral_commissions`
--
ALTER TABLE `pending_referral_commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_levels`
--
ALTER TABLE `reward_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_reward_levels`
--
ALTER TABLE `user_reward_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deposits_crypto_wallet_id_foreign` FOREIGN KEY (`crypto_wallet_id`) REFERENCES `crypto_wallets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deposits_deposit_payment_method_id_foreign` FOREIGN KEY (`deposit_payment_method_id`) REFERENCES `deposit_payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `earning_commission_structures`
--
ALTER TABLE `earning_commission_structures`
  ADD CONSTRAINT `earning_commission_structures_mining_plan_id_foreign` FOREIGN KEY (`mining_plan_id`) REFERENCES `mining_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_mining_plan_id_foreign` FOREIGN KEY (`mining_plan_id`) REFERENCES `mining_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investment_commission_structures`
--
ALTER TABLE `investment_commission_structures`
  ADD CONSTRAINT `investment_commission_structures_mining_plan_id_foreign` FOREIGN KEY (`mining_plan_id`) REFERENCES `mining_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pending_earning_commissions`
--
ALTER TABLE `pending_earning_commissions`
  ADD CONSTRAINT `pending_earning_commissions_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_earning_commissions_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_earning_commissions_mining_plan_id_foreign` FOREIGN KEY (`mining_plan_id`) REFERENCES `mining_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_earning_commissions_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pending_referral_commissions`
--
ALTER TABLE `pending_referral_commissions`
  ADD CONSTRAINT `pending_referral_commissions_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_referral_commissions_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_referral_commissions_mining_plan_id_foreign` FOREIGN KEY (`mining_plan_id`) REFERENCES `mining_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_referral_commissions_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_reward_levels`
--
ALTER TABLE `user_reward_levels`
  ADD CONSTRAINT `user_reward_levels_reward_level_id_foreign` FOREIGN KEY (`reward_level_id`) REFERENCES `reward_levels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_reward_levels_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `withdrawals_crypto_wallet_id_foreign` FOREIGN KEY (`crypto_wallet_id`) REFERENCES `crypto_wallets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `withdrawals_deposit_payment_method_id_foreign` FOREIGN KEY (`deposit_payment_method_id`) REFERENCES `deposit_payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
