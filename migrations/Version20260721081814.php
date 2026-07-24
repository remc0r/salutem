<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260721081814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opening_hour (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, day VARCHAR(20) NOT NULL, day_order INTEGER NOT NULL, opens_at TIME DEFAULT NULL, closes_at TIME DEFAULT NULL, is_closed BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_969BD765E5A02990 ON opening_hour (day)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_969BD7654CE42F7C ON opening_hour (day_order)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE opening_hour');
    }
}
