<?php

namespace Digbang\L4Backoffice;

use Illuminate\Support\Collection;

class Listing extends Collection
{
	public function __construct(ColumnCollection $columnCollection)
	{
		$this->merge($columnCollection);
	}
}
