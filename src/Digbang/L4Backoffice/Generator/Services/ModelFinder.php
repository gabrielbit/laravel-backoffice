<?php namespace Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ColumnDecorator;
use Digbang\L4Backoffice\Support\Collection;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Database\DatabaseManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Str;

/**
 * Class ModelFinder
 * @package Digbang\L4Backoffice\Generator\Services
 */
class ModelFinder
{
	protected $db;
	protected $config;
	protected $schemaManager;
	protected $str;

	function __construct(DatabaseManager $databaseManager, Config $config, Str $str)
	{
		$this->db = $databaseManager;
		$this->config = $config;
		$this->str = $str;

		$databases = array_fetch($this->config->get('database.connections'), 'database');

		if (empty($databases)) throw new \InvalidArgumentException('No databases found. Configure your connection and try again');

		$this->schemaManager = $this->db->connection()->getDoctrineSchemaManager();
	}


	public function find()
    {
	    $tableNames = [];

	    foreach ($this->schemaManager->listTables() as $table)
	    {
		    /* @var $table \Doctrine\DBAL\Schema\Table */
		    if ($table->getName() != 'migrations')
		    {
			    $tableNames[$table->getName()] = $table->getName();
		    }
	    }

	    foreach ($tableNames as $tableName)
	    {
		    foreach ($tableNames as $otherTableName)
		    {
			    $singulars = [$this->str->singular($tableName), $this->str->singular($otherTableName)];
			    $key = implode('_', $singulars);

			    unset($tableNames[$key]);
		    }
	    }

	    // Hard-coded unset of the Sentry relationship table
	    unset($tableNames[\Config::get('security::auth.user_groups_pivot_table')]);
	    unset($tableNames[\Config::get('security::auth.users.table')]);
	    unset($tableNames[\Config::get('security::auth.groups.table')]);

	    natcasesort($tableNames);

	    return array_values($tableNames);
    }

	public function columns($tableName)
	{
		return array_map(function(Column $column){
			// Decorate Doctrine Column with our decorator
			return new ColumnDecorator($column, $this->str);
		}, $this->schemaManager->listTableColumns($tableName));
	}

	public function foreignKeys($tableName)
	{
		return new Collection($this->schemaManager->listTableForeignKeys($tableName));
	}
}