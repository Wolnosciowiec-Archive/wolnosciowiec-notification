<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Creates the structure for ApiUser
 *
 * @see ApiUser
 */
class Version20170110084813 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('wnotify_apiuser');

        $table->addColumn('id', 'guid')
            ->setNotnull(true)
            ->setLength(63);

        $table->addColumn('active', 'boolean')
            ->setDefault(true);

        $table->addColumn('api_key', 'string')
            ->setLength(128)
            ->setNotnull(true);

        $table->addColumn('name', 'string')
            ->setLength(64)
            ->setNotnull(true);

        $table->addColumn('date_added', 'datetime');
        $table->addUniqueIndex(['api_key'], 'by_api_key');
        $table->addUniqueIndex(['name'], 'by_user_name');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('wnotify_apiuser');
    }
}
