# Docker pour le projet CMF (Webinar) 🐳

Le dépôt `rahinsoo/CMF` est quasi vide pour l'instant. Voici une configuration **complète et précise** pour dockeriser ton site de Webinaire avec Symfony 7.4.

---

## 📁 Structure des fichiers à créer

```
CMF/
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
├── .env
└── symfony/          ← ton projet Symfony ira ici
```

---

## 1️⃣ `docker-compose.yml` — Le fichier central

```yaml name=docker-compose.yml
version: '3.9'

services:

  # ============================================
  # 🐘 PHP 8.4 + Symfony
  # ============================================
  php:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: cmf_php
    volumes:
      - ./symfony:/var/www/symfony
    networks:
      - cmf_network
    depends_on:
      - database
    environment:
      APP_ENV: dev
      DATABASE_URL: "mysql://root:${MYSQL_ROOT_PASSWORD}@database:3306/${MYSQL_DATABASE}?serverVersion=8.0&charset=utf8mb4"

  # ============================================
  # 🌐 Nginx (serveur web)
  # ============================================
  nginx:
    image: nginx:alpine
    container_name: cmf_nginx
    ports:
      - "8080:80"
    volumes:
      - ./symfony:/var/www/symfony
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - cmf_network
    depends_on:
      - php

  # ============================================
  # 🗄️ MySQL 8.0 (MariaDB)
  # ============================================
  database:
    image: mariadb:10.11
    container_name: cmf_database
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    ports:
      - "3306:3306"
    volumes:
      - cmf_db_data:/var/lib/mysql
    networks:
      - cmf_network

  # ============================================
  # 🛠️ phpMyAdmin
  # ============================================
  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    container_name: cmf_phpmyadmin
    restart: always
    ports:
      - "8081:80"
    environment:
      PMA_HOST: database
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
    networks:
      - cmf_network
    depends_on:
      - database

networks:
  cmf_network:
    driver: bridge

volumes:
  cmf_db_data:
    driver: local
```

> ⚠️ **Note sur PHP 8.5** : PHP 8.5 n'est pas encore sorti en version stable (date de sortie prévue fin 2025/2026). On utilise **PHP 8.4** qui est la dernière version stable. Met à jour quand elle sera disponible.

---

## 2️⃣ `docker/php/Dockerfile` — Image PHP personnalisée

```dockerfile name=docker/php/Dockerfile
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
```

---

## 3️⃣ `docker/nginx/default.conf` — Configuration Nginx

```nginx name=docker/nginx/default.conf
server {
    listen 80;
    server_name localhost;

    root /var/www/symfony/public;
    index index.php;

    # Gestion des routes Symfony
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # Exécution des fichiers PHP via PHP-FPM
    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Sécurité : bloquer l'accès aux fichiers cachés
    location ~ /\.ht {
        deny all;
    }

    # Logs
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
}
```

---

## 4️⃣ `.env` — Variables d'environnement

```bash name=.env
# Base de données
MYSQL_ROOT_PASSWORD=root_secret
MYSQL_DATABASE=cmf_webinar
MYSQL_USER=cmf_user
MYSQL_PASSWORD=cmf_secret
```

---

## 5️⃣ Installation pas à pas 🚀

### Étape 1 — Cloner le dépôt et se positionner dedans
```bash name=terminal
git clone https://github.com/rahinsoo/CMF.git
cd CMF
```

### Étape 2 — Créer le fichier `.env` à la racine
```bash name=terminal
cp .env.example .env  # ou créer manuellement avec le contenu ci-dessus
```

### Étape 3 — Démarrer les conteneurs Docker
```bash name=terminal
docker compose up -d --build
```

### Étape 4 — Créer le projet Symfony 7.4 dans le conteneur PHP
```bash name=terminal
# Entrer dans le conteneur PHP
docker exec -it cmf_php bash

# Créer le projet Symfony 7.4 dans le dossier courant
composer create-project symfony/skeleton:"7.4.*" .

# Installer les composants essentiels
composer require symfony/webapp-pack
composer require doctrine/doctrine-bundle doctrine/orm
composer require symfony/mailer
composer require guzzlehttp/guzzle
```

### Étape 5 — Configurer le `.env` de Symfony
```bash name=symfony/.env
# Dans symfony/.env, modifier la ligne DATABASE_URL :
DATABASE_URL="mysql://cmf_user:cmf_secret@database:3306/cmf_webinar?serverVersion=8.0&charset=utf8mb4"
```

