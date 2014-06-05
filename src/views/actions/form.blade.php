{{ Form::open(['url' => $target, 'class' => 'form-actions', 'role' => 'form']) }}
	<button type="submit"{{ HTML::attributes($options) }}>
		{{ $label }}
	</button>
{{ Form::close() }}