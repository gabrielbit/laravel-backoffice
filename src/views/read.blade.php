<div class="panel panel-default">
	<div class="form-horizontal form-bordered">
		<div class="panel-heading">
			<h4 class="panel-title">{{ $label }}</h4>
			@if(!empty($topActions))
			<div class="mt10">
				@foreach($topActions as $action)
					{{ $action->render() }}
				@endforeach
			</div>
			@endif
		</div>
		<div class="panel-body panel-body-nopadding">
			<div class="form-horizontal form-bordered">
				@foreach($data as $label => $value)
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ $label }}</label>
					<div class="col-sm-6">
						<p class="form-control-static">{{ \Str::parse($value) }}</p>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					@foreach($actions as $action)
					{{ $action->render() }}
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>