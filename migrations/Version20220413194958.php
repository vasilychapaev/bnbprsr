<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413194958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blockchain_transactions DROP FOREIGN KEY FK_92DC1A378DB60186');
        $this->addSql('ALTER TABLE blockchain_transactions ADD CONSTRAINT FK_92DC1A378DB60186 FOREIGN KEY (task_id) REFERENCES parser_tasks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parser_tasks ADD title VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blockchain_transactions DROP FOREIGN KEY FK_92DC1A378DB60186');
        $this->addSql('ALTER TABLE blockchain_transactions CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hash hash VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE method method VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE from_contract from_contract VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE to_contract to_contract VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE currency currency VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE detail_raw detail_raw LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blockchain_transactions ADD CONSTRAINT FK_92DC1A378DB60186 FOREIGN KEY (task_id) REFERENCES parser_tasks (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE parser_processes CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE log log LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE parser_tasks DROP title, DROP description, CHANGE contract contract VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_transaction_hash last_transaction_hash VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_users CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
