<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210710084907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `person` (`id` INT AUTO_INCREMENT NOT NULL, `family_name` VARCHAR(64) NOT NULL, `given_name` VARCHAR(64) DEFAULT NULL, `additional_name` VARCHAR(64) DEFAULT NULL, `honorific_prefix` VARCHAR(64) DEFAULT NULL, `honorific_suffix` VARCHAR(64) DEFAULT NULL, `gender` VARCHAR(16) DEFAULT NULL, `birth_date` DATE DEFAULT NULL, `is_active` TINYINT(1) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `person`');
    }
}
