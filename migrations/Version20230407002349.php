<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230407002349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Transaction';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_transaction (id UUID NOT NULL, owner_id UUID NOT NULL, type VARCHAR(255) NOT NULL, from_operation JSON NOT NULL, to_operation JSON NOT NULL, adjustment_operations JSON NOT NULL, concluded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84740CE77E3C61F9 ON panda_transaction (owner_id)');
        $this->addSql('COMMENT ON COLUMN panda_transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.from_operation IS \'(DC2Type:json_document)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.to_operation IS \'(DC2Type:json_document)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.adjustment_operations IS \'(DC2Type:json_document)\'');
        $this->addSql('ALTER TABLE panda_transaction ADD CONSTRAINT FK_84740CE77E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_transaction DROP CONSTRAINT FK_84740CE77E3C61F9');
        $this->addSql('DROP TABLE panda_transaction');
    }
}
