Bonjour mon étudiant ! C'est une excellente question. Vous voulez ajouter un service Jitsi Meet à votre stack Symfony existante. C'est un exercice passionnant car vous allez faire communiquer deux mondes : votre application métier (Symfony) et un outil de visioconférence (Jitsi).

Votre `Dockerfile` et `docker-compose.yml` actuels sont très bien configurés pour Symfony. L'idée n'est pas de les "modifier" profondément, mais plutôt de les **étendre** pour y intégrer Jitsi. C'est une approche modulaire et propre.

Voici une explication complète, étape par étape, pour vous guider.

---

### 1. Analyse de votre configuration actuelle

Votre `docker-compose.yml` définit un réseau appelé `app_network`. Tous vos services (PHP, Nginx, MySQL, phpMyAdmin) sont sur ce réseau et peuvent communiquer entre eux en utilisant leur nom de service (ex: `php`, `database`).

**Le principe** : Nous allons ajouter les services Jitsi (web, prosody, jicofo, jvb) sur ce même réseau. Ainsi, votre application Symfony pourra communiquer avec le service `web` de Jitsi, et vice-versa. C'est comme ajouter un nouvel invité à une fête dans une maison : tout le monde est au même endroit et peut se parler.

### 2. Ajout de Jitsi Meet à votre stack Docker

Nous allons nous baser sur la configuration officielle de Jitsi. Comme le préconise la documentation , nous allons utiliser le dépôt officiel `docker-jitsi-meet` comme source d'inspiration, mais nous adapterons les fichiers pour les fusionner avec les vôtres.

#### a. Fichiers à ajouter

Vous devez ajouter les fichiers suivants à la racine de votre projet (au même niveau que votre `docker-compose.yml` actuel) :

1.  **Un nouveau fichier : `docker-compose.jitsi.yml`**
    Ce fichier contiendra la définition des services Jitsi. Nous ne toucherons pas à votre `docker-compose.yml` principal pour garder les choses organisées.

2.  **Un fichier : `.env.jitsi`**
    Ce fichier contiendra toutes les variables de configuration pour Jitsi. C'est une bonne pratique de séparer la configuration de Symfony (dans `.env`) de celle de Jitsi.

#### b. Le contenu de `docker-compose.jitsi.yml`

Voici le contenu à copier. Je l'ai adapté pour qu'il utilise votre réseau `app_network`.

