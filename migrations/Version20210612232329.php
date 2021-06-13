<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210612232329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD id_ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816F7E4ECA3 FOREIGN KEY (id_ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_C35F0816F7E4ECA3 ON adresse (id_ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816F7E4ECA3');
        $this->addSql('DROP INDEX IDX_C35F0816F7E4ECA3 ON adresse');
        $this->addSql('ALTER TABLE adresse DROP id_ville_id');
    }
}
