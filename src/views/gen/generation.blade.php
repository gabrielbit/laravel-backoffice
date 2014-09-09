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
			<h5>Please add the new resources to your routes.php:</h5>
			<div class="well-lg">
				<pre>
					<code class="php">
Route::group(['prefix' => 'backoffice', 'before' => 'backoffice.auth.withPermissions'], function(){
	$bkNamespace = '{{ $backofficeNamespace }}';
	Route::get('/', ['as' => 'backoffice.index', 'uses' => "$bkNamespace\\HomeController@index"]);
	$resources = [
		// ... Add this new resources if it's not the first time you run the gen
@foreach($tables as $table)
		'{{ \Str::studly(\Str::singular($table)) }}' => '{{ $table }}',
@endforeach
		// ...
	];

	foreach ($resources as $name => $path)
	{
		Route::group(['prefix' => $path], function() use ($name, $path, $bkNamespace) {
			Route::get("/",              ["as" => "backoffice.$path.index",   "uses" => "$bkNamespace\\{$name}Controller@index",   "permission" => "backoffice.$path.list"]);
			Route::get("create",         ["as" => "backoffice.$path.create",  "uses" => "$bkNamespace\\{$name}Controller@create",  "permission" => "backoffice.$path.create"]);
			Route::post("/",             ["as" => "backoffice.$path.store",   "uses" => "$bkNamespace\\{$name}Controller@store",   "permission" => "backoffice.$path.create"]);
			Route::get("@{{$path}}",      ["as" => "backoffice.$path.show",    "uses" => "$bkNamespace\\{$name}Controller@show",    "permission" => "backoffice.$path.read"]);
			Route::get("@{{$path}}/edit", ["as" => "backoffice.$path.edit",    "uses" => "$bkNamespace\\{$name}Controller@edit",    "permission" => "backoffice.$path.update"]);
			Route::match(['PUT',
				'PATCH'], "@{{$path}}",   ["as" => "backoffice.$path.update",  "uses" => "$bkNamespace\\{$name}Controller@update",  "permission" => "backoffice.$path.update"]);
			Route::delete("@{{$path}}",   ["as" => "backoffice.$path.destroy", "uses" => "$bkNamespace\\{$name}Controller@destroy", "permission" => "backoffice.$path.delete"]);
			Route::get("export",         ['as' => "backoffice.$path.export",  "uses" => "$bkNamespace\\{$name}Controller@export",  "permission" => "backoffice.$path.list"]);
		});
	}
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