@foreach($allPermissions as $group => $permissions)
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a href="#permission-table" class="minimize maximize">&plus;</a>
		</div>
		<h3 class="panel-title">{{ $group }}</h3>
	</div>
	<table id="permission-table" class="table table-striped panel-body" style="display: none">
		<tbody>
			@foreach($permissions as $permission => $name)
			<tr>
				<td style="width: 90%">{{ $name }}</td>
				<td style="width: 10%">
					@if ($userOrGroup->hasAccess($permission))
					<i class="fa fa-check fa-2x text-success"></i>
					@else
					<i class="fa fa-ban fa-2x text-danger"></i>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endforeach