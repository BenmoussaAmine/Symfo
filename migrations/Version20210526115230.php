<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526115230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dataset_tables (id INT AUTO_INCREMENT NOT NULL, id_dataset_id INT NOT NULL, table_name VARCHAR(255) NOT NULL, cle_jointure VARCHAR(255) NOT NULL, INDEX IDX_E231D69B71F6ADCC (id_dataset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dataset_tables_fields (id INT AUTO_INCREMENT NOT NULL, id_dataset_table_id INT NOT NULL, label VARCHAR(255) NOT NULL, agregation VARCHAR(255) NOT NULL, INDEX IDX_4E49C17A90E14B04 (id_dataset_table_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dataset_tables ADD CONSTRAINT FK_E231D69B71F6ADCC FOREIGN KEY (id_dataset_id) REFERENCES dataset (id)');
        $this->addSql('ALTER TABLE dataset_tables_fields ADD CONSTRAINT FK_4E49C17A90E14B04 FOREIGN KEY (id_dataset_table_id) REFERENCES dataset_tables (id)');
        $this->addSql('DROP TABLE tab1');
        $this->addSql('DROP TABLE tab1_tab2');
        $this->addSql('DROP TABLE tab2');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE dataset DROP tables');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dataset_tables_fields DROP FOREIGN KEY FK_4E49C17A90E14B04');
        $this->addSql('CREATE TABLE tab1 (id INT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, prenom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tab1_tab2 (id INT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, gouv VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tab2 (id INT NOT NULL, adresse VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, gouv VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, prenom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, date_naissance DATE DEFAULT NULL, pays VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, code_postal VARCHAR(5) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, nombre_achat INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE dataset_tables');
        $this->addSql('DROP TABLE dataset_tables_fields');
        $this->addSql('ALTER TABLE dataset ADD tables JSON DEFAULT NULL');
    }
}
