<?php

/**
 * Class for handling embedded media in gallery
 *
 */
class WDWLibraryEmbed {
  public function __construct() {}

  public function get_provider($oembed, $url, $args = '') {
		$provider = false;
		if (!isset($args['discover'])) {
			$args['discover'] = true;
    }
		foreach ($oembed->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;
			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}
			if ( preg_match( $matchmask, $url ) ) {
				$provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				break;
			}
		}
		if ( !$provider && $args['discover'] ) {
			$provider = $oembed->discover($url);
    }
		return $provider;
	}

  /**
   * check host and get data for a given url
   * @return encode_json(associative array of data) on success
   * @return encode_json(array[false, "error message"]) on failure
   *
   * EMBED TYPES
   *
   *  EMBED_OEMBED_YOUTUBE_VIDEO
   *  EMBED_OEMBED_VIMEO_VIDEO
   *  EMBED_OEMBED_DAILYMOTION_VIDEO
   *  EMBED_OEMBED_INSTAGRAM_IMAGE
   *  EMBED_OEMBED_INSTAGRAM_VIDEO
   *  EMBED_OEMBED_INSTAGRAM_POST
   *  EMBED_OEMBED_FLICKR_IMAGE
   *
   *  EMBED_OEMBED_FACEBOOK_IMAGE
   *  EMBED_OEMBED_FACEBOOK_VIDEO
   *  EMBED_OEMBED_FACEBOOK_POST
   *
   *  RULES FOR NEW TYPES
   *
   *  1. begin type name with EMBED_
   *  2. if using WP native OEMBED class, add _OEMBED then
   *  3. add provider name
   *  4. add _VIDEO, _IMAGE FOR embedded media containing only video or image
   *  5. add _DIRECT_URL from static URL of image (not implemented yet)
   *
   */
  public static function add_embed($url, $instagram_data = null) {
    $url = sanitize_text_field(urldecode($url));

    $embed_type = '';
    $host = '';
    /*returns this array*/
    $embedData = array(
      'name' => '',
      'description' => '',
      'filename' => '',
      'url' => '',
      'reliative_url' => '',
      'thumb_url' => '',
      'thumb' => '',
      'size' => '',
      'filetype' => '',
      'date_modified' => '',
      'resolution' => '',
      'redirect_url' => '');

    $accepted_oembeds = array(
      'YOUTUBE' => '/youtube/',
      'VIMEO' => '/vimeo/',
      'FLICKR' => '/flickr/',
      'INSTAGRAM' => '/instagram/',
      'DAILYMOTION' => '/dailymotion/'
      );
    
    /*check if url is from facebook */
    //explodes URL based on slashes
    $first_token  = strtok($url, '/');
    $second_token = strtok('/');
    $third_token = strtok('/');	 
    //for video's url
    $fourth = strtok('/');
    //fifth is for post's fbid if url is post url
    $fifth = strtok('/');
    //sixth is for video's fbid if url is video url
    $sixth = strtok('/');
    if($second_token === 'www.facebook.com') {
      $facebook_sdk = self::get_facebook_sdk();
      if(is_array($facebook_sdk) && $facebook_sdk[0] == 'error')
        return json_encode($facebook_sdk);
      //if url contain photo.php string thow it can be video or photo url
      //in this case we mean profile photo
      $pos_for_photo_or_video_url = strpos($third_token, 'photo.php');	  
      
      //in this case we mean page photo
      $pos_for_page_photo = strpos($fourth, 'photos');	  
      
      //if embeded url is video's url or photo's url
      if($pos_for_photo_or_video_url !== false) {	    
        if(strpos($third_token, '?v=') !== false) {
        //if embeded url is video's
          $pos_for_video_url = explode('?v=', $third_token);
          $video_id = $pos_for_video_url[1];
          $video_data = self::get_facebook_video_data($video_id, $facebook_sdk);
          $data = self::get_facebook_valid_data_for_video($video_data, 'regular'); 	
          return json_encode($data);
        }
        //if embeded url is profile photo's
        elseif(strpos($third_token, '?fbid=') !== false) {       
          $photo_data = self::get_facebook_photo_data(htmlspecialchars_decode($url), $facebook_sdk, 'profile');
          $data = self::get_facebook_valid_data_for_photo($photo_data, 'regular');
          return json_encode($data);
        }
        else {
          return json_encode(array("error", "Does not supported Facebook photo url."));
        }
      }
      //if embeded url is page photo's
      elseif($pos_for_page_photo !== false) {	  
        //pass third arg as photo id
        $photo_data = self::get_facebook_photo_data(htmlspecialchars_decode($url), $facebook_sdk, $sixth);
        $data = self::get_facebook_valid_data_for_photo($photo_data, 'regular');
        return json_encode($data);
      }
      //if embeded url is another type of video's url
      elseif($fourth == 'videos') {	    
        if($sixth != NULL) {
          $video_id  = $sixth;
        }
        elseif($fifth != NULL) {
          $video_id  = $fifth;
        }
        else {
          return json_encode(array("error", "Does not supported Facebook video url."));
        }
        $video_data = self::get_facebook_video_data($video_id, $facebook_sdk);
        $data = self::get_facebook_valid_data_for_video($video_data, 'regular'); 	
        return json_encode($data);
      }
      //if embeded url is post's url
      elseif($fourth == 'posts') {
        //embed post is disabled for a while
        return json_encode(array("error", "Embed post is disabled for a while"));
        
        $post_id  = $fifth;
        $post_data = self::get_facebook_post_data($post_id, $facebook_sdk);
        $data = self::get_facebook_valid_data_for_post($post_data);
        return json_encode($data);
      }
      else {
        return json_encode(array("error", "Incorect url."));
      }
    }
	
    /*check if we can embed this using wordpress class WP_oEmbed */
    if ( !function_exists( '_wp_oembed_get_object' ) )
      include( ABSPATH . WPINC . '/class-oembed.php' );
    // get an oembed object
    $oembed = _wp_oembed_get_object();
    if (method_exists($oembed, 'get_provider')) {
      // Since 4.0.0
      $provider = $oembed->get_provider($url);
    }
    else {
      $provider = self::get_provider($oembed, $url);
    }
    foreach ($accepted_oembeds as $oembed_provider => $regex) {
      if(preg_match($regex, $provider)==1){
        $host = $oembed_provider;
      }
    }
    /*
		 * Wordpress oembed not recognize instagram post url,
		 * so we check manually.
		*/
		if (!$host) {
			$parse = parse_url($url);
			$host = ($parse['host'] == "www.instagram.com") ? 'INSTAGRAM' : false;
		}
		/*return json_encode($host); for test*/
    /*handling oembed cases*/    
    if ($host) {
      /*instagram is exception*/
      /*standard oembed fetching does not return thumbnail_url! so we do it manually*/
      if ($host == 'INSTAGRAM' && strtolower(substr($url,-4)) != 'post') {
        $embed_type = 'EMBED_OEMBED_INSTAGRAM';

        $insta_host_and_id = strtok($url, '/')."/".strtok('/')."/".strtok('/')."/".strtok('/');
        $insta_host = strtok($url, '/')."/".strtok('/')."/".strtok('/')."/";
        $filename = str_replace($insta_host, "", $insta_host_and_id);
        $thumb_filename = $filename;

        if (!$instagram_data) {
          $instagram_data = new stdClass();
          $get_embed_data = wp_remote_get("http://api.instagram.com/oembed?url=http://instagram.com/p/".$filename);
          if ( is_wp_error( $get_embed_data ) ) {
            return json_encode(array("error", "cannot get Instagram data"));
          }
          $result  = json_decode(wp_remote_retrieve_body($get_embed_data));
          if(empty($result)){
            return json_encode(array("error", wp_remote_retrieve_body($get_embed_data)));
          }
          list($img_width, $img_height) = @getimagesize('https://instagram.com/p/' . $thumb_filename . '/media/?size=l');
          $instagram_data->caption = new stdClass();
          $instagram_data->caption->text = $result->title;
          $instagram_data->images = new stdClass();
          $instagram_data->images->standard_resolution = new stdClass();
          $instagram_data->images->standard_resolution->width = $img_width;
          $instagram_data->images->standard_resolution->height = $img_height;

          /*get instagram post html page, parse its DOM to find video URL*/
          $DOM = new DOMDocument;
          libxml_use_internal_errors(true);
          $html_code = wp_remote_get($url);
          if ( is_wp_error( $html_code ) ) {
            return json_encode(array("error", "cannot get Instagram data"));
          }
          $html_body = wp_remote_retrieve_body($html_code);
          if(empty($html_body)){
            return json_encode(array("error", wp_remote_retrieve_body($html_code)));
          }

          $DOM->loadHTML($html_body);
          $finder = new DomXPath($DOM);
          $query = "//meta[@property='og:video']";
          $nodes = $finder->query($query);
          $node = $nodes->item(0);
          if ($node) {
            $length = $node->attributes->length;
            for ($i = 0; $i < $length; ++$i) {
              $name = $node->attributes->item($i)->name;
              $value = $node->attributes->item($i)->value;
              if ($name == 'content') {
                $filename = $value;
              }
            }
            $instagram_data->videos = new stdClass();
            $instagram_data->videos->standard_resolution = new stdClass();
            $instagram_data->videos->standard_resolution->url = $filename;
            $instagram_data->type = 'video';
          }
          else {
            $instagram_data->type = 'image';
          }
        }
        $embedData = array(
          'name' => isset($instagram_data->caption->text) ? htmlspecialchars($instagram_data->caption->text) : '',
          'description' => isset($instagram_data->caption->text) ? htmlspecialchars($instagram_data->caption->text) : '',
          'filename' => $filename,
          'url' => $url,
          'reliative_url' => $url,
          'thumb_url' => 'https://instagram.com/p/' . $thumb_filename . '/media/?size=t',
          'thumb' => 'https://instagram.com/p/' . $thumb_filename . '/media/?size=t',
          'size' => '',
          'filetype' => $embed_type,
          'date_modified' => date('d F Y, H:i'),
          'resolution' => $instagram_data->images->standard_resolution->width . " x " . $instagram_data->images->standard_resolution->height . " px",
          'redirect_url' => '');
        if ($instagram_data->type == 'video') {
          $embedData['filename'] = $instagram_data->videos->standard_resolution->url;
          $embedData['filetype'] .= '_VIDEO';
        }
        else {
          $embedData['filetype'] .= '_IMAGE'; 
        }

        return json_encode($embedData);
      }
      if ($host == 'INSTAGRAM' && strtolower(substr($url,-4)) == 'post') {
        /*check if instagram post*/
        $url = substr($url, 0, -4);
        $embed_type = 'EMBED_OEMBED_INSTAGRAM_POST';  
        parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
        $matches = array();
        $filename = '';
        $regex = "/^.*?instagram\.com\/p\/(.*?)[\/]?$/";
        if(preg_match($regex, $url, $matches)){
          $filename = $matches[1];
        }
        if (!$instagram_data) {
          $get_embed_data = wp_remote_get("http://api.instagram.com/oembed?url=http://instagram.com/p/".$filename);
          if ( is_wp_error( $get_embed_data ) ) {
            return json_encode(array("error", "cannot get Instagram data"));
          }
          $result  = json_decode(wp_remote_retrieve_body($get_embed_data));
          if(empty($result)){
            return json_encode(array("error", wp_remote_retrieve_body($get_embed_data)));
          }
          list($img_width, $img_height) = @getimagesize('https://instagram.com/p/' . $filename . '/media/?size=l');
          $instagram_data->caption = new stdClass();
          $instagram_data->caption->text = $result->title;
          $instagram_data->images = new stdClass();
          $instagram_data->images->standard_resolution = new stdClass();
          $instagram_data->images->standard_resolution->width = $img_width;
          $instagram_data->images->standard_resolution->height = $img_height;
        }
        $embedData = array(
          'name' => htmlspecialchars($instagram_data->caption->text),
          'description' => htmlspecialchars($instagram_data->caption->text),
          'filename' => $filename,
          'url' => $url,
          'reliative_url' => $url,
          'thumb_url' => 'https://instagram.com/p/' . $filename . '/media/?size=t',
          'thumb' => 'https://instagram.com/p/' . $filename . '/media/?size=t',
          'size' => '',
          'filetype' => $embed_type,
          'date_modified' => date('d F Y, H:i'),
          'resolution' => $instagram_data->images->standard_resolution->width . " x " . $instagram_data->images->standard_resolution->height . " px",
          'redirect_url' => '');
 
        return json_encode($embedData);      
      }

      $result = $oembed->fetch( $provider, $url);
      /*no data fetched for a known provider*/
      if(!$result){
          return json_encode(array("error", "please enter ". $host . " correct single media URL"));
      }
      else{/*one of known oembed types*/
        $embed_type = 'EMBED_OEMBED_'.$host;
        switch ($embed_type) {
          case 'EMBED_OEMBED_YOUTUBE':
            $youtube_regex = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#";
            $matches = array();
            preg_match($youtube_regex , $url , $matches);
            $filename = $matches[0];

            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_VIMEO':
            
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $result->video_id,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_FLICKR':
            $matches = preg_match('~^.+/(\d+)~',$url,$matches);
            $filename = $matches[1];
            /*if($result->type =='photo')
              $embed_type .= '_IMAGE';
            if($result->type =='video')
              $embed_type .= '_VIDEO';*/
              /*flickr video type not implemented yet*/
              $embed_type .= '_IMAGE';
                         
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' =>substr($result->thumbnail_url, 0, -5)."b.jpg", 
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type,
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            break;
          
          case 'EMBED_OEMBED_DAILYMOTION':
            $filename = strtok(basename($url), '_');

            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_GETTYIMAGES':
          /*not working yet*/
            $filename = strtok(basename($url), '_');
            
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type,
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
         
          default:
            return json_encode(array("error", "unknown URL host"));
            break;
        }
      }
    }/*end of oembed cases*/
    else {
      /*check for direct image url*/
      /*check if something else*/
      /*not implemented yet*/
      return json_encode(array("error", "unknown URL"));
    }
    return json_encode(array("error", "unknown URL"));
  }


