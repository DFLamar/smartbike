# SmartBike

Site e-commerce de vente de vélos électriques, réalisé en projet de fin de formation.

Les visiteurs parcourent le catalogue, créent un compte, remplissent leur panier et paient en ligne avec Stripe. Un espace d'administration permet de gérer les produits et de suivre les commandes.

## Technologies

- **PHP 8.3+** avec le framework **Laravel 13**
- **MySQL / MariaDB** (via XAMPP)
- **Blade**, HTML et CSS pour les pages
- **Stripe Checkout** pour le paiement (mode test)
- **Composer** pour les dépendances PHP

## Prérequis

- [XAMPP](https://www.apachefriends.org/fr/) (fournit PHP et MySQL) : PHP **8.3 ou plus récent**
- [Composer](https://getcomposer.org/)
- Un compte [Stripe](https://dashboard.stripe.com/register) (gratuit) pour obtenir des clés de test

## Installation

1. **Copier le projet** dans `C:\xampp\htdocs\` puis ouvrir un terminal dans le dossier `smartbike`.

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Créer le fichier de configuration**
   ```bash
   copy .env.example .env
   ```
   (sous Linux / macOS : `cp .env.example .env`)

4. **Générer la clé de l'application**
   ```bash
   php artisan key:generate
   ```

5. **Configurer la base de données** : dans `.env`, remplacer les lignes `DB_` par :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smartbike
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Créer la base de données**
   1. Démarrer **Apache** et **MySQL** dans le panneau XAMPP.
   2. Dans [phpMyAdmin](http://localhost/phpmyadmin), créer une base nommée `smartbike` (interclassement `utf8mb4_unicode_ci`).
   3. Onglet **Importer** : choisir le fichier `database/smartbike.sql`. Il crée toutes les tables, vides.
   4. Remplir la base avec les données de démonstration (catégories, vélos, comptes de test) :
      ```bash
      php artisan db:seed
      ```

7. **Rendre les photos des vélos accessibles**
   ```bash
   php artisan storage:link
   ```

8. **Ajouter les clés Stripe de test** : dans le [tableau de bord Stripe](https://dashboard.stripe.com/test/apikeys), en **mode test**, copier les deux clés à la fin du `.env` :
   ```env
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   ```

9. **Lancer le site**
   ```bash
   php artisan serve
   ```
   Le site est accessible sur <http://127.0.0.1:8000>.

## Identifiants de test

Ces comptes sont créés par `php artisan db:seed`.

| Rôle           | Email                 | Mot de passe |
|----------------|-----------------------|--------------|
| Administrateur | `admin@smartbike.fr`  | `Admin123!`  |
| Client         | `client@smartbike.fr` | `Client123!` |

L'administrateur est redirigé vers le back-office (`/admin`) après la connexion.

## Payer avec Stripe (mode test)

Aucun vrai paiement n'est effectué. Sur la page de paiement Stripe, utiliser :

- **Numéro de carte** : `4242 4242 4242 4242`
- **Date d'expiration** : n'importe quelle date future (ex. `12/34`)
- **CVC** : n'importe quels 3 chiffres (ex. `123`)
- **Nom / code postal** : n'importe quelle valeur

## Fonctionnalités

**Côté visiteur et client**
- Page d'accueil et catalogue de vélos, avec pagination
- Fiche produit : photos, prix, autonomie, puissance, poids, stock disponible
- Inscription, connexion, déconnexion
- Mot de passe oublié : lien de réinitialisation envoyé par email, valable 60 minutes
- Panier : ajout, modification des quantités, suppression. Les quantités sont limitées au stock.
- Commande avec choix de la livraison (standard ou express) et paiement sécurisé par Stripe
- Email de confirmation de commande
- Espace client : modification du profil et historique des commandes
- Formulaire de contact

**Côté administrateur**
- Tableau de bord
- Gestion des produits : ajout, modification, suppression, photos
- Suivi des commandes et changement de leur statut

**Sécurité**
- Mots de passe hachés (bcrypt)
- Protection CSRF sur tous les formulaires
- Nouvelle session à chaque connexion. La session est détruite à la déconnexion.
- Limitation à 5 tentatives de connexion par minute
- Pages client et admin protégées par des middlewares
- Stock revérifié avant le paiement
