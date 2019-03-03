<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190303120353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		$this->addSql('CREATE TABLE conge (id_conge INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, duree INT NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id_conge)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE conge ADD debut_matin BOOLEAN NOT NULL AFTER date_debut');
		$this->addSql('ALTER TABLE conge ADD fin_matin BOOLEAN NOT NULL AFTER date_fin');
        $this->addSql('ALTER TABLE conge ADD service_id INT DEFAULT NULL, ADD collabo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED893484BCD09B0 FOREIGN KEY (collabo_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_2ED89348ED5CA9E6 ON conge (service_id)');
        $this->addSql('CREATE INDEX IDX_2ED893484BCD09B0 ON conge (collabo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348ED5CA9E6');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED893484BCD09B0');
        $this->addSql('DROP INDEX IDX_2ED89348ED5CA9E6 ON conge');
        $this->addSql('DROP INDEX IDX_2ED893484BCD09B0 ON conge');
		$this->addSql('DROP TABLE conge');
    }
}
