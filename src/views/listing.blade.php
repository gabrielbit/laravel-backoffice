<div class="row">
    <div class="col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="results-list">
					<table class="table">
						<thead>
							<tr>
							@foreach($columns as $column)
								<th>{{ $column->getLabel() }}</th>
							@endforeach
							</tr>
						</thead>
						<tbody>
						@foreach($items as $row)
							<tr>
							@foreach($columns as $column)
								<td>{{ $row[$column->getId()] }}</td>
							@endforeach
							</tr>
						@endforeach
						</tbody>
					</table>
				</div><!-- results-list -->
			</div><!-- panel-body -->
		</div><!-- panel -->
	</div><!-- col-sm-8 -->
</div><!-- row -->