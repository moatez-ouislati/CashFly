-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 02:21 PM
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
-- Database: `mycashflydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id_message` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('text','image','file','system') DEFAULT 'text',
  `reply_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_edited` tinyint(1) DEFAULT 0,
  `status` enum('active','deleted') DEFAULT 'active',
  `edited_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id_message`, `id_room`, `id_utilisateur`, `message`, `type`, `reply_to`, `created_at`, `is_edited`, `status`, `edited_at`) VALUES
(44, 31, 111, '[Message supprimé]', 'text', NULL, '2026-02-28 08:11:07', 2, 'deleted', '2026-02-28 08:11:10'),
(45, 31, 112, '[Message supprimé]', 'text', NULL, '2026-03-03 08:58:34', 2, 'deleted', '2026-03-03 08:58:38'),
(46, 31, 112, 'tadfasdasdasd', 'text', NULL, '2026-03-03 08:58:42', 0, 'active', NULL),
(47, 31, 112, 'modified', 'text', NULL, '2026-03-03 10:46:44', 1, 'active', '2026-03-03 21:36:03'),
(48, 31, 112, '[Message supprimé]', 'text', 47, '2026-03-03 10:46:51', 2, 'deleted', '2026-03-03 21:35:54'),
(49, 31, 111, 'Rreponse', 'text', 48, '2026-03-03 21:34:08', 0, 'active', NULL),
(50, 31, 111, 'Reply', 'text', 47, '2026-03-03 21:36:59', 0, 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_presence`
--

CREATE TABLE `chat_presence` (
  `id_presence` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_online` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_presence`
--

INSERT INTO `chat_presence` (`id_presence`, `id_room`, `id_utilisateur`, `last_seen`, `is_online`) VALUES
(65, 30, 112, '2026-02-28 06:16:33', 0),
(68, 31, 111, '2026-03-03 21:37:17', 0),
(69, 31, 112, '2026-03-03 21:37:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `entreprises`
--

CREATE TABLE `entreprises` (
  `id_entreprise` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `forme_juridique` varchar(50) DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `capital` decimal(15,2) DEFAULT 0.00,
  `id_proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investissements`
--

CREATE TABLE `investissements` (
  `id_investissement` int(11) NOT NULL,
  `id_entreprise` int(11) NOT NULL,
  `id_investisseur` int(11) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `date_investissement` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','actif','termine','annule') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journées_portes_ouvertes`
--

CREATE TABLE `journées_portes_ouvertes` (
  `id_evenement` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `date_evenement` date NOT NULL,
  `lieu` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `max_participants` int(11) NOT NULL DEFAULT 50,
  `current_participants` int(11) NOT NULL DEFAULT 0,
  `id_createur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journées_portes_ouvertes`
--

INSERT INTO `journées_portes_ouvertes` (`id_evenement`, `titre`, `date_evenement`, `lieu`, `description`, `image_path`, `max_participants`, `current_participants`, `id_createur`) VALUES
(26, 'Journée Portes Ouvertes - École Supérieure de Commerce', '2026-02-12', 'ESCT Campus Centre-Ville, Tunis', 'Présentation des programmes en gestion, finance et marketing digital. Ateliers interactifs et témoignages d’anciens diplômés.', '/cashfly/images/events/jpo_commerce_2026.jpg', 100, 0, 111),
(27, 'Journée Portes Ouvertes - Institut Supérieur des Études Technologiques', '2026-02-18', 'ISET Sousse', 'Exploration des parcours technologiques : génie mécanique, électrique et informatique industrielle. Visite des laboratoires techniques.', '/cashfly/images/events/jpo_iset_2026.jpg', 80, 0, 111),
(28, 'Journée Portes Ouvertes - École d’Ingénieurs', '2026-02-22', 'Technopole Ghazala, Ariana', 'Présentation des spécialités en génie logiciel, télécommunications et systèmes embarqués. Démonstrations robotiques et stands projets.', '/cashfly/images/events/jpo_ingenieurs_2026.jpg', 150, 0, 111),
(29, 'Journée Portes Ouvertes - Faculté des Lettres et Sciences Humaines', '2026-02-27', 'Campus La Manouba', 'Découverte des filières en langues, communication et sciences sociales. Conférences thématiques et visite guidée des départements.', '/cashfly/images/events/jpo_lettres_2026.jpg', 70, 0, 111),
(30, 'Journée Portes Ouvertes - Centre de Formation Professionnelle', '2026-03-03', 'Centre Sectoriel de Formation, Sfax', 'Présentation des formations professionnelles certifiantes dans les domaines techniques et industriels.', '/cashfly/images/events/jpo_cfp_2026.jpg', 60, 1, 111),
(31, 'Journée Portes Ouvertes - École Supérieure des Technologies Avancées', '2026-03-08', 'Technopark Sousse', 'Immersion dans les programmes en intelligence artificielle, data science et cybersécurité. Démonstrations pratiques et ateliers coding.', '/cashfly/images/events/jpo_tech_2026.jpg', 110, 1, 111),
(32, 'Journée Portes Ouvertes - Institut des Arts et Multimédia', '2026-03-14', 'Campus Culturel, Tunis', 'Présentation des formations en design graphique, animation 3D et production audiovisuelle. Exposition des travaux étudiants.', '/cashfly/images/events/jpo_arts_2026.jpg', 1, 1, 122),
(33, 'Journée Portes Ouvertes - Faculté de Médecine', '2026-03-20', 'Faculté de Médecine de Monastir', 'Rencontre avec le corps enseignant et présentation du cursus médical, visites des laboratoires et amphithéâtres.', '/cashfly/images/events/jpo_medecine_2026.jpg', 130, 0, 122),
(34, 'Journée Portes Ouvertes - École Supérieure d’Architecture', '2026-03-27', 'Campus Urbanisme et Architecture, Nabeul', 'Découverte des ateliers d’architecture, maquettes étudiantes et présentation des projets de fin d’études.', '/cashfly/images/events/jpo_architecture_2026.jpg', 90, 0, 122),
(44, 'Journee Porte Ouverte Asus IT', '2026-03-14', 'Ghazela', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\n\n', '/cashfly/images/events/1772572029224_jpo_lettres_2026.jpg', 12, 0, 111),
(45, 'Journée Portes Ouvertes - École Supérieure des Technologies Avancées', '2026-03-08', 'Gammart', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\n\n', '/cashfly/images/events/1772572952788_1772572029224_jpo_lettres_2026.jpg', 20, 0, 111);

-- --------------------------------------------------------

--
-- Table structure for table `liste_attente`
--

CREATE TABLE `liste_attente` (
  `id_attente` int(11) NOT NULL,
  `id_evenement` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_demande` datetime DEFAULT current_timestamp(),
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opérations`
--

CREATE TABLE `opérations` (
  `id_operation` int(11) NOT NULL,
  `id_tresorerie` int(11) NOT NULL,
  `type` enum('revenu','depense') NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `categorie` varchar(50) DEFAULT NULL,
  `date_operation` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participation_jpo`
--

CREATE TABLE `participation_jpo` (
  `id_participation` int(11) NOT NULL,
  `id_evenement` int(11) NOT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `statut` enum('confirmé','en_attente','annulé','présent') DEFAULT 'confirmé',
  `date_inscription` datetime DEFAULT current_timestamp(),
  `badge_genere` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participation_jpo`
--

INSERT INTO `participation_jpo` (`id_participation`, `id_evenement`, `id_entreprise`, `id_utilisateur`, `statut`, `date_inscription`, `badge_genere`) VALUES
(38, 32, NULL, 113, 'annulé', '2026-02-28 08:33:51', 0),
(39, 32, NULL, 112, 'confirmé', '2026-03-01 04:46:57', 0),
(42, 30, NULL, 112, 'confirmé', '2026-03-01 00:18:08', 1),
(43, 31, NULL, 112, 'confirmé', '2026-03-03 09:22:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `retour_investissement`
--

CREATE TABLE `retour_investissement` (
  `id_retour` int(11) NOT NULL,
  `id_investissement` int(11) NOT NULL,
  `pourcentage_roi` decimal(5,2) DEFAULT NULL,
  `montant_gain` decimal(15,2) DEFAULT NULL,
  `date_paiement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `score_entreprise`
--

CREATE TABLE `score_entreprise` (
  `id_score` int(11) NOT NULL,
  `id_entreprise` int(11) NOT NULL,
  `score_ai` decimal(5,2) DEFAULT NULL CHECK (`score_ai` >= 0 and `score_ai` <= 100),
  `niveau_risque` enum('faible','moyen','eleve') DEFAULT 'moyen',
  `score_rentabilite` decimal(5,2) DEFAULT NULL,
  `score_croissance` decimal(5,2) DEFAULT NULL,
  `derniere_maj` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trésorerie`
--

CREATE TABLE `trésorerie` (
  `id_tresorerie` int(11) NOT NULL,
  `id_entreprise` int(11) NOT NULL,
  `solde` decimal(15,2) DEFAULT 0.00,
  `devise` varchar(3) DEFAULT 'EUR',
  `derniere_maj` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_kyc`
--

CREATE TABLE `user_kyc` (
  `user_id` int(11) NOT NULL,
  `face_embedding` blob DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `id_document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `cin` int(11) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('proprietaire','investisseur','administrateur') NOT NULL,
  `face_image` varchar(255) DEFAULT NULL,
  `yearsExperience` varchar(100) DEFAULT NULL,
  `highestProfit` varchar(100) DEFAULT NULL,
  `budget` varchar(100) DEFAULT NULL,
  `active` int(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `cin`, `tel`, `nom`, `prenom`, `nom_complet`, `email`, `mot_de_passe`, `role`, `face_image`, `yearsExperience`, `highestProfit`, `budget`, `active`, `date_creation`) VALUES
(111, NULL, NULL, NULL, NULL, 'Mehdi Temime', '111', '123', 'proprietaire', NULL, NULL, NULL, NULL, 1, '2026-02-21 01:20:25'),
(112, NULL, NULL, NULL, NULL, 'Hassine Temime', '222', '123', 'investisseur', NULL, NULL, NULL, NULL, 1, '2026-02-21 01:21:25'),
(113, NULL, NULL, NULL, NULL, 'Yassine Temime', '333', '123', 'investisseur', NULL, NULL, NULL, NULL, 1, '2026-02-21 01:21:58'),
(122, NULL, NULL, NULL, NULL, 'Firdaws Temime', '444', '123', 'proprietaire', NULL, NULL, NULL, NULL, 1, '2025-02-21 01:21:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `reply_to` (`reply_to`),
  ADD KEY `idx_message_room` (`id_room`,`created_at`);

--
-- Indexes for table `chat_presence`
--
ALTER TABLE `chat_presence`
  ADD PRIMARY KEY (`id_presence`),
  ADD UNIQUE KEY `unique_presence` (`id_room`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id_entreprise`),
  ADD KEY `id_proprietaire` (`id_proprietaire`);

--
-- Indexes for table `investissements`
--
ALTER TABLE `investissements`
  ADD PRIMARY KEY (`id_investissement`),
  ADD UNIQUE KEY `unique_investissement` (`id_entreprise`,`id_investisseur`),
  ADD KEY `id_investisseur` (`id_investisseur`);

--
-- Indexes for table `journées_portes_ouvertes`
--
ALTER TABLE `journées_portes_ouvertes`
  ADD PRIMARY KEY (`id_evenement`);

--
-- Indexes for table `liste_attente`
--
ALTER TABLE `liste_attente`
  ADD PRIMARY KEY (`id_attente`),
  ADD KEY `id_evenement` (`id_evenement`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `opérations`
--
ALTER TABLE `opérations`
  ADD PRIMARY KEY (`id_operation`),
  ADD KEY `id_tresorerie` (`id_tresorerie`);

--
-- Indexes for table `participation_jpo`
--
ALTER TABLE `participation_jpo`
  ADD PRIMARY KEY (`id_participation`),
  ADD UNIQUE KEY `unique_participation_investisseur` (`id_evenement`,`id_utilisateur`),
  ADD UNIQUE KEY `unique_participation_entreprise` (`id_evenement`,`id_entreprise`),
  ADD KEY `id_entreprise` (`id_entreprise`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `retour_investissement`
--
ALTER TABLE `retour_investissement`
  ADD PRIMARY KEY (`id_retour`),
  ADD KEY `id_investissement` (`id_investissement`);

--
-- Indexes for table `score_entreprise`
--
ALTER TABLE `score_entreprise`
  ADD PRIMARY KEY (`id_score`),
  ADD UNIQUE KEY `unique_entreprise_score` (`id_entreprise`);

--
-- Indexes for table `trésorerie`
--
ALTER TABLE `trésorerie`
  ADD PRIMARY KEY (`id_tresorerie`),
  ADD UNIQUE KEY `id_entreprise` (`id_entreprise`);

--
-- Indexes for table `user_kyc`
--
ALTER TABLE `user_kyc`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `chat_presence`
--
ALTER TABLE `chat_presence`
  MODIFY `id_presence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investissements`
--
ALTER TABLE `investissements`
  MODIFY `id_investissement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journées_portes_ouvertes`
--
ALTER TABLE `journées_portes_ouvertes`
  MODIFY `id_evenement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `liste_attente`
--
ALTER TABLE `liste_attente`
  MODIFY `id_attente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opérations`
--
ALTER TABLE `opérations`
  MODIFY `id_operation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participation_jpo`
--
ALTER TABLE `participation_jpo`
  MODIFY `id_participation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `retour_investissement`
--
ALTER TABLE `retour_investissement`
  MODIFY `id_retour` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `score_entreprise`
--
ALTER TABLE `score_entreprise`
  MODIFY `id_score` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trésorerie`
--
ALTER TABLE `trésorerie`
  MODIFY `id_tresorerie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `journées_portes_ouvertes` (`id_evenement`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_3` FOREIGN KEY (`reply_to`) REFERENCES `chat_messages` (`id_message`);

--
-- Constraints for table `chat_presence`
--
ALTER TABLE `chat_presence`
  ADD CONSTRAINT `chat_presence_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `journées_portes_ouvertes` (`id_evenement`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_presence_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Constraints for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_ibfk_1` FOREIGN KEY (`id_proprietaire`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `investissements`
--
ALTER TABLE `investissements`
  ADD CONSTRAINT `investissements_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`),
  ADD CONSTRAINT `investissements_ibfk_2` FOREIGN KEY (`id_investisseur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `liste_attente`
--
ALTER TABLE `liste_attente`
  ADD CONSTRAINT `liste_attente_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `journées_portes_ouvertes` (`id_evenement`),
  ADD CONSTRAINT `liste_attente_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `opérations`
--
ALTER TABLE `opérations`
  ADD CONSTRAINT `opérations_ibfk_1` FOREIGN KEY (`id_tresorerie`) REFERENCES `trésorerie` (`id_tresorerie`);

--
-- Constraints for table `participation_jpo`
--
ALTER TABLE `participation_jpo`
  ADD CONSTRAINT `participation_jpo_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `journées_portes_ouvertes` (`id_evenement`),
  ADD CONSTRAINT `participation_jpo_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`),
  ADD CONSTRAINT `participation_jpo_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `retour_investissement`
--
ALTER TABLE `retour_investissement`
  ADD CONSTRAINT `retour_investissement_ibfk_1` FOREIGN KEY (`id_investissement`) REFERENCES `investissements` (`id_investissement`);

--
-- Constraints for table `score_entreprise`
--
ALTER TABLE `score_entreprise`
  ADD CONSTRAINT `score_entreprise_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`);

--
-- Constraints for table `trésorerie`
--
ALTER TABLE `trésorerie`
  ADD CONSTRAINT `trésorerie_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`);

--
-- Constraints for table `user_kyc`
--
ALTER TABLE `user_kyc`
  ADD CONSTRAINT `user_kyc_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
