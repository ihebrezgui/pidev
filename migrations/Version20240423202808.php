<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423202808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY pk-id');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY planning_ibfk_1');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE code_promo');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE donateur');
        $this->addSql('DROP TABLE dons');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE facturation');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE candidat MODIFY idC INT NOT NULL');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY fk_candidat_id');
        $this->addSql('DROP INDEX `primary` ON candidat');
        $this->addSql('ALTER TABLE candidat CHANGE idC recrutments INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD PRIMARY KEY (recrutments)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapitre (idChapitre INT AUTO_INCREMENT NOT NULL, nomC VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, description VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, idCours INT NOT NULL, INDEX pk_id2 (idCours), PRIMARY KEY(idChapitre)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE code_promo (id_promo INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_expiration DATE NOT NULL, active TINYINT(1) NOT NULL, idUser INT NOT NULL, UNIQUE INDEX cle (idUser), PRIMARY KEY(id_promo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cours (idCours INT AUTO_INCREMENT NOT NULL, nomCours VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, description VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, categorie VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, prix DOUBLE PRECISION NOT NULL, idFormation INT NOT NULL, INDEX pk_id (idFormation), PRIMARY KEY(idCours)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE donateur (idDonateur INT AUTO_INCREMENT NOT NULL, nom INT NOT NULL, Prenom INT NOT NULL, PRIMARY KEY(idDonateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dons (idDon INT AUTO_INCREMENT NOT NULL, somme INT NOT NULL, idDonateur INT NOT NULL, INDEX cle (idDonateur), PRIMARY KEY(idDon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE events (id_event INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_event DATE NOT NULL, nbr_place INT NOT NULL, description VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE facturation (IdFacture INT AUTO_INCREMENT NOT NULL, infoClient VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Quantite INT NOT NULL, Prix DOUBLE PRECISION NOT NULL, DateFacturation DATE NOT NULL, IdPanier INT NOT NULL, INDEX pk-id (IdPanier), PRIMARY KEY(IdFacture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE formation (idFormation INT AUTO_INCREMENT NOT NULL, typeF VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(idFormation)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (Id INT AUTO_INCREMENT NOT NULL, NomProduit VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Quantite INT NOT NULL, DateAjout DATE NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE planning (id_event INT AUTO_INCREMENT NOT NULL, id_planning INT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, approved TINYINT(1) NOT NULL, UNIQUE INDEX cle (id_planning), PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_nais DATE NOT NULL, num_tel INT NOT NULL, email VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, sexe VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT pk-id FOREIGN KEY (IdPanier) REFERENCES panier (Id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT planning_ibfk_1 FOREIGN KEY (id_planning) REFERENCES events (id_event) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat MODIFY recrutments INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON candidat');
        $this->addSql('ALTER TABLE candidat CHANGE recrutments idC INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT fk_candidat_id FOREIGN KEY (idC) REFERENCES recrutments (idR)');
        $this->addSql('ALTER TABLE candidat ADD PRIMARY KEY (idC)');
    }
}
