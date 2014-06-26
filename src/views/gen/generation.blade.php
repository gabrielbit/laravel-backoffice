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
	</div>
@stop