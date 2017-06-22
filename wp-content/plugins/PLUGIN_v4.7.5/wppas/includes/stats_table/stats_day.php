<?php
class Pro_Ad_Stats_Day_List_Table extends WP_List_Table {
   
    
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
		
		add_action('admin_footer', array($this,'ajax_script'));
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => __('stats_day', 'wpproads'),     //singular name of the listed records
            'plural'    => __('stats_day', 'wpproads'),    //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
        ) );
        
    }
    
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name)
	{	
		global $pro_ads_templates, $pro_ads_multisite;
		
		$wpproads_stats_data = $pro_ads_multisite->wpproads_get_option('wpproads_stats_data', 'hour');
		
        switch($column_name){
			/*case 'hits':
				return count($item['hits']);*/
            case 'banner':
				return '<a href="post.php?post='.$item['banner_id'].'&action=edit">'.get_the_title($item['banner_id']).'</a>';
			case 'browser':
				return !empty($item['userdata']['browser']) ? '<img src="'.WP_ADS_URL.'images/browser/'.$item['userdata']['browser'].'.png" alt="'.$item['userdata']['browser'].'" />' : __('n/a','wpproads');
			case 'platform':
				return !empty($item['userdata']['platform']) ? '<img src="'.WP_ADS_URL.'images/platform/'.$item['userdata']['platform'].'.png" alt="'.$item['userdata']['platform'].'" />' : __('n/a','wpproads');
			case 'user_info':
				$html = '';
				$html.= $item['ip_address'];
				/*$html.= $pro_ads_templates->stats_user_info_popup_screen( $item );
				$html.= '<a href="#TB_inline?width=600&inlineId=stats_user_info_popup_'.$item->id.'" class="thickbox" title="'.__('Detailed User Info', 'wpproads').'">';
					$html.= '<img src="'.WP_ADS_URL.'images/popupIcon.gif" /> '.__('User Info', 'wpproads');
                $html.= '</a>';*/
				return $html;
            default:
				return date_i18n('G:i', $item['time']); //date_i18n('l, d - G:i', $item['time']);
                //return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    
        
    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_stats($item){
		
		global $main_template_class;
        
        //Build row actions
		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=%s&evt=%s">'.__('Edit','wpproads').'</a>',$_REQUEST['page'],'edit',$item->id),
			'export'    => sprintf('<a href="?page=%s&action=%s&evt=%s">'.__('Export','wpproads').'</a>',$_REQUEST['page'],'export',$item->id),
			'delete'    => sprintf('<a href="?page=%s&action=%s&event[]=%s">'.__('Delete','wpproads').'</a>',$_REQUEST['page'],'delete',$item->id),
		);
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(ID:%2$s)</span>%3$s',
            /*$1%s*/ $item->name,
            /*$2%s*/ $item->id,
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            //'<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label
            /*$2%s*/ $item->id                //The value of the checkbox should be the record's id
        );
    }
    
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            //'cb'        => '', //'<input type="checkbox" />', //Render a checkbox instead of text
            'time'     => __('Time', 'wpproads'),
			//'hits' => __('Hits', 'wpproads'),
			'banner'  => __('Banner', 'wpproads'),
			'browser'  => __('Browser', 'wpproads'),
			'platform'  => __('Platform', 'wpproads'),
			'user_info'  => __('User Info', 'wpproads')
        );
        return $columns;
    }
    
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            //'date'    => array('date',false),     //true means it's already sorted
            //'count'   => array('count',false)
        );
        return $sortable_columns;
    }
    
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    /*function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
	*/
    
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete' === $this->current_action() ) {
		 	//print_r( $_GET['event'] );
            //wp_die( 'Deleting items is not yet available.');
        }
        
    }
    
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items( $args = array() ) {
        global $wpdb, $pro_ads_main, $pro_ads_statistics, $pro_ads_advertisers, $wppas_stats; 

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
		
		// create where status
		$defaults = array(
			'stats' => array(),
			'type'   => array('slug' => 'clicks', 'name' => __('Clicks', 'wpproads')),
			'range'  => 'month',
			'day'    => $pro_ads_main->time_by_timezone('d'),
			'month'  => $pro_ads_main->time_by_timezone('m'),
			'year'   => $pro_ads_main->time_by_timezone('Y'),
			'unique' => 0,
			'select' => array()
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		if( empty($args['stats']))
		{
			$sdate = mktime(0,0,0, $args['month'], $args['day'], $args['year']);
			$edate = mktime(23,59,59, $args['month'], $args['day'], $args['year']);
			$args['stats'] = $wppas_stats->create_stats_array(array('start' => $sdate, 'end' => $edate));
		}
		
		$data = array();
		$advertiser = $pro_ads_advertisers->get_advertisers( array('meta_key' => '_proad_advertiser_wpuser', 'meta_value' => get_current_user_id()) );
		$select = $wppas_stats->decode_array( $args['select'] );
		$select = current_user_can(WP_ADS_ROLE_ADMIN) ? $select : wp_parse_args(array('advertiser_id' => $advertiser[0]->ID), $select);
		
		
		if( !empty($args['stats'][$args['year']][$args['month']][$args['day']]))
		{
			// Create all stats per user array.
			$n = 0;
			foreach($args['stats'][$args['year']][$args['month']][$args['day']] as $arrs)
			{
				foreach( $arrs as $arr )
				{
					$match = !empty($select) ? $wppas_stats->check_maching_values(array('select' => $select, 'all_stats' => $arr)) : 1;
					
					// Check if matches are needed.
					if( empty($select) || $match )
					{
						$banner_id = $arr['banner_id'];
						
						if( !empty($arr[$args['type']['slug']] ))
						{
							foreach( $arr[$args['type']['slug']] as $val )
							{
								if( !$args['unique'] )
								{
									// All data - not unique.
									foreach($val['hits'] as $itm)
									{
										$data[$n]['time'] = $itm['time'];
										$data[$n]['banner_id'] = $banner_id;
										$data[$n]['ip_address'] = $val['ip_address'];
										$data[$n]['userdata'] = $val['userdata'];
										$data[$n]['hits'] = $itm;
										$n++;
									}
								}
								else
								{
									// unique data.
									$data[$n]['time'] = $val['hits'][0]['time'];
									$data[$n]['banner_id'] = $banner_id;
									$data[$n]['ip_address'] = $val['ip_address'];
									$data[$n]['userdata'] = $val['userdata'];
									$data[$n]['hits'] = $val['hits'][0];
									$n++;
								}
							}	
						}
					}
				}
				
			}
			
			// Sort array high to low.
			arsort($data);
		}
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page),   //WE have to calculate the total number of pages
			
			// Set ordering values if needed (useful for AJAX)
			'orderby'   => ! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'title',
			'order'     => ! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ? $_REQUEST['order'] : 'asc'
        ) );
    }
	
	
	
	
	
	
	
	
	
	
	////////////////////////////////////////
	     ///////    AJAX 
		 ////// http://blog.caercam.org/2014/04/03/a-way-to-implement-ajax-in-wp_list_table/
	////////////////////////////////////////
	
	/**
	 * Display the table
	 * Adds a Nonce field and calls parent's display method
	 *
	 * @since 3.1.0
	 * @access public
	 */
	function display() {
		wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
		echo '<input type="hidden" id="order" name="order" value="' . $this->_pagination_args['order'] . '" />';
		echo '<input type="hidden" id="orderby" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';
		parent::display();
	}
	
	
	/**
	 * Handle an incoming ajax request (called from admin-ajax.php)
	 *
	 * @since 3.1.0
	 * @access public
	 */
	function ajax_response( $args = array() ) 
	{
		global $hook_suffix, $pro_ads_main;
		
		check_ajax_referer( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
		
		$defaults = array(
			'stats' => array(),
			'type'   => array('slug' => 'click', 'name' => __('Clicks', 'wpproads')),
			'range'  => 'month',
			'day'    => $pro_ads_main->time_by_timezone('d'),
			'month'  => $pro_ads_main->time_by_timezone('m'),
			'year'   => $pro_ads_main->time_by_timezone('Y'),
			'unique' => 0,
			'group'    => '',
			'group_id' => ''
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		/*$array = array(
			'type'   => array('slug' => 'impression', 'name' => __('Impressions', 'wpproads')),
			'range'  => 'day',
			'day'    => $pro_ads_main->time_by_timezone('d'),
			'month'  => $pro_ads_main->time_by_timezone('m'),
			'year'   => $pro_ads_main->time_by_timezone('Y')
		);
		$res = array_merge($array, $arr);*/
		
		$this->prepare_items( $args );
		extract( $this->_args );
		extract( $this->_pagination_args, EXTR_SKIP );
		ob_start();
		if ( ! empty( $_REQUEST['no_placeholder'] ) )
			$this->display_rows();
		else
			$this->display_rows_or_placeholder();
		$rows = ob_get_clean();
		ob_start();
		$this->print_column_headers();
		$headers = ob_get_clean();
		ob_start();
		$this->pagination('top');
		$pagination_top = ob_get_clean();
		ob_start();
		$this->pagination('bottom');
		$pagination_bottom = ob_get_clean();
		$response = array( 'rows' => $rows );
		$response['pagination']['top'] = $pagination_top;
		$response['pagination']['bottom'] = $pagination_bottom;
		$response['column_headers'] = $headers;
		if ( isset( $total_items ) )
			$response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );
		if ( isset( $total_pages ) ) {
			$response['total_pages'] = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n( $total_pages );
		}
		die( json_encode( $response ) );
	}




	
	
	function ajax_script() 
	{
		wp_enqueue_script('pro_ad_statistics_WP_List_Table_ajax.js', WP_ADS_TPL_URL . '/js/WP_List_Table_ajax.js');
	}
    
}
/////////////////////////////////////////////
        //////////// EINDE CLASS
