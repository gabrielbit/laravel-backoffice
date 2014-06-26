<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

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

	function let(DatabaseManager $databaseManager, Builder $queryBuilder)
	{
		$databaseManager->table('information_schema.tables')->willReturn($queryBuilder);

		$queryBuilder->where('table_schema', 'public')->willReturn($queryBuilder);
		$queryBuilder->where('table_catalog', $this->catalogName)->willReturn($queryBuilder);

		$queryBuilder->get()->willReturn(new Collection());

		$this->beConstructedWith($databaseManager);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Services\ModelFinder');
    }

	function it_should_find_database_tables()
	{
		$this->find($this->catalogName)->shouldBeAnInstanceOf('Illuminate\Support\Collection');

		$this->find($this->catalogName)->count()->shouldBeGreaterThanOrEqualTo(1);
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

class DatabaseManager extends \Illuminate\Database\DatabaseManager
{
	public function table()
	{

	}
}