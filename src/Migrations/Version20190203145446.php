<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203145446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborateur_service (collaborateur_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_C6A2D320A848E3B1 (collaborateur_id), INDEX IDX_C6A2D320ED5CA9E6 (service_id), PRIMARY KEY(collaborateur_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, date DATETIME NOT NULL, test VARCHAR(255) NOT NULL, INDEX IDX_BF5476CAA848E3B1 (collaborateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateur_service ADD CONSTRAINT FK_C6A2D320A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur_service ADD CONSTRAINT FK_C6A2D320ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE collaborateur ADD service_id INT NOT NULL, ADD email VARCHAR(1024) NOT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur (service_id)');
        $this->addSql('ALTER TABLE ligne_de_frais ADD justificatif VARCHAR(512) DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_de_frais ADD CONSTRAINT FK_A92D02FBC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9ED5CA9E6 ON projet (service_id)');
        $this->addSql('ALTER TABLE service ADD chef_id INT NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2150A48F1 FOREIGN KEY (chef_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2150A48F1 ON service (chef_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collaborateur_service');
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3ED5CA9E6');
        $this->addSql('DROP INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur DROP service_id, DROP email');
        $this->addSql('ALTER TABLE ligne_de_frais DROP FOREIGN KEY FK_A92D02FBC18272');
        $this->addSql('ALTER TABLE ligne_de_frais DROP justificatif');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9ED5CA9E6');
        $this->addSql('DROP INDEX IDX_50159CA9ED5CA9E6 ON projet');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2150A48F1');
        $this->addSql('DROP INDEX IDX_E19D9AD2150A48F1 ON service');
        $this->addSql('ALTER TABLE service DROP chef_id');
    }
}
