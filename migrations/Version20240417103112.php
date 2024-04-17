<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417103112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat MODIFY idC INT NOT NULL');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY fk_candidat_id');
        $this->addSql('DROP INDEX `primary` ON candidat');
        $this->addSql('ALTER TABLE candidat CHANGE idC recrutments INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD PRIMARY KEY (recrutments)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        
        $this->addSql('ALTER TABLE candidat MODIFY recrutments INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON candidat');
        $this->addSql('ALTER TABLE candidat CHANGE recrutments idC INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT fk_candidat_id FOREIGN KEY (idC) REFERENCES recrutments (idR)');
        $this->addSql('ALTER TABLE candidat ADD PRIMARY KEY (idC)');
    }
}
