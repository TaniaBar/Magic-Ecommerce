<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014151633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD nom_entreprise VARCHAR(150) NOT NULL, ADD adresse_entreprise VARCHAR(150) NOT NULL, DROP nom, DROP adresse, CHANGE email email_entreprise VARCHAR(254) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD nom VARCHAR(150) NOT NULL, ADD adresse VARCHAR(150) NOT NULL, DROP nom_entreprise, DROP adresse_entreprise, CHANGE email_entreprise email VARCHAR(254) NOT NULL');
    }
}
