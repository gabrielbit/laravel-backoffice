$(document).ready(function(){
	$('.select2').each(function(){
		$(this).select2();
		
		if($(this).attr('multiple'))
		{
			$(this).select2({
				'closeOnSelect': false
			});
		}
	});
});