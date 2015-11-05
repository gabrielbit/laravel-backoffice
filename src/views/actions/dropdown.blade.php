<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		{{ $label }}<span class="caret"></span>
	</button>
	@include('backoffice::common.list', ['items' => $actions, 'options' => ['class' => 'dropdown-menu', 'role' => 'menu']])
</div>

