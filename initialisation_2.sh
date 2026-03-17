#!/bin/bash

# ============================================
#  suite du script d'initialisation du projet Symfony
# avec Docker Compose.
#
# Ce script :
#   3. Installe le composant webapp (Twig, Form, etc.)
#   4. Crée la base de données et applique les migrations
#
# Usage : ./initialisation_2.sh
# ============================================

set -e  # Arrête le script immédiatement en cas d'erreur

echo "=== Démarrage de la suite de l'initialisation ==="


# ============================================
# Étape 2 : Création du projet Symfony (si pas encore fait)
# On vérifie l'existence de composer.json pour éviter
# d'écraser un projet existant.
# "require webapp" installe webapp si Symfony est installé.
# "--no-interaction" évite les questions interactives.
# ============================================
if [ ! -f "composer.json" ]; then

    echo ">>> Installation du composant webapp (Twig, Form, Doctrine, etc.)..."
    # "webapp" est un meta-package Symfony qui installe les dépendances
    # classiques d'une application web complète.
    docker-compose exec php composer require webapp # --no-interaction
    docker-compose exec php composer require easycorp/easyadmin-bundle
else

    echo ">>> composer.json détecté, le projet Symfony existe déjà."
    echo ">>> Installation/mise à jour des dépendances Composer..."
    # Met à jour les dépendances selon composer.lock
    docker-compose exec php composer install # --no-interaction
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
