<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ModelFinderSpec
 * @mixin \Digbang\L4Backoffice\Generator\Services\ModelFinder
 * @package spec\Digbang\L4Backoffice\Generator\Services
 */
class ModelFinderSpec extends ObjectBehavior
{
	protected $catalogName = 'some_catalog_name';

	function let(DatabaseManager $databaseManager, Builder $queryBuilder, Config $config, Connection $connection)
	{
		$config->get(Argument::any())->willReturn(
			[
				[
					'database' => $this->catalogName
				]
			]);

		$databaseManager->connection()->willReturn($connection);
		$connection->table('information_schema.tables')->willReturn($queryBuilder);

		$queryBuilder->where(Argument::any(), Argument::any(), Argument::any())->willReturn($queryBuilder);
		$queryBuilder->orderBy(Argument::any(), Argument::any())->willReturn($queryBuilder);

		$queryBuilder->get()->willReturn(new Collection());
		$queryBuilder->lists(Argument::any())->willReturn(new Collection());

		$this->beConstructedWith($databaseManager, $config);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Services\ModelFinder');
    }

	function it_should_find_database_tables()
	{
		$this->find()->shouldBeAnInstanceOf('Illuminate\Support\Collection');

		$this->find()->count()->shouldBeGreaterThanOrEqualTo(1);
	}

	public function getMatchers()
	{
		return [
			'beGreaterThan' => function($subject, $key){
				return $key > $subject;
			},
			'beGreaterThanOrEqualTo' => function($subject, $key){
				return $key >= $subject;
			},
			'beLessThan' => function($subject, $key){
				return $key < $subject;
			},
			'beLessThanOrEqualTo' => function($subject, $key){
				return $key <= $subject;
			},
		];
	}
}