```yaml
# docker-compose.jitsi.yml
services:
  # Serveur XMPP (Prosody) - Le coeur de la signalisation
  prosody:
    image: jitsi/prosody:stable
    container_name: cmf_jitsi_prosody
    restart: unless-stopped
    networks:
      - app_network
    volumes:
      - ~/.jitsi-meet-cfg/prosody/config:/config:Z
      - ~/.jitsi-meet-cfg/prosody/prosody-plugins-custom:/prosody-plugins-custom:Z
    environment:
      - TZ=${TZ:-UTC}
      - XMPP_DOMAIN=${XMPP_DOMAIN:-meet.jitsi}
      - XMPP_AUTH_DOMAIN=${XMPP_AUTH_DOMAIN:-auth.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_INTERNAL_MUC_DOMAIN=${XMPP_INTERNAL_MUC_DOMAIN:-internal-muc.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_MUC_DOMAIN=${XMPP_MUC_DOMAIN:-muc.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_RECORDER_DOMAIN=${XMPP_RECORDER_DOMAIN:-recorder.${XMPP_DOMAIN:-meet.jitsi}}
      - JICOFO_AUTH_PASSWORD=${JICOFO_AUTH_PASSWORD}
      - JVB_AUTH_PASSWORD=${JVB_AUTH_PASSWORD}
      - JIGASI_XMPP_PASSWORD=${JIGASI_XMPP_PASSWORD:-}
      - JIBRI_XMPP_PASSWORD=${JIBRI_XMPP_PASSWORD:-}
      - JIBRI_RECORDER_PASSWORD=${JIBRI_RECORDER_PASSWORD:-}
    labels:
      - "traefik.enable=false" # On désactive si vous utilisez Traefik

  # Focus Component (Jicofo) - L'orchestrateur de la conférence
  jicofo:
    image: jitsi/jicofo:stable
    container_name: cmf_jitsi_jicofo
    restart: unless-stopped
    networks:
      - app_network
    depends_on:
      - prosody
    environment:
      - TZ=${TZ:-UTC}
      - XMPP_DOMAIN=${XMPP_DOMAIN:-meet.jitsi}
      - XMPP_AUTH_DOMAIN=${XMPP_AUTH_DOMAIN:-auth.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_INTERNAL_MUC_DOMAIN=${XMPP_INTERNAL_MUC_DOMAIN:-internal-muc.${XMPP_DOMAIN:-meet.jitsi}}
      - JICOFO_AUTH_PASSWORD=${JICOFO_AUTH_PASSWORD}
      - BRIDGE_SELECTION_STRATEGY=${BRIDGE_SELECTION_STRATEGY:-SplitBridgeSelectionStrategy}

  # Jitsi Videobridge (JVB) - Le routeur vidéo
  jvb:
    image: jitsi/jvb:stable
    container_name: cmf_jitsi_jvb
    restart: unless-stopped
    ports:
      - "10000:10000/udp" # Port UDP pour la vidéo
    networks:
      - app_network
    depends_on:
      - prosody
    environment:
      - TZ=${TZ:-UTC}
      - XMPP_DOMAIN=${XMPP_DOMAIN:-meet.jitsi}
      - XMPP_AUTH_DOMAIN=${XMPP_AUTH_DOMAIN:-auth.${XMPP_DOMAIN:-meet.jitsi}}
      - JVB_AUTH_PASSWORD=${JVB_AUTH_PASSWORD}
      - DOCKER_HOST_ADDRESS=${JVB_ADVERTISE_IPS}
      - JVB_ADVERTISE_IPS=${JVB_ADVERTISE_IPS}
      - JVB_BREWERY_MUC=${JVB_BREWERY_MUC:-jvbbrewery}
      - JVB_PORT=10000
      - JVB_TCP_HARVESTER_DISABLED=${JVB_TCP_HARVESTER_DISABLED:-true}
      - JVB_TCP_MAPPED_PORT=${JVB_TCP_MAPPED_PORT:-4443}

  # Interface Web de Jitsi (Nginx)
  web:
    image: jitsi/web:stable
    container_name: cmf_jitsi_web
    restart: unless-stopped
    networks:
      - app_network
    depends_on:
      - prosody
      - jicofo
      - jvb
    ports:
      - "8082:80" # Port HTTP pour Jitsi, accessible via localhost:8082
      - "8443:443" # Port HTTPS pour Jitsi, si vous activez SSL
    volumes:
      - ~/.jitsi-meet-cfg/web:/config:Z
    environment:
      - TZ=${TZ:-UTC}
      - XMPP_DOMAIN=${XMPP_DOMAIN:-meet.jitsi}
      - XMPP_AUTH_DOMAIN=${XMPP_AUTH_DOMAIN:-auth.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_INTERNAL_MUC_DOMAIN=${XMPP_INTERNAL_MUC_DOMAIN:-internal-muc.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_MUC_DOMAIN=${XMPP_MUC_DOMAIN:-muc.${XMPP_DOMAIN:-meet.jitsi}}
      - XMPP_RECORDER_DOMAIN=${XMPP_RECORDER_DOMAIN:-recorder.${XMPP_DOMAIN:-meet.jitsi}}
      - PUBLIC_URL=${PUBLIC_URL:-http://localhost:8082}
      - JICOFO_AUTH_PASSWORD=${JICOFO_AUTH_PASSWORD}
      - JVB_AUTH_PASSWORD=${JVB_AUTH_PASSWORD}
```

#### c. Le contenu du fichier `.env.jitsi`

Créez un fichier `.env.jitsi` à la racine. Voici un exemple de base à configurer :

```bash
# .env.jitsi
# Fuseau horaire
TZ=Europe/Paris

# Domaine XMPP interne (ne changez pas si vous ne maîtrisez pas)
XMPP_DOMAIN=meet.jitsi

# IP ou nom de domaine public de votre serveur
# Si vous testez en local, mettez votre IP locale (ex: 192.168.1.10)
# ATTENTION : Ceci est très important pour la vidéo.
JVB_ADVERTISE_IPS=<IP_PUBLIQUE_OU_LOCALE_DE_VOTRE_MACHINE>

# URL publique sous laquelle Jitsi sera accessible
# Cela peut être http://localhost:8082 pour des tests locaux
PUBLIC_URL=http://localhost:8082

# --- Génération des mots de passe (très important !) ---
# Vous devez générer des mots de passe forts. Utilisez la commande :
# openssl rand -hex 16
# Et remplacez les valeurs ci-dessous.
JICOFO_AUTH_PASSWORD=<mot_de_passe_complexe_1>
JVB_AUTH_PASSWORD=<mot_de_passe_complexe_2>
JIGASI_XMPP_PASSWORD=<mot_de_passe_complexe_3> # Optionnel pour Jigasi
JIBRI_XMPP_PASSWORD=<mot_de_passe_complexe_4>  # Optionnel pour Jibri
JIBRI_RECORDER_PASSWORD=<mot_de_passe_complexe_5> # Optionnel pour Jibri
```

