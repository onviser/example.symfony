<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313173938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agreement (agreement_id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, landlord_id INT NOT NULL, agreement_identificator VARCHAR(50) NOT NULL, agreement_tenant VARCHAR(255) NOT NULL, agreement_month_rent_amount INT NOT NULL, agreement_date_start DATE NOT NULL, agreement_date_end DATE DEFAULT NULL, UNIQUE INDEX UNIQ_2E655A249855A282 (agreement_identificator), INDEX IDX_2E655A24549213EC (property_id), INDEX IDX_2E655A24D48E7AED (landlord_id), PRIMARY KEY(agreement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agreement ADD CONSTRAINT FK_2E655A24549213EC FOREIGN KEY (property_id) REFERENCES property (property_id)');
        $this->addSql('ALTER TABLE agreement ADD CONSTRAINT FK_2E655A24D48E7AED FOREIGN KEY (landlord_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agreement');
        $this->addSql('ALTER TABLE property CHANGE property_name property_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE property_type CHANGE property_type_name property_type_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
