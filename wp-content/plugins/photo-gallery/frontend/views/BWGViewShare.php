<?php

class BWGViewShare {
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
    $current_url = isset($_GET['curr_url']) ? $_GET['curr_url'] : '';
    if (isset($_GET['image_id'])) {
      require_once(WD_BWG_DIR . '/framework/WDWLibrary.php');
      $get_image_id = $_GET['image_id'];
      $cur_image_row = $this->model->get_image_row_data($get_image_id);
      if (!$cur_image_row) {
        header("HTTP/1.1 410 Gone");
        die();
      }
      $gallery_id = $cur_image_row->gallery_id;
      $alt = ($cur_image_row->alt != '') ? $cur_image_row->alt: get_bloginfo('name');
      $description = $cur_image_row->description;	  
      $is_embed = preg_match('/EMBED/', $cur_image_row->filetype)==1 ? true : false;     
      $share_url = add_query_arg(array('curr_url' => $current_url, 'image_id' => $get_image_id), WDWLibrary::get_share_page()) . '#bwg' . $gallery_id . '/' . $get_image_id;
      if (!$is_embed) {
        list($image_thumb_width, $image_thumb_height) = getimagesize(htmlspecialchars_decode(ABSPATH . $WD_BWG_UPLOAD_DIR . $cur_image_row->image_url, ENT_COMPAT | ENT_QUOTES));
      }
      else {
        global $wd_bwg_options;
        $image_thumb_width = $wd_bwg_options->thumb_width;
        if ($cur_image_row->resolution != '') {
          $resolution_arr = explode(" ", $cur_image_row->resolution);
          $resolution_w = intval($resolution_arr[0]);
          $resolution_h = intval($resolution_arr[2]);
          if ($resolution_w != 0 && $resolution_h != 0) {
            $scale = $scale = max($wd_bwg_options->thumb_width / $resolution_w, $wd_bwg_options->thumb_height / $resolution_h);
            $image_thumb_width = $resolution_w * $scale;
            $image_thumb_height = $resolution_h * $scale;
          }
          else {
            $image_thumb_width = $wd_bwg_options->thumb_width;
            $image_thumb_height = $wd_bwg_options->thumb_height;
          }
        }
        else {
          $image_thumb_width = $wd_bwg_options->thumb_width;
          $image_thumb_height = $wd_bwg_options->thumb_height;
        }
      }
      ?>
      <!DOCTYPE html>
      <script>
        var bwg_hash = window.parent.location.hash;
        if (bwg_hash) {
          if (bwg_hash.indexOf("bwg") == "-1") {
            bwg_hash = bwg_hash.replace("#", "#bwg");
          }
          window.location.href = "<?php echo $current_url; ?>" + bwg_hash;
        }
      </script>
      <html>
        <head>
          <title><?php echo get_bloginfo('name'); ?></title>
          <meta property="og:title" content="<?php echo $alt; ?>" />
          <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
          <meta property="og:url" content="<?php echo $share_url; ?>" />
          <meta property="og:description" content="<?php echo $description; ?>" />
          <meta property="og:image" content="<?php echo  $is_embed ? $cur_image_row->thumb_url : site_url() . '/' . $WD_BWG_UPLOAD_DIR . str_replace(' ', '%20', $cur_image_row->image_url); ?>" alt="<?php echo $cur_image_row->alt; ?>" />
          <meta property="og:image:width" name="bwg_width" content="<?php echo $image_thumb_width; ?>" />
          <meta property="og:image:height" name="bwg_height" content="<?php echo $image_thumb_height; ?>" />
        </head>
        <body style="display:none">
          <img src="<?php echo  $is_embed ? $cur_image_row->thumb_url : site_url() . '/' . $WD_BWG_UPLOAD_DIR . str_replace(' ', '%20', $cur_image_row->image_url); ?>" alt="<?php echo $alt; ?>">
        </body>
      </html>	  
      <?php
    }
    die();
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