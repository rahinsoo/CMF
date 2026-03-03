# ============================================
# Image de base : PHP 8.4 avec PHP-FPM
# PHP-FPM = gestionnaire de processus PHP,
# il reçoit les requêtes de Nginx et exécute le PHP
# ============================================
FROM php:8.4-fpm

# ============================================
# Dépendances système nécessaires pour Symfony
# ============================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ============================================
# Extensions PHP requises par Symfony 7.4
# - pdo_mysql    : connexion à MariaDB/MySQL
# - intl         : internationalisation (dates, langues)
# - zip          : gestion des archives (Composer)
# - opcache      : mise en cache du bytecode PHP (performance)
# - mbstring     : gestion des chaînes multi-octets (UTF-8)
# - xml          : traitement XML
# ============================================
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    zip \
    opcache \
    mbstring \
    xml

# ============================================
# Composer : gestionnaire de dépendances PHP
# Copié depuis l'image officielle composer:latest
# ============================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================
# Répertoire de travail dans le conteneur.
# Correspond au dossier CMF/ monté depuis l'hôte.
# C'est ici que Symfony sera installé.
# ============================================
# WORKDIR /var/www/html
WORKDIR /app

# ============================================
# Ajustement des permissions de www-data
# pour correspondre à l'utilisateur hôte (UID 1000)
# Evite les problèmes de droits sur les fichiers créés
# ============================================
RUN groupmod -g 1000 www-data && usermod -u 1000 www-data
# RUN groupmod -g 1000 app-data && usermod -u 1000 app-data