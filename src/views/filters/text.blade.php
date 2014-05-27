<input type="text"
	@if($value) value="{{{ $value }}}" @endif
	@if($label) title="{{{ $label }}}" @endif
	@foreach($options as $key => $value)
		{{{ $key }}}="{{{ $value }}}"
	@endforeach
/>