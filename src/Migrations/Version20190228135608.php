<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190228135608 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE collaborateur_projet (collaborateur_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_6D1DC4D8A848E3B1 (collaborateur_id), INDEX IDX_6D1DC4D8C18272 (projet_id), PRIMARY KEY(collaborateur_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur_projet ADD CONSTRAINT FK_6D1DC4D8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
