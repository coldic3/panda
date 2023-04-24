<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230412194912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Transaction and Operation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_operation (id UUID NOT NULL, resource_id UUID DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_39D1107389329D25 ON panda_operation (resource_id)');
        $this->addSql('COMMENT ON COLUMN panda_operation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_operation.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE panda_transaction (id UUID NOT NULL, from_operation_id UUID DEFAULT NULL, to_operation_id UUID DEFAULT NULL, owner_id UUID NOT NULL, type VARCHAR(255) NOT NULL, concluded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84740CE779EAEF65 ON panda_transaction (from_operation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84740CE79039965F ON panda_transaction (to_operation_id)');
        $this->addSql('CREATE INDEX IDX_84740CE77E3C61F9 ON panda_transaction (owner_id)');
        $this->addSql('COMMENT ON COLUMN panda_transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.from_operation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.to_operation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE panda_transaction_adjustment_operation (transaction_id UUID NOT NULL, operation_id UUID NOT NULL, PRIMARY KEY(transaction_id, operation_id))');
        $this->addSql('CREATE INDEX IDX_AA936A0D2FC0CB0F ON panda_transaction_adjustment_operation (transaction_id)');
        $this->addSql('CREATE INDEX IDX_AA936A0D44AC3583 ON panda_transaction_adjustment_operation (operation_id)');
        $this->addSql('COMMENT ON COLUMN panda_transaction_adjustment_operation.transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_transaction_adjustment_operation.operation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_operation ADD CONSTRAINT FK_39D1107389329D25 FOREIGN KEY (resource_id) REFERENCES panda_asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_transaction ADD CONSTRAINT FK_84740CE779EAEF65 FOREIGN KEY (from_operation_id) REFERENCES panda_operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_transaction ADD CONSTRAINT FK_84740CE79039965F FOREIGN KEY (to_operation_id) REFERENCES panda_operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_transaction ADD CONSTRAINT FK_84740CE77E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_transaction_adjustment_operation ADD CONSTRAINT FK_AA936A0D2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES panda_transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_transaction_adjustment_operation ADD CONSTRAINT FK_AA936A0D44AC3583 FOREIGN KEY (operation_id) REFERENCES panda_operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_operation DROP CONSTRAINT FK_39D1107389329D25');
        $this->addSql('ALTER TABLE panda_transaction DROP CONSTRAINT FK_84740CE779EAEF65');
        $this->addSql('ALTER TABLE panda_transaction DROP CONSTRAINT FK_84740CE79039965F');
        $this->addSql('ALTER TABLE panda_transaction DROP CONSTRAINT FK_84740CE77E3C61F9');
        $this->addSql('ALTER TABLE panda_transaction_adjustment_operation DROP CONSTRAINT FK_AA936A0D2FC0CB0F');
        $this->addSql('ALTER TABLE panda_transaction_adjustment_operation DROP CONSTRAINT FK_AA936A0D44AC3583');
        $this->addSql('DROP TABLE panda_operation');
        $this->addSql('DROP TABLE panda_transaction');
        $this->addSql('DROP TABLE panda_transaction_adjustment_operation');
    }
}
