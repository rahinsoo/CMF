<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Inscription;
use App\Entity\Metier;
use App\Entity\Webinar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Fixtures de démonstration pour le site ChooseMyFutur.
 * Insère des données exemples : métiers, webinars, inscriptions et commentaires.
 *
 * Pour charger les fixtures : php bin/console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    /**
     * Charge toutes les données de démonstration en base de données.
     */
    public function load(ObjectManager $manager): void
    {
        // ===== CRÉATION DES MÉTIERS =====
        $metiers = $this->creerMetiers($manager);

        // ===== CRÉATION DES WEBINARS =====
        $webinars = $this->creerWebinars($manager, $metiers);

        // ===== CRÉATION DES COMMENTAIRES =====
        $this->creerCommentaires($manager, $webinars);

        // ===== CRÉATION DES INSCRIPTIONS =====
        $this->creerInscriptions($manager, $webinars);

        // Enregistre tout en base de données
        $manager->flush();
    }

    /**
     * Crée et persiste les métiers de démonstration.
     *
     * @return Metier[] Liste des métiers créés
     */
    private function creerMetiers(ObjectManager $manager): array
    {
        // Données des métiers : [nom, secteur, description]
        $donneesMetiers = [
            ['Infirmier(e)', 'Santé', 'Professionnel de santé qui soigne et accompagne les patients.'],
            ['Médecin généraliste', 'Santé', 'Praticien de première ligne pour le suivi médical des patients.'],
            ['Architecte', 'BTP / Construction', 'Conçoit et supervise la construction de bâtiments.'],
            ['Développeur web', 'Informatique', 'Crée et maintient des applications et sites web.'],
            ['Data Scientist', 'Informatique', 'Analyse de grandes quantités de données pour en extraire de la valeur.'],
            ['Enseignant(e)', 'Éducation', 'Transmet des savoirs et accompagne les élèves dans leur apprentissage.'],
            ['Avocat(e)', 'Droit', 'Conseille et défend ses clients dans les affaires juridiques.'],
            ['Chef cuisinier', 'Restauration', 'Crée des recettes et dirige une brigade en cuisine.'],
            ['Pompier', 'Sécurité publique', 'Intervient pour lutter contre les incendies et secourir les personnes.'],
            ['Journaliste', 'Médias / Communication', 'Collecte et diffuse l\'information auprès du public.'],
            ['Vétérinaire', 'Santé animale', 'Soigne les animaux et conseille leurs propriétaires.'],
            ['Pilote de ligne', 'Transport aérien', 'Conduit des avions de transport de passagers ou de fret.'],
            ['Psychologue', 'Santé mentale', 'Accompagne les individus dans leurs difficultés psychologiques.'],
            ['Ingénieur civil', 'BTP / Construction', 'Conçoit et supervise des projets d\'infrastructure.'],
            ['Designer graphique', 'Arts / Communication', 'Crée des visuels et identités visuelles pour les marques.'],
            ['Pharmacien(ne)', 'Santé', 'Délivre les médicaments et conseille les patients.'],
            ['Commercial(e)', 'Vente / Commerce', 'Développe le portefeuille clients et réalise des ventes.'],
            ['Comptable', 'Finance / Gestion', 'Gère la comptabilité et les finances d\'une entreprise.'],
            ['Électricien(ne)', 'Artisanat / BTP', 'Installe et entretient les systèmes électriques.'],
            ['Photographe', 'Arts / Médias', 'Réalise des prises de vue artistiques ou professionnelles.'],
        ];

        $listeMetiers = [];

        foreach ($donneesMetiers as [$nom, $secteur, $description]) {
            $metier = new Metier();
            $metier->setNom($nom);
            $metier->setSecteur($secteur);
            $metier->setDescription($description);
            $manager->persist($metier);
            $listeMetiers[] = $metier;
        }

        return $listeMetiers;
    }

    /**
     * Crée et persiste les webinars de démonstration.
     *
     * @param Metier[] $metiers Liste des métiers disponibles
     * @return Webinar[] Liste des webinars créés
     */
    private function creerWebinars(ObjectManager $manager, array $metiers): array
    {
        // Données des webinars : [titre, index du métier, description, décalage en jours, estActif]
        $donneesWebinars = [
            [
                'titre'       => 'Une journée dans la vie d\'une infirmière',
                'metierIndex' => 0,
                'description' => 'Découvrez le quotidien d\'une infirmière en service de réanimation. '
                    . 'Elle vous présentera ses missions, ses responsabilités et sa passion pour ce métier.',
                'joursDecalage' => 5,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
            [
                'titre'       => 'Être développeur web : de la formation à l\'emploi',
                'metierIndex' => 3,
                'description' => 'Un développeur web senior partage son parcours, ses outils favoris '
                    . 'et les opportunités dans ce secteur en pleine croissance.',
                'joursDecalage' => 10,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
            [
                'titre'       => 'L\'architecture : créer les espaces de demain',
                'metierIndex' => 2,
                'description' => 'Une architecte vous présente ses projets, son processus créatif '
                    . 'et les défis du métier dans un monde qui cherche à construire durablement.',
                'joursDecalage' => 15,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
            [
                'titre'       => 'Data Science : exploiter la donnée au service des entreprises',
                'metierIndex' => 4,
                'description' => 'Un Data Scientist vous explique comment les données transforment '
                    . 'les décisions d\'entreprise et vous présente son quotidien.',
                'joursDecalage' => 20,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
            [
                'titre'       => 'Cuisiner avec passion : itinéraire d\'un chef',
                'metierIndex' => 7,
                'description' => 'Un chef étoilé raconte son parcours, de l\'apprentissage aux '
                    . 'brigades de haute gastronomie. Passion, rigueur et créativité au programme.',
                'joursDecalage' => -5,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
            [
                'titre'       => 'Le métier de pompier : héros du quotidien',
                'metierIndex' => 8,
                'description' => 'Un sapeur-pompier professionnel vous présente ses interventions, '
                    . 'sa formation et l\'esprit d\'équipe qui anime ce corps d\'élite.',
                'joursDecalage' => 25,
                'videoUrl'    => null,
                'estActif'    => true,
            ],
        ];

        $listeWebinars = [];

        foreach ($donneesWebinars as $donnee) {
            $webinar = new Webinar();
            $webinar->setTitre($donnee['titre']);
            $webinar->setMetier($metiers[$donnee['metierIndex']]->getNom());
            $webinar->setDescription($donnee['description']);
            $webinar->setVideoUrl($donnee['videoUrl']);
            $webinar->setEstActif($donnee['estActif']);

            // Calcule la date de début en ajoutant les jours de décalage
            $date = new \DateTime();
            $date->modify('+' . $donnee['joursDecalage'] . ' days');
            $date->setTime(18, 0, 0);
            $webinar->setDateDebut($date);

            $manager->persist($webinar);
            $listeWebinars[] = $webinar;
        }

        return $listeWebinars;
    }

    /**
     * Crée et persiste des commentaires de démonstration pour les webinars.
     *
     * @param Webinar[] $webinars Liste des webinars
     */
    private function creerCommentaires(ObjectManager $manager, array $webinars): void
    {
        // Commentaires pour le premier webinar (infirmière)
        $commentairesData = [
            [
                'auteur'  => 'Sophie M.',
                'contenu' => 'Super présentation ! Ça m\'a beaucoup aidée à me décider pour mes études.',
                'webinar' => 0,
            ],
            [
                'auteur'  => 'Lucas B.',
                'contenu' => 'Merci pour ces explications claires sur les conditions de travail.',
                'webinar' => 0,
            ],
            [
                'auteur'  => 'Emma L.',
                'contenu' => 'Est-ce que vous pouvez parler des spécialisations possibles après le diplôme ?',
                'webinar' => 1,
            ],
            [
                'auteur'  => 'Thomas R.',
                'contenu' => 'Le métier de développeur web semble vraiment passionnant !',
                'webinar' => 1,
            ],
        ];

        foreach ($commentairesData as $data) {
            $commentaire = new Commentaire();
            $commentaire->setAuteur($data['auteur']);
            $commentaire->setContenu($data['contenu']);
            $commentaire->setWebinar($webinars[$data['webinar']]);
            $manager->persist($commentaire);
        }
    }

    /**
     * Crée et persiste des inscriptions de démonstration.
     *
     * @param Webinar[] $webinars Liste des webinars
     */
    private function creerInscriptions(ObjectManager $manager, array $webinars): void
    {
        $inscriptionsData = [
            [
                'nom'             => 'Dupont',
                'prenom'          => 'Marie',
                'email'           => 'marie.dupont@lycee.fr',
                'ecole'           => 'Lycée Victor Hugo',
                'region'          => 'Île-de-France',
                'metierRecherche' => 'Infirmier(e)',
                'webinar'         => 0,
            ],
            [
                'nom'             => 'Martin',
                'prenom'          => 'Paul',
                'email'           => 'paul.martin@college.fr',
                'ecole'           => 'Collège Jean Moulin',
                'region'          => 'Auvergne-Rhône-Alpes',
                'metierRecherche' => 'Développeur web',
                'webinar'         => 1,
            ],
        ];

        foreach ($inscriptionsData as $data) {
            $inscription = new Inscription();
            $inscription->setNom($data['nom']);
            $inscription->setPrenom($data['prenom']);
            $inscription->setEmail($data['email']);
            $inscription->setEcole($data['ecole']);
            $inscription->setRegion($data['region']);
            $inscription->setMetierRecherche($data['metierRecherche']);
            $inscription->setWebinar($webinars[$data['webinar']]);
            $manager->persist($inscription);
        }
    }
}
