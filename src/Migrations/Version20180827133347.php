<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180827133347 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE offer (id VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, area INT NOT NULL, rooms INT NOT NULL, thumb_url VARCHAR(255) NOT NULL, description TEXT NOT NULL, pictures TEXT NOT NULL, including_charges BOOLEAN DEFAULT NULL, is_furnished BOOLEAN NOT NULL, viewed BOOLEAN DEFAULT \'false\' NOT NULL, starred BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX url_idx ON offer (url)');
        $this->addSql('COMMENT ON COLUMN offer.pictures IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE offer');
    }
}
