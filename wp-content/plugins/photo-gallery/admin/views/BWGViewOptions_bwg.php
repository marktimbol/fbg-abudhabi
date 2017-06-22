<?php

class BWGViewOptions_bwg {
  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display($reset = FALSE) {
    if (isset($_GET['bwg_start_tour']) && $_GET['bwg_start_tour'] == '1') {
      update_user_meta(get_current_user_id(), 'bwg_photo_gallery', '1');
      WDWLibrary::spider_redirect('admin.php?page=options_bwg');
    }
    global $WD_BWG_UPLOAD_DIR;
    global $wd_bwg_fb;
    ?>
    <script>
      function bwg_add_music(files) {
        document.getElementById("slideshow_audio_url").value = files[0]['url'];
      }
      function bwg_add_built_in_watermark_image(files) {
        document.getElementById("built_in_watermark_url").value = '<?php echo site_url() . '/' . $WD_BWG_UPLOAD_DIR; ?>' + files[0]['url'];
      }
      function bwg_add_watermark_image(files) {
        document.getElementById("watermark_url").value = '<?php echo site_url() . '/' . $WD_BWG_UPLOAD_DIR; ?>' + files[0]['url'];
      }
    </script>
    <?php
    $row = new WD_BWG_Options($reset);
    if (!$row) {
      echo WDWLibrary::message_id(2);
      return;
    }
    $permissions = array(
      'manage_options' => 'Administrator',
      'moderate_comments' => 'Editor',
      'publish_posts' => 'Author',
      'edit_posts' => 'Contributor',
    );

    $facebook_app_id = $row->facebook_app_id;
    $facebook_app_secret = $row->facebook_app_secret;	
    $this->model->get_facebook_data($facebook_app_id, $facebook_app_secret);

    $built_in_watermark_fonts = array();
    foreach (scandir(path_join(WD_BWG_DIR, 'fonts')) as $filename) {
			if (strpos($filename, '.') === 0) continue;
			else $built_in_watermark_fonts[] = $filename;
		}
    $watermark_fonts = array(
      'arial' => 'Arial',
      'Lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' =>'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    $effects = array(
      'none' => 'None',
      'cubeH' => 'Cube Horizontal',
      'cubeV' => 'Cube Vertical',
      'fade' => 'Fade',
      'sliceH' => 'Slice Horizontal',
      'sliceV' => 'Slice Vertical',
      'slideH' => 'Slide Horizontal',
      'slideV' => 'Slide Vertical',
      'scaleOut' => 'Scale Out',
      'scaleIn' => 'Scale In',
      'blockScale' => 'Block Scale',
      'kaleidoscope' => 'Kaleidoscope',
      'fan' => 'Fan',
      'blindH' => 'Blind Horizontal',
      'blindV' => 'Blind Vertical',
      'random' => 'Random',
    );
    ?>
    <form method="post" class="wrap bwg_form" action="admin.php?page=options_bwg" style="width: 98%; float: left;">
      <?php wp_nonce_field( 'options_bwg', 'bwg_nonce' ); ?>
      <div>
        <span class="option-icon"></span>
        <h2 id="ed_options"><?php _e('Edit options', 'bwg_back'); ?></h2>
      </div>
      <div style="display: inline-block; width: 100%;">
        <div style="float: right;">
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" id="bwg_save_options" type="submit" onclick=" spider_set_input_value('task', 'save')" value="<?php _e('Save', 'bwg_back'); ?>" />
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-reset" type="submit" onclick="if (confirm('<?php echo addslashes(__('Do you want to reset to default?', 'bwg_back')); ?>')) {
                                                                 spider_set_input_value('task', 'reset');
                                                               } else {
                                                                 return false;
                                                               }" value="<?php _e('Reset all options', 'bwg_back'); ?>" />
        </div>
      </div>
      <div class="tab_conteiner">
				<div class="tab_button_wrap option_tab_button_wrap" id="bwg_options" onclick="bwg_change_tab('bwg_options_box');return false;">
					<a id="bwg_standart" class="wd-btn_tabs wd-btn-primary-tabs wd-not-image_gray">
						<div class="bwg_tab_label"><?php _e('Global options', 'bwg_back'); ?></div>
					</a>
				</div>
        <div class="tab_button_wrap default_tab_button_wrap" id="bwg_default_option" onclick="bwg_change_tab('bwg_default_box');return false;">
					<a id="bwg_default" class="wd-btn_tabs wd-btn-primary-tabs wd-not-image_gray">
            <div class="bwg_tab_label">
              <?php _e('Shortcode defaults', 'bwg_back'); ?>
              <small class="bwg_tab_desc"><?php _e('Applies to new shortcodes only', 'bwg_back'); ?></small>
            </div>
				  </a>
			  </div>
        <input type="hidden" id="type_option" name="type_option" value="<?php echo (isset($_POST["type_option"]) ? esc_html(stripslashes($_POST["type_option"])) : "bwg_default_box"); ?>" />
      </div>
      <div class="bwg_default_box default_option">
        <div style="display: none; width: 100%;" id="display_default_option_panel">
          <div class="options_tab">
            <div id="div_8" class="gallery_type" onclick="bwg_change_option_type('8')"> <?php echo __('Thumbnail options', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_9" class="gallery_type" onclick="bwg_change_option_type('9')"> <?php echo __('Lightbox', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_10" class="gallery_type" onclick="bwg_change_option_type('10')"> <?php echo __('Slideshow', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_11" class="gallery_type" onclick="bwg_change_option_type('11')"> <?php echo __('Album options', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_12" class="gallery_type" onclick="bwg_change_option_type('12')"> <?php echo __('Image options', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_13" class="gallery_type" onclick="bwg_change_option_type('13')"> <?php echo __('Carousel', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_14" class="gallery_type" onclick="bwg_change_option_type('14')"> <?php echo __('Advertisement', 'bwg_back'); ?></div>
            <input type="hidden" id="type_def" name="type_def" value="<?php echo (isset($_POST["type_def"]) ? esc_html(stripslashes($_POST["type_def"])) : "8"); ?>"/>
          </div>
          <!--Thumbnail options-->
          <div class="spider_div_options" id="div_content_8">        
            <table>
              <tbody>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Masonry:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="masonry" id="masonry_0" value="vertical" <?php if ($row->masonry == "vertical") echo 'checked="checked"'; ?> /><label for="masonry_0"><?php echo __('Vertical', 'bwg_back'); ?></label>
                    <input type="radio" name="masonry" id="masonry_1" value="horizontal" <?php if ($row->masonry == "horizontal") echo 'checked="checked"'; ?> /><label for="masonry_1"><?php echo __('Horizontal', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Mosaic:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="mosaic" id="mosaic_0" value="vertical" <?php if ($row->mosaic == "vertical") echo 'checked="checked"'; ?> /><label for="mosaic_0"><?php echo __('Vertical', 'bwg_back'); ?></label>
                    <input type="radio" name="mosaic" id="mosaic_1" value="horizontal" <?php if ($row->mosaic == "horizontal") echo 'checked="checked"'; ?> /><label for="mosaic_1"><?php echo __('Horizontal', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Resizable mosaic:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="resizable_mosaic" id="resizable_mosaic_0" value="0" <?php if ($row->resizable_mosaic == "0") echo 'checked="checked"'; ?> /><label for="resizable_mosaic_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <input type="radio" name="resizable_mosaic" id="resizable_mosaic_1" value="1" <?php if ($row->resizable_mosaic == "1") echo 'checked="checked"'; ?> /><label for="resizable_mosaic_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="mosaic_total_width"><?php echo __('Total width of mosaic:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="mosaic_total_width" id="mosaic_total_width" value="<?php echo $row->mosaic_total_width; ?>" class="spider_int_input" /> %
                    <div class="spider_description"><?php echo __("Width of mosaic as a percentage of container's width.", 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="image_column_number"><?php echo __('Number of image columns:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="image_column_number" id="image_column_number" value="<?php echo $row->image_column_number; ?>" class="spider_int_input" />
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="images_per_page"><?php echo __('Images per page:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="images_per_page" id="images_per_page" value="<?php echo $row->images_per_page; ?>" class="spider_int_input" />
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="thumb_width"><?php echo __('Frontend thumbnail dimensions:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="thumb_width" id="thumb_width" value="<?php echo $row->thumb_width; ?>" class="spider_int_input" /> x 
                    <input type="text" name="thumb_height" id="thumb_height" value="<?php echo $row->thumb_height; ?>" class="spider_int_input" /> px
                    <div class="spider_description"><?php echo __('The default size of the thumbnail which will be displayed in the website.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show image title:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="image_title_show_hover" id="image_title_show_hover_1" value="hover" <?php if ($row->image_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_1"><?php echo __('Show on hover', 'bwg_back'); ?></label><br />
                    <input type="radio" name="image_title_show_hover" id="image_title_show_hover_0" value="show" <?php if ($row->image_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_0"><?php echo __('Always show', 'bwg_back'); ?></label><br />
                    <input type="radio" name="image_title_show_hover" id="image_title_show_hover_2" value="none" <?php if ($row->image_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_2"><?php echo __("Don't show", 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <?php
                if (defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php')) {
                  ?>
                <tr>
                  <td class="spider_label_options">
                    <label><?php _e('Show ecommerce icon:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_1" value="hover" <?php if ($row->ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_1">Show on hover</label><br />
                    <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_0" value="show" <?php if ($row->ecommerce_icon_show_hover == "show") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_0">Always show</label><br />
                    <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_2" value="none" <?php if ($row->ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_2">Don't show</label>
                    <div class="spider_description"></div>
                  </td>
                </tr>              
                  <?php
                }
                ?>
                <tr>
                  <td class="spider_label_options"><label><?php echo __('Enable image pagination:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="image_enable_page" id="image_enable_page_yes" value="1" <?php if ($row->image_enable_page) echo 'checked="checked"'; ?> /><label for="image_enable_page_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="image_enable_page" id="image_enable_page_no" value="0" <?php if (!$row->image_enable_page) echo 'checked="checked"'; ?> /><label for="image_enable_page_no"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options"><label><?php echo __('Show gallery name:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="showthumbs_name" id="thumb_name_yes" value="1" <?php if ($row->showthumbs_name) echo 'checked="checked"'; ?> /><label for="thumb_name_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="showthumbs_name" id="thumb_name_no" value="0"  <?php if (!$row->showthumbs_name) echo 'checked="checked"'; ?> /><label for="thumb_name_no"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options"><label><?php _e('Show gallery description:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="show_gallery_description" id="show_gallery_description_1" value="1" <?php if ($row->show_gallery_description) echo 'checked="checked"'; ?> /><label for="show_gallery_description_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_gallery_description" id="show_gallery_description_0" value="0" <?php if (!$row->show_gallery_description) echo 'checked="checked"'; ?> /><label for="show_gallery_description_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                 </tr>
                 <tr>
                  <td class="spider_label_options"><label><?php echo __('Image click action:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_1" value="open_lightbox" <?php if ($row->thumb_click_action == 'open_lightbox') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_thumb_link_target', 'thumb_click_action_1')" /><label for="thumb_click_action_1"><?php echo __('Open lightbox', 'bwg_back'); ?></label>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_2" value="redirect_to_url" <?php if ($row->thumb_click_action == 'redirect_to_url') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_thumb_link_target', 'thumb_click_action_2')" /><label for="thumb_click_action_2"><?php echo __('Redirect to url', 'bwg_back'); ?></label>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_3" value="do_nothing" <?php if ($row->thumb_click_action == 'do_nothing') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_thumb_link_target', 'thumb_click_action_3')" /><label for="thumb_click_action_3"><?php echo __('Do Nothing', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr id="tr_thumb_link_target">
                  <td class="spider_label_options"><label><?php echo __('Open in a new window:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="thumb_link_target" id="thumb_link_target_yes" value="1" <?php if ($row->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="thumb_link_target" id="thumb_link_target_no" value="0" <?php if (!$row->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_no"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--Lightbox-->
          <div class="spider_div_options" id="div_content_9">        
            <table style="width: 100%;">
              <tr>
                <td class="options_left">
                  <table style="display: inline-table;">
                    <tbody>			
                      <tr id="tr_popup_full_width">
                        <td class="spider_label_options">
                          <label><?php echo __('Full width lightbox:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_fullscreen" id="popup_fullscreen_1" value="1" <?php if ($row->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwg_popup_fullscreen(1)" /><label for="popup_fullscreen_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_fullscreen" id="popup_fullscreen_0" value="0" <?php if (!$row->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwg_popup_fullscreen(0)" /><label for="popup_fullscreen_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"><?php echo __('Enable full width feature for the lightbox.', 'bwg_back'); ?></div>
                        </td>
                      </tr>			
                      <tr id="tr_popup_dimensions" >
                        <td class="spider_label_options">
                          <label for="popup_width"><?php echo __('Lightbox dimensions:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="popup_width" id="popup_width" value="<?php echo $row->popup_width; ?>" class="spider_int_input" /> x 
                          <input type="text" name="popup_height" id="popup_height" value="<?php echo $row->popup_height; ?>" class="spider_int_input" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="popup_type"><?php echo __('Lightbox effect:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <select name="popup_type" id="popup_type" class='select_icon' style="width:120px;">
                            <?php
                            foreach ($effects as $key => $effect) {
                              ?>
                              <option value="<?php echo $key; ?>" <?php if ($row->popup_type == $key) echo 'selected="selected"'; ?>><?php echo __($effect, 'bwg_back'); ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="popup_effect_duration"><?php echo __('Effect duration:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="popup_effect_duration" id="popup_effect_duration" value="<?php echo $row->popup_effect_duration; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_autoplay">
                        <td class="spider_label_options">
                          <label><?php echo __('Lightbox autoplay:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="popup_autoplay" id="popup_autoplay_1" value="1" <?php if ($row->popup_autoplay) echo 'checked="checked"'; ?> /><label for="popup_autoplay_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_autoplay" id="popup_autoplay_0" value="0" <?php if (!$row->popup_autoplay) echo 'checked="checked"'; ?> /><label for="popup_autoplay_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="popup_interval"><?php echo __('Time interval:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="popup_interval" id="popup_interval" value="<?php echo $row->popup_interval; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable filmstrip:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_filmstrip" id="popup_enable_filmstrip_1" value="1" <?php if ($row->popup_enable_filmstrip ) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_enable_filmstrip_1')" /><label for="popup_enable_filmstrip_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_filmstrip" id="popup_enable_filmstrip_0" value="0" <?php if (!$row->popup_enable_filmstrip ) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_popup_filmstrip_height', 'popup_enable_filmstrip_0')" /><label for="popup_enable_filmstrip_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_filmstrip_height">
                        <td class="spider_label_options">
                          <label for="popup_filmstrip_height"><?php echo __('Filmstrip size:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="popup_filmstrip_height" id="popup_filmstrip_height" value="<?php echo $row->popup_filmstrip_height; ?>" class="spider_int_input" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_hit_counter">
                        <td class="spider_label_options">
                          <label><?php echo __('Display hit counter:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_hit_counter" id="popup_hit_counter_1" value="1" <?php if ($row->popup_hit_counter) echo 'checked="checked"'; ?> /><label for="popup_hit_counter_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_hit_counter" id="popup_hit_counter_0" value="0" <?php if (!$row->popup_hit_counter) echo 'checked="checked"'; ?> /><label for="popup_hit_counter_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable control buttons:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_ctrl_btn" id="popup_enable_ctrl_btn_1" value="1" <?php if ($row->popup_enable_ctrl_btn) echo 'checked="checked"'; ?> 
                            onClick="bwg_enable_disable('', 'tr_popup_fullscreen', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_info', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_comment', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_facebook', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_twitter', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_google', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_pinterest', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_tumblr', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_comment_moderation', 'comment_moderation_1');
                            bwg_enable_disable('', 'tr_popup_email', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_captcha', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_download', 'popup_enable_ctrl_btn_1');
                            bwg_enable_disable('', 'tr_popup_fullsize_image', 'popup_enable_ctrl_btn_1');" /><label for="popup_enable_ctrl_btn_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_ctrl_btn" id="popup_enable_ctrl_btn_0" value="0" <?php if (!$row->popup_enable_ctrl_btn) echo 'checked="checked"'; ?> 
                            onClick="bwg_enable_disable('none', 'tr_popup_fullscreen', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_info', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_comment', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_facebook', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_twitter', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_google', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_pinterest', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_tumblr', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_comment_moderation', 'comment_moderation_0');
                            bwg_enable_disable('none', 'tr_popup_email', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_captcha', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_download', 'popup_enable_ctrl_btn_0');
                            bwg_enable_disable('none', 'tr_popup_fullsize_image', 'popup_enable_ctrl_btn_0');" /><label for="popup_enable_ctrl_btn_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_fullscreen">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable fullscreen:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_fullscreen" id="popup_enable_fullscreen_1" value="1" <?php if ($row->popup_enable_fullscreen) echo 'checked="checked"'; ?> /><label for="popup_enable_fullscreen_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_fullscreen" id="popup_enable_fullscreen_0" value="0" <?php if (!$row->popup_enable_fullscreen) echo 'checked="checked"'; ?> /><label for="popup_enable_fullscreen_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_info">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable info:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_info" id="popup_enable_info_1" value="1" <?php if ($row->popup_enable_info) echo 'checked="checked"'; ?> /><label for="popup_enable_info_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_info" id="popup_enable_info_0" value="0" <?php if (!$row->popup_enable_info) echo 'checked="checked"'; ?> /><label for="popup_enable_info_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_info_always_show">
                        <td class="spider_label_options">
                          <label><?php echo __('Display info by default:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_info_always_show" id="popup_info_always_show_1" value="1" <?php if ($row->popup_info_always_show) echo 'checked="checked"'; ?> /><label for="popup_info_always_show_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_info_always_show" id="popup_info_always_show_0" value="0" <?php if (!$row->popup_info_always_show) echo 'checked="checked"'; ?> /><label for="popup_info_always_show_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_info_full_width">
                        <td class="spider_label_options">
                          <label><?php echo __('Full width info:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_info_full_width" id="popup_info_full_width_1" value="1" <?php if ($row->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_info_full_width" id="popup_info_full_width_0" value="0" <?php if (!$row->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"><?php echo __('Display image information based on the lightbox dimensions.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td class="options_right">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr id="tr_popup_rate">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable rating:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_rate" id="popup_enable_rate_1" value="1" <?php if ($row->popup_enable_rate) echo 'checked="checked"'; ?> /><label for="popup_enable_rate_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_rate" id="popup_enable_rate_0" value="0" <?php if (!$row->popup_enable_rate) echo 'checked="checked"'; ?> /><label for="popup_enable_rate_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_comment">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable comments:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_comment" id="popup_enable_comment_1" value="1" <?php if ($row->popup_enable_comment) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_comment_moderation', 'popup_enable_comment_1');
                                                                                                                                                                                            bwg_enable_disable('', 'tr_popup_email', 'popup_enable_comment_1');
                                                                                                                                                                                            bwg_enable_disable('', 'tr_popup_captcha', 'popup_enable_comment_1');" /><label for="popup_enable_comment_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_comment" id="popup_enable_comment_0" value="0" <?php if (!$row->popup_enable_comment) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_comment_moderation', 'popup_enable_comment_0');
                                                                                                                                                                                             bwg_enable_disable('none', 'tr_popup_email', 'popup_enable_comment_0');
                                                                                                                                                                                             bwg_enable_disable('none', 'tr_popup_captcha', 'popup_enable_comment_0');" /><label for="popup_enable_comment_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_comment_moderation">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable comments moderation:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="comment_moderation" id="comment_moderation_1" value="1" <?php if ($row->comment_moderation) echo 'checked="checked"'; ?> /><label for="comment_moderation_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="comment_moderation" id="comment_moderation_0" value="0" <?php if (!$row->comment_moderation) echo 'checked="checked"'; ?> /><label for="comment_moderation_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_facebook">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Facebook button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_facebook" id="popup_enable_facebook_1" value="1" <?php if ($row->popup_enable_facebook) echo 'checked="checked"'; ?> /><label for="popup_enable_facebook_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_facebook" id="popup_enable_facebook_0" value="0" <?php if (!$row->popup_enable_facebook) echo 'checked="checked"'; ?> /><label for="popup_enable_facebook_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_twitter">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Twitter button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_twitter" id="popup_enable_facebook_1" value="1" <?php if ($row->popup_enable_twitter) echo 'checked="checked"'; ?> /><label for="popup_enable_twitter_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_twitter" id="popup_enable_facebook_0" value="0" <?php if (!$row->popup_enable_twitter) echo 'checked="checked"'; ?> /><label for="popup_enable_twitter_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_google">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Google+ button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_google" id="popup_enable_google_1" value="1" <?php if ($row->popup_enable_google) echo 'checked="checked"'; ?> /><label for="popup_enable_google_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_google" id="popup_enable_google_0" value="0" <?php if (!$row->popup_enable_google) echo 'checked="checked"'; ?> /><label for="popup_enable_google_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_pinterest">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Pinterest button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_pinterest" id="popup_enable_pinterest_1" value="1" <?php if ($row->popup_enable_pinterest) echo 'checked="checked"'; ?> /><label for="popup_enable_pinterest_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_pinterest" id="popup_enable_pinterest_0" value="0" <?php if (!$row->popup_enable_pinterest) echo 'checked="checked"'; ?> /><label for="popup_enable_pinterest_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_tumblr">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Tumblr button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_tumblr" id="popup_enable_tumblr_1" value="1" <?php if ($row->popup_enable_tumblr) echo 'checked="checked"'; ?> /><label for="popup_enable_tumblr_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_tumblr" id="popup_enable_tumblr_0" value="0" <?php if (!$row->popup_enable_tumblr) echo 'checked="checked"'; ?> /><label for="popup_enable_tumblr_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    <?php
                      if(defined('WDPG_ECOMMERCE_NAME') && is_plugin_active(WDPG_ECOMMERCE_NAME.'/'.'photo-gallery-ecommerce.php')){	
                    ?>						
                      <tr id="tr_popup_ecommerce">
                        <td class="spider_label_options">
                          <label>Enable Ecommerce button:</label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_ecommerce" id="popup_enable_ecommerce_1" value="1" <?php if ($row->popup_enable_ecommerce) echo 'checked="checked"'; ?> /><label for="popup_enable_ecommerce_1">Yes</label>
                          <input type="radio" name="popup_enable_ecommerce" id="popup_enable_ecommerce_0" value="0" <?php if (!$row->popup_enable_ecommerce) echo 'checked="checked"'; ?> /><label for="popup_enable_ecommerce_0">No</label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    <?php	
                      }
                    ?>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <!--Slideshow-->
          <div class="spider_div_options" id="div_content_10">
            <table style="width: 100%;">
              <tr>
                <td class="options_left">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options">
                          <label for="slideshow_type"><?php echo __('Slideshow effect:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <select name="slideshow_type" id="slideshow_type" class="select_icon" style="width:120px;">
                            <?php
                            foreach ($effects as $key => $effect) {
                              ?>
                              <option value="<?php echo $key; ?>" <?php if ($row->slideshow_type == $key) echo 'selected="selected"'; ?>><?php echo __($effect, 'bwg_back'); ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="slideshow_effect_duration"><?php echo __('Effect duration:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="slideshow_effect_duration" id="slideshow_effect_duration" value="<?php echo $row->slideshow_effect_duration; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="slideshow_interval"><?php echo __('Time interval:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="slideshow_interval" id="slideshow_interval" value="<?php echo $row->slideshow_interval; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="slideshow_width"><?php echo __('Slideshow dimensions:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="slideshow_width" id="slideshow_width" value="<?php echo $row->slideshow_width; ?>" class="spider_int_input" /> x 
                          <input type="text" name="slideshow_height" id="slideshow_height" value="<?php echo $row->slideshow_height; ?>" class="spider_int_input" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable autoplay:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="slideshow_enable_autoplay" id="slideshow_enable_autoplay_yes" value="1" <?php if ($row->slideshow_enable_autoplay) echo 'checked="checked"'; ?> /><label for="slideshow_enable_autoplay_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_autoplay" id="slideshow_enable_autoplay_no" value="0" <?php if (!$row->slideshow_enable_autoplay) echo 'checked="checked"'; ?> /><label for="slideshow_enable_autoplay_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable shuffle:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="slideshow_enable_shuffle" id="slideshow_enable_shuffle_yes" value="1" <?php if ($row->slideshow_enable_shuffle) echo 'checked="checked"'; ?> /><label for="slideshow_enable_shuffle_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_shuffle" id="slideshow_enable_shuffle_no" value="0" <?php if (!$row->slideshow_enable_shuffle) echo 'checked="checked"'; ?> /><label for="slideshow_enable_shuffle_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable control buttons:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="slideshow_enable_ctrl" id="slideshow_enable_ctrl_yes" value="1" <?php if ($row->slideshow_enable_ctrl) echo 'checked="checked"'; ?> /><label for="slideshow_enable_ctrl_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_ctrl" id="slideshow_enable_ctrl_no" value="0" <?php if (!$row->slideshow_enable_ctrl) echo 'checked="checked"'; ?> /><label for="slideshow_enable_ctrl_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options"><label><?php echo __('Enable slideshow filmstrip:', 'bwg_back'); ?> </label></td>
                        <td>
                          <input type="radio" name="slideshow_enable_filmstrip" id="slideshow_enable_filmstrip_yes" value="1" <?php if ($row->slideshow_enable_filmstrip) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_yes')" /><label for="slideshow_enable_filmstrip_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_filmstrip" id="slideshow_enable_filmstrip_no" value="0" <?php if (!$row->slideshow_enable_filmstrip) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_no')" /><label for="slideshow_enable_filmstrip_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_slideshow_filmstrip_height">
                        <td class="spider_label_options"><label for="slideshow_filmstrip_height"><?php echo __('Slideshow filmstrip size:', 'bwg_back'); ?> </label></td>
                        <td>
                          <input type="text" name="slideshow_filmstrip_height" id="slideshow_filmstrip_height" value="<?php echo $row->slideshow_filmstrip_height; ?>" class="spider_int_input" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td class="options_right">
                  <table style="width: 100%; display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options"><label><?php echo __('Enable image title:', 'bwg_back'); ?> </label></td>
                        <td>
                          <input type="radio" name="slideshow_enable_title" id="slideshow_enable_title_yes" value="1" <?php if ($row->slideshow_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_title_position', 'slideshow_enable_title_yes')" /><label for="slideshow_enable_title_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_title" id="slideshow_enable_title_no" value="0" <?php if (!$row->slideshow_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_title_position', 'slideshow_enable_title_no')" /><label for="slideshow_enable_title_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_slideshow_title_position">
                        <td class="spider_label_options"><label><?php echo __('Title position:', 'bwg_back'); ?> </label></td>
                        <td>
                          <table class="bwg_position_table">
                            <tbody>
                              <tr>
                                <td><input type="radio" value="top-left" id="slideshow_title_topLeft" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "top-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="top-center" id="slideshow_title_topCenter" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "top-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="top-right" id="slideshow_title_topRight" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "top-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="middle-left" id="slideshow_title_midLeft" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "middle-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="middle-center" id="slideshow_title_midCenter" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "middle-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="middle-right" id="slideshow_title_midRight" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "middle-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="bottom-left" id="slideshow_title_botLeft" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "bottom-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="bottom-center" id="slideshow_title_botCenter" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "bottom-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="bottom-right" id="slideshow_title_botRight" name="slideshow_title_position" <?php if ($row->slideshow_title_position == "bottom-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="spider_description"><?php echo __('Image title position on slideshow', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_slideshow_full_width_title">
                        <td class="spider_label_options">
                          <label><?php echo __('Full width title:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_1" value="1" <?php if ($row->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_0" value="0" <?php if (!$row->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"><?php echo __('Display image title based on the slideshow dimensions.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options"><label><?php echo __('Enable image description:', 'bwg_back'); ?> </label></td>
                        <td>
                          <input type="radio" name="slideshow_enable_description" id="slideshow_enable_description_yes" value="1" <?php if ($row->slideshow_enable_description) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_description_position', 'slideshow_enable_description_yes')" /><label for="slideshow_enable_description_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_description" id="slideshow_enable_description_no" value="0" <?php if (!$row->slideshow_enable_description) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_description_position', 'slideshow_enable_description_no')" /><label for="slideshow_enable_description_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_slideshow_description_position">
                        <td class="spider_label"><label><?php echo __('Description position:', 'bwg_back'); ?> </label></td>
                        <td>
                          <table class="bwg_position_table">
                            <tbody>
                              <tr>
                                <td><input type="radio" value="top-left" id="slideshow_description_topLeft" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "top-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="top-center" id="slideshow_description_topCenter" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "top-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="top-right" id="slideshow_description_topRight" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "top-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="middle-left" id="slideshow_description_midLeft" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "middle-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="middle-center" id="slideshow_description_midCenter" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "middle-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="middle-right" id="slideshow_description_midRight" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "middle-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="bottom-left" id="slideshow_description_botLeft" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "bottom-left") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="bottom-center" id="slideshow_description_botCenter" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "bottom-center") echo 'checked="checked"'; ?>></td>
                                <td><input type="radio" value="bottom-right" id="slideshow_description_botRight" name="slideshow_description_position" <?php if ($row->slideshow_description_position == "bottom-right") echo 'checked="checked"'; ?>></td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="spider_description"><?php echo __('Image description position on slideshow', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable slideshow Music:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="slideshow_enable_music" id="slideshow_enable_music_yes" value="1" <?php if ($row->slideshow_enable_music) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_music_url', 'slideshow_enable_music_yes')" /><label for="slideshow_enable_music_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="slideshow_enable_music" id="slideshow_enable_music_no" value="0" <?php if (!$row->slideshow_enable_music) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_music_url', 'slideshow_enable_music_no')"  /><label for="slideshow_enable_music_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_slideshow_music_url">
                        <td class="spider_label_options">
                          <label for="slideshow_audio_url"><?php echo __('Music url:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" id="slideshow_audio_url" name="slideshow_audio_url" style="width: 70%;" value="<?php echo $row->slideshow_audio_url; ?>" style="display:inline-block;" />
                          <?php
                          $query_url = add_query_arg(array('action' => 'addMusic', 'width' => '700', 'height' => '550', 'extensions' => 'aac,m4a,f4a,mp3,ogg,oga', 'callback' => 'bwg_add_music'), admin_url('admin-ajax.php'));
                          $query_url = wp_nonce_url( $query_url, 'addMusic', 'bwg_nonce' );
                          $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url );
                          ?>
                          <a href="<?php echo $query_url; ?>" id="button_add_music" class="wd-btn wd-btn-primary wd-not-image thickbox thickbox-preview"
                             title="Add music"
                             onclick="return false;"
                             style="margin-bottom:5px;">
                            <?php echo __('Add Music', 'bwg_back'); ?>
                          </a>
                          <div class="spider_description"><?php echo __("Only", 'bwg_back'); ?> .aac,.m4a,.f4a,.mp3,.ogg,.oga <?php echo __("formats are supported.", 'bwg_back'); ?></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <!--Album options-->
          <div class="spider_div_options" id="div_content_11">        
            <table>
              <tbody>
               <tr>
                  <td class="spider_label_options">
                    <label><?php _e('Show album/gallery name:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_album_name" id="show_album_name_enable_1" value="1" <?php if ($row->show_album_name) echo 'checked="checked"'; ?> /><label for="show_album_name_enable_1"><?php _e('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_album_name" id="show_album_name_enable_0" value="0" <?php if (!$row->show_album_name) echo 'checked="checked"'; ?> /><label for="show_album_name_enable_0"><?php _e('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="album_column_number"><?php echo __('Number of album columns:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="album_column_number" id="album_column_number" value="<?php echo $row->album_column_number; ?>" class="spider_int_input" />
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="albums_per_page"><?php echo __('Albums per page:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="albums_per_page" id="albums_per_page" value="<?php echo $row->albums_per_page; ?>" class="spider_int_input" />
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable pagination:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="album_enable_page" id="album_enable_page_1" value="1" <?php if ($row->album_enable_page) echo 'checked="checked"'; ?> /><label for="album_enable_page_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="album_enable_page" id="album_enable_page_0" value="0" <?php if (!$row->album_enable_page) echo 'checked="checked"'; ?> /><label for="album_enable_page_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Album view type:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="album_view_type" id="album_view_type_1" value="thumbnail" <?php if ($row->album_view_type == "thumbnail") echo 'checked="checked"'; ?> /><label for="album_view_type_1"><?php echo __('Thumbnail', 'bwg_back'); ?></label>
                    <input type="radio" name="album_view_type" id="album_view_type_0" value="masonry" <?php if ($row->album_view_type == "masonry") echo 'checked="checked"'; ?> /><label for="album_view_type_0"><?php echo __('Masonry', 'bwg_back'); ?></label>
                    <input type="radio" name="album_view_type" id="album_view_type_2" value="mosaic" <?php if ($row->album_view_type == "mosaic") echo 'checked="checked"'; ?> /><label for="album_view_type_1"><?php echo __('Mosaic', 'bwg_back'); ?></label>
                    <div class="spider_description">T<?php echo __('he gallery images view type in the album.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show title:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="album_title_show_hover" id="album_title_show_hover_1" value="hover" <?php if ($row->album_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_1"><?php echo __('Show on hover', 'bwg_back'); ?></label><br />
                    <input type="radio" name="album_title_show_hover" id="album_title_show_hover_0" value="show" <?php if ($row->album_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_0"><?php echo __('Always show', 'bwg_back'); ?></label><br />
                    <input type="radio" name="album_title_show_hover" id="album_title_show_hover_2" value="none" <?php if ($row->album_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_2"><?php echo __("Don't show", 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable extended album description:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="extended_album_description_enable" id="extended_album_description_enable_1" value="1" <?php if ($row->extended_album_description_enable) echo 'checked="checked"'; ?> /><label for="extended_album_description_enable_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="extended_album_description_enable" id="extended_album_description_enable_0" value="0" <?php if (!$row->extended_album_description_enable) echo 'checked="checked"'; ?> /><label for="extended_album_description_enable_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="album_thumb_width"><?php echo __('Album thumbnail dimensions:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="album_thumb_width" id="album_thumb_width" value="<?php echo $row->album_thumb_width; ?>" class="spider_int_input" /> x 
                    <input type="text" name="album_thumb_height" id="album_thumb_height" value="<?php echo $row->album_thumb_height; ?>" class="spider_int_input" /> px
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="extended_album_height"><?php echo __('Extended album height:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="extended_album_height" id="extended_album_height" value="<?php echo $row->extended_album_height; ?>" class="spider_int_input" /> px
                    <div class="spider_description"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--Image options-->
          <div class="spider_div_options" id="div_content_12">        
            <table>
              <tbody>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable image title for Image Browser view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="image_browser_title_enable" id="image_browser_title_enable_1" value="1" <?php if ($row->image_browser_title_enable) echo 'checked="checked"'; ?> /><label for="image_browser_title_enable_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="image_browser_title_enable" id="image_browser_title_enable_0" value="0" <?php if (!$row->image_browser_title_enable) echo 'checked="checked"'; ?> /><label for="image_browser_title_enable_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable image description for Image Browser view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="image_browser_description_enable" id="image_browser_description_enable_1" value="1" <?php if ($row->image_browser_description_enable) echo 'checked="checked"'; ?> /><label for="image_browser_description_enable_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="image_browser_description_enable" id="image_browser_description_enable_0" value="0" <?php if (!$row->image_browser_description_enable) echo 'checked="checked"'; ?> /><label for="image_browser_description_enable_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="image_browser_width"><?php echo __('Image width for Image Browser view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="text" name="image_browser_width" id="image_browser_width" value="<?php echo $row->image_browser_width; ?>" class="spider_int_input" /> px
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable image title for Blog Style view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="blog_style_title_enable" id="blog_style_title_enable_1" value="1" <?php if ($row->blog_style_title_enable) echo 'checked="checked"'; ?> /><label for="blog_style_title_enable_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="blog_style_title_enable" id="blog_style_title_enable_0" value="0" <?php if (!$row->blog_style_title_enable) echo 'checked="checked"'; ?> /><label for="blog_style_title_enable_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo _e('Enable image description for Blog Style view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="blog_style_description_enable" id="blog_style_description_enable_1" value="1" <?php if ($row->blog_style_description_enable) echo 'checked="checked"'; ?> /><label for="blog_style_description_enable_1"><?php echo _e('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="blog_style_description_enable" id="blog_style_description_enable_0" value="0" <?php if (!$row->blog_style_description_enable) echo 'checked="checked"'; ?> /><label for="blog_style_description_enable_0"><?php echo _e('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="blog_style_width"><?php echo __('Image width for Blog Style view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="text" name="blog_style_width" id="blog_style_width" value="<?php echo $row->blog_style_width; ?>" class="spider_int_input" /> px
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="blog_style_images_per_page"><?php echo __('Images per page in Blog Style view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="text" name="blog_style_images_per_page" id="blog_style_images_per_page" value="<?php echo $row->blog_style_images_per_page; ?>" class="spider_int_input" />
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable pagination for Blog Style view:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="blog_style_enable_page" id="blog_style_enable_page_1" value="1" <?php if ($row->blog_style_enable_page) echo 'checked="checked"'; ?> /><label for="blog_style_enable_page_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="blog_style_enable_page" id="blog_style_enable_page_0" value="0" <?php if (!$row->blog_style_enable_page) echo 'checked="checked"'; ?> /><label for="blog_style_enable_page_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Carousel-->
          <div class="spider_div_options" id="div_content_13">
            <table style="width: 100%;">
              <tr>
                <td class="options_left">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options">
                          <label for="carousel_interval"><?php echo __('Time interval:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="carousel_interval" id="carousel_interval" value="<?php echo $row->carousel_interval; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="carousel_image_column_number"><?php echo __('Max. number of images:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="carousel_image_column_number" id="carousel_image_column_number" value="<?php echo $row->carousel_image_column_number; ?>" class="spider_int_input" /> sec.
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label for="carousel_image_par"><?php echo __('Carousel image ratio:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="carousel_image_par" id="carousel_image_par" value="<?php echo $row->carousel_image_par; ?>"  /> 
                          <div class="spider_description"></div>
                        </td>
                      </tr>                
                      <tr>
                        <td class="spider_label_options">
                          <label for="carousel_width"><?php echo __('Image dimensions:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="carousel_width" id="carousel_width" value="<?php echo $row->carousel_width; ?>" class="spider_int_input" /> x 
                          <input type="text" name="carousel_height" id="carousel_height" value="<?php echo $row->carousel_height; ?>" class="spider_int_input" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>         
                      <tr>
                        <td class="spider_label_options">
                          <label for="carousel_r_width"><?php echo __('Fixed width:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="carousel_r_width" id="carousel_r_width" value="<?php echo $row->carousel_r_width; ?>" class="spider_int_input" /> px                         
                        </td>
                      </tr>   
                    </tbody>
                  </table>
                </td>
                <td class="options_right">
                  <table style="width: 100%; display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options"><label><?php echo __('Enable image title:', 'bwg_back'); ?> </label></td>
                        <td>
                          <input type="radio" name="carousel_enable_title" id="carousel_enable_title_yes" value="1" <?php if ($row->carousel_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_carousel_title_position', 'carousel_enable_title_yes')" /><label for="carousel_enable_title_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="carousel_enable_title" id="carousel_enable_title_no" value="0" <?php if (!$row->carousel_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_carousel_title_position', 'carousel_enable_title_no')" /><label for="carousel_enable_title_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable autoplay:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="carousel_enable_autoplay" id="carousel_enable_autoplay_yes" value="1" <?php if ($row->carousel_enable_autoplay) echo 'checked="checked"'; ?> /><label for="carousel_enable_autoplay_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="carousel_enable_autoplay" id="carousel_enable_autoplay_no" value="0" <?php if (!$row->carousel_enable_autoplay) echo 'checked="checked"'; ?> /><label for="carousel_enable_autoplay_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label> <?php echo __('Container fit:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_yes" value="1" <?php if ($row->carousel_fit_containerWidth) echo 'checked="checked"'; ?> /><label for="carousel_fit_containerWidth_yes"><?php echo __("Yes", 'bwg_back'); ?></label>
                          <input type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_no" value="0" <?php if (!$row->carousel_fit_containerWidth) echo 'checked="checked"'; ?> /><label for="carousel_fit_containerWidth_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr> 
                      <tr>
                        <td class="spider_label_options">
                          <label> <?php echo __('Next/Previous buttons:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_yes" value="1" <?php if ($row->carousel_prev_next_butt) echo 'checked="checked"'; ?> /><label for="carousel_prev_next_butt_yes"><?php echo __("Yes", 'bwg_back'); ?></label>
                          <input type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_no" value="0" <?php if (!$row->carousel_prev_next_butt) echo 'checked="checked"'; ?> /><label for="carousel_prev_next_butt_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr> 
                      <tr>
                        <td class="spider_label_options">
                          <label> <?php echo __('Play/Pause button:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_yes" value="1" <?php if ($row->carousel_play_pause_butt) echo 'checked="checked"'; ?> /><label for="carousel_play_pause_butt_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_no" value="0" <?php if (!$row->carousel_play_pause_butt) echo 'checked="checked"'; ?> /><label for="carousel_play_pause_butt_no"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr> 
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <!--Advertisement-->
          <div class="spider_default_div_options" id="div_content_14">
            <table style="width: 100%;">
              <tr>
                <td class="options_left">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr id="tr_watermark_type">
                        <td class="spider_label_options">
                          <label><?php echo __('Advertisement type:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="radio" name="watermark_type" id="watermark_type_none" value="none" <?php if ($row->watermark_type == 'none') echo 'checked="checked"'; ?> onClick="bwg_watermark('watermark_type_none')" />
                            <label for="watermark_type_none"><?php echo __('None', 'bwg_back'); ?></label>
                          <input type="radio" name="watermark_type" id="watermark_type_text" value="text" <?php if ($row->watermark_type == 'text') echo 'checked="checked"'; ?> onClick="bwg_watermark('watermark_type_text')" onchange="preview_watermark()" />
                            <label for="watermark_type_text"><?php echo __('Text', 'bwg_back'); ?></label>
                          <input type="radio" name="watermark_type" id="watermark_type_image" value="image" <?php if ($row->watermark_type == 'image') echo 'checked="checked"'; ?> onClick="bwg_watermark('watermark_type_image')" onchange="preview_watermark()" />
                            <label for="watermark_type_image"><?php echo __('Image', 'bwg_back'); ?></label>
                            <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_url">
                        <td class="spider_label_options">
                          <label for="watermark_url"><?php echo __('Advertisement url:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" id="watermark_url" name="watermark_url" style="width: 68%;" value="<?php echo $row->watermark_url; ?>" style="display:inline-block;" onchange="preview_watermark()" />
                          
                          <?php
                          $query_url = add_query_arg(array('action' => 'addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwg_add_watermark_image'), admin_url('admin-ajax.php'));
                          $query_url = wp_nonce_url( $query_url, 'addImages', 'bwg_nonce' );
                          $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url );
                          ?>

                          <a href="<?php echo $query_url; ?>" id="button_add_watermark_image" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add thickbox thickbox-preview"
                             title="Add image" 
                             onclick="return false;"
                             style="margin-bottom:5px;">
                            <?php echo __('Add Image', 'bwg_back'); ?>
                          </a>
                          <div class="spider_description"><?php echo __('Enter absolute image file url or add file from Options page. (.jpg,.jpeg,.png,.gif formats are supported)', 'bwg_back'); ?></div>
                        </td>
                      </tr>                    
                      <tr id="tr_watermark_text">
                        <td class="spider_label_options">
                          <label for="watermark_text"><?php echo __('Advertisement text:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_text" id="watermark_text" style="width: 100%;" value="<?php echo $row->watermark_text; ?>" onchange="preview_watermark()" onkeypress="preview_watermark()" />
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_link">
                        <td class="spider_label_options">
                          <label for="watermark_link"><?php echo __('Advertisement link:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_link" id="watermark_link" style="width: 100%;" value="<?php echo $row->watermark_link; ?>" onchange="preview_watermark()" onkeypress="preview_watermark()" />
                          <div class="spider_description"><?php echo __('Enter a URL to open when the advertisement banner is clicked.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_width_height">
                        <td class="spider_label_options">
                          <label for="watermark_width"><?php echo __('Advertisement dimensions:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_width" id="watermark_width" value="<?php echo $row->watermark_width; ?>" class="spider_int_input" onchange="preview_watermark()" /> x 
                          <input type="text" name="watermark_height" id="watermark_height" value="<?php echo $row->watermark_height; ?>" class="spider_int_input" onchange="preview_watermark()" /> px
                          <div class="spider_description"><?php echo __('Maximum values for watermark image width and height.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_font_size">
                        <td class="spider_label_options">
                          <label for="watermark_font_size"><?php echo __('Advertisement font size:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_font_size" id="watermark_font_size" value="<?php echo $row->watermark_font_size; ?>" class="spider_int_input" onchange="preview_watermark()" /> px
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_font">
                        <td class="spider_label_options">
                          <label for="watermark_font"><?php echo __('Advertisement font style:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <select name="watermark_font" id="watermark_font" class="select_icon bwg_font_select" style="width:120px;" onchange="preview_watermark()">
                            <?php
                            $google_fonts = WDWLibrary::get_google_fonts();
                            $is_google_fonts = (in_array($row->watermark_font, $google_fonts) ) ? true : false;
                            $watermark_font_families = ($is_google_fonts == true) ? $google_fonts : $watermark_fonts;
                            foreach ($watermark_font_families as $watermark_font) {
                              ?>
                              <option value="<?php echo $watermark_font; ?>" <?php if ($row->watermark_font == $watermark_font) echo 'selected="selected"'; ?>><?php echo $watermark_font; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <input type="radio" name="watermark_google_fonts" id="watermark_google_fonts1" onchange="bwg_change_fonts('watermark_font', jQuery(this).attr('id'))" value="1" <?php if ($is_google_fonts) echo 'checked="checked"'; ?> />
                          <label for="watermark_google_fonts1" id="watermark_google_fonts1_lbl"><?php echo __('Google fonts', 'bwg_back'); ?></label>
                          <input type="radio" name="watermark_google_fonts" id="watermark_google_fonts0" onchange="bwg_change_fonts('watermark_font', '')" value="0" <?php if (!$is_google_fonts) echo 'checked="checked"'; ?> />
                          <label for="watermark_google_fonts0" id="watermark_google_fonts0_lbl"><?php echo __('Default', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_color">
                        <td class="spider_label_options">
                          <label for="watermark_color"><?php echo __('Advertisement color:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_color" id="watermark_color" value="<?php echo $row->watermark_color; ?>" class="color" onchange="preview_watermark()" />
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_opacity">
                        <td class="spider_label_options">
                          <label for="watermark_opacity"><?php echo __('Advertisement opacity:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="watermark_opacity" id="watermark_opacity" value="<?php echo $row->watermark_opacity; ?>" class="spider_int_input" onchange="preview_watermark()" /> %
                          <div class="spider_description"><?php echo __('Value must be between 0 to 100.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_watermark_position">
                        <td class="spider_label_options">
                          <label><?php echo __('Advertisement position:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <table class="bwg_position_table">
                            <tbody>
                              <tr>
                                <td><input type="radio" value="top-left" name="watermark_position" <?php if ($row->watermark_position == "top-left") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="top-center" name="watermark_position" <?php if ($row->watermark_position == "top-center") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="top-right" name="watermark_position" <?php if ($row->watermark_position == "top-right") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="middle-left" name="watermark_position" <?php if ($row->watermark_position == "middle-left") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="middle-center" name="watermark_position" <?php if ($row->watermark_position == "middle-center") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="middle-right" name="watermark_position" <?php if ($row->watermark_position == "middle-right") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="bottom-left" name="watermark_position" <?php if ($row->watermark_position == "bottom-left") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="bottom-center" name="watermark_position" <?php if ($row->watermark_position == "bottom-center") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                                <td><input type="radio" value="bottom-right" name="watermark_position" <?php if ($row->watermark_position == "bottom-right") echo 'checked="checked"'; ?> onchange="preview_watermark()"></td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td class="options_right">
                  <table style="width: 100%; display: inline-table;">
                    <tbody>
                      <tr>
                        <td>
                          <span id="preview_watermark" style="display:table-cell; background-image:url('<?php echo WD_BWG_URL . '/images/watermark_preview.jpg'?>');background-size:100% 100%;width:400px;height:400px;padding-top: 4px; position:relative;">
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>  
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="bwg_options_box standart_option" style="display:none;">
        <div style="display:none; width: 100%;" id="display_panel">
          <div class="options_tab">
            <div id="div_1" class="gallery_type" onclick="bwg_change_option_type('1')"><?php _e('General', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_2" class="gallery_type" onclick="bwg_change_option_type('2')"><?php _e('Thumbnail options', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_3" class="gallery_type" onclick="bwg_change_option_type('3')"><?php _e('Lightbox', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_4" class="gallery_type" onclick="bwg_change_option_type('4')"><?php _e('Slideshow', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_6" class="gallery_type" onclick="bwg_change_option_type('6')"><?php _e('Social options', 'bwg_back'); ?></div>
            <div class="bwg_line_option">|</div>
            <div id="div_7" class="gallery_type" onclick="bwg_change_option_type('7')"><?php _e('Watermark', 'bwg_back'); ?></div>
            <input type="hidden" id="type" name="type" value="<?php echo (isset($_POST["type"]) ? esc_html(stripslashes($_POST["type"])) : 1); ?>" />
          </div>
          <!--Global options-->
          <div class="spider_div_options" id="div_content_1">
            <table>
              <tbody>
                <tr>
                  <td class="spider_label_options">
                    <label for="images_directory"><?php echo __('Images directory:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input id="images_directory" name="images_directory" type="text" style="display:inline-block; width:100%;" value="<?php echo $row->images_directory; ?>" />
                    <input type="hidden" id="old_images_directory" name="old_images_directory" value="<?php echo $row->old_images_directory; ?>"/>
                    <div class="spider_description"><?php echo __('Input an existing directory inside the Wordpress directory to store uploaded images.<br />Old directory content will be moved to the new one.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="upload_img_width"><?php echo __('Image dimensions:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="upload_img_width" id="upload_img_width" value="<?php echo $row->upload_img_width; ?>" class="spider_int_input" /> x 
                    <input type="text" name="upload_img_height" id="upload_img_height" value="<?php echo $row->upload_img_height; ?>" class="spider_int_input" /> px
                    <div class="spider_description"><?php echo __('The maximum size of the uploaded image (0 for original size).', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label for="image_quality"><?php _e('Image quality:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="text" name="image_quality" id="image_quality" value="<?php echo $row->image_quality; ?>" class="spider_int_input" /> %
                    <div class="spider_description"><?php _e('Set the quality of gallery images. Provide a value from 0 to 100%.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Right click protection:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="image_right_click" id="image_right_click_1" value="1" <?php if ($row->image_right_click) echo 'checked="checked"'; ?> /><label for="image_right_click_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="image_right_click" id="image_right_click_0" value="0" <?php if (!$row->image_right_click) echo 'checked="checked"'; ?> /><label for="image_right_click_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php echo __('Disable image right click possibility.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show search box:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_search_box" id="show_search_box_1" value="1" <?php if ($row->show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_search_box_width', 'show_search_box_1'); bwg_enable_disable('', 'tr_search_box_placeholder', 'show_search_box_1')" /><label for="show_search_box_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_search_box" id="show_search_box_0" value="0" <?php if (!$row->show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_search_box_width', 'show_search_box_0'); bwg_enable_disable('none', 'tr_search_box_placeholder', 'show_search_box_0')" /><label for="show_search_box_0"><?php echo __('No', 'bwg_back'); ?></label>
                   <div class="spider_description"></div>
                  </td>
                </tr>
                <tr id="tr_search_box_placeholder">
                  <td class="spider_label_options">
                    <label for="placeholder"><?php echo __('Add placeholder to search:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="placeholder" id="placeholder" value="<?php echo $row->placeholder; ?>"  /> 
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr id="tr_search_box_width">
                  <td class="spider_label_options">
                    <label for="search_box_width"><?php echo __('Search box width:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="search_box_width" id="search_box_width" value="<?php echo $row->search_box_width; ?>" class="spider_int_input" /> px
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show "Order by" dropdown list:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_sort_images" id="show_sort_images_1" value="1" <?php if ($row->show_sort_images) echo 'checked="checked"'; ?> /><label for="show_sort_images_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_sort_images" id="show_sort_images_0" value="0" <?php if (!$row->show_sort_images) echo 'checked="checked"'; ?> /><label for="show_sort_images_0"><?php echo __('No', 'bwg_back'); ?></label>
                   <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show tag box:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_tag_box" id="show_tag_box_1" value="1" <?php if ($row->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_tag_box_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_tag_box" id="show_tag_box_0" value="0" <?php if (!$row->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_tag_box_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Preload images:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="preload_images" id="preload_images_1" value="1" <?php if ($row->preload_images) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_preload_images_count', 'preload_images_1')" /><label for="preload_images_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="preload_images" id="preload_images_0" value="0" <?php if (!$row->preload_images) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_preload_images_count', 'preload_images_0')" /><label for="preload_images_0"><?php echo __('No', 'bwg_back'); ?></label>
                   <div class="spider_description"></div>
                  </td>
                </tr>	
                <tr id="tr_preload_images_count">
                  <td class="spider_label_options">
                    <label for="preload_images_count"><?php echo __('Count of images:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="preload_images_count" id="preload_images_count" value="<?php echo $row->preload_images_count; ?>" class="spider_int_input" />
                    <div class="spider_description"><?php echo __('Count of images to preload (0 for all).', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php _e('Enable html editor:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="enable_wp_editor" id="enable_wp_editor_1" value="1" <?php if ($row->enable_wp_editor) echo 'checked="checked"'; ?> /><label for="enable_wp_editor_1"><?php _e('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="enable_wp_editor" id="enable_wp_editor_0" value="0" <?php if (!$row->enable_wp_editor) echo 'checked="checked"'; ?> /><label for="enable_wp_editor_0"><?php _e('No', 'bwg_back'); ?></label>
                   <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Enable href attribute:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="enable_seo" id="enable_seo_1" value="1" <?php if ($row->enable_seo) echo 'checked="checked"'; ?> /><label for="enable_seo_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="enable_seo" id="enable_seo_0" value="0" <?php if (!$row->enable_seo) echo 'checked="checked"'; ?> /><label for="enable_seo_0"><?php echo __('No', 'bwg_back'); ?></label>
                   <div class="spider_description"><?php echo __('Disable this option only if it conflicts with your theme.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Meta auto-fill:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="read_metadata" id="read_metadata_1" value="1" <?php if ($row->read_metadata) echo 'checked="checked"'; ?> /><label for="read_metadata_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="read_metadata" id="read_metadata_0" value="0" <?php if (!$row->read_metadata) echo 'checked="checked"'; ?> /><label for="read_metadata_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php echo __('Enabling this option the meta description of the image will be automatically filled in image description field.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Roles:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <select name="permissions" class='select_icon' onchange="bwg_show_hide_roles();" style="width: 120px;">
                      <?php
                      foreach ($permissions as $key => $permission) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if ($row->permissions == $key) echo 'selected="selected"'; ?>><?php echo $permission; ?></option>
                        <?php
                      }
                      ?>
                    </select>
                    <div class="spider_description"><?php echo __('Choose a user type who can add/edit galleries, images, albums and tags.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr class="bwg_roles">
                  <td class="spider_label_options">
                    <label><?php echo __('Gallery role:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="gallery_role" id="gallery_role_1" value="1" <?php if ($row->gallery_role) echo 'checked="checked"'; ?> /><label for="gallery_role_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="gallery_role" id="gallery_role_0" value="0" <?php if (!$row->gallery_role) echo 'checked="checked"'; ?> /><label for="gallery_role_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php echo __('Only author can change a gallery.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr class="bwg_roles">
                  <td class="spider_label_options">
                    <label><?php echo __('Album role:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="album_role" id="album_role_1" value="1" <?php if ($row->album_role) echo 'checked="checked"'; ?> /><label for="album_role_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="album_role" id="album_role_0" value="0" <?php if (!$row->album_role) echo 'checked="checked"'; ?> /><label for="album_role_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php echo __('Only author can change an album.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr class="bwg_roles">
                  <td class="spider_label_options">
                    <label><?php echo __('Image role:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="image_role" id="image_role_1" value="1" <?php if ($row->image_role) echo 'checked="checked"'; ?> /><label for="image_role_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="image_role" id="image_role_0" value="0" <?php if (!$row->image_role) echo 'checked="checked"'; ?> /><label for="image_role_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php echo __('Only author can change an image.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show/hide custom post types:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_hide_custom_post" id="show_hide_custom_post_1" value="1" <?php if ($row->show_hide_custom_post) echo 'checked="checked"'; ?> /><label for="show_hide_custom_post_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_hide_custom_post" id="show_hide_custom_post_0" value="0" <?php if (!$row->show_hide_custom_post) echo 'checked="checked"'; ?> /><label for="show_hide_custom_post_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show/hide comments for custom post types:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="show_hide_post_meta" id="show_hide_post_meta_1" value="1" <?php if ($row->show_hide_post_meta) echo 'checked="checked"'; ?> /><label for="show_hide_post_meta_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_hide_post_meta" id="show_hide_post_meta_0" value="0" <?php if (!$row->show_hide_post_meta) echo 'checked="checked"'; ?> /><label for="show_hide_post_meta_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Include styles/scripts in necessary pages only:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="use_inline_stiles_and_scripts" id="use_inline_stiles_and_scripts_1" value="1" <?php if ($row->use_inline_stiles_and_scripts) echo 'checked="checked"'; ?> /><label for="use_inline_stiles_and_scripts_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="use_inline_stiles_and_scripts" id="use_inline_stiles_and_scripts_0" value="0" <?php if (!$row->use_inline_stiles_and_scripts) echo 'checked="checked"'; ?> /><label for="use_inline_stiles_and_scripts_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php _e('Enable bulk download button:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <input type="radio" name="gallery_download" id="gallery_download_1" value="1" <?php if ($row->gallery_download) echo 'checked="checked"'; ?> /><label for="gallery_download_1"><?php _e('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="gallery_download" id="gallery_download_0" value="0" <?php if (!$row->gallery_download) echo 'checked="checked"'; ?> /><label for="gallery_download_0"><?php _e('No', 'bwg_back'); ?></label>
                    <div class="spider_description"><?php _e('If enabled,it will be possible to download entire gallery with a single button.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Introduction tour:', 'bwg_back'); ?></label>
                  </td>
                  <td>
                    <a href="admin.php?page=options_bwg&bwg_start_tour=1" class="wd-btn wd-btn-primary wd-not-image" title="<?php echo _e('Start tour', 'bwg_back'); ?>">
                      <?php _e('Start tour', 'bwg_back'); ?>
                    </a>
                    <div class="spider_description"><?php echo __('Take this tour to quickly learn about the use of this plugin.', 'bwg_back'); ?></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--Thumbnail options-->
          <div class="spider_div_options" id="div_content_2">        
            <table>
              <tbody>
                <tr>
                  <td class="spider_label_options">
                    <label for="upload_thumb_width"><?php echo __('Generated thumbnail dimensions:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="text" name="upload_thumb_width" id="upload_thumb_width" value="<?php echo $row->upload_thumb_width; ?>" class="spider_int_input" /> x 
                    <input type="text" name="upload_thumb_height" id="upload_thumb_height" value="<?php echo $row->upload_thumb_height; ?>" class="spider_int_input" /> px
                    <input type="submit" class="wd-btn wd-btn-primary wd-not-image" onclick="spider_set_input_value('task', 'save'); spider_set_input_value('recreate', 'resize_image_thumb');" value="<?php echo __('Recreate', 'bwg_back'); ?>" />
                    <div class="spider_description"><?php echo __('The maximum size of the generated thumbnail. Its dimensions should be larger than the ones of the frontend thumbnail.', 'bwg_back'); ?></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options">
                    <label><?php echo __('Show description in Vertical Masonry view:', 'bwg_back'); ?> </label>
                  </td>
                  <td>
                    <input type="radio" name="show_masonry_thumb_description" id="masonry_thumb_desc_1" value="1" <?php if ($row->show_masonry_thumb_description) echo 'checked="checked"'; ?> /><label for="masonry_thumb_desc_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="show_masonry_thumb_description" id="masonry_thumb_desc_0" value="0" <?php if (!$row->show_masonry_thumb_description) echo 'checked="checked"'; ?> /><label for="masonry_thumb_desc_0"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
                <tr>
                  <td class="spider_label_options"><label><?php echo __('Play icon over the video thumbnail:', 'bwg_back'); ?> </label></td>
                  <td>
                    <input type="radio" name="play_icon" id="play_icon_yes" value="1" <?php if ($row->play_icon) echo 'checked="checked"'; ?> /><label for="play_icon_yes"><?php echo __('Yes', 'bwg_back'); ?></label>
                    <input type="radio" name="play_icon" id="play_icon_no" value="0" <?php if (!$row->play_icon) echo 'checked="checked"'; ?> /><label for="play_icon_no"><?php echo __('No', 'bwg_back'); ?></label>
                    <div class="spider_description"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--Lightbox-->
          <div class="spider_div_options" id="div_content_3">        
            <table style="width: 100%;">
              <tr>
                <td style="width: 50%; vertical-align: top;">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Show Next / Previous buttons:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="autohide_lightbox_navigation" id="autohide_lightbox_navigation_1" value="1" <?php if ($row->autohide_lightbox_navigation ) echo 'checked="checked"'; ?> /><label for="autohide_lightbox_navigation_1"><?php echo __('On hover', 'bwg_back'); ?></label>
                          <input type="radio" name="autohide_lightbox_navigation" id="autohide_lightbox_navigation_0" value="0" <?php if (!$row->autohide_lightbox_navigation ) echo 'checked="checked"'; ?> /><label for="autohide_lightbox_navigation_0"><?php echo __('Always', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_email">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Email for comments:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_email" id="popup_enable_email_1" value="1" <?php if ($row->popup_enable_email) echo 'checked="checked"'; ?> /><label for="popup_enable_email_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_email" id="popup_enable_email_0" value="0" <?php if (!$row->popup_enable_email) echo 'checked="checked"'; ?> /><label for="popup_enable_email_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_captcha">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable Captcha for comments:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_captcha" id="popup_enable_captcha_1" value="1" <?php if ($row->popup_enable_captcha) echo 'checked="checked"'; ?> /><label for="popup_enable_captcha_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_captcha" id="popup_enable_captcha_0" value="0" <?php if (!$row->popup_enable_captcha) echo 'checked="checked"'; ?> /><label for="popup_enable_captcha_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_fullsize_image">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable original image display button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_fullsize_image" id="popup_enable_fullsize_image_1" value="1" <?php if ($row->popup_enable_fullsize_image) echo 'checked="checked"'; ?> /><label for="popup_enable_fullsize_image_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_fullsize_image" id="popup_enable_fullsize_image_0" value="0" <?php if (!$row->popup_enable_fullsize_image) echo 'checked="checked"'; ?> /><label for="popup_enable_fullsize_image_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_popup_download">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable download button:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="popup_enable_download" id="popup_enable_download_1" value="1" <?php if ($row->popup_enable_download) echo 'checked="checked"'; ?> /><label for="popup_enable_download_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="popup_enable_download" id="popup_enable_download_0" value="0" <?php if (!$row->popup_enable_download) echo 'checked="checked"'; ?> /><label for="popup_enable_download_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_image_count">
                        <td class="spider_label_options">
                          <label><?php echo __('Show images count:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="show_image_counts" id="show_image_counts_current_image_number_1" value="1" <?php if ($row->show_image_counts) echo 'checked="checked"'; ?> /><label for="show_image_counts_current_image_number_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="show_image_counts" id="show_image_counts_current_image_number_0" value="0" <?php if (!$row->show_image_counts) echo 'checked="checked"'; ?> /><label for="show_image_counts_current_image_number_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_image_cycle">
                        <td class="spider_label_options">
                          <label><?php echo __('Enable loop:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="enable_loop" id="enable_loop_1" value="1" <?php if ($row->enable_loop) echo 'checked="checked"'; ?> /><label for="enable_loop_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="enable_loop" id="enable_loop_0" value="0" <?php if (!$row->enable_loop) echo 'checked="checked"'; ?> /><label for="enable_loop_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php echo __('Enable AddThis:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="enable_addthis" id="enable_addthis_1" value="1" <?php if ($row->enable_addthis ) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_addthis_profile_id', 'enable_addthis_1')" />
                          <label for="enable_addthis_1"><?php echo __('Yes', 'bwg_back'); ?></label>
                          <input type="radio" name="enable_addthis" id="enable_addthis_0" value="0" <?php if (!$row->enable_addthis ) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_addthis_profile_id', 'enable_addthis_0')" />
                          <label for="enable_addthis_0"><?php echo __('No', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_addthis_profile_id">
                        <td class="spider_label_options">
                          <label for="addthis_profile_id">AddThis <?php echo __('profile id:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="addthis_profile_id" id="addthis_profile_id" value="<?php echo $row->addthis_profile_id; ?>" />
                          <div class="spider_description"><?php echo __('Create an account', 'bwg_back'); ?> <a target="_blank" href="https://www.addthis.com/register"><?php echo __('here', 'bwg_back'); ?></a>.</div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <!--Slideshow-->
          <div class="spider_div_options" id="div_content_4">
            <table style="width: 100%;">
              <tr>
                <td style="width: 50%; vertical-align: top;">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr>
                        <td class="spider_label_options">
                          <label><?php _e('Show Next / Previous buttons:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="autohide_slideshow_navigation" id="autohide_slideshow_navigation_1" value="1" <?php if ($row->autohide_slideshow_navigation) echo 'checked="checked"'; ?> /><label for="autohide_slideshow_navigation_1"><?php _e('On hover', 'bwg_back'); ?></label>
                          <input type="radio" name="autohide_slideshow_navigation" id="autohide_slideshow_navigation_0" value="0" <?php if (!$row->autohide_slideshow_navigation) echo 'checked="checked"'; ?> /><label for="autohide_slideshow_navigation_0"><?php _e('Always', 'bwg_back'); ?></label>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <!--Social options-->
          <div class="spider_div_options" id="div_content_6">
            <!--Instagram-->
            <div style="margin-bottom: 30px;">
              <table>
                <tbody>
                <tbody>
                  <tr>
                    <td class="spider_label_options">
                      <label><?php _e('Gallery autoupdate interval:', 'bwg_back'); ?></label>
                    </td>
                    <td>
                      <input type="number" id="autoupdate_interval_hour" class="spider_int_input" name="autoupdate_interval_hour" min="0" max="24" value="<?php echo floor($row->autoupdate_interval / 60); ?>" />
                      <?php _e('hour', 'bwg_back'); ?>
                      <input type="number" id="autoupdate_interval_min" class="spider_int_input" name="autoupdate_interval_min" min="0" max="59" value="<?php echo floor($row->autoupdate_interval % 60); ?>" />
                      <?php _e('min', 'bwg_back'); ?>
                      <div class="spider_description"><?php _e('Minimum 1 min.', 'bwg_back'); ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td class="spider_label_options">
                      <label><?php _e('Get access token:', 'bwg_back'); ?></label>
                    </td>
                    <td>
                      <?php 
                      if(isset($_GET['access_token'])) {
                        $access_token = esc_html($_GET['access_token']);
                        ?>
                        <script>
                          jQuery(document).ready(function(){
                            bwg_change_option_type('14');
                            bwg_change_tab('bwg_options_box');
                            jQuery('#instagram_access_token').val('<?php echo $access_token; ?>');
                            jQuery('#bwg_save_options').trigger('click');
                          });
                        </script>
                        <?php
                      } 
                      $new_url = urlencode(admin_url('admin.php?page=options_bwg')) . '&response_type=token'; 
                      ?>
                      <div id="login_with_instagram">
                        <a href="https://api.instagram.com/oauth/authorize/?client_id=54da896cf80343ecb0e356ac5479d9ec&scope=basic+public_content&redirect_uri=http://api.web-dorado.com/instagram/?return_url=<?php echo $new_url;?>"><img src="<?php echo WD_BWG_URL . '/images/sign_in_with_instagram.png'; ?>"></a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="spider_label_options">
                      <label><?php _e('Instagram Access Token:', 'bwg_back'); ?></label>
                    </td>
                    <td>
                      <input id="instagram_access_token" name="instagram_access_token" type="text" style="display:inline-block; width:100%;" size="30" value="<?php echo $row->instagram_access_token; ?>" readonly />
                      <div class="spider_description"><?php echo __('Enable creating Instagram galleries.', 'bwg_back'); ?></div>
                      <div style="margin-top:15px;">
                        <input type="button" value="<?php _e('Reset access token', 'bwg_back'); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-reset" name="reset_access_token" onClick="if(confirm('<?php echo addslashes(__('Are you sure you want to reset access token, after resetting it you will need to log in with Instagram again for using plugin', 'bwg_back')); ?>')){ jQuery('#instagram_access_token').val(''); jQuery('#bwg_save_options').trigger('click');}">
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <?php if($wd_bwg_fb) : ?>
            <!--Facebook-->
            <div style="float:right; width:55%">
              <table style="width: 100%;">
                <tbody>
                  <tr>
                    <td class="spider_label_options">
                      <label for="facebook_app_id">Facebook <?php echo __('app Id', 'bwg_back'); ?>: </label>
                    </td>
                    <td>
                      <input type="text" name="facebook_app_id" id="facebook_app_id" size="30" value="<?php echo $row->facebook_app_id ?>" class="" onchange="()" /> 
                      <div class="spider_description"></div>
                    </td>
                  </tr>
                  <tr>
                    <td class="spider_label_options">
                      <label for="facebook_app_secret">Facebook <?php echo __('app Secret:', 'bwg_back'); ?> </label>
                    </td>
                    <td>
                      <input type="text" name="facebook_app_secret" id="facebook_app_secret" size="40" value="<?php echo $row->facebook_app_secret ?>" class="" onchange="()" /> 
                      <div class="spider_description"></div>
                    </td>
                  </tr>		
                  <tr>
                    <td class="spider_label_options">
                      <label for="facebook_log_in">Facebook <?php echo __('login / logout:', 'bwg_back'); ?> </label>
                    </td>
                    <td>
                      <?php echo $this->model->log_in_log_out(); ?>
                    </td>
                  </tr>					
                </tbody>
              </table>
            </div>
            <div style="clear:both"></div>
            <?php endif; ?>
          </div>
          <!--Watermark-->
          <div class="spider_div_options" id="div_content_7">
            <table style="width: 100%;">
              <tr>
                <td class="options_left">
                  <table style="display: inline-table;">
                    <tbody>
                      <tr id="tr_built_in_watermark_type">
                        <td class="spider_label_options">
                          <label><?php _e('Watermark type: ', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_none" value="none" <?php if ($row->built_in_watermark_type == 'none') echo 'checked="checked"'; ?> onClick="bwg_built_in_watermark('watermark_type_none')" />
                            <label for="built_in_watermark_type_none"><?php _e('None', 'bwg_back'); ?></label>
                          <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_text" value="text" <?php if ($row->built_in_watermark_type == 'text') echo 'checked="checked"'; ?> onClick="bwg_built_in_watermark('watermark_type_text')" onchange="preview_built_in_watermark()" />
                            <label for="built_in_watermark_type_text"><?php _e('Text', 'bwg_back'); ?></label>
                          <input type="radio" name="built_in_watermark_type" id="built_in_watermark_type_image" value="image" <?php if ($row->built_in_watermark_type == 'image') echo 'checked="checked"'; ?> onClick="bwg_built_in_watermark('watermark_type_image')" onchange="preview_built_in_watermark()" />
                            <label for="built_in_watermark_type_image"><?php _e('Image', 'bwg_back'); ?></label>
                            <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_url">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_url"><?php _e('Watermark url: ', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" id="built_in_watermark_url" name="built_in_watermark_url" style="width: 68%;" value="<?php echo $row->built_in_watermark_url; ?>" style="display:inline-block;" onchange="preview_built_in_watermark()" />
                          <?php
                          $query_url = add_query_arg(array('action' => 'addImages', 'width' => '700', 'height' => '550', 'extensions' => 'png', 'callback' => 'bwg_add_built_in_watermark_image'), admin_url('admin-ajax.php'));
                          $query_url = wp_nonce_url( $query_url, 'addImages', 'bwg_nonce' );
                          $query_url =  add_query_arg(array('TB_iframe' => '1'), $query_url );
                          ?>
                          <a href="<?php echo $query_url; ?>" id="button_add_built_in_watermark_image" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add thickbox thickbox-preview"
                             title="Add image" 
                             onclick="return false;"
                             style="margin-bottom:5px;">
                            <?php _e('Add Image', 'bwg_back'); ?>
                          </a>
                          <div class="spider_description"><?php _e('Only .png format is supported.', 'bwg_back'); ?></div>
                        </td>
                      </tr>                    
                      <tr id="tr_built_in_watermark_text">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_text"><?php _e('Watermark text: ', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="built_in_watermark_text" id="built_in_watermark_text" style="width: 100%;" value="<?php echo $row->built_in_watermark_text; ?>" onchange="preview_built_in_watermark()" onkeypress="preview_built_in_watermark()" />
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_size">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_size"><?php _e('Watermark size: ', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="built_in_watermark_size" id="built_in_watermark_size" value="<?php echo $row->built_in_watermark_size; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" /> %
                          <div class="spider_description"><?php _e('Enter size of watermark in percents according to image.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_font_size">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_font_size"><?php _e('Watermark font size:', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <input type="text" name="built_in_watermark_font_size" id="built_in_watermark_font_size" value="<?php echo $row->built_in_watermark_font_size; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" /> 
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_font">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_font"><?php _e('Watermark font style: ', 'bwg_back'); ?></label>
                        </td>
                        <td>
                          <select class="select_icon" name="built_in_watermark_font" id="built_in_watermark_font" style="width:120px;" onchange="preview_built_in_watermark()">
                            <?php
                            foreach ($built_in_watermark_fonts as $watermark_font) {
                              ?>
                              <option value="<?php echo $watermark_font; ?>" <?php if ($row->built_in_watermark_font == $watermark_font) echo 'selected="selected"'; ?>><?php echo $watermark_font; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <?php 
                            foreach ($built_in_watermark_fonts as $watermark_font) {
                              ?>
                              <style>
                              @font-face {
                                font-family: <?php echo 'bwg_' . str_replace('.ttf', '', $watermark_font); ?>;
                                src: url("<?php echo WD_BWG_URL . '/fonts/' . $watermark_font; ?>");
                               }
                              </style>
                              <?php
                            }
                          ?>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_color">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_color"><?php _e('Watermark color:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="built_in_watermark_color" id="built_in_watermark_color" value="<?php echo $row->built_in_watermark_color; ?>" class="color" onchange="preview_built_in_watermark()" />
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_opacity">
                        <td class="spider_label_options">
                          <label for="built_in_watermark_opacity"><?php _e('Watermark opacity:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <input type="text" name="built_in_watermark_opacity" id="built_in_watermark_opacity" value="<?php echo $row->built_in_watermark_opacity; ?>" class="spider_int_input" onchange="preview_built_in_watermark()" /> %
                          <div class="spider_description"><?php _e('Opacity value must be in the range of 0 to 100.', 'bwg_back'); ?></div>
                        </td>
                      </tr>
                      <tr id="tr_built_in_watermark_position">
                        <td class="spider_label_options">
                          <label><?php _e('Watermark position:', 'bwg_back'); ?> </label>
                        </td>
                        <td>
                          <table class="bwg_position_table">
                            <tbody>
                              <tr>
                                <td><input type="radio" value="top-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="top-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="top-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "top-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="middle-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="middle-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="middle-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "middle-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              </tr>
                              <tr>
                                <td><input type="radio" value="bottom-left" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-left") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="bottom-center" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-center") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                                <td><input type="radio" value="bottom-right" name="built_in_watermark_position" <?php if ($row->built_in_watermark_position == "bottom-right") echo 'checked="checked"'; ?> onchange="preview_built_in_watermark()"></td>
                              </tr>
                            </tbody>
                          </table>
                          <input type="submit" class="wd-btn wd-btn-primary wd-not-image" title="<?php _e('Set watermark', 'bwg_back'); ?>" style="margin-top: 5px;"
                                 onclick="spider_set_input_value('task', 'save'); spider_set_input_value('watermark', 'image_set_watermark');"
                                 value="<?php _e('Set Watermark', 'bwg_back'); ?>"/>
                          <input type="submit" class="wd-btn wd-btn-primary wd-not-image" title="<?php _e('Reset watermark', 'bwg_back'); ?>" style="margin-top: 5px;"
                                 onclick="spider_set_input_value('task', 'image_recover_all');"
                                 value="<?php echo __('Reset Watermark', 'bwg_back'); ?>"/>
                          <div class="spider_description"></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td class="options_right">
                  <table style="width: 100%; display: inline-table;">
                    <tbody>
                      <tr>
                        <td>
                          <span id="preview_built_in_watermark" style="display:table-cell; background-image:url('<?php echo WD_BWG_URL .    '/images/watermark_preview.jpg'?>');background-size:100% 100%;width:400px;height:400px;padding-top: 4px; position:relative;"></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <input id="task" name="task" type="hidden" value="" />
      <input id="recreate" name="recreate" type="hidden" value="" />
      <input id="watermark" name="watermark" type="hidden" value="" />
      <script>
        // "state" global get var is for checking redirect from facebook.
        window.onload = bwg_change_tab('<?php echo isset($_POST['type_option']) ? esc_html($_POST['type_option']) : (isset($_GET['state']) ? 'bwg_default_box' : 'bwg_options_box'); ?>');
        window.onload = bwg_inputs();
        window.onload = bwg_watermark('watermark_type_<?php echo $row->watermark_type ?>');
        window.onload = bwg_built_in_watermark('watermark_type_<?php echo $row->built_in_watermark_type ?>');
        window.onload = bwg_popup_fullscreen(<?php echo $row->popup_fullscreen; ?>);
        window.onload = bwg_enable_disable(<?php echo $row->show_search_box ? "'', 'tr_search_box_width', 'show_search_box_1'" : "'none', 'tr_search_box_width', 'show_search_box_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->show_search_box ? "'', 'tr_search_box_placeholder', 'show_search_box_1'" : "'none', 'tr_search_box_placeholder', 'show_search_box_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->preload_images ? "'', 'tr_preload_images_count', 'preload_images_1'" : "'none', 'tr_preload_images_count', 'preload_images_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_fullscreen', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_fullscreen', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_info', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_info', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_download', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_download', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_fullsize_image', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_fullsize_image', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_comment', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_comment', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? ($row->popup_enable_comment ? "'', 'tr_comment_moderation', 'popup_enable_comment_1'" : "'none', 'tr_comment_moderation', 'popup_enable_comment_0'") : "'none', 'tr_comment_moderation', 'popup_enable_comment_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? ($row->popup_enable_comment ? "'', 'tr_popup_email', 'popup_enable_comment_1'" : "'none', 'tr_popup_email', 'popup_enable_comment_0'") : "'none', 'tr_popup_email', 'popup_enable_comment_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? ($row->popup_enable_comment ? "'', 'tr_popup_captcha', 'popup_enable_comment_1'" : "'none', 'tr_popup_captcha', 'popup_enable_comment_0'") : "'none', 'tr_popup_captcha', 'popup_enable_comment_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_facebook', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_facebook', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_twitter', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_twitter', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_google', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_google', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_pinterest', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_pinterest', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_ctrl_btn ? "'', 'tr_popup_thumblr', 'popup_enable_ctrl_btn_1'" : "'none', 'tr_popup_thumblr', 'popup_enable_ctrl_btn_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->popup_enable_filmstrip ? "'', 'tr_popup_filmstrip_height', 'popup_enable_filmstrip_1'" : "'none', 'tr_popup_filmstrip_height', 'popup_enable_filmstrip_0'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->slideshow_enable_filmstrip ? "'', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_yes'" : "'none', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_no'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->slideshow_enable_title ? "'', 'tr_slideshow_title_position', 'slideshow_enable_title_yes'" : "'none', 'tr_slideshow_title_position', 'slideshow_enable_title_no'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->slideshow_enable_description ? "'', 'tr_slideshow_description_position', 'slideshow_enable_description_yes'" : "'none', 'tr_slideshow_description_position', 'slideshow_enable_description_no'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->slideshow_enable_music ? "'', 'tr_slideshow_music_url', 'slideshow_enable_music_yes'" : "'none', 'tr_slideshow_music_url', 'slideshow_enable_music_no'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->enable_addthis ? "'', 'tr_addthis_profile_id', 'enable_addthis_yes'" : "'none', 'tr_addthis_profile_id', 'enable_addthis_no'" ?>);
        window.onload = bwg_enable_disable(<?php echo $row->thumb_click_action == 'redirect_to_url' ? "'', 'tr_thumb_link_target', 'thumb_click_action_2'" : "'none', 'tr_thumb_link_target', 'thumb_click_action_" . ($row->thumb_click_action == 'open_lightbox' ? 1 : 3) . "'"; ?>);
        window.onload = preview_watermark();
        window.onload = preview_built_in_watermark();
        window.onload = bwg_show_hide_roles();
      </script>
    </form>
    <?php
  }
}
