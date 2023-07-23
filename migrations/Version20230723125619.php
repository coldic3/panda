<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230723125619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace base/quote assets with base/quote tickers in ExchangeRate';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate DROP CONSTRAINT fk_1992c0394f31bab9');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP CONSTRAINT fk_1992c039efe0b1d0');
        $this->addSql('DROP INDEX uniq_1992c0394f31bab9efe0b1d0');
        $this->addSql('DROP INDEX idx_1992c039efe0b1d0');
        $this->addSql('DROP INDEX idx_1992c0394f31bab9');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD base_resource_ticker VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD quote_resource_ticker VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP base_asset_id');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP quote_asset_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1992C039D58BBB05589C0D1C ON panda_exchange_rate (base_resource_ticker, quote_resource_ticker)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_1992C039D58BBB05589C0D1C');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD base_asset_id UUID NOT NULL');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD quote_asset_id UUID NOT NULL');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP base_resource_ticker');
        $this->addSql('ALTER TABLE panda_exchange_rate DROP quote_resource_ticker');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate.base_asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate.quote_asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD CONSTRAINT fk_1992c0394f31bab9 FOREIGN KEY (base_asset_id) REFERENCES panda_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_exchange_rate ADD CONSTRAINT fk_1992c039efe0b1d0 FOREIGN KEY (quote_asset_id) REFERENCES panda_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_1992c0394f31bab9efe0b1d0 ON panda_exchange_rate (base_asset_id, quote_asset_id)');
        $this->addSql('CREATE INDEX idx_1992c039efe0b1d0 ON panda_exchange_rate (quote_asset_id)');
        $this->addSql('CREATE INDEX idx_1992c0394f31bab9 ON panda_exchange_rate (base_asset_id)');
    }
}
