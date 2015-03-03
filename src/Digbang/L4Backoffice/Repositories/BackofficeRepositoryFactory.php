<?php namespace Digbang\L4Backoffice\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BackofficeRepositoryFactory
 * @package Digbang\L4Backoffice\Repositories
 */
class BackofficeRepositoryFactory
{
	public function makeForEloquentModel(Model $eloquent)
	{
		return new EloquentBackofficeRepository($eloquent);
	}
}