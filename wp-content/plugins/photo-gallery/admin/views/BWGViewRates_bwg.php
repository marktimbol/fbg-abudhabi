<?php

class BWGViewRates_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $WD_BWG_UPLOAD_DIR;
    $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
    $search_value = ((isset($_POST['search_value'])) ? esc_html($_POST['search_value']) : '');
    $search_select_gal_value = ((isset($_POST['search_select_gal_value'])) ? (int) $_POST['search_select_gal_value'] : 0);
    $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'desc');
    $order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) : 'rate');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    $per_page = $this->model->per_page();
	  $pager = 0;
    $rates_button_array = array(
      'delete_all' => __('Delete', 'bwg_back')
    );
    ?>
    <form class="wrap bwg_form" id="rates_form" method="post" action="admin.php?page=rates_bwg" style="width: 98%; float: left;">
      <?php wp_nonce_field( 'rates_bwg', 'bwg_nonce' ); ?>
      <div>
        <span class="rating_icon"></span>
        <h2>
          <?php echo __('Ratings', 'bwg_back'); ?>
        </h2>
      </div>
      <?php WDWLibrary::search('IP', $search_value, 'rates_form',''); ?>
      <div class="tablenav buttons_div ">
      <div style="float:left; margin-right:10px;">
        <span class="wd-btn wd-btn-primary-gray bwg_check_all  non_selectable" onclick="spider_check_all_items()">
          <input type="checkbox" id="check_all_items" name="check_all_items" onclick="spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
          <span style="vertical-align: middle;"><?php echo __('Select All', 'bwg_back'); ?></span>
        </span>
        <select class="select_icon bulk_action" style="width:150px; margin-right:5px;">
          <option value=""><?php _e('Bulk Actions', 'bwg_back'); ?></option>
          <?php 
          foreach ($rates_button_array as $key => $value) {
            ?>
          <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php
          }
          ?>
        </select>
        <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" type="button" title="<?php _e("Apply", "bwg_back"); ?>" onclick="if (!bwg_bulk_actions('.bulk_action', 'rates_page')) {return false;}" value="<?php _e("Apply", "bwg_back"); ?>" />       
        </div>
        <?php
        WDWLibrary::search_select(__('Gallery','bwg_back'), 'search_select_gal_value', $search_select_gal_value, $this->model->get_galleries(), 'rates_form');
        WDWLibrary::search_select(__('Image','bwg_back'), 'search_select_value', $search_select_value, $this->model->get_images($search_select_gal_value), 'rates_form');
        WDWLibrary::html_page_nav($page_nav['total'], $pager++,  $page_nav['limit'], 'rates_form', $per_page);
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" onclick="spider_check_all(this)" type="checkbox" style="margin:0;" /></th>
          <th class="sortable table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'id') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'rates_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="sortable table_big_col <?php if ($order_by == 'image_id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'image_id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'image_id') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'rates_form')" href="">
              <span><?php echo __('Image', 'bwg_back'); ?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="sortable table_big_col <?php if ($order_by == 'rate') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'rate');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'rate') && esc_html($_POST['asc_or_desc']) == 'desc') ? 'asc' : 'desc'); ?>');
                        spider_form_submit(event, 'rates_form')" href="">
              <span><?php echo __('Rating', 'bwg_back'); ?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="sortable <?php if ($order_by == 'ip') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'ip');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'ip') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'rates_form')" href="">
              <span><?php echo __('IP', 'bwg_back'); ?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="sortable <?php if ($order_by == 'date') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'date');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'date') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'rates_form')" href="">
              <span><?php echo __('Date', 'bwg_back'); ?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_small_col"><?php echo __('Delete', 'bwg_back'); ?></th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              
              $is_embed = preg_match('/EMBED/',$row_data->filetype)==1 ? true :false;
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="spider_check_all(this)" type="checkbox" /></td>
                <td class="table_small_col"><?php echo $row_data->id; ?></td>
                <td class="table_big_col ">
                  <?php 
                  if ($row_data->thumb_url) {
                  ?>
                  <img title="<?php echo $row_data->alt; ?>" style="border: 1px solid #CCCCCC; max-width: 60px; max-height: 60px;" src="<?php echo (!$is_embed ? site_url() . '/' . $WD_BWG_UPLOAD_DIR : "") . $row_data->thumb_url . (($is_embed) ? '' : '?date=' . date('Y-m-y H:i:s')); ?>">
                  <?php
                  }
                  else {
                    echo $row_data->alt;
                  }
                  ?>
                </td>
                <td class="table_big_col"><?php echo $row_data->rate; ?></td>
                <td><?php echo $row_data->ip; ?></td>
                <td><?php echo $row_data->date; ?></td>
                <td class="table_big_col"><a class="bwg_img_remove" title="<?php echo __('Delete', 'bwg_back'); ?>" onclick="if (confirm('<?php echo addslashes(__('Do you want to delete selected items?', 'bwg_back')); ?>')) {spider_set_input_value('task', 'delete');
                                                      spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                spider_form_submit(event, 'rates_form')}
                else {
                  return false;
                }" href=""></a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
        </tbody>
      </table>
      <div class="tablenav bottom">
        <?php
        WDWLibrary::html_page_nav($page_nav['total'], $pager++,  $page_nav['limit'], 'rates_form', $per_page);
        ?>
      </div>
      <input id="task" name="task" type="hidden" value="" />
      <input id="current_id" name="current_id" type="hidden" value="" />
      <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="<?php echo $asc_or_desc; ?>" />
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
    </form>
    <?php
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