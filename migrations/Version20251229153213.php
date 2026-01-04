<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229153213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu ADD type_id INT NOT NULL, CHANGE site_web site_web VARCHAR(50) DEFAULT NULL, CHANGE horaires horaires VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_2F577D59C54C8C93 ON lieu (type_id)');
        $this->addSql('ALTER TABLE parcours CHANGE duree_estime duree_estime VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_profil image_profil VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59C54C8C93');
        $this->addSql('DROP INDEX IDX_2F577D59C54C8C93 ON lieu');
        $this->addSql('ALTER TABLE lieu DROP type_id, CHANGE site_web site_web VARCHAR(50) DEFAULT \'NULL\', CHANGE horaires horaires VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE parcours CHANGE duree_estime duree_estime VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE image_profil image_profil VARCHAR(255) DEFAULT \'NULL\'');
    }
}
