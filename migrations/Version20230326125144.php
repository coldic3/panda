<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230326125144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce User';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_user (id UUID NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_317F2FC6E7927C74 ON panda_user (email)');
        $this->addSql('COMMENT ON COLUMN panda_user.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE panda_user');
    }
}
