@if($label)
<label for="{{ $name }}">{{ $label }}</label>
@endif
{{ Form::select($name, $data, $value, (array) $options) }}