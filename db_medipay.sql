-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 23 juin 2025 à 10:13
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_medipay`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergies`
--

CREATE TABLE `allergies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Antalgiques', 'Distinctio quae in odio qui quidem tempore nulla.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(2, 'Antibiotiques', 'Modi aperiam dignissimos optio.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(3, 'Antihistaminiques', 'Debitis nam aut magni voluptate minus iure.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(4, 'Vitamines', 'Sed asperiores voluptas sit aut.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(5, 'Dermatologiques', 'Dolore aperiam et esse est.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(6, 'Antiparasitaires', 'Animi sed voluptatibus ut aspernatur quas et.', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29');

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `nom`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Antibiotiques', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(2, 'Antihypertenseurs', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(3, 'Antidépresseurs', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(4, 'Anti-inflammatoires', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(5, 'Antalgiques', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(6, 'Antidiabétiques', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(7, 'Antihistaminiques', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(8, 'Bronchodilatateurs', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(9, 'Anticoagulants', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(10, 'Statines', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29');

-- --------------------------------------------------------

--
-- Structure de la table `dcis`
--

CREATE TABLE `dcis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dcis`
--

INSERT INTO `dcis` (`id`, `nom`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Paracétamol', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(2, 'Ibuprofène', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(3, 'Amoxicilline', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(4, 'Metformine', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(5, 'Atorvastatine', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(6, 'Oméprazole', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(7, 'Amlodipine', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(8, 'Acide acétylsalicylique', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(9, 'Ciprofloxacine', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(10, 'Lévothyroxine', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(11, 'New DCI', NULL, '2025-06-21 20:41:09', '2025-06-21 20:41:09');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formes`
--

CREATE TABLE `formes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formes`
--

