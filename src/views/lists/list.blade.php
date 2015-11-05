<table class="table table-striped table-bordered">
	<thead>
	<tr>
		@if(count($bulkActions))
		<th class="selectors">
			{{ Form::checkbox('all', 'all', null, ['class' => 'chk-all']) }}
		</th>
		@endif
		@foreach($columns as $column)
		<th>
			@if($column->sortable())
				@include('backoffice::actions.sort', ['column' => $column])
			@else
				{{ $column->getLabel() }}
			@endif
		</th>
		@endforeach
		@if($rowActions)
		<th>{{ Lang::get('backoffice::default.actions') }}</th>
		@endif
	</tr>
	</thead>
	<tbody>
	@if(count($items))
	@foreach($items as $row)
	<tr>
		@if(count($bulkActions))
		<td>
			{{ Form::checkbox('row', array_get($row, 'id'), null, ['class' => 'chk-bulk']) }}
		</td>
		@endif
		@foreach($columns as $column)
		<td>{{ \Str::parse($column->getValue($row)) ?: '-' }}</td>
		@endforeach
		@if($rowActions)
		<td class="row-actions">
			@foreach($rowActions as $action)
				{{ $action->renderWith($row) }}
			@endforeach
		</td>
		@endif
	</tr>
	@endforeach
	@else
	<tr>
		<td colspan="{{ count($columns) + 1 }}" class="text-danger">
			{{ Lang::get('backoffice::default.empty_listing') }}
		</td>
	</tr>
	@endif
	</tbody>
</table>