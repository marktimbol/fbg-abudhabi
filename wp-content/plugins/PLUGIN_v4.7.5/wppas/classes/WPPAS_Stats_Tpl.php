<?php
/**
 * Array stats templates
 * @since v4.7.5
*/
class WPPAS_Stats_Tpl {	
	
	public $year;
	public $month;
	public $day;
	
	public function __construct() 
	{
		global $wppas_stats;
		
		$this->year = $wppas_stats->year;
		$this->month = $wppas_stats->month;
		$this->day = $wppas_stats->day;
	}
	
	
	
	
	
	
	
	
	/**
	 * STATS TEMPLATE
	*/
	public function stats_tpl($args = array())
	{
		global $wppas_stats;
		
		$defaults = array(
			'year'             => $this->year,
			'month'            => $this->month,
			'day'              => $this->day,
			's_type'           => 'day',
			'unique'           => 0,
			'select'           => $wppas_stats->default_select()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		
		$html = '';
		$style = '';
		$unique_str = $args['unique'] ? __('unique','wpproads').' ' : '';
		
		if( $args['s_type'] == 'day' )
		{
			$sdate = mktime(0,0,0, $args['month'], $args['day'], $args['year']);
			$edate = mktime(23,59,59, $args['month'], $args['day'], $args['year']);
			$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
			//echo '<pre>'.print_r($stats,true).'</pre>';
			
			$impr = $wppas_stats->get_daily_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions', 'unique' => $args['unique']), $args));
			$clicks = $wppas_stats->get_daily_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks', 'unique' => $args['unique']), $args));
			$text = array(
				'click'      => sprintf(__(ucfirst($unique_str).'Clicks on %s:', 'wpproads'), date_i18n('l, M d', $sdate)),
				'impression' => sprintf(__(ucfirst($unique_str).'Impressions on %s:', 'wpproads'),date_i18n('l, M d', $sdate)),
				'ctr'        => sprintf(__('CTR (%s):', 'wpproads'),date_i18n('l, F d', $sdate)),
			);
		}
		elseif( $args['s_type'] == 'month')
		{
			$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
			$sdate = mktime(0,0,0, $args['month'], 1, $args['year']);
			$edate = mktime(23,59,59, $args['month'], $am_days, $args['year']);
			$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
			
			$impr = $wppas_stats->get_monthly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions', 'unique' => $args['unique']), $args));
			$clicks = $wppas_stats->get_monthly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks', 'unique' => $args['unique']), $args));
			$text = array(
				'click'      => sprintf(__('All '.$unique_str.'clicks in %s:', 'wpproads'), date_i18n('F', $sdate)),
				'impression' => sprintf(__('All '.$unique_str.'impressions in %s:', 'wpproads'),date_i18n('F', $sdate)),
				'ctr'        => sprintf(__('CTR (%s):', 'wpproads'),date_i18n('F', $sdate)),
			);
		}
		elseif( $args['s_type'] == 'year')
		{
			$sdate = mktime(0,0,0, 1, 1, $args['year']);
			$edate = mktime(23,59,59, 12, 31, $args['year']);
			
			$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
			//echo date('y-m-d', $sdate).' '.date('y-m-d', $edate);
			//print_r($stats);
			
			$impr = $wppas_stats->get_yearly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions', 'unique' => $args['unique']), $args));
			$clicks = $wppas_stats->get_yearly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks', 'unique' => $args['unique']), $args));
			$text = array(
				'click'      => sprintf(__('Total amount of '.$unique_str.'clicks in %s:', 'wpproads'), date_i18n('Y', $sdate)),
				'impression' => sprintf(__('Total amount of '.$unique_str.'impressions in %s:', 'wpproads'),date_i18n('Y', $sdate)),
				'ctr'        => sprintf(__('CTR (%s):', 'wpproads'),date_i18n('Y', $sdate)),
			);
		}
		else
		{
			// stats - for faster page load - less db queries.
			$years = $wppas_stats->get_stat_years();
			$l = count($years)-1;
			
			$start = mktime(0,0,0, 1, 1, $years[0]);
			$end = mktime(23,59,59, 12, 31, $years[$l]);
			$stats = $wppas_stats->create_stats_array(array('start' => $start, 'end' => $end));
			//
			
			$impr = $wppas_stats->get_alltime_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions', 'unique' => $args['unique']), $args));
			$clicks = $wppas_stats->get_alltime_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks', 'unique' => $args['unique']), $args));
		
			$text = array(
				'click'      => __('Total amount of '.$unique_str.'clicks:', 'wpproads'),
				'impression' => __('Total amount of '.$unique_str.'impressions:', 'wpproads'),
				'ctr'        => __('CTR:', 'wpproads'),
			);
		}
		
		
		$style = 'style="width:78%; float:left;"';
		
		echo '<div class="pro_ad_main_stats_graph" '.$style.'>';
		
			// Header
			echo $this->stats_header_tpl(
				array(
					'impressions' => $impr, 
					'clicks'      => $clicks,
					'text'        => $text
				)
			);
			
			
			//echo $this->stats_menu_tpl($args);
			
			
			// Graph
			echo $this->stats_graph_tpl(wp_parse_args(array('stats' => $stats), $args));
			
			
		
			// Tables
			echo '<div style="background: #FFF; padding: 5px 20px; margin: 20px 0; box-shadow: 0px 1px 2px rgba(0,0,0,.1); border-radius: 3px; position:relative;">';
				echo '<h2 style="position:absolute; margin: 10px 0;">';
					//echo __('Impressions', 'wpproads');
					echo $args['s_type'] == 'day' ? __('Impressions', 'wpproads') : __('Impressions & Clicks', 'wpproads');
				echo '</h2>';
				echo $this->stats_table_tpl(wp_parse_args(array('stats' => $stats, 'type' => array('slug' => 'impressions')), $args));
			echo '</div>';
			
			if( $args['s_type'] == 'day' )
			{
				echo '<div style="background: #FFF; padding: 5px 20px; margin: 20px 0; box-shadow: 0px 1px 2px rgba(0,0,0,.1); border-radius: 3px; position:relative;">';
					echo '<h2 style="position:absolute; margin: 10px 0;">'.__('Clicks', 'wpproads').'</h2>';
					echo $this->stats_table_tpl(wp_parse_args(array('stats' => $stats, 'type' => array('slug' => 'clicks')), $args));
				echo '</div>';
			}
			
		echo '</div>';
		
		
		/**
		 * SIDEBAR
		 */
		 
		echo '<div class="pro_ad_stats_sidebar" style="width:20%; margin:20px 0; float:right;">';
				
			// Device stats
			echo '<div class="device_stats" style="text-align:center; padding:10px; background:#FFF;">';
				echo '<h2 style="background:#808080; color:#FFF; padding: 5px 0; margin:0 0 20px 0; border-radius: 5px;">'.__('Device Stats','wpproads').'</h2>';
				echo '<h3>'.__('Impressions','wpproads').'</h3>';
				echo $this->device_graph_tpl(wp_parse_args(array('stats' => $stats, 'type' => 'impressions'), $args));
				
				echo '<div style="border-bottom:solid 1px #EFEFEF; margin:20px 0;"></div>';
				
				echo '<h3>'.__('Clicks','wpproads').'</h3>';
				echo $this->device_graph_tpl(wp_parse_args(array('stats' => $stats, 'type' => 'clicks'), $args));
				
			echo '</div>';
			
			
			// PDF EXPORT
			$pdf_data = array(
				'year'             => $args['year'],
				'month'            => $args['month'],
				'day'              => $args['day'],
				's_type'           => $args['s_type'],
				'unique'           => $args['unique'],
				'select'           => $args['select']
			);
			$get = serialize($pdf_data);
			$get = htmlspecialchars($get);
			$get = base64_encode($get);
			echo '<div class="export_stats" style="text-align:center; margin-top:10px; padding:10px; background:#FFF;">';
				echo '<h2 style="background:#808080; color:#FFF; padding: 5px 0; margin:0 0 20px 0; border-radius: 5px;">'.__('Export Stats','wpproads').'</h2>';
				echo '<a href="'.get_bloginfo('url').'?stats_pdf=1&amp;data='.$get.'" target="_blank" style="font-size:35px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
				
				
			echo '</div>';
			
			
			
		echo '</div>';
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * STATS HEADER TEMPLATE
	*/
	public function stats_header_tpl($args = array())
	{	
		global $wppas_stats;
		
		$defaults = array(
			'clicks'           => 0,
			'impressions'      => 0,
			'text'             => array(),
			's_type'           => 'day',
			'unique'           => 0
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$html = '';
		$style = '';
		
		if( empty($args['text']))
		{
			if($args['stype'] == 'day')
			{
				$str = array(
					'click'       => __('Clicks', 'wpproads'),
					'impression'  => __('Impressions', 'wpproads'),
					'ctr'         => __('CTR', 'wpproads')
				);	
			}
		}
		else
		{
			$str = $args['text'];	
		}
		
		$ctr = $wppas_stats->get_ctr(array('clicks' => $args['clicks'], 'impressions' => $args['impressions']));
			
		$html.= '<div class="stats_header_cont">';
			$html.= '<div class="stats_header_box">';
				$html.= '<div>'.$str['click'].'</div>';
				$html.= '<div class="am_data">'.$args['clicks'].'</div>';
			$html.= '</div>';
			$html.= '<div class="stats_header_box">';
				$html.= '<div>'.$str['impression'].'</div>';
				$html.= '<div class="am_data">'.$args['impressions'].'</div>';
			$html.= '</div>';
			$html.= '<div class="stats_header_box" style="margin:0;">';
				$html.= '<div>'.$str['ctr'].'</div>';
				$html.= '<div class="am_data">'.$ctr.'</div>';
			$html.= '</div>';
			$html.= '<div class="clearFix"></div>';
		$html.= '</div>';
					
		return $html;
	}
	
	
	
	
	
	
	
	
	
	/*
	 * Statistics Menu
	 *
	 * @access public
	 * @param array $args
	 * @return html
	*/
	public function stats_menu_tpl( $args = array() )
	{
		$defaults = array(
			'year'            => $this->year,
			'month'           => $this->month,
			'day'             => $this->day,
			's_type'          => 'year',
			'unique'          => 0,
			'select'          => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$html = '';
		
		$html.= '<div style="float:right; margin:12px 10px 0 0;">'; //class="graph-info"
			
			$html.= $args['s_type'] != 'all' ? '<a href="javascript:void(0)" class="time_frame_btn" s_type="all" year="'.$args['year'].'" month="'.$args['month'].'" unique="'.$args['unique'].'" select="'.$args['select'].'">' : '';
				$html.= $args['s_type'] == 'all' ? '<strong style="color:#000;">' : '';
					$html.= __('All Time','wpproads');
				$html.= $args['s_type'] == 'all' ? '</strong>' : '';
			$html.= $args['s_type'] != 'all' ? '</a>' : '';
			
			$html.= ' / ';
			
			$html.= $args['s_type'] != 'year' ? '<a href="javascript:void(0)" class="time_frame_btn" s_type="year" year="'.$args['year'].'" month="'.$args['month'].'" unique="'.$args['unique'].'" select="'.$args['select'].'">' : '';
				$html.= $args['s_type'] == 'year' ? '<strong style="color:#000;">' : '';
					$html.= $args['year'];
				$html.= $args['s_type'] == 'year' ? '</strong>' : '';
			$html.= $args['s_type'] != 'year' ? '</a>' : '';
			
			$html.= ' / ';
			
			$html.= $args['s_type'] != 'month' ? '<a href="javascript:void(0)" class="time_frame_btn" s_type="month" year="'.$args['year'].'" month="'.$args['month'].'" unique="'.$args['unique'].'" select="'.$args['select'].'">' : '';
				$html.= $args['s_type'] == 'month' ? '<strong style="color:#000;">' : '';
					$html.= date_i18n('F', mktime(0,0,0, $args['month'], $args['day'], $args['year']));
				$html.= $args['s_type'] == 'month' ? '</strong>' : '';
			$html.= $args['s_type'] != 'month' ? '</a>' : '';
			
			$html.= ' / ';
			
			$html.= $args['s_type'] != 'day' ? '<a href="javascript:void(0)" class="time_frame_btn" s_type="day" year="'.$args['year'].'" month="'.$args['month'].'" day="'.$args['day'].'" unique="'.$args['unique'].'" select="'.$args['select'].'">' : '';
				$html.= $args['s_type'] == 'day' ? '<strong style="color:#000;">' : '';
					$html.= date_i18n('l, F d', mktime(0,0,0, $args['month'], $args['day'], $args['year']));
				$html.= $args['s_type'] == 'day' ? '</strong>' : '';
			$html.= $args['s_type'] != 'day' ? '</a>' : '';
			
			
		$html.= '</div>';
		
		return $html;
	}
	
	
	
	
	
	
	
	
	public function stats_graph_tpl($args = array())
	{
		global $wppas_stats;
		
		$defaults = array(
			'stats'            => array(),
			'year'             => $this->year,
			'month'            => $this->month,
			'day'              => $this->day,
			's_type'           => 'day',
			'unique'           => 0,
			'select'           => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		
		$html = '';
		//$stats = $wpproads_stats->load_stats_array($args['stats']);
		
		// Fot testing only
		/*
		if( !empty($stats[$this->year][$this->month][$this->day]))
		{
			foreach($stats[$this->year][$this->month][$this->day] as $today)
			{
				foreach($today['impressions'] as $impr)
				{
					foreach($impr['hits'] as $hits)
					{
						if(date('G', $hits['time']) == $i)
						{
							$cnt++;
						}
					}
				}
			}
		}
		*/
		// end testing
		
		// Buttons
		$html.= '<div style="float:left; width: 400px;">';
			$html.= '<div id="choices">';
				//$html.= '<div>Show/Hide:</div>';
				
				// Impressions BTN
				$html.= '<div class="ck-button stats_btn impressions">';
					$html.= '<label>';
						$html.= '<input type="checkbox" name="impressions" checked="checked" id="idimpressions">';
						$html.= '<span>';
							$html.= '<label for="idimpressions">Impressions</label>';
						$html.= '</span>';
					$html.= '</label>';
				$html.= '</div>';
				
				// Clicks BTN
				$html.= '<div class="ck-button stats_btn clicks">';
					$html.= '<label>';
						$html.= '<input type="checkbox" name="clicks" checked="checked" id="idclicks">';
						$html.= '<span>';
							$html.= '<label for="idclicks">Clicks</label>';
						$html.= '</span>';
					$html.= '</label>';
				$html.= '</div>';
				
				//echo $args['s_type'];
				
				// Unique BTN
				$update_unique = $args['unique'] ? 0 : 1;
				
				$html.= '<a class="ck-button unique unique_stats_btn time_frame_btn" s_type="'.$args['s_type'].'" year="'.$args['year'].'" month="'.$args['month'].'" day="'.$args['day'].'" unique="'.$update_unique.'" select="'.$args['select'].'">';
					$html.= '<label>';
						$html.= '<input type="checkbox" name="unique" '.checked( 1,$args['unique'], false ).' id="idunique">';
						$html.= '<span>';
							$html.= '<label for="idunique">Unique</label>';
						$html.= '</span>';
					$html.= '</label>';
				$html.= '</a>';
				
			$html.= '</div>';
			$html.= '<div class="clearFix"></div>';
		$html.= '</div>';
		
		// Menu
		$html.= $this->stats_menu_tpl($args);
		
		$html.= '<div class="clearFix"></div>';
		
		// Graph
		$html.= '<div class="graph-container">';
			$html.= '<div id="graph-lines"></div>';
		$html.= '</div>';
		
		
		
		
		// JS
		$html.= '<script type="text/javascript">';
			$html.= 'jQuery(document).ready(function($) {';
	
				$html.= 'var point_txt = [];';
				
				if( $args['s_type'] == 'day' )
				{	
					for( $i = 1; $i <= 24; $i++ )
					{
						$html.= 'point_txt.push("'.__('at', 'wpproads').' '.date_i18n('G:i', mktime($i,0,0, $args['month'], $i, $args['year'])).'");';
					}
				}
				elseif( $args['s_type'] == 'month' )
				{
					$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
					for( $i = 1; $i <= $am_days; $i++ )
					{
						$html.= 'point_txt.push("'.__('on', 'wpproads').' '.date_i18n('F, d', mktime(0,0,0, $args['month'], $i, $args['year'])).'");';
					}
				}
				elseif( $args['s_type'] == 'year' )
				{
					for( $i = 1; $i <= 12; $i++ )
					{
						$html.= 'point_txt.push("'.__('in', 'wpproads').' '.date_i18n('F', mktime(0,0,0, $i, $args['day'], $args['year'])).'");';
					}
				}
				elseif( $args['s_type'] == 'all' )
				{
					$years_arr = $wppas_stats->get_stat_years();
					
					$html.= 'point_txt.push("'.__('in', 'wpproads').' '.$years_arr[0].'");';
					
					if( !empty($years_arr) )
					{
						foreach( $years_arr as $year )
						{
							$html.= 'point_txt.push("'.__('in', 'wpproads').' '.$year.'");';
						}
					}
					else
					{
						$html.= 'point_txt.push("'.__('in', 'wpproads').' '.date('Y').'");';
					}
				}
	
	$html.= 'var graphDataSet = {
		"impressions" : {
			color: "#77B7C5",
			label: "'.__('Impressions','wpproads').'",
			data: [';
			
			$html.= $wppas_stats->graph_data(
				array(
					'stats'  => $args['stats'],
					's_type' => $args['s_type'],
					'type'   => 'impressions',
					'unique' => $args['unique'],
					'year'   => $args['year'],
					'month'  => $args['month'],
					'day'    => $args['day'],
					'select' => $args['select']
				)
			);
			
			$html.= ']
		},
		"clicks" : {
			color: "#66b71a",
			label: "'.__('Clicks', 'wpproads').'",
			data: [';
			
			$html.= $wppas_stats->graph_data(
				array(
					'stats'  => $args['stats'],
					's_type' => $args['s_type'],
					'type'   => 'clicks',
					'unique' => $args['unique'],
					'year'   => $args['year'],
					'month'  => $args['month'],
					'day'    => $args['day'],
					'select' => $args['select']
				)
			);
			
			$html.= ']';
		$html.= '}';
	$html.= '};';
	
	
	$html.= 'var choiceContainer = $("#choices");';
	
   /* $.each(graphDataSet, function(key, val) {
        choiceContainer.append(\'<div class="ck-button stats_btn \'+key+\'"><label><input type="checkbox" name="\' + key +
                               \'" checked="checked" id="id\' + key + \'">\' +
                               \'<span><label for="id\' + key + \'">\'
                                + val.label + \'</label></span></label></div>\');
    });*/
	
    $html.= 'choiceContainer.find("input").click(plotAccordingToChoices);
	
	
	// SHOW CHART
	function plotAccordingToChoices() {
        var data = [];

        choiceContainer.find("input:checked").each(function () {
            var key = $(this).attr("name");
            if (key && graphDataSet[key])
                data.push(graphDataSet[key]);
        });

        if (data.length > 0)
            
			$.plot($("#graph-lines"), data, {
				series: {
					points: {show: true,radius: 3},
					lines: {show: true,fill:.2,},
					shadowSize: 0
				},
				grid: {
					borderColor: {
						bottom: "#EEEEEE", 
						left: "#EEEEEE"
					},
					//borderWidth: {top: 0, right: 0, bottom: 2, left: 2},
					hoverable: true
				},';
				$html.= 'legend: { show: false },';
				
				if( $args['s_type'] == 'all' )
				{
					$html.= 'xaxis: {tickColor: "#F5F5F5",tickDecimals: 0,tickSize:1,min:'.$years_arr[0].'},';
				}
				else
				{
					$html.= 'xaxis: {tickColor: "#F5F5F5",tickDecimals: 0,tickSize:1,min:1},';
				}
				
				$html.= 'yaxis: {tickColor: "#EEEEEE",tickDecimals: 0,min:0}
				//yaxis: {tickColor: "#EEEEEE",tickSize: five_multiple( five_multiple( 191, 5)/4, 5 ),tickDecimals: 0,min:0}
			});
			
       
    }

    plotAccordingToChoices();
	
	
	
	
	
	
	
	
	
	/**
	 * TOOLTIP FUNCTIONS
	*/
	function showTooltip(x, y, contents) {
		$(\'<div id="tooltip">\' + contents + \'</div>\').css({top: y - 16,left: x + 20}).appendTo("body").fadeIn();
	}
	var previousPoint = null;
	
	$("#graph-lines").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				$("#tooltip").remove();
				var x = item.datapoint[0],
					y = item.datapoint[1];
				
				//showTooltip(item.pageX, item.pageY, y + " Clicks " + point_txt[item.dataIndex]);
				showTooltip(item.pageX, item.pageY, y + " "+item.series[\'label\']+" " + point_txt[item.dataIndex]);
				
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
	});
	
	
	
	

});



function five_multiple( i, v){
	return v*(Math.round(i/v));
}
</script>';
		
		
		return $html;	
	}
	
	
	
	
	
	
	public function device_graph_tpl($args = array())
	{
		global $wppas_stats;
		
		$html = '';
		$device_colors = array('desktop' => '#006EA6', 'tablet' => '#35AF9B', 'mobile' => '#875497', 'unknown' => '#CCC');
		
		//print_r($wpproads_stats->count_daily_device_stats());
		//print_r($wpproads_stats->count_monthly_device_stats( array('month' => 11) ));
		//print_r($wpproads_stats->count_yearly_device_stats());
		//print_r($wpproads_stats->count_all_device_stats());
		
		
		$html.= '<style>#device-graph-pie-'.$args['type'].' { width: 200px; height: 200px; }.legend table, .legend > div { height: 82px !important; opacity: 1 !important; left: 170px; top: 10px; }.legend table { border: none; padding: 5px; }</style>';
			
		$html.= '<div id="device-graph-wrapper-'.$args['type'].'">';
			$html.= '<div class="device-graph-container-'.$args['type'].'">';
				$html.= '<div id="device-graph-pie-'.$args['type'].'"></div>';
			$html.= '</div>';
		$html.= '</div>';
		
		// start JS
		$html.= '<script type="text/javascript">';
			
			$html.= 'jQuery(document).ready(function($) {';	
			
				$html.= 'var data = [';
				
					if( $args['s_type'] == 'day' )
					{
						$device_stats = $wppas_stats->count_daily_device_stats($args);
						foreach($device_stats as $key => $d_stats)
						{
							$html.= '{ label: "'.$key.'",  data: '.$d_stats.', color: "'.$device_colors[$key].'"},';
						}
					}
					if( $args['s_type'] == 'month' )
					{
						$device_stats = $wppas_stats->count_monthly_device_stats($args);
						foreach($device_stats as $key => $d_stats)
						{
							$html.= '{ label: "'.$key.'",  data: '.$d_stats.', color: "'.$device_colors[$key].'"},';
						}
					}
					if( $args['s_type'] == 'year' )
					{
						$device_stats = $wppas_stats->count_yearly_device_stats($args);
						foreach($device_stats as $key => $d_stats)
						{
							$html.= '{ label: "'.$key.'",  data: '.$d_stats.', color: "'.$device_colors[$key].'"},';
						}
					}
					if( $args['s_type'] == 'all' )
					{
						$device_stats = $wppas_stats->count_all_device_stats($args);
						foreach($device_stats as $key => $d_stats)
						{
							$html.= '{ label: "'.$key.'",  data: '.$d_stats.', color: "'.$device_colors[$key].'"},';
						}
					}
					/*$html.= '{ label: "Desktop",  data: 5, color: "#006EA6"},'; //19.5
					$html.= '{ label: "Tablet",  data: 0, color: "#35AF9B"},'; // 4.5
					$html.= '{ label: "Mobile",  data: 0, color: "#875497"},'; //36.6*/
				$html.= '];';
				
				$html.= 'var count_device_stats = 0;';
				$html.= 'jQuery.each( data, function( i, val ) {';
					$html.= 'count_device_stats += val["data"];';
				$html.= '});';
				
				$html.= 'if(count_device_stats > 0 ){';
					$html.= '$.plot("#device-graph-pie-'.$args['type'].'", data, {';
						$html.= 'series: {';
							$html.= 'pie: {';
								$html.= 'radius: 1,';
								$html.= 'innerRadius: 0.5,';
								$html.= 'show: true,';
								$html.= 'label: {';
									$html.= 'show: true,';
									$html.= 'radius: 1,';
							
									$html.= 'formatter: function(label, series) {';
										$html.= 'return \'<div style="font-size:11px; text-align:center; padding:2px; color:white;">\'+label+\'<br/>\'+Math.round(series.percent)+\'%</div>\';';
									$html.= '},';
									$html.= 'background: {';
										$html.= 'opacity: 0.8';
									$html.= '}';
								$html.= '}';
							$html.= '}';
						$html.= '},';
						$html.= 'legend: {';
							$html.= 'show: false';
						$html.= '}';
					$html.= '});';
				$html.= '}else{';
					$html.= '$("#device-graph-pie-'.$args['type'].'").html("<br>'.__('No stats available.','wpproads').'");';
				$html.= '}';
				
			$html.= '});';
		
		$html.= '</script>';
		
		return $html;
	}
	
	
	
	
	/**
	 * STATS TABLE
	 *
	 * @return plain html
	*/
	public function stats_table_tpl($args = array())
	{
		// STATS TABLE
		$defaults = array(
			'stats'    => array(),
			'type'     => array('slug' => 'clicks'), 
			's_type'   => 'day',
			'day'      => $this->day,
			'month'    => $this->month,
			'year'     => $this->year,
			'unique'   => 0,
			'select'   => $args['select']
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		//print_r($args);
		if( $args['s_type'] == 'day' )
		{
			$statsTable = new Pro_Ad_Stats_Day_List_Table();
			$statsTable->prepare_items( $args );
			$filter_str = 'day-stats-filter';
			$range = 'day';
		}
		elseif( $args['s_type'] == 'month' )
		{
			$statsTable = new Pro_Ad_Stats_List_Table();
			$statsTable->prepare_items( $args );
			$filter_str = 'month-stats-filter';
			$range = 'month';
		}
		elseif( $args['s_type'] == 'year' )
		{
			$statsTable = new Pro_Ad_Stats_Year_List_Table();
			$statsTable->prepare_items( $args );
			$filter_str = 'year-stats-filter';
			$range = 'year';
		}
		elseif( $args['s_type'] == 'all' )
		{
			$statsTable = new Pro_Ad_All_Stats_List_Table();
			$statsTable->prepare_items( $args );
			$filter_str = 'all-stats-filter';
			$range = 'all';
		}
			
		echo '<form id="'.$filter_str.'" class="stats-filter '.$args['type']['slug'].'" range="'.$range.'" type="'.$args['type']['slug'].'" day="'.$args['day'].'" month="'.$args['month'].'" year="'.$args['year'].'" unique="'.$args['unique'].'" method="get">';
			//echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
			$statsTable->display();
		echo '</form>';
	}
	
}
?>