<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		{{ Form::checkbox('all') }}<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li><a href="#">{{ Lang::get('l4-backoffice::default.select-all') }}</a></li>
		<li><a href="#">{{ Lang::get('l4-backoffice::default.select-none') }}</a></li>
	</ul>
</div>