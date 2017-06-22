jQuery(document).ready( function($) {
jQuery("#modify_button").click(function() {
	jQuery(this).next("#modify_form").stop().slideToggle( "slow" ); 
	});
 });