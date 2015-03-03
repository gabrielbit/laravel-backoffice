<?php namespace Digbang\L4Backoffice\Auth\Mappings;

use Digbang\Doctrine\Metadata\Builder;
use Digbang\L4Backoffice\Auth\Entities\Group;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

final class UserMappingHelper
{
	/**
	 * @type string
	 */
	private $groupClassName;

	/**
	 * @type string
	 */
	private $groupField;

	/**
	 * @param string $groupClassName
	 * @param string $groupField
	 */
	public function __construct($groupClassName = Group::class, $groupField = 'groups')
	{
		$this->groupClassName = $groupClassName;
		$this->groupField = $groupField;
	}

	public function addMappings(Builder $builder)
	{
		$builder->primary();
		$builder->string('email', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->unique();
		});
		$builder->string('password');
		$builder->boolean('activated');
		$builder->string('activationCode', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->datetime('activatedAt', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->datetime('lastLogin', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->string('persistCode', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->string('resetPasswordCode', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->string('firstName', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->string('lastName', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});

		$builder->timestamps();

		$builder->belongsToMany($this->groupClassName, $this->groupField);

		$builder->addIndex(['activation_code'], 'backoffice_users_activation_code_index');
		$builder->addIndex(['reset_password_code'], 'backoffice_users_reset_password_code_index');
	}
}
