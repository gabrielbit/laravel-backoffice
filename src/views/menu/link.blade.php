<a href="{{ $url }}"{{ \HTML::attributes($options) }}>
	@if($icon) {{ Digbang\FontAwesome\Facade::icon($icon) }} @endif
	<span>{{ $link }}</span>
</a>