<?php namespace Digbang\L4Backoffice\Support;

use Illuminate\Support\Contracts\ArrayableInterface;

class Collection extends \Illuminate\Support\Collection
{
	public function mergeInto($items)
	{
		if ($items instanceof Collection)
		{
			$items = $items->all();
		}
		elseif ($items instanceof ArrayableInterface)
		{
			$items = $items->toArray();
		}

		$this->items = array_merge($this->items, $items);

		return $this;
	}
} 