<div class="checkbox">
	<label for="{{ $name }}"{{ HTML::attributes(array_diff_key($options, ['id' => 'id'])) }}>
		{{ Form::checkbox($name, 'On', $value, ['id' => $options['id']]) }}
	</label>
</div>