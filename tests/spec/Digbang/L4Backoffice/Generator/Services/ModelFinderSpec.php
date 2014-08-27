<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ModelFinderSpec
 * @mixin \Digbang\L4Backoffice\Generator\Services\ModelFinder
 * @package spec\Digbang\L4Backoffice\Generator\Services
 */
class ModelFinderSpec extends ObjectBehavior
{
	function it_should_find_database_tables(DatabaseManager $databaseManager, Config $config, Connection $connection, AbstractSchemaManager $schemaManager, Str $str, Table $table, Table $anotherTable, Table $migrationsTable)
	{
		/*
		$config->get('database.connections')->shouldBeCalled()->willReturn([['database' => 'some_catalog_name']]);

		$databaseManager->connection()->shouldBeCalled()->willReturn($connection);
		$connection->getDoctrineSchemaManager()->shouldBeCalled()->willReturn($schemaManager);

		$table->getName()->willReturn('a_table');
		$anotherTable->getName()->willReturn('another_table');
		$migrationsTable->getName()->willReturn('migrations');

		$tables = [$table, $anotherTable, $migrationsTable];
		$response = [$table, $anotherTable];

		$schemaManager->listTables()->shouldBeCalled()->willReturn($tables);

		$this->beConstructedWith($databaseManager, $config, $str);

		$this->find()->shouldReturn($response);*/
	}
}
