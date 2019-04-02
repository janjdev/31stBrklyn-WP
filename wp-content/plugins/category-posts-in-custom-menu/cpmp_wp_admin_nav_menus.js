(function($) {

	$(document).ready(function(){

		$('.cpmp-description > p.description:not(:first-child)').addClass('hidden');

		var toggle = function(){
			var checked = $(this).is(':checked');
			var items = $(this).parents('.cpmp-description').find('p.description:not(:first-child)');

			if(checked) {
				items.removeClass('hidden');
			} else {
				items.addClass('hidden');
			}
		};

		$('.cpmp-description > .field-cpcm-unfold input[type="checkbox"]').on('change', toggle).trigger('change');

	});

})(jQuery); // Fully reference jQuery after this point.
