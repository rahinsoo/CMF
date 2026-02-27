# CMF

## Description courte du site :

> Plateforme de webinars dédiée à l’orientation, à l’emploi et à la formation.

> Elle permet aux entreprises, écoles et partenaires de présenter leurs métiers, leurs besoins en recrutement et leurs formations à un public de jeunes, d’étudiants et de personnes en reconversion.

> Les utilisateurs accèdent à des webinars thématiques, généralistes ou territoriaux, avec des connexions aux ENT et à des outils emploi comme Cjob.

> La plateforme est évolutive et s’intègre dans des packs de communication payants pour les acteurs qui souhaitent gagner en visibilité et attirer de nouveaux profils.

## Description détaillée du site :

Application web complète de gestion de Webinar créer par des entreprises. Il est

Site permettant de créer des Webinar et de les animer (pour les Company … puis School / Partner). Avec un public à la recherche d’infos sur les entreprises et métiers (dans un 1et temps) puis pour un public à la recherche d’infos sur les établissements et les formations, ainsi que des webinar “généralistes” (comment faire son CV …) ou encore sur des thématiques (travailler en Bretagne, travailler dans le secteur du commerce, se former en Occitanie, se former dans un IAE …)

C’est un site évolutif avec des rajouts de thématiques sur les Webinar, ainsi que des rubriques et outils

Les objectifs :
faire un lien avec les ENT (Pronote et Ypareo) afin d’apporter un large public de jeunes s’inscrivant aux webinars.
créer des liens avec Cjob, notamment avec l’inscription de UserPerso.
intégrer les webinars dans le Pack Communication (Payant)


### 🔷 Présentation du projet

Le projet consiste à créer une **plateforme de webinars dédiée à l’orientation, à l’emploi et à la formation**, à destination de plusieurs publics :

* des personnes en reconversion professionnelle ou en recherche d’emploi
* des étudiants et jeunes en formation
* des entreprises, établissements de formation et partenaires souhaitant présenter leurs métiers, formations ou besoins spécifiques en recrutement

La plateforme permettra aux **entreprises, écoles et partenaires** d’organiser et d’animer facilement des webinars thématiques, tout en offrant au public un accès simple à des contenus utiles pour construire ou faire évoluer leur parcours professionnel.

---

### 🔷 Types de webinars proposés

Dans un premier temps, la plateforme sera orientée vers :

* la **découverte des entreprises et des métiers**
* les besoins en recrutement et les nouveaux profils recherchés

Puis, progressivement, elle intégrera :

* des webinars dédiés aux **établissements de formation** et aux **parcours pédagogiques**
* des webinars **généralistes** (rédiger un CV, préparer un entretien, se reconvertir, choisir une formation…)
* des webinars **thématiques ou territoriaux**, par exemple :

    * Travailler en Bretagne
    * Travailler dans le secteur du commerce
    * Se former en Occitanie
    * Se former dans un IAE

La plateforme est pensée comme un **outil évolutif**, permettant d’ajouter de nouvelles thématiques, rubriques et fonctionnalités au fil du temps.

---

### 🔷 Fonctionnalités et intégrations clés

Le site intégrera plusieurs connexions stratégiques :

* un lien avec les **ENT** (Pronote, Ypareo) afin de faciliter l’inscription des jeunes aux webinars
* une connexion avec **Cjob**, notamment via l’inscription des utilisateurs disposant d’un profil personnel (UserPerso)
* l’intégration des webinars dans un **Pack Communication payant**, destiné aux entreprises et partenaires souhaitant valoriser leur marque, leurs métiers ou leurs formations

---

### 🔷 Objectifs du projet

* Mettre en relation les **publics en recherche d’orientation ou d’emploi** avec les acteurs du monde professionnel et de la formation
* Offrir aux entreprises et établissements un **outil simple et efficace de communication et de recrutement**
* Centraliser des contenus utiles autour de l’emploi, de la formation et des territoires
* Développer un modèle économique basé sur des **offres de visibilité et de communication**

---

### 💡 1. Parcours personnalisé

Après inscription :

* recommandation automatique de webinars selon :

    * âge / statut (étudiant, demandeur d’emploi, salarié)
    * secteur d’intérêt
    * région

---

### 💡 2. Replay + contenus enrichis

* accès aux **replays**
* fiches métiers / formations associées
* liens directs vers offres, formations ou candidatures

---

### 💡 3. Label ou badge partenaire

Créer des statuts :

* Entreprise partenaire
* École partenaire
* Territoire partenaire

👉 Très utile pour le Pack Communication et la crédibilité du site.

---

### 💡 4. Webinars “co-animés”

* entreprise + école
* entreprise + territoire
* école + ancien étudiant

👉 Très différenciant et très apprécié des jeunes.


