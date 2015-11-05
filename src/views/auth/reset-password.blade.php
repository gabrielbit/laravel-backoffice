@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			{{ Form::open(['route' => ["backoffice.auth.password.reset-request", $id], 'role' => 'form']) }}
				<h4 class="nomargin">{{ Lang::get('backoffice::auth.reset-password.title') }}</h4>
				<p class="mt5 mb20">{{ Lang::get('backoffice::auth.reset-password.text') }}</p>

				<div class="form-group">
					{{ Form::hidden('id', $id) }}
					{{ Form::hidden('reset_password_code', $resetCode) }}
				</div>
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					{{ Form::password('password', ['class' => 'form-control pword', 'placeholder' => Lang::get('backoffice::auth.password')]) }}
					@if ($errors->has('password'))
						@foreach ($errors->get('password') as $error)
							<label for="password" class="error">{{ $error }}</label>
						@endforeach
					@endif
				</div>
				<div class="form-group{{ $errors->has('confirm-password') ? ' has-error' : '' }}">
					{{ Form::password('password_confirmation', ['class' => 'form-control pword', 'placeholder' => Lang::get('backoffice::auth.password')]) }}
					@if ($errors->has('password_confirmation'))
						@foreach ($errors->get('password_confirmation') as $error)
							<label for="password_confirmation" class="error">{{ $error }}</label>
						@endforeach
					@endif
				</div>
				{{ Form::submit(Lang::get('backoffice::auth.reset-password.submit'), ['class' => 'btn btn-success btn-block']) }}
			{{ Form::close() }}
		</div>
	</div>

	<div class="signup-footer">
		<div class="pull-left">
			{{ Lang::get('backoffice::auth.copyright') }}
		</div>
	</div>
</div>
@stop