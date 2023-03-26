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
        $this->addSql('CREATE TABLE panda_asset (id UUID NOT NULL, ticker VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN panda_asset.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE panda_asset');
    }
}
