<?php namespace Digbang\L4Backoffice\Auth\Mappings;

use Digbang\Doctrine\Metadata\Builder;
use Digbang\L4Backoffice\Auth\Entities\User;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

final class ThrottleMappingHelper
{
	/**
	 * @type string
	 */
	private $userClassName;

	/**
	 * @type string
	 */
	private $userField;

	function __construct($userClassName = User::class, $userField = 'user')
	{
		$this->userClassName = $userClassName;
		$this->userField     = $userField;
	}

	public function addMappings(Builder $builder)
	{
		$builder->primary();
		$builder->string('ipAddress', function(FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->integer('attempts');
		$builder->boolean('suspended');
		$builder->boolean('banned');
		$builder->datetime('lastAttemptAt', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->datetime('suspendedAt', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
		$builder->datetime('bannedAt', function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});

		$builder->belongsTo($this->userClassName, $this->userField, function (FieldBuilder $fieldBuilder){
			$fieldBuilder->nullable();
		});
	}
}
