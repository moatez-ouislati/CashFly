-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 02, 2026 at 09:23 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cashflydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_room` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('text','image','file','system') COLLATE utf8mb4_general_ci DEFAULT 'text',
  `reply_to` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_edited` tinyint(1) DEFAULT '0',
  `status` enum('active','deleted') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `edited_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `reply_to` (`reply_to`),
  KEY `idx_message_room` (`id_room`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_presence`
--

DROP TABLE IF EXISTS `chat_presence`;
CREATE TABLE IF NOT EXISTS `chat_presence` (
  `id_presence` int NOT NULL AUTO_INCREMENT,
  `id_room` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_online` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_presence`),
  UNIQUE KEY `unique_presence` (`id_room`,`id_utilisateur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id_document` int NOT NULL AUTO_INCREMENT,
  `id_entreprise` int NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `nom_document` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type_document` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chemin_fichier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `texte_ocr` longtext COLLATE utf8mb4_general_ci,
  `date_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_document`),
  KEY `fk_documents_entreprise` (`id_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id_document`, `id_entreprise`, `id_utilisateur`, `nom_document`, `type_document`, `statut`, `chemin_fichier`, `description`, `texte_ocr`, `date_upload`) VALUES
(5, 18, 7, 'DOCUMENT', 'pitch_deck', 'valide', 'C:\\Users\\ergue\\Desktop\\pdf-sample_0.pdf', 'DDD', 'Dummy PDF file\n', '2026-03-03 10:22:26'),
(6, 18, 7, 'EDE', 'pitch_deck', 'valide', 'C:\\Users\\ergue\\Desktop\\Examen-ASSEU_2324_S1_Correction (2).pdf', 'DD', 'esprit”\nAVIS IMPORTANT AUX ETUDIANTS ee ne omer autromnent,\nHONORIS UNITED UNIVERSITIES\n1. Chacune des feuilles de votre copie doit comporter une étiquette code a barres placée a\nl\'endroit indiqué «coller ici votre code a barres».\n2. Une copie d’examen comporte une seule «feuille principale» et des «feuilles suites». Sur\nchacune de vos feuilles, le code a barres est obligatoire.\n3. Cette feuille d’examen est strictement personnelle. Elle ne doit comporter aucun signe distinctif. N OT E\nElle doit étre écrite en noir et/ou bleu.\n4. Lenonrespectdel’unedecesrecommandations peutfaire attribuerlanote ZERO al’épreuve.\ncode a barre\n[00 01] 02] 03] o4 | 05] 06 | o7 | 08 | 09 10) 14/12) 19] +4] 15] 16] 17| 18] 19) 20| | 00 | 25] 50] 75)\nModule : Administration et sécurité des SE UNIX Documents autorisés: OUI[L_] NON\nEnseignant(s) : UP Systeme Calculatrice autorisée : OUI[_] NONE\nClasse(s) : 3A2-->3A45, 31A Internet autorisée : OUIL] NONE\nSession: Principale Nombre de page : 7\nDate : 17/01/2024 Heure: 11h00 Durée : 1h30\nExercice 1 : (3 pts)\n1. Expliquer le role d’un sticky bit. (pt)\nLorsqu’un sticky bit est appliqué a un répertoire, un fichier dedans peut étre visualisé et modifié, mais seul son\npropriétaire (et le root) peuvent le supprimer. Le sticky bit permet donc d’affecter une protection contre l’ effacement\ndu contenu d’un répertoire.\n2. Soit la commande suivante :\n$useradd -m -u 2000 -s \"/bin/sh\" -c \"Engineers\" -G \"Marketing\" Mohamed\na. Quel sera le groupe primaire de Mohamed ? (0.5pt)\nbie eeceeeeeeeeceesseessssessesssessseess groupe par défaut « Mohamed »............ccecceeeeeeeeeeeeeees\nb. Quel sera le répertoire personnel de Mohamed ? (0.5pt)\nPIII 00) 001270 \\/ (0) 04000 (16 Da\n3. On suppose que sur un disque GPT, les LBA 1-33 sont endommagés. Est-ce que les partitions\nexistantes sur ce disque seront toujours accessibles ? si oui comment ? (1pt)\nOui elles restent toujours accessibles et ceci a travers Secondary partition table\nNom, prénom et signature\nde l’enseignant correcteur\n666F\nNe rien écrire ici\nExercice 2 : (7 pts)\n\nAu sein d’une entreprise, un administrateur systeme se préoccupe de la création et la gestion de\nnouveaux comptes utilisateurs pour les nouveaux employés. Cette gestion implique I’ajout de nouvelles\nlignes dans plusieurs fichiers de configuration. Ci-dessous quelques exemples de ces lignes.\n\n(1) Managers : x : 1004: userl, user2 D .... 0. ccc e ccc cece eee eens\n\n(2) userl :x: 1001: 1001:: /home/user! : /bin/bash ® ............ 0... c cece cece eee ee ees\n\n(3) userl : $1$.AbCdEfGh123456789A1b2C3d4. : 18015 : 20: 90:5: 30::\n\n(4) Engineers : ! : user2 : user3, user4 Do... 0. eee cece cece eee es\n\n(5) user2 : !$6$123456789aBcDeFgHal1 B2c3d4ESf6g7H8I9 : 18015:0:20:5:::\n\nFigure 1\n1. Identifier le fichier de configuration correspondant a chacune des lignes indiquées dans la figure | :\n(Ipt)\ne107 4 0) bi 0k (0020) 0) 9\n\nNom, prénom et signature\nde l’enseignant correcteur\n(2) /etc/passwd (O.25pt).......cc ccc cece cece eee eee nen EEE LEED EEE EEE EEE EERE EEE; EEE EEE EEE EEE EES\n(3) & (5) /etc/shadow (0.25 pt).... 0... cece cece cece nee eee e nen e EEE enn ene teen eae ed\n(4) /etc/gshadow (0.25 pt)........ccce cece cece cence een eee cece ee eee e nee e ee ene eee e ee eee eae\n2. a. Quel utilisateur pami les utilisateurs cités dans la figure 1 ne pourra pas se connecter a son compte ?\nExpliquer. (1 pt)\nDans la ligne (5) il existe un point d’exclamation devant le hash de mot de passe dans le fichier /etc/shadow, donc\nPutilisateur user2 ne peut pas se connecter 4 son compte car son mdp est verrouillé....... 0... cece cece cece eee eee eens\nb. Donner deux commandes différentes qui peuvent étre utilisées pour lever cette restriction et\npermettre a cet utilisateur de se connecter. (0.5pt)\nusermod -U user2\npasswd -u user2\nc. Quelle est la durée de validité de son mot de passe ? (0.5pt)\n20 JOULS. 2... cece ccc eee eee eee nee een nnn EERE EEE EEE EEE EEE EEE EEE; EE EEE EEE EEE EEE EEE; EE; E Ee EEE; EE eR Geta EERE EEE EEE SEES\nd. Donner la commande qui permet d’étendre cette durée de 10 jours. (0.5pt)\nSoit on modifie le champ 5 du /etc/shadow et le rendre a 30 (20+10)\nsudo passwd -x 30 user2\nSoit on modifie le champ 7 du /etc/shadow et le rendre a 10\nSUdO passwd —1 10 USCT2..... cece ccc eee eee nee eee ene een Eee Een EEE E Ee EEE EEE SEES\n3. Donner la ligne 4 (de la figure 1) mise a jour suite a l’exécution de la commande suivante :\n« gpasswd -M userl,user2 Engineers». (1pt)\nCette commande modifie les membres du groupe \"Engineers\" en remplacant la liste existante (user3 et user4) par la\nnouvelle liste spécifiée dans la commande (userl et user2). Ainsi, la ligne devient : Engineers : ! : userl,\nuSeI2..... 2... eee eee\n4. La figure ci-dessous montre la sortie de la commande « id » exécutée par l\'utilisateur \"user2\".\nuser2@esprit-virtual-machine:~$ id\nuid=1002(user2) gid=1002(user2) groups=1002(user2) ,1004(Managers) ,1005(Engineers)\nuser2@esprit-virtual-machine:~$\nFigure 2\nEtant donné un groupe nommeé « Admins » sécurisé par un mot de passe, donner la commande qui permet\na user2 de changer son groupe primaire temporairement en « Admins ». (0.5pt)\nNEWEIP ACMINS......... cece cece eee e eee eee eee eeeeeeneeeeeeeeeeeeeeeeee\n5. Afin d’organiser la gestion des fichiers, l\'administrateur a créé des répertoires partagés pour chaque\ngroupe d\'employés au sein de l\'entreprise. La figure suivante montre les permissions du répertoire\n\"Rep-Engineers\" spécifiquement dédié aux ingénieurs.\nroot@esprit-virtual-machine:~# ls -ld /Rep-Engtneers\ndrwxr-xr-x 2 root Engineers 4096 Jan 15 03:29 /Rep-Engineers\nFigure 3\na. Comme étant ingénieur, user2 a créé un fichier projet! sous le répertoire /Rep-Engineers, quel\nsera le groupe propriétaire de ce fichier ? (0.5pt)\nNom, prénom et signature\nde l’enseignant correcteur\nLe groupe proprictaire de ce fichier sera le méme que le groupe primaire de user2 donc user2\n\nb. Donner la commande nécessaire pour assurer que tous les fichiers créés sous le répertoire /Rep-\n\nEngineers auront comme groupe proprictaire le groupe Engineers. (0.75pt)\nPlacer un SGID sur ce répertoire\nchmod g+s /Rep-Engineers ..................\n\nc. Ci-dessous le résultat de l’exécution de la commande « Is -I /usr/bin/sudo ». Quel bit spécial est\ndéfini et quelle est sa signification ? (0.75pt)\nuser2@esprit-virtual-machine:~/DesktopS 1s -1l /usr/bin/sudo\n-rwsr-xr-x 1 root root 232416 2023 16  .ail> /usr/bin/sudo\n\nFigure 4\nC’est le bit SUID.\nLorsque ce bit est placé sur un fichier exécutable, et lors de l’exécution de ce fichier, ce fichier sera exécuté\navec les droits de SON Proprictaire...... 6... cece ccc e cece eee eee eee e eee e eee e tenet eee eee enna\nExercice 3 : (10 pts)\n\nL’administrateur systéme est responsable aussi de la gestion de son espace de stockage en utilisant le\n\ngestionnaire de volumes logiques (LVM) sur un serveur Linux.\n\nPartie 1 : (5 pt)\n\nPour lui faciliter la gestion, vous étes amené a créer un script lvm_setup.sh pour automatiser certaines\n\ntaches. Ci-dessous un exemple d’exécution de ce script :\n\n$ lvm_setup.sh /dev/sda /dev/sdb\nCompleéter le script suivant.\nNom, prénom et signature\nde l’enseignant correcteur\nColler ici votre\ncode a barre\n#!/bin/bash\n# Les disques a utiliser pour créer les volumes physiques seront stockés dans un tableau \"disks\"\n# Boucle for pour créer ce tableau\nfor ((i=1; i<=$#; i++)) ;\ndo\ndiskst=$i\ndone\n# Création des volumes physiques (0,5 pt)\npvcreate \"S{disks[@]}\"\nread -p \"Entrez le nom du groupe de volumes (VG) :\" vg name\n# Création du groupe de volumes (0,5 pt)\nvgecreate Svg_name \"S{disks[@]}\"\nread -p \"Entrez le nom et la taille du premier volume logique (LV) :\"Ilv1_name lIv1_size\nread -p \"Entrez le nom et la taille du deuxicéme volume logique (LV) :\" lv2_name lIv2_size\n# Création de deux volumes logiques (0,75 pt)\nIvcreate -n Slv1_name -L Slv1_size Svg_name\nIvcreate -n Slv2_name -L Slv2_size Svg_name\nread -p \"Entrez le type de systcme de fichiers respectivement pour $lv1_name et $lv2_name : \" fs1\nfs2\n# Formatage des volumes logiques avec les systemes de fichiers spécifiés (0,75 pt)\nmkfs.Sfs1 /dev/Svg_name/Slv1_name\nmkfs.Sfs2 /dev/Svg_name/Slv2_name\nread -p \"Entrez les points de montage pour $lv1_name et $lv2_name:\" mount_dir1 mount_dir2\n# Création des points de montage (0,25 pt)\nmkdir -p \"Smount_dir1\" \"Smount_dir2\"\n# Montage automatique des volumes logiques (0,5 pt)\n# Montage avec les options par défaut des 2 volumes logiques (0,25 pt)\n#Activation des quotas pour les utilisateurs sur le volume logique $lv1_name (0,25 pt)\necho \"/dev/Svg_name/Slv1_name Smount_dir1 Sfs1 defaults, usrquota,grpquota 0 0\" >> /etc/fstab\necho \"/dev/Svg_name/Slv2_name Smount_dir2 Sfs2 default 0 0\" >> /etc/fstab\n# Remonter tous les systcmes de fichiers (0,25 pt)\nmount -a\n# Configuration des quotas\nread -p \"Entrez le nom d‘utilisateur pour définir les quotas : \" username\n# Verifier et mettre 4 jour la configuration des quotas (0,25 pt)\nquotacheck -avug\n# Activer les quotas disque (0,25 pt)\n\nquotaon /dev/Svg_name/Slv_name1\n\n# Editer les quotas pour l\'utilisateur spécifié (0,5 pt)\nedquota -u Susername\nTILT \" -\nPartie 2 : (5 pt)\nDans cette partie, vous serez chargé de mettre en place un service systemd appelé\nquotachecker.service. Ce service effectuera une vérification, 4 chaque démarrage de la machine, des\ninodes disponibles pour l\'utilisateur « Testuser » dans la partition ot! les quotas ont été configurés.\nLe service doit se conformer aux exigences suivantes :\ne L\'unité « local-fs.target » est activée avant le démarrage du service.\ne L\'unité « proc-fs-nfsd.mount » doit obligatoirement démarrer avant le service.\ne Avant le lancement du service, l\'unité « rescue.target »» doit étre désactivée.\ne L\'unité « multi-user.target » lance automatiquement ce service.\ne Ce service lance le script « quota.sh ».\n1. Ecrire le fichier de l’unité « quotachecker.service »» conformément aux instructions mentionnées ci-\ndessus. (2.5pt) 0.5pt/instruction (After, Requires, Conflicts, ExecStart, WantedBy)\n1 [Unit]\n2 Description=Quota checker\n3 After=local-fs.target proc-fs-nfsd.mount\n4 Requires=proc-fs-nfsd.mount\n5 Conflicts=rescue.target\n7 [Service]\n8 Type=simple\n9 ExecStart=/sbin/quota.sh\na [Install]\nl2 WantedBy=multi-user.target\n2. Le script « quota.sh » a créer doit effectuer les actions suivantes :\ne Identifier les points de montage ot: les quotas sont configurés.\ne Enregistrer dans un fichier nommeé « checkinode.txt » le nombre d\'inodes disponibles pour\nl\'utilisateur « Testuser ».\nEcrire le script « quota.sh »» qui sera lancé par le service. (2pt)\n1#!/bin/bash\n- Récupére le point de montage ol! les quotas sont configurés\n5 partitions$(grep “usrquota\" /etc/fstab | cut -d \" \" -f 2)\n7 inodes totals=- repquota -u $partition | grep Testuser | tr -s \" \" | cut -d\" \" -f 8\n8 inodes_utilises= repquota -u $partition | grep Testuser | tr -s “ \" | cut -d\" \" -f 6\n9inodes_disponibles=$(expr $inodes totals - $inodes_utilises)\n10 cho -e \" IL reste $inodes_ disponibles inodes disponibles sur la partition $partition.\\n\" >> checkinode.txt\n0.5pt/ligneS, ligne7, ligne8\n0.25pt/ligne 9, ligne11\nNB: Dans ce script, on va accepter les réponses sans la commande « tr » pour les lignes 7\net 8\n3. Donner les deux commandes a exécuter pour activer le service quotachecker au _ prochain\ndémarrage. (0.5pt)\nSudo systemctl daemon-relaod\nSudo sysetmctl enable/usr/lib/systemd/system/quotachecker.service...............\nAnnexe :\nroot@HostAdmin:~# repquota -u /data\n*** Report for user quotas on device /dev/sdb\nBlock grace time: 7days; Inode grace time: 7days\nBlock limits File limits\nUser used soft hard grace used soft hard grace\nroot -- 20 0 0 2 0 ic}\nuser -+ 20 (0) (0) 7 5 10 6days\nTestuser -+ 20 (0) (0) 12 10 30 6days\nroot@HostAdmin:~# cat checkinode.txt\nIl reste 20 inodes disponibles sur la partition /data.\nBon courage\n9\n', '2026-03-03 10:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `entreprises`
--

DROP TABLE IF EXISTS `entreprises`;
CREATE TABLE IF NOT EXISTS `entreprises` (
  `id_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secteur` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forme_juridique` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `capital` decimal(15,2) DEFAULT '0.00',
  `id_proprietaire` int NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_entreprise`),
  KEY `id_proprietaire` (`id_proprietaire`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entreprises`
--

INSERT INTO `entreprises` (`id_entreprise`, `nom`, `secteur`, `forme_juridique`, `date_creation`, `capital`, `id_proprietaire`, `latitude`, `longitude`, `adresse`) VALUES
(17, 'Cashfly', 'Fintech', 'SA', '2026-03-10', 10000.00, 14, 36.8334618, 10.1708613, '12 Rue de l’Espoir, Mutuelleville, Tunis, Tunisia'),
(18, 'SOUHIR CORP', 'nothing', 'SA', '2026-03-16', 5000.00, 7, 33.8439408, 9.400138, 'Avenue de la Ligue des États Arabes1002 Tunis, Tunisia');

-- --------------------------------------------------------

--
-- Table structure for table `investissement`
--

DROP TABLE IF EXISTS `investissement`;
CREATE TABLE IF NOT EXISTS `investissement` (
  `id_investissement` int NOT NULL AUTO_INCREMENT,
  `id_investisseur` int NOT NULL,
  `id_entreprise` int NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `date_investissement` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('EN_ATTENTE','ACTIF','TERMINE','ANNULE') COLLATE utf8mb4_general_ci DEFAULT 'EN_ATTENTE',
  `taux_rendement_prevu` decimal(5,2) DEFAULT NULL COMMENT 'Expected ROI %',
  `duree_mois` int DEFAULT NULL COMMENT 'Duration in months',
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_investissement`),
  KEY `fk_inv_user` (`id_investisseur`),
  KEY `fk_inv_entreprise` (`id_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journees_portes_ouvertes`
--

DROP TABLE IF EXISTS `journees_portes_ouvertes`;
CREATE TABLE IF NOT EXISTS `journees_portes_ouvertes` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_evenement` datetime NOT NULL,
  `lieu` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journées_portes_ouvertes`
--

DROP TABLE IF EXISTS `journées_portes_ouvertes`;
CREATE TABLE IF NOT EXISTS `journées_portes_ouvertes` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `date_evenement` date NOT NULL,
  `lieu` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `max_participants` int NOT NULL DEFAULT '50',
  `current_participants` int NOT NULL DEFAULT '0',
  `id_createur` int DEFAULT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journées_portes_ouvertes`
--

INSERT INTO `journées_portes_ouvertes` (`id_evenement`, `titre`, `date_evenement`, `lieu`, `description`, `image_path`, `max_participants`, `current_participants`, `id_createur`) VALUES
(1, 'Journée Portes Ouvertes - Startup Tech', '2026-03-07', 'Technopole El Ghazela, Ariana, Tunisie', 'Découvrez un événement exclusif dédié à l’investissement dans les startups technologiques tunisiennes. Cette journée unique vous offre l’opportunité de rencontrer des entrepreneurs innovants, d’assister à des pitchs d’investissement dynamiques et d’échanger avec des investisseurs institutionnels dans un cadre propice au networking et aux partenariats stratégiques.', '/cashfly/images/events/1772320156629_bg_f8f8f8-flat_750x_075_f-pad_750x1000_f8f8f8__1_.jpg', 50, 2, 9),
(2, 'Forum Investissement & Innovation', '2026-03-11', 'Hôtel Laico Tunis', 'Une journée dédiée aux entrepreneurs et investisseurs souhaitant collaborer sur des projets à fort potentiel.\nInterventions d’experts financiers et témoignages de fondateurs de startups locales.', '/cashfly/images/events/1772370497578_logo_assad.jpg', 50, 0, 9),
(3, 'FFF', '2026-03-12', 'TECH', 'ZZZZ', NULL, 50, 0, 7);

-- --------------------------------------------------------

--
-- Table structure for table `operation_notes`
--

DROP TABLE IF EXISTS `operation_notes`;
CREATE TABLE IF NOT EXISTS `operation_notes` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `id_operation` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_note`),
  KEY `id_operation` (`id_operation`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `operation_notes`
--

INSERT INTO `operation_notes` (`id_note`, `id_operation`, `content`, `created_at`, `updated_at`) VALUES
(3, 73, '<html dir=\"ltr\"><head></head><body contenteditable=\"true\"><p><span style=\"font-family: &quot;&quot;;\">WAAAA</span></p></body></html>', '2026-03-03 08:10:53', '2026-03-03 08:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `opérations`
--

DROP TABLE IF EXISTS `opérations`;
CREATE TABLE IF NOT EXISTS `opérations` (
  `id_operation` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tresorerie` int NOT NULL,
  `type` enum('revenu','depense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `categorie` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_operation` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_operation`),
  UNIQUE KEY `reference` (`reference`),
  UNIQUE KEY `facture` (`facture`),
  KEY `id_tresorerie` (`id_tresorerie`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opérations`
--

INSERT INTO `opérations` (`id_operation`, `reference`, `facture`, `pdf_url`, `id_tresorerie`, `type`, `montant`, `categorie`, `date_operation`, `description`) VALUES
(73, 'OP-00001', '555', 'uploads/factures/facture_OP-00001_1772525442856.pdf', 31, 'revenu', 336.79, 'AAS', '2026-03-03 09:10:43', 'SS');

-- --------------------------------------------------------

--
-- Table structure for table `participation_jpo`
--

DROP TABLE IF EXISTS `participation_jpo`;
CREATE TABLE IF NOT EXISTS `participation_jpo` (
  `id_participation` int NOT NULL AUTO_INCREMENT,
  `id_evenement` int NOT NULL,
  `id_entreprise` int DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `role` enum('entreprise','investisseur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `badge_genere` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_participation`),
  UNIQUE KEY `unique_participation_investisseur` (`id_evenement`,`id_utilisateur`),
  UNIQUE KEY `unique_participation_entreprise` (`id_evenement`,`id_entreprise`),
  KEY `id_entreprise` (`id_entreprise`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `participation_jpo`
--

INSERT INTO `participation_jpo` (`id_participation`, `id_evenement`, `id_entreprise`, `id_utilisateur`, `role`, `statut`, `date_inscription`, `badge_genere`) VALUES
(2, 1, NULL, 9, 'entreprise', 'confirmé', '2026-02-28 22:39:27', 1),
(3, 1, NULL, 7, 'entreprise', 'confirmé', '2026-03-03 07:59:22', 0),
(4, 2, NULL, 7, 'entreprise', 'annulé', '2026-03-03 01:16:11', 0),
(5, 3, NULL, 7, 'entreprise', 'annulé', '2026-03-03 01:19:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rendement_investissement`
--

DROP TABLE IF EXISTS `rendement_investissement`;
CREATE TABLE IF NOT EXISTS `rendement_investissement` (
  `id_rendement` int NOT NULL AUTO_INCREMENT,
  `id_investissement` int NOT NULL,
  `date_calcul` date NOT NULL,
  `gain` decimal(15,2) DEFAULT '0.00',
  `perte` decimal(15,2) DEFAULT '0.00',
  `valeur_portefeuille` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id_rendement`),
  KEY `fk_rend_inv_invest` (`id_investissement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `score_entreprise`
--

DROP TABLE IF EXISTS `score_entreprise`;
CREATE TABLE IF NOT EXISTS `score_entreprise` (
  `id_score` int NOT NULL AUTO_INCREMENT,
  `id_entreprise` int NOT NULL,
  `score_ai` decimal(5,2) DEFAULT NULL,
  `niveau_risque` enum('faible','moyen','eleve') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'moyen',
  `score_rentabilite` decimal(5,2) DEFAULT NULL,
  `score_croissance` decimal(5,2) DEFAULT NULL,
  `derniere_maj` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_score`),
  UNIQUE KEY `unique_entreprise_score` (`id_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trésorerie`
--

DROP TABLE IF EXISTS `trésorerie`;
CREATE TABLE IF NOT EXISTS `trésorerie` (
  `id_tresorerie` int NOT NULL AUTO_INCREMENT,
  `id_entreprise` int NOT NULL,
  `nom_compte` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_compte` enum('CAISSE','BANQUE','CARTE','WALLET') COLLATE utf8mb4_unicode_ci NOT NULL,
  `solde` decimal(15,2) DEFAULT '0.00',
  `devise` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'EUR',
  `derniere_maj` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rib` varchar(34) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_compte` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_tresorerie`),
  UNIQUE KEY `numero_compte` (`numero_compte`),
  KEY `id_entreprise` (`id_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trésorerie`
--

INSERT INTO `trésorerie` (`id_tresorerie`, `id_entreprise`, `nom_compte`, `type_compte`, `solde`, `devise`, `derniere_maj`, `rib`, `numero_compte`) VALUES
(30, 17, 'Capital', 'CAISSE', 10000.00, 'TND', '2026-03-03 02:10:22', NULL, NULL),
(31, 18, 'Capital', 'CAISSE', 5336.79, 'TND', '2026-03-03 09:10:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_kyc`
--

DROP TABLE IF EXISTS `user_kyc`;
CREATE TABLE IF NOT EXISTS `user_kyc` (
  `user_id` int NOT NULL,
  `face_embedding` blob NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `id_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_kyc`
--

INSERT INTO `user_kyc` (`user_id`, `face_embedding`, `is_verified`, `verified_at`, `id_document_path`, `created_at`, `updated_at`) VALUES
(7, 0x686973745f636f7272656c5f31373732353333383130393233, 1, '2026-03-03 10:30:11', 'C:/Users/ergue/Desktop/operation/validation/CASHFLYNEEDDESIGN/operation financierefull work/uploads/kyc/faces/bee3966e-9981-4d53-ab15-19232a7b220d.png', '2026-03-03 10:30:10', '2026-03-03 10:30:10'),
(9, 0x686973745f636f7272656c5f31373732333037313031313635, 1, '2026-02-28 19:31:41', 'C:/Users/moate/OneDrive/Bureau/CashFly Demo/CashFly/operation financierefull work/uploads/kyc/faces/1b7a8fca-c52c-41e9-b6d6-e7eb00ef3394.png', '2026-02-28 19:31:41', '2026-02-28 19:31:41'),
(14, 0x686973745f636f7272656c5f31373732343939393534313035, 1, '2026-03-03 01:05:54', 'C:/Users/ergue/Desktop/operation/validation/CASHFLYNEEDDESIGN/operation financierefull work/uploads/kyc/faces/9bac019c-a86e-4aa9-af84-3721237c6cc2.png', '2026-03-03 01:05:54', '2026-03-03 01:05:54');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `cin` int DEFAULT NULL,
  `tel` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('proprietaire','investisseur','administrateur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `yearsExperience` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `highestProfit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `face_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `cin`, `tel`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_creation`, `yearsExperience`, `highestProfit`, `budget`, `active`, `face_image`) VALUES
(7, 14785236, '55288599', 'mohamed', 'erguez', 'erguez.mohamed@gmail.com', '348adffc36e3a4d21401765142114aea', 'proprietaire', '2026-02-27 23:52:20', 'AA', 'DD', 'DD', 1, 'faces/WIN_20260226_13_21_30_Pro.jpg'),
(9, 1585698, '95524483', 'moataz', 'ouesleti', 'moatez679@gmail.com', '5b7727ef0f841b3bfdbc124dc55fc8f4', 'proprietaire', '2026-02-28 20:28:48', NULL, NULL, NULL, 1, NULL),
(10, 12235678, NULL, 'Talbi', 'Salma', 'X1@GMAIL.COM', '631abaa78c0ceb33dd4c7bc6d7e4db02', 'administrateur', '2026-03-02 09:25:55', '\"C', NULL, NULL, 1, 'faces/admin_150.jpg'),
(14, 12135485, '55288599', 'ERGZ', 'mohamed', 'mohamed.erguez@gmail.com', '57383e307938869ea558c79c915238c7', 'investisseur', '2026-03-03 02:04:53', '5', '1000', '10', 1, NULL),
(15, 9885557, '09885555', '09885555', '09885555', 'ekher@gmail.com', '6cf7b17a2e9c66300dbeeefab4ce5aa3', 'investisseur', '2026-03-03 08:28:32', '8', '8', '6888', 1, NULL),
(21, 12345678, '55288599', 'HH', 'HH', 'X45@GMAIL.COM', 'd41d8cd98f00b204e9800998ecf8427e', 'proprietaire', '2026-03-03 10:56:50', NULL, NULL, NULL, 1, NULL);

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
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_entreprise` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE;

--
-- Constraints for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_ibfk_1` FOREIGN KEY (`id_proprietaire`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `investissement`
--
ALTER TABLE `investissement`
  ADD CONSTRAINT `fk_inv_entreprise` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inv_user` FOREIGN KEY (`id_investisseur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opérations`
--
ALTER TABLE `opérations`
  ADD CONSTRAINT `opérations_ibfk_1` FOREIGN KEY (`id_tresorerie`) REFERENCES `trésorerie` (`id_tresorerie`);

--
-- Constraints for table `participation_jpo`
--
ALTER TABLE `participation_jpo`
  ADD CONSTRAINT `participation_jpo_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`),
  ADD CONSTRAINT `participation_jpo_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `rendement_investissement`
--
ALTER TABLE `rendement_investissement`
  ADD CONSTRAINT `fk_rend_inv_invest` FOREIGN KEY (`id_investissement`) REFERENCES `investissement` (`id_investissement`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_user_kyc` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
