<?php namespace Digbang\L4Backoffice\Urls;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Class PersistentUrlFilter
 * @package Digbang\L4Backoffice\Urls
 */
class PersistentUrlFilter
{
	/**
	 * @type PersistentUrl
	 */
	private $persistentUrl;

	public function __construct(PersistentUrl $persistentUrl)
	{
		$this->persistentUrl = $persistentUrl;
	}

	/**
	 * @param Route $route
	 *
	 * @return \Illuminate\Http\RedirectResponse|null
	 *
	 * Filter name: backoffice.urls.persistent
	 */
	public function persist(Route $route, Request $request)
	{
		if($route->getParameter('persistent'))
		{//Should be persisted
			$this->persistentUrl->persist($route, $request);
		}

		return null; //transparent filter (always)
	}
}
