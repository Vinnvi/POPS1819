<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190224104037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande_avance (id INT AUTO_INCREMENT NOT NULL, collabo_id INT NOT NULL, INDEX IDX_F467F5234BCD09B0 (collabo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_avance_ligne_de_frais (demande_avance_id INT NOT NULL, ligne_de_frais_id INT NOT NULL, INDEX IDX_35583034E657DC7E (demande_avance_id), INDEX IDX_35583034A839675F (ligne_de_frais_id), PRIMARY KEY(demande_avance_id, ligne_de_frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_avance ADD CONSTRAINT FK_F467F5234BCD09B0 FOREIGN KEY (collabo_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE demande_avance_ligne_de_frais ADD CONSTRAINT FK_35583034E657DC7E FOREIGN KEY (demande_avance_id) REFERENCES demande_avance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_avance_ligne_de_frais ADD CONSTRAINT FK_35583034A839675F FOREIGN KEY (ligne_de_frais_id) REFERENCES ligne_de_frais (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande_avance_ligne_de_frais DROP FOREIGN KEY FK_35583034E657DC7E');
        $this->addSql('DROP TABLE demande_avance');
        $this->addSql('DROP TABLE demande_avance_ligne_de_frais');
    }
}
