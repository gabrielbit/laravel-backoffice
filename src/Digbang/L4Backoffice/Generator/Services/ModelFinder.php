<?php namespace Digbang\L4Backoffice\Generator\Services;

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

	function __construct(DatabaseManager $databaseManager, Config $config)
	{
		$this->db = $databaseManager;
		$this->config = $config;
	}


	public function find()
    {
	    $databases = array_fetch($this->config->get('database.connections'), 'database');

	    if (empty($databases)) throw new \InvalidArgumentException('No databases found. Configure your connection and try again');

	    return $this->db->connection()
		    ->table('information_schema.tables')
		        ->where('table_schema', 'public')
			    ->where('table_catalog', array_shift($databases))
		        ->where('table_name', '<>', 'migrations')
		        ->orderBy('table_name', 'asc')
		    ->lists('table_name');
    }

	public function columns($tableName)
	{
		return $this->db->connection()
			->table('information_schema.columns')
				->where('table_name', $tableName)
			->lists('column_name');
	}
}