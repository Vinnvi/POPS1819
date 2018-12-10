<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209160634 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service ADD chef_id INT NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2150A48F1 FOREIGN KEY (chef_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2150A48F1 ON service (chef_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2150A48F1');
        $this->addSql('DROP INDEX IDX_E19D9AD2150A48F1 ON service');
        $this->addSql('ALTER TABLE service DROP chef_id');
    }
}
