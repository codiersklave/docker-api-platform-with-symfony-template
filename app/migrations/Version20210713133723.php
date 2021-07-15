<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713133723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `user` (`id` INT AUTO_INCREMENT NOT NULL, `email` VARCHAR(255) NOT NULL, `username` VARCHAR(255) NOT NULL, `roles` JSON NOT NULL, `password` VARCHAR(255) DEFAULT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6496F279BB4 (`email`), UNIQUE INDEX UNIQ_8D93D649854D4CE4 (`username`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `user`');
    }
}