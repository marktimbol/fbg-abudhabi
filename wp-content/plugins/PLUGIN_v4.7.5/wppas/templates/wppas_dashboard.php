<?php
/**
 * NEW DASHBOARD 
 * since v4.7.0
 */

global $pro_ads_templates, $pro_ads_statistics, $pro_ads_main, $pro_ads_multisite, $pro_ads_bs_main, $pro_ads_bs_templates, $pro_ads_bs_woo_templates, $pro_ads_bs_woo_main, $pro_ads_adzones, $wppas_stats_tpl, $wppas_stats;

wp_enqueue_script('wp_pro_ads_ace');
$notice = array();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['1']))
	{
		$pro_ads_multisite->wpproads_update_option( 'wpproads_custom_css', $_POST['wpproads_custom_css']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_stats', $_POST['wpproads_enable_stats']);
		/*
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_impr', $_POST['wpproads_enable_impr']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_clicks', $_POST['wpproads_enable_clicks']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_stats_version', $_POST['wpproads_stats_version']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_save_impressions_type', $_POST['wpproads_save_impressions_type']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_save_clicks_type', $_POST['wpproads_save_clicks_type']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_stats_data', $_POST['wpproads_stats_data']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_stats_save_days', $_POST['wpproads_stats_save_days']);
		*/
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_userdata_stats', $_POST['wpproads_enable_userdata_stats']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_stats_blocked_ips', $_POST['wpproads_stats_blocked_ips']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_async_js_tag', $_POST['wpproads_enable_async_js_tag']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_allowed_origens', $_POST['wpproads_allowed_origens']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_google_analytics_id', $_POST['wpproads_google_analytics_id']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_enable_adminbar', $_POST['wpproads_enable_adminbar']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_uninstall', $_POST['wpproads_uninstall']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_adzone_class', $_POST['wpproads_adzone_class']);
		$pro_ads_multisite->wpproads_update_option( 'wpproads_page_caching', $_POST['wpproads_page_caching']);
		
		
		
		// permalinks
		$cur_str = get_option('wp_ads_mod_rewrite', 'pas');
		$update_permalinks = $cur_str != $_POST['wp_ads_mod_rewrite'] ? 1 : 0;
		update_option( 'wpproads_enable_mod_rewrite', $_POST['wpproads_enable_mod_rewrite']);
		update_option( 'wp_ads_mod_rewrite', $_POST['wp_ads_mod_rewrite']);
		
		$notice[] = !$update_permalinks ? __('General Settings are updated successfully.','wpproads') : __('Please UPDATE your <a href="'.get_admin_url().'options-permalink.php">Permalinks</a>.', 'wpproads');
	}
	elseif(isset($_POST['buyandsell_settings']))
	{
		$notice[] = $pro_ads_bs_main->wpproads_buyandsell_save_options();
	}
	elseif(isset($_POST['bs_woo_settings']))
	{
		$notice[] = $pro_ads_bs_woo_main->wpproads_buyandsell_woo_save_settings_options();
	}
	elseif(isset($_POST['bsads_woo']))
	{
		$notice[] = $pro_ads_bs_woo_main->wpproads_buyandsell_woo_save_options();
	}
	elseif(isset($_POST['wpproads_post_template']))
	{
		update_option( 'wpproads_enable_post_ads', $_POST['wpproads_enable_post_ads'] );
		update_option( 'wpproads_post_ads_top', $_POST['wpproads_post_ads_top'] );
		update_option( 'wpproads_post_ads_center', $_POST['wpproads_post_ads_center'] );
		update_option( 'wpproads_post_ads_center_para', $_POST['wpproads_post_ads_center_para'] );
		update_option( 'wpproads_post_ads_center_align', $_POST['wpproads_post_ads_center_align'] );
		update_option( 'wpproads_post_ads_bottom', $_POST['wpproads_post_ads_bottom'] );
		
		// Multisite, network activated
		if( $pro_ads_multisite->pro_ads_plugin_is_network_activated() && is_main_site() )
		{
			update_site_option( 'wpproads_enable_post_ads', $_POST['wpproads_enable_post_ads'] );
			update_site_option( 'wpproads_post_ads_top', $_POST['wpproads_post_ads_top'] );
			update_site_option( 'wpproads_post_ads_center', $_POST['wpproads_post_ads_center'] );
			update_site_option( 'wpproads_post_ads_center_para', $_POST['wpproads_post_ads_center_para'] );
			update_site_option( 'wpproads_post_ads_center_align', $_POST['wpproads_post_ads_center_align'] );
			update_site_option( 'wpproads_post_ads_bottom', $_POST['wpproads_post_ads_bottom'] );
		}
	}
	elseif( isset($_POST['wpproads_register_plugin']))
	{
		$registered = $pro_ads_main->PLU_registration( $_POST['wpproads_license_key'] );
		
		if( $registered == 1 )
		{
			update_option( 'wpproads_license_key', $_POST['wpproads_license_key'] );
			if( $pro_ads_multisite->pro_ads_plugin_is_network_activated() && is_main_site() )
			{
				update_site_option( 'wpproads_license_key', $_POST['wpproads_license_key'] );
			}
		}
		else
		{
			update_option( 'wpproads_license_key', '' );
			if( $pro_ads_multisite->pro_ads_plugin_is_network_activated() && is_main_site() )
			{
				update_site_option( 'wpproads_license_key', '' );
			}
		}
		$notice[] = $registered == 1 ? __('Plugin registered successfully.','wpproads') : __('Please add a valid/unique license key.', 'wpproads');
	}
}


//$wpproads_enable_custom_css = get_option('wpproads_enable_custom_css', 1);
$custom_css = get_option('wpproads_custom_css', '');
$wpproads_license_key = get_option('wpproads_license_key', '');
$wpproads_enable_stats = get_option('wpproads_enable_stats', 0);
$wpproads_enable_impr = get_option('wpproads_enable_impr', 1);
$wpproads_enable_clicks = get_option('wpproads_enable_clicks', 1);
$wpproads_stats_data = get_option('wpproads_stats_data', 'hour');
$wpproads_enable_userdata_stats = get_option('wpproads_enable_userdata_stats', 0);
$wpproads_save_clicks_type = get_option('wpproads_save_clicks_type', 'unique');
$wpproads_save_impressions_type = get_option('wpproads_save_impressions_type', 'unique');
$wpproads_stats_save_days = get_option('wpproads_stats_save_days', '');
$wpproads_stats_version = get_option('wpproads_stats_version', '_new');
$wpproads_stats_blocked_ips = get_option('wpproads_stats_blocked_ips', '');
$wpproads_enable_post_ads = get_option('wpproads_enable_post_ads',array());
$wpproads_enable_async_js_tag = get_option('wpproads_enable_async_js_tag', 1);
$wpproads_allowed_origens = get_option('wpproads_allowed_origens', '');
$wpproads_google_analytics_id = get_option('wpproads_google_analytics_id', '');
$wpproads_post_ads_top = get_option('wpproads_post_ads_top',0);
$wpproads_post_ads_center = get_option('wpproads_post_ads_center',0);
$wpproads_post_ads_center_para = get_option('wpproads_post_ads_center_para', 2);
$wpproads_post_ads_center_align = get_option('wpproads_post_ads_center_align', '');
$wpproads_post_ads_bottom = get_option('wpproads_post_ads_bottom',0);
$wpproads_enable_adminbar = get_option('wpproads_enable_adminbar',1);
$wpproads_uninstall = get_option('wpproads_uninstall', 0);
$wpproads_adzone_class = get_option('wpproads_adzone_class', 'wppaszone');
$wpproads_page_caching = get_option('wpproads_page_caching', 1);

