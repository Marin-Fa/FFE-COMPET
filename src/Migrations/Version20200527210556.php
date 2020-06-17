<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527210556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contest (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, adress VARCHAR(90) NOT NULL, zipcode VARCHAR(10) NOT NULL, city VARCHAR(80) NOT NULL, country VARCHAR(50) NOT NULL, creation_date DATETIME NOT NULL, end_of_registration DATETIME NOT NULL, discipline VARCHAR(50) NOT NULL, max_contestants_total INT NOT NULL, beginning_date DATE NOT NULL, end_date DATE NOT NULL, picture VARCHAR(255) DEFAULT NULL, stable_name VARCHAR(60) NOT NULL, INDEX IDX_1A95CB5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, contest_id INT NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, level VARCHAR(100) NOT NULL, estimated_starting_time TIME NOT NULL, max_contestants INT NOT NULL, INDEX IDX_3BAE0AA71CD0F0DE (contest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horse (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, gender VARCHAR(40) NOT NULL, INDEX IDX_629A2F18A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horserider (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, horse_id INT NOT NULL, start_number INT NOT NULL, INDEX IDX_E5CF471D71F7E88B (event_id), INDEX IDX_E5CF471DA76ED395 (user_id), INDEX IDX_E5CF471D76B275AD (horse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(60) NOT NULL, last_name VARCHAR(60) NOT NULL, licence_number VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE horse ADD CONSTRAINT FK_629A2F18A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE horserider ADD CONSTRAINT FK_E5CF471D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE horserider ADD CONSTRAINT FK_E5CF471DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE horserider ADD CONSTRAINT FK_E5CF471D76B275AD FOREIGN KEY (horse_id) REFERENCES horse (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71CD0F0DE');
        $this->addSql('ALTER TABLE horserider DROP FOREIGN KEY FK_E5CF471D71F7E88B');
        $this->addSql('ALTER TABLE horserider DROP FOREIGN KEY FK_E5CF471D76B275AD');
        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB5A76ED395');
        $this->addSql('ALTER TABLE horse DROP FOREIGN KEY FK_629A2F18A76ED395');
        $this->addSql('ALTER TABLE horserider DROP FOREIGN KEY FK_E5CF471DA76ED395');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contest');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE horse');
        $this->addSql('DROP TABLE horserider');
        $this->addSql('DROP TABLE user');
    }
}
