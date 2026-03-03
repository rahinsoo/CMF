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

1. L’utilisateur s’inscrit sur ton site
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
J'ai fait plusieurs essais, voici celui que j'ai choisi.
---

## 📁 Structure des fichiers à créer

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

## 1️⃣ `docker-compose.yml` — Le fichier central

```yaml name=docker-compose.yml
services:

  # ============================================
  # 🐘 PHP 8.4-FPM
  # Exécute le code Symfony.
  # Le dossier racine CMF/ est monté directement
  # dans /var/www/html (pas de sous-dossier Symfony/)
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

> ⚠️ **Note sur PHP 8.5** : PHP 8.5 n'est pas encore sorti en version stable (date de sortie prévue fin 2025/2026). On utilise **PHP 8.4** qui est la dernière version stable. Met à jour quand elle sera disponible.

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

## 3️⃣ `default.conf` — Configuration Nginx

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

## 4️⃣ `.env` — Variables d'environnement

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

## 5️⃣ Installation pas à pas 🚀

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

### Premier test 

> impossible d'installer Symfony directement avec php en docker
> erreur : Project directory "/app/." is not empty.
> j'ai donc tout supprimé : 
```bash
# pour supprimer les container
docker compose down -v
# pour tout supprimer (volumes compris)
docker system prune -a
```

 
### Proposition 1

> Installer symphony en docker comme php.
> je vais donc sur le site de Symphony : https://symfony.com/doc/7.4/setup/docker.html
> je vérifie si j'ai bien installé docker Compose : 
```bash
docker compose version
```
> sinon, installation  (ici une commande Ubuntu et Debian) https://github.com/dunglas/symfony-docker:
```bash
sudo apt-get update
sudo apt-get install docker-compose-plugin
```

```bash
Run docker compose build --pull --no-cache # to build fresh images
Run docker compose up --wait # to set up and start a fresh Symfony project
Open https://localhost # in your favorite web browser and accept the auto-generated TLS certificate
Run docker compose down --remove-orphans # to stop the Docker containers.
```




### Utilisation de serveur en local qui sera sur la machine virtuelle chez OVH