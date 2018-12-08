<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181208194654 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_de_frais ADD collabo_id INT NOT NULL');
        $this->addSql('ALTER TABLE note_de_frais ADD CONSTRAINT FK_E6ECCF534BCD09B0 FOREIGN KEY (collabo_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_E6ECCF534BCD09B0 ON note_de_frais (collabo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_de_frais DROP FOREIGN KEY FK_E6ECCF534BCD09B0');
        $this->addSql('DROP INDEX IDX_E6ECCF534BCD09B0 ON note_de_frais');
        $this->addSql('ALTER TABLE note_de_frais DROP collabo_id');
    }
}
