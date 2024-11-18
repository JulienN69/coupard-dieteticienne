<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118084112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_22D1FE13933FE08C ON recipe_ingredient');
        $this->addSql('DROP INDEX `PRIMARY` ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient DROP id');
        $this->addSql('ALTER TABLE recipe_ingredient ADD PRIMARY KEY (ingredient_id)');
    }
}
