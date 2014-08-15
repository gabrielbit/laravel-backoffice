@extends('l4-backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('l4-backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			<p>{{ Lang::get('l4-backoffice::auth.activation.expired.title') }}</p>
			<p>{{ Lang::get('l4-backoffice::auth.activation.expired.link', ['email' => $email]) }}</p>
		</div>
	</div>
	<div class="signup-footer">
		<div class="pull-left">
			{{ Lang::get('l4-backoffice::auth.copyright') }}
		</div>
	</div>
</div>
@stop