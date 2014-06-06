@include('l4-backoffice::filters', ['filters' => $filters])
<div class="row">
    <div class="col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="results-list">
					<table class="table">
						<thead>
							<tr>
							@foreach($columns as $column)
								<th>
									{{ $column->getLabel() }}
									@if($column->sortable())
										@include('l4-backoffice::actions.sort', ['column' => $column])
									@endif
								</th>
							@endforeach
								<th>{{ Lang::get('l4-backoffice::default.actions') }}</th>
							</tr>
						</thead>
						<tbody>
						@foreach($items as $row)
							<tr>
							@foreach($columns as $column)
								<td>{{ array_get($row, $column->getId(), '-') }}</td>
							@endforeach
								<td>
									@foreach($actions as $action)
										{{ $action->renderWith($row) }}
									@endforeach
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div><!-- results-list -->
			</div><!-- panel-body -->
		</div><!-- panel -->
	</div><!-- col-sm-8 -->
</div><!-- row -->