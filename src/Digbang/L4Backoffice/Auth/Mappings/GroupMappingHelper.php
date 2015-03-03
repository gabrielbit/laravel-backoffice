<?php namespace Digbang\L4Backoffice\Auth\Mappings;

use Digbang\Doctrine\Metadata\Builder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

final class GroupMappingHelper
{
	public function addMappings(Builder $builder)
	{
		$builder->primary();
		$builder->string('name', function(FieldBuilder $fieldBuilder){
			$fieldBuilder->unique();
		});
		$builder->timestamps();
	}
}
