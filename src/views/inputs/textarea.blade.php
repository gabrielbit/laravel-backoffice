@if($readonly)
<p class="form-control-static">{{ $value }}</p>
@else
{{ Form::textarea($name, $value, $options) }}
@endif