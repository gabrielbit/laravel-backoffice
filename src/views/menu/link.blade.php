<a href="{{ $target }}"{{ \HTML::attributes($options) }}>
	@if($icon) {{ Digbang\FontAwesome\Facade::icon($icon) }} @endif
	<span>{{ $label }}</span>
</a>