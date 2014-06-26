@extends('l4-backoffice::layouts.default')

@if(isset($title))
	@section('head.title', $title)
	@section('titlebar.title', $title)
@endif

@if(isset($breadcrumb))
	@section('titlebar.breadcrumb', $breadcrumb)
@endif

@section('panel.content')
	<div class="notice">
		Controllers generated for the following tables:
		<ul>
			@foreach($tables as $table)
			<li>{{ $table }}</li>
			@endforeach
		</ul>
		Please add this routes to your routes.php:
		<pre>Route::group(['prefix' => 'backoffice'], function(){
	...
@foreach($tables as $table)
	Route::resource('{{ $table }}', "{{ $backofficeNamespace }}\\{{ \Str::studly(\Str::singular($table)) }}Controller");
@endforeach
	...
});</pre>
	</div>
@stop