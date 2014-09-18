@include('l4-backoffice::actions.link', compact($target, $options, $label))

@section('body.content')
@parent
<div id="{{ $uniqid }}" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
				<h4 class="modal-title">&nbsp;</h4>
			</div>
			<div class="modal-body">
				{{ $form->render() }}
			</div>
		</div>
	</div>
</div>
@stop