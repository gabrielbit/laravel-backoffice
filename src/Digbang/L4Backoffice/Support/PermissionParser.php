<?php namespace Digbang\L4Backoffice\Support;

use Illuminate\Support\Str as LaravelStr;
use Illuminate\Translation\Translator;

/**
 * Class PermissionParser
 * @package Digbang\L4Backoffice\Support
 */
class PermissionParser
{
	/**
	 * @var \Illuminate\Support\Str
	 */
	protected $str;

	/**
	 * @var \Illuminate\Translation\Translator
	 */
	protected $translator;

	function __construct(LaravelStr $str, Translator $translator)
	{
		$this->str = $str;
		$this->translator = $translator;
	}

	public function toDropdownArray(array $permissions, $addEmptyValue = false)
	{
		$output = [];

		if ($addEmptyValue)
		{
			$output[''] = '';
		}

		foreach ($permissions as $permission)
		{
			list($prefix, $resource, $method) = array_pad(explode('.', (string) $permission, 3), 3, null);

			if (!$method)
			{
				$method = $resource;
			}

			$title = $this->trans($resource, 'permission-group', $resource);

			if (!array_key_exists($title, $output))
			{
				$output[$title] = [];
			}

			$output[$title][(string) $permission] = $this->trans($resource, $method);
		}

		return $output;
	}

	public function toViewTable(array $allPermissions, $userOrGroup)
	{
		return \View::make('backoffice::auth.partials.permissions', [
			'allPermissions' => $this->toDropdownArray($allPermissions),
			'userOrGroup' => $userOrGroup
		]);
	}

	public function trans($resource, $method, $default = null)
	{
		if ($this->translator->has("backoffice::permissions.$resource.$method"))
		{
			return $this->translator->get("backoffice::permissions.$resource.$method");
		}

		if ($this->translator->has("backoffice::permissions.system-defaults.$method"))
		{
			return $this->translator->get("backoffice::permissions.system-defaults.$method");
		}

		if($default !== null)
		{
			$method = $default;
		}

		return $this->titleize($method);
	}

	protected function titleize($string)
	{
		return $this->str->title(str_replace(['.', '-', '_', '/'], ' ', $string));
	}
}