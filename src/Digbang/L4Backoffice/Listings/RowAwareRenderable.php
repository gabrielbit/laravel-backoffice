<?php namespace Digbang\L4Backoffice\Listings;

use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\RenderableInterface;

interface RowAwareRenderable
{
	/**
	 * @param Collection $row
	 *
	 * @return string|RenderableInterface|\Illuminate\View\View
	 */
	public function renderWith(Collection $row);
}
