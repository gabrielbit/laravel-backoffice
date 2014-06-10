<div class="bulk-actions-container">
	@foreach($actions as $action)
		{{ $action->render() }}
	@endforeach
</div>