INSERT INTO `formes` (`id`, `nom`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Comprimé', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(2, 'Gélule', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(3, 'Sirop', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(4, 'Injectable', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(5, 'Pommade', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(6, 'Suppositoire', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(7, 'Crème', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(8, 'Solution buvable', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(9, 'Poudre', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(10, 'Collyre', NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medicaments_prescrits`
--

CREATE TABLE `medicaments_prescrits` (
  `quantite` int(11) NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'false: non sélectionné, true: sélectionné',
  `posologie` varchar(191) DEFAULT NULL,
  `duree` varchar(191) DEFAULT NULL,
  `avis` varchar(191) DEFAULT NULL,
  `substitution_autorisee` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pharmaceutical_product_id` bigint(20) UNSIGNED NOT NULL,
  `ordonnance_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `medicaments_prescrits`
--

INSERT INTO `medicaments_prescrits` (`quantite`, `statut`, `posologie`, `duree`, `avis`, `substitution_autorisee`, `deleted_at`, `created_at`, `updated_at`, `pharmaceutical_product_id`, `ordonnance_id`) VALUES
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:20', '2025-06-22 10:32:20', 1, 2),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:29', '2025-06-22 10:32:29', 1, 3),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-21 21:30:18', '2025-06-21 21:30:18', 2, 1),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:20', '2025-06-22 10:32:20', 2, 2),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:29', '2025-06-22 10:32:29', 2, 3),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:20', '2025-06-22 10:32:20', 3, 2),
(1, 0, '1 comprimé par jour', '7 jours', 'Aucun', 1, NULL, '2025-06-22 10:32:29', '2025-06-22 10:32:29', 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_14_092800_create_services_table', 1),
(5, '2025_04_14_092837_create_stocks_table', 1),
(6, '2025_04_14_092916_create_rendez_vous_table', 1),
(7, '2025_04_14_093004_create_ordonnances_table', 1),
(8, '2025_04_14_093015_create_paiements_table', 1),
(9, '2025_04_16_101050_create_categories_table', 1),
(10, '2025_04_16_101108_create_sous_categories_table', 1),
(11, '2025_04_16_101117_create_formes_table', 1),
(12, '2025_04_16_101129_create_classes_table', 1),
(13, '2025_04_16_101144_create_dcis_table', 1),
(14, '2025_04_16_153628_create_pharmaceutical_products_table', 1),
(15, '2025_04_16_154200_create_medicaments_prescrits_table', 1),
(16, '2025_05_22_052347_add_telephone_to_password_reset_tokens_table', 1),
(17, '2025_05_22_151245_create_allergies_table', 1),
(18, '2025_05_22_154913_create_personal_access_tokens_table', 1),
(19, '2025_05_28_114543_add_device_token_to_users_table', 1),
(20, '2025_05_28_151740_create_permission_tables', 1),
(21, '2025_06_04_103721_create_simple_notifications_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 4),
(7, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Structure de la table `ordonnances`
--

CREATE TABLE `ordonnances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `montant_total` double NOT NULL,
  `montant_paye` double NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ordonnances`
--

INSERT INTO `ordonnances` (`id`, `montant_total`, `montant_paye`, `patient_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 608, 0, 2, NULL, '2025-06-21 21:30:18', '2025-06-21 21:30:18'),
(2, 1108, 0, 2, NULL, '2025-06-22 10:32:20', '2025-06-22 10:32:20'),
(3, 1108, 0, 2, NULL, '2025-06-22 10:32:29', '2025-06-22 10:32:29');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prix_total` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '94977b640507f37e0443081af0afea9db9d8eb071db9a9c774999bfc9eaeb617', '[\"*\"]', NULL, NULL, '2025-06-21 20:07:44', '2025-06-21 20:07:44'),
(3, 'App\\Models\\User', 3, 'auth_token', 'ea878a28b4e904d3a9332e743816467a04c07a4f3265920bd041a3c25b2b5b35', '[\"*\"]', NULL, NULL, '2025-06-21 20:12:37', '2025-06-21 20:12:37'),
(4, 'App\\Models\\User', 4, 'auth_token', '0bdb9692f5b20015c4e8668604f583cd7438ac77c05b69ecea9ec9ae4e970d31', '[\"*\"]', NULL, NULL, '2025-06-21 20:14:43', '2025-06-21 20:14:43'),
(5, 'App\\Models\\User', 5, 'auth_token', '9543d03a670e83eeead8fb6b36863a8ef1773a9ad1618bc59469544ee1c81707', '[\"*\"]', NULL, NULL, '2025-06-21 20:17:46', '2025-06-21 20:17:46'),
(6, 'App\\Models\\User', 6, 'auth_token', 'b7fd9a0d933c7665b842618248772520aa6569abc8cfc4fc786a9f6d0396ae53', '[\"*\"]', NULL, NULL, '2025-06-21 20:20:23', '2025-06-21 20:20:23'),
(11, 'App\\Models\\User', 2, 'auth_token', '073427a3d7820e9eab30327288f4cff00092d03e722661f900246fd55840f078', '[\"*\"]', '2025-06-21 21:53:18', NULL, '2025-06-21 21:31:21', '2025-06-21 21:53:18'),
(14, 'App\\Models\\User', 2, 'auth_token', '233902a652bb0c5d5a2ab52272b39533fd90c6344e881d5d9cd18fde0b005e4c', '[\"*\"]', '2025-06-22 10:33:35', NULL, '2025-06-22 10:33:30', '2025-06-22 10:33:35'),
(15, 'App\\Models\\User', 2, 'auth_token', '65a7493775de02ebc5238d298a230cfbe95e8d7f8077f067acfddc1527dbc977', '[\"*\"]', '2025-06-22 15:08:33', NULL, '2025-06-22 13:47:48', '2025-06-22 15:08:33'),
(16, 'App\\Models\\User', 2, 'auth_token', '614eeda03044171b882b2f0c06eecd90d7bcaa33ced6a92399c52bb2229c620b', '[\"*\"]', '2025-06-22 16:44:15', NULL, '2025-06-22 16:10:56', '2025-06-22 16:44:15'),
(17, 'App\\Models\\User', 2, 'auth_token', '768c584f7901121865839e061bc86d5417b2f74aa62b1012c108e83a7ba8485a', '[\"*\"]', '2025-06-22 19:59:27', NULL, '2025-06-22 19:08:23', '2025-06-22 19:59:27'),
(18, 'App\\Models\\User', 2, 'auth_token', '1c0f89a53586b0adc35ffa4d7e60b772dfe51bbc85fe91fc5e6a0653d2e90a24', '[\"*\"]', '2025-06-22 20:47:17', NULL, '2025-06-22 20:47:12', '2025-06-22 20:47:17');

-- --------------------------------------------------------

--
-- Structure de la table `pharmaceutical_products`
--

CREATE TABLE `pharmaceutical_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(191) NOT NULL,
  `nom_produit` varchar(191) NOT NULL,
  `dosage` varchar(191) NOT NULL,
  `prix` double NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `dci_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `sous_categorie_id` bigint(20) UNSIGNED NOT NULL,
  `forme_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pharmaceutical_products`
--

INSERT INTO `pharmaceutical_products` (`id`, `image_path`, `nom_produit`, `dosage`, `prix`, `stock`, `description`, `date_expiration`, `dci_id`, `classe_id`, `categorie_id`, `sous_categorie_id`, `forme_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'storage/products/1750545409.jpg', 'embrouille', '200mh', 200, 8, 'tu es problème mon fils', '2025-07-17', 5, 7, 3, 10, 5, NULL, '2025-06-21 20:36:49', '2025-06-21 20:36:49'),
(2, 'storage/products/1750545557.jpg', 'psychopathe', '700mg', 608, 30, 'les devs sont des courageux', '2025-08-13', 4, 4, 1, 3, 3, NULL, '2025-06-21 20:39:17', '2025-06-21 20:39:17'),
(3, 'storage/products/1750545640.jpg', 'devs conflits', '300 mg', 300, 200, 'je suis un dev fluustack', '2025-08-13', 5, 3, 2, 7, 3, NULL, '2025-06-21 20:40:40', '2025-06-21 20:40:40');

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

CREATE TABLE `rendez_vous` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom_visiteur` varchar(191) DEFAULT NULL,
  `prenom_visiteur` varchar(191) DEFAULT NULL,
  `numero_visiteur` varchar(191) DEFAULT NULL,
  `date_rdv` date DEFAULT NULL,
  `statut` varchar(191) NOT NULL DEFAULT 'attente' COMMENT 'Statut du rendez-vous: attente, annule, termine, confirme',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL
) ;

--
-- Déchargement des données de la table `rendez_vous`
--

INSERT INTO `rendez_vous` (`id`, `nom_visiteur`, `prenom_visiteur`, `numero_visiteur`, `date_rdv`, `statut`, `deleted_at`, `created_at`, `updated_at`, `patient_id`, `service_id`) VALUES
(1, NULL, NULL, NULL, NULL, 'attente', NULL, '2025-06-21 20:25:16', '2025-06-21 20:25:16', 2, 2),
(2, NULL, NULL, NULL, '2025-06-29', 'attente', NULL, '2025-06-21 20:26:04', '2025-06-21 20:26:04', 2, 3),
(3, 'AGNIDE', 'Akim', '0149653233', NULL, 'attente', NULL, '2025-06-21 20:28:05', '2025-06-21 20:28:05', 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'pending', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(2, 'admin', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(3, 'admin_pharmacie', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(4, 'personnel_accueil', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(5, 'pharmacie', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(6, 'service_medical', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(7, 'patient', 'web', '2025-06-21 20:05:30', '2025-06-21 20:05:30');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `telephone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `prix_rdv` double DEFAULT NULL,
  `heure_ouverture` time DEFAULT NULL,
  `heure_fermeture` time DEFAULT NULL,
  `duree_moy_rdv` time DEFAULT NULL,
  `sous_rdv` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `nom`, `telephone`, `email`, `prix_rdv`, `heure_ouverture`, `heure_fermeture`, `duree_moy_rdv`, `sous_rdv`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Pédiatrie', '0151439322', 'pediatrie@centre-sante.com', 2000, '08:00:00', '16:00:00', '00:30:00', 1, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(2, 'Oncologie', '0151439323', 'oncologie@centre-sante.com', 5000, '09:00:00', '17:00:00', '01:00:00', 0, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(3, 'Médecine Générale', '0151439324', 'medecine.generale@centre-sante.com', 1500, '07:30:00', '18:00:00', '00:20:00', 1, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(4, 'Cardiologie', '0151439325', 'cardiologie@centre-sante.com', 3500, '08:30:00', '16:30:00', '00:45:00', 0, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(5, 'Gynécologie', '0151439326', 'gynecologie@centre-sante.com', 2500, '09:00:00', '17:00:00', '00:30:00', 1, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(6, 'Dermatologie', '0151439327', 'dermatologie@centre-sante.com', 2000, '10:00:00', '18:00:00', '00:25:00', 1, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30'),
(7, 'Ophtalmologie', '0151439328', 'ophtalmologie@centre-sante.com', 3000, '08:00:00', '15:00:00', '00:30:00', 0, NULL, '2025-06-21 20:05:30', '2025-06-21 20:05:30');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `simple_notifications`
--

CREATE TABLE `simple_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personnel_sante_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'personnel_registration',
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `simple_notifications`
--

INSERT INTO `simple_notifications` (`id`, `personnel_sante_id`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'personnel_registration', 'accepted', '2025-06-21 20:12:37', '2025-06-21 20:22:26'),
(2, 4, 'personnel_registration', 'accepted', '2025-06-21 20:14:43', '2025-06-21 20:22:39'),
(3, 5, 'personnel_registration', 'accepted', '2025-06-21 20:17:46', '2025-06-21 20:22:44'),
(4, 6, 'personnel_registration', 'accepted', '2025-06-21 20:20:23', '2025-06-21 20:22:47');

-- --------------------------------------------------------

--
-- Structure de la table `sous_categories`
--

CREATE TABLE `sous_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sous_categories`
--

INSERT INTO `sous_categories` (`id`, `nom`, `categorie_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Antipyrétiques', 1, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(2, 'Anti-inflammatoires', 1, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(3, 'Opioïdes', 1, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(4, 'Analgésiques topiques', 1, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(5, 'Antibactériens', 2, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(6, 'Antiviraux', 2, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(7, 'Antifongiques', 2, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(8, 'Antituberculeux', 2, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(9, 'Statines', 3, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(10, 'H1-antagonistes', 3, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(11, 'H2-antagonistes', 3, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(12, 'Vitamine C', 4, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(13, 'Vitamine D', 4, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(14, 'Vitamine B', 4, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(15, 'Multivitamines', 4, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(16, 'Corticostéroïdes', 5, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(17, 'Antifongiques topiques', 5, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(18, 'Antiacnéiques', 5, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(19, 'Hydratants', 5, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(20, 'Anthelminthiques', 6, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(21, 'Antiprotozoaires', 6, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29'),
(22, 'Ectoparasiticides', 6, NULL, '2025-06-21 20:05:29', '2025-06-21 20:05:29');

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quantite_disponible` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) NOT NULL,
  `prenom` varchar(191) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `code_patient` varchar(40) DEFAULT NULL,
  `genre` varchar(191) DEFAULT NULL,
  `date_naissance` varchar(191) DEFAULT NULL,
  `npi` varchar(191) DEFAULT NULL,
  `role_voulu` varchar(191) DEFAULT NULL,
  `service_voulu` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `device_token` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `telephone`, `code_patient`, `genre`, `date_naissance`, `npi`, `role_voulu`, `service_voulu`, `email`, `device_token`, `email_verified_at`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '+2290151439322', NULL, 'male', '02/06/2025', NULL, NULL, NULL, 'admin@gmail.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$EgFW0L9LGq4yejBR4cHxf.gC3aHc6a1RdoY2tZnTEtVd.uwo3Lt3K', NULL, NULL, '2025-06-21 20:07:44', '2025-06-21 20:07:44'),
(2, 'obed', 'obed', '+2290140479728', NULL, 'male', '06/06/2025', NULL, NULL, NULL, 'obedsallaiadifon@gmai.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$pPvM3Lj1ZuCPG81vTqDSzOmCCmjhbK9QxAjqys3o0AixEWAxraXwK', NULL, NULL, '2025-06-21 20:09:56', '2025-06-21 20:09:56'),
(3, 'dani', 'dani', '+2290140479729', NULL, NULL, NULL, 'hfkdldh', 'admin_pharmacie', NULL, 'dani@gmail.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$lEY7uQOwofcniVclAVlEt.2NqVg5qrMpnMp5kCnUbcJxClQognjuy', NULL, NULL, '2025-06-21 20:12:37', '2025-06-21 20:12:37'),
(4, 'sarah', 'Fanou', '+2290140479730', NULL, NULL, NULL, 'hdjdgdl', 'service_medical', '1', 'sarahf@gmail.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$4A6pV9qex/or1imyz58ey./23OKWimCheqg.2BTgNbeLSg5o9wPdy', NULL, NULL, '2025-06-21 20:14:43', '2025-06-21 20:14:43'),
(5, 'sanda', 'djilâne', '+2290140479731', NULL, NULL, NULL, 'jdjdhdk', 'personnel_accueil', NULL, 'djiso@gmail.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$n9p7WO6aw87JbahP90ZkMuvyLWoBmUVYkJxuJaU4wrgfAI0.apJvi', NULL, NULL, '2025-06-21 20:17:46', '2025-06-21 20:17:46'),
(6, 'TOGNY', 'Gérard', '+2290140479732', NULL, NULL, NULL, 'hfjdkf kdkdld kdhdkdl', 'pharmacie', NULL, 'gerard@gmail.com', 'd0GvABWnSwa9RnpRc_NQ1j:APA91bELdn91Yp-R8rkXHgMib2QflqTJtGgz7zrzEG2wiKjvbzyAn3KGriQYWrONNz4L8YQoMJhHIzFF4c_ypWEgzstP9uT5R6JqaF_BZiZLnbUgpBy0gUk', NULL, '$2y$12$Wnf1Ijtt.lK.KMCm5aOp0.1vIwkEMNmoQY8eF6YBHSLjPDWuNL9VS', NULL, NULL, '2025-06-21 20:20:23', '2025-06-21 20:20:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergies`
--
ALTER TABLE `allergies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allergies_patient_id_foreign` (`patient_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dcis`
--
ALTER TABLE `dcis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `formes`
--
ALTER TABLE `formes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `medicaments_prescrits`
--
ALTER TABLE `medicaments_prescrits`
  ADD PRIMARY KEY (`pharmaceutical_product_id`,`ordonnance_id`),
  ADD KEY `medicaments_prescrits_ordonnance_id_foreign` (`ordonnance_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `ordonnances`
--
ALTER TABLE `ordonnances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordonnances_patient_id_foreign` (`patient_id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `pharmaceutical_products`
--
ALTER TABLE `pharmaceutical_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmaceutical_products_dci_id_foreign` (`dci_id`),
  ADD KEY `pharmaceutical_products_classe_id_foreign` (`classe_id`),
  ADD KEY `pharmaceutical_products_categorie_id_foreign` (`categorie_id`),
  ADD KEY `pharmaceutical_products_sous_categorie_id_foreign` (`sous_categorie_id`),
  ADD KEY `pharmaceutical_products_forme_id_foreign` (`forme_id`);

--
-- Index pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rendez_vous_patient_id_foreign` (`patient_id`),
  ADD KEY `rendez_vous_service_id_foreign` (`service_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_email_unique` (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `simple_notifications`
--
ALTER TABLE `simple_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simple_notifications_personnel_sante_id_foreign` (`personnel_sante_id`);

--
-- Index pour la table `sous_categories`
--
ALTER TABLE `sous_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sous_categories_categorie_id_foreign` (`categorie_id`);

--
-- Index pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_telephone_unique` (`telephone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergies`
--
ALTER TABLE `allergies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `dcis`
--
ALTER TABLE `dcis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `formes`
--
ALTER TABLE `formes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `ordonnances`
--
ALTER TABLE `ordonnances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `pharmaceutical_products`
--
ALTER TABLE `pharmaceutical_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `simple_notifications`
--
ALTER TABLE `simple_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `sous_categories`
--
ALTER TABLE `sous_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `allergies`
--
ALTER TABLE `allergies`
  ADD CONSTRAINT `allergies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `medicaments_prescrits`
--
ALTER TABLE `medicaments_prescrits`
  ADD CONSTRAINT `medicaments_prescrits_ordonnance_id_foreign` FOREIGN KEY (`ordonnance_id`) REFERENCES `ordonnances` (`id`),
  ADD CONSTRAINT `medicaments_prescrits_pharmaceutical_product_id_foreign` FOREIGN KEY (`pharmaceutical_product_id`) REFERENCES `pharmaceutical_products` (`id`);

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ordonnances`
--
ALTER TABLE `ordonnances`
  ADD CONSTRAINT `ordonnances_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `pharmaceutical_products`
--
ALTER TABLE `pharmaceutical_products`
  ADD CONSTRAINT `pharmaceutical_products_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmaceutical_products_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmaceutical_products_dci_id_foreign` FOREIGN KEY (`dci_id`) REFERENCES `dcis` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmaceutical_products_forme_id_foreign` FOREIGN KEY (`forme_id`) REFERENCES `formes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmaceutical_products_sous_categorie_id_foreign` FOREIGN KEY (`sous_categorie_id`) REFERENCES `sous_categories` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD CONSTRAINT `rendez_vous_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rendez_vous_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `simple_notifications`
--
ALTER TABLE `simple_notifications`
  ADD CONSTRAINT `simple_notifications_personnel_sante_id_foreign` FOREIGN KEY (`personnel_sante_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sous_categories`
--
ALTER TABLE `sous_categories`
  ADD CONSTRAINT `sous_categories_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
