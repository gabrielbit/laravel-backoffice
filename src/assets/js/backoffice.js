jQuery(document).ready(function() {
	// Copied from tables.js
	jQuery('#table1').dataTable();

	jQuery('#table2').dataTable({
		"sPaginationType": "full_numbers"
	});

	// Chosen Select
	jQuery("select:not(.multiselect):not(.select2)").each(function(){
		$(this).chosen({
			'width': "100%",
			'white-space': 'nowrap',
			disable_search_threshold: 10
		});
	});

	jQuery('form').on('reset', function(){
		jQuery('select', this).each(function(){
			jQuery(this).val('').trigger('chosen:updated');
		});
	});

	// Delete row in a table
	jQuery('.delete-row').click(function(){
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
					jQuery(this).remove();
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

	// Show aciton upon row hover
	jQuery('.table-hidaction tbody tr').hover(function(){
		jQuery(this).find('.table-action-hide a').animate({opacity: 1});
	},function(){
		jQuery(this).find('.table-action-hide a').animate({opacity: 0});
	});

	// --- copied from search-results.js
	// Basic Slider
	if (jQuery('#slider').length) {
		jQuery('#slider').slider({
			range: "min",
			max: 100,
			value: 50
		});
	}

	// Date Picker
	if (jQuery('.form-date').length){
		jQuery('.form-date').datepicker();
	}

	// Copied from form-validation.js (edited as well)

	// Basic Form
	jQuery("form").validate({
		highlight: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		}
	});

	jQuery('.shoutMe').each(function(){
		jQuery.gritter.add({
			title:      $(this).data('title') || 'Message',
			text:       $(this).text(),
			class_name: $(this).data('class_name'),
			image: $(this).data('image'),
			sticky: $(this).data('sticky') === 'true',
			time: $(this).data('time') || ''
		});
	});

	jQuery('.wysiwyg').wysihtml5({
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

	jQuery('.multiselect').multiSelect({
		selectableHeader: '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="Search...">',
		selectionHeader: '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="Search...">',
		afterInit: function(ms){
			var that = this,
				$selectableSearch = that.$selectableUl.prev(),
				$selectionSearch = that.$selectionUl.prev(),
				selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
				selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

			that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
				.on('keydown', function(e){
					if (e.which === 40){
						that.$selectableUl.focus();
						return false;
					}
				});

			that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
				.on('keydown', function(e){
					if (e.which == 40){
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

	$(document).on('click', '#show-more-link', function(ev) {
		$obj = $(this);

		$('.filter-advance').toggle();

		var new_text = $obj.data('text'),
			old_text = $obj.text();

		$obj.text(new_text);
		$obj.data('text', old_text);
	});

	$('.select2').each(function(){
		$(this).select2({
			closeOnSelect: $(this).attr('multiple')
		});
	});
});

function Export()
{
    var query = 'export=1';
    if(window.location.search != '')
    {
        query = '&' + query;
    }
    window.location.search = window.location.search + query
}