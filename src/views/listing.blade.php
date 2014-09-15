@include('l4-backoffice::filters', ['filters' => $filters])
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="header-list">
					@if($actions || $bulkActions)
						@include('l4-backoffice::lists.actions', ['actions' => $actions, 'bulkActions' => $bulkActions])
					@endif
					@if($paginator)
						@include('l4-backoffice::lists.pagination', ['paginator' => $paginator])
					@endif
				</div>
				<div class="results-list">
					@include('l4-backoffice::lists.list', ['bulkActions' => $bulkActions, 'columns' => $columns, 'rowActions' => $rowActions, 'items' => $items])
				</div>
			</div>
		</div>
	</div>
</div>