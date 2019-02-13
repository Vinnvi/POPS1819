<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211204538 extends AbstractMigration
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
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur CHANGE email email VARCHAR(1024) NOT NULL, CHANGE salt salt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ligne_de_frais ADD last_modif DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE note_de_frais ADD last_modif DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collaborateur_projet');
        $this->addSql('ALTER TABLE collaborateur CHANGE service_id service_id INT DEFAULT NULL, CHANGE salt salt VARCHAR(128) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE ligne_de_frais DROP last_modif');
        $this->addSql('ALTER TABLE note_de_frais DROP last_modif');
    }
}
