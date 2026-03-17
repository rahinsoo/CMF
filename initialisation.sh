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
    docker-compose exec php composer create-project symfony/skeleton cmf_webinar # --no-interaction
fi
