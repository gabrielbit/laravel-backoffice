<div class="colorpicker-container">
	<div class="col-xs-1">
		<span class="colorselector">
			<span class="preview"{{ $value ? " style=\"background-color: $value;\"" : '' }}></span>
		</span>
	</div>
	@unless($readonly)
	<div class="col-xs-3">
		{{ Form::text($name, $value, $options) }}
	</div>
	@endunless
</div>