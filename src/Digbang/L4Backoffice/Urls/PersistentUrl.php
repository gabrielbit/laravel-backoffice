<?php namespace Digbang\L4Backoffice\Urls;

use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;

/**
 * Class PersistentUrl
 * @package Digbang\L4Backoffice\Urls
 */
class PersistentUrl
{
	/**
	 * @type Store
	 */
	private $store;
	/**
	 * @type SecureUrl
	 */
	private $secureUrl;

	public function __construct(Store $store, SecureUrl $secureUrl)
	{
		$this->store = $store;
		$this->secureUrl = $secureUrl;
	}

	private function getStoreKey($name)
	{
		return 'persistenturl_' . $name;
	}

	public function persist(Route $route, Request $request)
	{
		$key = $this->getStoreKey($route->getPath());

		if(strtoupper($request->getMethod()) == 'GET')
		{
			$this->store->put($key, $request->all());
		}
	}

	public function getPersisted($path, $parameters = [])
	{
		$path = parse_url($path);

		$key = $this->getStoreKey(ltrim($path['path'], '/'));

		if($persisted = $this->store->get($key))
		{
			return array_merge($persisted, $parameters);
		}

		return $parameters;
	}

	/**
	 * @param string $route
	 * @param array  $parameters
	 *
	 * @return string
	 * @throws \Digbang\Security\Permissions\Exceptions\PermissionException
	 */
	public function route($route, $parameters = [])
	{
		$path = $this->secureUrl->route($route, []);

		$parameters = $this->getPersisted($path, $parameters);

		return $this->secureUrl->route($route, $parameters);
	}

	/**
	 * @param string $action
	 * @param array  $parameters
	 *
	 * @return string
	 * @throws \Digbang\Security\Permissions\Exceptions\PermissionException
	 */
	public function action($action, $parameters = [])
	{
		$path = $this->secureUrl->action($action, []);

		$parameters = $this->getPersisted($path, $parameters);

		return $this->secureUrl->action($action, $parameters);
	}

	/**
	 * @param string $path
	 * @param array  $extra
	 * @param null   $secure
	 *
	 * @return string
	 * @throws \Digbang\Security\Permissions\Exceptions\PermissionException
	 */
	public function may($path, $extra = array(), $secure = null)
	{
		//The user is trying to redirecto to a specific url. We don't want to override that.

		return $this->secureUrl->may($path, $extra, $secure);
	}

	/**
	 * Allow access to the URL object
	 * @return UrlGenerator
	 */
	public function insecure()
	{
		return $this->secureUrl->insecure();
	}

	public function best($method, array $routes)
	{
		if (!method_exists($this, $method))
		{
			throw new \UnexpectedValueException("Method $method does not exist.");
		}

		foreach ($routes as $route)
		{
			if (! is_array($route))
			{
				$route = [$route];
			}

			try {
				return call_user_func_array([$this, $method], $route);
			} catch (PermissionException $e){}
		}
	}

    public function bestRoute(array $routes)
    {
	    return $this->best('route', $routes);
    }

	public function bestAction(array $actions)
	{
		return $this->best('action', $actions);
	}
}
