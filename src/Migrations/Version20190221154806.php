<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190221154806 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conge (id_conge INT AUTO_INCREMENT NOT NULL, id_collabo INT NOT NULL, type VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, duree INT NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id_conge)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE conge ADD CONSTRAINT foreignIdConge FOREIGN KEY (id_collabo) REFERENCES collaborateur(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
		// $this->addSql('ALTER TABLE collaborateur ADD conge INT NOT NULL');
		// $this->addSql('ALTER TABLE collaborateur ADD rtt INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE pops1819.conge DROP FOREIGN KEY foreignIdConge');
        $this->addSql('DROP TABLE conge');
		// $this->addSql('ALTER TABLE collaborateur DROP conge');
		// $this->addSql('ALTER TABLE collaborateur DROP rtt');
    }
}
