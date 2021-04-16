<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416091137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_request (id INT AUTO_INCREMENT NOT NULL, from_order_id INT DEFAULT NULL, created_at DATETIME NOT NULL, paid_at DATETIME DEFAULT NULL, validated TINYINT(1) DEFAULT NULL, stripe_session_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_22DE8175CB708DA2 (from_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_request ADD CONSTRAINT FK_22DE8175CB708DA2 FOREIGN KEY (from_order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_request');
    }
}
