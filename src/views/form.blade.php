<div class="panel panel-default">
	{{ Form::open($formOptions) }}
	<div class="panel-heading">
		<h4 class="panel-title">{{ $label }}</h4>
	</div>
	<div class="panel-body panel-body-nopadding">
		@foreach($inputs as $input)
		<div class="form-group{{ $errors->hasAny((array) $input->name()) ? ' has-error' : '' }}">
			<label for="{{ implode(' ', (array) $input->name()) }}" class="col-sm-3 control-label">
				{{ implode('/', (array) $input->label()) }}
			</label>
			<div class="col-sm-6">
				{{ $input->render() }}
				@if($errors->hasAny((array) $input->name()))
					@foreach($errors->get($input->name()) as $error)
						<label for="{{ implode(' ', (array) $input->name()) }}" class="error">{{ $error }}</label>
					@endforeach
				@endif
			</div>
		</div>
		@endforeach
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				{{ Form::submit($label, ['class' => 'btn btn-primary']) }}
				<a href="{{ $cancelAction }}" class="btn btn-default">Cancel</a>
			</div>
		</div>
	</div>
	{{ Form::close() }}
</div>