<?php
/*
+--------------------------------------------------------------------------------------------------------------+
| Wp Pro Advertising System - Asynchronous JS Tag                                                              |
| http://wpproads.com                                                                                          |
|                                                                                                              |
| Note:                                                                                                        |
| External sites need to be aproved in order to show ads.                                                      |
| Pro_Ads_Main.php add_allowed_origins()                                                                       |
|                                                                                                              |
| Usage:                                                                                                       |
| <ins data-wpas-zoneid="123"></ins>                                                                           |
| <script async src="http://yoursite.com/wp-admin/admin-ajax.php?action=wppas_asyncjs"></script>               |
+--------------------------------------------------------------------------------------------------------------+
*/
header("Content-Type: text/javascript");

$js = '';

$js.= '(function($) {';
	
	$js.= '$(function() {';
		$js.= '$("[data-wpas-zoneid]").each(function() {';
		
			$js .= 'var $this = $(this),';
			$js.= 'zoneid = $this.data("wpas-zoneid"),'; // data-wpas-zoneid="123"
			$js.= 'is_popup = $this.data("wpas-is-popup") && $this.data("wpas-is-popup") != 0 ? $this.data("wpas-is-popup") : 0,'; // data-wpas-is-popup=1
			$js.= 'is_flyin = $this.data("wpas-is-flyin") && $this.data("wpas-is-flyin") != 0 ? $this.data("wpas-is-flyin") : 0,'; // data-wpas-is-flyin=1
			$js.= 'is_corner_curl = $this.data("wpas-is-corner-curl") && $this.data("wpas-is-corner-curl") != 0 ? $this.data("wpas-is-corner-curl") : 0;'; // data-wpas-is-corner-curl=1
			$js.= 'is_bxslider = $this.data("wpas-is-bxslider") && $this.data("wpas-is-bxslider") != 0 ? $this.data("wpas-is-bxslider") : 0;'; // data-wpas-is-bxslider=1
			$js.= 'is_showoff = $this.data("wpas-is-showoff") && $this.data("wpas-is-showoff") != 0 ? $this.data("wpas-is-showoff") : 0;'; // data-wpas-is-showoff=1
			 
			// Load scripts & styles - for external websites.
				// http://stackoverflow.com/a/13412353
			$js.= 'window.wppas_ajax_script = "'.admin_url("admin-ajax.php").'";';
			$js.= '$.getScript("'.WP_ADS_TPL_URL.'/js/wppas.min.js");';
			// Corner curl
			$js.= 'if(is_corner_curl){';
				$js.= '$.getScript("'.WP_ADS_TPL_URL.'/js/corncurl.min.js");';
			$js.= '}';
			// BX SLider.
			$js.= 'if(is_bxslider){';
				$js.= '$.getScript("'.WP_ADS_TPL_URL . '/js/jquery.bxslider.min.pas.js");';
			$js.= '}';
			// BX SLider.
			$js.= 'if(is_showoff){';
				$js.= '$.getScript("'.WP_ADS_TPL_URL . '/js/jquery.jshowoff.min.js");';
			$js.= '}';
			$js.= '$("<link>").attr("rel","stylesheet").attr("type","text/css").attr("href","'.WP_ADS_TPL_URL.'/css/wppas.min.css").appendTo("head");';
			
			// Ajax call to load banners
			$js.= '$.ajax({';
				 $js.= 'type: "POST",';
				 $js.= 'url: "'.admin_url('admin-ajax.php').'",';
				 $js.= 'data: {';
				 	$js.= 'action: "pas_async_load_adzone",';
					$js.= 'adzone_id: zoneid,';
					$js.= 'is_popup: is_popup,';
					$js.= 'is_flyin: is_flyin,';
					$js.= 'is_corner_curl: is_corner_curl';
				 $js.= '}';
				 //$js.= 'data: "action=pas_async_load_adzone&adzone_id="+zoneid+"&is_popup="+is_popup';
			$js.= '}).done(function( obj ) {';
				$js.= '$this.html(obj);';
			$js.= '});';
		$js.= '});';
   $js.= ' });';
	   
$js.= '})(jQuery);';

echo $js;
?>