<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530075223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE chart ADD dataset_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chart ADD CONSTRAINT FK_E5562A2AD47C2D1B FOREIGN KEY (dataset_id) REFERENCES dataset (id)');
        $this->addSql('CREATE INDEX IDX_E5562A2AD47C2D1B ON chart (dataset_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT NOT NULL, nomCl VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, age INT DEFAULT NULL, typeCl VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chart DROP FOREIGN KEY FK_E5562A2AD47C2D1B');
        $this->addSql('DROP INDEX IDX_E5562A2AD47C2D1B ON chart');
        $this->addSql('ALTER TABLE chart DROP dataset_id');
    }
}
