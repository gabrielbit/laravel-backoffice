<?php namespace Digbang\L4Backoffice\Inputs;

use Carbon\Carbon;
use Digbang\L4Backoffice\Controls\ControlInterface;

/**
 * Class DateTime
 * @package Digbang\L4Backoffice\Inputs
 */
class DateTime extends Input implements InputInterface
{
	protected $date;

	public function setValue($name, $value)
	{
		if (is_array($value))
		{
			$value = implode(' ', $value);
		}

		if (is_string($value))
		{
			$value = Carbon::parse($value);
		}

		parent::setValue($name, $value);
	}
}
