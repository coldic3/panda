<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230722173504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add main resource to Portfolio';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_portfolio ADD main_resource_ticker VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panda_portfolio ADD main_resource_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_portfolio DROP main_resource_ticker');
        $this->addSql('ALTER TABLE panda_portfolio DROP main_resource_name');
    }
}
