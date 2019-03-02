<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209101816 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_de_frais ADD note_id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_de_frais ADD CONSTRAINT FK_A92D02FB26ED0855 FOREIGN KEY (note_id) REFERENCES note_de_frais (id)');
        $this->addSql('CREATE INDEX IDX_A92D02FB26ED0855 ON ligne_de_frais (note_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_de_frais DROP FOREIGN KEY FK_A92D02FB26ED0855');
        $this->addSql('DROP INDEX IDX_A92D02FB26ED0855 ON ligne_de_frais');
        $this->addSql('ALTER TABLE ligne_de_frais DROP note_id');
    }
}
