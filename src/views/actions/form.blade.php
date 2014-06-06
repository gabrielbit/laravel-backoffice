{{ Form::open(['url' => $target, 'method' => $method, 'class' => 'form-actions', 'role' => 'form']) }}
	<button type="submit"{{ HTML::attributes($options) }}>
		{{ $label }}
	</button>
{{ Form::close() }}