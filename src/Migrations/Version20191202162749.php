<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202162749 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'diff-[1.0.0-alpha.0]-[1.0.0-alpha.1]';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE guardian_kid_pending_relation (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, kid_id INT NOT NULL, INDEX guardian_kid_pending_relation_guardian (guardian_id), INDEX guardian_kid_pending_relation_kid (kid_id), UNIQUE INDEX guardian_kid_unique (guardian_id, kid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concrete_task (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, kid_id INT NOT NULL, created_at DATETIME NOT NULL, day DATE NOT NULL, time_end TIME NOT NULL, time_start TIME NOT NULL, img_url LONGTEXT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_11C0725811CC8B0A (guardian_id), INDEX IDX_11C072586A973770 (kid_id), INDEX concrete_task_day_kid (day, kid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guardian (id INT AUTO_INCREMENT NOT NULL, birth_date DATETIME DEFAULT NULL, name VARCHAR(40) NOT NULL, surname VARCHAR(40) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_64486055E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guardian_kid_relation (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, kid_id INT NOT NULL, INDEX guardian_kid_relation_guardian (guardian_id), INDEX guardian_kid_relation_kid (kid_id), UNIQUE INDEX guardian_kid_unique (guardian_id, kid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_task (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, kid_id INT NOT NULL, date_off_set VARCHAR(255) NOT NULL, day DATE NOT NULL, time_end TIME NOT NULL, time_start TIME NOT NULL, img_url LONGTEXT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_EBEB312711CC8B0A (guardian_id), INDEX IDX_EBEB31276A973770 (kid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concrete_subtask (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, img_url LONGTEXT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_84FECBC58DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kid (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, birth_date DATETIME DEFAULT NULL, name VARCHAR(40) NOT NULL, surname VARCHAR(40) NOT NULL, nick VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_4523887C290B2F37 (nick), INDEX IDX_4523887C11CC8B0A (guardian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guardian_kid_pending_relation ADD CONSTRAINT FK_C642AFD111CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id)');
        $this->addSql('ALTER TABLE guardian_kid_pending_relation ADD CONSTRAINT FK_C642AFD16A973770 FOREIGN KEY (kid_id) REFERENCES kid (id)');
        $this->addSql('ALTER TABLE concrete_task ADD CONSTRAINT FK_11C0725811CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id)');
        $this->addSql('ALTER TABLE concrete_task ADD CONSTRAINT FK_11C072586A973770 FOREIGN KEY (kid_id) REFERENCES kid (id)');
        $this->addSql('ALTER TABLE guardian_kid_relation ADD CONSTRAINT FK_A9EBC23A11CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id)');
        $this->addSql('ALTER TABLE guardian_kid_relation ADD CONSTRAINT FK_A9EBC23A6A973770 FOREIGN KEY (kid_id) REFERENCES kid (id)');
        $this->addSql('ALTER TABLE cron_task ADD CONSTRAINT FK_EBEB312711CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id)');
        $this->addSql('ALTER TABLE cron_task ADD CONSTRAINT FK_EBEB31276A973770 FOREIGN KEY (kid_id) REFERENCES kid (id)');
        $this->addSql('ALTER TABLE concrete_subtask ADD CONSTRAINT FK_84FECBC58DB60186 FOREIGN KEY (task_id) REFERENCES concrete_task (id)');
        $this->addSql('ALTER TABLE kid ADD CONSTRAINT FK_4523887C11CC8B0A FOREIGN KEY (guardian_id) REFERENCES guardian (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE concrete_subtask DROP FOREIGN KEY FK_84FECBC58DB60186');
        $this->addSql('ALTER TABLE guardian_kid_pending_relation DROP FOREIGN KEY FK_C642AFD111CC8B0A');
        $this->addSql('ALTER TABLE concrete_task DROP FOREIGN KEY FK_11C0725811CC8B0A');
        $this->addSql('ALTER TABLE guardian_kid_relation DROP FOREIGN KEY FK_A9EBC23A11CC8B0A');
        $this->addSql('ALTER TABLE cron_task DROP FOREIGN KEY FK_EBEB312711CC8B0A');
        $this->addSql('ALTER TABLE kid DROP FOREIGN KEY FK_4523887C11CC8B0A');
        $this->addSql('ALTER TABLE guardian_kid_pending_relation DROP FOREIGN KEY FK_C642AFD16A973770');
        $this->addSql('ALTER TABLE concrete_task DROP FOREIGN KEY FK_11C072586A973770');
        $this->addSql('ALTER TABLE guardian_kid_relation DROP FOREIGN KEY FK_A9EBC23A6A973770');
        $this->addSql('ALTER TABLE cron_task DROP FOREIGN KEY FK_EBEB31276A973770');
        $this->addSql('DROP TABLE guardian_kid_pending_relation');
        $this->addSql('DROP TABLE concrete_task');
        $this->addSql('DROP TABLE guardian');
        $this->addSql('DROP TABLE guardian_kid_relation');
        $this->addSql('DROP TABLE cron_task');
        $this->addSql('DROP TABLE concrete_subtask');
        $this->addSql('DROP TABLE kid');
    }
}
