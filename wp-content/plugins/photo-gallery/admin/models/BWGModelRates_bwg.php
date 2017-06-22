<?php

class BWGModelRates_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $per_page = 20;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
    $user = get_current_user_id();
    $screen = get_current_screen();
    $option = $screen->get_option('per_page', 'option');
    
    
    $this->per_page = get_user_meta($user, $option, true);
    
    if ( empty ( $this->per_page) || $this->per_page < 1 ) {
      $this->per_page = $screen->get_option( 'per_page', 'default' );
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function get_rows_data() {
    global $wpdb;
    $where = 'WHERE t1.ip LIKE ' . ((isset($_POST['search_value'])) ? '"%' . esc_html($_POST['search_value']) . '%"' : '"%%"');
    $where .= ((isset($_POST['search_select_gal_value']) && ((int) $_POST['search_select_gal_value'])) ? ' AND t2.gallery_id=' . (int) $_POST['search_select_gal_value'] : '');
    $where .= ((isset($_POST['search_select_value']) && ((int) $_POST['search_select_value'])) ? ' AND t1.image_id=' . (int) $_POST['search_select_value'] : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc']) && esc_html($_POST['asc_or_desc']) == 'desc') ? 'desc' : 'asc');
    $order_by_arr = array('id', 'image_id', 'rate', 'ip', 'date');
    $order_by = ((isset($_POST['order_by']) && in_array(esc_html($_POST['order_by']), $order_by_arr)) ? esc_html($_POST['order_by']) : 'rate');
    $order_by = ' ORDER BY t1.' . $order_by . ' ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
    }
    else {
      $limit = 0;
    }
    $rows = $wpdb->get_results("SELECT t1.*, t2.thumb_url, t2.alt, t2.filetype  FROM " . $wpdb->prefix . "bwg_image_rate as t1 INNER JOIN " . $wpdb->prefix . "bwg_image as t2 on t1.image_id=t2.id " . $where . $order_by . " LIMIT " . $limit . ",".$this->per_page);
    return $rows;
  }

  public function get_galleries() {
    global $wpdb;
    $rows_object = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "bwg_gallery WHERE published=1");
    $rows[0] = __('All galleries', 'bwg_back');
    if ($rows_object) {
      foreach ($rows_object as $row_object) {
        $rows[$row_object->id] = $row_object->name;
      }
    }
    return $rows;
  }

  public function get_images($gal_id) {
    global $wpdb;
    $where = ($gal_id ? ' AND gallery_id=' . $gal_id : '');
    $rows_object = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "bwg_image WHERE published=1" . $where);
    $rows[0] = __('All images', 'bwg_back');
    if ($rows_object) {
      foreach ($rows_object as $row_object) {
        $rows[$row_object->id] = $row_object->alt;
      }
    }
    return $rows;
  }

  public function page_nav() {
    global $wpdb;
    $where = 'WHERE t1.ip LIKE ' . ((isset($_POST['search_value'])) ? '"%' . esc_html($_POST['search_value']) . '%"' : '"%%"');
    $where .= (isset($_POST['search_select_gal_value']) ? ' AND t2.gallery_id=' . (int) $_POST['search_select_gal_value'] : '');
    $where .= ((isset($_POST['search_select_value']) && ((int) $_POST['search_select_value'])) ? ' AND t1.image_id=' . (int) $_POST['search_select_value'] : '');
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "bwg_image_rate as t1 INNER JOIN " . $wpdb->prefix . "bwg_image as t2 on t1.image_id=t2.id " . $where;
    $total = $wpdb->get_var($query);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / $this->per_page + 1);
    return $page_nav;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function per_page(){
    return $this->per_page;

  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}