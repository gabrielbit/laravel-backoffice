<?php namespace Digbang\L4Backoffice\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentBackofficeRepositoryTrait
 * @package Digbang\L4Backoffice\Repositories
 */
trait EloquentBackofficeRepositoryTrait
{
	protected function sortByJoinedRelation(Model $model, Builder $eloquent, Relation $relationObject, $remoteColumn, $sortSense)
	{
		if (!($relationObject instanceof BelongsTo || $relationObject instanceof HasOne))
		{
			throw new \InvalidArgumentException('Method expects $relationObject to be of type Illuminate\Database\Eloquent\Relations\BelongsTo or Illuminate\Database\Eloquent\Relations\HasOne');
		}

		/* @var $eloquent Builder */
		$eloquent = $eloquent->select($model->getTable() . '.*');

		$relationTable = $relationObject->getRelated()->getTable();

		$foreignKey = $this->getForeignKeyForRelation($relationObject);
		$otherKey = $this->getOtherKeyForRelation($relationObject);

		$eloquent = $eloquent->join(
			$relationTable,
			$foreignKey,
			'=',
			$otherKey
		);

		return $eloquent->orderBy("$relationTable.$remoteColumn", $sortSense);
	}

	protected function getForeignKeyForRelation(Relation $relationObject)
	{
		if($relationObject instanceof BelongsTo)
		{
			return $relationObject->getQualifiedForeignKey();
		}
		elseif($relationObject instanceof HasOne)
		{
			return $relationObject->getForeignKey();
		}

		throw new \InvalidArgumentException('Method expects $relationObject to be of type Illuminate\Database\Eloquent\Relations\BelongsTo or Illuminate\Database\Eloquent\Relations\HasOne');
	}

	protected function getOtherKeyForRelation(Relation $relationObject)
	{
		if($relationObject instanceof BelongsTo)
		{
			return $relationObject->getQualifiedOtherKeyName();
		}
		elseif($relationObject instanceof HasOne)
		{
			return $relationObject->getQualifiedParentKeyName();
		}

		throw new \InvalidArgumentException('Method expects $relationObject to be of type Illuminate\Database\Eloquent\Relations\BelongsTo or Illuminate\Database\Eloquent\Relations\HasOne');
	}

	protected function extractOperatorFrom($value)
	{
		if (preg_match('/^[<>=!]+/', $value, $matches))
		{
			return $matches[0];
		}

		return '=';
	}

	protected function trimOperators($value)
	{
		return ltrim($value, ' <>=!');
	}
}