/////////////////////////////////////////////		

/**
 * Callback function for 'wp_ajax__ajax_fetch_custom_list' action hook.
 * 
 * Loads the Custom List Table Class and calls ajax_response method
 */

add_action('wp_ajax__ajax_fetch_custom_list_day', '_ajax_fetch_custom_list_day_callback');
function _ajax_fetch_custom_list_day_callback() 
{
	global $pro_ads_statistics, $hook_suffix;
	
	$arr = array(
		'stats'    => array(),
		'type'     => array('slug' => $_POST['type']), 
		'name'     => $pro_ads_statistics->stat_types($_POST['type']),
		'color'    => $pro_ads_statistics->stat_type_color( $_POST['type'] ),
		'rid'      => $_POST['rid'],
		'year'     => $_POST['year'],
		'month'    => !empty($_POST['month']) ? $_POST['month'] : '',
		'day'      => !empty($_POST['day']) ? $_POST['day'] : '',
		'unique'   => $_POST['unique'],
		'group'    => !empty($_POST['group']) ? $_POST['group'] : '',
		'group_id' => !empty($_POST['group_id']) ? $_POST['group_id'] : ''
	);
	
	$wp_list_table = new Pro_Ad_Stats_Day_List_Table();
	$wp_list_table->ajax_response( $arr );
	
	exit();
}
