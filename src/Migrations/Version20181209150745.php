<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
  final class Version20181209150745 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborateur_service (collaborateur_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_C6A2D320A848E3B1 (collaborateur_id), INDEX IDX_C6A2D320ED5CA9E6 (service_id), PRIMARY KEY(collaborateur_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateur_service ADD CONSTRAINT FK_C6A2D320A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur_service ADD CONSTRAINT FK_C6A2D320ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collaborateur_service');
    }
}