**Explication des variables importantes** :
- `JVB_ADVERTISE_IPS` : C'est crucial. JVB doit communiquer son adresse IP aux clients pour le flux vidéo. Si vous êtes en local, mettez l'IP de votre machine sur le réseau local (ex: `192.168.1.10`). Si vous avez un domaine public, mettez-le .
- `PUBLIC_URL` : C'est l'URL que vous utiliserez dans votre navigateur pour accéder à Jitsi. Ici, nous mettons `http://localhost:8082` pour des tests.
- **Les mots de passe** : Ces mots de passe sont utilisés pour sécuriser les communications entre les différents services Jitsi (Prosody, Jicofo, JVB). Ne les laissez pas vides. La documentation officielle insiste sur ce point pour des raisons de sécurité .

### 3. Mise à jour de votre fichier `.env` Symfony

Dans votre fichier `.env` Symfony, vous allez ajouter une variable qui pointe vers votre nouveau service Jitsi.

```dotenv
# .env
# ... vos variables existantes ...

###> jitsi-meet ###
JITSI_URL=http://cmf_jitsi_web:80
###< jitsi-meet ###
```

Pourquoi `http://cmf_jitsi_web:80` ? Parce que dans le réseau Docker `app_network`, votre conteneur `php` peut joindre le conteneur `cmf_jitsi_web` en utilisant son nom. Le port `80` est celui que nous avons exposé en interne pour le service `web` de Jitsi. Ainsi, depuis votre code Symfony (par exemple, pour générer un lien vers une salle de conférence), vous pourrez utiliser cette variable d'environnement.

### 4. Comment lancer et utiliser le tout

1.  **Générez vos mots de passe** pour le fichier `.env.jitsi`. Ouvrez un terminal et exécutez :
    ```bash
    openssl rand -hex 16
    ```
    Faites-le 5 fois pour obtenir 5 mots de passe différents.

2.  **Créez les répertoires de configuration** de Jitsi sur votre machine hôte. Cela permet de persister la configuration et de ne pas la perdre en recréant les conteneurs.
    ```bash
    mkdir -p ~/.jitsi-meet-cfg/{web,transcripts,prosody/config,prosody/prosody-plugins-custom,jicofo,jvb,jigasi,jibri}
    ```

3.  **Lancez toute votre stack**. Depuis la racine de votre projet, exécutez :
    ```bash
    docker compose -f docker-compose.yml -f docker-compose.jitsi.yml --env-file .env.jitsi up -d
    ```
    - `-f docker-compose.yml -f docker-compose.jitsi.yml` : Cela dit à Docker Compose de fusionner vos deux fichiers de configuration.
    - `--env-file .env.jitsi` : On passe le fichier de variables d'environnement spécifique à Jitsi.

4.  **Accédez à votre conférence**. Ouvrez votre navigateur et allez à l'adresse que vous avez mise dans `PUBLIC_URL`, par exemple `http://localhost:8082`. Vous devriez voir l'interface de Jitsi !

### 5. Récapitulatif et points de vigilance

Voici un récapitulatif de ce que nous avons fait et de ce qu'il faut surveiller :

| Élément | Action / Modification | Explication |
| :--- | :--- | :--- |
| **`docker-compose.yml` (Symfony)** | **Aucune modification nécessaire** | Votre fichier actuel est parfait. On l'utilise tel quel. |
| **`docker-compose.jitsi.yml`** | **Création d'un nouveau fichier** | Il définit les services Jitsi et les place sur le réseau `app_network` pour qu'ils puissent communiquer avec Symfony. |
| **`.env.jitsi`** | **Création d'un nouveau fichier** | Il contient la configuration propre à Jitsi (domaines, mots de passe). C'est une séparation claire des responsabilités. |
| **`.env` (Symfony)** | **Ajout de `JITSI_URL`** | Cette variable permet à votre code Symfony de connaître l'URL du frontend de Jitsi. |
| **Ports réseau** | **Ports 8082 et 8443 (hôte) et 10000/udp** | - `8082` : Accès HTTP à Jitsi (pour les tests).<br>- `8443` : Accès HTTPS (si vous configurez Let's Encrypt plus tard).<br>- `10000/udp` : **Critique !** Port UDP pour le flux vidéo. Ouvrez-le dans votre firewall . |
| **`JVB_ADVERTISE_IPS`** | **Valeur obligatoire** | C'est l'IP que les clients utiliseront pour recevoir la vidéo. En local, mettez l'IP de votre machine. |
| **Génération des mots de passe**| **Obligatoire** | Ne démarrez pas les services sans avoir généré ces mots de passe. C'est une mesure de sécurité fondamentale . |

Votre application Symfony et votre serveur Jitsi sont maintenant sur le même réseau Docker. Vous pouvez, par exemple, créer une entité `Conference` dans Symfony qui génère un identifiant de salle unique, puis rediriger l'utilisateur vers `http://localhost:8082/ma-salle-de-conf`. C'est une intégration de base.

N'hésitez pas si vous avez des questions sur la configuration ou l'intégration plus poussée (comme l'authentification unique, par exemple). C'est un beau projet qui vous fera progresser sur la maîtrise de Docker et de l'architecture applicative !
