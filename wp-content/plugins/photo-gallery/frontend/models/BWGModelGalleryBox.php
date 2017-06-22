<?php
class BWGModelGalleryBox {
  public function get_comment_rows_data($image_id) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_image_comment WHERE image_id="%d" AND published=1 ORDER BY `id` DESC', $image_id));
    return $row;
  }

  public function get_image_rows_data($gallery_id, $bwg, $sort_by, $order_by = 'asc', $tag = 0) {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(image.' . $sort_by . ' AS SIGNED) ';
    }
    elseif ($sort_by == 'random' || $sort_by == 'RAND()') {
      $sort_by = 'RAND()';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      $sort_by = 'image.`order`';
    }
    if (strtolower($order_by) != 'asc') {
      $order_by = 'desc';
    }
    $bwg_random_seed = isset($_SESSION['bwg_random_seed_'. $bwg]) ? $_SESSION['bwg_random_seed_'. $bwg] : '';
    $filter_tags = (isset($_REQUEST['filter_tag_'. $bwg]) && $_REQUEST['filter_tag_'. $bwg]) ? explode(",", $_REQUEST['filter_tag_'. $bwg]) : array();
    $filter_search_name = (isset($_REQUEST['filter_search_name_'. $bwg])) ? esc_html($_REQUEST['filter_search_name_'. $bwg]) : '';
    $where = '';
    if ($filter_search_name != '') {
     $where = ' AND (image.alt LIKE "%%' . $filter_search_name . '%%" OR image.description LIKE "%%' . $filter_search_name . '%%")';
    }

    $where .= ($gallery_id ? ' AND image.gallery_id = "' . $gallery_id . '" ' : '') . ($tag ? ' AND tag.tag_id = "' . $tag . '" ' : '');
    $join = $tag ? 'LEFT JOIN ' . $wpdb->prefix . 'bwg_image_tag as tag ON image.id=tag.image_id' : '';

    if ($filter_tags){
      $join .= ' LEFT JOIN (SELECT GROUP_CONCAT(tag_id SEPARATOR ",") AS tags_combined, image_id FROM  ' . $wpdb->prefix . 'bwg_image_tag' . ($gallery_id ? ' WHERE gallery_id="' . $gallery_id . '"' : '') . ' GROUP BY image_id) AS tags ON image.id=tags.image_id';
      $where .= ' AND CONCAT(",", tags.tags_combined, ",") REGEXP ",(' . implode("|", $filter_tags) . ')," ';
    }

    $row = $wpdb->get_results('SELECT image.*, rates.rate FROM ' . $wpdb->prefix . 'bwg_image as image LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE ip="%s") as rates ON image.id=rates.image_id ' . $join . ' WHERE image.published=1 ' . $where . ' ORDER BY ' . str_replace('RAND()', 'RAND(' . $bwg_random_seed . ')', $sort_by) . ' ' . $order_by);

    return $row;
  }
  
  public function get_image_pricelists($pricelist_id) {
    $pricelist_data = array();
    if (!$pricelist_id) {
      $pricelist = new StdClass();
      $pricelist->price = NULL;
      $pricelist->manual_description = NULL;
      $pricelist->manual_title = NULL;
      $pricelist->sections = NULL;

      $pricelist_data["pricelist"] = $pricelist;
      $pricelist_data["download_items"] = "";
      $pricelist_data["parameters"] = "";
      $options = new StdClass();
      $options->show_digital_items_count = NULL;
      $options->checkout_page = NULL;
      $options->currency_sign = NULL;
      $options->checkout_page = NULL;
      $pricelist_data["options"] = $options;
      $pricelist_data["products_in_cart"] =  0;

      return $pricelist_data;
    }	
      
    global $wpdb;
    // pricelist
    $pricelist= $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdpg_ecommerce_pricelists WHERE id="%d" ',$pricelist_id));
    
    // download items
    $download_items= $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdpg_ecommerce_pricelist_items 
    WHERE pricelist_id="%d" ',$pricelist_id));
    
    // parameters	
    $parameter_rows= $wpdb->get_results($wpdb->prepare('SELECT T_PRICELIST_PARAMETERS.*, T_PARAMETERS.title,  T_PARAMETERS.type  FROM ' . $wpdb->prefix . 'wdpg_ecommerce_pricelist_parameters AS T_PRICELIST_PARAMETERS LEFT JOIN ' . $wpdb->prefix . 'wdpg_ecommerce_parameters
    AS T_PARAMETERS ON T_PRICELIST_PARAMETERS.parameter_id = T_PARAMETERS.id WHERE pricelist_id="%d" AND T_PARAMETERS.published="%d" ORDER BY T_PRICELIST_PARAMETERS.id',$pricelist_id,1));

    $parameters_map = array();
    foreach ($parameter_rows as $parameter_row) {
      $parameter_id = $parameter_row->parameter_id;
      $param_value = array();
      $param_value['parameter_value'] = $parameter_row->parameter_value;
      $param_value['parameter_value_price'] = $parameter_row->parameter_value_price;
      $param_value['parameter_value_price_sign'] = $parameter_row->parameter_value_price_sign;							
      $parameters_map[$parameter_id]['title'] = $parameter_row->title;
      $parameters_map[$parameter_id]['type'] = $parameter_row->type;
      $parameters_map[$parameter_id]["values"][] = $param_value;
      
    }

    //options	
    $options_rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdpg_ecommerce_options ');
    $options = new stdClass();
    foreach ($options_rows as $row) {
      $name = $row->name;
      $value = $row->value;
      $options->$name = $value;
    }

    // shopping cart options
    $products_in_cart = 0;
    $order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();

    $user = get_current_user_id();
    if ($user == 0 && empty($order_rand_ids ) === false) {
      array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
      // get image order rows
      foreach ($order_rand_ids as $order_rand_id) {
        $product_in_cart = $wpdb->get_var($wpdb->prepare('SELECT products_count FROM ' . $wpdb->prefix . 'wdpg_ecommerce_order_images 
        WHERE user_id="%d" AND rand_id = "%d"  AND order_id="%d" ', $user, $order_rand_id, 0));
        $products_in_cart += $product_in_cart;
      }
    }
    elseif ($user != 0 ) {
      // get image order rows
      $products_in_cart = $wpdb->get_var($wpdb->prepare('SELECT SUM(products_count) FROM ' . $wpdb->prefix . 'wdpg_ecommerce_order_images 
      WHERE user_id="%d" AND order_id="%d"', $user, 0));
    } 

    $pricelist_data["pricelist"] = $pricelist;
    $pricelist_data["download_items"] = $download_items;
    $pricelist_data["parameters"] = $parameters_map;
    $pricelist_data["options"] = $options;
    $pricelist_data["products_in_cart"] = $products_in_cart ? $products_in_cart : 0;

    return $pricelist_data;
  }

  public function get_image_pricelist($image_id) {
    global $wpdb;
    $image_pricelist = $wpdb->get_var($wpdb->prepare('SELECT pricelist_id FROM ' . $wpdb->prefix . 'bwg_image WHERE id="%d" ', $image_id));
    return $image_pricelist;	  
  }
}