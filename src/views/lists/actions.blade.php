<div class="actions-container">
	@if($actions)
	<div class="actions">
		@foreach($actions as $action)
			{{ $action->render() }}
		@endforeach
	</div>
	@endif
	@if($bulkActions)
	<div class="actions actions-bulk">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{{ \Lang::get('l4-backoffice::default.bulk-actions') }} <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				@foreach($bulkActions as $bulkAction)
				<li>{{ $bulkAction->render() }}</li>
				@endforeach
			</ul>
		</div>
	</div>
	@endif
</div>