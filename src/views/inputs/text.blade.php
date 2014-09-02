@if($readonly)
{{ $value }}
@else
{{ Form::text($name, $value, $options) }}
@endif