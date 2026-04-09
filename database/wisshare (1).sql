-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 10 avr. 2026 à 01:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wisshare`
--

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categorie` varchar(50) DEFAULT 'web',
  `type_fichier` varchar(10) DEFAULT 'pdf',
  `fichier` varchar(255) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_depot` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `titre`, `description`, `categorie`, `type_fichier`, `fichier`, `utilisateur_id`, `date_depot`) VALUES
(2, 'Plateforme E-learning Innovante', 'Plateforme complète avec chat en temps réel, visioconférence et système de notation automatique.', 'web', 'pdf', 'projet_e-learning.pdf', 1, '2025-04-01 10:30:00'),
(3, 'Site Portfolio Dynamique', 'Site vitrine avec administration pour créateurs, gestion de projets et blog intégré.', 'web', 'zip', 'portfolio_dynamique.zip', 2, '2025-03-28 14:15:00'),
(4, 'Système de Gestion de Bibliothèque', 'Application web pour gérer les emprunts, retours et catalogue avec QR codes.', 'web', 'pdf', 'gestion_bibliotheque.pdf', 3, '2025-03-25 09:00:00'),
(5, 'Dashboard Administratif', 'Interface pour gérer les étudiants, professeurs et emplois du temps.', 'web', 'zip', 'dashboard_admin.zip', 1, '2025-03-20 16:45:00'),
(6, 'Chatbot Éducatif IA', 'Assistant virtuel pour répondre aux questions des étudiants sur les cours.', 'ia', 'pdf', 'chatbot_educatif.pdf', 4, '2025-04-02 11:20:00'),
(7, 'Système de Reconnaissance de Visage', 'Algorithme pour la présence automatique en cours.', 'ia', 'zip', 'reconnaissance_visage.zip', 2, '2025-03-30 08:30:00'),
(8, 'Prédiction de Réussite Scolaire', 'Modèle ML pour prédire les risques d’échec et proposer des tutorats.', 'ia', 'pdf', 'prediction_reussite.pdf', 5, '2025-03-27 13:45:00'),
(9, 'Générateur de QCM Automatique', 'Utilise NLP pour créer des quiz à partir des notes de cours.', 'ia', 'zip', 'generateur_qcm.zip', 1, '2025-03-22 10:00:00'),
(10, 'Application de Covoiturage Étudiant', 'App mobile pour partager les trajets vers l’université.', 'mobile', 'pdf', 'covoiturage_etudiant.pdf', 3, '2025-04-03 15:30:00'),
(11, 'Gestionnaire de Budget Étudiant', 'Suivi des dépenses avec graphiques et alertes.', 'mobile', 'zip', 'budget_etudiant.zip', 4, '2025-03-29 12:00:00'),
(12, 'Application de Révisions Flash', 'Système de flashcards avec répétition espacée.', 'mobile', 'pdf', 'flashcards_revision.pdf', 2, '2025-03-26 17:20:00'),
(13, 'Réseau Social Universitaire', 'App pour partager des notes, événements et discuter entre étudiants.', 'mobile', 'zip', 'reseau_social_uni.zip', 5, '2025-03-21 09:15:00'),
(14, 'Refonte UI/UX Plateforme Cours', 'Amélioration de l’expérience utilisateur avec design system complet.', 'design', 'pdf', 'refonte_ui_ux.pdf', 1, '2025-04-01 14:00:00'),
(15, 'Kit UI pour Applications Éducatives', 'Composants réutilisables pour projets étudiants.', 'design', 'zip', 'kit_ui_educatif.zip', 3, '2025-03-28 11:30:00'),
(16, 'Design d’Application Santé Mentale', 'Interface apaisante pour application de bien-être étudiant.', 'design', 'pdf', 'design_sante_mentale.pdf', 4, '2025-03-24 08:45:00'),
(17, 'Analyse des Performances Académiques', 'Dashboard interactif avec Tableau/Python sur les notes.', 'data', 'pdf', 'analyse_performances.pdf', 2, '2025-04-02 09:00:00'),
(18, 'Prédiction des Tendances d’Emploi', 'Analyse des offres de stage pour étudiants.', 'data', 'zip', 'prediction_emploi.zip', 5, '2025-03-31 16:00:00'),
(19, 'Visualisation des Flux Migratoires', 'Carte interactive des étudiants internationaux.', 'data', 'pdf', 'flux_migratoire.pdf', 1, '2025-03-26 13:20:00'),
(20, 'Système de Recommandation de Cours', 'Algorithme pour suggérer des formations pertinentes.', 'data', 'zip', 'recommandation_cours.zip', 3, '2025-03-23 11:00:00'),
(21, 'Chatbot Éducatif IA', 'Assistant virtuel pour répondre aux questions des étudiants sur les cours.', 'ia', 'pdf', 'chatbot_educatif.pdf', 4, '2025-04-02 11:20:00'),
(22, 'Système de Reconnaissance de Visage', 'Algorithme pour la présence automatique en cours.', 'ia', 'zip', 'reconnaissance_visage.zip', 2, '2025-03-30 08:30:00'),
(23, 'Prédiction de Réussite Scolaire', 'Modèle ML pour prédire les risques d’échec et proposer des tutorats.', 'ia', 'pdf', 'prediction_reussite.pdf', 5, '2025-03-27 13:45:00'),
(24, 'Générateur de QCM Automatique', 'Utilise NLP pour créer des quiz à partir des notes de cours.', 'ia', 'zip', 'generateur_qcm.zip', 1, '2025-03-22 10:00:00'),
(25, 'hjh', 'jjjk', 'ia', 'pdf', 'projet_69d82d56eba133.27073866.pdf', 1, '2026-04-09 23:51:02'),
(26, 'vjn', 'jjj', 'ia', 'pdf', 'projet_69d82d95193f23.45654078.pdf', 1, '2026-04-09 23:52:05'),
(27, 'Application de Covoiturage Étudiant', 'App mobile pour partager les trajets vers l’université.', 'mobile', 'pdf', 'covoiturage_etudiant.pdf', 3, '2025-04-03 15:30:00'),
(28, 'Gestionnaire de Budget Étudiant', 'Suivi des dépenses avec graphiques et alertes.', 'mobile', 'zip', 'budget_etudiant.zip', 4, '2025-03-29 12:00:00'),
(29, 'Application de Révisions Flash', 'Système de flashcards avec répétition espacée.', 'mobile', 'pdf', 'flashcards_revision.pdf', 2, '2025-03-26 17:20:00'),
(30, 'Réseau Social Universitaire', 'App pour partager des notes, événements et discuter entre étudiants.', 'mobile', 'zip', 'reseau_social_uni.zip', 5, '2025-03-21 09:15:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `date_inscription`) VALUES
(1, 'Jean Dupont', 'jean.dupont@email.com', 'password123', '2026-04-09 23:47:16'),
(2, 'Marie Curie', 'marie.curie@email.com', 'password123', '2026-04-09 23:47:16'),
(3, 'Amadou Diallo', 'amadou.diallo@email.com', 'password123', '2026-04-09 23:47:16'),
(4, 'Sophie Martin', 'sophie.martin@email.com', 'password123', '2026-04-09 23:47:16'),
(5, 'Thomas Bernard', 'thomas.bernard@email.com', 'password123', '2026-04-09 23:47:16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `idx_categorie` (`categorie`),
  ADD KEY `idx_type_fichier` (`type_fichier`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