$wpproads_enable_mod_rewrite = get_option('wpproads_enable_mod_rewrite', 0);
$wp_ads_mod_rewrite = get_option('wp_ads_mod_rewrite', 'pas');

$custom_origens = $pro_ads_main->get_allowed_origins();
?>

<div class="wppas_dashboard">
	<div class="wrap">

        <div class="wppas_header">
            
            <div class="wppas_logo float_left">
            	<img src="<?php echo WP_ADS_URL.'/images/admin/logo.png'; ?>" style="height:36px; float:left; margin-right:10px;" />
                <h3 class="float_left">WP PRO ADVERTISING SYSTEM</h3>
                <div class="version"><em><?php _e('Version','wpproads'); ?> <?php echo PAS()->version; ?></em></div>
            </div>
            
            <?php
			if( current_user_can(WP_ADS_ROLE_ADMIN))
			{
				?>
            	<a href="<?php echo PAS()->docs; ?>" class="button-secondary float_right" target="_blank"><?php _e('Help','wpproads'); ?></a>
                <?php
			}
			?>
            <!--<a id="button_general_settings" class="button-secondary float_right" style="margin-right:10px;"><?php _e('General Settings','wpproads'); ?></a>-->
            <div class="clearFix"></div>
        </div>
        <!-- end .wppas_header -->
        
        
        <!-- Wordpress Messages -->
        <h2 class="messages-position"></h2>
        
        <?php
		// admin_notice Messages
		if( !empty($notice) )
		{
			foreach($notice as $note)
			{
				echo !empty($note) ? '<div class="updated wpproads-message"><p>'.$note.'</p></div>' : '';
			}
		}
		
		/*if( empty($wpproads_license_key))
		{
			?>
			<div class="updated wpproads-message">
				<p><?php _e('Please Register your copy of the WP PRO Advertising System to receive automatic plugin updates.','wpproads'); ?></p>
				<form action="" method="post" enctype="multipart/form-data">
					<p>
						<input type="text" name="wpproads_license_key" style="width:300px;" placeholder="<?php _e('License Key','wpproads'); ?>"> 
						<input type="submit" value="<?php _e('Register Plugin', 'wpproads'); ?>" class="main_button" name="wpproads_register_plugin" />
					</p>
			   </form>
			</div>
			<?php
		}*/
		?>
        
        
        
        
        
        <div class="wppas_box_container">
        
        
        	
            <!--
             /**
              * STATISTICS + GET STARTED BOX
              */
            -->
        	<div class="wppas_box_group">
            	
                <!--
                 /**
                  * STATISTICS
                  */
                -->
                <div class="wppas_box col2 float_left">
                    <div class="wppas_box_header">
                        <h3 class="float_left" style="color:#ADD97A;"><?php _e('Today\'s Statistics','wpproads'); ?></h3>
                        <div class="wppas_status green float_right">
                        	<small style="font-size:12px;"><em><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date_i18n('l, F d', $pro_ads_main->time_by_timezone()); ?></em></small>
                        </div>
                        <div class="clearFix"></div>
                    </div>
                    <div class="wppas_actions">
                    	
                        <!-- Toadays statistics -->
                        <div class="dashboard_stats ad_dasboard_boxes">
                            
                            <?php 
							if($wpproads_enable_stats)
                            {
								global $wppas_stats;
								
								// Load daily stats.
								$stats = $wppas_stats->load_stats(array(
									'select' => 'impressions,clicks', 
									'where'  => 'time = '.$wppas_stats->today,
									'limit'  => 1
								));
								
								$impressions = !empty($stats[0]->impressions) ? $stats[0]->impressions : 0;
								$clicks = !empty($stats[0]->clicks) ? $stats[0]->clicks : 0;
								
								echo $wppas_stats_tpl->stats_header_tpl(
									array(
										'impressions' => $impressions, 
										'clicks'      => $clicks,
										'text'  => array(
											'click'       => __('Clicks','wpproads'),
											'impression'  => __('Impressions','wpproads'),
											'ctr'         => __('CTR','wpproads'),
										), 
									)
								);
                            	?>
                            	<a href="?page=wp-pro-ads-stats" class="button-secondary"><?php _e('Check all statistics','wpproads'); ?></a>
                                <?php
							}
							?>
                        </div>
                        <!-- end Toadays statistics -->
                        
                    </div>
                </div>
                <!-- end .wppas_box -->
                
                
                
                <!--
                 /**
                  * LICENSE KEY BOX
                  */
                -->
                <?php
				if( current_user_can(WP_ADS_ROLE_ADMIN))
				{
					$reg_status = empty($wpproads_license_key) ? 0 : 1;
					$text_color = $reg_status ? 'green' : 'red';
					$reg_status_text = $reg_status ? '<i class="fa fa-check" aria-hidden="true"></i> '.__('Activated','wpproads') : '<i class="fa fa-ban" aria-hidden="true"></i> '.__('Not Activated','wpproads');
					?>
					<div class="wppas_box col2 float_left">
						<div class="wppas_box_header">
							<h3 class="float_left <?php echo $text_color; ?>"><?php _e('Plugin Activation','wpproads'); ?></h3>
							<div class="wppas_status <?php echo $text_color; ?> float_right">
								<small style="font-size:12px;"><em><?php echo $reg_status_text; ?></em></small>
							</div>
							<div class="clearFix"></div>
						</div>
						<div class="wppas_actions">
							
							<?php
							if( empty($wpproads_license_key))
							{
								?>
								<p><?php _e('Please Register your copy of the WP PRO Advertising System to receive automatic plugin updates.','wpproads'); ?></p>
								<form action="" method="post" enctype="multipart/form-data">
									<p>
										<input type="text" name="wpproads_license_key" style="width:300px;" placeholder="<?php _e('License Key','wpproads'); ?>"> 
										<input type="submit" value="<?php _e('Register Plugin', 'wpproads'); ?>" class="button-secondary" name="wpproads_register_plugin" />
									</p>
							   </form>
							   <span class="description"><a href="<?php echo PAS()->faq; ?>get-license-key/" target="_blank"><?php _e('Where do I get the registration code?','wpproads'); ?></a></span>
								<?php
							}
							else
							{
								_e('Thanks for validating your license key. You will now receive automatic plugin updates.','wpproads');
								?>
								<ul class="validation_links">
									<li>
										<a href="<?php echo PAS()->docs; ?>" target="_blank">
											<div style="float:left; width:40px; padding-top: 5px;">
												<i class="fa fa-file-text-o" aria-hidden="true" style="font-size:26px;"></i>
											</div>
											<div class="float_left">
												<div class="main"><?php _e('Online Documentation','wpproads'); ?></div>
												<div><?php _e('The best start for beginners.','wpproads'); ?></div>
											</div>
											<div class="clearFix"></div>
										</a>
									</li>
									<li>
										<a href="<?php echo PAS()->faq; ?>" target="_blank">
											<div style="float:left; width:40px; padding-top: 5px;">
												<i class="fa fa-question" aria-hidden="true" style="font-size:26px;"></i>
											</div>
											<div class="float_left">
												<div class="main"><?php _e('Browse FAQ\'s','wpproads'); ?></div>
												<div><?php _e('Instant solutions for a lot of problems.','wpproads'); ?></div>
											</div>
											<div class="clearFix"></div>
										</a>
									</li>
									<li>
										<a href="<?php echo PAS()->support; ?>" target="_blank">
											<div style="float:left; width:40px; padding-top: 5px;">
												<i class="fa fa-comments-o" aria-hidden="true" style="font-size:26px;"></i>
											</div>
											<div class="float_left">
												<div class="main"><?php _e('Ticket Support','wpproads'); ?></div>
												<div><?php _e('Direct help from our qualified support team.','wpproads'); ?></div>
											</div>
											<div class="clearFix"></div>
										</a>
									</li>
								</ul>
								<?php
							}
							?>
							
						</div>
					</div>
					<!-- end .wppas_box -->
                <?php
				}
				?>
                
            	<div class="clearFix"></div>
            </div>
            <!-- end .wppas_box_group -->
            
            
            
            
           	<?php
			/*
            <div class="wppas_box_group">
			
                <div class="wppas_box col2 float_left">
                    <div class="wppas_box_header">
                        <h3><?php _e('New Banner - Kickstart','wpproads'); ?></h3>
                    </div>
                    <div class="wppas_actions kickstart">
                    	
                        <div class="wppas_action wppas_action_addnew">
                        	<?php
							echo '<a href="javascript:void(0);" class="wppas_kickstart_flyin" data-content="file_shortcode" flyin-data=\''.json_encode(array('title' => __('File Shortcode','wpproads'), 'cancel_txt' => __('Close','wpproads'), 'submit' => 1)).'\' >'; ?>
                            
                                <span class="wppas_icon_wrapper add_new_advertiser">
                                    <span class="wppas_action_icon add_new"></span>
                                </span>
                                <span class="wppas_action_info">
                                     <span class="wppas_action_title_wrapper">		
                                        <span class="wppas_action_title"><?php _e('1. Select Advertiser','wpproads'); ?></span>	
                                    </span>
                                </span>
                             </a>
                         </div>
                        
                    </div>
                    <!-- end .wppas_actions -->
                        
                </div>
                <!-- end .wppas_box -->
                
                <div class="clearFix"></div>
            </div>
			<!-- end .wppas_box_group --> 
            */
			?>
            
            
            
            
            
            <?php
			if( current_user_can(WP_ADS_ROLE_ADMIN))
			{
				?>
                <div class="wppas_box_group">
                    
                    <!--
                     /**
                      * GET STARTED
                      */
                    -->
                    <div class="wppas_box">
                        <div class="wppas_box_header">
                            <h3><?php _e('Get Started','wpproads'); ?></h3>
                        </div>
                        <ul class="wppas_actions">
                            
                            <!--<li class="wppas_action">
                                <div class="wppas_action_meta">
                                    
                                    <span class="wppas_action_info">
                                        <span class="wppas_action_title">New Advertiser</span>
                                        <a class="tls-settings" href="" ><i class="revicon-cog"></i></a>
                                        <a class="tls-editslides" href=""><i class="revicon-pencil-1"></i></a>
                                        <span class="tls-showmore"><i class="eg-icon-down-open"></i></span>
                                        
                                    </span>
                                    
                                </div>
                            </li>
                            <li class="wppas_action wppas_action_addnew">
                                <a href="">
                                    <span class="wppas_action_info">
                                        <span class="wppas_icon_wrapper">
                                            <span class="wppas_action_icon"></span>
                                        </span>
                                        <span class="wppas_action_title_wrapper">			
                                            <span class="wppas_action_title">New Campaign</span>					
                                        </span>
                                    </span>
                                </a>
                            </li>-->
                            <!-- end .wppas_action -->
                            
                            <li class="wppas_action wppas_action_addnew">
                                
                                <div class="wppas_action_meta">
                                    <a href="post-new.php?post_type=advertisers" title="<?php _e('Add New Advertiser','wpproads'); ?>">
                                        <span class="wppas_icon_wrapper add_new_advertiser">
                                            <span class="wppas_action_icon add_new"></span>
                                        </span>
                                     </a>
                                    <span class="wppas_action_info">
                                         <span class="wppas_action_title_wrapper">		
                                            <a href="edit.php?post_type=advertisers" title="<?php _e('All Advertisers','wpproads'); ?>">	
                                                <span class="wppas_action_title"><?php _e('Advertisers','wpproads'); ?></span>	
                                                <div class="float_right"><i class="fa fa-list" aria-hidden="true" style="margin-right: 5px;"></i></div>
                                            </a>				
                                        </span>
                                    </span>
                                </div>
                                
                            </li>
                            <!-- end .wppas_action -->
                            
                            <li class="wppas_action wppas_action_addnew">
                                
                                <div class="wppas_action_meta">
                                    <a href="post-new.php?post_type=campaigns" title="<?php _e('Add New Campaign','wpproads'); ?>">
                                        <span class="wppas_icon_wrapper add_new_campaign">
                                            <span class="wppas_action_icon add_new"></span>
                                        </span>
                                    </a>
                                    <span class="wppas_action_info">
                                         <span class="wppas_action_title_wrapper">	
                                            <a href="edit.php?post_type=campaigns" title="<?php _e('All Campaigns','wpproads'); ?>">		
                                                <span class="wppas_action_title"><?php _e('Campaigns','wpproads'); ?></span>	
                                                <div class="float_right"><i class="fa fa-list" aria-hidden="true" style="margin-right: 5px;"></i></div>
                                            </a>					
                                        </span>
                                    </span>
                                    
                                </div>
                                
                            </li>
                            <!-- end .wppas_action -->
                            
                            <li class="wppas_action wppas_action_addnew">
                                
                                <div class="wppas_action_meta">
                                    <?php //echo '<a href="javascript:void(0);" class="wppas_kickstart_flyin" data-content="add_banner" flyin-data=\''.json_encode(array('title' => __('Add New Banner','wpproads'), 'cancel_txt' => __('Close','wpproads'), 'submit' => 0)).'\' title="'.__('Add New Banner','wpproads').'">'; ?>
                                    <a href="post-new.php?post_type=banners" title="<?php _e('Add New Banner','wpproads'); ?>">
                                        <span class="wppas_icon_wrapper add_new_banner">
                                            <span class="wppas_action_icon add_new"></span>
                                        </span>
                                    </a>
                                    <span class="wppas_action_info">
                                         <span class="wppas_action_title_wrapper">	
                                            <a href="edit.php?post_type=banners" title="<?php _e('All Banners','wpproads'); ?>">		
                                                <span class="wppas_action_title"><?php _e('Banners','wpproads'); ?></span>	
                                                <div class="float_right"><i class="fa fa-list" aria-hidden="true" style="margin-right: 5px;"></i></div>
                                            </a>						
                                        </span>
                                    </span>
                                </div>
                                
                            </li>
                            <!-- end .wppas_action -->
                            
                            <li class="wppas_action wppas_action_addnew">
                                
                                <div class="wppas_action_meta">
                                    <a href="post-new.php?post_type=adzones" title="<?php _e('Add New Adzone','wpproads'); ?>">
                                        <span class="wppas_icon_wrapper add_new_adzone">
                                            <span class="wppas_action_icon add_new"></span>
                                        </span>
                                    </a>
                                    <span class="wppas_action_info">
                                         <span class="wppas_action_title_wrapper">	
                                            <a href="edit.php?post_type=adzones" title="<?php _e('All Adzones','wpproads'); ?>">		
                                                <span class="wppas_action_title"><?php _e('Adzones','wpproads'); ?></span>		
                                                <div class="float_right"><i class="fa fa-list" aria-hidden="true" style="margin-right: 5px;"></i></div>
                                            </a>						
                                        </span>
                                    </span>
                                    
                                </div>
                                
                            </li>
                            <!-- end .wppas_action -->
                            
                        </ul>
                        <!-- end .wppas_actions -->
                    </div>
                    <!-- end .wppas_box -->
                    
                </div>
                <!-- end .wppas_box_group -->    
                <?php
			}
			?>
            
            
            
            
            
            <!--
             /**
              * PLUGIN OPTIONS BOX
              */
            -->
            <?php
			if( current_user_can(WP_ADS_ROLE_ADMIN))
			{
				?>
                <div class="wppas_box_group">
                
                    <div class="wppas_box">
                        <div class="wppas_box_header">
                            <h3><?php _e('Plugin Options','wpproads'); ?></h3>
                        </div>
                        <div class="wppas_actions">
                            
                           
    
                            
                            <!-- 
                             /**
                              * tabs
                              */
                             -->
                            <ul id="wppas_tab" class="wppas_menu_toggle">
                                <?php
                                if( current_user_can(WP_ADS_ROLE_ADMIN))
                                {
                                    ?>
                                    <li class="selected" data-target="general-settings">
                                        <i class="fa fa-cog" aria-hidden="true" style="margin-right: 5px;"></i> <?php _e('General Settigs','wpproads'); ?>
                                    </li>
                                    <li data-target="post-template">
                                        <i class="fa fa-columns" aria-hidden="true" style="margin-right: 5px;"></i> <?php _e('Manage Post Ads','wpproads'); ?>
                                    </li>
                                    <li data-target="manual-updates" title="">
                                        <i class="fa fa-refresh" aria-hidden="true" style="margin-right: 5px;"></i> <?php _e('Manual Updates', 'wpproads'); ?>
                                    </li>
                                    <li data-target="buyandsell-addon" title="">
                                        <i class="fa fa-usd" aria-hidden="true" style="margin-right: 5px;"></i> <?php _e('Buy and Sell Ads', 'wpproads'); ?>
                                    </li>
                                    <?php
									// import stats tab
									global $wpdb;
									$chk_query = $wpdb->get_results('SELECT id FROM '.$wpdb->prefix.'wpproads_user_stats LIMIT 1');
									if( !empty($chk_query))
									{
										?>
                                        <li data-target="import-old-stats" title="">
                                            <i class="fa fa-area-chart" aria-hidden="true" style="margin-right: 5px;"></i> <?php _e('Import Old Stats', 'wpproads'); ?>
                                        </li>
                                    	<?php
									}
                                }
                                ?>
                            </ul>
                            <div class="clearFix"></div>
                            
                            
                            
                            
                            
                            <!-- 
                             /**
                              * start // tabs content 
                              */
                             -->
                            <?php
                            if( current_user_can(WP_ADS_ROLE_ADMIN))
                            {
                                ?>
                                <div id="general-settings" class="nfer">
                                    
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="tuna_meta metabox-holder">
                                        
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Settings','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Enable Admin Bar", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want to add the custom admin bar?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_adminbar" name="wpproads_enable_adminbar">
                                                                        <option value="0" <?php echo empty($wpproads_enable_adminbar) ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="1" <?php echo $wpproads_enable_adminbar ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Uninstall Option", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Remove all data while uninstalling the plugin?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_uninstall" name="wpproads_uninstall">
                                                                        <option value="0" <?php echo empty($wpproads_uninstall) ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="1" <?php echo $wpproads_uninstall ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Adzone Class", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('The default adzone class name.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <input type="text" name="wpproads_adzone_class" value="<?php echo $wpproads_adzone_class; ?>" />
                                                                    <span class="description">
                                                                        <?php _e('Adjust this class name from time to time to mislead adblockers.','wpproads'); ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Permalinks','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Enable Clean permalinks", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want to enable clean permalinks for your ads?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_mod_rewrite" name="wpproads_enable_mod_rewrite">
                                                                        <option value="0" <?php echo empty($wpproads_enable_mod_rewrite) ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="1" <?php echo $wpproads_enable_mod_rewrite ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr id="wpproads_enable_mod_rewrite_box" <?php echo !empty($wpproads_enable_mod_rewrite) ? '' : 'style="display:none;"'; ?>>
                                                                <th scope="row">
                                                                    <?php _e( "Link prefix", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Add your permalink prefix. <strong>NOTE:</strong> You will need to update your Permalinks after updating.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <input type="text" name="wp_ads_mod_rewrite" value="<?php echo $wp_ads_mod_rewrite; ?>" />
                                                                    <span class="description">
                                                                        <?php _e('ex.:','wpproads'); ?> <?php bloginfo('url'); ?>/<strong><?php echo $wp_ads_mod_rewrite; ?></strong>/banner-name/adzone-name
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Statistics','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Enable Satistics", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want statistics to be saved?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_stats" name="wpproads_enable_stats">
                                                                        <option value="0" <?php echo empty($wpproads_enable_stats) ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="1" <?php echo $wpproads_enable_stats ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            
                                                            <?php
															/*
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Disable Impressions", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want to disable impressions from being saved?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_impr" name="wpproads_enable_impr">
                                                                        <option value="1" <?php echo $wpproads_enable_impr ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="0" <?php echo empty($wpproads_enable_impr) ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Disable Clicks", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want to disable clicks from being saved?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_clicks" name="wpproads_enable_clicks">
                                                                        <option value="1" <?php echo $wpproads_enable_clicks ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="0" <?php echo empty($wpproads_enable_clicks) ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Statistics version", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('What version do you want to use?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_stats_version" name="wpproads_stats_version">
                                                                    	<option value="" <?php echo empty($wpproads_stats_version) ? 'selected' : ''; ?>>
                                                                        	<?php _e('--- Select ---', 'wpproads'); ?>
                                                                        </option>
                                                                        <!--<option value="" <?php echo empty($wpproads_stats_version) ? 'selected' : ''; ?>>
                                                                            <?php _e('Deafault Statistics', 'wpproads'); ?>
                                                                        </option>-->
                                                                        <option value="_new" <?php echo $wpproads_stats_version == '_new' ? 'selected' : ''; ?>>
                                                                            <?php _e('New Statistics (recommended)', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="array_stats" <?php echo $wpproads_stats_version == 'array_stats' ? 'selected' : ''; ?>>
                                                                            <?php _e('Array Statistics', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Statistics data", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('How detailed do you want to track statistics? NOTE: Hourly statistics will make your database grow fast', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_stats_data" name="wpproads_stats_data">
                                                                        <option value="hour" <?php echo empty($wpproads_stats_data) || $wpproads_stats_data == 'hour' ? 'selected' : ''; ?>>
                                                                            <?php _e('Hourly Statistics', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="day" <?php echo $wpproads_stats_data == 'day' ? 'selected' : ''; ?>>
                                                                            <?php _e('Daily Statistics (recommended)', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"><?php _e( "Only available for the NEW statistics version.", 'wpproads' ); ?></span>
                                                                </td>
                                                            </tr>
															*/
															?>
                                                            <tr id="enable_userdata_stats" <?php echo !empty($wpproads_enable_stats) ? '' : 'style="display:none;"'; ?>>
                                                                <th scope="row">
                                                                    <?php _e( "Enable User data stats", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want specific user data for statistics to be saved?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_userdata_stats" name="wpproads_enable_userdata_stats">
                                                                        <option value="0" <?php echo empty($wpproads_enable_userdata_stats) ? 'selected' : ''; ?>>
                                                                            <?php _e('No', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="1" <?php echo $wpproads_enable_userdata_stats ? 'selected' : ''; ?>>
                                                                            <?php _e('Yes', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"><?php echo sprintf(__('<strong>Did you know?</strong> You can save specific user GEO data by installing the %s plugin.', 'wpproads'), '<a href="http://bit.ly/wpgeotargeting" target="_blank">WP PRO GEO TARGETING</a>'); ?></span>
                                                                </td>
                                                            </tr>
                                                            
                                                            <?php
															/*
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Save Impressions", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want unique or all impressions to be saved for statistics?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_save_impressions_type" name="wpproads_save_impressions_type">
                                                                        <option value="unique" <?php echo empty($wpproads_save_impressions_type) || $wpproads_save_impressions_type == 'unique' ? 'selected' : ''; ?>>
                                                                            <?php _e('Unique Impressions', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="all" <?php echo $wpproads_save_impressions_type == 'all' ? 'selected' : ''; ?>>
                                                                            <?php _e('All Impressions', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Save Clicks", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Do you want unique or all clicks to be saved for statistics?', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_save_clicks_type" name="wpproads_save_clicks_type">
                                                                        <option value="unique" <?php echo empty($wpproads_save_clicks_type) || $wpproads_save_clicks_type == 'unique' ? 'selected' : ''; ?>>
                                                                            <?php _e('Unique Clicks', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="all" <?php echo $wpproads_save_clicks_type == 'all' ? 'selected' : ''; ?>>
                                                                            <?php _e('All Clicks', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Days to save stats", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Choose the number of days you want to save statistics to the database. Keep this at 30 days or less to keep the database small.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <input name="wpproads_stats_save_days" type="number" value="<?php echo $wpproads_stats_save_days; ?>" />
                                                                    <span class="description"><?php _e( "Leave empty to save all stats (not recommended)", 'wpproads' ); ?></span>
                                                                </td>
                                                            </tr>
															*/
															?>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e('Block IP Addresses', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('Add IP addresses where you don\'t want to save statistics for.','wpproads'); ?><br /><br /><?php echo sprintf(__('Your current IP: %s ','wpproads'), '<strong>'.$pro_ads_main->IP.'</strong>'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <textarea id="wpproads_stats_blocked_ips" class="ivc_input" style="height:100px; margin:10px 0 0 0;" name="wpproads_stats_blocked_ips"><?php echo $wpproads_stats_blocked_ips; ?></textarea>
                                                                    <span class="description"><?php _e('Comma separated','wpproads'); ?></span>
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            
                                            
                                            
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Allowed Origins','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                        	<tr>
                                                                <th scope="row">
                                                                    <?php _e('Enable Asynchronous JS Tag', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('Do you want to enable the Asynchronous JS Tag.','wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                	<select name="wpproads_enable_async_js_tag">
                                                                    	<option value="1" <?php echo $wpproads_enable_async_js_tag ? 'selected="selected"' : ''; ?>><?php _e('Yes','wpproads'); ?></option>
                                                                        <option value="0" <?php echo !$wpproads_enable_async_js_tag ? 'selected="selected"' : ''; ?>><?php _e('No', 'wpproads'); ?></option>
                                                                    </select>
                                                                    
                                                                    <span class="description"><?php _e('','wpproads'); ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e('Asynchronous JS Tag', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('Add the domain names for the external websites that are able to use the Asynchronous JS Tag to show banners.','wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <textarea class="ivc_input" style="height:100px; margin:10px 0 0 0;" name="wpproads_allowed_origens"><?php echo stripslashes($wpproads_allowed_origens); ?></textarea>
                                                                    <span class="description"><?php _e('Add domain names separated by comma.','wpproads'); ?></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            
                                            
                                            
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Google Analytics','wpproads'); ?></h3>
                                                    <p><?php _e('When you are running Google Analytics for your website and you add your tracking / web property ID (UA-XXXX-Y), all statistics will be redirected to your Google Analytics page.','wpproads'); ?></p>
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "The tracking / web property ID. The format is UA-XXXX-Y", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('You will find this ID on your Google Analytics page.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <input type="text" name="wpproads_google_analytics_id" value="<?php echo $wpproads_google_analytics_id; ?>" />
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('W3 Total Page Caching','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row" colspan="2">
                                                                    <?php _e('Disable page caching on pages with banners', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('Sometimes page caching plugins will prevent adzones from rotating banners. This option aloows you to turn of page caching for pages where banners are shown.','wpproads'); ?></span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <select id="wpproads_page_caching" name="wpproads_page_caching">
                                                                        <option value="1" <?php echo !empty($wpproads_page_caching) ? 'selected' : ''; ?>>
                                                                            <?php _e('Enable page caching', 'wpproads'); ?>
                                                                        </option>
                                                                        <option value="0" <?php echo empty($wpproads_page_caching) ? 'selected' : ''; ?>>
                                                                            <?php _e('Disable page caching', 'wpproads'); ?>
                                                                        </option>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <h3><?php _e('Style','wpproads'); ?></h3>
                                                    <table class="form-table">
                                                        <tbody>
                                                        <?php /*
                                                        	<tr>
                                                                <th scope="row">
                                                                    <?php _e('Enable custom CSS', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('Do you want to enable custom CSS?','wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                	<select name="wpproads_enable_custom_css">
                                                                    	<option value="1" <?php echo $wpproads_enable_custom_css ? 'selected="selected"' : ''; ?>><?php _e('Yes','wpproads'); ?></option>
                                                                        <option value="0" <?php echo !$wpproads_enable_custom_css ? 'selected="selected"' : ''; ?>><?php _e('No', 'wpproads'); ?></option>
                                                                    </select>
                                                                    
                                                                    <span class="description"><?php _e('','wpproads'); ?></span>
                                                                </td>
                                                            </tr>
															*/
															?>
                                                            <tr>
                                                                <th scope="row" colspan="2">
                                                                    <?php _e('Custom CSS', 'wpproads'); ?>
                                                                    <span class="description"><?php _e('If you need to customize some style for the Ads plugin you can add the custom CSS here.','wpproads'); ?></span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <pre id="wppas_code_editor"><?php echo stripslashes($custom_css); ?></pre>
                                                                    <textarea id="wpproads_custom_css" class="ivc_input" style="height:100px; margin:10px 0 0 0;" name="wpproads_custom_css"><?php echo stripslashes($custom_css); ?></textarea>
                                                                    <span class="description"><?php _e('','wpproads'); ?></span>
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                            
                                        </div>
                                        <!-- end .tuna_meta -->
                                                
                                        
                                        <div class="btn_container_with_menu" style="margin-top:40px;">
                                            <input type="submit" value="<?php _e('Save General Settings', 'wpproads'); ?>" class="button-secondary" name="1" />
                                        </div>
                                    </form>
                                </div>
                                <!-- end #general-settings -->
                                
                                
                                
                                
                                
                                <div id="post-template" style="display:none;" class="nfer">
                                    
                                   <form action="" method="post" enctype="multipart/form-data">
                                        <div class="tuna_meta metabox-holder">
                            
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Enable Post Ads", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('This allows you to add banners to all posts automatically. Select the post types where you want to use this function.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_enable_post_ads" name="wpproads_enable_post_ads[]" data-placeholder="<?php _e('No post types selected.', 'wpproads'); ?>" class="chosen-select-ad-dashboard" multiple>
                                                                        <option value="0"><?php _e('','wpproads'); ?></option>
                                                                        <?php
                                                                        $post_types = get_post_types();
                                                                        if( !empty($post_types ))
                                                                        {
                                                                            foreach( $post_types as $post_type )
                                                                            {
                                                                                $exclude = array('attachment', 'revision', 'nav_menu_item', 'vbc_banners');
                                                                                if( !in_array( $post_type, PAS()->cpts ) && !in_array( $post_type, $exclude))
                                                                                {
                                                                                    $selected = in_array($post_type, $wpproads_enable_post_ads) ? 'selected' : '';
                                                                                    echo '<option value="'.$post_type.'" '.$selected.'>'.$post_type.'</option>';
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Top AD", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('This will show an adzone above the post.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_post_ads_top" name="wpproads_post_ads_top" data-placeholder="<?php _e('No adzone selected.', 'wpproads'); ?>" class="chosen-select-ad-dashboard">
                                                                        <option value="0"><?php _e('Leave Empty','wpproads'); ?></option>
                                                                        <?php
                                                                        $all_adzones = $pro_ads_adzones->get_adzones();
                                                                        foreach( $all_adzones as $adzone )
                                                                        {
                                                                            $disabled = !$pro_ads_adzones->check_if_adzone_is_active( $adzone->ID ) ? 'disabled="true"' : '';
                                                                            $selected = $adzone->ID == $wpproads_post_ads_top ? 'selected' : '';
                                                                            echo '<option '.$disabled.' value="'.$adzone->ID.'" '.$selected.'>'.$adzone->post_title.'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Center AD", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('This will show an adzone after the 2nd paragraph of the post.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_post_ads_center" name="wpproads_post_ads_center" data-placeholder="<?php _e('No adzone selected.', 'wpproads'); ?>" class="chosen-select-ad-dashboard">
                                                                        <option value="0"><?php _e('Leave Empty','wpproads'); ?></option>
                                                                        <?php
                                                                        $all_adzones = $pro_ads_adzones->get_adzones();
                                                                        foreach( $all_adzones as $adzone )
                                                                        {
                                                                            $disabled = !$pro_ads_adzones->check_if_adzone_is_active( $adzone->ID ) ? 'disabled="true"' : '';
                                                                            $selected = $adzone->ID == $wpproads_post_ads_center ? 'selected' : '';
                                                                            echo '<option '.$disabled.' value="'.$adzone->ID.'" '.$selected.'>'.$adzone->post_title.'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Center AD Paragraph", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Select after how many paragraphs the center ad should show.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_post_ads_center_para" name="wpproads_post_ads_center_para">
                                                                        <option value="1" <?php echo $wpproads_post_ads_center_para == 1 ? 'selected="selected"' : ''; ?>>1</option>
                                                                        <option value="2" <?php echo empty($wpproads_post_ads_center_para) || $wpproads_post_ads_center_para == 2 ? 'selected="selected"' : ''; ?>>2</option>
                                                                        <option value="3" <?php echo $wpproads_post_ads_center_para == 3 ? 'selected="selected"' : ''; ?>>3</option>
                                                                        <option value="4" <?php echo $wpproads_post_ads_center_para == 4 ? 'selected="selected"' : ''; ?>>4</option>
                                                                        <option value="5" <?php echo $wpproads_post_ads_center_para == 5 ? 'selected="selected"' : ''; ?>>5</option>
                                                                        <option value="6" <?php echo $wpproads_post_ads_center_para == 6 ? 'selected="selected"' : ''; ?>>6</option>
                                                                    </select>
                                                                    <span class="description"><?php _e( "", 'wpproads' ); ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Center AD Align", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('Select how you want to align the ad in your post.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_post_ads_center_align" name="wpproads_post_ads_center_align">
                                                                        <option value="" <?php echo empty($wpproads_post_ads_center_align) ? 'selected="selected"' : ''; ?>><?php _e('Default','wpproads'); ?></option>
                                                                        <option value="left" <?php echo $wpproads_post_ads_center_align == 'left' ? 'selected="selected"' : ''; ?>><?php _e('Left','wpproads'); ?></option>
                                                                        <option value="right" <?php echo $wpproads_post_ads_center_align == 'right' ? 'selected="selected"' : ''; ?>><?php _e('Right','wpproads'); ?></option>
                                                                    </select>
                                                                    <span class="description"><?php _e( "Selecting Left or Right will wrap the post text around your advertisement.", 'wpproads' ); ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php _e( "Bottom AD", 'wpproads' ); ?>
                                                                    <span class="description"><?php _e('This will show an adzone under the post.', 'wpproads'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="wpproads_post_ads_bottom" name="wpproads_post_ads_bottom" data-placeholder="<?php _e('No adzone selected.', 'wpproads'); ?>" class="chosen-select-ad-dashboard">
                                                                        <option value="0"><?php _e('Leave Empty','wpproads'); ?></option>
                                                                        <?php
                                                                        $all_adzones = $pro_ads_adzones->get_adzones();
                                                                        foreach( $all_adzones as $adzone )
                                                                        {
                                                                            $disabled = !$pro_ads_adzones->check_if_adzone_is_active( $adzone->ID ) ? 'disabled="true"' : '';
                                                                            $selected = $adzone->ID == $wpproads_post_ads_bottom ? 'selected' : '';
                                                                            echo '<option '.$disabled.' value="'.$adzone->ID.'" '.$selected.'>'.$adzone->post_title.'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <span class="description"></span>
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                        </div>
                                        <!-- end .tuna_meta -->
                                                
                                        
                                        <div class="btn_container_with_menu" style="margin-top:40px;">
                                            <input type="submit" value="<?php _e('Save Post Ad Settings', 'wpproads'); ?>" class="button-secondary" name="wpproads_post_template" />
                                        </div>
                                    </form>
                                   
                                </div>
                                <!-- end #post-template -->
                                
                                <div id="manual-updates" style="display:none;" class="nfer">
                                    <div class="tuna_meta metabox-holder">
                                            
                                        <div class="postbox nobg">
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                
                                                                <?php _e('Campaign, Banner and Statistic statuses get updated once a day automatically. If you want to, you can manually Finish or Start campaigns, remove outdated statistics by clicking the update button.','wpproads'); ?>
                                                                <br /><br />
                                                                <a href="javascript:void(0)" id="manual_update_campaings_banners" class="button-secondary"><?php _e('Update Campaigns/Banners/Statistics','wpproads'); ?></a>
                                                                <span class="description manual_update_info"><?php _e('','wpproads'); ?></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- end .postbox -->
                                    </div>
                                    <!-- end .tuna_meta --> 
                                </div>
                                <!-- end #manual-updates -->
                                
                                <div id="buyandsell-addon" style="display:none;" class="nfer">
                                    <h2>
                                        <?php 
                                        if( $pro_ads_main->buyandsell_is_active() )
                                        {
                                            _e('Buy and Sell Ads, Settings', 'wpproads');
                                        }
                                        elseif( $pro_ads_main->buyandsell_woo_is_active() )
                                        {
                                            _e('Buy and Sell Ads, Woocommerce Settings', 'wpproads');
                                        }
                                        else
                                        {
                                            _e('Buy and Sell Ads', 'wpproads');
                                        }
                                        ?>
                                    </h2>
                                    
                                    <table class="form-table">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    
                                                    <?php
                                                    if( $pro_ads_main->buyandsell_is_active() )
                                                    {
                                                        $pro_ads_bs_templates->pro_ad_buy_and_sell_settings();
                                                    }
                                                    elseif( $pro_ads_main->buyandsell_woo_is_active() )
                                                    {
                                                        $pro_ads_bs_woo_templates->pro_ad_buy_and_sell_woo_settings();
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <div style="float:left; width:150px; height:100px; background:#F3F3F3; border:solid 1px #EDEDED; margin:0 10px 0;">
                                                            <div style="text-align:center; padding-top:40px;">
                                                                <a href="http://bit.ly/buyandsellads" style="text-decoration:none; color:#999;" target="_blank"><?php _e('Advertise Here','wpproads'); ?></a>
                                                            </div>
                                                        </div> 
                                                        <?php _e('Make sure your adzones do not remain empty! Let users buy advertisments directly on your website! The Buy and Sell Add-on turns your empty adzones into links where users can instantly upload their banner, pay and activate their advertisement on your website.','wpproads'); ?>
                                                        
                                                        <div class="clearFix"></div>
                                                        <br /><br />
                                                        <a href="http://bit.ly/buyandsellads" class="button-secondary" target="_blank"><?php _e('Download Buy and Sell Ads - ADD-ON','wpproads'); ?></a>
                                                        <?php	
                                                    }
                                                    ?>
                                                    <span class="description manual_update_info"><?php _e('','wpproads'); ?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                            
                                </div>
                                <!-- end #buyandsell-addon -->
                                
                                
                                
                                
                                <div id="import-old-stats" style="display:none;" class="nfer">
                                	<div class="tuna_meta metabox-holder">
                            
                                        <div class="postbox nobg">
                                            <div class="inside">
                                	
                                                <h2><?php _e('Import old Stats', 'wpproads'); ?></h2>
                                                <p>
                                                    <?php _e('Since version 4.7.5 we drastically improved the statistics for the plugin. Old stats may currently not be visible in your statistics but don\'t worry you can still import them into the new statistics database.', 'wpproads'); ?>
                                                </p>
                                                
                                                <table class="form-table imp_dates_form" width="200">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                            	<?php _e('Start day:','wpproads'); ?>
                                                                <span class="description"></span>
                                                            </th>
                                                            <td><input type="number" id="imp_sday" min="1" max="31" value="1" /></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
																<?php _e('End day:','wpproads'); ?>
                                                                <span class="description"></span>
                                                            </th>
                                                            <td><input type="number" id="imp_eday" min="1" max="31" value="1" /></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
																<?php _e('Month:','wpproads'); ?>
                                                                <span class="description"></span>
                                                            </th>
                                                            <td><input type="number" id="imp_month" min="1" max="12" value="1" /></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
																<?php _e('Year:','wpproads'); ?>
                                                                <span class="description"></span>
                                                            </th>
                                                            <td><input type="number" id="imp_year" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <a id="imp_calc_data" style="cursor:pointer;" class="button-secondary"><?php _e('Calculate stats data','wpproads'); ?></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <table class="form-table" width="200">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="imp_info"></div>
                                                                <a id="save_old_stats" style="display:none; cursor:pointer;" class="button-secondary"><?php _e('Start Importing Stats','wpproads'); ?></a>
                                                                <div id="progressCounter" data-perc="0"></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                
                                                <script type="text/javascript">
                                                jQuery(document).ready(function($){
                                                    
                                                    $("body").on("click", "#imp_calc_data", function(event){
                                                        var sday = $('#imp_sday').val();
                                                        var eday = $('#imp_eday').val();
                                                        var month = $('#imp_month').val();
                                                        var year = $('#imp_year').val();
                                                        
                                                        $('.imp_dates_form').css({'opacity': .3});
                                                        
                                                        $.ajax({
                                                            type: "POST",
                                                            url: ajaxurl,
                                                            data: "action=wppas_calculate_stats_import&sday="+sday+"&eday="+eday+"&month="+month+"&year="+year
                                                        }).done(function(obj) {
                                                            
                                                            msg = JSON.parse( obj );
                                                            
                                                            var itemcount = msg.itemcount;
                                                            var batches = msg.batches;
                                                            var bnum = msg.bnum;
                                                            var sdate = msg.sdate;
                                                            var edate = msg.edate;
                                                            //console.log(msg.itemcount);
                                                            $('.imp_dates_form').css({'opacity': 1});
                                                            
                                                            if( itemcount < 5000 && itemcount > 0 )
                                                            {
                                                                $('.imp_info').html('Great, you will be importing about '+itemcount+' stats. Click the "Import Stats" button to start.');
                                                                $('#save_old_stats').show();
                                                                
                                                                
                                                                $("body").on("click", "#save_old_stats", function(event){
                                                            
                                                                    var perc = 0;
                                                                    
                                                                    console.log(perc+"%");
                                                                    
                                                                    for(var i = 0; i <= batches; i++)
                                                                    {
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: ajaxurl,
                                                                            data: "action=wppas_save_old_stats&num="+i+"&bnum="+bnum+"&batches="+batches+"&sdate="+sdate+"&edate="+edate
                                                                        }).done(function(msg) {
                                                                            
                                                                            perc = Math.round(msg);
                                                                            
                                                                            if(perc > $('#progressCounter').data('perc') ){
                                                                                $('#progressCounter').html(perc+"%");
                                                                                $('#progressCounter').data('perc', perc);
                                                                                //console.log(perc);
                                                                            }
                                                                            
                                                                        });
                                                                    }
                                                                });
                                                            }else{
                                                                
                                                                if( itemcount > 5000){
                                                                    $('.imp_info').html('<span style="color:#ff0081; font-weight:bold;">Woops, It looks like you have lots of data to import. Over <u>'+itemcount+'</u> is not ideal. try adjusting the dates and import stats in smaller batches.</span>');
                                                                }else{
                                                                    $('.imp_info').html('Woops, No stats data could be found for this date.');
                                                                }
                                                            } 
                                                        });
                                                    });
                                                    
                                                });
                                                </script>
                                			</div>
                                		</div>
                                	</div>
                                </div>
                                <!-- end #import-old-stats -->
                                
                               
                               
                                
                                <?php
                            }
                            ?>
                         
                            <!-- end // tabs content -->
                           
                        </div>
                    </div>
                    <!-- end .wppas_box -->
                    
                    <div class="clearFix"></div>
                </div>
                <!-- end .wppas_box_group -->
            <?php
			}
			?>
            
            
            
        </div>
        <!-- end .wppas_box_container -->
    
    
    
	</div>
    <!-- end .wrap -->
</div>
<!-- end .wppas_dashboard -->


<?php
echo $pro_ads_templates->flyin_container();
?>



<script type='text/javascript'>
jQuery(document).ready(function($) {
    
    
	
	// switching between tabs
	$('#wppas_tab').find('li').click(function(){
		
		var nfer_id = $(this).data('target');
		
		$('.nfer').hide();
		$('#'+nfer_id).show();
		
		$('#wppas_tab').find('li').removeClass('selected');
		$(this).addClass('selected');
		
		//window.location.hash = nfer_id;
		return false;
		
	});
	
	
	/**
	 * Code Editor
	 */
	var editor = ace.edit("wppas_code_editor");
	var textarea = $("textarea[name=wpproads_custom_css]").hide();
	
	editor.setTheme("ace/theme/github");
	editor.session.setMode("ace/mode/css");
	editor.setOptions({
		maxLines: 20,
		minLines: 5,
		showPrintMargin: false
	});
	
	editor.getSession().on("change", function(){
		textarea.val(editor.getSession().getValue());
	});
	
	
	
	/**
	 * Open POPUP
	 */
	/*$("body").on("click", "#wppas_kickstart", function(event){
		var source_item = $('.wppas-popup-item');
		wppas_popup_options( source_item );
	});*/
	
	/**
	 * Open FLYIN
	 */
	$("body").on("click", ".wppas_kickstart_flyin", function(event){
		source_item = jQuery('.wppas-flyin-container');
		source_item.find('.wppas-flyin-save-btn').html('Save Changes');
		source_item.find('.wppas-flyin').show();
		wppas_flyin_options( source_item );
		jQuery("#wppas_flyin_loader").show();
		flyin_resize({percent:73, w:45});
		
		
		var content = jQuery(this).attr('data-content'),
			flyin_data = jQuery(this).attr('flyin-data');
			
		flyin_data = flyin_data != null ? JSON.parse(flyin_data) : null;
		
		if( flyin_data != null ){
			flyin_data.title != null ? source_item.find('.wppas-flyin-title').html(flyin_data.title) : '';
			flyin_data.cancel_txt != null ? source_item.find('.cancel_txt').html(flyin_data.cancel_txt) : '';
			flyin_data.submit_txt != null ? source_item.find('.submit_txt').html(flyin_data.submit_txt) : '';
			flyin_data.submit != null ? flyin_data.submit > 0 ? source_item.find('.wppas-flyin-save-btn').show() : source_item.find('.wppas-flyin-save-btn').hide() : '';
		}
		
		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: "action=wppas_load_popup&post_id=0&type=flyin&content="+content
		}).done(function(msg) {
			source_item.find('.wppas-flyin-body').html(msg);
			$("#wppas_flyin_loader").hide();
			
			
			create_banner_kickstart();
			
			// Save Button
			/*jQuery('.snipr-flyin-save-btn').unbind('click').bind('click', function (e) {
			
				jQuery("#snipr_flyin_loader").show();
				jQuery(".snipr-flyin-body").css({opacity: .3});
				
				var settings = {};
				
				jQuery('.snipr_edit_template .input').each(function () {
					key = jQuery(this).attr('id').replace('snipr_ps_','');
					settings[key] = jQuery(this).val();
				});
				
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: "action=snipr_save_project_settings&post_id="+builder_data.post_id+"&settings="+JSON.stringify(settings)
				}).done(function(msg) {
					//console.log(msg);
					
					// Close popup/flyin
					snipr_flyin_close( source_item );
				});
			});*/
		});
	});
    
});





/**
 * POPUP / FLYIN options
 */
function wppas_popup_options( source_item ){
	
	// Show BG
	source_item.find('.wppas_pop_bg').show();
	//jQuery(".wppas-item-meta-cont").css({opacity: 1});
	//jQuery("body").addClass("wppas-model-open");
	jQuery("html").addClass("wppas-model-open");
	
	// popup - close item ------------------------------------------
	jQuery("body").on("click", ".wppas-popup-close", function(event){
		wppas_popup_close( source_item );
	});
}

function wppas_close( source_item ){
	source_item = jQuery('.wppas-popup-item');
	source_item.hide();
	source_item.find('.wppas-popup-bg').hide();
	source_item.find('.wppas-item-meta-cont').html('');
	jQuery(".wppas-item-meta-cont").css({opacity: 1});
	jQuery("html").removeClass("wppas-model-open");
	jQuery("#wppas_popup_loader").hide();
}


function wppas_flyin_options( source_item ){
	
	// Show BG
	source_item.find('.wppas-flyin-bg').show();
	jQuery("html").addClass("wppas-model-open");
	
	// flyin - cancel btn ------------------------------------------
	jQuery('body').find('.wppas-flyin-cancel-btn').unbind('click').bind('click', function (e) {
	//jQuery("body").on("click", ".wppas-flyin-cancel-btn", function(event){
		wppas_flyin_close( jQuery('.wppas-flyin-container') );
	});
}

function wppas_flyin_close( source_item ){
	source_item.find('.wppas-flyin').hide();
	source_item.find('.wppas-flyin-bg').hide();
	source_item.find('.wppas-flyin-body').html('');
	source_item.find('.wppas-flyin-title').html('');
	source_item.find('.cancel_txt').html('Cancel');
	source_item.find('.wppas-flyin-save-btn').show();
	jQuery("#wppas_flyin_loader").hide();
	jQuery(".wppas-flyin-body").css({opacity: 1});
	jQuery("html").removeClass("wppas-model-open");
	
	jQuery(this).addClass('small');
	jQuery(this).removeClass('large');
	flyin_resize({percent:73, w:45});
}


// Flyin Resize
function flyin_resize(obj){
	
	var percent = obj.percent != null ? obj.percent : 73,
		w = obj.w != null ? obj.w : percent,
		h = obj.h != null ? obj.h : percent;
	
	jQuery(window).bind('resize', function () {
		
		var $this  = jQuery(this);	
		
		jQuery('.wppas-flyin').css({
			width      : Math.floor($this.width() * (w / 100)),
			height     : Math.floor($this.height() * (h / 100)),
			marginLeft : Math.floor((1 - (percent / 100)) / 2 * $this.width()),
			marginTop  : 20 * (percent / 100),
			'padding-bottom' : 50 * (percent / 100)
		});
		jQuery('.wppas-flyin-body').css({
			'max-height' : Math.floor(jQuery('.wppas-flyin').height() - 70),
			'height' : Math.floor(jQuery('.wppas-flyin').height() - 70),
		});	
		
	}).trigger('resize');
}

</script>