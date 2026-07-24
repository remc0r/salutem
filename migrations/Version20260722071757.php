<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260722071757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__appointment AS SELECT id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id FROM appointment');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_name VARCHAR(255) NOT NULL, patient_first_name VARCHAR(255) NOT NULL, patient_mail VARCHAR(255) NOT NULL, patient_phone_number VARCHAR(255) NOT NULL, date DATETIME NOT NULL, message CLOB DEFAULT NULL, specialty_id INTEGER NOT NULL, doctor_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, CONSTRAINT FK_FE38F8449A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F844A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO appointment (id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id) SELECT id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id FROM __temp__appointment');
        $this->addSql('DROP TABLE __temp__appointment');
        $this->addSql('CREATE INDEX IDX_FE38F84487F4FB17 ON appointment (doctor_id)');
        $this->addSql('CREATE INDEX IDX_FE38F8449A353316 ON appointment (specialty_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A76ED395 ON appointment (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__appointment AS SELECT id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id FROM appointment');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_name VARCHAR(255) NOT NULL, patient_first_name VARCHAR(255) NOT NULL, patient_mail VARCHAR(255) NOT NULL, patient_phone_number VARCHAR(255) NOT NULL, date DATETIME NOT NULL, message CLOB DEFAULT NULL, specialty_id INTEGER NOT NULL, doctor_id INTEGER DEFAULT NULL, CONSTRAINT FK_FE38F8449A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO appointment (id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id) SELECT id, patient_name, patient_first_name, patient_mail, patient_phone_number, date, message, specialty_id, doctor_id FROM __temp__appointment');
        $this->addSql('DROP TABLE __temp__appointment');
        $this->addSql('CREATE INDEX IDX_FE38F8449A353316 ON appointment (specialty_id)');
        $this->addSql('CREATE INDEX IDX_FE38F84487F4FB17 ON appointment (doctor_id)');
    }
}
