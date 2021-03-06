@extends('l4-backoffice::layouts.empty')

@section('body.navigation')
	@include('l4-backoffice::menu')
@stop

@if(Cookie::get('leftpanel-collapsed'))
	@section('body.class', 'leftpanel-collapsed')
@endif

@section('body.content')
<div class="leftpanel">
	@section('body.logo')
	<div class="logopanel">
		<h1><span>[</span> @yield('body.title', Lang::get('l4-backoffice::default.backoffice')) <span>]</span></h1>
	</div>
	@show
	<div class="leftpanelinner">
		@yield('body.navigation')
	</div>
</div>
<div class="mainpanel">
	@section('body.header')
	<div class="headerbar">
		<a class="menutoggle"><i class="fa fa-bars"></i></a>
		<div class="header-right">
			@include('l4-backoffice::auth.partials.menu')
		</div>
	</div>
	@show
	<div class="pageheader">
		<h2>@yield('titlebar.title')</h2>
		@yield('titlebar.actions')
		<div class="breadcrumb-wrapper">
			@yield('titlebar.breadcrumb')
		</div>
	</div>
	<div class="contentpanel">
		@yield('panel.content')
	</div>
</div>
@stop