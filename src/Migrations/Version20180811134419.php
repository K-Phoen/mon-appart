<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180811134419 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE offer (id VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price INTEGER NOT NULL, area INTEGER NOT NULL, rooms INTEGER NOT NULL, thumb_url VARCHAR(255) NOT NULL, description TEXT NOT NULL, pictures TEXT NOT NULL --(DC2Type:array)
        , including_charges BOOLEAN DEFAULT NULL, is_furnished BOOLEAN NOT NULL, viewed BOOLEAN DEFAULT \'0\' NOT NULL, starred BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX url_idx ON offer (url)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE offer');
    }
}