## UML - Looping

### Merise

- image de la Merise

  ![La vue de la BDD en Merise](Diagrammes/CmF_bdd_V0.2.png "Dessin merise")

- image de la Merise V0.3

  ![La vue de la BDD en Merise](Diagrammes/CmF_bdd_V0.3.png "Dessin merise")

### Diagramme de classe

- image du Diagramme de classe

  ![La vue de la BDD en Diagramme de classe](Diagrammes/CmF_bdd_V0.2_UML.png "Diagramme de classe")

- image du Diagramme de classe V0.3

![La vue de la BDD en Diagramme de classe](Diagrammes/CmF_bdd_V0.3_UML.png "Diagramme de classe")

**Voici une explication de chaque entité du diagramme UML :**

**User & user_role**  
Le cœur du système. Un utilisateur possède un rôle (admin, présentateur, etc.). Il a ses informations de contact, peut créer ou participer à des webinars, et peut nécessiter une validation pour y participer (`NeedValidationForWebinar`).

**Webinar**  
Représente un événement en ligne avec un titre, une date, une durée, et une clé d'accès. Un webinar peut avoir plusieurs présentateurs (Users) et plusieurs inscrits.

**Inscrits_Webinar**  
Table de liaison entre les webinars et les participants. Elle stocke les informations du participant (mail, nom, prénom, type, téléphone) au moment de l'inscription, ainsi que son école d'origine (`Id_school`).

**Prospect_Company**  
Représente des entreprises prospects intéressées par les webinars ou formations. Elle contient les coordonnées du contact, le nom de l'entreprise, la tranche d'effectif, et si c'est bien un prospect actif.

**Prospect_School**  
Similaire mais pour les établissements scolaires. Elle précise les types de formations proposées (initiale, alternance, continue) et le contact référent de l'école.

**School**  
Référentiel simple des écoles partenaires (id + nom). Elle est liée aux inscrits et aux prospects école.

**Referentiel_Metier**  
Liste les métiers et fonctions disponibles, utilisée pour qualifier les prospects et inscrits.

**Referentiel_Secteur**  
Liste les secteurs d'activité, rattachés aux prospects entreprise.

**Referentiel_Region**  
Liste des régions géographiques, utilisée pour localiser les prospects école.

**Referentiel_ENT**  
Référentiel des ENT (Environnements Numériques de Travail) avec un fichier XLS associé, lié aux écoles.

**Referentiel_ETP**  
Référentiel des tranches d'effectif (ETP = Équivalent Temps Plein), lié aux prospects entreprise.

---

Voici l'explication de chaque lien (association) du diagramme :

**attribuer** — `user_role` → `User` (1 à 1..*)
Un rôle permet à un ou plusieurs utilisateurs d'exister dans le système. Chaque utilisateur a exactement un rôle.

**Presenter** — `User` ↔ `Webinar` (* à 1..*)
Un utilisateur peut présenter plusieurs webinars, et un webinar peut avoir plusieurs présentateurs.

**SuivreEntreprise** — `Webinar` ↔ `Prospect_Company` (* à 1..*)
Ce lien semble représenter la **participation** d'un utilisateur interne à un webinar, distinct du rôle de présentateur. Je te suggère de le renommer **"Participer"**.

**Assister** — `Webinar` ↔ `Inscrits_Webinar` (1..* à *)
Un webinar peut avoir plusieurs inscrits, et une inscription est liée à un webinar.

**LienMétier** — `Inscrits_Webinar` ↔ `Referentiel_Metier` (1..* à 1)
Un inscrit est associé à un métier du référentiel.

**ÊtreDansSecteur   ** — `Prospect_Company` ↔ `Referentiel_Secteur` (1..* à 1)
Une entreprise prospect appartient à un secteur d'activité.

**ConcernerMétier** — `Prospect_Company` ↔ `Referentiel_Metier` (1..* à 1)
Une entreprise prospect est associée à un ou plusieurs métiers.

**VenirDe** — `Inscrits_Webinar` ↔ `Referentiel_Region` (1..* à 1..*)
Lie les inscrits à une école prospect.

**AppartenirÉcole** — `Inscrits_Webinar` ↔ `School` (1..* à 1)
Lie un inscrit à son école dans le référentiel School.

**UtiliserENT** — `Referentiel_ENT` ↔ `Prospect_School` (1 à 1..*)
Un ENT peut être associé à plusieurs écoles prospects.

**LocaliserDans** — `Referentiel_Region` ↔ `Prospect_School` (1 à 1..*)
Une région est associée à plusieurs écoles prospects.

**DestinerA** — `Referentiel_Region` ↔ `Webinar` (1 à 1..*)
Un webinar est ciblé ou rattaché à une région.

