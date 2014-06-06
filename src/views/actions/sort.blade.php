@if(Input::get(Digbang\L4Backoffice\Support\LinkMaker::SORT_BY) == $column->getId())
	@if(Input::get(Digbang\L4Backoffice\Support\LinkMaker::SORT_SENSE) == SORT_DESC)
		<a href="{{ Digbang\L4Backoffice\Facades\Link::sort($column->getId(), SORT_ASC) }}">
			{{ Digbang\FontAwesome\Facade::icon('sort-asc') }}
		</a>
	@else
		<a href="{{ Digbang\L4Backoffice\Facades\Link::sort($column->getId(), SORT_DESC) }}">
			{{ Digbang\FontAwesome\Facade::icon('sort-desc') }}
		</a>
	@endif
@else
	<a href="{{ Digbang\L4Backoffice\Facades\Link::sort($column->getId(), SORT_ASC) }}">
		{{ Digbang\FontAwesome\Facade::icon('sort') }}
	</a>
@endif
