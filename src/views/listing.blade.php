@if(count($items))
	@include('l4-backoffice::filters', ['filters' => $filters])
	<div class="row">
	    <div class="col-sm-12 col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="header-list">
						@if(count($bulkActions))
							@include('l4-backoffice::lists.bulk-actions', ['actions' => $bulkActions])
						@endif
						@if($paginator)
							@include('l4-backoffice::lists.pagination', ['paginator' => $paginator])
						@endif
					</div>
					<div class="results-list">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									@if(count($bulkActions))
										<th class="selectors">
											{{ Form::checkbox('all', 'all', null, ['class' => 'chk-all']) }}
										</th>
									@endif
									@foreach($columns as $column)
										<th>
											@if($column->sortable())
												@include('l4-backoffice::actions.sort', ['column' => $column])
											@else
												{{ $column->getLabel() }}
											@endif
										</th>
									@endforeach
									<th>{{ Lang::get('l4-backoffice::default.actions') }}</th>
								</tr>
							</thead>
							<tbody>
							@foreach($items as $row)
								<tr>
								@if(count($bulkActions))
									<td>
										{{ Form::checkbox('row', array_get($row, 'id'), null, ['class' => 'chk-bulk']) }}
									</td>
								@endif
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
					</div>
				</div>
			</div>
		</div>
	</div>
@else
	<div class="alert alert-danger">
		{{ Lang::get('l4-backoffice::default.empty_listing') }}
	</div>
@endif