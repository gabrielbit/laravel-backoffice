<?php namespace Digbang\L4Backoffice\Auth\Contracts;

use Doctrine\Common\Persistence\ObjectRepository;

interface RepositoryAware
{
	public function setRepository(ObjectRepository $repository);
}
