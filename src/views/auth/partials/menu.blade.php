@if($user)
<ul class="headermenu">
	<li>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{{ Lang::get('l4-backoffice::auth.user_name', ['name' => $user->first_name, 'lastname' => $user->last_name]) }}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-usermenu pull-right">
				<li>
					<a href="{{ route('backoffice.auth.logout') }}">
						<i class="fa fa-sign-out"></i> {{ Lang::get('l4-backoffice::auth.sign_out') }}
					</a>
				</li>
			</ul>
		</div>
	</li>
</ul>
@endif