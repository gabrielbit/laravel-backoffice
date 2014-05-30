@extends('l4-backoffice::layouts.default')

@if(isset($title))
	@section('head.title', $title)
@endif

@if(isset($breadcrumb))
	@section('titlebar.breadcrumb', $breadcrumb)
@endif

@if(isset($actions))
	@section('titlebar.actions')
	@stop
@endif

@section('panel.content')
	{{ $list }}
@stop