-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 16 juil. 2026 à 13:23
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
-- Base de données : `mini_drive`
--

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT 0,
  `mime_type` varchar(100) DEFAULT NULL,
  `is_folder` tinyint(1) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `share_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `files`
--

INSERT INTO `files` (`id`, `user_id`, `filename`, `original_name`, `file_path`, `file_size`, `mime_type`, `is_folder`, `parent_id`, `is_deleted`, `deleted_at`, `share_token`, `created_at`) VALUES
(1, 1, 'folder_69ec1614619f3', 'Tracker_Team', 'folder_69ec1614619f3', 0, NULL, 1, NULL, 0, NULL, NULL, '2026-04-25 01:17:08'),
(2, 1, 'folder_69ec163752780', 'CTF', 'folder_69ec1614619f3/folder_69ec163752780', 0, NULL, 1, 1, 0, NULL, NULL, '2026-04-25 01:17:43'),
(3, 1, '69ec1695823d3.txt', 'note.txt', 'folder_69ec1614619f3/folder_69ec163752780/69ec1695823d3.txt', 28, 'text/plain', 0, 2, 0, NULL, NULL, '2026-04-25 01:19:17'),
(4, 1, '69ec16f2c925f.', ' ', 'folder_69ec1614619f3/folder_69ec163752780/69ec16f2c925f.', 26333, 'image/png', 0, 2, 1, '2026-04-25 05:12:09', 'bd9dd5a04284a860bd8fed566c97b696622ab21e64db0ebde0e6c87b52092210', '2026-04-25 01:20:50'),
(5, 1, 'folder_69ec21c1e0a08', 'Images', 'folder_69ec21c1e0a08', 0, NULL, 1, NULL, 0, NULL, '359549cac00c2be043f109f65af16d9891f7dd8b73c8c894e4e0e5d839372876', '2026-04-25 02:06:57'),
(6, 1, '69ec21c1e4892.png', 'Capture d’écran_2026-03-19_06-32-03.png', 'folder_69ec21c1e0a08/69ec21c1e4892.png', 92238, 'image/png', 0, 5, 0, NULL, '43dd86eeff639188130a13d1dd9d44ff8098acedda0e3d743dd89e43859e347b', '2026-04-25 02:06:57'),
(7, 1, '69ec21c1e55ad.png', 'tab_rip_R6.png', 'folder_69ec21c1e0a08/69ec21c1e55ad.png', 96426, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(8, 1, '69ec21c1e625a.png', 'shR3.png', 'folder_69ec21c1e0a08/69ec21c1e625a.png', 45255, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(9, 1, '69ec21c1e700c.png', 'captureR3_R4_apres_suprR2.png', 'folder_69ec21c1e0a08/69ec21c1e700c.png', 358945, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(10, 1, '69ec21c1e7cdf.png', 'configAddR2.png', 'folder_69ec21c1e0a08/69ec21c1e7cdf.png', 111566, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(11, 1, '69ec21c1e89ff.png', 'capture_R1_R6_apres_suprR2.png', 'folder_69ec21c1e0a08/69ec21c1e89ff.png', 368650, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(12, 1, '69ec21c1e96d0.png', 'ripR3.png', 'folder_69ec21c1e0a08/69ec21c1e96d0.png', 53548, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(13, 1, '69ec21c1ea3e5.png', 'tab_rip_R3.png', 'folder_69ec21c1e0a08/69ec21c1ea3e5.png', 94895, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(14, 1, '69ec21c1eb0df.png', 'tab_rip_R4.png', 'folder_69ec21c1e0a08/69ec21c1eb0df.png', 92913, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(15, 1, '69ec21c1ebdfa.png', 'ripR6.png', 'folder_69ec21c1e0a08/69ec21c1ebdfa.png', 52538, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(16, 1, '69ec21c1ecadd.png', 'topologie_OSPF.png', 'folder_69ec21c1e0a08/69ec21c1ecadd.png', 111347, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(17, 1, '69ec21c1ed732.png', 'shR2.png', 'folder_69ec21c1e0a08/69ec21c1ed732.png', 45461, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(18, 1, '69ec21c1ee417.png', 'conf_Add_R9.png', 'folder_69ec21c1e0a08/69ec21c1ee417.png', 135626, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(19, 1, '69ec21c1ef0a1.png', 'conf_Add_R8.png', 'folder_69ec21c1e0a08/69ec21c1ef0a1.png', 136454, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(20, 1, '69ec21c1efcf6.png', 'topologieRIP.png', 'folder_69ec21c1e0a08/69ec21c1efcf6.png', 39786, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(21, 1, '69ec21c1f097d.png', 'ripR5.png', 'folder_69ec21c1e0a08/69ec21c1f097d.png', 53003, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(22, 1, '69ec21c1f15ad.png', 'ping_rip_R1.png', 'folder_69ec21c1e0a08/69ec21c1f15ad.png', 130015, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(23, 1, '69ec21c1f232a.png', 'configAddR3.png', 'folder_69ec21c1e0a08/69ec21c1f232a.png', 113467, 'image/png', 0, 5, 0, NULL, NULL, '2026-04-25 02:06:57'),
(24, 1, '69ec21c1f30b5.png', 'Capture d’écran_2026-04-15_15-05-42.png', 'folder_69ec21c1e0a08/69ec21c1f30b5.png', 113287, 'image/png', 0, 5, 0, NULL, '86a851fc4541764ebc49aba71b8ff4f77e0319b964199672f23ab2437b8390b1', '2026-04-25 02:06:57'),
(25, 1, '69ec21d7b690e.png', 'Capture d’écran_2026-04-15_14-51-53.png', '/69ec21d7b690e.png', 23316, 'image/png', 0, NULL, 0, NULL, NULL, '2026-04-25 02:07:19'),
(26, 1, 'folder_69ec227489f60', 'docDrive', 'folder_69ec227489f60', 0, NULL, 1, NULL, 1, '2026-04-25 05:40:54', NULL, '2026-04-25 02:09:56'),
(27, 1, 'folder_69ec3fbced4f3', 'driveDoc', 'folder_69ec3fbced4f3', 0, NULL, 1, NULL, 0, NULL, '77d8c011bd505b97671530310370a2ac49aed919e7833e3404606bbf31de70fc', '2026-04-25 04:14:52'),
(28, 4, '6a58bdbc8eec3.jpg', '515-5154371_kuroko-no-basket-kuroko-chibi-hd-png-download.jpg', '/6a58bdbc8eec3.jpg', 38271, 'image/jpeg', 0, NULL, 0, NULL, NULL, '2026-07-16 11:17:16'),
(29, 4, '6a58bdde8ecd2.jpg', 'images.jpg', '/6a58bdde8ecd2.jpg', 8307, 'image/jpeg', 0, NULL, 0, NULL, 'fee3f25805fa8161da28d9978d70b175096398075619bab015c55c4477da0549', '2026-07-16 11:17:50');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'lfmijoro', 'fymijoro@gmail.com', '$2y$10$yexmtGSSCW6rE3bwD5E2IuO2.2eBtboR0PUototstqwqL683ua1i6', '2026-04-25 01:16:23'),
(2, 'Philiastre Gyani', 'philiastrex@gmail.com', '$2y$10$sbqTYHcK2O.qfpUvI8Ih7.db1.Aqnol6sNJ1NOG4x9O4CwJQwUo4e', '2026-04-25 03:23:35'),
(3, 'Luck', 'luck@gmail.com', '$2y$10$URRx.NsCdRLE6gd.J5QtHOhur/ftBWYwy/mtafUDALAeEVLJocnpm', '2026-04-25 11:57:58'),
(4, 'lf', 'exemple@gmail.com', '$2y$10$S2FOboOuLxk1VcFvSgIfV.ndu6jX7qI5nxPAYcEgam5EJ0ojouqKO', '2026-07-16 11:16:48');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `share_token` (`share_token`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_user_parent` (`user_id`,`parent_id`,`is_deleted`),
  ADD KEY `idx_share_token` (`share_token`),
  ADD KEY `idx_search` (`user_id`,`original_name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
