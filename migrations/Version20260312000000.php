<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration initiale : création des tables pour les entités
 * Webinar, Metier, Inscription et Commentaire.
 */
final class Version20260312000000 extends AbstractMigration
{
    /**
     * Description de cette migration.
     */
    public function getDescription(): string
    {
        return 'Création des tables webinar, metier, inscription et commentaire.';
    }

    /**
     * Applique la migration : création des tables.
     */
    public function up(Schema $schema): void
    {
        // Création de la table des métiers
        $this->addSql('CREATE TABLE metier (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            secteur VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table des webinars
        $this->addSql('CREATE TABLE webinar (
            id INT AUTO_INCREMENT NOT NULL,
            titre VARCHAR(255) NOT NULL,
            metier VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            date_debut DATETIME NOT NULL,
            video_url VARCHAR(500) DEFAULT NULL,
            image VARCHAR(500) DEFAULT NULL,
            est_actif TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table des inscriptions
        $this->addSql('CREATE TABLE inscription (
            id INT AUTO_INCREMENT NOT NULL,
            webinar_id INT DEFAULT NULL,
            nom VARCHAR(255) NOT NULL,
            prenom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            ecole VARCHAR(255) DEFAULT NULL,
            region VARCHAR(255) DEFAULT NULL,
            metier_recherche VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            INDEX IDX_5E90F6D68C93EB6 (webinar_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table des commentaires
        $this->addSql('CREATE TABLE commentaire (
            id INT AUTO_INCREMENT NOT NULL,
            webinar_id INT NOT NULL,
            auteur VARCHAR(255) NOT NULL,
            contenu LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX IDX_67F068BC8C93EB6 (webinar_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Ajout des clés étrangères
        $this->addSql('ALTER TABLE inscription
            ADD CONSTRAINT FK_5E90F6D68C93EB6
            FOREIGN KEY (webinar_id) REFERENCES webinar (id)');

        $this->addSql('ALTER TABLE commentaire
            ADD CONSTRAINT FK_67F068BC8C93EB6
            FOREIGN KEY (webinar_id) REFERENCES webinar (id)');
    }

    /**
     * Annule la migration : suppression des tables dans l'ordre inverse.
     */
    public function down(Schema $schema): void
    {
        // Suppression des clés étrangères puis des tables
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D68C93EB6');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8C93EB6');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE webinar');
        $this->addSql('DROP TABLE metier');
    }
}
