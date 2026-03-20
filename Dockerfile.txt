# ============================================
# Image de base : PHP 8.4 avec PHP-FPM.
# PHP-FPM (FastCGI Process Manager) gère un pool
# de processus PHP qui reçoivent les requêtes de Nginx
# via le protocole FastCGI sur le port 9000.
# ============================================
FROM php:8.4-fpm

# Arguments Docker Build pour correspondre à l'UID/GID
# de l'utilisateur hôte (évite les conflits de permissions
# sur les fichiers créés dans le volume monté).
# Valeur par défaut : 1000 (UID standard sous Linux/macOS).
ARG USER_ID=1000
ARG GROUP_ID=1000

# ============================================
# Dépendances système nécessaires pour compiler
# les extensions PHP et utiliser les outils Symfony.
# - git       : utilisé par Composer pour cloner des dépendances
# - curl      : téléchargements HTTP
# - unzip/zip : décompression des packages Composer
# - libicu-dev    : bibliothèque pour l'extension PHP "intl" (i18n)
# - libpng-dev    : bibliothèque pour l'extension PHP "gd" (images)
# - libzip-dev    : bibliothèque pour l'extension PHP "zip"
# - libonig-dev   : bibliothèque pour l'extension PHP "mbstring"
# - libxml2-dev   : bibliothèque pour l'extension PHP "xml"
# ============================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ============================================
# Extensions PHP requises par Symfony 7.x
# - pdo         : couche d'abstraction base de données (PDO)
# - pdo_mysql   : driver PDO pour MySQL/MariaDB (Doctrine ORM)
# - intl        : internationalisation (dates, devises, langues)
# - zip         : gestion des archives ZIP (utilisé par Composer)
# - opcache     : cache du bytecode PHP compilé (gain de performance)
# - mbstring    : manipulation de chaînes multi-octets (UTF-8)
# - xml         : parsing et génération de XML
# - exif        : lecture des métadonnées EXIF des images
# - pcntl       : contrôle de processus (signaux Unix, utile pour les workers)
# - bcmath      : calculs numériques de haute précision
# - gd          : manipulation d'images (redimensionnement, miniatures)
# ============================================
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    zip \
    opcache \
    mbstring \
    xml \
    exif \
    pcntl \
    bcmath \
    gd

# ============================================
# Composer : gestionnaire de dépendances PHP.
# On copie uniquement le binaire depuis l'image officielle
# plutôt que de l'installer manuellement (plus propre et à jour).
# ============================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================
# Répertoire de travail dans le conteneur.
# Correspond au dossier CMF/ monté depuis l'hôte.
# Toutes les commandes suivantes s'exécutent depuis ce dossier.
# ============================================
WORKDIR /var/www/html

# ============================================
# Gestion des permissions utilisateur.
# On crée un utilisateur "appuser" avec le même UID/GID
# que l'utilisateur hôte (passé via --build-arg).
# Cela évite que les fichiers créés dans le conteneur
# (cache Symfony, logs, etc.) appartiennent à root sur l'hôte.
# ============================================
RUN groupadd -g ${GROUP_ID} appgroup && \
    useradd -u ${USER_ID} -g appgroup -m appuser

# Donne la propriété du dossier de travail à appuser
RUN chown -R appuser:appgroup /var/www/html

# Basculer sur l'utilisateur non-root pour plus de sécurité
USER appuser

# Port exposé par PHP-FPM (utilisé par Nginx dans docker-compose)
EXPOSE 9000

# Commande de démarrage : lance le serveur PHP-FPM
CMD ["php-fpm"]
