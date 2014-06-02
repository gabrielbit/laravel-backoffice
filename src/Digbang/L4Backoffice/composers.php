<?php
View::composer('l4-backoffice::menu', function(\Illuminate\View\View $view){
	$view->with(['menu' => Digbang\L4Backoffice\Facades\Menu::make()]);
});