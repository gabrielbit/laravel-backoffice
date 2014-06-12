<div class="panel panel-default">
	<div class="form-horizontal form-bordered">
		<div class="panel-heading">
			<h4 class="panel-title">{{ $label }}</h4>
			<div class="mt10">
				<a href="{{ $backAction }}"><i class="fa fa-arrow-left"></i> {{ Lang::get('l4-backoffice::default.back') }}</a>
			</div>

		</div>
		<div class="panel-body panel-body-nopadding">
			@foreach($data as $label => $value)
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ $label }}</label>
				<div class="col-sm-6"><p class="form-control-static">{{ $value }}</p></div>
			</div>
			@endforeach
		</div>
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<a href="{{ $editAction }}" class="btn btn-success">{{ Lang::get('l4-backoffice::default.edit') }}</a>
					<a href="{{ $backAction }}" class="btn btn-default">{{ Lang::get('l4-backoffice::default.back') }}</a>
				</div>
			</div>
		</div>
	</div>
</div>