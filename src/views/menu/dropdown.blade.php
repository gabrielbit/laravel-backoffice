@if ($actions->count() > 0)
	@include('backoffice::menu.link', ['target' => $target, 'label' => $label, 'icon' => $icon, 'options' => []])
	@include('backoffice::menu.list', ['actionTree' => $actions, 'options' => ['class' => 'children', 'style' => ($isActive ? 'display: block' : '')]])
@endif