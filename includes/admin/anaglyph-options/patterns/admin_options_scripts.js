;(function ($) {
	"use strict";
	
	// Changes Redux Subscribe fields
	$(document).ready(function () {

		var $ffc_stats_subscribe_name  = $('#ffc_subscribe_name');
		var $ffc_stats_subscribe_email = $('#ffc_subscribe_email');

		$ffc_stats_subscribe_name.attr('required', 'required');
		$ffc_stats_subscribe_email.attr('required', 'required');
		$ffc_stats_subscribe_email.attr('type', 'email');

		//var isValid = $ffc_stats_subscribe_email[0].checkValidity();
		//console.log( 'isValid', isValid );

	});
})(jQuery);


