<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190225210758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE ligne_de_frais DROP FOREIGN KEY FK_A92D02FBE657DC7E');
        //$this->addSql('DROP TABLE demande_avance');
        //$this->addSql('DROP INDEX IDX_A92D02FBE657DC7E ON ligne_de_frais');
        $this->addSql('ALTER TABLE ligne_de_frais ADD type VARCHAR(255) NOT NULL/*, DROP demande_avance_id*/');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande_avance (id INT AUTO_INCREMENT NOT NULL, collabo_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, motif VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_modif DATETIME NOT NULL, INDEX IDX_F467F5234BCD09B0 (collabo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE demande_avance ADD CONSTRAINT FK_F467F5234BCD09B0 FOREIGN KEY (collabo_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE ligne_de_frais ADD demande_avance_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE ligne_de_frais ADD CONSTRAINT FK_A92D02FBE657DC7E FOREIGN KEY (demande_avance_id) REFERENCES demande_avance (id)');
        $this->addSql('CREATE INDEX IDX_A92D02FBE657DC7E ON ligne_de_frais (demande_avance_id)');
    }
}
