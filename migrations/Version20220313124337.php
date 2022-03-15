<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313124337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE property (property_id INT AUTO_INCREMENT NOT NULL, property_type_id INT NOT NULL, landlord_id INT NOT NULL, property_name VARCHAR(255) NOT NULL, INDEX IDX_8BF21CDE9C81C6EB (property_type_id), INDEX IDX_8BF21CDED48E7AED (landlord_id), PRIMARY KEY(property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_type (property_type_id INT AUTO_INCREMENT NOT NULL, property_type_name VARCHAR(255) NOT NULL, PRIMARY KEY(property_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9C81C6EB FOREIGN KEY (property_type_id) REFERENCES property_type (property_type_id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDED48E7AED FOREIGN KEY (landlord_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE9C81C6EB');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDED48E7AED');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_type');
        $this->addSql('DROP TABLE `user`');
    }
}
