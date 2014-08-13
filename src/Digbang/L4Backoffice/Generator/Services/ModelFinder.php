<?php namespace Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ColumnDecorator;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Database\DatabaseManager;
use Illuminate\Config\Repository as Config;

/**
 * Class ModelFinder
 * @package Digbang\L4Backoffice\Generator\Services
 */
class ModelFinder
{
	protected $db;
	protected $config;
	protected $schemaManager;

	function __construct(DatabaseManager $databaseManager, Config $config)
	{
		$this->db = $databaseManager;
		$this->config = $config;

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
			return new ColumnDecorator($column);
		}, $this->schemaManager->listTableColumns($tableName));
	}
}