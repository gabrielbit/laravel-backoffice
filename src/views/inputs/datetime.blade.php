<div class="form-group form-datetime" {{ HTML::attributes($options) }}>
	<div class="row">
		<input type="hidden" name="{{ $name }}" value="{{ $value ? $value->format('Y-m-d H:i:s') : '' }}" />
		<div class="col-xs-6">
			<div class="input-group">
				<input name="{{ $name }}_date" id="{{ $name }}_date" type="text" class="form-date form-control" placeholder="{{ $label }} date" value="{{ $value ? $value->format('Y-m-d') : '' }}" style="position: relative; z-index: 2000" />
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="input-group">
				@include('backoffice::inputs.time', ['name' => "{$name}_time", 'label' => "$label time", 'options' => ['style' => 'position: relative; z-index: 2000'], 'value' => $value ? $value->format('H:i:s') : ''])
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			</div>
		</div>
	</div>
</div>