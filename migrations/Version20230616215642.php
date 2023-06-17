<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230616215642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraint for ticker';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F1428147EC308967E3C61F9 ON panda_asset (ticker, owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31DB5053B63FE26CB96B5643 ON panda_portfolio_item (resource_ticker, portfolio_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_7F1428147EC308967E3C61F9');
        $this->addSql('DROP INDEX UNIQ_31DB5053B63FE26CB96B5643');
    }
}
