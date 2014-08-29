<h5 class="sidebartitle">{{ $label }}</h5>
<ul {{ \HTML::attributes($options) }}>
	@foreach($actionTree as $action)
		@if ($action instanceof Digbang\L4Backoffice\Actions\Composite)
			<li class="nav-parent{{ $action->isActive() ? ' nav-active active' : '' }}">
				{{ $action->render() }}
			</li>
		@else
			<li class="{{ $action->isActive() ? 'active' : '' }}">
				{{ $action->render() }}
			</li>
		@endif
	@endforeach
</ul>