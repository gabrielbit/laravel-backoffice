<?php namespace Digbang\L4Backoffice\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentBackofficeRepository
 * @package Digbang\L4Backoffice\Repositories
 */
class EloquentBackofficeRepository implements BackofficeRepository
{
	protected $eloquent;
	protected $eagerLoad = [];

	use EloquentBackofficeRepositoryTrait;

	function __construct(Model $eloquent)
	{
		$this->eloquent = $eloquent;
	}

	public function with(array $eagerLoad)
	{
		$this->eagerLoad = $eagerLoad;

		return $this;
	}

	/**
	 * @param $id
	 * @return Model
	 */
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
			$eloquent = $eloquent->where(
				$key,
				$this->extractOperatorFrom($value),
				$this->trimOperators($value)
			);
		}

		if ($sortBy && $sortSense)
		{
			if (strpos($sortBy, '.') !== false)
			{
				list($relation, $remoteColumn) = explode('.', $sortBy, 2);
				$relationObject = $this->eloquent->{$relation}();

				if ($relationObject instanceof BelongsTo)
				{
					$eloquent = $this->sortByJoinedRelation($eloquent, $relationObject, $remoteColumn, $sortSense);
				}
			}
			else
			{
				$eloquent = $eloquent->orderBy($sortBy, $sortSense);
			}
		}

		if($limit)
		{
			$results = $eloquent->paginate($limit);
		}
		else
		{
			$results = $eloquent->get();
		}

		return $results;
	}

	public function all()
	{
		$eloquent = $this->eloquent->with($this->eagerLoad);

		return $eloquent->get();
	}
}