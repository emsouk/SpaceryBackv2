<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320101036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, rue VARCHAR(100) NOT NULL, code_postal VARCHAR(50) NOT NULL, ville VARCHAR(50) NOT NULL, image_lieu VARCHAR(255) NOT NULL, payant TINYINT NOT NULL, creer_le DATETIME NOT NULL, maj_le DATETIME NOT NULL, pays VARCHAR(50) NOT NULL, lat_long DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, site_web VARCHAR(50) DEFAULT NULL, horaires VARCHAR(50) DEFAULT NULL, type_id INT NOT NULL, UNIQUE INDEX UNIQ_2F577D596C6E55B5 (nom), INDEX IDX_2F577D59C54C8C93 (type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description_parcours LONGTEXT NOT NULL, image_parcours VARCHAR(255) NOT NULL, duree_estime VARCHAR(50) DEFAULT NULL, date_creation DATE NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_99B1DEE3FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE parcours_lieu (parcours_id INT NOT NULL, lieu_id INT NOT NULL, INDEX IDX_BF92DB936E38C0DB (parcours_id), INDEX IDX_BF92DB936AB213CC (lieu_id), PRIMARY KEY (parcours_id, lieu_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, date_creation_utilisateur DATE NOT NULL, nom_utilisateur VARCHAR(50) NOT NULL, prenom_utilisateur VARCHAR(50) NOT NULL, email_utilisateur VARCHAR(50) NOT NULL, image_profil VARCHAR(255) DEFAULT NULL, role_id INT NOT NULL, INDEX IDX_1D1C63B3D60322AC (role_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE parcours_lieu ADD CONSTRAINT FK_BF92DB936E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_lieu ADD CONSTRAINT FK_BF92DB936AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59C54C8C93');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE3FB88E14F');
        $this->addSql('ALTER TABLE parcours_lieu DROP FOREIGN KEY FK_BF92DB936E38C0DB');
        $this->addSql('ALTER TABLE parcours_lieu DROP FOREIGN KEY FK_BF92DB936AB213CC');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE parcours');
        $this->addSql('DROP TABLE parcours_lieu');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