### Étape 6 — Créer la base de données et les tables
```bash name=terminal
# Toujours dans le conteneur PHP
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Étape 7 — Vider le cache
```bash name=terminal
php bin/console cache:clear
```

---

## 6️⃣ Accéder aux services 🌐

| Service | URL | Identifiants |
|---|---|---|
| 🌐 **Site Symfony** | http://localhost:8080 | — |
| 🛠️ **phpMyAdmin** | http://localhost:8081 | root / root_secret |
| 🗄️ **MySQL** | localhost:3306 | cmf_user / cmf_secret |

---

## 7️⃣ Commandes Docker utiles

```bash name=terminal
# Démarrer tous les conteneurs
docker compose up -d

# Arrêter tous les conteneurs
docker compose down

# Voir les logs en temps réel
docker compose logs -f

# Entrer dans le conteneur PHP (pour Symfony)
docker exec -it cmf_php bash

# Entrer dans le conteneur MySQL
docker exec -it cmf_database mysql -u root -p

# Reconstruire les images après modification du Dockerfile
docker compose up -d --build

# Supprimer les volumes (⚠️ efface la base de données)
docker compose down -v
```

---

## 8️⃣ Flux de développement recommandé

```
docker compose up -d
        ↓
docker exec -it cmf_php bash
        ↓
php bin/console make:entity    ← créer entités (Webinar, etc.)
        ↓
php bin/console make:migration
        ↓
php bin/console doctrine:migrations:migrate
        ↓
php bin/console make:controller
        ↓
Tester sur http://localhost:8080
```

---

## 📚 Sources

- 📖 [Docker Compose Documentation](https://docs.docker.com/compose/)
- 📖 [Image PHP officielle Docker Hub](https://hub.docker.com/_/php)
- 📖 [Image MariaDB Docker Hub](https://hub.docker.com/_/mariadb)
- 📖 [Image phpMyAdmin Docker Hub](https://hub.docker.com/_/phpmyadmin)
- 📖 [Symfony 7.4 - Installation](https://symfony.com/doc/7.4/setup.html)
- 📖 [Doctrine ORM avec Symfony](https://symfony.com/doc/current/doctrine.html)

> 💡 **Résumé** : Lance `docker compose up -d --build`, entre dans le conteneur PHP avec `docker exec -it cmf_php bash`, installe Symfony avec Composer, et ton environnement complet est opérationnel en quelques minutes !
> 
>

Modification demandée :
Dans ta proposition, tu me proposes de travailler dans /var/www/symfony/public, le serveur utilisé est 0.0.0.0 et tu te sers de nginx.

Avec cette configuration :
```
CMF/
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
├── .env
└── symfony/          ← ton projet Symfony ira ici
```

Je souhaite plusieurs modifications :
1. Travailler directement dans le dossier CMF. Tous les éléments liés à Symfony seraient dans ce dossier.
```
CMF/    ← ton projet Symfony ira ici
├── docker-compose.yml
├── Dockerfile
├── default.conf    ← config nginx (ici ou propose moi une autre alternative, seulement si nécessaire)
└── .env
```
2. Fait ressortir le site directement à partir de CMF/public/index.php
3. Explique l'utilité de nginx, si besoin, prépare le mois.
4. Connecte-toi au serveur 127.0.0.1 dans le fichier .env ou celui que gèrent ces informations

Expliques en détail.
Prépare les fichiers avec les commentaires qui ont de l'intérêt.

# Modifications de la Configuration Docker 🐳

## 🏗️ Nouvelle Structure

```
CMF/                          ← RACINE du projet (Symfony + Docker)
├── public/
│   └── index.php             ← Point d'entrée Symfony
├── src/
├── config/
├── docker-compose.yml
├── Dockerfile                ← Image PHP (plus de dossier docker/)
├── default.conf              ← Config Nginx
├── .env                      ← Variables Docker + Symfony
└── ...                       ← Reste des fichiers Symfony
```

---

## 3️⃣ Nginx : Utile ou pas ? 🤔

### Pourquoi Nginx est nécessaire ici

```
[Navigateur]
     ↓  requête HTTP
[Nginx :80]          ← Reçoit TOUTES les requêtes HTTP
     ↓  fichiers statiques (css, js, images) → servis directement
     ↓  fichiers .php → transmis à PHP-FPM
[PHP-FPM :9000]      ← Exécute uniquement le code PHP
     ↓
