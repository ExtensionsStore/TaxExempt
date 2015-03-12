/**
 * TaxExempt js
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */

function TaxExempt($) 
{
	var check = function(e) {
		
		var $checkbox = $(this);
		var $list = $('#taxexempt-list');

		if ($checkbox.is(':checked')) {

			$list.show();

		} else {
			
			$list.hide();
		}
	};

	return {
		init : function() {
			
			var $taxExemptCheckbox = $('#taxexempt-checkbox');
			$taxExemptCheckbox.click(check).next('label').click(function(e){
				$(this).prev('input').click();
			});
			
			if ($taxExemptCheckbox.is(':checked')){
				$('#taxexempt-list').show();
			}
			
			setTimeout(function(){
				$('.taxexempt-field').removeAttr('disabled');
			},500);
			
		}
	
	};
	
}

if (!window.jQuery) {

	document.write('<script src="//ajax.googleapis.com/ajax/libs/$/1.11.2/$.min.js">\x3C/script><script>$.noConflict(); var taxexempt = new TaxExempt(jQuery); jQuery(function(){ taxexempt.init(); });</script>');

} else {

	var taxexempt = new TaxExempt(jQuery);
	jQuery(function() {
		taxexempt.init();
	});
}