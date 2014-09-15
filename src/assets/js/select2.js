$(document).ready(function(){
	$('.select2').each(function(){
		$(this).select2();
		
		if($(this).attr('multiple'))
		{
			$(this).select2({
				'closeOnSelect': false
			});
		}

		if ($(this).attr('readonly'))
		{
			$(this).select2('readonly', true);
		}
	});
});