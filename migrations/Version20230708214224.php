<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230708214224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce ExchangeRate';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_exchange_rate (id UUID NOT NULL, base_asset_id UUID NOT NULL, quote_asset_id UUID NOT NULL, rate DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1992C0394F31BAB9 ON panda_exchange_rate (base_asset_id)');
        $this->addSql('CREATE INDEX IDX_1992C039EFE0B1D0 ON panda_exchange_rate (quote_asset_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1992C0394F31BAB9EFE0B1D0 ON panda_exchange_rate (base_asset_id, quote_asset_id)');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate.base_asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate.quote_asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD CONSTRAINT FK_1992C0394F31BAB9 FOREIGN KEY (base_asset_id) REFERENCES panda_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD CONSTRAINT FK_1992C039EFE0B1D0 FOREIGN KEY (quote_asset_id) REFERENCES panda_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate DROP CONSTRAINT FK_1992C0394F31BAB9');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP CONSTRAINT FK_1992C039EFE0B1D0');
        $this->addSql('DROP TABLE panda_exchange_rate');
    }
}
