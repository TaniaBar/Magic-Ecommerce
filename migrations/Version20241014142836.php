<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014142836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60A76ED395');
        $this->addSql('DROP INDEX IDX_D19FA60A76ED395 ON entreprise');
        $this->addSql('ALTER TABLE entreprise ADD nom_titulaire VARCHAR(50) NOT NULL, ADD numero_titulaire VARCHAR(20) NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD user_id INT NOT NULL, DROP nom_titulaire, DROP numero_titulaire, DROP password');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D19FA60A76ED395 ON entreprise (user_id)');
    }
}
