<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\Composite;
use Digbang\L4Backoffice\Actions\ActionFactory as ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
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
	protected $translator;
	protected $secureUrl;
	protected $currentUrl;

	function __construct(ActionFactory $actionFactory, ControlFactory $controlFactory, Config $config, Translator $translator, SecureUrl $secureUrl, Request $request = null)
	{
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
		$this->config         = $config;
		$this->translator     = $translator;
		$this->secureUrl      = $secureUrl;

		if ($request)
		{
			$this->currentUrl = $request->url();
		}

	}

	public function make()
	{
		if (!$this->menu)
		{
			$menu  = $this->config->get('l4-backoffice::menu');

			$actionTree = $this->actionFactory->dropdown($this->translator->get('l4-backoffice::default.navigation'));
			foreach ($menu as $label => $config)
			{
				$this->buildActionTree($actionTree, $label, $config);
			}

			$this->menu = new Menu($this->controlFactory->make('l4-backoffice::menu', ''), $actionTree);
		}

		return $this->menu;
	}

	protected function buildActionTree(Composite $root, $label, $config)
	{
		if (!is_array($config))
		{
			$config = ['path' => $config];
		}

		if (array_key_exists('children', $config))
		{
			$this->iterateChildren($root, $label, $config['children']);
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
			return $this->secureUrl->path($config['path']);
		}

		if (array_key_exists('route', $config))
		{
			return $this->secureUrl->route($config['route']);
		}

		if (array_key_exists('action', $config))
		{
			return $this->secureUrl->action($config['action']);
		}

		throw new \UnexpectedValueException('Invalid menu configuration.');
	}

	protected function addLink(Composite $root, $label, $config)
	{
		$url = $this->getUrlFromConfig($config);
		$options = [];

		if ($url == $this->currentUrl)
		{
			$options['selected'] = true;
		}

		$root->link($url, $label, $options);
	}

	/**
	 * @param Composite $root
	 * @param           $label
	 * @param array     $children
	 */
	protected function iterateChildren(Composite $root, $label, $children)
	{
		$branch = $root->dropdown($label);

		foreach ($children as $leafLabel => $config)
		{
			$this->buildActionTree($branch, $leafLabel, $config);
		}
	}
} 