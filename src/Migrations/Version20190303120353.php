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

        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY conge_ibfk_1');
        $this->addSql('DROP INDEX foreignIdConge ON conge');
        $this->addSql('ALTER TABLE conge DROP id_collabo, DROP id_service');
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
        $this->addSql('ALTER TABLE conge DROP service_id, DROP collabo_id');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT conge_ibfk_1 FOREIGN KEY (id_collabo) REFERENCES collaborateur (id)');
    }
}
