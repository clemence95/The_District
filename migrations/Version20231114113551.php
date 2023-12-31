<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114113551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD is_verified TINYINT(1) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE cp cp VARCHAR(20) DEFAULT NULL, CHANGE ville ville VARCHAR(50) DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP is_verified, CHANGE email email VARCHAR(50) NOT NULL, CHANGE password password VARCHAR(50) NOT NULL, CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE adresse adresse VARCHAR(50) NOT NULL, CHANGE cp cp VARCHAR(20) NOT NULL, CHANGE ville ville VARCHAR(50) NOT NULL, CHANGE roles roles VARCHAR(255) NOT NULL');
    }
}
