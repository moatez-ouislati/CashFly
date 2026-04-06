# CashFly - Plateforme de Gestion de Trésorerie et d'Investissement pour PME

CashFly est une application web complète développée avec **Symfony 6.4**, conçue pour aider les propriétaires de petites et moyennes entreprises (PME) à gérer leurs finances et permettre aux investisseurs de découvrir des opportunités de croissance.

## 🚀 Fonctionnalités Principales

### 👤 Gestion des Utilisateurs & Sécurité
- **Rôles Multiples** : Administrateur, Propriétaire de PME, Investisseur.
- **Sécurité Stricte** : Blocage automatique des comptes inactifs avec message de redirection vers l'administrateur.
- **Profils Enrichis** : Collecte de données spécifiques (CIN, Tel, Expérience, Budget) lors de l'inscription et de la première connexion.

### 🏢 Gestion des PME
- **Portefeuille d'Entreprises** : Création et gestion détaillée des entreprises (Secteur, Capital, Localisation).
- **Tableau de Bord Propriétaire** : Vue d'ensemble des soldes cumulés, des dernières opérations et des alertes de trésorerie.
- **Découverte (Investisseurs)** : Exploration des PME avec accès aux détails publics (Secteur, Contact, ROI prévu).

### 💰 Trésorerie & Opérations
- **Multi-comptes** : Gestion de plusieurs comptes (Banque, Caisse, Wallet) par entreprise.
- **Suivi des Flux** : Enregistrement des revenus et dépenses avec mise à jour automatique du solde.
- **Virements Internes** : Transfert de fonds sécurisé entre les comptes d'une même entreprise avec vérification de solde.

### 📈 Investissements
- **Propositions** : Les investisseurs peuvent proposer des montants avec un taux de rendement et une durée.
- **Suivi des Rendements** : Tableau de bord dédié aux investisseurs calculant le profit estimé (ROI) en temps réel.
- **Gestion des Investisseurs** : Les propriétaires de PME peuvent voir qui a investi chez eux et consulter leur profil d'investisseur.

### 📁 Gestion Documentaire
- **Coffre-fort Numérique** : Upload de documents sensibles (Pitch Deck, Business Plan, Factures).
- **Confidentialité** : Les investisseurs n'ont pas accès aux documents internes des PME, garantissant la protection des données.

## 🛠 Architecture Technique
- **Framework** : Symfony 6.4 (MVC)
- **Base de Données** : MySQL (Structure stable et optimisée)
- **Frontend** : Twig & Tailwind CSS (Interface moderne et responsive)
- **Validation** : Assertions Symfony pour une intégrité totale des données.

## ⚙️ Installation
1. Clonez le dépôt.
2. Installez les dépendances : `composer install`.
3. Configurez votre `.env` avec vos accès MySQL.
4. Créez la base de données : `php bin/console doctrine:database:create`.
5. Lancez le serveur : `symfony serve`.

---
*Développé avec ❤️ pour la croissance des PME.*
