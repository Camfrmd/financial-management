# Digital Village - Financial Management

Ce projet est une application web de gestion financière dédiée aux communautés, permettant le suivi des transactions et un processus de validation structuré.

## Prérequis

- PHP >= 8.2
- Composer
- Node.js & NPM
- Une base de données locale (SQLite, MySQL, etc.)

## Installation et Démarrage Rapide

Voici les commandes strictement nécessaires pour démarrer le projet en environnement de développement local :

1. **Installer les dépendances :**
   ```bash
   composer install
   npm install
   ```

2. **Configurer l'environnement :**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note : Assurez-vous de configurer les accès à votre base de données dans le fichier `.env` si vous n'utilisez pas la configuration par défaut.*

3. **Compiler les ressources front-end :**
   ```bash
   npm run build
   ```
   *(Si vous développez, utilisez plutôt `npm run dev`)*

4. **Migrer la base de données et injecter les données de test :**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Lancer le serveur web local :**
   ```bash
   php artisan serve
   ```
   L'application sera disponible à l'adresse : `http://localhost:8000`.

## Identifiants de connexion (Données de test)

Grâce à la commande de *seed* (étape 4), des utilisateurs par défaut ont été créés pour vous permettre de tester les différents droits d'accès.

**Accès Trésorier (Saisie des transactions) :**
- **Email :** `treasurer@banjar.com`
- **Mot de passe :** `password123`

**Accès Kelian (Validation et supervision) :**
- **Email :** `kelian@banjar.com`
- **Mot de passe :** `password123`
