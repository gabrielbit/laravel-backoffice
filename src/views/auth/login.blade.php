@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			{{ Form::open(['route' => 'backoffice.auth.login', 'role' => 'form']) }}
			<h4 class="nomargin">{{ Lang::get('backoffice::auth.sign_in') }}</h4>
			<p class="mt5 mb20">{{ Lang::get('backoffice::auth.sign_in_msg') }}</p>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				{{ Form::text('email', Input::old('email'), ['class' => 'form-control uname', 'placeholder' => Lang::get('backoffice::auth.email')]) }}
				@if ($errors->has('email'))
					@foreach ($errors->get('email') as $error)
						<label for="email" class="error">{{ $error }}</label>
					@endforeach
				@endif
			</div>
			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				{{ Form::password('password', ['class' => 'form-control pword', 'placeholder' => Lang::get('backoffice::auth.password')]) }}
				@if ($errors->has('password'))
					@foreach ($errors->get('password') as $error)
						<label for="password" class="error">{{ $error }}</label>
					@endforeach
				@endif
			</div>
			<div class="form-group ckbox ckbox-success">
				{{ Form::checkbox('remember', 'on', false, ['id' => 'remember']) }}
				<label for="remember">{{ Lang::get('backoffice::auth.remember_me') }}</label>
			</div>
			<div class="form-group">
				{{ Form::submit(Lang::get('backoffice::auth.sign_in'), ['class' => 'btn btn-success btn-block']) }}
				{{ link_to_route('backoffice.auth.password.forgot', Lang::get('backoffice::auth.forgot_password'), [], ['class' => 'mt10 pull-right']) }}
			</div>
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