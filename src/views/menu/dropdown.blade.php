<li class="nav-parent{{ isset($options['selected']) ? ' nav-active active' : '' }}">
	@include('l4-backoffice::menu.link', ['url' => array_get($options, 'url', ''),'link' => $item, 'icon' => array_get($options, 'icon'), 'options' => []])
	@include('l4-backoffice::menu.main', ['items' => $options['children'], 'options' => ['class' => 'children', 'style' => (isset($options['selected']) ? 'display: block' : '')]])
</li>