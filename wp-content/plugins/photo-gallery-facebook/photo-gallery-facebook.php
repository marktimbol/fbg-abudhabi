<?php

/**
 * Plugin Name: Photo Gallery Facebook 
 * Plugin URI: https://web-dorado.com/products/wordpress-photo-gallery-facebook-plugin.html
 * Description: Addon for adding facebook galleries to photo gallery. 
 * Version: 1.0.1
 * Author: WebDorado
 * Author URI: https://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

add_action('admin_notices', 'check_photo_gallery');
function check_photo_gallery() {
  if (!is_plugin_active('photo-gallery/photo-gallery.php') || !defined('WD_BWG_PRO') || WD_BWG_PRO != true) {
    ?>
    <div class="error">
      <p><?php _e('Photo Gallery Facebook add-on will not work without Photo Gallery Pro plugin.'); ?></p>
    </div>
    <?php
  }
  elseif (version_compare(get_option("wd_bwg_version"), '1.2.59') == -1) {
      ?>
    <div class="error">
      <p><?php _e('Photo Gallery Facebook add-on requires Photo Gallery Pro version 2.2.59 and higher. Please update your plugin.'); ?></p>
    </div>
      <?php
  }
}