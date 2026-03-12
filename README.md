# mise en place 

- créer défault.conf pour Nginx
- // docker compose yml
- Dockerfile
- initialisation.sh pour préparer docker et installer symfony
- préparer un fichier .env en avance
- déplacer le fichier Symfony dans le dossier mère
- copier avec les bonnes infos le fichier .env (ici les infos de mysql)
>DATABASE_URL="mysql://app:app@database:3306/app?serverVersion=8.0&charset=utf8mb4"
- installer les webapp
- doctrine:database:create

## test sans bdd du mode webinar
