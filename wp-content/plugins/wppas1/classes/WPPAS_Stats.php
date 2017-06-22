<?php
class WPPAS_Stats {	

	public $IP;
	public $today;
	public $year;
	public $month;
	public $day;
	public $hour;
	public $current_time;
	

	public function __construct() 
	{
		global $pro_ads_main;
		
		add_action( 'wp_loaded', array( $this, 'wpproads_pdf_template' ) );	
		
		$this->IP = $pro_ads_main->IP;
		$this->current_time = current_time( 'timestamp' );
		$this->today = mktime(0, 0, 0, $pro_ads_main->time_by_timezone('m'), $pro_ads_main->time_by_timezone('d'), $pro_ads_main->time_by_timezone('Y'));
		$this->year = $pro_ads_main->time_by_timezone('Y');
		$this->month = $pro_ads_main->time_by_timezone('n');
		$this->day = $pro_ads_main->time_by_timezone('j');
		$this->hour = date_i18n("G");
	}
	
	
	
	
	/*
	 * IP Addresses blocked?
	 *
	 * @access public
	 * @return bool
	*/
	public function ip_blocked()
	{
		global $pro_ads_multisite;
		
		$ip_adress = $this->IP;
		$wpproads_stats_blocked_ips = $pro_ads_multisite->wpproads_get_option('wpproads_stats_blocked_ips', '');
		$blocked_ips = explode(',', $wpproads_stats_blocked_ips);
		
		$is_blocked = in_array($ip_adress, $blocked_ips) ? 1 : 0;
		
		return $is_blocked;
	}
	
	
	
	/*
	 * Load stats cache
	 *
	 * @access public
	 * @return array
	*/
	public function load_stats_cache($stats = array())
	{
		global $pro_ads_multisite;
		
		return !empty($stats) ? $stats : $pro_ads_multisite->wpproads_get_option('wpproads_stats_cache', array());
	}
	
	
	
	
	public function default_select()
	{
		$select = current_user_can(WP_ADS_ROLE_ADMIN) ? array() : array('advertiser_id' => get_current_user_id());
		return $select;
	}	
	
	
	
	
	
	/*
	 * Serialize array
	 *
	 * @access public
	 * @return bool
	*/
	public function serialize_array($args = array())
	{	
		return serialize($args);
	}
	
	/*
	 * UN Serialize array
	 *
	 * @access public
	 * @return bool
	*/
	public function unserialize_array($args = array())
	{	
		return unserialize($args);
	}
	
	
	
	/*
	 * QUERIES
	 *
	 * @access public
	 * @return string
	*/
	public function where_qry($where = '')
	{
		return !empty($where) ? ' WHERE '.$where : '';
	}
	public function limit_qry($limit = '')
	{
		return !empty($limit) ? ' LIMIT '.$limit : '';
	}
	public function order_qry($order = '')
	{
		return !empty($order) ? ' ORDER BY '.$order : '';
	}
	
	
	
	
	
	/**
	 * Get stats CTR
	 *
	 * @access public
	 * @return array
	*/
	public function get_ctr($args = array())
	{
		$ctr = !empty($args['clicks']) && !empty($args['impressions']) ? $args['clicks'] / $args['impressions'] * 100 : 0;
		return round($ctr,2).'%';	
	}
	
	
	
	
	
	/**
	 * Check array match
	 *
	 * @access public
	 * @return bool
	*/
	public function check_array_match($args, $st, $to_check = array('banner_id'))
	{
		$match = 0;
		
		foreach( $to_check as $key )
		{
			if( $args[$key] == $st[$key])
			{
				$match = 1;
			}
			else
			{
				return 0;
			}
		}
		
		return $match;
	}
	
	
	
	
	/**
	 * Select array to get specific data from the stats array.
	 *
	 * @access public
	 * @return array
	*/
	public function select_array($args = array() )
	{
		$groups = explode('-', $args['group']);
		$group_ids = explode('-', $args['group_id']);
		$data = array();
		
		foreach($groups as $i => $group)
		{
			$data[$group] = $group_ids[$i];	
		}
		
		return $data;
	}
	
	
	
	
	
