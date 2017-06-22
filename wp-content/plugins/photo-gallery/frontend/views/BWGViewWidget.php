<?php
class BWGViewWidgetFrontEnd {
  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function view_tags($params) {
    global $wp;
    $current_url = $wp->query_string;
    global $WD_BWG_UPLOAD_DIR;
    global $bwg;

    $type = isset($params["type"]) ? $params["type"] : 'text';
    $show_name = isset($params["show_name"]) ? $params["show_name"] : 0;
    $open_option = isset($params["open_option"]) ? $params["open_option"] : 'page';
    $count = isset($params["count"]) ? $params["count"] : 0;
    $width = isset($params["width"]) ? $params["width"] : 250;
    $height = isset($params["height"]) ? $params["height"] : 250;
    $background_transparent = isset($params["background_transparent"]) ? $params["background_transparent"] : 1;
    $background_color = isset($params["background_color"]) ? $params["background_color"] : "000000";
    $text_color = isset($params["text_color"]) ? $params["text_color"] : "ffffff";
    $theme_id = isset($params["theme_id"]) ? $params["theme_id"] : 0;

    $tags = $this->model->get_tags_data($count);
    $theme_row = WDWLibrary::get_theme_row_data($theme_id);
    global $wd_bwg_options;
    ob_start();
    ?>
      @media screen and (max-width: <?php echo $width ?>px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> { 
          display: none;
        }
      }
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> {
				width:<?php echo $width ?>px;
				height:<?php echo $height ?>px;
				margin:0 auto;
				overflow: hidden;
				position: relative;
        background-color: <?php echo $background_transparent ? 'transparent' : '#' . $background_color ?>;
        color: #<?php echo $text_color ?> !important;
        max-width: 100%;
			}
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> ul {
				list-style-type: none;
			}
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> ul li:before {
				content: "";
			}
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> ul li a {
				color: inherit !important;
			}
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> .bwg_link_widget {
        text-decoration: none;
        color: #<?php echo $text_color ?> !important;
        cursor: pointer;
        font-size: inherit !important;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
		<?php
    $inline_style = ob_get_clean();
    if ($wd_bwg_options->use_inline_stiles_and_scripts) {
      wp_enqueue_style('bwg_frontend');
      wp_add_inline_style('bwg_frontend', $inline_style);
      wp_enqueue_style('bwg_font-awesome');
      wp_enqueue_style('bwg_mCustomScrollbar');
      $google_fonts = WDWLibrary::get_google_fonts();
      wp_enqueue_style('bwg_googlefonts');
      if (!wp_script_is('bwg_frontend', 'done')) {
        wp_print_scripts('bwg_frontend');
      }
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
      if (!wp_script_is('bwg_jquery_mobile', 'done')) {
        wp_print_scripts('bwg_jquery_mobile');
      }
      if (!wp_script_is('bwg_3DEngine', 'done')) {
        wp_print_scripts('bwg_3DEngine');
      }
      if (!wp_script_is('bwg_Sphere', 'done')) {
        wp_print_scripts('bwg_Sphere');
      }
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>

    <script type="text/javascript">
      jQuery(document).ready(function() {
        var container = jQuery("#tags_cloud_item_<?php echo $bwg; ?>")
        var camera = new Camera3D();
        camera.init(0, 0, 0, (container.width() + container.height()) / 2);
        var tags_cloud_item = new Object3D(container);
        radius = (container.height() > container.width() ? container.width() : container.height());
        tags_cloud_item.addChild(new Sphere(radius * 0.35, <?php echo sqrt(count($tags)) ?>, <?php echo count($tags) ?>));
        var scene = new Scene3D();
        scene.addToScene(tags_cloud_item);        
        var mouseX = 20;
        var mouseY = 30;
        var offsetX = container.offset().left;
        var offsetY = container.offset().top;
        var speed = 6000;
        container.mousemove(function(e){
         offsetX = container.offset().left;
         offsetY = container.offset().top;
         mouseX = (e.clientX + jQuery(window).scrollLeft() - offsetX - (container.width() / 2)) % container.width();
         mouseY = (e.clientY + jQuery(window).scrollTop() - offsetY - (container.height() / 2)) % container.height();
       });


        var animateIt = function(){
          if (mouseX != undefined){
            axisRotation.y += (mouseX) / speed;
          }
          if (mouseY != undefined){
            axisRotation.x -= mouseY / speed;
          }
          scene.renderCamera(camera);          
        };
        setInterval(animateIt, 60/*was 20*/);
      });
      jQuery(window).load(function () {
        jQuery("#tags_cloud_item_<?php echo $bwg; ?>").attr("style", "visibility: visible;");
      });
    </script>
    <div id="bwg_container1_<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <div id="tags_cloud_item_<?php echo $bwg; ?>" style="visibility: hidden;">
          <ul>
          <?php
            foreach ($tags as $tag) {
              if ($open_option == 'lightbox') {
                $params_array = array(
                  'tags' => $tag->term_id,
                  'action' => 'GalleryBox',
                  'open_with_fullscreen' => $wd_bwg_options->popup_fullscreen,
                  'open_with_autoplay' => $wd_bwg_options->popup_autoplay,
                  'current_view' => $bwg,
                  'image_id' => $tag->image_id,
                  'theme_id' => $theme_id,
                  'thumb_width' => $wd_bwg_options->thumb_width,
                  'thumb_height' => $wd_bwg_options->thumb_height,
                  'image_width' => $wd_bwg_options->popup_width,
                  'image_height' => $wd_bwg_options->popup_height,
                  'image_effect' => $wd_bwg_options->popup_type,
                  'wd_sor' => 'order',
                  'enable_image_filmstrip' => $wd_bwg_options->popup_enable_filmstrip,
                  'image_filmstrip_height' => $wd_bwg_options->popup_filmstrip_height,
                  'enable_image_ctrl_btn' => $wd_bwg_options->popup_enable_ctrl_btn,
                  'enable_image_fullscreen' => $wd_bwg_options->popup_enable_fullscreen,
                  'popup_enable_info' => $wd_bwg_options->popup_enable_info,
                  'popup_info_always_show' => $wd_bwg_options->popup_info_always_show,
                  'popup_hit_counter' => $wd_bwg_options->popup_hit_counter,
                  'popup_enable_rate' => $wd_bwg_options->popup_enable_rate,
                  'thumb_click_action' => $wd_bwg_options->thumb_click_action,
                  'slideshow_interval' => $wd_bwg_options->popup_interval,
                  'enable_comment_social' => $wd_bwg_options->popup_enable_comment,
                  'enable_image_facebook' => $wd_bwg_options->popup_enable_facebook,
                  'enable_image_twitter' => $wd_bwg_options->popup_enable_twitter,
                  'enable_image_google' => $wd_bwg_options->popup_enable_google,
                  'enable_image_pinterest' => $wd_bwg_options->popup_enable_pinterest,
                  'enable_image_tumblr' => $wd_bwg_options->popup_enable_tumblr,
                  'watermark_type' => $wd_bwg_options->watermark_type
                );
                if ($params_array['watermark_type'] != 'none') {
                  $params_array['watermark_link'] = $wd_bwg_options->watermark_link;
                  $params_array['watermark_opacity'] = $wd_bwg_options->watermark_opacity;
                  $params_array['watermark_position'] = $wd_bwg_options->watermark_position;
                }
                if ($params_array['watermark_type'] == 'text') {
                  $params_array['watermark_text'] = $wd_bwg_options->watermark_text;
                  $params_array['watermark_font_size'] = $wd_bwg_options->watermark_font_size;
                  $params_array['watermark_font'] = $wd_bwg_options->watermark_font;
                  $params_array['watermark_color'] = $wd_bwg_options->watermark_color;
                }
                elseif ($params_array['watermark_type'] == 'image') {
                  $params_array['watermark_url'] = $wd_bwg_options->watermark_url;
                  $params_array['watermark_width'] = $wd_bwg_options->watermark_width;
                  $params_array['watermark_height'] = $wd_bwg_options->watermark_height;
                }
                $params_array['current_url'] = $current_url;
                if ($type == 'text') {
                  ?>
                  <li><a class="bwg_link_widget" onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwg; ?>', '800', '600', 1, 'testpopup', 5, '<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>'); return false;"><?php echo $tag->name; ?></a></li>
                  <?php
                }
                else {
                  
                  $is_embed = preg_match('/EMBED/',$tag->filetype)==1 ? true :false;

                  ?>
                  <li style="text-align: center;">
                    <a class="bwg_link_widget" onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwg; ?>', '800', '600', 1, 'testpopup', 5, '<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>'); return false;">
                      <img id="imgg" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
              else {
                if ($type == 'text') {
                  ?>
                  <li><a class="tag_cloud_link" href="<?php echo $tag->permalink; ?>"><?php echo $tag->name; ?></a></li>
                  <?php
                }
                else {
                  $is_embed = preg_match('/EMBED/', $tag->filetype) == 1 ? true : false;
                  ?>
                  <li style="text-align: center;">
                    <a class="bwg_link_widget" href="<?php echo $tag->permalink; ?>">
                      <img id="imgg" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name; ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
            }
          ?>
          </ul>
        </div>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <?php
  }
}