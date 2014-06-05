<?php namespace Digbang\L4Backoffice\Support;

/**
 * Class MenuFactory
 * @package Digbang\L4Backoffice
 */
class MenuFactory
{
	protected $menu;

	public function make()
	{
		if (!$this->menu)
		{
			$menu = \Config::get('l4-backoffice::menu');

			$currentRoute = \Request::url();

			array_walk($menu, function(&$value) use ($currentRoute) {
				if (array_get($value, 'url')  == $currentRoute)
				{
					$value['selected'] = true;
				}
				else if (array_key_exists('children', $value))
				{
					foreach ($value['children'] as $item)
					{
						if (array_get($item, 'url')  == $currentRoute)
						{
							$value['selected'] = true;
						}
					}
				}
			});

			$this->menu = $menu;
		}

		return $this->menu;
	}
} 