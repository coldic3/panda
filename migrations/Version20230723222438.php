<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230723222438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename ExchangeRate into ExchangeRateLive';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate RENAME TO panda_exchange_rate_live');
        $this->addSql('ALTER INDEX uniq_1992c039d58bbb05589c0d1c RENAME TO UNIQ_B1C1ADC316A390FF447BA6D5');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_exchange_rate_live RENAME TO panda_exchange_rate');
        $this->addSql('ALTER INDEX uniq_b1c1adc316a390ff447ba6d5 RENAME TO uniq_1992c039d58bbb05589c0d1c');
    }
}