[Symfony / index.php]
```

> **PHP-FPM** (le mode utilisé dans Docker) est un **gestionnaire de processus PHP**, il ne sait pas écouter des requêtes HTTP tout seul. Il a **besoin d'un serveur web** (Nginx ou Apache) pour lui transmettre les requêtes.

### Alternative sans Nginx : `symfony server:start`
```bash name=terminal
# Possible UNIQUEMENT en développement local
symfony server:start --no-tls
# Démarre un serveur intégré sur 127.0.0.1:8000
# ⚠️ Non recommandé en production, moins performant
```

> ✅ **Conclusion** : On **garde Nginx** car on utilise PHP-FPM dans Docker. C'est la configuration standard et la plus robuste, même en développement.

---

## 1️⃣ `docker-compose.yml`

```yaml name=docker-compose.yml
version: '3.9'

services:

  # ============================================
  # 🐘 PHP 8.4-FPM
  # Exécute le code Symfony.
  # Le dossier racine CMF/ est monté directement
  # dans /var/www/html (pas de sous-dossier symfony/)
  # je test avec /app
  # ============================================
  php:
    build:
      context: .               # Dockerfile à la racine de CMF/
      dockerfile: Dockerfile
    container_name: cmf_php
    volumes:
      - .:/app  #.:/var/www/html        # CMF/ → /var/www/html dans le conteneur
    networks:
      - cmf_network
    depends_on:
      - database

  # ============================================
  # 🌐 Nginx
  # Reçoit les requêtes HTTP et les transmet
  # à PHP-FPM. Sert aussi les fichiers statiques.
  # Port local 8080 → port 80 du conteneur
  # ============================================
  nginx:
    image: nginx:alpine
    container_name: cmf_nginx
    ports:
      - "8080:80"
    volumes:
      - .:/app    #.:/var/www/html                          # Même dossier que PHP
      - ./default.conf:/etc/nginx/conf.d/default.conf  # Config Nginx
    networks:
      - cmf_network
    depends_on:
      - php

  # ============================================
  # 🗄️ MariaDB 10.11 (compatible MySQL 8.0)
  # Stocke toutes les données du site.
  # Les données sont persistées dans un volume Docker
  # pour ne pas être perdues au redémarrage.
  # ============================================
  database:
    image: mariadb:10.11
    container_name: cmf_database
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    ports:
      - "3306:3306"             # Accessible sur 127.0.0.1:3306 depuis ta machine
    volumes:
      - cmf_db_data:/var/lib/mysql
    networks:
      - cmf_network

  # ============================================
  # 🛠️ phpMyAdmin
  # Interface graphique pour gérer la base de données.
  # Accessible sur http://127.0.0.1:8081
  # ============================================
  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    container_name: cmf_phpmyadmin
    restart: always
    ports:
      - "8081:80"               # Accessible sur 127.0.0.1:8081 depuis ta machine
    environment:
      PMA_HOST: database        # Nom du service MariaDB ci-dessus
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
    networks:
      - cmf_network
    depends_on:
      - database

# ============================================
# Réseau interne partagé entre tous les conteneurs
# ============================================
networks:
  cmf_network:
    driver: bridge

# ============================================
# Volume persistant pour les données MariaDB
# ============================================
volumes:
  cmf_db_data:
    driver: local
```

---

## 2️⃣ `Dockerfile` — À la racine de CMF/

```dockerfile name=Dockerfile
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
#RUN groupmod -g 1000 www-data && usermod -u 1000 www-data
RUN groupmod -g 1000 app-data && usermod -u 1000 app-data
```

---

## 4️⃣ `default.conf` — Configuration Nginx

```nginx name=default.conf
# ============================================
# Configuration Nginx pour Symfony 7.4
# ============================================
server {
    listen 80;

    # 127.0.0.1 = ta machine locale.
    # Dans Docker, Nginx écoute sur toutes les interfaces
    # du conteneur, le binding 127.0.0.1:8080 est géré
    # par docker-compose (ports: "8080:80").
    server_name 127.0.0.1 localhost;

    # ============================================
    # Point d'entrée : CMF/public/
    # Nginx sert les fichiers depuis ce dossier.
    # Symfony place son index.php dans /public/
    # ============================================
    # root /var/www/html/public;
    root /app/public;

    # Fichier par défaut à servir
    index index.php;

    # ============================================
    # Règle principale de routage Symfony
    # Toutes les URLs qui ne correspondent pas
    # à un fichier/dossier réel sont redirigées
    # vers index.php (le Front Controller Symfony)
    # ============================================
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # ============================================
    # Traitement des fichiers PHP
    # Nginx transmet les requêtes .php à PHP-FPM
    # via le protocole FastCGI sur le port 9000.
    # "php" = nom du service dans docker-compose.yml
    # ============================================
    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;

        # Sécurité : évite l'exécution de PHP
        # dans des chemins non existants
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # ============================================
    # Fichiers statiques (CSS, JS, images)
    # Servis directement par Nginx sans passer par PHP.
    # expires 1y = mis en cache 1 an par le navigateur
    # ============================================
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|woff|woff2|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # ============================================
    # Sécurité : interdit l'accès aux fichiers cachés
    # Ex : .env, .git, .htaccess
    # ============================================
    location ~ /\. {
        deny all;
        return 404;
    }

    # Logs d'erreur et d'accès Nginx
    error_log  /var/log/nginx/cmf_error.log;
    access_log /var/log/nginx/cmf_access.log;
}
```

---

## 5️⃣ `.env` — Variables d'environnement

```bash name=.env
# ============================================
# 🗄️ CONFIGURATION MARIADB
# Ces variables sont lues par docker-compose.yml
# pour initialiser le conteneur MariaDB.
# ============================================

