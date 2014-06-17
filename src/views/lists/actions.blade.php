<div class="actions-container">
	@if($actions)
		@foreach($actions as $action)
			{{ $action->render() }}
		@endforeach
	@endif
	@if($bulkActions)
		@foreach($bulkActions as $bulkAction)
			{{ $bulkAction->render() }}
		@endforeach
	@endif
</div>