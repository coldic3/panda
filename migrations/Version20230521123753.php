<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230521123753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Portfolio and PortfolioItem';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE panda_portfolio (id UUID NOT NULL, owner_id UUID NOT NULL, name VARCHAR(255) NOT NULL, default_ BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_89BDA67C7E3C61F9 ON panda_portfolio (owner_id)');
        $this->addSql('COMMENT ON COLUMN panda_portfolio.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_portfolio.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE panda_portfolio_item (id UUID NOT NULL, portfolio_id UUID NOT NULL, long_quantity INT NOT NULL, short_quantity INT NOT NULL, resource_ticker VARCHAR(255) NOT NULL, resource_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_31DB5053B96B5643 ON panda_portfolio_item (portfolio_id)');
        $this->addSql('COMMENT ON COLUMN panda_portfolio_item.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN panda_portfolio_item.portfolio_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE panda_portfolio ADD CONSTRAINT FK_89BDA67C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES panda_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panda_portfolio_item ADD CONSTRAINT FK_31DB5053B96B5643 FOREIGN KEY (portfolio_id) REFERENCES panda_portfolio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE panda_portfolio DROP CONSTRAINT FK_89BDA67C7E3C61F9');
        $this->addSql('ALTER TABLE panda_portfolio_item DROP CONSTRAINT FK_31DB5053B96B5643');
        $this->addSql('DROP TABLE panda_portfolio');
        $this->addSql('DROP TABLE panda_portfolio_item');
    }
}
