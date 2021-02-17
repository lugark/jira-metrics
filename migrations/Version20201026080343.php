<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201026080343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sprint_issue_type_statistic (id INT AUTO_INCREMENT NOT NULL, sprint_id INT NOT NULL, issue_type VARCHAR(255) NOT NULL, count INT NOT NULL, INDEX IDX_C92DE8648C24077B (sprint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sprint_issue_type_statistic ADD CONSTRAINT FK_C92DE8648C24077B FOREIGN KEY (sprint_id) REFERENCES sprint (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sprint_issue_type_statistic');
    }
}
