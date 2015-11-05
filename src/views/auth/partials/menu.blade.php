@if($user)
<ul class="headermenu">
	<li>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{{ trim(trans('backoffice::auth.user_name', ['name' => $user->getFirstName(), 'lastname' => $user->getLastName()])) ?: $user->getEmail() }}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-usermenu pull-right">
				<li>
					<a href="{{ route('backoffice.auth.logout') }}">
						<i class="fa fa-sign-out"></i> {{ Lang::get('backoffice::auth.sign_out') }}
					</a>
				</li>
			</ul>
		</div>
	</li>
</ul>
@endif