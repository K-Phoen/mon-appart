<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20150404182313 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE offer ADD url VARCHAR(255)');

        $this->addSql('UPDATE offer SET url = id');

        $this->addSql('ALTER TABLE offer ALTER COLUMN url SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX url_idx ON offer (url)');
    }

    public function postUp(Schema $schema)
    {
        $repo = $this->container->get('repository.offer');
        $em   = $this->container->get('doctrine.orm.default_entity_manager');

        foreach ($repo->findAll() as $offer) {
            $offer->generateUuid();
            $em->persist($offer);
        }
        $em->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX url_idx');
        $this->addSql('ALTER TABLE Offer DROP url');
    }
}
