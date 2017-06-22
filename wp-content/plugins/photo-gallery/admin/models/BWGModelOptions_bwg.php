<?php

class BWGModelOptions_bwg {

  private $facebook_sdk;
  private $app_id;
  private $app_secret;
  private $access_token;

  public function get_facebook_data($facebook_app_id, $facebook_app_secret) {
    global $wd_bwg_fb;
    if ($wd_bwg_fb && !class_exists('Facebook') && file_exists(WD_BWG_FB_DIR . "/facebook-sdk/facebook.php")) {
      require_once WD_BWG_FB_DIR . "/facebook-sdk/facebook.php";
    }
    else {
      return;
    }
    $this->app_id = $facebook_app_id;
    $this->app_secret = $facebook_app_secret;
    $this->access_token = '';
    $this->facebook_sdk = new Facebook(array(
      'appId'  => $this->app_id,
      'secret' => $this->app_secret,
    ));	  
    if (isset($_POST['app_log_out'])) {
      //setcookie('fbs_'.$this->facebook_sdk->getAppId(), '', time()-100, '/', 'http://localhost/wordpress_rfe/');
      session_destroy();
      //although you need reload the page for loging out
      //so we destroy user access token stored in session var
    }
    if ($this->facebook_sdk->getUser()) {
      try {
      }
      catch (FacebookApiException $e) {
        echo "<!--DEBUG: " . $e . " :END-->";
        error_log($e);
      }
    }
    //echo $this->facebook_sdk->getAccessToken();
    return $this->facebook_sdk->getUser();
  }

  public function log_in_log_out() {
    $user = $this->facebook_sdk->getUser();
    if ($user) {
      try {
      $old_access_token = $this->access_token;
      // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $this->facebook_sdk->api('/me');
        $this->facebook_sdk->setExtendedAccessToken();
        $access_token = $this->facebook_sdk->getAccessToken();
      } catch (FacebookApiException $e) {
      echo '<div class="wd_error"><p><strong>OAuth Error</strong>Error added to error_log: '.$e.'</p></div>';
      error_log($e);
      $user = null;
      }
    }
    // Login or logout url will be needed depending on current user state.
    $app_link_text = $app_link_url = null;
    if ($user && !isset($_POST['app_log_out'])) {
      $app_link_url = $this->facebook_sdk->getLogoutUrl(array('next' => admin_url() . 'admin.php?page=options_bwg'));
      $app_link_text = __("Logout of your app", 'facebook-albums');
    } else {
      $app_link_url = $this->facebook_sdk->getLoginUrl(array('scope' => 'user_photos,user_videos,user_posts'));
        $app_link_text = __('Log into Facebook with your app', 'facebook-albums');
    } ?>
    <input type="hidden" name="facebookalbum[app_id]" value="<?php echo $this->app_id; ?>" />
    <input type="hidden" name="facebookalbum[app_secret]" value="<?php echo $this->app_secret; ?>" />
    <input type="hidden" name="facebookalbum[access_token]" value="<?php echo $this->access_token; ?>" />
    <?php if($user && !isset($_POST['app_log_out'])) : ?>
    <div style="float: right;"><span style="margin: 0 10px;"><?php echo $user_profile['name']; ?></span><img src="https://graph.facebook.com/<?php echo $user_profile['id']; ?>/picture?type=square" style="vertical-align: middle"/></div>
      <ul style="margin:0px;list-style-type:none">
      <li><a href="https://developers.facebook.com/apps/<?php echo $this->app_id; ?>" target="_blank"><?php _e("View your application's settings.", 'facebook-albums'); ?></a></li>
        <input class="button-primary" type="submit" name="app_log_out" value="Log out from app" />
    </ul>
    <?php else :  ?>
      <a href="<?php echo $app_link_url; ?>"><?php echo $app_link_text; ?></a>	   
    <?php endif; ?>
    <div style="clear: both;">&nbsp;</div>	  
    <?php
    /*<!-- <p><?php printf(__('Having issues once logged in? Try <a href="?page=facebook-album&amp;reset_application=%s">resetting application data.</a> <em>warning: removes App ID and App Secret</em>'), wp_create_nonce($current_user->data->user_email)); ?></p>
    <p><strong>Notice!</strong> Your extended access token will only last about 2 months. So visit this page every month or so to keep the access token fresh.</p> -->*/
  }

}