**ReprésentersEntreprise** — `User` ↔ `Prospect_Company` (1..* à 1)
Un utilisateur est rattaché à une entreprise prospect.

**AvoirEffectif** — `Prospect_Company` ↔ `Referentiel_ETP` (1..* à 1)
Une entreprise prospect a une tranche d'effectif.

### BDD SQL

- image de la version ou code

  ![La vue de la BDD en SQL](Diagrammes/CmF_bdd_V0.2_MLD.png "Dessin SQL")

## Première verion graphique Evolution du Wareframe (Figma)

- image de la version proposée

  ![Première verion graphique](img/CMF_desktop_design_V0.0.jpg "Première verion graphique")

- Proposition V0.1 de la version desktop

  ![V0.1 de la version desktop](img/CMF_desktop_design_V0.1.png "V0.1 de la version desktop")

- Proposition V0.1 de la version smartphone

  ![V0.1 de la version smartphone](img/CMF_smartphone_design_V0.1.png "V0.1 de la version smartphone")

---

## Recherche Discord

### Inscription obligatoire ?

Pour participer à un webinar sur Discord, une personne doit :

1. Créer un compte Discord

2. Rejoindre ton serveur via un lien d’invitation

3. Avoir l’application ou utiliser la version web

⚠️ Certaines personnes (surtout +30 ans ou entreprises traditionnelles) peuvent bloquer à cette étape.

### Est-ce un problème ?

✅ Si ta cible est :

- Étudiants
- Jeunes diplômés
- Profils tech / gaming

👉 Discord est parfait.

❌ Si ta cible est :

- Profils en reconversion 35–50 ans
- Entreprises industrielles
- RH traditionnels

👉 Discord peut devenir un frein.

## 🔹 STRUCTURE IDÉALE DU SERVEUR

### Catégorie 1 : Accueil

* #bienvenue
* #règles
* #comment-participer

### Catégorie 2 : Webinars en cours

Pour chaque webinar :

* 🎤 vocal : "Webinar – Développeur Web"
* 💬 salon texte : "questions-webinar-dev"

### Catégorie 3 : Replay

* #replay-dev
* #replay-marketing

### Catégorie 4 : Communauté

* #entraide
* #opportunités
* #discussions-métiers

---

## 4️⃣ Gestion des rôles (très important)

Discord fonctionne avec des **rôles**.

Tu dois créer :

* 🎙️ Présentateur
* 🛡️ Modérateur
* 👥 Participant
* 🏢 Entreprise

Les permissions :

| Rôle         | Parler         | Partager écran | Modérer |
| ------------ | -------------- | -------------- | ------- |
| Présentateur | Oui            | Oui            | Non     |
| Modérateur   | Oui            | Oui            | Oui     |
| Participant  | Non (sauf Q&A) | Non            | Non     |

Ça évite le chaos.

---

## 5️⃣ Le vrai problème : l’automatisation

Si ton site donne un lien Discord brut, tu perds :

* Le suivi des inscrits
* La donnée
* Le contrôle
* L’expérience premium

### Solution propre 👇

1. L’utilisateur s’insit sur ton site
2. Il reçoit un email avec :

* Lien Discord
* Tutoriel rapide
3. Sur Discord :

* Un bot attribue automatiquement le rôle "Participant"

---

## 6️⃣ Dois-tu obliger Discord ?

👉 Pas forcément.

Tu as 3 options stratégiques :

---

### OPTION A – 100% Discord

Avantages :

* Gratuit
* Communauté forte
* Interaction fluide

Inconvénients :

* Friction
* Image moins "pro"

---

### OPTION B – Webinar sur Zoom / Livestorm + Discord communauté

Structure :

* Webinar principal sur Zoom
* Discord pour échanges après

👉 Beaucoup plus rassurant pour entreprises.

---

### OPTION C – Tu crées une plateforme hybride (plus avancé)

* Inscription sur ton site
* Accès streaming intégré
* Chat intégré
* Discord en complément

Mais ça demande développement.

---

## Conclusion

- Discord demande forcément une inscription et pour les professionnels et pour les inscrits
- Donc pour créer le Webinar les pros doivent être connecté et créent et gère le webinar seulement depuis l'app discord.
- les inscrits doivent être connecté à discord pour pouvoir voir le Webinar

## Test de l'option C avec Symfony et

## Préparation du dossier de travail

### Choix des services

Utilisation :

- HTLM/CSS/JS
- php (8.2)
- MySQL
- API Discord

Pratique :

- Symfony (7.4 LTS)
- Docker (php, phpmyadmin, MySQL, volumes)

### Préparation de l'installation et utilisation via Docker

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

### Utilisation de serveur en local qui sera sur la machine virtuel chez OVH