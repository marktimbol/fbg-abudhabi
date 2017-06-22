<?php
global $pro_ads_statistics, $pro_ads_main, $pro_ads_multisite, $wppas_stats, $wppas_stats_tpl;
?>

<div class="wrap">

<?php 
$wpproads_enable_stats = get_option('wpproads_enable_stats', 0);
if($wpproads_enable_stats)
{
	echo '<div class="bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>';
	
	//if( $pro_ads_multisite->wpproads_get_option('wpproads_stats_version', '_new') == 'array_stats' )
	if( PAS()->version >= '4.7.5')
	{
		$group = isset($_GET['group']) ? $_GET['group'] : '';
		$group_id = isset( $_GET['group_id']) ? $_GET['group_id'] : '';
		$select = !empty($group) ? $wppas_stats->select_array( array('group' => $group.'_id', 'group_id' => $group_id) ) : array();
		$select = wp_parse_args($wppas_stats->default_select(), $select);
		
		//$t = $wpproads_stats->encode_array($select);
		//print_r( $wpproads_stats->decode_array($t) );
		echo '<h2 class="messages-position"></h2>';
		
		if(!empty($group))
		{
			echo '<div style="background: #ABDB6D; color: #FFF; font-size: 24px; border-radius: 5px; padding: 20px;">';
				echo sprintf(__('Stats for %s: %s', 'wpproads'), $group, '<a href="post.php?post='.$group_id.'&action=edit" style="color: #FFF; padding: 0px 10px; border-radius: 5px;">'.get_the_title($group_id).' - ID.'.$group_id.'</a>');
			echo '</div>';	
		}
			
		echo '<div class="pro_ad_stats_graph">';
			echo $wppas_stats_tpl->stats_tpl(array('select' => $wppas_stats->encode_array($select)));
		echo '</div>';
	}
	else
	{
		echo '<div class="pro_ad_stats_graph">';
			$group = isset($_GET['group']) ? $_GET['group'] : '';
			$group_id = isset( $_GET['group_id']) ? $_GET['group_id'] : '';
			echo $pro_ads_statistics->pro_ad_show_statistics(array('rid'  => 4, 'range' => 'day', 'day' => $pro_ads_main->time_by_timezone('d'), 'group' => $group, 'group_id' => $group_id)); 
		echo '</div>';
	}
}
else
{
	?>
    <h2><?php _e('Statistics are disabled','wpproads'); ?></h2>
	<p><?php _e('You can enable statistics on the AD Dashboard under <em>General Settings</em>','wpproads'); ?></p>
    <?php
}
?>


</div>
<!-- end wrap -->