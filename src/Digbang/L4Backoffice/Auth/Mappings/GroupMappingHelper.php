<?php namespace Digbang\L4Backoffice\Auth\Mappings;

use Digbang\Doctrine\Metadata\Builder;
use Digbang\Doctrine\Metadata\Relations\BelongsToMany;
use Digbang\Doctrine\Metadata\Relations\HasMany;
use Digbang\L4Backoffice\Auth\Entities\User;
use Digbang\L4Backoffice\Auth\Entities\GroupPermission;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

final class GroupMappingHelper
{
	use MappingHelper;

	private $userClass;
	private $groupPermissionClass;

	public function __construct($userClass = User::class, $groupPermissionClass = GroupPermission::class)
	{
		$this->userClass            = $userClass;
		$this->groupPermissionClass = $groupPermissionClass;
	}

	public function addMappings(Builder $builder)
	{
		$this->addProperties($builder);
		$this->addRelations($builder);
	}

	public function addProperties(Builder $builder)
	{
		$builder->primary();
		$builder->string('name', function(FieldBuilder $fieldBuilder){
			$fieldBuilder->unique();
		});
		$builder->timestamps();
	}

	public function addRelations(Builder $builder)
	{
		$builder->belongsToMany($this->userClass, 'users', function(BelongsToMany $belongsToMany){
			$belongsToMany->mappedBy('groups');
			$belongsToMany->cascadeAll();
		});

		$builder->hasMany($this->groupPermissionClass, 'permissions', function(HasMany $hasMany){
			$hasMany->mappedBy('group');
			$hasMany->cascadeAll();

			$this->orphanRemovalHack($hasMany);
		});
	}
}
