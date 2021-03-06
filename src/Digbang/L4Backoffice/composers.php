<?php
View::composer('l4-backoffice::menu', function(\Illuminate\View\View $view){
	$view->with(['menus' => Digbang\L4Backoffice\Facades\Menu::make()]);
});

View::composer('l4-backoffice::auth.partials.menu', function(\Illuminate\View\View $view){
	$sentry = \App::make('sentry');
	$view->with(['user' => $sentry->getUser()]);
});