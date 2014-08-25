<?php namespace Digbang\L4Backoffice\Repositories;

interface BackofficeRepository
{
	public function findById($id);

	public function create($params);

	public function update($id, $params);

	public function destroy($id);

	public function search($filters, $sortBy = null, $sortSense = null, $limit = 10, $offset = 0, $eagerLoad = []);

	public function all($eagerLoad = []);
} 