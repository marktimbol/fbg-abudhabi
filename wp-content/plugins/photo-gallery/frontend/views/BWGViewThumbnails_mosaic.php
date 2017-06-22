<?php
class BWGViewThumbnails_mosaic {
  public function display($params, $from_shortcode = 0, $bwg = 0) {
    $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    global $WD_BWG_UPLOAD_DIR;
    global $wd_bwg_options;
    require_once(WD_BWG_DIR . '/framework/WDWLibrary.php');
		$from = (isset($params['from']) ? esc_html($params['from']) : 0);
    if (!isset($params['tag'])) {
      $params['tag'] = 0;
    }
    if (!isset($params['image_title'])) {
      $params['image_title'] = 'none';
    }
    if (!isset($params['ecommerce_icon'])) {
      $params['ecommerce_icon'] = 'none';
    }
    if (!isset($params['popup_enable_ecommerce'])) {
      $params['popup_enable_ecommerce'] = 0;
    }
    if (!isset($params['popup_fullscreen'])) {
      $params['popup_fullscreen'] = 0;
    }
    if (!isset($params['popup_autoplay'])) {
      $params['popup_autoplay'] = 0;
    }
    if (!isset($params['order_by'])) {
      $order_by = 'asc';
    }
    else {
      $order_by = $params['order_by'];
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
    if (!isset($params['popup_info_full_width'])) {
      $params['popup_info_full_width'] = 0;
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
    if (!isset($params['show_sort_images'])) {
      $params['show_sort_images'] = 0;
    }
    if (!isset($params['image_enable_page'])) {
      $params['image_enable_page'] = 1;
    }
    if (!isset($params['show_tag_box'])) {
      $params['show_tag_box'] = 0;
    }
    if (!isset($params['show_gallery_description'])) {
      $params['show_gallery_description'] = 0;
    }
    if (!isset($params['showthumbs_name'])) {
      $params['showthumbs_name'] = $wd_bwg_options->showthumbs_name;
    }
		$placeholder = isset($wd_bwg_options->placeholder) ? $wd_bwg_options->placeholder : '';
    $play_icon = $wd_bwg_options->play_icon;
    $gallery_download = isset($wd_bwg_options->gallery_download) ? $wd_bwg_options->gallery_download : 0;
    if ($from) {
      $params['gallery_id'] = $params['id'];
      $params['mosaic_hor_ver'] = $wd_bwg_options->mosaic;
      $params['resizable_mosaic'] = $wd_bwg_options->resizable_mosaic;
      $params['mosaic_total_width'] = $wd_bwg_options->mosaic_total_width;
      $params['images_per_page'] = $params['count'];
      $params['sort_by'] = (($params['show'] == 'random') ? 'RAND()' : 'order');

      if ($params['show'] == 'last') {
        $order_by = 'DESC';
      }
      elseif ($params['show'] == 'first') {
        $order_by = 'ASC';
      }
      $params['image_enable_page'] = $wd_bwg_options->image_enable_page;
      $params['image_title'] = $wd_bwg_options->image_title_show_hover;
      $params['ecommerce_icon'] = $wd_bwg_options->ecommerce_icon_show_hover;
      $params['thumb_height'] = $params['height'];
      $params['thumb_width'] = $params['width'];
      $params['popup_fullscreen'] = $wd_bwg_options->popup_fullscreen;
      $params['popup_autoplay'] = $wd_bwg_options->popup_autoplay;
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
      $params['thumb_click_action'] = $wd_bwg_options->thumb_click_action;
      $params['thumb_link_target'] = $wd_bwg_options->thumb_link_target;
      $params['popup_effect_duration'] = isset($wd_bwg_options->popup_effect_duration) ? $wd_bwg_options->popup_effect_duration : 1;
    }
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
    $theme_row = WDWLibrary::get_theme_row_data($params['theme_id']);
    if (!$theme_row) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', 'bwg'), 'wd_error');
      return;
    }
     if (!isset($theme_row->mosaic_thumb_gal_title_font_color)) {
        $theme_row->mosaic_thumb_gal_title_font_color = 'CCCCCC';
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_font_style)) {
        $theme_row->mosaic_thumb_gal_title_font_style = 'segoe ui';
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_font_size)) {
        $theme_row->mosaic_thumb_gal_title_font_size = 16;
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_font_weight)) {
        $theme_row->mosaic_thumb_gal_title_font_weight = 'bold';
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_margin)) {
        $theme_row->mosaic_thumb_gal_title_margin = '2px';
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_shadow)) {
        $theme_row->mosaic_thumb_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($theme_row->mosaic_thumb_gal_title_align)) {
        $theme_row->mosaic_thumb_gal_title_align = 'center';
      }
      if (!isset($theme_row->image_browser_gal_title_font_color)) {
        $theme_row->image_browser_gal_title_font_color = 'CCCCCC';
      }
    $gallery_row = WDWLibrary::get_gallery_row_data($params['gallery_id']);
    if (!$gallery_row && $params["tag"] == 0) {
      echo WDWLibrary::message(__('There is no gallery selected or the gallery was deleted.', 'bwg'), 'wd_error');
      return;
    }
    $params['load_more_image_count'] = (isset($params['load_more_image_count']) && ($params['image_enable_page'] == 2)) ? $params['load_more_image_count'] : $params['images_per_page'];
    $items_per_page = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);
    $image_rows = WDWLibrary::get_image_rows_data($params['gallery_id'], $bwg, 'gallery', 'bwg_tag_id_bwg_mosaic_thumbnails_' . $bwg, $params['tag'], $params['images_per_page'], $params['load_more_image_count'], $params['sort_by'], $order_by);
		if (!$from) {
			if ($params['image_enable_page'] && $params['images_per_page']) {
				$page_nav = $image_rows['page_nav'];
			}
		}
    $image_rows = $image_rows['images'];
    $images_count = count($image_rows);
    if (!$image_rows) {
      if ($params['tag']) {
        echo WDWLibrary::message(__('There are no images.', 'bwg'), 'wd_error');
      }
      else {
        echo WDWLibrary::message(__('There are no images in this gallery.', 'bwg'), 'wd_error');
      }
    }
    $tags_rows = WDWLibrary::get_tags_rows_data($params['gallery_id']);
    $image_right_click = $wd_bwg_options->image_right_click;
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->mosaic_thumbs_bg_color);

    $inline_style = $this->inline_styles($bwg, $theme_row, $params);
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
          if ($params['show_search_box']) {
            WDWLibrary::ajax_html_frontend_search_box('gal_front_form_' . $bwg, $bwg, 'bwg_mosaic_thumbnails_' . $bwg, $images_count, $params['search_box_width'], $placeholder);
          }
          if (isset($params['show_sort_images']) && $params['show_sort_images']) {
            WDWLibrary::ajax_html_frontend_sort_box('gal_front_form_' . $bwg, $bwg, 'bwg_mosaic_thumbnails_' . $bwg, $params['sort_by'], $params['search_box_width']);
          }
          if (isset($params['show_tag_box']) && $params['show_tag_box']) {
              WDWLibrary::ajax_html_frontend_search_tags('gal_front_form_' . $bwg, $bwg, 'bwg_mosaic_thumbnails_' . $bwg, $images_count,$tags_rows);
          }
          if ($params['showthumbs_name'] && $gallery_row->name != '') {
            ?>
              <div class="bwg_gal_title_<?php echo $bwg; ?>"><?php echo $gallery_row->name; ?></div>
            <?php
          }
          if ($params['show_gallery_description'] && $gallery_row->name != '') {
            ?>
              <div class="bwg_gal_title_<?php echo $bwg; ?>"><?php echo $gallery_row->description; ?></div>
            <?php
          }
          ?>
          <div id="bwg_mosaic_thumbnails_div_<?php echo $bwg; ?>" style="position:relative; background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>); text-align: <?php echo $theme_row->mosaic_thumb_align; ?>; width: 100%;">
            <div id="ajax_loading_<?php echo $bwg; ?>" style="position:absolute; top:0px;  width: 100%; z-index: 115; text-align: center; height: 100%; vertical-align: middle;">
              <div style="display: table; vertical-align: middle; width: 100%; height: 100%; background-color: #FFFFFF; opacity: 0.7; filter: Alpha(opacity=70);">
                <div style="display: table-cell; text-align: center; position: relative; vertical-align: middle;" >
                  <div id="loading_div_<?php echo $bwg; ?>" class="bwg_spider_ajax_loading" style="display: inline-block; text-align:center; position:relative; vertical-align:middle; background-image:url(<?php echo WD_BWG_URL . '/images/ajax_loader.gif'; ?>); float: none; width:30px;height:30px;background-size:30px 30px;">
                  </div>
                </div>
              </div>
            </div>
            <?php
						if (!$from) {
							if ($params['image_enable_page']  && $params['images_per_page'] && ($theme_row->page_nav_position == 'top')) {
								WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page, $bwg, 'bwg_mosaic_thumbnails_' . $bwg, 0, 'album', $wd_bwg_options->enable_seo, $params['image_enable_page']);
							}
						}
            ?>
            <div id="bwg_mosaic_thumbnails_<?php echo $bwg; ?>" class="bwg_mosaic_thumbnails_<?php echo $bwg; ?>">
              <?php
              foreach ($image_rows as $image_row) {
                $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                $is_embed_video = preg_match('/VIDEO/',$image_row->filetype)==1 ? true :false;
                ?>
                <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox_' . $bwg . '"' . ($wd_bwg_options->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . $WD_BWG_UPLOAD_DIR . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '"') : ($params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                  <div class="bwg_mosaic_thumb_spun_<?php echo $bwg; ?>">
                  <?php
                    if ($play_icon && $is_embed_video) {
                      ?>
                    <span class="bwg_mosaic_play_icon_spun_<?php echo $bwg; ?>">
                       <i title="<?php echo __('Play', 'bwg'); ?>"  class="fa fa-play bwg_mosaic_play_icon_<?php echo $bwg; ?>"></i>
                    </span>
                      <?php
                    }
                    if ($params['image_title'] == 'hover' || $params['image_title'] == 'show') {
                      ?>
                      <span class="bwg_mosaic_title_spun1_<?php echo $bwg; ?>">
                        <span class="bwg_mosaic_title_spun2_<?php echo $bwg; ?>">
                          <?php echo $image_row->alt; ?>
                        </span>
                      </span>
                      <?php
                    }
                    if (defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php') &&$params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id) {
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
                      <img class="bwg_mosaic_thumb_<?php echo $bwg; ?> bwg_img_clear bwg_img_custom" id="<?php echo $image_row->id; ?>" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $image_row->thumb_url; ?>" alt="<?php echo $image_row->alt; ?>" />


                  </div>
                </a>
                <?php
              }
              ?>
            </div>
            <script>
              <?php
              if ($params['mosaic_hor_ver'] == 'vertical') :
                ?>
               /* Vertical.*/
              function bwg_mosaic_<?php echo $bwg; ?>(event_type) {
                /* Resizable mosaic.*/
                <?php
                if ($params['resizable_mosaic'] == 1):
                  ?>
                  if (jQuery(window).width() >= 1920) {
                    var thumbnail_width = (1+jQuery(window).width() / 1920) * <?php echo $params['thumb_width']; ?>;
                  }
                  else if (jQuery(window).width() <= 640) {
                    var thumbnail_width = jQuery(window).width() / 640 * <?php echo $params['thumb_width']; ?>;
                  }
                  else {
                    var thumbnail_width = <?php echo $params['thumb_width']; ?>;
                  }
                  <?php
                else:
                  ?>
                  var thumbnail_width = <?php echo $params['thumb_width']; ?>;
                  <?php
                endif;
                ?>
                /* Initialize.*/
                var mosaic_pics = jQuery(".bwg_mosaic_thumb_<?php echo $bwg; ?>");
                mosaic_pics.each(function (index) {
                  var thumb_w = mosaic_pics.get(index).naturalWidth;
                  var thumb_h = mosaic_pics.get(index).naturalHeight;
                  mosaic_pics.eq(index).height(thumb_h * thumbnail_width/thumb_w);
                  mosaic_pics.eq(index).width(thumbnail_width);
                });
                /* Resize.*/
                var divwidth = jQuery("#bwg_mosaic_thumbnails_div_<?php echo $bwg; ?>").width() / 100 * <?php echo $params['mosaic_total_width'] ?>;
                /* Set absolute width of mosaic.*/
                jQuery("#bwg_mosaic_thumbnails_<?php echo $bwg; ?>").width(divwidth);
                var padding_px = <?php echo $theme_row->mosaic_thumb_padding; ?>;
                var border_px = <?php echo $theme_row->mosaic_thumb_border_width; ?>;
                var border_and_padding= border_px+ padding_px;
                var col_width = thumbnail_width +2*border_and_padding < divwidth ? thumbnail_width : divwidth - 2*border_and_padding;
                var col_number= Math.floor(divwidth / (col_width+2*border_and_padding));
                var col_of_img = [];/*column of the current image*/
                col_of_img[0] = 0;
                var imgs_by_cols = [];/*number of images in each column*/
                /*zero points*/
                var min_top = [];
                for (var x = 0; x < col_number; x++) {
                  min_top[x] = 0;
                  imgs_by_cols[x] = 0;
                }
                var img_wrap_left = 0;
                /*create masonry vertical*/
                mosaic_pics.each(function (index) {
                  var col = 0;
                  var min = min_top[0];
                  for (var x = 0; x < col_number; x++) {
                    if (min > min_top[x]) {
                      min = min_top[x];
                      col = x;
                    }
                  }
                  col_of_img[index] = col;/*store in which col is arranged*/
                  imgs_by_cols[col]++;
                  img_container_top = min;
                  img_container_left = img_wrap_left + col * (col_width + 2 * border_and_padding);
                  mosaic_pics.eq(index).parent().css({top: img_container_top, left: img_container_left});
                  min_top[col] += mosaic_pics.eq(index).height() + 2 * border_and_padding;
                });
                /*create mosaics*/
                var stretch = [];
                stretch[0] = 1;
                var sum_col_width = 0;
                var sum_col_height = [];/*array to store height of every column*/
                /*solve equations to calculate stretch[col] factors*/
                var axbx = 0;
                var axpxbx = 0;
                for (var x = 0; x < col_number; x++) {
                  sum_col_width += col_width;
                  sum_col_height[x] = 0;
                  mosaic_pics.each(function (index) {
                    if (col_of_img[index] == x) {
                      sum_col_height[x] += mosaic_pics.eq(index).height();
                    }
                  });
                  if (sum_col_height[x] != 0) {
                    axbx += col_width / sum_col_height[x];
                    axpxbx += col_width * imgs_by_cols[x] * 2 * border_and_padding / sum_col_height[x];
                  }
                }
                var common_height = 0;
                if (axbx != 0) {
                  common_height = (sum_col_width + axpxbx) / axbx;
                }
                for (var x = 0; x < col_number; x++) {
                  if (sum_col_height[x] != 0) {
                    stretch[x] = (common_height - imgs_by_cols[x] * 2 * border_and_padding ) / sum_col_height[x];
                  }
                }
                var img_container_left = [];
                /*position.left of every column*/
                img_container_left[0] = img_wrap_left;
                for (var x = 1; x <= col_number; x++) {
                  img_container_left[x] = img_container_left[x - 1] + col_width * stretch[x - 1] + 2 * border_and_padding;
                }
                /*reset min_top array to the position.top of #wrap container*/
                var img_container_top = [];
                for (var x = 0; x < col_number; x++) {
                  img_container_top[x] = 0;
                }
                /*stretch and shift to create mosaic verical*/
                var last_img_index = [];
                /* last image in column*/
                last_img_index[0] = 0;
                mosaic_pics.each(function (index) {
                  var thumb_w = mosaic_pics.eq(index).width();
                  var thumb_h = mosaic_pics.eq(index).height();
                  mosaic_pics.eq(index).width(thumb_w * stretch[col_of_img[index]]);
                  mosaic_pics.eq(index).height(thumb_h * stretch[col_of_img[index]]);
                  mosaic_pics.eq(index).parent().css({
                    top: img_container_top[col_of_img[index]],
                    left: img_container_left[col_of_img[index]]
                  });
                  img_container_top[col_of_img[index]] += thumb_h * stretch[col_of_img[index]] + 2 * border_and_padding;
                  last_img_index[col_of_img[index]] = index;
                });
                jQuery("#bwg_mosaic_thumbnails_<?php echo $bwg; ?>").width(img_container_left[col_number]);
                jQuery("#bwg_mosaic_thumbnails_<?php echo $bwg; ?>").height(img_container_top[0]);
                /*IMPORTANT!*/
                jQuery(".bwg_mosaic_thumbnails_<?php echo $bwg; ?>").css({visibility: 'visible'});
                jQuery(".tablenav-pages_<?php echo $bwg; ?>").css({visibility: 'visible'});
                jQuery("#ajax_loading_<?php echo $bwg; ?>").css({display: 'none'});
                show_title_always_show_<?php echo $bwg; ?>();
                show_mosaic_play_icons_<?php echo $bwg; ?>();
              }  /* end of function bwg_mosaic_ for vertical*/
              <?php
            else :
              ?>
              /* horizontal mosaic*/
              function bwg_mosaic_<?php echo $bwg; ?>(event_type) {
                /*resizable mosaic*/
                <?php
                if ($params['resizable_mosaic'] == 1):
                  ?>
                  if (jQuery(window).width() >= 1920) {
                    var thumbnail_height = (1+jQuery(window).width()/1920)*<?php echo $params['thumb_height']; ?>;
                  }
                  else if (jQuery(window).width() <= 640) {
                    var thumbnail_height = jQuery(window).width()/640*<?php echo $params['thumb_height']; ?>;
                  }
                  else {
                    var thumbnail_height = <?php echo $params['thumb_height']; ?>;
                  }
                  <?php
                else:
                  ?>
                  var thumbnail_height = <?php echo $params['thumb_height']; ?>;
                  <?php
                endif;
                ?>
                /* initialize */
                var mosaic_pics = jQuery(".bwg_mosaic_thumb_<?php echo $bwg; ?>");
                mosaic_pics.each(function (index) {
                  var thumb_w = mosaic_pics.get(index).naturalWidth;
                  var thumb_h = mosaic_pics.get(index).naturalHeight;
                  thumb_w = thumb_w * thumbnail_height / thumb_h;
                  mosaic_pics.eq(index).height(thumbnail_height);
                  mosaic_pics.eq(index).width(thumb_w);
                });
                /* resize */
                var divwidth = jQuery("#bwg_mosaic_thumbnails_div_<?php echo $bwg; ?>").width() / 100 * <?php echo $params['mosaic_total_width'] ?>;
                /*set absolute mosaic width*/
                jQuery("#bwg_mosaic_thumbnails_<?php echo $bwg; ?>").width(divwidth);
                var padding_px = <?php echo $theme_row->mosaic_thumb_padding; ?>;
                var border_px = <?php echo $theme_row->mosaic_thumb_border_width; ?>;
                var border_and_padding = border_px + padding_px;
                var row_height = thumbnail_height + 2 * border_and_padding;
                var row_number = 0;
                var row_of_img = [];
                /* row of the current image*/
                row_of_img[0] = 0;
                var imgs_by_rows = [];
                /* number of images in each row */
                imgs_by_rows[0] = 0;
                var row_cum_width = 0;
                /* width of the current row */
                /* create masonry horizontal */
                mosaic_pics.each(function (index) {
                  row_cum_width2 = row_cum_width + mosaic_pics.eq(index).width() + 2 * border_and_padding;
                  if (row_cum_width2 - divwidth < 0) { /* add the image to the row */
                    row_cum_width = row_cum_width2;
                    row_of_img[index] = row_number;
                    imgs_by_rows[row_number]++;
                  }
                  else {
                    if (index !== mosaic_pics.length - 1) { /* if not last element */
                      if ((Math.abs(row_cum_width - divwidth) > Math.abs(row_cum_width2 - divwidth)) || !(!(Math.abs(row_cum_width - divwidth) <= Math.abs(row_cum_width2 - divwidth)) || !(imgs_by_rows[row_number] == 0))) {
                        if (index !== mosaic_pics.length - 2) { /* add and shrink if not the second */
                          row_cum_width = row_cum_width2;
                          row_of_img[index] = row_number;
                          imgs_by_rows[row_number]++;
                          row_number++;
                          imgs_by_rows[row_number] = 0;
                          row_cum_width = 0;
                        }
                        else { /* add second but NOT shrink and not change row */
                          row_cum_width = row_cum_width2;
                          row_of_img[index] = row_number;
                          imgs_by_rows[row_number]++;
                        }
                      }
                      else { /* add to new row and  stretch prev row (or shrink if even one pic is big) */
                        row_number++;
                        imgs_by_rows[row_number] = 1;
                        row_of_img[index] = row_number;
                        row_cum_width = row_cum_width2 - row_cum_width;
                      }
                    }
                    else { /* if the last element, add and shrink */
                      row_cum_width = row_cum_width2;
                      row_of_img[index] = row_number;
                      imgs_by_rows[row_number]++;
                    }
                  }
                });
                /* create mosaics */
                var stretch = [];
                /* stretch[row] factors */
                var row_new_height = [];
                /* array to store height of every column */
                for (var row = 0; row <= row_number; row++) {
                  stretch[row] = 1;
                  row_new_height[row] = row_height;
                }
                /* find stretch factors */
                for (var row = 0; row <= row_number; row++) {
                  row_cum_width = 0;
                  mosaic_pics.each(function (index) {
                    if (row_of_img[index] == row) {
                      row_cum_width += mosaic_pics.eq(index).width();
                    }
                  });
                  stretch[row] = x = (divwidth - imgs_by_rows[row] * 2 * border_and_padding) / row_cum_width;
                  row_new_height[row] = (row_height - 2 * border_and_padding) * stretch[row] + 2 * border_and_padding;
                }
                /* stretch and shift to create mosaic horizontal */
                var last_img_index = [];
                /* last image in row */
                last_img_index[0] = 0;
                /* zero points */
                var img_left = [];
                var row_top = [];
                img_left[0] = 0;
                row_top[0] = 0;
                for (var row = 1; row <= row_number; row++) {
                  img_left[row] = img_left[0];
                  row_top[row] = row_top[row - 1] + row_new_height[row - 1];
                }
                mosaic_pics.each(function (index) {
                  var thumb_w = mosaic_pics.eq(index).width();
                  var thumb_h = mosaic_pics.eq(index).height();
                  mosaic_pics.eq(index).width(thumb_w * stretch[row_of_img[index]]);
                  mosaic_pics.eq(index).height(thumb_h * stretch[row_of_img[index]]);
                  mosaic_pics.eq(index).parent().css({
                    top: row_top[row_of_img[index]],
                    left: img_left[row_of_img[index]]
                  });
                  img_left[row_of_img[index]] += thumb_w * stretch[row_of_img[index]] + 2 * border_and_padding;
                  last_img_index[row_of_img[index]] = index;
                });
                jQuery("#bwg_mosaic_thumbnails_<?php echo $bwg; ?>").height(row_top[row_number] + row_new_height[row_number] - row_top[0]);
                /* IMPORTANT! */
                jQuery(".bwg_mosaic_thumbnails_<?php echo $bwg; ?>").css({visibility: 'visible'});
                jQuery(".tablenav-pages_<?php echo $bwg; ?>").css({visibility: 'visible'});
                jQuery("#ajax_loading_<?php echo $bwg; ?>").css({display: 'none'});
                show_title_always_show_<?php echo $bwg; ?>();
                show_mosaic_play_icons_<?php echo $bwg; ?>();
              } /* end of function bwg_mosaic_ horizontal*/
              <?php
            endif;
            ?>
            jQuery(window).load(function() {
              bwg_mosaic_<?php echo $bwg; ?>("load");
            });
            jQuery(window).resize(function() {
              bwg_mosaic_<?php echo $bwg; ?>("resize");
            });
            jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?> img").error(function() {
              jQuery(this).height(100);
              jQuery(this).width(100);
            });
            function bwg_mosaic_ajax_<?php echo $bwg; ?>(tot_cccount_mosaic_ajax){
                var cccount_mosaic_ajax = 0;
                jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?> img").on("load", function() {
                  if (++cccount_mosaic_ajax >= tot_cccount_mosaic_ajax) {
                    window["bwg_mosaic_<?php echo $bwg; ?>"]("load");
                  }
                });
                jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?> img").error(function() {
                  jQuery(this).height(100);
                  jQuery(this).width(100);
                  if (++cccount_mosaic_ajax >= tot_cccount_mosaic_ajax) {
                    bwg_mosaic_<?php echo $bwg; ?>("load");
                  }
                });
              }
            <?php
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
              ?>
              /* If page is called by AJAX use this instead of window.load.*/
              bwg_mosaic_ajax_<?php echo $bwg; ?>(0);
            <?php
            endif;
            if ($params['image_title'] == 'hover'):
              ?>
            jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?>").on("mouseenter", function() {
              var img_w = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").width();
              var img_h = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").height();
              jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").width(img_w);
              var title_w = jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").width();
              var title_h = jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").height();
              var paddings_and_borders = <?php echo $theme_row->mosaic_thumb_padding; ?> + <?php echo $theme_row->mosaic_thumb_border_width; ?> ;
              jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").css({
                top: paddings_and_borders + 0.5 * img_h - 0.5 * title_h,
                left: paddings_and_borders +  0.5 * img_w - 0.5 * title_w,
                'opacity': 1,
                'filter': 'Alpha(opacity=100)'
              });
            });
            jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?>").on("mouseleave", function() {
            jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").css({ top: 0, left: -10000,'opacity': 0,
                  'filter': 'Alpha(opacity=0)','padding': '<?php echo $theme_row->mosaic_thumb_title_margin; ?>'});
            });

            <?php
            endif;
             if (defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php') && $params['ecommerce_icon'] == 'hover'):
            ?>
            jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?>").on("mouseenter", function() {
              var img_w = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").width();
              var img_h = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").height();
              jQuery(this).children(".bwg_ecommerce_spun1_<?php echo $bwg; ?>").width(img_w);
              var title_w = jQuery(this).children(".bwg_ecommerce_spun1_<?php echo $bwg; ?>").width();
              var title_h = jQuery(this).children(".bwg_ecommerce_spun1_<?php echo $bwg; ?>").height();
              var paddings_and_borders = <?php echo $theme_row->mosaic_thumb_padding; ?> + <?php echo $theme_row->mosaic_thumb_border_width; ?> ;
              jQuery(this).children(".bwg_ecommerce_spun1_<?php echo $bwg; ?>").css({
                top: paddings_and_borders + 0.5 * img_h - 0.5 * title_h,
                left: paddings_and_borders +  0.5 * img_w - 0.5 * title_w,
                'opacity': 1,
                'filter': 'Alpha(opacity=100)'
              });
            });
            jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?>").on("mouseleave", function() {
            jQuery(this).children(".bwg_ecommerce_spun1_<?php echo $bwg; ?>").css({ top: 0, left: -10000,'opacity': 0,
                  'filter': 'Alpha(opacity=0)','padding': '<?php echo $theme_row->mosaic_thumb_title_margin; ?>'});
            });
            <?php
            endif;
            ?>
            function show_title_always_show_<?php echo $bwg; ?>() {
              <?php
            if ($params['image_title'] == 'show') :
              ?>
              jQuery(".bwg_mosaic_thumb_spun_<?php echo $bwg; ?>").each(function() {
                var img_w = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").width();
                var img_h = jQuery(this).children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").height();
                jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").width(img_w);
                var title_w = jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").width();
                var title_h = jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").height();
                var paddings_and_borders = <?php echo $theme_row->mosaic_thumb_padding; ?> + <?php echo $theme_row->mosaic_thumb_border_width; ?> ;
                jQuery(this).children(".bwg_mosaic_title_spun1_<?php echo $bwg; ?>").css({
                  top: paddings_and_borders + 0.5 * img_h - 0.5 * title_h,
                  left: paddings_and_borders +  0.5 * img_w - 0.5 * title_w,
                  'opacity': 1,
                  'filter': 'Alpha(opacity=100)',
                  'padding': '<?php echo $theme_row->mosaic_thumb_title_margin; ?>'
                });
              });
              <?php
              endif;
              ?>
              /*if not $params['image_title'] == 'show', function body is empty*/
            }
            function show_mosaic_play_icons_<?php echo $bwg; ?>() {
              <?php
              if ($play_icon):
              ?>
              jQuery(".bwg_mosaic_play_icon_spun_<?php echo $bwg; ?>").each(function() {
                var img_w = jQuery(this).parent().children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").width();
                var img_h = jQuery(this).parent().children(".bwg_mosaic_thumb_<?php echo $bwg; ?>").height();
                var paddings_and_borders = <?php echo $theme_row->mosaic_thumb_padding; ?> + <?php echo $theme_row->mosaic_thumb_border_width; ?>;
                jQuery(this).css({
                  top: paddings_and_borders + 0.5 * img_h -0.5 *  jQuery(this).height(),
                  left: paddings_and_borders +  0.5 * img_w - 0.5 *  jQuery(this).width(),
                  'opacity': 1,
                   'filter': 'Alpha(opacity=100)'
                });
              });
              <?php
              endif;
              ?>
            }
            </script>
            <?php
            if ( $gallery_download && $image_rows ) {
              $query_url = addslashes(add_query_arg(array(
                                                      "action" => "download_gallery",
                                                      "gallery_id" => $params['gallery_id'],
                                                    ), admin_url('admin-ajax.php')));
              ?>
              <div class="bwg_download_gallery">
                <a href="<?php echo $query_url; ?>">
                  <i title="<?php _e('Download gallery', 'bwg'); ?>" class="bwg_ctrl_btn fa fa-download"></i>
                </a>
              </div>
              <?php
            }
            if ( !$from ) {
              if ( $params['image_enable_page'] && $params['images_per_page'] && ($theme_row->page_nav_position == 'bottom') ) {
                WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page, $bwg, 'bwg_mosaic_thumbnails_' . $bwg, 0, 'album', $wd_bwg_options->enable_seo, $params['image_enable_page']);
              }
            }
            ?>
          </div>
        </form>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      <?php
        $params_array = array(
          'action' => 'GalleryBox',
          'tags' => (isset($params['tag']) ? $params['tag'] : 0),
          'current_view' => $bwg,
          'gallery_id' => $params['gallery_id'],
          'theme_id' => $params['theme_id'],
          'thumb_width' => $params['thumb_width'],
          'thumb_height' => $params['thumb_height'],
          'open_with_fullscreen' => $params['popup_fullscreen'],
          'open_with_autoplay' => $params['popup_autoplay'],
          'image_width' => $params['popup_width'],
          'image_height' => $params['popup_height'],
          'image_effect' => $params['popup_effect'],
          'wd_sor' => (isset($params['type']) ? 'date' : (($params['sort_by'] == 'RAND()') ? 'order' : $params['sort_by'])),
          'wd_ord' => $order_by,
          'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
          'image_filmstrip_height' => $params['popup_filmstrip_height'],
          'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
          'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
          'popup_enable_info' => $params['popup_enable_info'],
          'popup_info_always_show' => $params['popup_info_always_show'],
          'popup_info_full_width' => $params['popup_info_full_width'],
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
      ?>
      function bwg_gallery_box_<?php echo $bwg; ?>(image_id, openEcommerce) {
        if (typeof openEcommerce == undefined) {
          openEcommerce = false;
        }
        var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";
        var filterTags = jQuery("#bwg_tags_id_bwg_mosaic_thumbnails_<?php echo $bwg; ?>" ).val() ? jQuery("#bwg_tags_id_bwg_mosaic_thumbnails_<?php echo $bwg; ?>" ).val() : 0;
        var filtersearchname = jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() ? "&filter_search_name_<?php echo $bwg; ?>=" + jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() : '';
        spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>&image_id=' + image_id + "&filter_tag_<?php echo $bwg; ?>=" +  filterTags + ecommerce + filtersearchname, '<?php echo $bwg; ?>', '<?php echo $params['popup_width']; ?>', '<?php echo $params['popup_height']; ?>', 1, 'testpopup', 5, "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>");
      }
      var bwg_hash = window.location.hash.substring(1);
      if (bwg_hash) {
        if (bwg_hash.indexOf("bwg") != "-1") {
          bwg_hash_array = bwg_hash.replace("bwg", "").split("/");
          if(bwg_hash_array[0] == "<?php echo $params_array['gallery_id']; ?>"){
            bwg_gallery_box_<?php echo $bwg; ?>(bwg_hash_array[1]);
          }
        }
      }
      function bwg_document_ready_<?php echo $bwg; ?>() {
        var bwg_touch_flag = false;
        jQuery(".bwg_lightbox_<?php echo $bwg; ?>").on("click", function () {
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            bwg_gallery_box_<?php echo $bwg; ?>(jQuery(this).attr("data-image-id"));
            return false;
          }
        });
		    jQuery(".bwg_lightbox_<?php echo $bwg; ?> .bwg_ecommerce").on("click", function (event) {
          event.stopPropagation();
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
		      	var image_id = jQuery(this).closest(".bwg_lightbox_<?php echo $bwg; ?>").attr("data-image-id");
            bwg_gallery_box_<?php echo $bwg; ?>(image_id, true);
            return false;
          }
        });
        <?php
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

  private function inline_styles($bwg, $theme_row, $params) {
    ob_start();
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->mosaic_thumbs_bg_color);
    ?>
      /*
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumbnails_<?php echo $bwg; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }

      */
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_<?php echo $bwg; ?> {
        display: block;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;
        border-radius: <?php echo $theme_row->mosaic_thumb_border_radius; ?>;
        border: <?php echo $theme_row->mosaic_thumb_border_width; ?>px <?php echo $theme_row->mosaic_thumb_border_style; ?> #<?php echo $theme_row->mosaic_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->mosaic_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->mosaic_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->mosaic_thumb_transparent; ?>);
        z-index: 100;

      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> {
        visibility: hidden;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumbnails_<?php echo $bwg; ?> {
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        position: relative;
        text-align: <?php echo $theme_row->mosaic_thumb_align; ?>;
        display:inline-block;
        visibility: hidden;
      }

      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?> {
        display:block;
        position: absolute;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;
        <?php echo ($theme_row->mosaic_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }


      /*image title styles*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_title_spun1_<?php echo $bwg; ?>,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?> {
         position: absolute;
          display:block;
          opacity: 0;
        filter: Alpha(opacity=0);
          left: -10000px;
      	  top: 0px;
      	  box-sizing:border-box;
          text-align: center;


        }

      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_title_spun2_<?php echo $bwg; ?>,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-family: <?php echo $theme_row->mosaic_thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->mosaic_thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->mosaic_thumb_title_font_weight; ?>;
        text-shadow: <?php echo $theme_row->mosaic_thumb_title_shadow; ?>;
        vertical-align: middle;
        word-wrap: break-word;
      }
      <?php
       if(defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php') && $params['ecommerce_icon'] == 'hover'){
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?> i{
            opacity:1 !important;
            font-size:20px !important;
            z-index: 45;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?>:hover img{
            opacity:0.5;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?>:hover{
            background:#000;
        }
        <?php
      }
        ?>
      .bwg_mosaic_play_icon_spun_<?php echo $bwg; ?>  {
        display: table;
        position: absolute;
        left: -10000px;
        top: 0px;
        opacity: 0;
        filter: Alpha(opacity=0);
      }
      .bwg_mosaic_play_icon_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->mosaic_thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }

      /*pagination styles*/
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
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_title_<?php echo $bwg; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->mosaic_thumb_gal_title_font_color; ?>;
        display: block;
        font-family: <?php echo $theme_row->mosaic_thumb_gal_title_font_style; ?>;
        font-size: <?php echo $theme_row->mosaic_thumb_gal_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->mosaic_thumb_gal_title_font_weight; ?>;
        padding: <?php echo $theme_row->mosaic_thumb_gal_title_margin; ?>;
        text-shadow: <?php echo $theme_row->mosaic_thumb_gal_title_shadow; ?>;
        text-align: <?php echo $theme_row->mosaic_thumb_gal_title_align; ?>;
      }
    <?php
    return ob_get_clean();
  }
}