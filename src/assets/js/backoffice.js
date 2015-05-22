$(document).ready(function(){
	bootbox.setDefaults("locale", document.documentElement.getAttribute('lang') || 'en');

	$selects = $('select');
	// Select2
	$selects.filter(":not(.multiselect)").each(function(){
		$(this).select2({
			closeOnSelect: $(this).attr('multiple'),
			with: "resolve",
			allowClear: true
		});

		$(this).select2('readonly', $(this).is('[readonly]'));
	});

	// Delete row in a table
	$('.delete-row').click(function(){
		var theForm = $(this).parents('form').first();

		bootbox.confirm("Are you sure you want to delete this?", function(result) {
			if (result) {
				theForm.trigger('submit');
			}
		});

		return false;
	}).each(function(){
		$(this).parents('form').ajaxForm({
			success: function(responseText, statusText, xhr, $form){
				$form.closest('tr').fadeOut(function(){
					$(this).remove();
				});
			},
			error: function(jqXHR, textStatus, errorThrown){
				var response = jqXHR.responseJSON;

				jQuery.gritter.add({
					title: response.title || 'Error',
					text: response.message,
					class_name: 'growl-danger'
				});
			}
		});
	});

	// Show action upon row hover
	$('.table-hidaction tbody tr').hover(function(){
		$(this).find('.table-action-hide a').animate({opacity: 1});
	},function(){
		$(this).find('.table-action-hide a').animate({opacity: 0});
	});

	// --- copied from search-results.js
	// Basic Slider
	if ($('#slider').length) {
		$('#slider').slider({
			range: "min",
			max: 100,
			value: 50
		});
	}

	// Date Picker
	if ($('.form-date').length){
		$('.form-date').datepicker({
			dateFormat: "yy-mm-dd",
			onSelect: function(date){
				$(this).trigger('changeDate', date);
			}
		});
	}

	// Time Picker
	if ($('.form-time').length){
		$('.form-time').each(function(){
			$(this).timepicker({
				defaultTime: false,
				showMeridian: false,
				showSeconds: true,
				minuteStep: 5,
				secondStep: 5,
				disableFocus: true
			});

			$(this).on('focus', function(){
				return $(this).timepicker('showWidget');
			});
		});
	}

	// Combined datetime picker
	$('.form-datetime').each(function(){
		var
			$date   = $('.form-date', this),
			$time   = $('.form-time', this),
			$hidden = $('input[type=hidden]', this),
			updateHidden = function(date, time){
				$hidden.val(date + ' ' + time);
			};

		$('.form-date', this).on('changeDate', function(event, date){
			updateHidden(date, $time.val());
		});
		$('.form-time', this).on('changeTime.timepicker', function(event){
			updateHidden($date.val(), event.time.value);
		});
	});

	// Copied from form-validation.js (edited as well)

	// Basic Form
	$("form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		}
	});

	$('.shoutMe').each(function(){
		jQuery.gritter.add({
			title:      $(this).data('title') || 'Message',
			text:       $(this).text(),
			class_name: $(this).data('class_name'),
			image: $(this).data('image'),
			sticky: $(this).data('sticky') === 'true',
			time: $(this).data('time') || ''
		});
	});

	$('.wysiwyg').wysihtml5({
		"autoLink": true,
		"font-styles": false,
		"color": false,
		"emphasis": true,
		"lists": true,
		"html": false,
		"link": true,
		"image": false,
		"parserRules": {
			tags: {
				strong: {},
				b:      {},
				i:      {},
				em:     {},
				br:     {},
				p:      {},
				div:    {},
				span:   {},
				ul:     {},
				ol:     {},
				li:     {},
				a: {
					set_attributes: {
						target: "_blank",
						rel:    "nofollow"
					},
					check_attributes: {
						href:   "url" // important to avoid XSS
					}
				}
			}
		},
		stylesheets: ['../../../packages/digbang/laravel4-backoffice-scaffold/css/custom.css']
	});

	$selects.filter('.multiselect').each(function(){
		$(this).multiSelect({
			selectableHeader: '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="' + $(this).attr('placeholder') + '">',
			selectionHeader:  '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="' + $(this).attr('placeholder') + '">',
			selectableOptgroup: true,
			afterInit: function(ms){
				var that = this,
					$selectableSearch = that.$selectableUl.prev(),
					$selectionSearch = that.$selectionUl.prev(),
					selectableSearchString = '#' + that.$container.attr('id') + ' .ms-selectable ul.ms-list > li:visible',
					selectionSearchString  = '#' + that.$container.attr('id') + ' .ms-selection ul.ms-list > li:visible';

				that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
					.on('keydown', function(e){
						if (e.which === 13){
							that.$selectableUl.focus();
							return false;
						}
					});

				that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
					.on('keydown', function(e){
						if (e.which == 13){
							that.$selectionUl.focus();
							return false;
						}
					});
			},
			afterSelect: function(){
				this.qs1.cache();
				this.qs2.cache();
			},
			afterDeselect: function(){
				this.qs1.cache();
				this.qs2.cache();
			}
		});
	});

	$(document).on('click', '#show-more-link', function(ev) {
		$obj = $(this);

		$('.filter-advance').toggle();

		var new_text = $obj.data('text'),
			old_text = $obj.text();

		$obj.text(new_text);
		$obj.data('text', old_text);
	});
	$(document).on('change', 'input.chk-all', function(){
		$('input.chk-bulk').attr('checked', $(this).is(':checked'));

		if ($(this).is(':checked'))
		{
			$('.actions-bulk').show();
		}
		else
		{
			$('.actions-bulk').hide();
		}
	});

	$(document).on('change', 'input.chk-bulk', function(){
		var $bulkInputs = $('input.chk-bulk');

		var total   = $bulkInputs.length,
			checked = $bulkInputs.filter(':checked').length,
			$all    = $('input.chk-all');

		$all.get(0).indeterminate = (checked > 0 && checked != total);

		if (checked == total)
		{
			$all.attr('checked', 'checked');
		}
		else
		{
			$all.attr('checked', null);
		}

		if (checked > 0)
		{
			$('.actions-bulk').show();
		}
		else
		{
			$('.actions-bulk').hide();
		}
	});

	$('.actions-bulk form').each(function(){
		$(this).on('submit', function(){
			var $self = $(this);
            $self.find('.bulk-row').remove();

            $('.chk-bulk:checked').each(function(){
                var $hidden = $('<input type="hidden">');
                $hidden.attr('class', 'bulk-row');
                $hidden.attr('name', 'row[]');
                $hidden.val($(this).val());

                $self.append($hidden);
            });
		});
	});
	var menuState = new function(){
		var collapsed = jQuery.cookie('leftpanel-collapsed') != undefined,
			collapse = function(){
				if (!collapsed) {
					jQuery.cookie('leftpanel-collapsed', 'leftpanel-collapsed');
					collapsed = !collapsed;
				}
			},
			expand = function(){
				if (collapsed) {
					jQuery.removeCookie('leftpanel-collapsed');
					collapsed = !collapsed;
				}
			};

		this.toggle = function(){
			if (collapsed) {
				console.log('removing cookie...');
				expand();
			} else {
				console.log('creating cookie...');
				collapse();
			}
		}
	};

	$(document).on('click', '.menutoggle', function(){
		menuState.toggle();
	});

	$('[data-toggle="tooltip"]').tooltip();

	// Hook up on any button that needs confirmation and alert the user
	$('button[data-confirm]').click(function(){
		var theForm = $(this).parents('form').first(),
			message = $(this).data('confirm');

		bootbox.confirm(message, function(result) {
			if (result) {
				theForm.trigger('submit');
			}
		});

		return false;
	});

	// ColorPicker
	$('.colorpicker-container').each(function(){
		$input = $('input[type=text]', this);
		$preview = $('.preview', this);

		$('.colorselector', this).ColorPicker({
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				var hexValue = '#' + hex;

				$preview.css('backgroundColor', hexValue);
				$input.val(hexValue);
			}
		});
	});

	// Cancel buttons on modal close modals instead
	$('.modal .js-btn-cancel').each(function(){
		$(this).attr('data-dismiss', 'modal');
	});
});