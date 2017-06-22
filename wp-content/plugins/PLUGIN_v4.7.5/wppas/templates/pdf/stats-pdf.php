<?php
global $pro_ads_statistics, $wppas_stats;


$html = '';
$html.= '<h1>'.__('Statistics','wpproads').'</h1>';

$time = mktime(0,0,0, $args['month'], $args['day'], $args['year']);
$sdate = mktime(0,0,0, $args['month'], $args['day'], $args['year']);
$edate = mktime(23,59,59, $args['month'], $args['day'], $args['year']);

if( $args['s_type'] == 'day')
{	
	$html.= '<div style="background-color:#abdb6d; color:#FFF; font-size:24px; text-align:center;">';
		$html.= sprintf(__('Stats for %s'), date_i18n('l, M d, Y', $time));
	$html.= '</div>';
	
	$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
	
	$impr = $wppas_stats->get_daily_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions'), $args));
	$clicks = !empty($impr) ? $wppas_stats->get_daily_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks'), $args)) : 0;
	$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		/*$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';*/
		$html.= '<tr>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Impressions','wpproads').'</div>';
				$html.= '<div style="font-size:14px;">'.$impr.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Clicks','wpproads').'</div>';
				$html.= '<div style="font-size:14px;">'.$clicks.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('CTR','wpproads').'</div>';
				$html.= '<div style="font-size:14px;">'.$ctr.'</div>';
			$html.= '</td>';
		$html.= '</tr>';
		/*$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';*/
	$html.= '</table>';
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		
		$html.= '<tr>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong style="padding:10px;">'.__('Time','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Impressions','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Clicks','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('CTR','wpproads').'</strong></th>';
		$html.= '</tr>';
		
		for( $i = 1; $i <= 24; $i++)
		{
			$impr = $wppas_stats->get_hourly_stats( wp_parse_args(array('stats' => $stats, 'hour' => $i, 'type' => 'impressions'), $args) );
			
			if( !empty($impr))
			{
				$clicks = $wppas_stats->get_hourly_stats( wp_parse_args(array('stats' => $stats, 'hour' => $i, 'type' => 'clicks'), $args) );
				$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
			
				$html.= '<tr>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.$i.':00</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$impr.'</strong> '.__('Impressions','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$clicks.'</strong> '.__('Clicks','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.__('CTR','wpproads').': <strong>'.$ctr.'</strong></td>';
				$html.= '</tr>';
			}
		}
		
	$html.= '</table>';
}
elseif( $args['s_type'] == 'month')
{
	$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
	
	$html.= '<div style="background-color:#abdb6d; color:#FFF; font-size:24px; text-align:center;">';
		$html.= sprintf(__('Stats for %s'), date_i18n('F, Y', $time));
	$html.= '</div>';
	
	$impr = $wppas_stats->get_monthly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions'), $args));
	$clicks = !empty($impr) ? $wppas_stats->get_monthly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks'), $args)) : 0;
	$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Impressions','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$impr.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Clicks','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$clicks.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('CTR','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$ctr.'</div>';
			$html.= '</td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
	$html.= '</table>';
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		
		$html.= '<tr>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong style="padding:10px;">'.__('Time','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Impressions','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Clicks','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('CTR','wpproads').'</strong></th>';
		$html.= '</tr>';
		
		$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
		
		for( $i = 1; $i <= $am_days; $i++ )
		{
			$impr = $wppas_stats->get_daily_stats( wp_parse_args(array('stats' => $stats, 'day' => $i, 'type' => 'impressions'), $args) );
			
			if( !empty($impr))
			{
				$clicks = $wppas_stats->get_daily_stats( wp_parse_args(array('stats' => $stats, 'day' => $i, 'type' => 'clicks'), $args) );
				$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
				
				$html.= '<tr>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.$i.'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$impr.'</strong> '.__('Impressions','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$clicks.'</strong> '.__('Clicks','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.__('CTR','wpproads').': <strong>'.$ctr.'</strong></td>';
				$html.= '</tr>';
			}
		}
		
	$html.= '</table>';
}
elseif( $args['s_type'] == 'year')
{	
	$html.= '<div style="background-color:#abdb6d; color:#FFF; font-size:24px; text-align:center;">';
		$html.= sprintf(__('Stats for %s'), $args['year']);
	$html.= '</div>';
	
	$stats = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
	
	$impr = $wppas_stats->get_yearly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions'), $args));
	$clicks = !empty($impr) ? $wppas_stats->get_yearly_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks'), $args)) : 0;
	$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Impressions','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$impr.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Clicks','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$clicks.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('CTR','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$ctr.'</div>';
			$html.= '</td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
	$html.= '</table>';
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		
		$html.= '<tr>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong style="padding:10px;">'.__('Time','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Impressions','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Clicks','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('CTR','wpproads').'</strong></th>';
		$html.= '</tr>';
		
		$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
		
		for( $i = 1; $i <= 12; $i++ )
		{
			$impr = $wppas_stats->get_monthly_stats( wp_parse_args(array('stats' => $stats, 'month' => $i, 'type' => 'impressions'), $args) );
			
			if( !empty($impr))
			{
				$clicks = $wppas_stats->get_monthly_stats( wp_parse_args(array('stats' => $stats, 'month' => $i, 'type' => 'clicks'), $args) );
				$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
			
				$html.= '<tr>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.$i.'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$impr.'</strong> '.__('Impressions','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$clicks.'</strong> '.__('Clicks','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.__('CTR','wpproads').': <strong>'.$ctr.'</strong></td>';
				$html.= '</tr>';
			}
		}
		
	$html.= '</table>';
}
elseif( $args['s_type'] == 'all')
{
	$html.= '<div style="background-color:#abdb6d; color:#FFF; font-size:24px; text-align:center;">';
		$html.= __('All Time Stats');
	$html.= '</div>';
	
	$years = $wppas_stats->get_stat_years();
	$l = count($years)-1;
	
	$start = mktime(0,0,0, 1, 1, $years[0]);
	$end = mktime(23,59,59, 31, 12, $years[$l]);
	$stats = $wppas_stats->create_stats_array(array('start' => $start, 'end' => $end));
	
	$impr = $wppas_stats->get_alltime_stats(wp_parse_args(array('stats' => $stats, 'type' => 'impressions'), $args));
	$clicks = !empty($impr) ? $wppas_stats->get_alltime_stats(wp_parse_args(array('stats' => $stats, 'type' => 'clicks'), $args)) : 0;
	$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Impressions','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$impr.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('Total Clicks','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$clicks.'</div>';
			$html.= '</td>';
			$html.= '<td style="text-align:center; background-color:#666; color:#FFF;">';
				$html.= '<div>'.__('CTR','wpproads').'</div>';
				$html.= '<div style="font-size:18px;">'.$ctr.'</div>';
			$html.= '</td>';
		$html.= '</tr>';
		$html.= '<tr>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
			$html.= '<td style="background-color:#666; height:1px;"></td>';
		$html.= '</tr>';
	$html.= '</table>';
	
	$html.= '<table style="border: 1px solid #EFEFEF;">';
		
		$html.= '<tr>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong style="padding:10px;">'.__('Time','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Impressions','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('Clicks','wpproads').'</strong></th>';
			$html.= '<th style="border: 1px solid #EFEFEF; border-bottom:1px solid #666; background-color:#EFEFEF;"><strong>'.__('CTR','wpproads').'</strong></th>';
		$html.= '</tr>';
		
		$years = $wppas_stats->get_stat_years();
		foreach($years as $i => $year)
		{
			$impr = $wppas_stats->get_yearly_stats( wp_parse_args(array('stats' => $stats, 'year' => $year, 'type' => 'impressions'), $args) );
			
			if( !empty($impr))
			{
				$clicks = $wppas_stats->get_yearly_stats( wp_parse_args(array('stats' => $stats, 'year' => $year, 'type' => 'clicks'), $args) );
				$ctr = $wppas_stats->get_ctr(array('clicks' => $clicks, 'impressions' => $impr));
			
				$html.= '<tr>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.$year.'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$impr.'</strong> '.__('Impressions','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;"><strong>'.$clicks.'</strong> '.__('Clicks','wpproads').'</td>';
					$html.= '<td style="border: 1px solid #EFEFEF;">'.__('CTR','wpproads').': <strong>'.$ctr.'</strong></td>';
				$html.= '</tr>';
			}
		}
		
	$html.= '</table>';
}
?>