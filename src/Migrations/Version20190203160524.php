<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203160524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur (service_id)');
        $this->addSql('ALTER TABLE ligne_de_frais ADD justificatif VARCHAR(512) DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_de_frais ADD CONSTRAINT FK_A92D02FBC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE notification DROP test');
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

        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3ED5CA9E6');
        $this->addSql('DROP INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur');
        $this->addSql('ALTER TABLE ligne_de_frais DROP FOREIGN KEY FK_A92D02FBC18272');
        $this->addSql('ALTER TABLE ligne_de_frais DROP justificatif');
        $this->addSql('ALTER TABLE notification ADD test VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9ED5CA9E6');
        $this->addSql('DROP INDEX IDX_50159CA9ED5CA9E6 ON projet');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2150A48F1');
        $this->addSql('DROP INDEX IDX_E19D9AD2150A48F1 ON service');
        $this->addSql('ALTER TABLE service DROP chef_id');
    }
}
