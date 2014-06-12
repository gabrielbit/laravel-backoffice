<div class="panel panel-default">
	{{ Form::open(['route' => $routes['store'], 'files' => $hasFile, 'class' => 'form-horizontal form-bordered']) }}
	<div class="panel-heading">
		<h4 class="panel-title">New {{ $title }}</h4>
		<div class="mt10">
			<a href="{{ $redirectCancel }}"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;{{ $redirectCancelMessage }}</a>
		</div>
	</div>
	<div class="panel-body panel-body-nopadding">
		@foreach($renderer->getAttributeRenderers() as $attributeRenderer)
		<div class="form-group{{ $errors->has($attributeRenderer->getName()) ? ' has-error' : '' }}">
			{{ $attributeRenderer->getLabel('col-sm-3 control-label') }}
			<div class="col-sm-6">
				{{ $attributeRenderer->render() }}
				@if($errors->has($attributeRenderer->getName()))
				@foreach($errors->get($attributeRenderer->getName()) as $error)
				<label for="{{ $attributeRenderer->getName() }}" class="error">{{ $error }}</label>
				@endforeach
				@endif
			</div>
		</div>
		@endforeach
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				{{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
				<a href="{{ $redirectCancel }}" class="btn btn-default">Cancel</a>
			</div>
		</div>
	</div>
	{{ Form::close() }}
</div>