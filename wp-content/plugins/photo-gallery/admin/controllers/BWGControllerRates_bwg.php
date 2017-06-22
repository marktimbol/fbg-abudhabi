<?php

class BWGControllerRates_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html($_POST['task']) : '');
    $id = ((isset($_POST['current_id'])) ? esc_html($_POST['current_id']) : 0);


    if($task != ''){
      if(!WDWLibrary::verify_nonce('rates_bwg')){
        die(__('Sorry, your nonce did not verify.', 'bwg_back'));
      }
    }

    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWG_DIR . "/admin/models/BWGModelRates_bwg.php";
    $model = new BWGModelRates_bwg();

    require_once WD_BWG_DIR . "/admin/views/BWGViewRates_bwg.php";
    $view = new BWGViewRates_bwg($model);
    $view->display();
  }

  public function delete($id) {
    global $wpdb;
    $image_id = $wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE id="%d"', $id));
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE id="%d"', $id);
    if ($wpdb->query($query)) {
      $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE image_id="%d"', $image_id));
      $wpdb->update($wpdb->prefix . 'bwg_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
      echo WDWLibrary::message(__('Item Succesfully Deleted.', 'bwg_back'), 'wd_updated');
    }
    else {
      echo WDWLibrary::message(__('Error. Please install plugin again.', 'bwg_back'), 'wd_error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwg_image_rate');
    foreach ($ids_col as $id) {
      if (isset($_POST['check_' . $id])) {      
        $flag = TRUE;
        $image_id = $wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE id="%d"', $id));
        $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE id="%d"', $id));
        $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE image_id="%d"', $image_id));
        $wpdb->update($wpdb->prefix . 'bwg_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
      }
    }
    if ($flag) {
      echo WDWLibrary::message(__('Items Succesfully Deleted.', 'bwg_back'), 'wd_updated');
    }
    else {
      echo WDWLibrary::message(__('You must select at least one item.', 'bwg_back'), 'wd_error');
    }
    $this->display();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}