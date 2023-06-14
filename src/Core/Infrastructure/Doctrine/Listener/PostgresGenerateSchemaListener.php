<?php

declare(strict_types=1);

namespace Panda\Core\Infrastructure\Doctrine\Listener;

use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

final class PostgresGenerateSchemaListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args->getEntityManager()->getConnection()->createSchemaManager();

        if (!$schemaManager instanceof PostgreSqlSchemaManager) {
            return;
        }

        $schema = $args->getSchema();

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if (!$schema->hasNamespace($namespace)) {
                $schema->createNamespace($namespace);
            }
        }
    }
}
