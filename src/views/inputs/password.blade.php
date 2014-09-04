@if($readonly)
<p class="form-control-static">****************</p>
@else
{{ Form::password($name, $options) }}
@endif