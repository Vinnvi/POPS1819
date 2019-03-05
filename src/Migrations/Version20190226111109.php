<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226111109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE collaborateur ADD conge INT NOT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD rtt INT NOT NULL');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE collaborateur DROP conge');
        $this->addSql('ALTER TABLE collaborateur DROP rtt');

    }
}
