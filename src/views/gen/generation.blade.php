@extends('l4-backoffice::layouts.default')

@if(isset($title))
	@section('head.title', $title)
	@section('titlebar.title', $title)
@endif

@if(isset($breadcrumb))
	@section('titlebar.breadcrumb', $breadcrumb)
@endif

@section('panel.content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">{{ $title }}</h4>
	</div>
	<div class="panel-body">
		<div class="notice">
			<h5>Controllers generated for the following tables:</h5>
			<dl class="dl-horizontal">
				@foreach($tables as $table)
					<dt>{{ $table }}</dt>
					<dd>{{ $backofficeNamespace }}\\{{ \Str::studly(\Str::singular($table)) }}Controller</dd>
				@endforeach
			</dl>
			<h5>Please add this routes to your routes.php:</h5>
			<div class="well-lg">
				<pre>
					<code class="php">
Route::group(['prefix' => 'backoffice'], function(){
	...
@foreach($tables as $table)
	Route::resource('{{ $table }}', "{{ $backofficeNamespace }}\\{{ \Str::studly(\Str::singular($table)) }}Controller");
@endforeach
	...
});
					</code>
				</pre>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
	</div>
</div>
@stop
@section('body.javascripts')
	<link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">
	<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
	<script>
		hljs.initHighlightingOnLoad();
	</script>
	<style>
		pre, code {
			background: #f8f8ff;
			font-family: monospace;
		}
	</style>
@append