{{ Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-filter']) }}
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="row">
			@foreach(array_slice($filters->toArray(), 0, 2) as $filter)
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					{{ $filter->render() }}
				</div>
			</div>
			@endforeach
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					{{ Form::submit(Lang::get('l4-backoffice::default.search'), ['class' => 'btn btn-primary']) }}
					<a href="{{ Request::url() }}" class="btn btn-default">{{ Lang::get('l4-backoffice::default.reset') }}</a>
					@if(count($filters) > 2)
                        <span class="show-more-link">
                            <a href="javascript:void(0);" onclick="$('.filter-advance').toggle('fast')">{{ Lang::get('l4-backoffice::default.advanced_search') }}</a>
                        </span>
					@endif
				</div>
			</div>
		</div>
		@if(count($filters) > 2)
			@foreach(array_chunk(array_slice($filters->toArray(), 2), 2) as $filterRow)
			<div class="row filter-advance">
				@foreach($filterRow as $filter)
				<div class="col-sm-4 col-md-4">
					<div class="form-group">
						{{ $filter->render() }}
					</div>
				</div>
				@endforeach
			</div>
			@endforeach
		@endif
	</div>
</div>
{{ Form::close() }}