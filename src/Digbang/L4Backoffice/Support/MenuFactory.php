<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Actions\ActionFactory as ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Illuminate\Http\Request;
use Illuminate\Config\Repository as Config;
use Illuminate\Translation\Translator;
use Digbang\Security\Urls\SecureUrl;

/**
 * Class MenuFactory
 * @package Digbang\L4Backoffice
 */
class MenuFactory
{
	protected $menu;
	protected $actionFactory;
	protected $controlFactory;
	protected $config;
	protected $secureUrl;

	function __construct(ActionFactory $actionFactory, ControlFactory $controlFactory, Config $config, SecureUrl $secureUrl)
	{
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
		$this->config         = $config;
		$this->secureUrl      = $secureUrl;
	}

	public function make()
	{
		if (!$this->menu)
		{
			$this->menu = [];

			$menus = $this->config->get('l4-backoffice::menu.menu');

			foreach ($menus as $title => $menu)
			{
				$actionTree = $this->actionFactory->collection();

				foreach ($menu as $label => $config)
				{
					$this->buildActionTree($actionTree, $label, $config);
				}

				$menu         = $this->buildMenu($title, $actionTree);
				if (! $menu->isEmpty())
				{
					$this->menu[] = $menu;
				}
			}
		}

		return $this->menu;
	}

	protected function buildMenu($title, ActionCollection $actionTree)
	{
		return new Menu(
			$this->controlFactory->make(
				'l4-backoffice::menu.main',
				$title,
				['class' => 'nav nav-pills nav-stacked nav-bracket']),
			$actionTree);
	}

	protected function buildActionTree(ActionCollection $root, $label, $config)
	{
		if (!is_array($config))
		{
			$config = ['path' => $config];
		}

		if (array_key_exists('children', $config))
		{
			$this->iterateChildren($root, $label, $config['children'], array_get($config, 'icon'));
		}
		else
		{
			$this->addLink($root, $label, $config);
		}

		return $root;
	}

	protected function getUrlFromConfig($config)
	{
		if (array_key_exists('path', $config))
		{
			return $this->secureUrl->may($config['path']);
		}

		if (array_key_exists('route', $config))
		{
			list($route, $params) = array_pad((array) $config['route'], 2, []);
			return $this->secureUrl->route($route, $params);
		}

		if (array_key_exists('action', $config))
		{
			return $this->secureUrl->action($config['action']);
		}

		throw new \UnexpectedValueException('Invalid menu configuration.');
	}

	protected function addLink(ActionCollection $root, $label, $config)
	{
		try
		{
			$root->link($this->getUrlFromConfig($config), $label, [], 'l4-backoffice::menu.link', array_get($config, 'icon'));
		}
		catch (PermissionException $e)
		{
			// Don't add it, user has no permission to see this
		}
	}

	/**
	 * @param ActionCollection $root
	 * @param                  $label
	 * @param array            $children
	 * @param string           $icon
	 */
	protected function iterateChildren(ActionCollection $root, $label, $children, $icon = null)
	{
		$branch = $root->dropdown($label, ['class' => 'nav-parent'], 'l4-backoffice::menu.dropdown', $icon);

		foreach ($children as $leafLabel => $config)
		{
			$this->buildActionTree($branch, $leafLabel, $config);
		}
	}
} 