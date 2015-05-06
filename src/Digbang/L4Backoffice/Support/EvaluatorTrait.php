<?php namespace Digbang\L4Backoffice\Support;

use Illuminate\Support\Collection as LaravelCollection;

trait EvaluatorTrait
{
	/**
	 * @param mixed|\Closure $possibleClosure
	 * @param LaravelCollection $row
	 *
	 * @return mixed
	 */
	protected function evaluate($possibleClosure, LaravelCollection $row)
	{
		if ($possibleClosure instanceof \Closure)
		{
			return $possibleClosure($row);
		}

		return $possibleClosure;
	}
}
