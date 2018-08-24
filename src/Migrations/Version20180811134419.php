<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180811134419 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE offer (id VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price INTEGER NOT NULL, area INTEGER NOT NULL, rooms INTEGER NOT NULL, thumb_url VARCHAR(255) NOT NULL, description CLOB NOT NULL, pictures CLOB NOT NULL --(DC2Type:array)
        , including_charges BOOLEAN DEFAULT NULL, is_furnished BOOLEAN NOT NULL, viewed BOOLEAN DEFAULT \'0\' NOT NULL, starred BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX url_idx ON offer (url)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE offer');
    }
}
