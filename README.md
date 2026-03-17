Dans le cadre de vouloir proposer de nouvelles possibilité vers de nouveaux emplois, je souhaite faire un site de webinar pour que les entreprises présentes leurs besoin et ou des utilisateur cherches un métier (étudiant ou personne souhaitant se reconvertir.

Fais une proposition simple sans base de donnée SQL pour ce premier test.
fonction principales: 
- Création d'un webinar sur un thème.
- Faire participer 50 personnes sur un webinar.

Soit clair dans tes choix et ajoute des explication avec le code.


Utilise les données du repository.
Utilise les fichier joint base_webinar.md et Desktop - Proto Wevinar.png comme base d'exemple.

# mise en place 

- créer défault.conf pour Nginx
- // docker compose yml
- Dockerfile
- initialisation.sh pour préparer docker et installer symfony
- préparer un fichier .env en avance
- déplacer le fichier Symfony dans le dossier mère
- copier evec les bonnes infos le fichier .env (ici les infos de mysql)
>DATABASE_URL="mysql://app:app@database:3306/app?serverVersion=8.0&charset=utf8mb4"
- installer les webapp
- doctrine:database:create

## test sans bdd du mode webinar
-> echec pour le moment 2026-03-10
