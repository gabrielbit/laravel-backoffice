<?php namespace Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ColumnDecorator;
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
	    return array_filter($this->schemaManager->listTableNames(), function($tableName){
		    return $tableName != 'migrations';
	    });
    }

	public function columns($tableName)
	{
		return array_map(function(Column $column){
			// Decorate Doctrine Column with our decorator
			return new ColumnDecorator($column, $this->str);
		}, $this->schemaManager->listTableColumns($tableName));
	}
}