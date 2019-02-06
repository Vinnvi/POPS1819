<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203144104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, date DATETIME NOT NULL, test VARCHAR(255) NOT NULL, INDEX IDX_BF5476CAA848E3B1 (collaborateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE collaborateur CHANGE service_id service_id INT NOT NULL, CHANGE email email VARCHAR(1024) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE collaborateur CHANGE service_id service_id INT DEFAULT NULL, CHANGE email email VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
