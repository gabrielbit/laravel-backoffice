<ul {{ \HTML::attributes($options) }}>
	@foreach($items as $item => $options)
        @if (isset($options['children']))
			@include('l4-backoffice::menu.dropdown', ['options' => $options, 'item' => $item])
        @else
            <li{{ isset($options['selected']) ? ' class="active"' : '' }}>
                @include('l4-backoffice::menu.link', ['url' => array_get($options, 'url', '#'),'link' => $item,'icon' => array_get($options, 'icon'),'options' => []])
            </li>
        @endif
	@endforeach
</ul>