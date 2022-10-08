<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007064150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_produits (categories_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_68D376B5A21214B7 (categories_id), INDEX IDX_68D376B5CD11A2CF (produits_id), PRIMARY KEY(categories_id, produits_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5A21214B7');
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5CD11A2CF');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_produits');
    }
}
