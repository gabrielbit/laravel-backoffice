@if($readonly)
<p class="form-control-static">{{ $value }}</p>
@else
{{ Form::text($name, $value, $options) }}
@endif