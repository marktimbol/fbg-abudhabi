<?php
class BWGViewAlbum_masonry_preview {
  public function display($params, $from_shortcode = 0, $bwg = 0) {
    $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    global $WD_BWG_UPLOAD_DIR;
    require_once(WD_BWG_DIR . '/framework/WDWLibrary.php');
		
		global $wd_bwg_options;
		$placeholder = isset($wd_bwg_options->placeholder) ? $wd_bwg_options->placeholder : '';
    $gallery_download = isset($wd_bwg_options->gallery_download) ? $wd_bwg_options->gallery_download : 0;
    if (!isset($params['ecommerce_icon'])) {
      $params['ecommerce_icon'] = 'none';
    }	
    if (!isset($params['popup_enable_ecommerce'])) {
      $params['popup_enable_ecommerce'] = 0;
    }		
    if (!isset($params['masonry_album_image_title'])) {
      $params['masonry_album_image_title'] = 'none';
    }
    if (!isset($params['popup_fullscreen'])) {
      $params['popup_fullscreen'] = 0;
    }
    if (!isset($params['popup_autoplay'])) {
      $params['popup_autoplay'] = 0;
    }
    if (!isset($params['popup_enable_pinterest'])) {
      $params['popup_enable_pinterest'] = 0;
    }
    if (!isset($params['popup_enable_tumblr'])) {
      $params['popup_enable_tumblr'] = 0;
    }
    if (!isset($params['show_search_box'])) {
      $params['show_search_box'] = 0;
    }
    if (!isset($params['search_box_width'])) {
      $params['search_box_width'] = 180;
    }
    if (!isset($params['popup_enable_info'])) {
      $params['popup_enable_info'] = 1;
    }
    if (!isset($params['popup_info_always_show'])) {
      $params['popup_info_always_show'] = 0;
    }
    if (!isset($params['popup_enable_rate'])) {
      $params['popup_enable_rate'] = 0;
    }
    if (!isset($params['thumb_click_action']) || $params['thumb_click_action'] == 'undefined') {
      $params['thumb_click_action'] = 'open_lightbox';
    }
    if (!isset($params['thumb_link_target'])) {
      $params['thumb_link_target'] = 1;
    }
    if (!isset($params['popup_hit_counter'])) {
      $params['popup_hit_counter'] = 0;
    }
    if (!isset($params['order_by'])) {
      $params['order_by'] = 'ASC';
    }
		if (!isset($params['show_album_name'])) {
      $params['show_album_name'] = $wd_bwg_options->show_album_name;
    }
    if (!isset($params['show_sort_images'])) {
      $params['show_sort_images'] = 0;
    }
    if (!isset($params['masonry_album_enable_page'])) {
      $params['masonry_album_enable_page'] = 1;
    }
    if (!isset($params['show_tag_box'])) {
      $params['show_tag_box'] = 0;
    }
    $from = (isset($params['from']) ? esc_html($params['from']) : 0);
    $type = (isset($_REQUEST['type_' . $bwg]) ? esc_html($_REQUEST['type_' . $bwg]) : (isset($params['type']) ? $params['type'] : 'album'));
    $bwg_search = ((isset($_POST['bwg_search_' . $bwg]) && esc_html($_POST['bwg_search_' . $bwg]) != '') ? esc_html($_POST['bwg_search_' . $bwg]) : '');
    $sort_direction = $params['order_by'];
    if ($from === "widget") {
      $params['album_id'] = $params['id'];
      $params['sort_by'] = $params['show'] == 'random' ? 'RAND()' : 'order';
      if ($params['show'] == 'last') {
        $sort_direction = 'DESC';
      }
      $params['masonry_albums_per_page'] = $params['count'];
      $params['masonry_album_column_number'] = $wd_bwg_options->album_column_number;
      $params['masonry_album_thumb_width'] = $params['width'];
      $params['masonry_album_thumb_height'] = $params['height'];
      $params['masonry_album_image_thumb_width'] = $params['width'];
      $params['masonry_album_enable_page'] = 0;  
      $params['popup_width'] = $wd_bwg_options->popup_width;
      $params['popup_height'] = $wd_bwg_options->popup_height;
      $params['popup_effect'] = $wd_bwg_options->popup_type;
      $params['popup_enable_filmstrip'] = $wd_bwg_options->popup_enable_filmstrip;
      $params['popup_filmstrip_height'] = $wd_bwg_options->popup_filmstrip_height;
      $params['popup_enable_ctrl_btn'] = $wd_bwg_options->popup_enable_ctrl_btn;
      $params['popup_enable_fullscreen'] = $wd_bwg_options->popup_enable_fullscreen;
      $params['popup_enable_info'] = $wd_bwg_options->popup_enable_info;
      $params['popup_info_always_show'] = $wd_bwg_options->popup_info_always_show;
      $params['popup_hit_counter'] = $wd_bwg_options->popup_hit_counter;
      $params['popup_enable_rate'] = $wd_bwg_options->popup_enable_rate;
      $params['popup_interval'] = $wd_bwg_options->popup_interval;
      $params['popup_enable_comment'] = $wd_bwg_options->popup_enable_comment;
      $params['popup_enable_facebook'] = $wd_bwg_options->popup_enable_facebook;
      $params['popup_enable_twitter'] = $wd_bwg_options->popup_enable_twitter;
      $params['popup_enable_google'] = $wd_bwg_options->popup_enable_google;
      $params['popup_enable_pinterest'] = $wd_bwg_options->popup_enable_pinterest;
      $params['popup_enable_tumblr'] = $wd_bwg_options->popup_enable_tumblr;
      $params['watermark_type'] = $wd_bwg_options->watermark_type;
      $params['watermark_link'] = urlencode($wd_bwg_options->watermark_link);
      $params['watermark_opacity'] = $wd_bwg_options->watermark_opacity;
      $params['watermark_position'] = $wd_bwg_options->watermark_position;
      $params['watermark_text'] = $wd_bwg_options->watermark_text;
      $params['watermark_font_size'] = $wd_bwg_options->watermark_font_size;
      $params['watermark_font'] = $wd_bwg_options->watermark_font;
      $params['watermark_color'] = $wd_bwg_options->watermark_color;
      $params['watermark_url'] = urlencode($wd_bwg_options->watermark_url);
      $params['watermark_width'] = $wd_bwg_options->watermark_width;
      $params['watermark_height'] = $wd_bwg_options->watermark_height;
      $params['ecommerce_icon'] = $wd_bwg_options->ecommerce_icon_show_hover;
      $params['popup_effect_duration'] = isset($wd_bwg_options->popup_effect_duration) ? $wd_bwg_options->popup_effect_duration : 1;
    }

    $theme_row = WDWLibrary::get_theme_row_data($params['theme_id']);
    if (!$theme_row) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', 'bwg'), 'wd_error');
      return;
    }
     if (!isset($theme_row->album_masonry_gal_title_font_color)) {
        $theme_row->album_masonry_gal_title_font_color = 'CCCCCC';
      }
      if (!isset($theme_row->album_masonry_gal_title_font_style)) {
        $theme_row->album_masonry_gal_title_font_style = 'segoe ui';
      }
      if (!isset($theme_row->album_masonry_gal_title_font_size)) {
        $theme_row->album_masonry_gal_title_font_size = 16;
      }
      if (!isset($theme_row->album_masonry_gal_title_font_weight)) {
        $theme_row->album_masonry_gal_title_font_weight = 'bold';
      }
      if (!isset($theme_row->album_masonry_gal_title_margin)) {
        $theme_row->album_masonry_gal_title_margin = '2px';
      }
      if (!isset($theme_row->album_masonry_gal_title_shadow)) {
        $theme_row->album_masonry_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($theme_row->album_masonry_gal_title_align)) {
        $theme_row->album_masonry_gal_title_align = 'center';
      }
    $album_gallery_id = (isset($_REQUEST['album_gallery_id_' . $bwg]) ? esc_html($_REQUEST['album_gallery_id_' . $bwg]) : $params['album_id']);
    $album_row = WDWLibrary::get_album_row_data($album_gallery_id, FALSE);
    if (!$album_gallery_id || ($type == 'album' && !$album_row)) {
      echo WDWLibrary::message(__('There is no album selected or the album was deleted.', 'bwg'), 'wd_error');
      return;
    }
    if ($type == 'gallery') {
      $items_per_page = $params['masonry_album_images_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['masonry_album_images_per_page'], 'load_more_image_count' => $params['masonry_album_images_per_page']);
      $items_col_num = $params['masonry_album_image_column_number'];
      if (isset($_POST['sortImagesByValue_' . $bwg])) {
        $sort_by = esc_html($_POST['sortImagesByValue_' . $bwg]);
        if ($sort_by == 'random') {
          $params['sort_by'] = 'RAND()';
        }
        else if ($sort_by == 'default')  {
          $params['sort_by'] = $params['sort_by'];
        }
        else {
          $params['sort_by'] = $sort_by;
        }
      }
      $image_rows = WDWLibrary::get_image_rows_data($album_gallery_id, $bwg, 'album_extended', 'bwg_tag_id_bwg_masonry_thumbnails_' . $bwg, '', $items_per_page, $params['masonry_album_images_per_page'], $params['sort_by'], $sort_direction);
      $page_nav = $image_rows['page_nav'];
      $image_rows = $image_rows['images'];
      $images_count = count($image_rows);
      if (!$images_count) {
        echo WDWLibrary::message(__('There are no images in this gallery.', 'bwg'), 'wd_error');
      }
      $album_gallery_div_id = 'bwg_album_masonry_' . $bwg;
      $album_gallery_div_class = 'bwg_standart_thumbnails_' . $bwg;
    }
    else {
      $items_per_page = $params['masonry_albums_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['masonry_albums_per_page'], 'load_more_image_count' => $params['masonry_albums_per_page']);
      $items_col_num = $params['masonry_album_column_number'];
      $album_galleries_row = WDWLibrary::get_alb_gals_row($album_gallery_id, $items_per_page, 'order', $bwg, 'ASC');
      $page_nav = $album_galleries_row['page_nav'];
      $album_galleries_row = $album_galleries_row['rows'];
      if (!$album_galleries_row) {
        echo WDWLibrary::message(__('There is no album selected or the album was deleted.', 'bwg'), 'wd_error');
        return;
      }
      $album_gallery_div_id = 'bwg_album_masonry_' . $bwg;
      $album_gallery_div_class = 'bwg_album_thumbnails_' . $bwg;
    }
	
    if ($type == 'gallery' ) {     
			$form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->masonry_thumb_align . '; width:100%;';
			$album_gallery_div_id = 'bwg_masonry_thumbnails_' . $bwg;
			$album_gallery_div_class = 'bwg_masonry_thumbnails_' . $bwg;     
    }
    else {
      $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->album_masonry_thumb_align . ';width:100%;';
    }

		$form_child_div_id = 'bwg_masonry_thumbnails_div_' . $bwg;
    
		$bwg_previous_album_id = (isset($_REQUEST['bwg_previous_album_id_' . $bwg]) ? esc_html($_REQUEST['bwg_previous_album_id_' . $bwg]) : $params['album_id']);
    $bwg_previous_album_page_number = (isset($_REQUEST['bwg_previous_album_page_number_' . $bwg]) ? esc_html($_REQUEST['bwg_previous_album_page_number_' . $bwg]) : 0);

    $params_array = array(
      'action' => 'GalleryBox',
      'current_view' => $bwg,
      'theme_id' => $params['theme_id'],
      'thumb_width' => $params['masonry_album_image_thumb_width'],
      'open_with_fullscreen' => $params['popup_fullscreen'],
      'open_with_autoplay' => $params['popup_autoplay'],
      'image_width' => $params['popup_width'],
      'image_height' => $params['popup_height'],
      'image_effect' => $params['popup_effect'],
      'wd_sor' => $params['sort_by'],
      'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
      'image_filmstrip_height' => $params['popup_filmstrip_height'],
      'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
      'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
      'popup_enable_info' => $params['popup_enable_info'],
      'popup_info_always_show' => $params['popup_info_always_show'],
      'popup_hit_counter' => $params['popup_hit_counter'],
      'popup_enable_rate' => $params['popup_enable_rate'],
      'slideshow_interval' => $params['popup_interval'],
      'enable_comment_social' => $params['popup_enable_comment'],
      'enable_image_facebook' => $params['popup_enable_facebook'],
      'enable_image_twitter' => $params['popup_enable_twitter'],
      'enable_image_google' => $params['popup_enable_google'],
      'enable_image_ecommerce' => $params['popup_enable_ecommerce'],
      'enable_image_pinterest' => $params['popup_enable_pinterest'],
      'enable_image_tumblr' => $params['popup_enable_tumblr'],
      'watermark_type' => $params['watermark_type'],
      'slideshow_effect_duration' => isset($params['popup_effect_duration']) ? $params['popup_effect_duration'] : 1,
      'current_url' => urlencode($current_url)
    );
    if ($params['watermark_type'] != 'none') {
      $params_array['watermark_link'] = urlencode($params['watermark_link']);
      $params_array['watermark_opacity'] = $params['watermark_opacity'];
      $params_array['watermark_position'] = $params['watermark_position'];
    }
    if ($params['watermark_type'] == 'text') {
      $params_array['watermark_text'] = urlencode($params['watermark_text']);
      $params_array['watermark_font_size'] = $params['watermark_font_size'];
      $params_array['watermark_font'] = $params['watermark_font'];
      $params_array['watermark_color'] = $params['watermark_color'];
    }
    elseif ($params['watermark_type'] == 'image') {
      $params_array['watermark_url'] = urlencode($params['watermark_url']);
      $params_array['watermark_width'] = $params['watermark_width'];
      $params_array['watermark_height'] = $params['watermark_height'];
    }
    $params_array_hash = $params_array;
    $tags_rows = WDWLibrary::get_tags_rows_data($album_gallery_id);
    $image_right_click = $wd_bwg_options->image_right_click;
    
    $inline_style = $this->inline_styles($bwg, $theme_row, $params, $album_gallery_div_class, $items_col_num);
    if ($wd_bwg_options->use_inline_stiles_and_scripts) {
      wp_enqueue_style('bwg_frontend');
      wp_add_inline_style('bwg_frontend', $inline_style);
      wp_enqueue_style('bwg_font-awesome');
      wp_enqueue_style('bwg_mCustomScrollbar');
      $google_fonts = WDWLibrary::get_google_fonts();
      wp_enqueue_style('bwg_googlefonts');
      if (isset($params['show_tag_box']) && $params['show_tag_box']) {
        wp_enqueue_style('bwg_sumoselect');
        if (!wp_script_is('bwg_sumoselect', 'done')) {
          wp_print_scripts('bwg_sumoselect');
        }
      }
      if (!wp_script_is('bwg_frontend', 'done')) {
        wp_print_scripts('bwg_frontend');
      }
      if ($params['thumb_click_action'] == 'open_lightbox') {
        if (!wp_script_is('bwg_mCustomScrollbar', 'done')) {
          wp_print_scripts('bwg_mCustomScrollbar');
        }
        if (!wp_script_is('jquery-fullscreen', 'done')) {
          wp_print_scripts('jquery-fullscreen');
        }
        if (!wp_script_is('bwg_gallery_box', 'done')) {
          wp_print_scripts('bwg_gallery_box');
        }
        if(!wp_script_is('bwg_raty', 'done')) {
          wp_print_scripts('bwg_raty');
        }
      }
      if (!wp_script_is('bwg_jquery_mobile', 'done')) {
        wp_print_scripts('bwg_jquery_mobile');
      }
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>
    <div id="bwg_container1_<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <form id="gal_front_form_<?php echo $bwg; ?>" method="post" action="#" data-current="<?php echo $bwg; ?>">
					<?php
					if ($type != 'gallery') {
						if ($bwg_previous_album_id != $params['album_id']) {
							?>
							<a class="bwg_back_<?php echo $bwg; ?>" onclick="spider_frontend_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', '<?php echo $album_gallery_div_id; ?>', 'back', '', 'album')"><?php echo __('Back', 'bwg'); ?></a>
							<?php
						}
					}
          if (!isset($_POST['album_gallery_id']) && $type != 'gallery') {
             if ($params['show_album_name']) {
              ?>
               <div id='album_name_container' class="bwg_back_<?php echo $bwg; ?>"><?php echo $album_row->name; ?></div>
              <?php
             }
           }
          if ($params['show_search_box'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_search_box('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $images_count, $params['search_box_width'], $placeholder);
          }
          if (isset($params['show_sort_images']) && $params['show_sort_images'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_sort_box('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $params['sort_by'], $params['search_box_width']);
          }
          if (isset($params['show_tag_box']) && $params['show_tag_box'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_search_tags('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $images_count, $tags_rows);
          }
          ?>
          <div id="<?php echo $form_child_div_id; ?>" style="<?php echo $form_child_div_style; ?>">
            <div id="ajax_loading_<?php echo $bwg; ?>" style="position:absolute;width: 100%; z-index: 115; text-align: center; height: 100%; vertical-align: middle; display: none;">
              <div style="display: table; vertical-align: middle; width: 100%; height: 100%; background-color: #FFFFFF; opacity: 0.7; filter: Alpha(opacity=70);">
                <div style="display: table-cell; text-align: center; position: relative; vertical-align: middle;" >
                  <div id="loading_div_<?php echo $bwg; ?>" class="bwg_spider_ajax_loading" style="display: inline-block; text-align:center; position:relative; vertical-align:middle; background-image:url(<?php echo WD_BWG_URL . '/images/ajax_loader.gif'; ?>); float: none; width:30px;height:30px;background-size:30px 30px;">
                  </div>
                </div>
              </div>
            </div>
            <?php
            if ($params['masonry_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'top') && $page_nav['total']) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page_arr, $bwg, $album_gallery_div_id, $params['album_id'], $type, $wd_bwg_options->enable_seo, $params['masonry_album_enable_page']);
            }
						if ($type == 'gallery') {
							if ($bwg_previous_album_id != $params['album_id']) {
								?>
								<a class="bwg_back_<?php echo $bwg; ?>" onclick="spider_frontend_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', '<?php echo $album_gallery_div_id; ?>', 'back', '', 'album')"><?php echo __('Back', 'bwg'); ?></a>
								<?php
							}
						}
						if ($type == 'gallery') {
              ?>
							<div class="gallery_name_container bwg_gal_title_<?php echo $bwg; ?>" ><?php if ($params['show_album_name']) echo isset($_POST['title_' . $bwg]) ? $_POST['title_' . $bwg] : ''; ?></div>
              <?php
						}
             ?>
            <div id="<?php echo $album_gallery_div_id; ?>" class="<?php echo $album_gallery_div_class; ?>" >
              <input type="hidden" id="bwg_previous_album_id_<?php echo $bwg; ?>" name="bwg_previous_album_id_<?php echo $bwg; ?>" value="<?php echo $bwg_previous_album_id; ?>" />
              <input type="hidden" id="bwg_previous_album_page_number_<?php echo $bwg; ?>" name="bwg_previous_album_page_number_<?php echo $bwg; ?>" value="<?php echo $bwg_previous_album_page_number; ?>" />
              <?php
              if ($type != 'gallery') {
                if (!$page_nav['total']) {
                  ?>
                  <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('Album is empty.', 'bwg'); ?></span>
                  <?php
                }
                foreach ($album_galleries_row as $album_galallery_row) {
                  if ($album_galallery_row->is_album) {
                    $album_row = WDWLibrary::get_album_row_data($album_galallery_row->alb_gal_id, $from === "widget");
                    if (!$album_row) {
                      continue;
                    }
                    $preview_image = $album_row->preview_image;
                    if (!$preview_image) {
                      $preview_image = $album_row->random_preview_image;
                    }
                    $def_type = 'album';
                    $title = $album_row->name;
                    $permalink = $album_row->permalink;
                  }
                  else {
                    $gallery_row = WDWLibrary::get_gallery_row_data($album_galallery_row->alb_gal_id, ($from === "widget" ? "masonry" : ""));										
                    if (!$gallery_row) {
                      continue;
                    }
                    $preview_image = $gallery_row->preview_image;
                    if (!$preview_image) {
                      $preview_image = $gallery_row->random_preview_image;
                    }
                    $def_type = 'gallery';
                    $title = $gallery_row->name;
                    $permalink = $gallery_row->permalink;
                  }
                  $local_preview_image = true;
                  $parsed_prev_url = parse_url($preview_image, PHP_URL_SCHEME);
                  if($parsed_prev_url =='http' || $parsed_prev_url =='https'){
                    $local_preview_image = false;
                  }


                  if (!$preview_image) {
                    $preview_url = WD_BWG_URL . '/images/no-image.png';
                    $preview_path = WD_BWG_DIR . '/images/no-image.png';
                  }
                  else {
                    if ($local_preview_image) {
                      $preview_url = site_url() . '/' . $WD_BWG_UPLOAD_DIR . $preview_image;
                      $preview_path = ABSPATH . $WD_BWG_UPLOAD_DIR . $preview_image;
                    }
                    else {
                      $preview_url = $preview_image;
                      $preview_path = $preview_image;
                    }
                  }
                  if ($type != 'gallery') {
										?>
										<span class="bwg_masonry_thumb_spun_<?php echo $bwg; ?>">
											<div class="gallery_name_container gallery_name_in_album_view_<?php echo $bwg; ?>"><?php if ($params['show_album_name']) echo $title; ?></div>																								
											<a class="bwg_album_<?php echo $bwg; ?>" <?php echo ($from !== "widget" ? ($wd_bwg_options->enable_seo ? "href='" . esc_url(add_query_arg(array("type_" . $bwg => $def_type, "album_gallery_id_" . $bwg => $album_galallery_row->alb_gal_id, "bwg_previous_album_id_" . $bwg => $album_gallery_id . ',' . $bwg_previous_album_id , "bwg_previous_album_page_number_" . $bwg => (isset($_REQUEST['page_number_' . $bwg]) ? esc_html($_REQUEST['page_number_' . $bwg]) : 0) . ',' . $bwg_previous_album_page_number), $_SERVER['REQUEST_URI'])) . "'" : "") . " data-alb_gal_id=\"" . $album_galallery_row->alb_gal_id . "\" data-def_type=\"" . $def_type . "\" data-title=\"" . htmlspecialchars(addslashes($title)) . "\"" : "href='" . $permalink . "'") ?>>
												<input id='<?php echo $album_galallery_row->alb_gal_id; ?>' type='hidden' value="<?php echo $title; ?>" />
												<img class="bwg_masonry_thumb_<?php echo $bwg; ?> bwg_img_clear bwg_img_custom" src="<?php echo $preview_url; ?>" alt="<?php echo $title; ?>" style="width: <?php echo $params['masonry_album_thumb_width'];?>px;" />
												<?php if ($wd_bwg_options->show_masonry_thumb_description) : ?>
													<div class="bwg_masonry_thumb_description_<?php echo $bwg; ?>">
														<span><?php if (!$album_galallery_row->is_album) { echo $gallery_row->description; } ?></span>
													</div>
												<?php	endif; ?>
											</a>
										</span>								
                    <?php								
                  }
                }	
              }
              elseif ($type == 'gallery') {
                if (!$page_nav['total']) {
                  if ($bwg_search != '') {
                    ?>
                    <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('There are no images matching your search.', 'bwg'); ?></span>
                    <?php
                  }
                  else {
                    ?>
                    <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('Gallery is empty.', 'bwg'); ?></span>
                    <?php
                  }
                }
                foreach ($image_rows as $image_row) {
                  $params_array['image_id'] = (isset($_POST['image_id']) ? esc_html($_POST['image_id']) : $image_row->id);
                  $params_array['gallery_id'] = $album_gallery_id;
                  $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                    ?>
                        <span class="bwg_masonry_thumb_spun_<?php echo $bwg; ?>">
                            <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox_' . $bwg . '"' . ($wd_bwg_options->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . $WD_BWG_UPLOAD_DIR . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '" data-gallery-id="' . $album_gallery_id . '"') : ($image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                            <?php if(defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php') && $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id){		 
                                ?>	
                                  <span class="bwg_ecommerce_spun1_<?php echo $bwg; ?>">
                                    <span class="bwg_ecommerce_spun2_<?php echo $bwg; ?>">
                                      <i title="<?php echo __('Open', 'bwg'); ?>" class="bwg_ctrl_btn bwg_open fa fa-share-square" ></i>
                                      <i title="<?php echo __('Ecommerce', 'bwg'); ?>" class="bwg_ctrl_btn bwg_ecommerce fa fa-shopping-cart" ></i>                               
                                    </span>
                                  </span>                               
                               <?php
                                  }                              
                              ?>
                                <img class="bwg_masonry_thumb_<?php echo $bwg; ?> bwg_img_clear bwg_img_custom" id="<?php echo $image_row->id; ?>" src="<?php echo ($is_embed ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $image_row->thumb_url; ?>" alt="<?php echo $image_row->alt; ?>" style="width: <?php echo $params['masonry_album_image_thumb_width'];?>px;" />
                                <?php if ($wd_bwg_options->show_masonry_thumb_description) : ?>
                                    <div class="bwg_masonry_thumb_description_<?php echo $bwg; ?>">
                                        <span><?php echo $image_row->description; ?></span>
                                    </div>
                                <?php	endif; ?>
                            </a>
                        </span>
                    <?php
                }
              }
              ?>
            </div>
              <script>
                function bwg_masonry_<?php echo $bwg; ?>() {
                  var image_width = <?php echo $type=='album'?$params['masonry_album_thumb_width']:$params['masonry_album_image_thumb_width'];?>;
                  var cont_div_width = <?php
										if ($type == 'gallery') {
											echo $items_col_num * ($params['masonry_album_image_thumb_width'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width));
										} else {
											echo $items_col_num * ($params['masonry_album_thumb_width'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width));
										}?>;
										
                  if (cont_div_width > jQuery("#bwg_masonry_thumbnails_div_<?php echo $bwg; ?>").width()) {
                    cont_div_width = jQuery("#bwg_masonry_thumbnails_div_<?php echo $bwg; ?>").width();
                  }
                  var col_count = parseInt(cont_div_width / image_width);
                  if (!col_count) {
                    col_count = 1;
                  }
                  var top = new Array();
                  var left = new Array();
                  for (var i = 0; i < col_count; i++) {
                    top[i] = 0;
                    left[i] = i * image_width;
                  }
                  var div_width = col_count * image_width;
                  if (div_width > jQuery(window).width()) {
                    div_width = jQuery(window).width();										
										jQuery(".bwg_masonry_thumb_<?php echo $bwg;?>").attr("style", "max-width: " + div_width + "px");									
                  }
                  else {
                    div_width = col_count * image_width;
                  }
									
									/*************************************************************************************************************/									
									<?php
                  if ($wd_bwg_options->show_masonry_thumb_description) {
                    ?>
                    jQuery(".bwg_masonry_thumb_description_<?php echo $bwg; ?>").attr("style", "max-width: " + image_width + "px");
                    <?php
                  }
									?>
									/*************************************************************************************************************/
									var min_top, index_min_top;
                  jQuery(".bwg_masonry_thumb_spun_<?php echo $bwg; ?>").each(function() {
                    min_top = Math.min.apply(Math, top);
                    index_min_top = jQuery.inArray(min_top, top);
                    jQuery(this).css({left: left[index_min_top], top: top[index_min_top]});
                    top[index_min_top] += jQuery(this).height();
                  });
                  jQuery(".bwg_masonry_thumbnails_<?php echo $bwg; ?>").width(div_width);
                  jQuery(".bwg_masonry_thumbnails_<?php echo $bwg; ?>").height(Math.max.apply(Math, top));
                  jQuery(".bwg_album_thumbnails_<?php echo $bwg; ?>").height(Math.max.apply(Math, top));
                  jQuery(".bwg_masonry_thumb_<?php echo $bwg; ?>").css({visibility: 'visible'});
                }
                jQuery(window).load(function() {
                  bwg_masonry_<?php echo $bwg; ?>();
                });
                jQuery(window).resize(function() {
                  bwg_masonry_<?php echo $bwg; ?>();
                });
                function bwg_masonry_ajax_<?php echo $bwg; ?>(tot_cccount_masonry_ajax) {
                  var cccount_masonry_ajax = 0;
                  jQuery(".bwg_masonry_thumb_spun_<?php echo $bwg; ?>  img").on("load", function() {
                    if (++cccount_masonry_ajax >= tot_cccount_masonry_ajax) {
                      window["bwg_masonry_<?php echo $bwg; ?>"]();
                    }
                  });
                  jQuery(".bwg_masonry_thumb_spun_<?php echo $bwg; ?> img").error(function() {
                    jQuery(this).height(100);
                    jQuery(this).width(100);
                    if (++cccount_masonry_ajax >= tot_cccount_masonry_ajax) {
                      window["bwg_masonry_<?php echo $bwg; ?>"]();
                    }
                  });
                }
                <?php
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
                  ?>
                  /* If page is called by AJAX use this instead of window.load.*/
                  bwg_masonry_ajax_<?php echo $bwg; ?>(0);
                  <?php
                endif;
                ?>
              </script>
              <?php
              if ( $type == 'gallery' ) {
                if ( $gallery_download && $image_rows ) {
                  $query_url = addslashes(add_query_arg(array(
                                                          "action" => "download_gallery",
                                                          "gallery_id" => $album_gallery_id,
                                                        ), admin_url('admin-ajax.php')));
                  ?>
                  <div class="bwg_download_gallery">
                    <a href="<?php echo $query_url; ?>">
                      <i title="<?php _e('Download gallery', 'bwg'); ?>" class="bwg_ctrl_btn fa fa-download"></i>
                    </a>
                  </div>
                  <?php
                }
              }
            if ($params['masonry_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'bottom') && $page_nav['total']) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page_arr, $bwg, $album_gallery_div_id, $params['album_id'], $type, $wd_bwg_options->enable_seo, $params['masonry_album_enable_page']);
            }
            ?>
          </div>
        </form>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      function bwg_gallery_box_<?php echo $bwg; ?>(gallery_id, image_id, openEcommerce) {
        if (typeof openEcommerce == undefined) {
          openEcommerce = false;
        }
        var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";
        var filterTags = jQuery("#bwg_tags_id_bwg_masonry_thumbnails_<?php echo $bwg; ?>" ).val() ? jQuery("#bwg_tags_id_bwg_masonry_thumbnails_<?php echo $bwg; ?>" ).val() : 0;
        var filtersearchname = jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() ? "&filter_search_name_<?php echo $bwg; ?>=" + jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() : '';
        spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>&gallery_id=' + gallery_id + '&image_id=' + image_id + "&filter_tag_<?php echo $bwg; ?>=" +  filterTags + ecommerce + filtersearchname, '<?php echo $bwg; ?>', '<?php echo $params['popup_width']; ?>', '<?php echo $params['popup_height']; ?>', 1, 'testpopup', 5, "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>");
      }
      var bwg_hash = window.location.hash.substring(1);
      if (bwg_hash) {
        if (bwg_hash.indexOf("bwg") != "-1") {
          bwg_hash_array = bwg_hash.replace("bwg", "").split("/");
          bwg_gallery_box_<?php echo $bwg; ?>(bwg_hash_array[0], bwg_hash_array[1]);
        }
      }
      function bwg_document_ready_<?php echo $bwg; ?>() {
        var bwg_touch_flag = false;
        jQuery(".bwg_lightbox_<?php echo $bwg; ?>").on("click", function () {
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            bwg_gallery_box_<?php echo $bwg; ?>(jQuery(this).attr("data-gallery-id"), jQuery(this).attr("data-image-id"));
            return false;
          }
        });
        jQuery(".bwg_lightbox_<?php echo $bwg; ?> .bwg_ecommerce").on("click", function (event) {
          event.stopPropagation();
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function () {
              bwg_touch_flag = false;
            }, 100);
            var image_id = jQuery(this).closest(".bwg_lightbox_<?php echo $bwg; ?>").attr("data-image-id");
            var gallery_id = jQuery(this).closest(".bwg_lightbox_<?php echo $bwg; ?>").attr("data-gallery-id");
            bwg_gallery_box_<?php echo $bwg; ?>(gallery_id, image_id, true);
            return false;
          }
        });
        <?php if ($from !== "widget") { ?>
        jQuery(".bwg_album_<?php echo $bwg; ?>").on("click", function () {
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            spider_frontend_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', 'bwg_album_masonry_<?php echo $bwg; ?>', jQuery(this).attr("data-alb_gal_id"), '<?php echo $album_gallery_id; ?>', jQuery(this).attr("data-def_type"), '', jQuery(this).attr("data-title"), 'default', false);
            return false;
          }
        });
        <?php }
        if ($image_right_click) {
          ?>
          /* Disable right click.*/
          jQuery('div[id^="bwg_container"]').bind("contextmenu", function () {
            return false;
          });
          jQuery('div[id^="bwg_container"]').css('webkitTouchCallout','none');
          <?php
        }
        ?>
      }
      jQuery(document).ready(function () {
        bwg_document_ready_<?php echo $bwg; ?>();
      });
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
  }

  private function inline_styles($bwg, $theme_row, $params, $album_gallery_div_class, $items_col_num) {
    ob_start();
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_album_masonry_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->album_masonry_thumbs_bg_color);
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->thumbs_bg_color);
    ?>
    /* Style for masonry view.*/
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> * {
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?> {
      visibility: hidden;
      text-align: center;
      display: inline-block;
      vertical-align: middle;  
      
      /*width: <?php echo $params['masonry_album_image_thumb_width'];?>px;*/
      
      border-radius: <?php echo $theme_row->album_masonry_thumb_border_radius; ?>;
      border: <?php echo $theme_row->album_masonry_thumb_border_width; ?>px <?php echo $theme_row->album_masonry_thumb_border_style; ?> #<?php echo $theme_row->album_masonry_thumb_border_color; ?>;
      background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
      margin: 0;
      padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
      opacity: <?php echo $theme_row->masonry_thumb_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
      <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      z-index: 100;
    }	
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
      -moz-box-sizing: border-box;
      background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo $theme_row->masonry_thumb_bg_transparent / 100; ?>);
      box-sizing: border-box;
      display: inline-block;
      font-size: 0;
      /*width: <?php echo $params['masonry_album_image_column_number'] * ($params['masonry_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
      width: 100%;
      position: relative;
      text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
    }
    @media only screen and (max-width : <?php echo $params['masonry_album_image_column_number'] * ($params['masonry_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
        width: inherit;
      }
    }	  	  
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
      opacity: <?php echo $theme_row->lightbox_overlay_bg_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .<?php echo $album_gallery_div_class; ?> * {
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumbnails_<?php echo $bwg; ?> {
      display: inline-block;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
      background-color: rgba(<?php echo $rgb_album_masonry_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_masonry_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_masonry_thumbs_bg_color['blue']; ?>, <?php echo $theme_row->album_masonry_thumb_bg_transparent / 100; ?>);
      font-size: 0;
      text-align: <?php echo $theme_row->album_masonry_thumb_align; ?>;
      max-width: <?php echo $items_col_num * ($params['masonry_album_thumb_width'] + 2 * (2 + $theme_row->album_masonry_thumb_margin + $theme_row->album_masonry_thumb_padding + $theme_row->album_masonry_thumb_border_width)); ?>px;
    }
    
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumbnails_<?php echo $bwg; ?> {
      -moz-box-sizing: border-box;
      display: inline-block;
      background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo $theme_row->thumb_bg_transparent / 100; ?>);
      box-sizing: border-box;
      font-size: 0;
      max-width: <?php echo $params['masonry_album_image_column_number'] * ($params['masonry_album_image_thumb_width'] + 2 * (2 + $theme_row->thumb_margin + $theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>px;
      text-align: <?php echo $theme_row->thumb_align; ?>;
    }    
    /*Pagination styles.*/
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> {
      text-align: <?php echo $theme_row->page_nav_align; ?>;
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      margin: 6px 0 4px;
      display: block;
      height: 30px;
      line-height: 30px;
    }
    @media only screen and (max-width : 320px) {
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
        display: none;
      }
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      margin-right: 10px;
      vertical-align: middle;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .paging-input_<?php echo $bwg; ?> {
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      vertical-align: middle;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:hover,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:focus {
      cursor: default;
      color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a {
      cursor: pointer;
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      text-decoration: none;
      padding: <?php echo $theme_row->page_nav_padding; ?>;
      margin: <?php echo $theme_row->page_nav_margin; ?>;
      border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
      border-style: <?php echo $theme_row->page_nav_border_style; ?>;
      border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
      border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
      background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
      opacity: <?php echo $theme_row->page_nav_button_bg_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
      box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
      <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> {
      background-color: rgba(0, 0, 0, 0);
      color: #<?php echo $theme_row->album_masonry_back_font_color; ?> !important;
      cursor: pointer;
      display: block;
      font-family: <?php echo $theme_row->album_masonry_back_font_style; ?>;
      font-size: <?php echo $theme_row->album_masonry_back_font_size; ?>px;
      font-weight: <?php echo $theme_row->album_masonry_back_font_weight; ?>;
      text-decoration: none;
      padding: <?php echo $theme_row->album_masonry_back_padding; ?>;
    }
    
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
      opacity: <?php echo $theme_row->lightbox_overlay_bg_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
    }
    
    
    /* --------------------------------------------------------------------------------------------------------------------------------------------- */
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?> {
      position: absolute;
      vertical-align: top;
      opacity: <?php echo $theme_row->album_masonry_thumb_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->album_masonry_thumb_transparent; ?>);
      <?php echo ($theme_row->album_masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>					
    }
    
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover {       
      opacity: <?php echo $theme_row->album_masonry_thumb_transparent / 100; ?>;
      filter: Alpha(opacity=<?php echo $theme_row->album_masonry_thumb_transparent; ?>);
      transform: <?php echo $theme_row->album_masonry_thumb_hover_effect; ?>(<?php echo $theme_row->album_masonry_thumb_hover_effect_value; ?>);
      -ms-transform: <?php echo $theme_row->album_masonry_thumb_hover_effect; ?>(<?php echo $theme_row->album_masonry_thumb_hover_effect_value; ?>);
      -webkit-transform: <?php echo $theme_row->album_masonry_thumb_hover_effect; ?>(<?php echo $theme_row->album_masonry_thumb_hover_effect_value; ?>);				
      backface-visibility: hidden;
      -webkit-backface-visibility: hidden;
      -moz-backface-visibility: hidden;
      -ms-backface-visibility: hidden;
      z-index: 102;
    }
    
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_description_<?php echo $bwg; ?> {
      padding: 0 5px;						
      line-height: 1.4;
      
      font-size: <?php echo $theme_row->masonry_description_font_size; ?>px;
      color: #<?php echo $theme_row->masonry_description_color; ?>;
      font-family: <?php echo $theme_row->masonry_description_font_style; ?>;   
      text-align: center;			
    }

    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .gallery_name_in_album_view_<?php echo $bwg; ?> {
      position: absolute;
      left: <?php echo $theme_row->album_masonry_thumb_border_width + 10; ?>px;
      top: <?php echo $theme_row->album_masonry_thumb_border_width + 2; ?>px;
      background-color: rgba(0, 0, 0, 0);
      color: #<?php echo $theme_row->album_masonry_title_font_color; ?>;
      font-family: <?php echo $theme_row->album_masonry_title_font_style; ?>;
      font-size: <?php echo $theme_row->album_masonry_title_font_size; ?>px;
      font-weight: <?php echo $theme_row->album_masonry_title_font_weight; ?>;
      text-shadow: <?php echo $theme_row->album_masonry_title_shadow; ?>;      
      cursor: pointer;
      display: block;
      z-index: 99999;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_title_<?php echo $bwg; ?> {
      background-color: rgba(0, 0, 0, 0);
      color: #<?php echo $theme_row->album_masonry_gal_title_font_color; ?>;
      display: block;
      font-family: <?php echo $theme_row->album_masonry_gal_title_font_style; ?>;
      font-size: <?php echo $theme_row->album_masonry_gal_title_font_size; ?>px;
      font-weight: <?php echo $theme_row->album_masonry_gal_title_font_weight; ?>;
      padding: <?php echo $theme_row->album_masonry_gal_title_margin; ?>;
      text-shadow: <?php echo $theme_row->album_masonry_gal_title_shadow; ?>;
      text-align: <?php echo $theme_row->album_masonry_gal_title_align; ?>;
    }
     <?php
    if(defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php') && $params['ecommerce_icon'] == 'hover'){
      ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?> {
        display: table;
        height: <?php echo $params['thumb_height'];?>px;
        left: -3000px;
        opacity: 0;
        filter: Alpha(opacity=0);
        position: absolute;
        top: 0px;
        width: 100%;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover img{
          opacity:0.5;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover{
          background:#000;
      }

      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?> i{
          opacity:1 !important;
          font-size:20px !important;
          z-index: 45;
      }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover  .bwg_ecommerce_spun1_<?php echo $bwg; ?>{
      top: 0px;
      bottom:0;
      left:0;
      right:0;
      margin:auto;
      opacity: 1;
      filter: Alpha(opacity=100);
    }

    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?>  .bwg_ecommerce_spun2_<?php echo $bwg; ?> {
      display: table-cell;
      height: inherit;
      padding: <?php echo $theme_row->thumb_title_margin; ?>;
      vertical-align: middle;
      width: inherit;
      word-wrap: break-word;
      color: #<?php echo $theme_row->masonry_description_color; ?>;
      text-align: center;
    } 
      <?php
    }
    return ob_get_clean();
  }
}