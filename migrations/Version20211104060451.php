<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104060451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD owner_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE cart ADD token VARCHAR(255) DEFAULT NULL;');
        $this->addSql('ALTER TABLE cart DROP session_id;');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B77E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE INDEX IDX_BA388B77E3C61F9 ON cart (owner_id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP owner_id;');
        $this->addSql('ALTER TABLE cart DROP token;');
        $this->addSql('ALTER TABLE cart ADD session_id VARCHAR(255) NOT NULL;');
    }
}
