<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210612134743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, id_gouvernorat_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, code_postal INT NOT NULL, INDEX IDX_C35F081648D1F32B (id_gouvernorat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, id_gouvernorat_id INT DEFAULT NULL, INDEX IDX_43C3D9C348D1F32B (id_gouvernorat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081648D1F32B FOREIGN KEY (id_gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C348D1F32B FOREIGN KEY (id_gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE produit');
        $this->addSql('ALTER TABLE user ADD id_adresse_id INT DEFAULT NULL, ADD etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E86D5C8B FOREIGN KEY (id_adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E86D5C8B ON user (id_adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E86D5C8B');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, age INT DEFAULT NULL, type VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (id INT NOT NULL, nomPr VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, quanttitePr INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP INDEX IDX_8D93D649E86D5C8B ON user');
        $this->addSql('ALTER TABLE user DROP id_adresse_id, DROP etat');
    }
}
