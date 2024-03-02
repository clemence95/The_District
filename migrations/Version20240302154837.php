<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302154837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demandes');
        $this->addSql('ALTER TABLE categorie CHANGE libelle libelle VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(50) NOT NULL, CHANGE active active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD utilisateur_id INT DEFAULT NULL, DROP id_plat, DROP quantite, DROP nom_client, DROP telephone_client, DROP email_client, DROP adresse_client, CHANGE total total NUMERIC(6, 2) NOT NULL, CHANGE date_commande date_commande DATE NOT NULL, CHANGE etat etat INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_6EEAA67DFB88E14F ON commande (utilisateur_id)');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F9382EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE plat CHANGE libelle libelle VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE prix prix NUMERIC(6, 2) NOT NULL, CHANGE image image VARCHAR(50) NOT NULL, CHANGE active active TINYINT(1) NOT NULL, CHANGE id_categorie categorie_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_2038A207BCF5E72D ON plat (categorie_id)');
        $this->addSql('ALTER TABLE utilisateur ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD telephone VARCHAR(20) DEFAULT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD cp VARCHAR(20) DEFAULT NULL, ADD ville VARCHAR(50) DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD is_verified TINYINT(1) NOT NULL, DROP nom_prenom, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demandes (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, telephone VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, demande TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, date_demande DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categorie CHANGE libelle libelle VARCHAR(100) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE active active VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DFB88E14F');
        $this->addSql('DROP INDEX IDX_6EEAA67DFB88E14F ON commande');
        $this->addSql('ALTER TABLE commande ADD id_plat INT NOT NULL, ADD quantite INT NOT NULL, ADD nom_client VARCHAR(150) NOT NULL, ADD telephone_client VARCHAR(20) NOT NULL, ADD email_client VARCHAR(150) NOT NULL, ADD adresse_client VARCHAR(255) NOT NULL, DROP utilisateur_id, CHANGE date_commande date_commande DATETIME NOT NULL, CHANGE total total NUMERIC(10, 2) NOT NULL, CHANGE etat etat VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY FK_2E067F93D73DB560');
        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY FK_2E067F9382EA2E54');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207BCF5E72D');
        $this->addSql('DROP INDEX IDX_2038A207BCF5E72D ON plat');
        $this->addSql('ALTER TABLE plat CHANGE libelle libelle VARCHAR(100) NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE active active VARCHAR(10) NOT NULL, CHANGE categorie_id id_categorie INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD nom_prenom VARCHAR(100) NOT NULL, DROP nom, DROP prenom, DROP telephone, DROP adresse, DROP cp, DROP ville, DROP roles, DROP is_verified, CHANGE email email VARCHAR(100) NOT NULL');
    }
}
