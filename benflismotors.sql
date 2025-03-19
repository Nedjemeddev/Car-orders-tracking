-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 09 déc. 2024 à 08:32
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
-- Base de données : `benflismotors`
--

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `vin` varchar(50) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `vehicle_brand` varchar(50) NOT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `observation` text DEFAULT NULL,
  `last_step` varchar(50) DEFAULT NULL,
  `last_step_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `client_name`, `vin`, `vehicle_type`, `vehicle_brand`, `vehicle_model`, `observation`, `last_step`, `last_step_date`) VALUES
(1, 'John Doe', '1HGCM82633A123456', 'SUV', 'FIAT', 'TIPO', NULL, NULL, NULL),
(6, 'Messaoudi Nedjem Eddine', '/', '', 'FIAT', 'TIPO', 'aile m3awd', 'ACCUSE', '2024-12-28'),
(111, 'DRIDI SALAH', 'ZFACF8AP2PU066100', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-01-28'),
(112, 'BOUKRI CHOUAIB', 'ZFA25000002Y79982', '', 'FIAT', 'DUCATO', '', 'DOSSIER_DAIRA', '2024-08-24'),
(113, 'BENANTAR ABDELGHANI', 'ZFACF8AP2PU102061', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-02-24'),
(114, 'SARL BACHA MOBILIER', 'VXFVFAHXGPZ088964', '', 'FIAT', 'SCUDO', '', 'LIVRAISON', '2024-04-14'),
(115, 'TAHTOUH AISSA', 'ZFACF8AP9PU093360', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-02-01'),
(116, 'ROUABAH ASSIA', 'ZFANF2BH2RPA31431', '', 'FIAT', '500X', '', 'LIVRAISON', '2024-02-07'),
(117, 'HADDADI RABAH', 'VXFVFAHXGPZ094829', '', 'FIAT', 'SCUDO', '', 'LIVRAISON', '2024-02-27'),
(118, 'GARAH BILAL', 'VYFEF9HPARJ544768', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-03-10'),
(119, 'BARA ABDENNOUR', 'VYFEF9HPARN517081', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-03-27'),
(120, 'HALIMI ZAHIA', 'ZFANF2BH9RPA31507', '', 'FIAT', '500X', '', 'LIVRAISON', '2024-01-25'),
(121, 'GOUDJIL ABDESSELEM', 'ZFACF8APXPU107699', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-02-24'),
(122, 'SEFFAH DJAMEL', 'ZFACF8AP8PU115168', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-07-07'),
(123, 'MEZIANI FARIDA', 'ZFACF8AP2PU092390', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-02-12'),
(124, 'MOHAD NOUAR', 'ZFANF2BH4RPA31415', '', 'FIAT', '500X', '', 'LIVRAISON', '2024-02-18'),
(125, 'BEZZALA ABDELAZIZ', 'ZFACF8AP3PU113554', '', 'FIAT', 'TIPO', '', 'CARTE_JAUNE', '2024-02-26'),
(126, 'BELLAL RABAH', 'ZFACF8APXPU113468', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-03-04'),
(127, 'ABDELAZIZ DALILA', 'VYFEF9HPARJ554552', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-03-09'),
(128, 'BOUGUERNIZ ALI', 'ZFACF8AP0PU112572', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-03-16'),
(129, 'SAADI SAID', 'ZFACF8AP0PU113477', '', 'FIAT', 'TIPO', '', 'CARTE_JAUNE', '2024-02-21'),
(130, 'BERKOUCHE YOUCEF', 'VXFVFAHXGPZ091936', '', 'FIAT', 'SCUDO', '', 'LIVRAISON', '2024-03-11'),
(131, 'BOUDJELAL WALID', 'VYFEF9HPARN509633', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-03-11'),
(132, 'KHABAT IMANE', 'ZFANF2BH9RPA35153', '', 'FIAT', '500X', '', 'DOSSIER_DAIRA', '2024-08-26'),
(133, 'DRIBINE ADEL ', 'VYFEF9HPARN521137', '', 'FIAT', 'DOBLO', '', 'CARTE_JAUNE', '2024-04-09'),
(134, 'MECHAKRA YOUNES', 'ZFACF8AP3PU111657', '', 'FIAT', 'TIPO', '', 'CARTE_JAUNE', '2024-02-20'),
(135, 'MERZOUK HIBA', 'ZFANF2BH3RPA33916', '', 'FIAT', '500X', '', 'LIVRAISON', '2024-02-15'),
(136, 'ARRAR LYAZID', 'VYFEF9HPARN508746', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-02-29'),
(137, 'CHABBI MOHAMED', 'VXFVFAHXGPZ094919', '', 'FIAT', 'SCUDO', '', 'LIVRAISON', '2024-02-28'),
(138, 'MEDDOURI LAZHAR ', 'ZFACF8AP9PU110707', '', 'FIAT', 'TIPO', '', 'LIVRAISON', '2024-07-07'),
(139, 'OUNIS FAROUK', 'VYFEF9HPARJ548276', '', 'FIAT', 'DOBLO', '', 'LIVRAISON', '2024-03-03');

-- --------------------------------------------------------

--
-- Structure de la table `order_steps`
--

CREATE TABLE `order_steps` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `step_name` varchar(50) NOT NULL,
  `step_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_steps`
--

INSERT INTO `order_steps` (`id`, `order_id`, `step_name`, `step_date`) VALUES
(1, 1, 'PROFORMA', '2024-12-01'),
(2, 1, 'COMMANDE', '2024-12-02'),
(8, 6, 'COMMANDE', '2024-12-09'),
(9, 6, 'PROFORMA', '2024-12-02'),
(10, 6, 'COMMANDE', '2024-12-09'),
(11, 6, 'PROFORMA', '2024-12-02'),
(12, 6, 'COMMANDE', '2024-12-09'),
(13, 6, 'PROFORMA', '2024-12-02'),
(14, 6, 'COMMANDE', '2024-12-09'),
(29, 6, 'PROFORMA', '2024-12-02'),
(30, 6, 'COMMANDE', '2024-12-09'),
(31, 6, 'VALIDATION', '2024-12-10'),
(32, 6, 'PROFORMA', '2024-12-02'),
(33, 6, 'COMMANDE', '2024-12-09'),
(34, 6, 'VALIDATION', '2024-12-10'),
(35, 6, 'ACCUSE', '2024-12-28'),
(448, 111, 'COMMANDE', '2023-10-21'),
(449, 111, 'ACCUSE', '2024-01-07'),
(450, 111, 'FACTURATION', '2024-01-10'),
(451, 111, 'ARRIVAGE', '2024-01-20'),
(452, 111, 'CARTE_JAUNE', '2024-01-20'),
(453, 111, 'LIVRAISON', '2024-01-28'),
(454, 112, 'COMMANDE', '2024-01-04'),
(455, 112, 'ACCUSE', '2023-10-16'),
(456, 112, 'FACTURATION', '2024-01-23'),
(457, 112, 'ARRIVAGE', '2024-02-11'),
(458, 112, 'CARTE_JAUNE', '2024-02-13'),
(459, 112, 'LIVRAISON', '2024-02-13'),
(460, 112, 'DOSSIER_DAIRA', '2024-08-24'),
(461, 113, 'COMMANDE', '2023-11-13'),
(462, 113, 'ACCUSE', '2023-12-18'),
(463, 113, 'FACTURATION', '2024-01-31'),
(464, 113, 'ARRIVAGE', '2024-02-23'),
(465, 113, 'CARTE_JAUNE', '2024-02-20'),
(466, 113, 'LIVRAISON', '2024-02-24'),
(467, 114, 'COMMANDE', '2024-01-03'),
(468, 114, 'ACCUSE', '2024-01-07'),
(469, 114, 'FACTURATION', '2024-01-14'),
(470, 114, 'ARRIVAGE', '2024-02-17'),
(471, 114, 'CARTE_JAUNE', '2024-01-20'),
(472, 114, 'LIVRAISON', '2024-04-14'),
(473, 114, 'DOSSIER_DAIRA', '2024-02-08'),
(474, 115, 'COMMANDE', '2024-01-03'),
(475, 115, 'FACTURATION', '2024-01-24'),
(476, 115, 'ARRIVAGE', '2024-01-28'),
(477, 115, 'CARTE_JAUNE', '2024-01-31'),
(478, 115, 'LIVRAISON', '2024-02-01'),
(479, 116, 'COMMANDE', '2024-01-04'),
(480, 116, 'ACCUSE', '2024-01-14'),
(481, 116, 'FACTURATION', '2024-01-19'),
(482, 116, 'ARRIVAGE', '2024-01-29'),
(483, 116, 'CARTE_JAUNE', '2024-01-24'),
(484, 116, 'LIVRAISON', '2024-02-07'),
(485, 117, 'COMMANDE', '2023-09-18'),
(486, 117, 'FACTURATION', '2024-01-31'),
(487, 117, 'ARRIVAGE', '2024-02-26'),
(488, 117, 'CARTE_JAUNE', '2024-02-20'),
(489, 117, 'LIVRAISON', '2024-02-27'),
(490, 118, 'COMMANDE', '2023-12-07'),
(491, 118, 'FACTURATION', '2024-01-30'),
(492, 118, 'ARRIVAGE', '2024-03-04'),
(493, 118, 'CARTE_JAUNE', '2024-02-26'),
(494, 118, 'LIVRAISON', '2024-03-10'),
(495, 119, 'COMMANDE', '2024-01-04'),
(496, 119, 'ACCUSE', '2024-01-08'),
(497, 119, 'FACTURATION', '2024-02-27'),
(498, 119, 'ARRIVAGE', '2024-03-10'),
(499, 119, 'CARTE_JAUNE', '2024-03-25'),
(500, 119, 'LIVRAISON', '2024-03-27'),
(501, 120, 'COMMANDE', '2024-01-04'),
(502, 120, 'ACCUSE', '2024-01-08'),
(503, 120, 'FACTURATION', '2024-01-15'),
(504, 120, 'ARRIVAGE', '2024-01-17'),
(505, 120, 'CARTE_JAUNE', '2024-01-24'),
(506, 120, 'LIVRAISON', '2024-01-25'),
(507, 121, 'COMMANDE', '2024-01-03'),
(508, 121, 'FACTURATION', '2024-01-23'),
(509, 121, 'ARRIVAGE', '2024-02-16'),
(510, 121, 'LIVRAISON', '2024-02-24'),
(511, 122, 'COMMANDE', '2024-01-03'),
(512, 122, 'ACCUSE', '2024-01-08'),
(513, 122, 'FACTURATION', '2024-06-28'),
(514, 122, 'ARRIVAGE', '2024-07-01'),
(515, 122, 'CARTE_JAUNE', '2024-07-06'),
(516, 122, 'LIVRAISON', '2024-07-07'),
(517, 123, 'COMMANDE', '2024-01-03'),
(518, 123, 'ACCUSE', '2024-01-08'),
(519, 123, 'FACTURATION', '2024-01-19'),
(520, 123, 'ARRIVAGE', '2024-02-10'),
(521, 123, 'CARTE_JAUNE', '2024-01-31'),
(522, 123, 'LIVRAISON', '2024-02-12'),
(523, 124, 'COMMANDE', '2024-01-04'),
(524, 124, 'FACTURATION', '2024-01-17'),
(525, 124, 'ARRIVAGE', '2024-01-19'),
(526, 124, 'CARTE_JAUNE', '2024-02-17'),
(527, 124, 'LIVRAISON', '2024-02-18'),
(528, 125, 'COMMANDE', '2024-01-06'),
(529, 125, 'ACCUSE', '2024-01-08'),
(530, 125, 'FACTURATION', '2024-01-23'),
(531, 125, 'ARRIVAGE', '2024-02-07'),
(532, 125, 'CARTE_JAUNE', '2024-02-26'),
(533, 125, 'LIVRAISON', '2024-02-26'),
(534, 126, 'COMMANDE', '2024-01-04'),
(535, 126, 'ACCUSE', '2024-01-08'),
(536, 126, 'FACTURATION', '2024-01-23'),
(537, 126, 'ARRIVAGE', '2024-02-10'),
(538, 126, 'CARTE_JAUNE', '2024-02-21'),
(539, 126, 'LIVRAISON', '2024-03-04'),
(540, 127, 'COMMANDE', '2024-01-04'),
(541, 127, 'ACCUSE', '2024-01-08'),
(542, 127, 'FACTURATION', '2024-01-30'),
(543, 127, 'ARRIVAGE', '2024-03-06'),
(544, 127, 'CARTE_JAUNE', '2024-02-24'),
(545, 127, 'LIVRAISON', '2024-03-09'),
(546, 128, 'COMMANDE', '2024-01-03'),
(547, 128, 'ACCUSE', '2024-01-08'),
(548, 128, 'FACTURATION', '2024-01-23'),
(549, 128, 'ARRIVAGE', '2024-03-09'),
(550, 128, 'CARTE_JAUNE', '2024-02-20'),
(551, 128, 'LIVRAISON', '2024-03-16'),
(552, 129, 'COMMANDE', '2024-01-03'),
(553, 129, 'FACTURATION', '2024-01-23'),
(554, 129, 'ARRIVAGE', '2024-02-10'),
(555, 129, 'CARTE_JAUNE', '2024-02-21'),
(556, 129, 'LIVRAISON', '2024-02-21'),
(557, 130, 'COMMANDE', '2024-01-04'),
(558, 130, 'ACCUSE', '2024-01-08'),
(559, 130, 'FACTURATION', '2024-01-30'),
(560, 130, 'ARRIVAGE', '2024-02-22'),
(561, 130, 'CARTE_JAUNE', '2024-03-02'),
(562, 130, 'LIVRAISON', '2024-03-11'),
(563, 131, 'COMMANDE', '2024-01-04'),
(564, 131, 'ACCUSE', '2024-01-08'),
(565, 131, 'FACTURATION', '2024-01-30'),
(566, 131, 'ARRIVAGE', '2024-03-06'),
(567, 131, 'CARTE_JAUNE', '2024-02-26'),
(568, 131, 'LIVRAISON', '2024-03-11'),
(569, 132, 'COMMANDE', '2024-01-04'),
(570, 132, 'ACCUSE', '2024-01-08'),
(571, 132, 'FACTURATION', '2024-01-23'),
(572, 132, 'ARRIVAGE', '2024-02-04'),
(573, 132, 'CARTE_JAUNE', '2024-02-13'),
(574, 132, 'LIVRAISON', '2024-02-14'),
(575, 132, 'DOSSIER_DAIRA', '2024-08-26'),
(576, 133, 'COMMANDE', '2024-01-04'),
(577, 133, 'ACCUSE', '2024-01-08'),
(578, 133, 'FACTURATION', '2024-03-25'),
(579, 133, 'ARRIVAGE', '2024-04-02'),
(580, 133, 'CARTE_JAUNE', '2024-04-09'),
(581, 133, 'LIVRAISON', '2024-04-09'),
(582, 134, 'COMMANDE', '2024-01-04'),
(583, 134, 'ACCUSE', '2024-01-08'),
(584, 134, 'FACTURATION', '2024-01-30'),
(585, 134, 'ARRIVAGE', '2024-02-08'),
(586, 134, 'CARTE_JAUNE', '2024-02-20'),
(587, 134, 'LIVRAISON', '2024-02-20'),
(588, 135, 'COMMANDE', '2024-01-04'),
(589, 135, 'ACCUSE', '2024-01-08'),
(590, 135, 'FACTURATION', '2024-01-24'),
(591, 135, 'ARRIVAGE', '2024-01-30'),
(592, 135, 'CARTE_JAUNE', '2024-02-13'),
(593, 135, 'LIVRAISON', '2024-02-15'),
(594, 136, 'COMMANDE', '2024-01-06'),
(595, 136, 'ACCUSE', '2024-01-08'),
(596, 136, 'FACTURATION', '2024-01-25'),
(597, 136, 'ARRIVAGE', '2024-02-13'),
(598, 136, 'CARTE_JAUNE', '2024-02-26'),
(599, 136, 'LIVRAISON', '2024-02-29'),
(600, 137, 'COMMANDE', '2024-01-11'),
(601, 137, 'FACTURATION', '2024-01-22'),
(602, 137, 'ARRIVAGE', '2024-02-08'),
(603, 137, 'CARTE_JAUNE', '2024-02-26'),
(604, 137, 'LIVRAISON', '2024-02-28'),
(605, 138, 'COMMANDE', '2023-12-28'),
(606, 138, 'FACTURATION', '2024-06-28'),
(607, 138, 'ARRIVAGE', '2024-07-01'),
(608, 138, 'CARTE_JAUNE', '2024-07-06'),
(609, 138, 'LIVRAISON', '2024-07-07'),
(610, 139, 'COMMANDE', '2024-01-04'),
(611, 139, 'ACCUSE', '2024-01-08'),
(612, 139, 'FACTURATION', '2024-01-25'),
(613, 139, 'ARRIVAGE', '2024-02-12'),
(614, 139, 'CARTE_JAUNE', '2024-02-26'),
(615, 139, 'LIVRAISON', '2024-03-03');

-- --------------------------------------------------------

--
-- Structure de la table `step_files`
--

CREATE TABLE `step_files` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `step_name` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `step_files`
--

INSERT INTO `step_files` (`id`, `order_id`, `step_name`, `file_path`) VALUES
(1, 6, 'COMMANDE', 'uploads/order_6/COMMANDE_6.png'),
(2, 6, 'DOSSIER DAIRA', 'uploads/order_6/DOSSIER DAIRA_6.png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','commercial','adv','livraison') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'joujouB2008', 'admin'),
(2, 'commercial_user', 'commercial_password', 'commercial'),
(3, 'adv_user', 'adv_password', 'adv'),
(4, 'livraison_user', 'livraison_password', 'livraison');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_steps`
--
ALTER TABLE `order_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Index pour la table `step_files`
--
ALTER TABLE `step_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT pour la table `order_steps`
--
ALTER TABLE `order_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=616;

--
-- AUTO_INCREMENT pour la table `step_files`
--
ALTER TABLE `step_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `order_steps`
--
ALTER TABLE `order_steps`
  ADD CONSTRAINT `order_steps_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `step_files`
--
ALTER TABLE `step_files`
  ADD CONSTRAINT `step_files_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
