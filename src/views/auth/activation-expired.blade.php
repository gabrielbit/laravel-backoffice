@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			<p>{{ Lang::get('backoffice::auth.activation.expired.title') }}</p>
			<p>{{ Lang::get('backoffice::auth.activation.expired.link', ['email' => $email]) }}</p>
		</div>
	</div>
	<div class="signup-footer">
		<div class="pull-left">
			{{ Lang::get('backoffice::auth.copyright') }}
		</div>
	</div>
</div>
@stop