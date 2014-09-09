$(document).on('change', '.user-groups', function(){
	var permissions = $(this).data('permissions'),
		groups = $(this).val();

	$('#permissions').multiSelect('deselect_all');

	$(groups).each(function(){
		$('#permissions').multiSelect('select', permissions[this]);
	});
});