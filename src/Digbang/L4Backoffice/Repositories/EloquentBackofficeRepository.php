<?php namespace Digbang\L4Backoffice\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentBackofficeRepository
 * @package Digbang\L4Backoffice\Repositories
 */
class EloquentBackofficeRepository implements BackofficeRepository
{
	protected $eloquent;
	protected $eagerLoad = [];

	function __construct(Model $eloquent)
	{
		$this->eloquent = $eloquent;
	}

	public function with(array $eagerLoad)
	{
		$this->eagerLoad = $eagerLoad;

		return $this;
	}

	public function findById($id)
	{
		return $this->eloquent->with($this->eagerLoad)->findOrFail($id);
	}

	public function create($params)
	{
		return $this->eloquent->create($params);
	}

	public function update($id, $params)
	{
		/* @var $model \Illuminate\Database\Eloquent\Model */
		$model = $this->eloquent->findOrFail($id);

		$model->fill($params);

		return $model->save();
	}

	public function destroy($id)
	{
		return $this->eloquent->destroy($id);
	}

	public function search($filters, $sortBy = null, $sortSense = null, $limit = 10, $offset = 0)
	{
		$eloquent = $this->eloquent->with($this->eagerLoad);

		$filters = array_filter($filters, function($filter){ return !empty($filter) || $filter === false; });
		foreach ($filters as $key => $value)
		{
			$eloquent = $eloquent->where($key, $value);
		}

		if ($sortBy && $sortSense)
		{
			$eloquent = $eloquent->orderBy($sortBy, $sortSense);
		}

		return $eloquent->paginate($limit);
	}

	public function all()
	{
		$eloquent = $this->eloquent->with($this->eagerLoad);

		return $eloquent->get();
	}
}