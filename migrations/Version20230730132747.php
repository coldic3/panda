<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230730132747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add owner to ExchangeRateLive and ExchangeRateLog';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate_live ADD owner_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate_live.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_exchange_rate_live ADD CONSTRAINT FK_B1C1ADC37E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1C1ADC37E3C61F9 ON panda_exchange_rate_live (owner_id)');
        $this->addSql('ALTER TABLE panda_exchange_rate_log ADD owner_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate_log.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_exchange_rate_log ADD CONSTRAINT FK_F9C4100C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F9C4100C7E3C61F9 ON panda_exchange_rate_log (owner_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate_live DROP CONSTRAINT FK_B1C1ADC37E3C61F9');
        $this->addSql('DROP INDEX IDX_B1C1ADC37E3C61F9');
        $this->addSql('ALTER TABLE panda_exchange_rate_live DROP owner_id');
        $this->addSql('ALTER TABLE panda_exchange_rate_log DROP CONSTRAINT FK_F9C4100C7E3C61F9');
        $this->addSql('DROP INDEX IDX_F9C4100C7E3C61F9');
        $this->addSql('ALTER TABLE panda_exchange_rate_log DROP owner_id');
    }
}
