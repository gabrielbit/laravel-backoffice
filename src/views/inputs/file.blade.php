@if($readonly)
<p class="form-control-static">{{ $value }}</p>
@else
{{ Form::file($name, $options) }}
@endif