<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328123946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blockchain_transactions (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, process_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, method VARCHAR(255) NOT NULL, block INT NOT NULL, from_contract VARCHAR(255) NOT NULL, to_contract VARCHAR(255) NOT NULL, fee DOUBLE PRECISION NOT NULL, fee_usd DOUBLE PRECISION NOT NULL, value DOUBLE PRECISION NOT NULL, value_usd DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, detail_raw LONGTEXT NOT NULL, date_time DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_92DC1A378DB60186 (task_id), INDEX IDX_92DC1A377EC2F574 (process_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parser_processes (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, processed INT NOT NULL, failure INT NOT NULL, total INT NOT NULL, log LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D8A6A6E88DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parser_tasks (id INT AUTO_INCREMENT NOT NULL, contract VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, last_transaction_hash VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blockchain_transactions ADD CONSTRAINT FK_92DC1A378DB60186 FOREIGN KEY (task_id) REFERENCES parser_tasks (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE blockchain_transactions ADD CONSTRAINT FK_92DC1A377EC2F574 FOREIGN KEY (process_id) REFERENCES parser_processes (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE parser_processes ADD CONSTRAINT FK_D8A6A6E88DB60186 FOREIGN KEY (task_id) REFERENCES parser_tasks (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blockchain_transactions DROP FOREIGN KEY FK_92DC1A377EC2F574');
        $this->addSql('ALTER TABLE blockchain_transactions DROP FOREIGN KEY FK_92DC1A378DB60186');
        $this->addSql('ALTER TABLE parser_processes DROP FOREIGN KEY FK_D8A6A6E88DB60186');
        $this->addSql('DROP TABLE blockchain_transactions');
        $this->addSql('DROP TABLE parser_processes');
        $this->addSql('DROP TABLE parser_tasks');
        $this->addSql('DROP TABLE user_users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
