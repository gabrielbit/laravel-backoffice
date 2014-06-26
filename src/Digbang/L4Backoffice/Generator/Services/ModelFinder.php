<?php namespace Digbang\L4Backoffice\Generator\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

/**
 * Class ModelFinder
 * @package Digbang\L4Backoffice\Generator\Services
 */
class ModelFinder
{
	protected $db;

	function __construct(DatabaseManager $databaseManager)
	{
		$this->db = $databaseManager;
	}


	public function find($catalogName)
    {
	    return $this->db->table('information_schema.tables')
	        ->where('table_schema', 'public')
		    ->where('table_catalog', $catalogName)
	        ->where('table_name', '<>', 'migrations')
	        ->orderBy('table_name', 'asc')
		    ->get();
    }

	public function columns($tableName)
	{
		return $this->db->table('information_schema.columns')
			->where('table_name', $tableName)
			->lists('column_name');
	}
}
