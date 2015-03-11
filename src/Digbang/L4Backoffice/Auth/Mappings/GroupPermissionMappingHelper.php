<?php namespace Digbang\L4Backoffice\Auth\Mappings;

use Digbang\Doctrine\Metadata\Builder;
use Digbang\Doctrine\Metadata\Relations\BelongsTo;
use Digbang\L4Backoffice\Auth\Entities\Group;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

class GroupPermissionMappingHelper
{
	use MappingHelper;

	private $groupClass;

	function __construct($groupClass = Group::class)
	{
		$this->groupClass = $groupClass;
	}

	public function addMappings(Builder $builder)
	{
		$builder->string('permission', function(FieldBuilder $fieldBuilder){
			$fieldBuilder->isPrimaryKey();
		});

		$builder->belongsTo($this->groupClass, 'group', function(BelongsTo $belongsTo){
			$belongsTo->keys($this->foreignKey($this->groupClass), 'id', false);
			$this->foreignIdentityHack($belongsTo);
		});
	}
}
