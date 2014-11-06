@if ($actions->count() > 0)
	@include('l4-backoffice::menu.link', ['target' => $target, 'label' => $label, 'icon' => $icon, 'options' => []])
	@include('l4-backoffice::menu.list', ['actionTree' => $actions, 'options' => ['class' => 'children', 'style' => ($isActive ? 'display: block' : '')]])
@endif