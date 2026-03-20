#!/bin/bash

# ============================================
# Script d'initialisation du projet Symfony
# avec Docker Compose.
#
# Ce script :
#   1. Construit et démarre les conteneurs Docker
#   2. Crée le projet Symfony si nécessaire
#   3. Installe le composant webapp (Twig, Form, etc.)
#   4. Crée la base de données et applique les migrations
#
# Usage : ./initialisation.sh
# ============================================

set -e  # Arrête le script immédiatement en cas d'erreur

echo "=== Démarrage de l'initialisation ==="

# ============================================
# Étape 1 : Construction et démarrage des conteneurs
# "build" reconstruit l'image PHP si le Dockerfile a changé.
# "up -d" démarre tous les services en arrière-plan (detached).
# ============================================
echo ">>> Construction des images Docker..."
docker-compose build

echo ">>> Démarrage des conteneurs..."
docker-compose up -d

# ============================================
# Attendre que les conteneurs soient prêts.
# MySQL peut mettre quelques secondes à être opérationnel.
# ============================================
echo ">>> Attente du démarrage des services (15 secondes)..."
sleep 15

# ============================================
# Étape 2 : Création du projet Symfony (si pas encore fait)
# On vérifie l'existence de composer.json pour éviter
# d'écraser un projet existant.
# "symfony/skeleton" installe le noyau Symfony minimal.
# "--no-interaction" évite les questions interactives.
# ============================================
if [ ! -f "composer.json" ]; then
    echo ">>> Création du projet Symfony (skeleton)..."
    # Crée le projet dans le dossier courant (/var/www/html dans le conteneur)
    # Note : on utilise "." pour créer dans le répertoire courant
    docker-compose exec php composer create-project symfony/skeleton . --no-interaction

    echo ">>> Installation du composant webapp (Twig, Form, Doctrine, etc.)..."
    # "webapp" est un meta-package Symfony qui installe les dépendances
    # classiques d'une application web complète.
    docker-compose exec php composer require webapp --no-interaction
    echo ">>> Installation du composant security (Sécurité, user, etc)..."
    docker-compose exec php composer require symfony/security-bundle --no-interaction
else
    echo ">>> composer.json détecté, le projet Symfony existe déjà."
    echo ">>> Installation/mise à jour des dépendances Composer..."
    # Met à jour les dépendances selon composer.lock
    docker-compose exec php composer install --no-interaction
fi

# ============================================
# Étape 3 : Initialisation de la base de données
# "doctrine:database:create" crée la BDD si elle n'existe pas.
# "doctrine:migrations:migrate" applique toutes les migrations
# Doctrine en attente (crée les tables, etc.).
# ============================================
echo ">>> Création de la base de données (si inexistante)..."
docker-compose exec php php bin/console doctrine:database:create --if-not-exists

echo ">>> Application des migrations Doctrine..."
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# ============================================
# Étape 4 : Résumé et accès
# ============================================
echo ""
echo "=== ✅ Initialisation terminée avec succès ! ==="
echo ""
echo "  🌐 Application Symfony  : http://localhost:8080"
echo "  🛠️  phpMyAdmin           : http://localhost:8081"
echo "  🗄️  MySQL (hôte)         : 127.0.0.1:3306"
echo ""
echo "Pour arrêter les conteneurs : docker-compose down"
echo "Pour voir les logs          : docker-compose logs -f"
