<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203132455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborateur_projet (collaborateur_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_6D1DC4D8A848E3B1 (collaborateur_id), INDEX IDX_6D1DC4D8C18272 (projet_id), PRIMARY KEY(collaborateur_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, minute INT NOT NULL, heure INT NOT NULL, jour INT NOT NULL, mois INT NOT NULL, annee INT NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_BF5476CAA848E3B1 (collaborateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE collaborateur CHANGE service_id service_id INT NOT NULL, CHANGE email email VARCHAR(1024) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collaborateur_projet');
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE collaborateur CHANGE service_id service_id INT DEFAULT NULL, CHANGE email email VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
