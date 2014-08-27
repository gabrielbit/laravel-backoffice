<?php namespace Digbang\L4Backoffice\Repositories;

interface BackofficeRepository
{
	/**
	 * @param array $eagerLoad
	 *
	 * @return \Digbang\L4Backoffice\Repositories\BackofficeRepository
	 *  This function is supposed to return $this, to use it as a pipe
	 */
	public function with(array $eagerLoad);

	public function findById($id);

	public function create($params);

	public function update($id, $params);

	public function destroy($id);

	public function search($filters, $sortBy = null, $sortSense = null, $limit = 10, $offset = 0);

	public function all();
} 