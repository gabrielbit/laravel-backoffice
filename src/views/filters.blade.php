{{ Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-filter']) }}
<div class="row">
	<div class="col-sm-9">
		<div class="row">
			@foreach($filters->slice(0, 2) as $filter)
			<div class="col-sm-6">
				<div class="form-group">
					{{ $filter->render() }}
				</div>
			</div>
			@endforeach
		</div>
		@if(count($filters) > 2)
			@foreach($filters->slice(2)->chunk(2) as $filterRow)
			<div class="row filter-advance">
				@foreach($filterRow as $filter)
				<div class="col-sm-6">
					<div class="form-group">
						{{ $filter->render() }}
					</div>
				</div>
				@endforeach
			</div>
			@endforeach
		@endif
	</div>
	<div class="col-sm-3">
		<div class="form-group pull-right">
			{{ Form::submit(Lang::get('l4-backoffice::default.search'), ['class' => 'btn btn-primary']) }}
			<a href="{{ Request::url() }}" class="btn btn-default">{{ Lang::get('l4-backoffice::default.reset') }}</a>
			@if(count($filters) > 2)
                    <span class="show-more-link">
                        <a id="show-more-link" href="#" data-text="{{ Lang::get('l4-backoffice::default.simple_filters') }}">{{ Lang::get('l4-backoffice::default.advanced_filters') }}</a>
                    </span>
			@endif
		</div>
	</div>
</div>
{{ Form::close() }}