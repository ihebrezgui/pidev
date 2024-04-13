<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionYYYYMMDDHHMMSS extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add utilisateur table';
    }



public function up(Schema $schema): void
{
    if (!$schema->hasTable('utilisateur')) {
        $table = $schema->createTable('utilisateur');
    }
}

    

    public function down(Schema $schema) : void
    {
        // Drop the utilisateur table if the migration is rolled back
        $schema->dropTable('utilisateur');
    }
}
