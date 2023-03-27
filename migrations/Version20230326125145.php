<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230326125145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Asset';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_asset (id UUID NOT NULL, owner_id UUID NOT NULL, ticker VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F1428147E3C61F9 ON panda_asset (owner_id)');
        $this->addSql('COMMENT ON COLUMN panda_asset.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_asset.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_asset ADD CONSTRAINT FK_7F1428147E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_asset DROP CONSTRAINT FK_7F1428147E3C61F9');
        $this->addSql('DROP TABLE panda_asset');
    }
}
