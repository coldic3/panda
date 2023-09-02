<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230902135611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Report';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_report (id UUID NOT NULL, portfolio_id UUID NOT NULL, name VARCHAR(255) NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, entry_type VARCHAR(255) NOT NULL, entry_configuration JSON NOT NULL, file_storage VARCHAR(255) DEFAULT NULL, file_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC550554B96B5643 ON panda_report (portfolio_id)');
        $this->addSql('COMMENT ON COLUMN panda_report.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_report.portfolio_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_report ADD CONSTRAINT FK_BC550554B96B5643 FOREIGN KEY (portfolio_id) REFERENCES panda_portfolio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_report DROP CONSTRAINT FK_BC550554B96B5643');
        $this->addSql('DROP TABLE panda_report');
    }
}
