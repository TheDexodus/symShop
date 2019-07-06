<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190706081748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user RENAME INDEX uniq_880e0d7692fc23a8 TO UNIQ_8D93D64992FC23A8');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_880e0d76a0d96fbf TO UNIQ_8D93D649A0D96FBF');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_880e0d76c05fb297 TO UNIQ_8D93D649C05FB297');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649a0d96fbf TO UNIQ_880E0D76A0D96FBF');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649c05fb297 TO UNIQ_880E0D76C05FB297');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d64992fc23a8 TO UNIQ_880E0D7692FC23A8');
    }
}
