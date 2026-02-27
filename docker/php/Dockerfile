FROM php:8.4-fpm

# ============================================
# Installation des dépendances système
# ============================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ============================================
# Extensions PHP requises pour Symfony
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
# Composer
# ============================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================
# Répertoire de travail
# ============================================
WORKDIR /var/www/symfony

# ============================================
# Permissions
# ============================================
RUN groupmod -g 1000 www-data && usermod -u 1000 www-data

USER www-data