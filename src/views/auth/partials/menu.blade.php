@if($user)
<ul class="headermenu">
	<li>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{{ $user->first_name }} {{ $user->last_name }}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-usermenu pull-right">
				<li>
					<a href="{{ route('backoffice.auth.logout') }}">
						<i class="fa fa-sign-out"></i> Sign Out
					</a>
				</li>
			</ul>
		</div>
	</li>
</ul>
@endif