<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ModelFinderSpec
 * @mixin \Digbang\L4Backoffice\Generator\Services\ModelFinder
 * @package spec\Digbang\L4Backoffice\Generator\Services
 */
class ModelFinderSpec extends ObjectBehavior
{
	protected $tables = [
		'one_table', 'two_tables', 'more_tables'
	];

	function it_should_find_database_tables(DatabaseManager $databaseManager, Config $config, Connection $connection, AbstractSchemaManager $schemaManager)
	{
		$config->get('database.connections')->shouldBeCalled()->willReturn([['database' => 'some_catalog_name']]);

		$databaseManager->connection()->shouldBeCalled()->willReturn($connection);
		$connection->getDoctrineSchemaManager()->shouldBeCalled()->willReturn($schemaManager);

		$tables = $this->tables + ['migrations'];

		$schemaManager->listTableNames()->shouldBeCalled()->willReturn($tables);

		$this->beConstructedWith($databaseManager, $config);

		$this->find()->shouldReturn($this->tables);
	}
}
