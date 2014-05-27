<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="shortcut icon" href="images/favicon.png" type="image/png">

	<title>@yield('head.title', Lang::get('default.backoffice')</title>

	<!--[if lt IE 9]>
	{{ HTML::script('packages/digbang/l4-backoffice/js/ie8compat.js', ['type' => 'text/javascript']) }}
	<![endif]-->

	{{ HTML::style('packages/digbang/l4-backoffice/css/backoffice.css') }}
	@yield('head.stylesheets')
    @yield('head.javascripts')
</head>
<body class="@yield('body.class')">
<section>
	@yield('body.content')
</section>
@foreach(['info', 'warning', 'danger', 'success', 'primary'] as $message)
	@if(\Session::has($message))
		<div class="shoutMe" data-class_name="growl-{{ $message }}">{{ \Session::remove($message) }}</div>
	@endif
@endforeach
{{ HTML::script('packages/digbang/l4-backoffice/js/backoffice.js', ['type' => 'text/javascript']) }}
@yield('body.javascripts')
</body>
</html>