	/*
	 * Load Stats
	 *
	 * @access public
	 * @return array
	*/
	public function load_stats($args = array())
	{
		global $wpdb, $pro_ads_multisite, $pro_ads_main;
		
		$defaults = array(
			'type'                 => 'impressions',
			'banner_id'            => 0,
			'adzone_id'            => 0,
			'select'               => '*',
			'where'                => '',
			'limit'                => '',
			'order'                => ''
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$res = $wpdb->get_results("SELECT ".$args['select']." FROM " . $pro_ads_multisite->wpproads_db_prefix() . "wppas_stats".
			$this->where_qry($args['where']).
			$this->order_qry($args['order']).
			$this->limit_qry($args['limit'])
			
		);
		
		return $res;
	}
	
	
	
	
	
	/*
	 * Save Stats
	 *
	 * @access public
	 * @return null
	*/
	public function save_stats( $args = array() )
	{
		global $wpdb, $pro_ads_multisite, $pro_ads_main, $pro_ads_banners;
		
		$defaults = array(
			'type'                 => 'impressions',
			'banner_id'            => 0,
			'adzone_id'            => 0
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// Check if satistics are enabled
		$wpproads_enable_stats = $pro_ads_multisite->wpproads_get_option('wpproads_enable_stats', 0);
		// Google Analytics
		$wpproads_google_analytics_id = get_option('wpproads_google_analytics_id', '');
		
		$advertiser_id = get_post_meta( $args['banner_id'], '_banner_advertiser_id', true );
		$campaign_id   = get_post_meta( $args['banner_id'], '_banner_campaign_id', true );
		
		$stats_data = array(
			'type'                => $args['type'],
			'banner_id'           => $args['banner_id'], 
			'adzone_id'           => $args['adzone_id'], 
			'advertiser_id'       => $advertiser_id,
			'campaign_id'         => $campaign_id,
			'google_analytics_id' => $wpproads_google_analytics_id
		);
		
		// Check if stats need to be saved for this IP
		if( !$this->ip_blocked() && $wpproads_enable_stats )
		{
			// Load user data.	
			$wpproads_enable_userdata_stats = $pro_ads_multisite->wpproads_get_option('wpproads_enable_userdata_stats', 0);
			$stats_data['userdata'] = $wpproads_enable_userdata_stats ? $pro_ads_main->get_user_data() : array();
			
			// INSERT STATISTICS
			$this->update_stats_db($stats_data, $args);
			
			
			// UPDATE DATA FOR BANNER CONTRACTS
			if($args['type'] == 'clicks')
			{
				// Update banner clicks
				$banner_clicks = get_post_meta( $stats_data['banner_id'], '_banner_clicks', true );
				$banner_clicks = $banner_clicks+1;
				update_post_meta( $stats_data['banner_id'], '_banner_clicks', $banner_clicks );
				
				// Update banner status
				$pro_ads_banners->update_banner_status( $stats_data['banner_id'] );
			}
			else
			{
				// Update banner impressions
				$banner_impressions = get_post_meta( $stats_data['banner_id'], '_banner_impressions', true );
				$banner_impressions = $banner_impressions+1;
				update_post_meta( $stats_data['banner_id'], '_banner_impressions', $banner_impressions );
				// Update banner in adzone impressions
				if( !empty($stats_data['adzone_id']) )
				{
					$banner_adzone_impressions = get_post_meta( $stats_data['banner_id'], '_adzone_'.$stats_data['adzone_id'].'_impressions', true );
					$banner_adzone_impressions = $banner_adzone_impressions+1;
					update_post_meta( $stats_data['banner_id'], '_adzone_'.$stats_data['adzone_id'].'_impressions', $banner_adzone_impressions );
				}
				// check banner contract
				$banner_contract = get_post_meta( $stats_data['banner_id'], '_banner_contract', true );
				if( $banner_contract == 2 )
				{
					// Update banner status
					$pro_ads_banners->update_banner_status( $stats_data['banner_id'] );
				}
			}
				
		}
		
		
		
		// GA
		if( !empty($wpproads_google_analytics_id) )
		{
			$this->save_ga_action( $stats_data );
		}
		
		// Filter -------------------------------------------------------
		apply_filters('wp_pro_ads_save_stats', $stats_data);
	}
	
	
	
	
	/*
	 * Update/Insert stats table data
	 *
	 * @access public
	 * @return null
	*/
	public function update_stats_db($stats_data = array(), $args = array())
	{
		global $wpdb, $pro_ads_multisite;
		
		$defaults = array(
			'type' => 'impressions',
			'time' => $this->today,
			'hits' => 1
		);
		$args = wp_parse_args( $args, $defaults );
		
		
		// Check if daily stats DB table already exists.
		$res = $wpdb->get_results("SELECT id, data, ".$args['type']." FROM " . $pro_ads_multisite->wpproads_db_prefix() . "wppas_stats USE INDEX(time)
			WHERE 
				time = ".$args['time']." 
			LIMIT 1"
		);
		
		
		if( !$res )
		{	
			// Stats DB table does not yet exists.
			$stats_array = $this->update_stats_array($stats_data);
			
			$wpdb->query("INSERT INTO " . $pro_ads_multisite->wpproads_db_prefix() . "wppas_stats 
				SET 
					time                  = '".$args['time']."',
					".$args['type']."     = ".$args['hits'].",
					data                  = '".$this->serialize_array($stats_array)."'												 
			");
		}
		else
		{
			// Stats DB table already exists.
			$stats_array = $this->update_stats_array(wp_parse_args(array('stats' => $this->unserialize_array($res[0]->data)), $stats_data));
			$hits = $res[0]->$args['type'] + $args['hits'];
			
			$wpdb->query("UPDATE " . $pro_ads_multisite->wpproads_db_prefix() . "wppas_stats 
				SET 
					".$args['type']." = ".$hits.", 
					data = '".$this->serialize_array($stats_array)."' 
				WHERE 
					id = ".$res[0]->id." 
			");
		}	
	}
	
	
	
	
	
	
	/**
	 * update the statistics array
	 * $this->update_stats_array(array('type' => 'click', 'banner_id' => 3));
	 *
	 * @access public
	 * @return array
	*/
	public function update_stats_array($args = array())
	{
		global $pro_ads_multisite, $pro_ads_main;
		
		$defaults = array(
			'stats'                => array(),
			'type'                 => 'impressions',
			'ip_address'           => $this->IP,
			'banner_id'            => 0,
			'adzone_id'            => 0,
			'advertiser_id'        => 0,
			'campaign_id'          => 0,
			'hour'                 => $this->hour,
			'time'                 => $this->current_time,
			'userdata'             => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		
		// Check if daily stats array exists
		if( !empty($args['stats'][$args['hour']]))
		{
			$match_bid = 0;
			foreach($args['stats'][$args['hour']] as $key => $st)
			{
				// Check if banner_id already exists in the daily array.
				if( $this->check_array_match($args, $st, array('banner_id')))
				{
					// user already visited today.
					$match_ip = 0;
					foreach( $st[$args['type']] as $hts => $hits_type)
					{
						if( $this->check_array_match($args, $hits_type, array('ip_address')))
						{
							$match_ip = 1;
							array_push(
								$args['stats'][$args['hour']][$key][$args['type']][$hts]['hits'], 
								array('time' => $args['time'])
							);
							break;
						}
					}
					$match_bid = 1;
					
					// New unique daily user.
					// If its a new user we add a new array in the impressions or clicks array. all user data gets stored in this array.
					if( !$match_ip )
					{
						array_push(
							$args['stats'][$args['hour']][$key][$args['type']], 
							array(
								'ip_address' => $args['ip_address'],
								'userdata' => $args['userdata'], 
								'hits' => array(array('time' => $args['time']))
							)
						);
					}	
				}
			}
			
			// No banner_id matches found in the daily array.
			if( !$match_bid )
			{
				array_push(
					$args['stats'][$args['hour']], 
					$this->add_new_array_item($args)
				);
			}	
		}
		else
		{
			// daily array does not yet exist.
			$args['stats'][$args['hour']] = array($this->add_new_array_item($args));
		}
		
		return $args['stats'];
	}
	
	
	
	
	
	
	
	
	/**
	 * ADD New array item
	 *
	 * @access public
	 * @return array
	*/
	public function add_new_array_item($args = array())
	{	
		global $pro_ads_main;
		
		$defaults = array(
			'hits' => 1
		);
		$args = wp_parse_args( $args, $defaults );
		
		$impressions = array();
		$clicks = array();
		
		$hits = array();
		//echo $args['hits'].'<br>';
		for($h = 0; $h < $args['hits']; $h++)
		{
			array_push($hits, array('time' => $args['time']));
		}
		//echo '<pre>'.print_r($hits,true).'</pre><br><br>';
		
		if( $args['type'] == 'impressions' )
		{
			array_push($impressions, array('ip_address' => $args['ip_address'], 'userdata' => $args['userdata'], 'hits' => $hits));
		}
		else
		{
			array_push($clicks, array('ip_address' => $args['ip_address'], 'userdata' => $args['userdata'], 'hits' => $hits));
		}
		
		$item = array(
			'banner_id'      => $args['banner_id'],
			'adzone_id'      => $args['adzone_id'],
			'campaign_id'    => $args['campaign_id'],
			'advertiser_id'  => $args['advertiser_id'],
			'impressions'    => $impressions,
			'clicks'         => $clicks
		);
		
		return $item;
	}
	
	
	
	
	
	
	/**
	 * Create stats array from database values
	 *
	 * @access public
	 * @return array
	*/
	public function create_stats_array($args = array() )
	{
		global $wpdb;
		
		$defaults = array(
			'start'  => '',
			'end'    => '',
			'stats'  => array(),
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		if( empty($args['stats']) )
		{
			$limit = $args['start'] == $args['end'] ? 1 : '';
			
			$stats = $this->load_stats(array(
				'select' => 'time,data',
				'where'  => 'time >= '.$args['start'].' AND time <= '.$args['end'],
				'limit'  => $limit
			));
			
			$stats_arr = array();
			
			foreach($stats as $stat)
			{
				$data = $this->unserialize_array($stat->data);
				$stats_arr[date_i18n('Y', $stat->time)][date_i18n('n', $stat->time)][date_i18n('j', $stat->time)] = $data;
			}
			
			//echo '<pre>'.print_r($stats_arr, true).'</pre>';
			
			return $stats_arr;
		}
		else
		{
			return $args['stats'];
		}
	}
	
	
	
	
	
	/**
	 * Check matching values
	 *
	 * @access public
	 * @return int
	*/
	public function check_maching_values($args = array())
	{
		$select = $this->decode_array( $args['select'] );
		
		if( !empty($select))
		{
			foreach($select as $sk => $sel )
			{
				$match = $select[$sk] == $args['all_stats'][$sk] ? 1 : 0;
				//echo $match;
				if( !$match ){ break; }
			}
			
			return $match;
		}
	}
	
	
	
	
	
	/**
	 * Stats counter
	 *
	 * @access public
	 * @return int
	*/
	public function stats_counter($args = array())
	{
		$defaults = array(
			'stats'        => array(),
			'cnt'          => 0,
			'type'         => 'impressions',
			'unique'       => 0
		);
		
		$args = wp_parse_args( $args, $defaults );
		$cnt = $args['cnt'];
		
		if( !$args['unique'] )
		{
			// ex.: $args['stats']['impressions']
			foreach( $args['stats'][$args['type']] as $val )
			{
				$cnt = $cnt + count($val['hits']);
			}
		}
		else
		{
			$cnt = $cnt + count($args['stats'][$args['type']]);
		}
		
		return $cnt;
	}
	
	
	
	
	
	/**
	 * Get hourly stats
	 *
	 * @access public
	 * @return int
	*/
	public function get_hourly_stats($args = array())
	{
		global $pro_ads_multisite;
		
		$defaults = array(
			'stats'                => array(),
			'start'                => $this->today,
			'end'                  => $this->today,
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'hour'                 => $this->hour,
			'type'                 => 'impressions',
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// load stats array
		$stats = $this->create_stats_array(array('stats' => $args['stats'], 'start' => $args['start'], 'end' => $args['end']));
		$select = $this->decode_array( $args['select'] );
		
		$cnt = 0;
		if( !empty($stats) && !empty($stats[$args['year']][$args['month']][$args['day']][$args['hour']]))
		{
			foreach($stats[$args['year']][$args['month']][$args['day']][$args['hour']] as $today_stats)
			{
				$match = !empty($select) ? $this->check_maching_values(array('select' => $select, 'all_stats' => $today_stats)) : 1;
				if( $match )
				{
					$cnt = $this->stats_counter(array('stats' => $today_stats, 'cnt' => $cnt, 'unique' => $args['unique'], 'type' => $args['type']));
				}
			}
		}
			
		return $cnt;
	}
	
	
	
	
	
	
	
	/**
	 * Get daily stats
	 *
	 * @access public
	 * @return int
	*/
	public function get_daily_stats($args = array())
	{
		global $pro_ads_multisite;
		
		$defaults = array(
			'stats'                => array(),
			'start'                => $this->today,
			'end'                  => $this->today,
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'type'                 => 'impressions',
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// load stats array
		$stats = $this->create_stats_array(array('stats' => $args['stats'], 'start' => $args['start'], 'end' => $args['end']));
		
		$cnt = 0;	
		for( $i = 0; $i <= 23; $i++ )
		{
			if( !empty($stats[$args['year']][$args['month']][$args['day']][$i]))
			{
				$cnt += $this->get_hourly_stats(
					array(
						'stats'   => $stats,
						'start'   => $args['start'],
						'end'     => $args['end'],
						'year'    => $args['year'],
						'month'   => $args['month'],
						'day'     => $args['day'],
						'hour'    => $i,
						'type'    => $args['type'],
						'unique'  => $args['unique'],
						'select'  => $args['select']
					)
				);
			}
		}
			
		return $cnt;
	}
	
	
	
	
	
	/**
	 * Get monthly stats
	 *
	 * @access public
	 * @return int
	*/
	public function get_monthly_stats($args = array())
	{
		global $pro_ads_multisite;
		
		$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
		
		$defaults = array(
			'stats'                => array(),
			'start'                => mktime(0,0,0, $this->month, 1, $this->year),
			'end'                  => mktime(0,0,0, $this->month, $am_days, $this->year),
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'type'                 => 'impressions',
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// load stats array
		$stats = $this->create_stats_array(array('stats' => $args['stats'], 'start' => $args['start'], 'end' => $args['end']));
		
		$cnt = 0;	
		for( $i = 1; $i <= $am_days; $i++ )
		{
			if( !empty($stats[$args['year']][$args['month']][$i]))
			{
				$cnt += $this->get_daily_stats(
					array(
						'stats'   => $stats,
						'start'   => $args['start'],
						'end'     => $args['end'],
						'year'    => $args['year'],
						'month'   => $args['month'],
						'day'     => $i,
						'type'    => $args['type'],
						'unique'  => $args['unique'],
						'select'  => $args['select']
					)
				);
			}
		}
			
		return $cnt;
	}
	
	
	
	
	
	
	
	/**
	 * Get yearly stats
	 *
	 * @access public
	 * @return int
	*/
	public function get_yearly_stats($args = array())
	{
		global $pro_ads_multisite;
		
		$defaults = array(
			'stats'                => array(),
			'start'                => mktime(0,0,0, 1, 1, $this->year),
			'end'                  => mktime(23,59,59, 12, 31, $this->year),
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'type'                 => 'impressions',
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// load stats array
		$stats = $this->create_stats_array(array('stats' => $args['stats'], 'start' => $args['start'], 'end' => $args['end']));
		//print_r($stats);
		$cnt = 0;
		for( $i = 1; $i <= 12; $i++ )
		{
			if( !empty($stats[$args['year']][$i]))
			{
				$cnt += $this->get_monthly_stats(
					array(
						'stats'   => $stats,
						'year'    => $args['year'],
						'month'   => $i,
						'day'     => $args['day'],
						'start'   => $args['start'],
						'end'     => $args['end'],
						'type'    => $args['type'],
						'unique'  => $args['unique'],
						'select'  => $args['select']
					)
				);
			}
		}
			
		return $cnt;
	}
	
	
	
	
	
	
	
	/**
	 * Get all time stats
	 *
	 * @access public
	 * @return int
	*/
	public function get_alltime_stats($args = array())
	{
		global $pro_ads_multisite;
		
		$defaults = array(
			'stats'                => array(),
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'type'                 => 'impressions',
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$years = $this->get_stat_years();
		
		if( empty($args['stats']))
		{
			$l = count($years)-1;
			
			$start = mktime(0,0,0, 1, 1, $years[0]);
			$end = mktime(0,0,0, 12, 31, $years[$l]);
			
			// load stats array
			$stats = $this->create_stats_array(array('stats' => $args['stats'], 'start' => $start, 'end' => $end));
		}
		else
		{
			$stats = $args['stats'];	
		}
		
		
		//print_r($stats);
		$cnt = 0;
		foreach( $years as $year )
		{
			$cnt += $this->get_yearly_stats(
				array(
					'stats'   => $stats,
					'year'    => $year,
					'start'   => mktime(0,0,0, 1, 1, $year),
					'end'     => mktime(23,59,59, 12, 31, $year),
					'month'   => $args['month'],
					'day'     => $args['day'],
					'type'    => $args['type'],
					'unique'  => $args['unique'],
					'select'  => $args['select']
				)
			);
		}
			
		return $cnt;
	}
	
	
	
	
	
	
	
	/**
	 * Daily Device Stats
	 *
	 * @access public
	 * @return arr
	*/
	public function count_daily_device_stats($args = array())
	{
		$defaults = array(
			'stats'                => array(),
			's_type'               => 'day',
			'type'                 => 'impressions',     
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'unique'               => 0,
			'select'               => array(),
			'device_count'         => array('desktop' => 0, 'tablet'  => 0, 'mobile'  => 0, 'unknown' => 0)
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		//$stats = $this->load_stats_array($args['stats']);
		$stats = $args['stats'];
		$select = $this->decode_array( $args['select'] );
		
		if( !empty($stats[$args['year']][$args['month']][$args['day']]))
		{
			foreach( $stats[$args['year']][$args['month']][$args['day']] as $all_statss )
			{
				foreach($all_statss as $all_stats)
				{
					$match = !empty($select) ? $this->check_maching_values(array('select' => $select, 'all_stats' => $all_stats)) : 1;
					if( $match )
					{
						foreach($all_stats[$args['type']] as $val )
						{
							if( !empty($val['userdata']['device']))
							{
								$args['device_count'][$val['userdata']['device']]++;
							}
							else
							{
								$args['device_count']['unknown']++;
							}
						}
					}
				}
			}
		}
		
		return $args['device_count'];
	}
	
	
	
	
	
	/**
	 * Monthly Device Stats
	 *
	 * @access public
	 * @return arr
	*/
	public function count_monthly_device_stats($args = array())
	{
		$defaults = array(
			'stats'                => array(),
			's_type'               => 'day',
			'type'                 => 'impressions',     
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'unique'               => 0,
			'select'               => array(),
			'device_count'         => array('desktop' => 0, 'tablet'  => 0, 'mobile'  => 0, 'unknown' => 0)
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		//$stats = $this->load_stats_array($args['stats']);
		$stats = $args['stats'];
		$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
		
		for( $i = 1; $i <= $am_days; $i++ )
		{
			$args['device_count'] = $this->count_daily_device_stats(wp_parse_args(array('day' => $i), $args));
		}
		
		return $args['device_count'];
	}
	
	
	
	/**
	 * Yearly Device Stats
	 *
	 * @access public
	 * @return arr
	*/
	public function count_yearly_device_stats($args = array())
	{
		$defaults = array(
			'stats'                => array(),
			's_type'               => 'day',
			'type'                 => 'impressions',     
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'unique'               => 0,
			'select'               => array(),
			'device_count'         => array('desktop' => 0, 'tablet'  => 0, 'mobile'  => 0, 'unknown' => 0)
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		//$stats = $this->load_stats_array($args['stats']);
		$stats = $args['stats'];
		
		for( $i = 1; $i <= 12; $i++ )
		{
			$args['device_count'] = $this->count_monthly_device_stats(wp_parse_args(array('month' => $i), $args));
		}
		
		return $args['device_count'];
	}
	
	
	
	
	/**
	 * All Time Device Stats
	 *
	 * @access public
	 * @return arr
	*/
	public function count_all_device_stats($args = array())
	{
		$defaults = array(
			'stats'                => array(),
			's_type'               => 'day',
			'type'                 => 'impressions',     
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'unique'               => 0,
			'select'               => array(),
			'device_count'         => array('desktop' => 0, 'tablet'  => 0, 'mobile'  => 0, 'unknown' => 0)
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		//$stats = $this->load_stats_array($args['stats']);
		$stats = $args['stats'];
		$years_all = $this->get_stat_years();
		
		foreach($years_all as $year)
		{
			$args['device_count'] = $this->count_yearly_device_stats(wp_parse_args(array('year' => $year), $args));
		}
		
		return $args['device_count'];
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * Graph data values
	*/
	public function graph_data($args = array())
	{
		$defaults = array(
			'stats'                => array(),
			's_type'               => 'day',
			'type'                 => 'impressions',     
			'year'                 => $this->year,
			'month'                => $this->month,
			'day'                  => $this->day,
			'unique'               => 0,
			'select'               => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$html = '';
		$select = $this->decode_array( $args['select'] );
		
		// Daily stats data
		if( $args['s_type'] == 'day' )
		{
			for( $i = 1; $i <= 24; $i++ )
			{
				$cnt = 0;
				if( !empty($args['stats'][$args['year']][$args['month']][$args['day']][$i]))
				{
					foreach($args['stats'][$args['year']][$args['month']][$args['day']][$i] as $today)
					{
						if( !empty($select))
						{
							$match = $this->check_maching_values(array('select' => $select, 'all_stats' => $today));
							if( $match )
							{
								// Impressions | clicks
								foreach($today[$args['type']] as $val)
								{
									// All data - not unique.
									if( !$args['unique'] )
									{
										foreach($val['hits'] as $hits)
										{
											if(date('G', $hits['time']) == $i)
											{
												$cnt++;
											}
										}
									}
									else
									{
										// Unique data.
										if(date('G', $val['hits'][0]['time']) == $i)
										{
											$cnt++;
										}
									}
								}
							}
						}
						else
						{
							// Impressions | clicks
							foreach($today[$args['type']] as $val)
							{
								// All data - not unique.
								if( !$args['unique'] )
								{
									foreach($val['hits'] as $hits)
									{
										if(date('G', $hits['time']) == $i)
										{
											$cnt++;
										}
									}
								}
								else
								{
									// Unique data.
									if(date('G', $val['hits'][0]['time']) == $i)
									{
										$cnt++;
									}
								}
							}
						}
					}
				}
				$html.= '["'.$i.'", '.$cnt.'],';
			}	
		}
		elseif( $args['s_type'] == 'month' )
		{
			$am_days = cal_days_in_month(CAL_GREGORIAN, $args['month'], $args['year']);
			
			for( $i = 1; $i <= $am_days; $i++ )
			{
				$cnt = $this->get_daily_stats(
					array(
						'stats'  => $args['stats'],
						'year'   => $args['year'],
						'month'  => $args['month'],
						'day'    => $i,
						'type'   => $args['type'],
						'unique' => $args['unique'],
						'select' => $args['select']
					)
				);
				$html.= '["'.$i.'", '.$cnt.'],';
			}
		}
		elseif( $args['s_type'] == 'year' )
		{
			for( $i = 1; $i <= 12; $i++ )
			{
				$cnt = $this->get_monthly_stats(
					array(
						'stats'   => $args['stats'],
						'year' => $args['year'],
						'month' => $i,
						'type' => $args['type'],
						'unique' => $args['unique'],
						'select' => $args['select']
					)
				);
				$html.= '["'.$i.'", '.$cnt.'],';
			}
		}
		elseif( $args['s_type'] == 'all' )
		{	
			// All Time Stats
			$years_arr = $this->get_stat_years();
			
			$html.= '["'.$years_arr[0].'", 0],';
			
			
			if( !empty($years_arr) )
			{
				foreach($years_arr as $year)
				{
					$cnt = $this->get_yearly_stats(wp_parse_args( array('year' => $year), $args));
					$html.= '["'.$year.'", '.$cnt.'],';
				}
			}
			else
			{
				$html.= '["'.date('Y').'", 0],';
			}
		}
		
		
		return $html;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * Encode array
	 *
	 * @access public
	 * @return array
	*/
	public function encode_array( $array )
	{
		if( is_array($array) )
		{
			$string = base64_encode(json_encode($array));
			return $string;
		}
		else
		{
			return $array;	
		}
	}
	
	
	
	
	/**
	 * Check if array needs to be decoded
	 *
	 * @access public
	 * @return array
	*/
	public function decode_array( $string )
	{
		if( !is_array($string) )
		{
			$string = $this->validBase64($string) ? base64_decode($string) : $string;
			return json_decode($string, true);
		}
		else
		{
			return $string;	
		}
	}
	
	
	
	/**
	 * Check if string is base64 encoded. 
	 *
	 * @access public
	 * @return array
	*/
	public function validBase64($string)
	{
        $decoded = base64_decode($string, true);
        // Check if there is no invalid character in strin
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

        // Decode the string in strict mode and send the responce
         if(!base64_decode($string, true)) return false;

        // Encode and compare it to origional one
        if(base64_encode($decoded) != $string) return false;

        return true;
    }
	
	
	
	
	
	
	
	
	/**
	 * Get array keys for all years from the stats array.
	*/
	public function get_stat_years()
	{
		$years = array();
		
		$first_year = $this->load_stats(array('select' => 'time', 'order' => 'id ASC', 'limit' => 1));
		
		$first_year = !empty($first_year) ? date_i18n('Y', $first_year[0]->time) : date_i18n('Y')-1;
		$last_year = date_i18n('Y');
		
		// Years array
		$years[0] = $first_year == $last_year ? $first_year - 1 : $first_year; // first year
		$years[1] = $last_year; // current year
		
		$dif = $years[1] - $years[0];
		
		if( $dif > 1 )
		{
			$y = 0;
			for($i = 1; $i < $dif; $i++)
			{
				$y = $y == 0 ? $years[0] + 1 : $y + 1;
				array_push($years, $y);
			}
		}
		
		// Sort years
		sort( $years );
		array_values($years);
		
		return $years;
	}
	
	
	
	
	
	
	
	
	/*
	 * PDF Invoice template
	*/ 
	function wpproads_pdf_template( $pdf_template_path )
	{	
		if(isset($_GET['stats_pdf']) && !empty($_GET['stats_pdf'])) 
		{
			$data = unserialize(htmlspecialchars_decode(base64_decode($_GET['data'])));
			
			$this->wpproads_save_stats_pdf($data);
		}
		
		return $pdf_template_path;
	}
	
	
	
	/*
	 * Save PDF
	 *
	 * This function creates the PDF invoice
	 * there are 2 options
	 * - view (will show the invoice in the browser)
	 * - save (will save the invoice to your computer)
	 *
	 * @access public
	 * @return html
	*/
	public function wpproads_save_stats_pdf( $args = array() )
	{
		if(isset($_GET['stats_pdf']) && !empty($_GET['stats_pdf'])) 
		{	
			require_once(WP_ADS_INC_DIR.'/tcpdf/tcpdf_include.php');
			
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->AddPage();

			// set margins
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			if ( $html = locate_template( array( 'wpproads/stats-pdf.php' ) ) ) 
			{
				include( $html );
			}
			else
			{
				include( WP_ADS_TPL_DIR.'/pdf/stats-pdf.php');
			}
			
			// Print text using writeHTMLCell()
			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			
			// ---------------------------------------------------------
			
			// Close and output PDF document
			// This method has several options, check the source code documentation for more information.
			// https://tcpdf.org/docs/source_docs/classTCPDF/#a3d6dcb62298ec9d42e9125ee2f5b23a1
			$pdf->Output('stats.pdf', 'I');
			
			exit();
		}
	}
	
	
	
	
	
	/*
	 * Save action with Google Analitics
	 *
	 * https://support.google.com/analytics/answer/1033068?hl=en 
	 *
	 * @access public
	 * @return html
	*/
	public function save_ga_action( $args )
	{
		if( !empty(	$args['google_analytics_id'] ))
		{
			$loops = array('banner', 'adzone', 'advertiser');
			
			//set POST variables
			$url = 'http://www.google-analytics.com/collect';
			
			foreach( $loops as $loop )
			{
				$data = get_post($args[$loop.'_id'], ARRAY_A);
				
				if($args['type'] != 'clicks')
				{	
					// http://tunasite.com/helpdesk/ftopic/google-analytics-integration-is-unusable/
					echo "<script>if(typeof wppas_ga != 'undefined'){wppas_ga('send', 'event', 'wpproads ".$loop." statistics', '".$args['type']."', '".$loop.': '.$data['post_name'].'-'.$args[$loop.'_id']."',{nonInteraction: true});}</script>";
					
				}
				else
				{
					// https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters
					$fields_string = '';
					$fields = array(
						'v'   => 1,
						'tid' => $args['google_analytics_id'],
						'cid' => 5555,
						't'   => 'event',
						'ec'  => 'wpproads '.$loop.' statistics',
						'ea'  => $args['type'],
						'el'  => $loop.': '.$data['post_name'].'-'.$args[$loop.'_id'],
						'ni'  => 1
					);
					
					//url-ify the data for the POST
					foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
					rtrim($fields_string, '&');
					
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL, $url); //curl the url
					curl_setopt($ch,CURLOPT_POST, count($fields));
					curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE); //return the transfer to be later saved to a variable
					$result = curl_exec($ch);
					
					//close connection
					curl_close($ch);
				}
			}
		}
	}
	
}
?>