<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423161221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ord (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_date DATE NOT NULL, later_price DOUBLE PRECISION NOT NULL, INDEX IDX_EB1CEB3AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ord_product (ord_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_99B86CA6E636D3F5 (ord_id), INDEX IDX_99B86CA64584665A (product_id), PRIMARY KEY(ord_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) NOT NULL, second_name VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ord ADD CONSTRAINT FK_EB1CEB3AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ord_product ADD CONSTRAINT FK_99B86CA6E636D3F5 FOREIGN KEY (ord_id) REFERENCES ord (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ord_product ADD CONSTRAINT FK_99B86CA64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ord_product DROP FOREIGN KEY FK_99B86CA6E636D3F5');
        $this->addSql('ALTER TABLE ord_product DROP FOREIGN KEY FK_99B86CA64584665A');
        $this->addSql('ALTER TABLE ord DROP FOREIGN KEY FK_EB1CEB3AA76ED395');
        $this->addSql('DROP TABLE ord');
        $this->addSql('DROP TABLE ord_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
