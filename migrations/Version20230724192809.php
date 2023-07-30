<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230724192809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ExchangeRateLog';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_exchange_rate_log (id UUID NOT NULL, base_ticker VARCHAR(255) NOT NULL, quote_ticker VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN panda_exchange_rate_log.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE panda_exchange_rate_log');
    }
}
