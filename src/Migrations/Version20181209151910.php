<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209151910 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborateur ADD service_id INT');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur (service_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3ED5CA9E6');
        $this->addSql('DROP INDEX IDX_770CBCD3ED5CA9E6 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur DROP service_id');
    }
}