/** 
 * client side analogue is function spider_display_embed in bwg_embed.js
 *
 * @param embed_type: string , one of predefined accepted types
 * @param embed_id: string, id of media in corresponding host, or url if no unique id system is defined for host
 * @param attrs: associative array with html attributes and values format e.g. array('width'=>"100px", 'style'=>"display:inline;")
 * 
 */

  public static function display_embed($embed_type, $file_url, $embed_id = '', $attrs = array()) {
    $html_to_insert = '';

    switch ($embed_type) {
      case 'EMBED_OEMBED_YOUTUBE_VIDEO':
        $oembed_youtube_html ='<iframe ';
        if ($embed_id != '') {
          $oembed_youtube_query_args = array();
          if (strpos($embed_id, "?t=") !== FALSE ) {
            $seconds = 0;
            $start_info = substr($embed_id, (strpos($embed_id,"?t=") + 3), strlen($embed_id));
            $embed_id = substr($embed_id, 0, strpos($embed_id, "?t="));
            if (strpos($start_info, "h") !== FALSE) {
               $hours = substr($start_info, 0,strpos($start_info, "h"));
               $seconds += $hours * 3600;
            }
            if (strpos($start_info, "m") !== FALSE) {
              if (strpos($start_info, "h") !== FALSE) {
                 $minutes = substr($start_info, strpos($start_info, "h") + 1, -strpos($start_info, "m"));
              } else {
                 $minutes = substr($start_info, 0, strpos($start_info, "m"));
              }
               $seconds += $minutes * 60;
            }
            if (strpos($start_info, "s") !== FALSE ) {
               if (strpos($start_info, "m") !== FALSE) {
                  $sec = substr($start_info, strpos($start_info, "m") + 1, -1);
               } else {
                  $sec = substr($start_info, 0, -1);
               }
               $seconds += $sec;
            }
            $oembed_youtube_query_args = array('start' => $seconds);
          }
          $oembed_youtube_query_args += array('enablejsapi' => 1, 'wmode' => 'transparent');
          $oembed_youtube_html .= ' src="' . add_query_arg($oembed_youtube_query_args, '//www.youtube.com/embed/'. $embed_id) . '"';
        }
        foreach ($attrs as $attr => $value) {
          if (preg_match('/src/i', $attr) === 0) {
            if ($attr != '' && $value != '') {
              $oembed_youtube_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_youtube_html .= " ></iframe>";
        $html_to_insert .= $oembed_youtube_html;
        break;
      case 'EMBED_OEMBED_VIMEO_VIDEO':
        $oembed_vimeo_html ='<iframe ';
        if($embed_id!=''){
          $oembed_vimeo_html .= ' src="' . '//player.vimeo.com/video/'. $embed_id . '?enablejsapi=1"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_vimeo_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_vimeo_html .= " ></iframe>";
        $html_to_insert .= $oembed_vimeo_html;
        break;
      case 'EMBED_OEMBED_FLICKR_IMAGE':
         $oembed_flickr_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_flickr_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_flickr_html .= " >";
        if($embed_id!=''){
        $oembed_flickr_html .= '<img src="'.$embed_id.'"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'auto !important'.
        '; height:'. 'auto !important' . 
        ';">';      
        }
        $oembed_flickr_html .="</div>";

        $html_to_insert .= $oembed_flickr_html;
        break;
      case 'EMBED_OEMBED_FLICKR_VIDEO':
        # code...not implemented yet
        break;  
      case 'EMBED_OEMBED_INSTAGRAM_VIDEO':
        $oembed_instagram_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if($embed_id!=''){
        $oembed_instagram_html .= '<video style="width:auto !important; height:auto !important; max-width:100% !important; max-height:100% !important; margin:0 !important;" controls>'.
        '<source src="'. $embed_id .
        '" type="video/mp4"> Your browser does not support the video tag. </video>'; 
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_INSTAGRAM_IMAGE':
        $oembed_instagram_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if($embed_id!=''){
        $oembed_instagram_html .= '<img src="//instagram.com/p/'.$embed_id.'/media/?size=l"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'auto !important'.
        '; height:'. 'auto !important' . 
        ';">';
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
	  case 'EMBED_OEMBED_FACEBOOK_IMAGE':
        $oembed_facebook_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_facebook_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_facebook_html .= " >";
        if($embed_id!=''){
        $oembed_facebook_html .= '<img src="'.$file_url.'"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'auto !important'.
        '; height:'. 'auto !important' . 
        ';">';
        }
        $oembed_facebook_html .="</div>";
        $html_to_insert .= $oembed_facebook_html;
        break;	  
	  case 'EMBED_OEMBED_FACEBOOK_VIDEO':
        $oembed_facebook_html ='<iframe class="bwg_fb_video"';
        if($embed_id!=''){
          $oembed_facebook_html .= ' src="//www.facebook.com/video/embed?video_id=' . $file_url . '&enablejsapi=1&wmode=transparent"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_facebook_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_facebook_html .= " ></iframe>";
        $html_to_insert .= $oembed_facebook_html;
        break;	  
      case 'EMBED_OEMBED_INSTAGRAM_POST':
        $oembed_instagram_html ='<div ';
        $id = '';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
              if(strtolower($attr) == 'class') {
                $class = $value;
              }
            }
          }
        }
        $oembed_instagram_html .= " >";       
        if($embed_id!=''){
        $oembed_instagram_html .= '<iframe class="inner_instagram_iframe_'.$class.'" src="//instagr.am/p/'.$embed_id.'/embed/?enablejsapi=1"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'100%'.
        '; height:'. '100%' .
        '; margin:0'.
        '; display:table-cell; vertical-align:middle;"'.
        'frameborder="0" scrolling="no" allowtransparency="false" allowfullscreen'. 
        '></iframe>';
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_DAILYMOTION_VIDEO':
        $oembed_dailymotion_html ='<iframe ';
        if($embed_id!=''){
          $oembed_dailymotion_html .= ' src="' . '//www.dailymotion.com/embed/video/'. $embed_id . '?api=postMessage"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_dailymotion_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_dailymotion_html .= " ></iframe>";
        $html_to_insert .= $oembed_dailymotion_html;
        break;
      default:
        # code...
        break;
    }

    echo $html_to_insert;

  }


/**
 *
 * @return json_encode(array("error","error message")) on failure
 * @return json_encode(array of data of instagram user recent posts) on success 
 */
  public static function add_instagram_gallery($instagram_user, $access_token, $whole_post, $autogallery_image_number) {
    @set_time_limit(0);
    $instagram_user = sanitize_text_field(urldecode($instagram_user));
		
    $instagram_user_response = wp_remote_get("https://api.instagram.com/v1/users/search?q=".$instagram_user."&access_token=".$access_token."&count=1"); 
    if ( is_wp_error( $instagram_user_response ) ) {
      return json_encode(array("error", "cannot get Instagram user parameters"));
    }
    $user_json = wp_remote_retrieve_body($instagram_user_response);
    $response_code = json_decode($user_json)->meta->code;

    /*
    instagram API returns
    
    *wrong username
    {"meta":{"code":200},"data":[]}
    
    *wrong access_token
    {"meta":{"error_type":"OAuthParameterException","code":400,"error_message":"The access_token provided is invalid and does not match a valid application."}}
  
    */

    if($response_code != 200){
      return json_encode(array("error", json_decode($user_json)->meta->error_message));
    }
    

    if(!property_exists(json_decode($user_json), 'data')){
      return json_encode(array("error", "cannot get Instagram user parameters"));
    }
    if(empty(json_decode($user_json)->data)){
      return json_encode(array("error", "wrong Instagram username"));
    }
    $user_data = json_decode($user_json)->data[0];
    $user_id = $user_data->id;  
    $instagram_posts_response = wp_remote_get("https://api.instagram.com/v1/users/".$user_id."/media/recent/?access_token=".$access_token."&count=".$autogallery_image_number);
    if ( is_wp_error( $instagram_posts_response ) ) {
      return json_encode(array("error", "cannot get Instagram user posts"));
    }
    $posts_json = wp_remote_retrieve_body($instagram_posts_response);
    $response_code = json_decode($posts_json)->meta->code;
    
    /*
    instagram API returns

    *private user
    '{"meta":{"error_type":"APINotAllowedError","code":400,"error_message":"you cannot view this resource"}}'
  
    */

    if($response_code != 200){
      return json_encode(array("error", json_decode($posts_json)->meta->error_message));
    }
    if(!property_exists(json_decode($posts_json), 'data')){
      return json_encode(array("error", "cannot get Instagram user posts data"));
    }
    /*
    if instagram user has no posts
    */
    if(empty(json_decode($posts_json)->data)){
      return json_encode(array("error", "Instagram user has no posts"));
    }
    $posts_array = json_decode($posts_json)->data;
    $instagram_album_data = array();
    if($whole_post==1){
      $post_flag ="post";
    }
    else{
      $post_flag =''; 
    }
    foreach ($posts_array as $post_data) {
      $url = $post_data->link . $post_flag;
      
      $post_to_embed = json_decode(self::add_embed($url, $post_data), true);
      
      /* if add_embed function did not indexed array because of error */
      if(!isset($post_to_embed[0]) ){
        array_push($instagram_album_data, $post_to_embed);
      }

    }
    
    return json_encode($instagram_album_data);
    
  }
  
  public static function get_facebook_photo_data($url, $facebook_sdk, $type_or_id) {
    if($type_or_id == 'profile') {
      $fbid = explode('&set=', $url);
      $fbid = $fbid['0'];
      $fbid =  explode('=', $fbid);
      $fbid = $fbid['1'];
    }
    else {
      $fbid = $type_or_id;
    }
    //passing fbid to photo_id var for api_call
    $photo_id = $fbid;	
    try { 
      $api_call = '/' . $photo_id.'?fields=link,name,images,width,height';						
      $photo = $facebook_sdk->api($api_call);
    } catch (FacebookApiException $e) {
      //error_log($e); "Does not supported Facebook photo url."
      $error = explode('.', $e->getMessage());
      $photo = array("error", $error[0]);
    }
    return $photo;
  }
  
  public static function get_facebook_video_data($video_id, $facebook_sdk) {    
    global $wpdb;
    global $wd_bwg_options;
    $app_id = $wd_bwg_options->facebook_app_id;
    $app_secret = $wd_bwg_options->facebook_app_secret;
    try {
      $api_call = '/' . $video_id.'?fields=source,created_time,updated_time,from,description,format';						
      $video = $facebook_sdk->api($api_call);
    } 
    catch (FacebookApiException $e) {
      //error_log($e); "Does not supported Facebook video url."
      $error = explode('.', $e->getMessage());
      $video = array("error", $error[0]);
    }
    return $video;
  }
  
  public static function get_facebook_post_data($post_id, $facebook_sdk) {
    global $wpdb;
    global $wd_bwg_options;
    $app_id = $wd_bwg_options->facebook_app_id;
    $app_secret = $wd_bwg_options->facebook_app_secret;
    try {
      $api_call = '/' . $post_id.'/';
      $post = $facebook_sdk->api($api_call);
    } catch (FacebookApiException $e) {
      //error_log($e); "Does not supported Facebook post url."
      $error = explode('.', $e->getMessage());
      $post = array("error", $error[0]);
    }
    return $post;
  }
  
  public static function get_facebook_valid_data_for_post($post_data) {
    if(array_key_exists (0, $post_data) && $post_data[0] == 'error')
      return $post_data;
    else { 
      //understaning if video or photo post
      if(array_key_exists('embed_html', $post_data)) {
        //if video post
        return self::get_facebook_valid_data_for_video($post_data, 'post');
      } 
      elseif(array_key_exists('images', $post_data)) {
        //if photo post
        return self::get_facebook_valid_data_for_photo($post_data, 'post');
      }	  
        else {
        return array("error", "Does not supported Facebook post url.");
      }
    }
  }
  
  public static function get_facebook_valid_data_for_video($video_data, $content_type) {   			
    if(array_key_exists (0, $video_data) && $video_data[0] == 'error')
      return $video_data;

    $video = new stdClass();
    $video->name = isset($video_data['description']) ? $video_data['description'] : $video_data['id'];
    $video->filename = isset($video_data['description']) ? $video_data['description'] : $video_data['id'];
    $video->size = '';
    $video->description = $video_data['description'];
    //$video->filetype = ($content_type == 'regular' ) ? 'EMBED_OEMBED_FACEBOOK_VIDEO' : 'EMBED_OEMBED_FACEBOOK_POST';
    $video->filetype = 'EMBED_OEMBED_FACEBOOK_VIDEO';
    $video->date_modified = date('d F Y, H:i');
    //$video->url = ($content_type == 'regular' ) ? $video_data['id'] : 'https://www.facebook.com/' . $video_data['from']['id'] . '/posts/' . $video_data['id'];
    //$video->reliative_url = ($content_type == 'regular' ) ? $video_data['id'] : 'https://www.facebook.com/' . $video_data['from']['id'] . '/posts/' . $video_data['id'];
    $video->url = $video_data['id'];
    $video->reliative_url = $video_data['id'];
    $video->redirect_url = '';
    
    $videos_size_count = count($video_data['format']);
    if($videos_size_count) {	  
        if(array_key_exists('1', $video_data['format']))
        $j = 1;	    
      else
        $j = $videos_size_count-1;
        
      //for thumb info
      $video->thumb = $video_data['format'][$j]['picture'];
      $video->thumb_url = $video_data['format'][$j]['picture'];
      //for resolution info
      $width = $video_data['format'][$videos_size_count-1]['width'];
      $height = $video_data['format'][$videos_size_count-1]['height'];
      $resolution = (string) $width . ' x ' . (string) $height . ' px';
      $video->resolution = $resolution;
    }
    return $video;
  }
  
  public static function get_facebook_sdk() {
    global $wd_bwg_fb;
    if ($wd_bwg_fb && !class_exists('Facebook') && file_exists(WD_BWG_FB_DIR . "/facebook-sdk/facebook.php")) {
      require_once WD_BWG_FB_DIR . "/facebook-sdk/facebook.php";
    }
    elseif (!class_exists('Facebook'))  {
      return array("error", "Please install photo-gallery-facebook plugin.");
    }
    global $wpdb;
    global $wd_bwg_options;
    $app_id = $wd_bwg_options->facebook_app_id;
    $app_secret = $wd_bwg_options->facebook_app_secret;
    $facebook_sdk = new Facebook(array(
      'appId'  => $app_id,
      'secret' => $app_secret,
    ));
    if (!empty($facebook_sdk)) {
      try {
        //if appId an appSecret exists and corect
        $api_call = '/' . $app_id;
        $app = $facebook_sdk->api($api_call);
      }
      catch (FacebookApiException $e) {
        //error_log($e);
        $error = explode('.', $e->getMessage());
        //return array("error", $error[0] . '. Check AppID and AppSecret.');
        return array("error", 'You do not have Facebook app Id or app Secret (Or they are incorrect). Input the values in Options > Social options.');
      }
    }
    else {
      return array("error", "You do not have Facebook app Id or app Secret (Or they are incorrect). Input the values in Options > Social options.");
    }
    return $facebook_sdk;
  }

  public static function get_facebook_album_data($album_url, $album_limit) {
    $facebook_sdk = self::get_facebook_sdk();
    //containing errors
    if(is_array($facebook_sdk))
      return $facebook_sdk;
    //explodes url based on slashes url based on slashes, we need the end of the url
    $first_token  = strtok($album_url, '/');
    $second_token = strtok('/');
    $error = array("error", "Incorrect url.");
    if ($second_token != 'www.facebook.com') {
      return $error;
    }
    $album_data = array();
    if (strpos($album_url, '?set=') === FALSE) {
      $album_id = explode('album_id=', $album_url);
      if (!array_key_exists (1, $album_id)) {
        return $error;
      }
      $album_id = $album_id[1];
      $album_id = explode('&', $album_id);
      if (!array_key_exists (0, $album_id)) {
        return $error;
      }
      $album_id = $album_id[0];
    }
    else {
      $album_id = explode('?set=', $album_url); //from album url
      if (!array_key_exists (1, $album_id)) {
        return $error;
      }
      $album_id = $album_id['1'];
      //explodes section by periods, Album ID is first of the 3 sets of numbers
      $album_id = explode('.', $album_id);
      if (!array_key_exists (1, $album_id)) {
        return $error;
      }
      $album_id = $album_id['1'];
    }
    try {
      $api_call = '/' . $album_id.'/photos?fields=source,link,name,images,from,album' . ($album_limit ? '&limit=' . $album_limit : '');
      $album = $facebook_sdk->api($api_call);
      if (array_key_exists ('data', $album)) {
        $album_data = array_merge($album_data, $album['data']);
      }
      while (array_key_exists('paging', $album) && (count($album_data) < $album_limit || !$album_limit)) {
        $api_call = substr($album['paging']['next'], strpos($album['paging']['next'], '/', 28)); //31 is length of 'https://graph.facebook.com/v2.5' & 28 is somewhere after 'facebook.com' and before album_id
        $album = $facebook_sdk->api($api_call);
        if (array_key_exists('data', $album)) {
          $album_data = array_merge($album_data, $album['data']);
        }
      }
      if ($album_limit) {
        $album_data = array_slice($album_data, 0, $album_limit);
      }
    }
    catch (FacebookApiException $e) {
      //error_log($e); "Does not supported Facebook album url."
      $error = explode('.', $e->getMessage());
      //$album = array("error", $error[0]);
      $album = array("error", 'Incorrect or private album Url');
    }
    return $album_data;
  }

  public static function get_facebook_valid_data_for_album($album_data, $content_type) {
    if(array_key_exists (0, $album_data) && $album_data[0] == 'error')
      return $album_data;
    
    if(count($album_data) == 0)
      return array("error", "Album is empty or belongs to another Facebook user");
        
    $files = array();
    for($i=0; $i<count($album_data); $i++) {
      $images = new stdClass();
      $images_size_count = count($album_data[$i]['images']);
        $push = false;	  
      for($j=0; $j<$images_size_count; $j++) {
      if($j==0) {
        $source = $album_data[$i]['images'][$j]['source'];
        //get photo's width and height for resolution
        $width = $album_data[$i]['images'][$j]['width'];
        $height = $album_data[$i]['images'][$j]['height'];
        $resolution = $width . ' x ' . $height . ' px';
        
        //get photo's fbid for filrname feild and for url if embed type is post
        $fbid = $album_data[$i]['id'];          		   
        
        //$images->url = ($content_type == 'regular' ) ? $source : 'https://www.facebook.com/' . $album_data['data'][$i]['from']['id'] . '/posts/' . $fbid;
        //$images->reliative_url = ($content_type == 'regular' ) ? $source : 'https://www.facebook.com/' . $album_data['data'][$i]['from']['id'] . '/posts/' . $fbid;
        $images->url = $source;
        $images->reliative_url = $source;
        $images->resolution = $resolution;
        $images->name = (isset($album_data[$i]['name']) ? $album_data[$i]['name'] : $fbid );
        $images->filename = $fbid;
        $images->description = $images->name;
        $images->size = '';
        //$images->filetype = ($content_type == 'regular' ) ? 'EMBED_OEMBED_FACEBOOK_IMAGE' : 'EMBED_OEMBED_FACEBOOK_POST';
        $images->filetype = 'EMBED_OEMBED_FACEBOOK_IMAGE';
        $images->date_modified = date('d F Y, H:i');
        $images->redirect_url = '';
            
        if($images_size_count <= 3) {
              $images->thumb = $source;
          $images->thumb_url = $source;
        $push = true;
            }			
      }
      else if($j==3 && $images_size_count > 3) {
        $thumb = $album_data[$i]['images'][$j]['source'];
        $images->thumb = $thumb;
        $images->thumb_url = $thumb;
        $push = true;
      }
      if($push) {
        array_push($files, $images);
        break;
        }
      }
    }
    
    return $files;
  }
  
  public static function get_facebook_valid_data_for_photo($photo_data, $content_type) {	
	if(array_key_exists (0, $photo_data) && $photo_data[0] == 'error')
	  return $photo_data;

	$image = new stdClass();
	$images_size_count = count($photo_data['images']);
	$push = false;
	for($i=0; $i<$images_size_count; $i++) {  
	  if($i==0) {
		  $source = $photo_data['images'][$i]['source'];
		  $width = $photo_data['images'][$i]['width'];
		  $height = $photo_data['images'][$i]['height'];
		  $resolution = $width . ' x ' . $height . ' px';		  
		  //get photo's fbid for filrname feild
          $fbid = $photo_data['id'];		  	
		  
		  //embed post disabled for a while		  
		  //$image->url = ($content_type == 'regular' ) ? $source : 'https://www.facebook.com/' . $photo_data['from']['id'] . '/posts/' . $fbid;
		  //$image->reliative_url = ($content_type == 'regular' ) ? $source : 'https://www.facebook.com/' . $photo_data['from']['id'] . '/posts/' . $fbid;
		  
		  $image->url = $source;
		  $image->reliative_url = $source;
		  
		  $image->resolution = $resolution;
		  $image->name = (isset($photo_data['name']) ? $photo_data['name'] : $fbid );
		  $image->filename = $fbid;
		  $image->description = $image->name;
		  $image->size = '';
		  //$image->filetype = ($content_type == 'regular' ) ? 'EMBED_OEMBED_FACEBOOK_IMAGE' : 'EMBED_OEMBED_FACEBOOK_POST';
		  $image->filetype = 'EMBED_OEMBED_FACEBOOK_IMAGE';
		  $image->date_modified = date('d F Y, H:i');
		  $image->redirect_url = '';		  
          
		  if($images_size_count <= 3) {
            $image->thumb = $source;
		    $image->thumb_url = $source;
			$push = true;
          }		  
		}
		else if($i==3 && $images_size_count > 3) {
		  $thumb = $photo_data['images'][$i]['source'];
		  $image->thumb = $thumb;
		  $image->thumb_url = $thumb;
		  $push = true;
		}		
		if($push) {
		  break;
	    }
  	}	
	return $image;
  }
  
/**
 *
 * @return array of galleries,
 * @return array(false, "error message"); 
 *  
 */  

  public static function check_instagram_galleries(){
    
    global $wpdb;
    $instagram_galleries = $wpdb->get_results( "SELECT id, gallery_type, gallery_source, update_flag, autogallery_image_number  FROM " . $wpdb->prefix . "bwg_gallery WHERE gallery_type='instagram' OR gallery_type='instagram_post'", OBJECT );
       
    $galleries_to_update = array();
    if($instagram_galleries){
      foreach ($instagram_galleries as $gallery) {
        if($gallery->update_flag == 'add' || $gallery->update_flag == 'replace'){
          array_push($galleries_to_update, $gallery);
        }
      }
      if(!empty($galleries_to_update)){
        return $galleries_to_update;
      }
      else{
        return array(false, "No instagram gallery has to be updated");
      }
    }
    else{
      return array(false,"There is no instagram gallery");
    }
  }

  public static function check_facebook_galleries() {
    global $wpdb;
    $facebook_galleries = $wpdb->get_results( "SELECT id, gallery_type, gallery_source, update_flag, autogallery_image_number  FROM " . $wpdb->prefix . "bwg_gallery WHERE gallery_type='facebook' OR gallery_type='facebook_post'");
       
    $galleries_to_update = array();
    if($facebook_galleries){
      foreach ($facebook_galleries as $gallery) {
        if($gallery->update_flag == 'add' || $gallery->update_flag == 'replace'){
          array_push($galleries_to_update, $gallery);
        }
      }
      if(!empty($galleries_to_update)){
        return $galleries_to_update;
      }
      else{
        return array(false, "No facebook gallery has to be updated");
      }
    }
    else{
      return array(false,"There is no facebook gallery");
    }
  }

/**
 *
 * @return array(true, "refresh time"); 
 * @return array(false, "error message"); 
 *  
 */

  public static function refresh_social_gallery($gallery) {
    global $wpdb;
    $id = $gallery->id;
    $type = $gallery->gallery_type;
    $update_flag = $gallery->update_flag;
    $autogallery_image_number = $gallery->autogallery_image_number;

    if($type == 'instagram'){
	  $is_instagram = true;
      $whole_post = 0;
    }
    elseif($type == 'facebook'){
      $is_instagram = false;
      $whole_post = 'regular';
    }
    elseif($type == 'instagram_post'){
        $is_instagram = true;
      $whole_post = 1;
      }
    elseif($type=='facebook_post'){
      $is_instagram = false;
	  $whole_post = 'post';
    }
	
    $source =$gallery->gallery_source;
    if(!$id || !$type || !$source){
      return array(false, "Gallery id, type or source are empty");
    }

    if ($is_instagram) {
      global $wd_bwg_options;
      $get_access_token = $wd_bwg_options->instagram_access_token;
      if(!$get_access_token){
        return array(false, "Cannot get access token from the database");
      }
      $access_token = $get_access_token;
      $new_images_data = self::add_instagram_gallery($source, $access_token, $whole_post, $autogallery_image_number);
    }
    else {
      //is facebook
      $album_data = self::get_facebook_album_data($source, $autogallery_image_number);
      $new_images_data = self::get_facebook_valid_data_for_album($album_data, $whole_post);
      $new_images_data = json_encode($new_images_data);
    }
    $images = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "bwg_image WHERE gallery_id='" . $id . "' ", OBJECT);
    $images_count = sizeof($images);

    if(!$new_images_data){
      return array(false, "Cannot get social data");
    }
    $images_new = json_decode($new_images_data);
    if(empty($images_new)){
      return array(false, "Cannot get social data"); 
    }
	
    if($images_new[0] == "error") {
      if(!$is_instagram) {
        //is facebook
        if($images_new[1] == 'Unsupported get request') {
          //facebook album was deleted or another problem, so unpulish it
          //$query = 'UPDATE ' .  $wpdb->prefix . 'bwg_gallery SET published=0 WHERE `id`='.$id;
          //$wpdb->query($query);		  
        }
      }
      return array(false, "Cannot get social data");
    }

    $images_count_new = sizeof($images_new);
    
    $images_update = array(); /*ids and orders of images existing in both arrays*/
    $images_insert = array(); /*data of new images*/
    $images_dated = array(); /*ids and orders of images not existing in the array of new images*/
    $new_order = 0; /*how many images should be added*/
    if($images_count!=0){
      $author = $images[0]->author; /* author is the same for the images in the gallery */
    }
    else{
      $author = 1; 
    }

    /*loops to compare new and existing images*/
    foreach ($images_new as $image_new) {
      $to_add = true;
      if($images_count != 0){
        foreach($images as $image){
          if($image_new->filename == $image->filename){
            /*if that image exist, do not update*/
            $to_add = false;
          }
        }
      }
      if($to_add){
        /*if image does not exist, insert*/
        $new_order++;
        $new_image_data = array(
          'gallery_id' => $id,
          'slug' => sanitize_title($image_new->name),
          'filename' => $image_new->filename,
          'image_url' => $image_new->url,
          'thumb_url' => $image_new->thumb_url,
          'description' => self::spider_replace4byte($image_new->description),
          'alt' => self::spider_replace4byte($image_new->name),
          'date' => $image_new->date_modified,
          'size' => $image_new->size,
          'filetype' => $image_new->filetype,
          'resolution' => $image_new->resolution,
          'author' => $author,
          'order' => $new_order,
          'published' => 1,
          'comment_count' => 0,
          'avg_rating' => 0,
          'rate_count' => 0,
          'hit_count' => 0,
          'redirect_url' => $image_new->redirect_url,
        );
        array_push($images_insert, $new_image_data);
      }
    }


    if($images_count != 0){
      foreach ($images as $image) {
        $is_dated = true;
        foreach($images_new as $image_new){
          if($image_new->filename == $image->filename){
            /* if that image exist, do not update */
            /* shift order by a number of new images */
            $image_update = array(
              'id' => $image->id ,
              'order'=> intval($image->order) + $new_order,
              "slug" => sanitize_title($image_new->name),
              "description" => $image_new->description,
              "alt" => $image_new->name,
              "date" => $image_new->date_modified);
            array_push($images_update, $image_update);
            $is_dated = false;
          }
        }
        if($is_dated){
        	$image_dated = array(
            'id' => $image->id ,
            'order'=> intval($image->order) + $new_order,
            );
          array_push($images_dated, $image_dated);
        }
      }
    }
    /*endof comparing loops*/
    
    $to_unpublish = true;
    if($update_flag == 'add'){
      $to_unpublish = false;
    }
    if($update_flag == 'replace'){
      $to_unpublish = true;
    }

    
    /*update old images*/
    if($images_count != 0){
      if($to_unpublish){
    		foreach ($images_dated as $image) {
    			$q = 'UPDATE ' .  $wpdb->prefix . 'bwg_image SET published=0, `order` ='.$image['order'].' WHERE `id`='.$image['id'];
          $wpdb->query($q);
    		}
    	}
    	else{
    		foreach ($images_dated as $image) {
          $q = 'UPDATE ' .  $wpdb->prefix . 'bwg_image SET `order` ='.$image['order'].' WHERE `id`='.$image['id'];
    			$wpdb->query($q);
    		}		
    	}

      foreach ($images_update as $image) {
        $save = $wpdb->update($wpdb->prefix . 'bwg_image', array(
          'order' => $image['order'],
          'slug' => self::spider_replace4byte($image['slug']),
          'description' => self::spider_replace4byte($image['description']),
          'alt' => self::spider_replace4byte($image['alt']),
          'date' => $image['date']
          ), array('id' => $image['id']));
      }
    }
  	/*add new images*/
  	foreach($images_insert as $image){
      $save = $wpdb->insert($wpdb->prefix . 'bwg_image', array(
        'gallery_id' => $image['gallery_id'],
        'slug' => self::spider_replace4byte($image['slug']),
        'filename' => $image['filename'],
        'image_url' => $image['image_url'],
        'thumb_url' => $image['thumb_url'],
        'description' => self::spider_replace4byte($image['description']),
        'alt' => self::spider_replace4byte($image['alt']),
        'date' => $image['date'],
        'size' => $image['size'],
        'filetype' => $image['filetype'],
        'resolution' => $image['resolution'],
        'author' => $image['author'],
        'order' => $image['order'],
        'published' => $image['published'],
        'comment_count' => $image['comment_count'],
        'avg_rating' => $image['avg_rating'],
        'rate_count' => $image['rate_count'],
        'hit_count' => $image['hit_count'],
        'redirect_url' => $image['redirect_url'],
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
      ));
   	}
    $time = date('d F Y, H:i');

    /*return time of last update*/
    return array(true, $time);

  }

  public static function get_autoupdate_interval(){
    global $wpdb;
    global $wd_bwg_options;

    if(!isset($wd_bwg_options)){
      return 30;
    }
    if(!isset($wd_bwg_options->autoupdate_interval)){
      return 30;
    }
    $autoupdate_interval = $wd_bwg_options->autoupdate_interval;
    return $autoupdate_interval;
  }

  public static function spider_replace4byte($string) {
    return preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
    )%xs', '', $string);    
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}