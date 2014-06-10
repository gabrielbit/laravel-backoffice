<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		{{ $label }}<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		@foreach($actions as $action)
			<li>{{ $action->render() }}</li>
		@endforeach
	</ul>
</div>