# Mot de passe du super-utilisateur root MariaDB
MYSQL_ROOT_PASSWORD=root_secret

# Nom de la base de données créée automatiquement
MYSQL_DATABASE=cmf_webinar

# Utilisateur applicatif (utilisé par Symfony)
MYSQL_USER=cmf_user

# Mot de passe de l'utilisateur applicatif
MYSQL_PASSWORD=cmf_secret

# ============================================
# 🔗 URL DE CONNEXION DOCTRINE (Symfony)
# 127.0.0.1 depuis ta machine hôte = port 3306 exposé
# MAIS dans Docker, Symfony tourne dans le conteneur PHP
# qui communique avec "database" (nom du service Docker).
# On utilise donc "database" comme hôte, pas 127.0.0.1.
#
# ⚠️ Si tu te connectes depuis un outil EXTERNE
# à Docker (TablePlus, DBeaver...) → utilise 127.0.0.1:3306
# ============================================
DATABASE_URL="mysql://cmf_user:cmf_secret@database:3306/cmf_webinar?serverVersion=8.0&charset=utf8mb4"

# ============================================
# 🔧 ENVIRONNEMENT SYMFONY
# dev   = mode développement (debug activé, logs détaillés)
# prod  = mode production (performances optimisées)
# ============================================
APP_ENV=dev
APP_SECRET=change_me_with_a_random_32char_string
```

---

## 🚀 Mise en place complète — Étapes

### Étape 1 — Construire et démarrer Docker
```bash name=terminal
cd CMF/
docker compose up -d --build
```

### Étape 2 — Installer Symfony 7.4 dans le conteneur
```bash name=terminal
# Entrer dans le conteneur PHP
docker exec -it cmf_php bash

# Installer Symfony 7.4 directement dans /var/www/html (= CMF/)
composer create-project symfony/skeleton:"7.4.*" .

# Installer les bundles nécessaires au Webinaire
composer require symfony/webapp-pack
composer require doctrine/doctrine-bundle doctrine/orm
composer require guzzlehttp/guzzle
composer require symfony/mailer
```

### Étape 3 — Créer la base de données
```bash name=terminal
# Toujours dans le conteneur
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Étape 4 — Vérifier que tout fonctionne
```bash name=terminal
# Quitter le conteneur
exit

# Vérifier l'état des conteneurs
docker compose ps
```

---

## 🌐 Accès aux services

| Service | URL | Identifiants |
|---|---|---|
| 🌐 **Site Symfony** | http://127.0.0.1:8080 | — |
| 🛠️ **phpMyAdmin** | http://127.0.0.1:8081 | root / root_secret |
| 🗄️ **MySQL (externe)** | 127.0.0.1:3306 | cmf_user / cmf_secret |

---

## 📊 Schéma final du réseau Docker

```
Ta machine (hôte)
│
├── 127.0.0.1:8080  ──→  [Nginx :80]  ──→  [PHP-FPM :9000]
│                              │                    │
│                         Fichiers statiques    Symfony
│                         CMF/public/           CMF/public/index.php
│
├── 127.0.0.1:8081  ──→  [phpMyAdmin :80]  ──→  [MariaDB :3306]
│
└── 127.0.0.1:3306  ──→  [MariaDB :3306]
                          (accès direct outils DB)
```

> 💡 **Résumé des points clés** :
> - **Nginx** est indispensable avec PHP-FPM, il reçoit les requêtes HTTP et délègue le PHP
> - **`root /var/www/html/public`** dans Nginx pointe directement vers `CMF/public/index.php`
> - **`DATABASE_URL`** utilise `database` (nom du service Docker) et **non** `127.0.0.1`, car Symfony tourne **à l'intérieur** du réseau Docker
> - **`127.0.0.1`** est utilisé uniquement pour accéder aux services **depuis ta machine** (navigateur, outils BDD)