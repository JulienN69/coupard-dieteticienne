<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030074613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_6BAF7870BCF5E72D ON ingredient (categorie_id)');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(20) NOT NULL, CHANGE last_name last_name VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870BCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX IDX_6BAF7870BCF5E72D ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP categorie_id');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL');
    }
}
