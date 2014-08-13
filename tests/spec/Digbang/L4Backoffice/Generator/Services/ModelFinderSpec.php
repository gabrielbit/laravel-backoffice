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

	function let(DatabaseManager $databaseManager, Config $config, Connection $connection, AbstractSchemaManager $schemaManager)
	{
		$config->get('database.connections')->willReturn([['database' => 'some_catalog_name']]);

		$databaseManager->connection()->willReturn($connection);
		$connection->getDoctrineSchemaManager()->willReturn($schemaManager);

		$tables = $this->tables + ['migrations'];

		$schemaManager->listTableNames()->willReturn($tables);
		$schemaManager->listTableColumns('a_table_name')->willReturn([
			'id', 'a_column_name', 'another_column'
		]);

		$this->beConstructedWith($databaseManager, $config);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Services\ModelFinder');
    }

	function it_should_find_database_tables()
	{
		$this->find()->shouldReturn($this->tables);
	}

	function it_should_find_table_columns()
	{
		$this->columns('a_table_name')->shouldReturn([
			'id', 'a_column_name', 'another_column'
		]);
	}
}