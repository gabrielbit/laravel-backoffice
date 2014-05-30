@if($label)
<h3>{{ $label }}</h3>
@endif
<ol class="breadcrumb" {{ HTML::attributes($options) }}>
	@foreach($items as $label => $route)
		@if(is_string($label))
			<li><a href="{{ $route }}">{{ $label }}</a></li>
		@else
			<li class="active">{{ $label }}</li>
		@endif
	@endforeach
</ol>