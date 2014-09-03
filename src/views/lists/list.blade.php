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
						@include('l4-backoffice::actions.sort', ['column' => $column])
					@else
						{{ $column->getLabel() }}
					@endif
				</th>
			@endforeach
			@if($rowActions)
				<th>{{ Lang::get('l4-backoffice::default.actions') }}</th>
			@endif
		</tr>
	</thead>
	<tbody>
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
	</tbody>
</table>