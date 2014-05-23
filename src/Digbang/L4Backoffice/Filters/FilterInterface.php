<?php namespace Digbang\L4Backoffice\Filters;

use Illuminate\Support\Contracts\RenderableInterface;

interface FilterInterface extends RenderableInterface
{
	public function name();

	public function label();

	public function value();

	public function options();
} 