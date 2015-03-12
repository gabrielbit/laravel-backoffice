<?php namespace fixtures\apis;

use Illuminate\Support\Collection;

interface MockBackofficeApi
{
	/**
	 * @param string     $firstParam
	 * @param string     $secondParam
	 * @param Collection $typedParam
	 * @param int|null   $optional
	 *
	 * @return Collection
	 */
	public function aSearchMethod($firstParam, $secondParam, Collection $typedParam, $optional = null);

	/**
	 * @param string     $firstParam
     * @param string     $secondParam
     * @param Collection $typedParam
     * @param int|null   $optional
	 */
	public function aStoreMethod($firstParam, $secondParam, Collection $typedParam, $optional = null);

	/**
	 * @param int $id
	 *
	 * @return Collection
	 */
	public function aFindMethod($id);
}
