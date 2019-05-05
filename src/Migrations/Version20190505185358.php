<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505185358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE inbox_membership_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inbox_membership (id INT NOT NULL, inbox_user_id INT DEFAULT NULL, user_inbox_id INT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4F89B56E925AD56A ON inbox_membership (inbox_user_id)');
        $this->addSql('CREATE INDEX IDX_4F89B56EF8576DDD ON inbox_membership (user_inbox_id)');
        $this->addSql('ALTER TABLE inbox_membership ADD CONSTRAINT FK_4F89B56E925AD56A FOREIGN KEY (inbox_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inbox_membership ADD CONSTRAINT FK_4F89B56EF8576DDD FOREIGN KEY (user_inbox_id) REFERENCES user_inbox (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inbox_membership_id_seq CASCADE');
        $this->addSql('DROP TABLE inbox_membership');
    }
}
