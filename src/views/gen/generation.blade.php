@extends('backoffice::layouts.default')

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
			<h5>Controllers generated for the following APIs:</h5>
			<dl class="dl-horizontal clearfix">
				@foreach($apis as $controller => $api)
					<dt style="width: 500px">{{ $api }}</dt>
					<dt style="width: 500px">{{ $controller }}</dt>
				@endforeach
			</dl>
			<h5>Please add the new resources to your <strong>BackofficeRouteBinder</strong>:</h5>
			<div class="well-lg">
				<pre>
					<code class="php">
// At the top, in the use section
@foreach($controllers as $controller => $methods)
use {{ $controller }};
@endforeach

// Inside the class, in the constants section
@foreach($controllers as $controller => $methods)
@foreach ($methods as $method)
@if($method == 'UPDATE')
const {{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_EDIT = 'backoffice.{{ str_plural(snake_case(str_replace('Controller', '', class_basename($controller)))) }}.edit';
@endif
const {{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_{{ strtoupper($method) }} = 'backoffice.{{ str_plural(snake_case(str_replace('Controller', '', class_basename($controller)))) }}.{{ strtolower($method) }}';
@if($method == 'CREATE')
const {{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_STORE = 'backoffice.{{ str_plural(snake_case(str_replace('Controller', '', class_basename($controller)))) }}.store';
@endif
@endforeach
@endforeach

// Then, in the bind method
    public function bind(Router $router)
    {
        // ...
@foreach($controllers as $controller => $methods)
        $router->group(['prefix' => '{{ str_plural(snake_case(str_replace('Controller', '', class_basename($controller)))) }}'], function() use ($router) {
@if(in_array('index', $methods))
            $router->get('/', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_LIST, 'uses' => {{ class_basename($controller) }}::class . '{{ '@index' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_LIST]);
@endif
@if(in_array('create', $methods))
            $router->get('create', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_CREATE, 'uses' => {{ class_basename($controller) }}::class . '{{ '@create' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_CREATE]);
            $router->post('/', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_STORE, 'uses' => {{ class_basename($controller) }}::class . '{{ '@store' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_CREATE]);
@endif
@if(in_array('read', $methods))
            $router->get('{{ '{ ' . (snake_case(str_replace('Controller', '', class_basename($controller)))) . ' }' }}', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_READ, 'uses' => {{ class_basename($controller) }}::class . '<?= '@show' ?>', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_READ]);
@endif
@if(in_array('update', $methods))
            $router->get('{{ '{' . (snake_case(str_replace('Controller', '', class_basename($controller)))) . '}' }}/edit', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_EDIT, 'uses' => {{ class_basename($controller) }}::class . '{{ '@edit' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_UPDATE]);
            $router->match(['PUT', 'PATCH'], '{{ '{ ' . (snake_case(str_replace('Controller', '', class_basename($controller)))) . ' }' }}', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_UPDATE, 'uses' => {{ class_basename($controller) }}::class . '{{ '@update' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_UPDATE]);
@endif
@if(in_array('delete', $methods))
            $router->delete('{{ '{ ' . (snake_case(str_replace('Controller', '', class_basename($controller)))) . ' }' }}', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_DELETE, 'uses' => {{ class_basename($controller) }}::class . '{{ '@destroy' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_DELETE]);
@endif
@if(in_array('export', $methods))
            $router->get('export', ['as' => self::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_EXPORT, 'uses' => {{ class_basename($controller) }}::class . '{{ '@export' }}', 'permission' => Permission::{{ strtoupper(str_plural(snake_case(str_replace('Controller', '', class_basename($controller))))) }}_LIST]);
@endif
        });
@endforeach
    }
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