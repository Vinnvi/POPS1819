<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190213165003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_de_frais ADD last_modif DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE note_de_frais ADD last_modif DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE background_pic_path');
        $this->addSql('DROP TABLE collaborateur_projet');
        $this->addSql('DROP TABLE profile_page');
        $this->addSql('ALTER TABLE collaborateur DROP background_pic_path, CHANGE salt salt VARCHAR(128) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE ligne_de_frais DROP last_modif');
        $this->addSql('ALTER TABLE note_de_frais DROP last_modif');
        $this->addSql('ALTER TABLE notification DROP personnel, DROP cumulable, DROP vu');
    }
}
