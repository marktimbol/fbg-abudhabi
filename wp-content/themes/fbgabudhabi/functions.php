<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'fbgabudhabi-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
    wp_enqueue_style( 'fbgabudhabi-app-style', get_stylesheet_directory_uri() . '/dist/css/app.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_scripts() {
    wp_enqueue_script( 'fbgabudhabi-app', get_stylesheet_directory_uri() . '/dist/js/app.js');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

// require_once Avada::$template_dir_path . '/includes/plugins/multiple-featured-images/multiple-featured-images.php';

function woocommerce_checkout_fields_overwrite( $address_fields ) { 
	$address_fields['billing_state']['required'] = false;
	$address_fields['billing_state']['label'] = ICL_LANGUAGE_CODE == 'en' ? 'State' : 'Etat';
	$address_fields['shipping_state']['required'] = false;
	$address_fields['shipping_state']['label'] = ICL_LANGUAGE_CODE == 'en' ? 'State' : 'Etat';

	return $address_fields;
}

// WooCommerce - Make "state" field not required on checkout page
add_filter( 'woocommerce_checkout_fields', 'woocommerce_checkout_fields_overwrite', 10, 1 );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
    $address_fields['state']['required'] = false;
    $address_fields['state']['label'] = ICL_LANGUAGE_CODE == 'en' ? 'State' : 'Etat';

    return $address_fields;
}

// Hook in
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

/**
 * ACF Options Page
 */

if ( function_exists( 'acf_add_options_page' ) )
{
	acf_add_options_page();
}

/**
 * Customize 'Fusion Events' Shortcode to 
 * add:
 *   1. Join Button
 *   2. Date of Event
 * Update:
 *   1. Simply the Date below Title
 *
 * Edited on: 22/12/16
 */

function fbg_fusion_events_shortcode_v2( $args, $content = '' ) {
	$html     = '';
	$defaults = shortcode_atts(
		array(
			'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
			'class'          => '',
			'id'             => '',
			'cat_slug'       => '',
			'columns'        => '4',
			'number_posts'   => '4',
			'picture_size'   => 'cover',
			), $args
		);

	extract( $defaults );

	if ( class_exists( 'Tribe__Events__Main' ) )
	{
		$args = array (
			'post_type' => 'tribe_events',
			'posts_per_page' => $number_posts,
		);

		if ( $cat_slug )
		{
			$terms = explode( ',', $cat_slug );
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'tribe_events_cat',
					'field'     => 'slug',
					'terms'     => array_map( 'trim', $terms ),
					),
				);
		}

		switch ( $columns )
		{
			case '1':
				$column_class = 'full-one';
				break;
			case '2':
				$column_class = 'one-half';
				break;
			case '3':
				$column_class = 'one-third';
				break;
			case '4':
				$column_class = 'one-fourth';
				break;
			case '5':
				$column_class = 'one-fifth';
				break;
			case '6':
				$column_class = 'one-sixth';
				break;
		}

		$events = fusion_builder_cached_query( $args );

		if ( ! $events->have_posts() ) {
			return fusion_builder_placeholder( 'tribe_events', 'events' );
		}

		$class = fusion_builder_visibility_atts( $hide_on_mobile, $class );

		if ( $events->have_posts() )
		{
			if ( $id )
			{
				$id = ' id="' . $id . '"';
			}

			$html .= '<div class="fusion-events-shortcode ' . $class . '"' . $id . ' m_slider_on_page set_different_color_for_right_triangle>';
			$i       = 1;
			$last    = false;
			$columns = (int) $columns;

			$hasCalendarTile = $events->post_count < 4 ? true : false;

			while ( $events->have_posts() )
			{
				$events->the_post();

				if ( $i == $columns ) {
					$last = true;
				}

				if ( $i > $columns ) {
					$i = 1;
					$last = false;
				}

				if ( 1 == $columns ) {
					$last = true;
				}

				$html .= '<div class="fusion-' . $column_class . ' fusion-spacing-yes fusion-layout-column ' . ( ( $last ) ? 'fusion-column-last' : '' ) . ' ">';
				$html .= '<div class="fusion-column-wrapper">';
				$thumb_id = get_post_thumbnail_id();
				$thumb_link = wp_get_attachment_image_src( $thumb_id, 'full', true );
				$thumb_url = '';

				if ( has_post_thumbnail( get_the_ID() ) ) {
					$thumb_url = $thumb_link[0];
				} elseif ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
					$thumb_url = esc_url( trailingslashit( Tribe__Events__Pro__Main::instance()->pluginUrl ) . 'src/resources/images/tribe-related-events-placeholder.png' );
				}

				$img_class = ( has_post_thumbnail( get_the_ID() ) ) ? '' : 'fusion-events-placeholder';

				if ( $thumb_url ) {
					$thumb_img = '<img class="' . $img_class . '" src="' . $thumb_url . '" alt="' . esc_attr( get_the_title( get_the_ID() ) ) . '" />';
					if ( has_post_thumbnail( get_the_ID() ) && 'auto' == $picture_size ) {
						$thumb_img = get_the_post_thumbnail( get_the_ID(), 'full' );
					}
					$thumb_bg = '<span class="tribe-events-event-image" style="background-image: url(' . $thumb_url . '); background-size: contain; background-position: top;"></span>';
				}
				$html .= '<div class="fusion-events-thumbnail hover-type-' . ( ( class_exists( 'Avada' ) ) ? Avada()->settings->get( 'ec_hover_type' ) : '' ) . '">';
				$html .= '<a href="' . get_the_permalink() . '" class="url" rel="bookmark">';

				if ( $thumb_url ) {
					$html .= ( 'auto' == $picture_size ) ? $thumb_img : $thumb_bg;
				} else {
					ob_start();
			        do_action( 'avada_placeholder_image', 'fixed' );
			        $placeholder = ob_get_clean();
			        $html .= str_replace( 'fusion-placeholder-image', ' fusion-placeholder-image tribe-events-event-image', $placeholder );
			    }    

		    	$event_date = tribe_get_start_date( null, false, 'd M' );
		      	$event_date_full = tribe_get_start_date( null, false, 'j M Y' );

				$mystring = $_SERVER['PHP_SELF'];
    			$url = $_SERVER['REQUEST_URI'];

      			if ( ICL_LANGUAGE_CODE=='fr' ) {
        			$eventButtonLink = "Voir";
      			} else {
		          $eventButtonLink = "Join the Event";
      			}

      			$html .= '</a>';
      			$html .= '</div>';
				$html .= '<div class="fusion-events-meta">';
				$html .= '<h2><a href="' . get_the_permalink() . '" class="url" rel="bookmark">' . get_the_title() . '</a></h2>';
				$html .= '<h4>' . $event_date_full . '</h4>';
				$html .= '</div>';
				$html .= '<div class="fusion-events-overlay">';
				$html .= '<div class="dt"><div class="dtc">
							<a href="' . get_the_permalink() . '" class="button button-round">' . __( $eventButtonLink, 'fbg' ) . '</a></div>
						</div>';
				$html .= '<div class="event-date"><span>' . $event_date . '</span></div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				if ( $last ) {
      				// $html .= '<div class="fusion-clearfix"></div>';
      			}
      			$i++;
  			} // end while  			

  			wp_reset_query();
 			// $html .= '<div class="fusion-clearfix"></div>';

			if ( $hasCalendarTile )
			{
				$viewCalendarlink = ICL_LANGUAGE_CODE == 'en' ? '/en/calendar-events' : '/calendar-events';
				$viewCalendarText = ICL_LANGUAGE_CODE == 'en' ? 'View Calendar' : 'Voir le calendrier';

				$html .= '<div class="fusion-one-fourth fusion-spacing-yes fusion-layout-column fusion-column-last visit-calendar-tile">';
					$html .= '<div class="fusion-column-wrapper">';
						$html .= '<div class="fusion-events-thumbnail hover-type-none">';
							$html .= '<a href="'.$viewCalendarlink.'" class="url" rel="bookmark">';
								$html .= '<span class="tribe-events-event-image"></span>';
							$html .= '</a>';
						$html .= '</div>';
						$html .= '<div class="fusion-events-overlay" style="z-index: 10 !important; opacity: 1 !important;">';
							$html .= '<div class="dt">';
								$html .= '<div class="dtc">';
									$html .= '<a href="'.$viewCalendarlink.'" class="button button-round">'.$viewCalendarText.'</a>';
								$html .= '</div>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			}  	

  		$html .= '</div>';	
	}
	return $html;
	}
}

add_action('wp_head', 'fbg_fusion_events_v2');

function fbg_fusion_events_v2()
{
	remove_shortcode( 'fusion_events' );
	// add_shortcode( 'fusion_events', 'fbg_fusion_events_shortcode_v2' );
}

add_action('init', 'fbg_team_cpt');
function fbg_team_cpt()
{
	register_post_type('team',
		array(
			'labels' => array (
				'name'               => 'All Team',
				'singular_name'      => 'Team Member',
				'add_new'            => 'Add a New Team Member',
				'add_new_item'       => 'Add a New Team Member',
				'edit_item'          => 'Edit Team Member',
				'new_item'           => 'New Team Member',
				'view_item'          => 'View Team Member',
				'search_items'       => 'Search Team',
				'not_found'          => 'No Team found',
				'not_found_in_trash' => 'No Team found in Trash',
				'parent_item_colon'  => 'Parent Team Member:',
				'menu_name'          => 'Team',
				),
			'public'      => false,
			'has_archive' => false,
			'show_ui'     => true,
			'supports'    => array( 'title', 'thumbnail' )
		)
	);
}

/**
 * FBG Team Shortcode
 */
function fbg_team_shortcode( $atts )
{
	$atts = shortcode_atts( array(
		'total' => 5,
		'columns' => 5
	), $atts );

	extract( $atts );

	$output = '';

	if ( ! function_exists( 'get_field') ) return;

	$q_args = array(
		'post_type' => 'team',
		'posts_per_page' => $total,
	);

	$q = new WP_Query( $q_args );
	if ( $q->have_posts() )
	{
		$output .= '<div class="fusion-image-carousel overlay-cards fusion-image-carousel-auto">'
		. '<div class="fusion-carousel" data-autoplay="no" data-columns="5" data-itemmargin="0" data-itemwidth="214" data-touchscroll="no" data-imagesize="auto" data-scrollitems="1">'
		. '<div class="fusion-carousel-positioner">'
		. '<ul class="fusion-carousel-holder">';

		while ( $q->have_posts() )
		{
			$q->the_post();

			$name        = get_the_title();
			$position    = get_field( '_position' );
			$linkedin    = get_field( '_linkedin' );
			$no_linkedin = get_field( '_hide_linkedin' );
			$bio         = get_field( '_bio' );
			$thumb_id    = get_post_thumbnail_id();
			$thumb       = wp_get_attachment_image_src( $thumb_id, 'medium' );

			if ( $thumb[0] )
			{
				$thumbnail = '<div class="fusion-carousel-thumbnail hover-type-none">'
				. '<span class="fusion-carousel-thumbnail-image" style="background-image: url(' . $thumb[0] . ');"></span>'
				. '</div>';
			}

			if ( $no_linkedin <> 1 && $linkedin <> "" )
			{
				$ln_link = '<a class="otc-social otc-linkedin fusion-social-network-icon fusion-linkedin fusion-icon-linkedin" href="' . $linkedin . '" target="_blank" rel="noopener noreferrer" title="Linkedin"><span class="screen-reader-text">Linkedin</span></a>';
			} else {
				$ln_link="";
			}

			$output .= '<li class="fusion-carousel-item"><div class="fusion-carousel-item-wrapper">'
			. $thumbnail
			. '<div class="fusion-carousel-meta' . ( $ln_link ? ' has-social' : '' ) . '">'
			. ( $name ? '<h3>' . $name . '</h3>' : '' )
			. ( $position ? '<h4>' . $position . '</h4>' : '' )
			. $ln_link
			. '</div>'
			. '<div class="fusion-carousel-overlay">'
			. '<div class="dt">'
			. '<div class="dtc"><p>' . $bio . $ln_link . '</p></div>'
			. '</div>'
			. '</div>'
			. '</div></li>';
		}

		$output .= '</ul>'
		. '<div class="fusion-carousel-nav"><span class="fusion-nav-prev"></span><span class="fusion-nav-next"></span></div>'
		. '</div>'
		. '</div>'
		. '</div>';
	}
	return $output;
}

add_shortcode( 'fbg_team', 'fbg_team_shortcode' );

add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
	register_nav_menu( 'footer_menu', __( 'Footer Menu', 'fbg' ) );
}

add_action( 'avada_footer_copyright_content', 'fbg_footer_menu', 12 );
function fbg_footer_menu()
{
	if ( has_nav_menu( 'footer_menu' ) ) {
		echo '<div class="footer-right">';
		wp_nav_menu( array(
			'theme_location' => 'footer_menu',
			'depth'          => 1,
			'container'      => false,
			'menu_id'        => 'footer-menu',
			'echo'           => 1,
			) );
	}
}

add_action( 'avada_footer_copyright_content', 'fbg_footer_after_social_closing_tag', 16 );
function fbg_footer_after_social_closing_tag()
{
	if ( has_nav_menu( 'footer_menu' ) ) {
		echo '</div>';
	}
}

/**
 * Add Contact info Below Side Menu Social Icons
 */
function fbg_below_menu_social_contact()
{
	if ( ! function_exists( 'get_field' ) ) return;

	$phone = get_field( '_phone', 'options' );
	$email = get_field( '_email', 'options' );

    if ( false ) {
        ?>
        <div class="side-menu-contact">
            <?php if ( $phone ): ?>
                <div class="phone"><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></div>
            <?php endif; ?>
            <?php if ( $email ): ?>
                <div class="email"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
            <?php endif; ?>
        </div>
        <?php
    }
}

add_action( 'avada_header_inner_after', 'fbg_below_menu_social_contact', 30 );

/**
 * Add Search Bar and Language Switcher Below Mobile Menu
 */
function fbg_below_menu_mobile_search_language()
{
	if ( ! function_exists( 'get_field' ) ) return;
	?>
	<div class="mobile-search-language">
		<?php 
		$languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
		$languages_html = '';

		if ( ! is_wp_error( $languages ) && ! empty( $languages ) ) {
			foreach ( $languages as $lang_key => $lang_value ) {

				if ( $lang_value['active'] ) {
					$button_class = 'btn-secondary';
				} else {
					$button_class = 'btn-primary';
				}

				$languages_html .= '<a href="' . $lang_value['url'] . '" class="btn ' . $button_class . '">' . $lang_value['native_name'] . '</a>';

			}
		}
		?>
		<?php if ( $languages_html ): ?>
			<div class="top-header-language-switcher">
				<?php echo $languages_html; ?>
			</div>
		<?php endif; ?>
		<div class="top-header-search">
			<form role="search" class="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
				<div class="search-table">
					<div class="search-field">
						<input type="text" value="" name="s" class="s" placeholder="<?php esc_html_e( 'Search Events, Directory & more', 'fbg' ); ?>" />
					</div>
					<div class="search-button">
						<input type="submit" class="searchsubmit" value="&#xf002;" />
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}

add_action( 'avada_header_inner_after', 'fbg_below_menu_mobile_search_language', 30 );


add_action('init', 'FBG_add_company');  

function FBG_add_company()
{
	$args = array(
		'label' => __('Company'),
		'singular_label' => __('Company'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'supports' => array('title', 'editor', 'thumbnail')
		);
	register_post_type( 'company' , $args );
}


add_action('init', 'FBG_add_sector');  

function FBG_add_sector()
{
	$args = array(
		'label' => __('Sector'),
		'singular_label' => __('Sector'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'supports' => array('title', 'editor', 'thumbnail')
		);
	register_post_type( 'sector' , $args );
}

add_action( 'gform_after_submission_1', 'create_users_and_company', 10, 2 );
function create_users_and_company( $entry, $form )
{
	global $woocommerce;
	$product = rgar( $entry, '2' );
	if(empty($product)) {
		$product = rgar( $entry, '3' );
	}
	if(empty($product)) {
		$product = rgar( $entry, '4' );
	}

	if($product == "1 Representative - AED 3,000") {
		$product_id = 15844;
	} else if($product == "2 Representatives - AED 5,700") {
		$product_id = 15845;
	} else if($product == "3 Representatives - AED 7,200") {
		$product_id = 15846;
	} else if($product == "4 Representatives - AED 8,700") {
		$product_id = 15847;
	} else if($product == "1 Representative - AED 2,000") {
		$product_id = 15848;
	} else if($product == "2 Representatives - AED 3,800") {
		$product_id = 15849;
	} else if($product == "1 Representative - AED 1,500") {
		$product_id = 15850;
	}

	$the_post_id  = wp_insert_post(
		array (
			'post_type' => 'company',
			'post_title' => rgar( $entry, '6' ),
			'post_content' => rgar( $entry, '19' ),
			'post_status' => 'publish',
			'comment_status' => 'closed'
			)
		);

	// company picture
	if( strlen( rgar( $entry, '64' ) )>0 )
	{
		$photo = new WP_Http();
		$photo = $photo->request( gar( $entry, '64' ) );

		$attachment = wp_upload_bits( rgar( $entry, '6' ) . time() . '.jpg', null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );

		$filetype = wp_check_filetype( basename( $attachment['file'] ), null );

		$postinfo = array(
			'post_mime_type'	=> $filetype['type'],
			'post_title'		=> rgar( $entry, '6' ) . ' photograph',
			'post_content'	=> '',
			'post_status'	=> 'inherit',
			);
		$filename = $attachment['file'];
		$attach_id = wp_insert_attachment( $postinfo, $filename, $the_post_id );

		update_field('company_picture', $attach_id, $the_post_id);
	}

	$digits=8;
	$corporate_id="zcrm_21762180000".str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	
	global $wpdb;
	$result_v = $wpdb->get_results("SELECT * FROM wp_xpksy4skky_postmeta WHERE meta_key='corporate_account_id' AND meta_value='{$user_id}'");
    $corporate_id = "";

	update_field('po_box_no', rgar( $entry, '60' ), $the_post_id);
	update_field('city', rgar( $entry, '61' ), $the_post_id);
	update_field('phone', rgar( $entry, '11' ), $the_post_id);
	update_field('fax', rgar( $entry, '12' ), $the_post_id);
	update_field('email', rgar( $entry, '13' ), $the_post_id);
	update_field('website', rgar( $entry, '14' ), $the_post_id);
	update_field('total_number_of_employees_worldwide', rgar( $entry, '15' ), $the_post_id);
	update_field('total_number_of_employees_uae', rgar( $entry, '16' ), $the_post_id);
	update_field('corporate_account_id', $corporate_id, $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);
	update_field('corporate_account_owner_id', "zcrm_2176218000000107005", $the_post_id);

	$value_sectors = array();

	$sectors1 = get_sector_id( rgar( $entry, '68' ) );
	array_push($value_sectors, array("sector_obj"	=> $sectors1 ) );
	
	if( strlen(rgar( $entry, '69' ))>0 && rgar( $entry, '69' ) != "SECTORS" ) {
		$sectors2 = get_sector_id( rgar( $entry, '69' ) );
		array_push($value_sectors, array("sector_obj"	=> $sectors2 ) );
	}

	if( strlen(rgar( $entry, '70' ))>0 && rgar( $entry, '70' ) != "SECTORS") {
		$sectors3 = get_sector_id( rgar( $entry, '70' ) );
		array_push($value_sectors, array("sector_obj"	=> $sectors3 ) );
	}

	if(count($value_sectors)>0) {
		update_field( "company_sector_obj", $value_sectors, $the_post_id );
	}

	// user 1
	if(!empty($entry['24']))
	{
		$french = 'No';
		if(isset($entry['86'])) {
			$french = $entry['86'];
		}

		create_and_email_user($entry, $the_post_id, '21', '22.3', '22.6', '23', '24', '25', '26', $french);

		$s_billing_phone='_billing_phone';
		$biillling_addres=rgar( $entry, '61' );

		global $wpdb;

		$wpdb->query(
			$wpdb->prepare (
				"INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
					VALUES ( 16798,  '_billing_address_1',  '{$biillling_addres}')")
			);

		$wpdb->query(
			$wpdb->prepare (
				"INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
					VALUES ( 16798,  '_billing_phone',  '{$biillling_addres}')" )
			);
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_billing_email',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_billing_company',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_billing_last_name',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_billing_first_name',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_billing_city',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_shipping_first_name',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_shipping_last_name',  '{$biillling_addres}')" ));
		$wpdb->query($wpdb->prepare ("INSERT INTO  `wp_xpksy4skky_postmeta` (  `post_id` ,  `meta_key` ,  `meta_value` ) 
			VALUES ( 16998,  '_shipping_company',  '{$biillling_addres}')" ));
	}

	// user 2
	if(!empty($entry['33']))
	{
		$french = 'No';
		if(isset($entry['87'])) {
			$french = $entry['87'];
		}
		create_and_email_user($entry, $the_post_id, '30', '31.3', '31.6', '32', '33', '34', '35', $french);		
	}

	// user 3
	if(!empty($entry['42']))
	{
		$french = 'No';
		if(isset($entry['88'])) {
			$french = $entry['88'];
		}
		create_and_email_user($entry, $the_post_id, '40', '39.3', '39.6', '41', '42', '43', '44', $french);		
	}

	// user 4
	if(!empty($entry['51']))
	{
		$french = 'No';
		if(isset($entry['89'])) {
			$french = $entry['89'];
		}
		create_and_email_user($entry, $the_post_id, '48', '49.3', '49.6', '50', '51', '52', '53', $french);		
	}

	$woocommerce->cart->add_to_cart($product_id);
	$cart_url = $woocommerce->cart->get_cart_url();
	header("Location: " . $cart_url);
}


function create_and_email_user($entry, $company_id, $pic, $fname, $lname, $position, $email, $mobile, $nationality, $french )
{
	$digits=8;

	$user_id="zcrm_21762180000".str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	global $wpdb;
	$result_v = $wpdb->get_results("SELECT * FROM wp_xpksy4skky_usermeta WHERE meta_key='contact_id' AND meta_value='{$user_id}'");
	foreach($result_v as $row_v) {
		while ($row_v['meta_value']==$user_id) {
			$user_id="zcrm_21762180000".str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
		}
	}

	$result_v1 = $wpdb->get_results(" SELECT * FROM wp_xpksy4skky_postmeta WHERE post_id={$company_id} AND meta_key='corporate_account_id' ");

	foreach($result_v1 as $row_v1) {
		$corporate_id=$row_v1->meta_value;
	}

	$pic = rgar( $entry, $pic );
	$fname = rgar( $entry, $fname );
	$lname = rgar( $entry, $lname );
	$position = rgar( $entry, $position );
	$email = rgar( $entry, $email );
	$mobile = rgar( $entry, $mobile );
	$nationality = rgar( $entry, $nationality );
	$corporate_account_id=$corporate_id;
	$contact_id = $user_id;


	if( email_exists( $email ) ) {
		return;
	} else {
		// Generate the password and create the user
		$password = wp_generate_password( 12, false );
		$user_id = wp_create_user( $email, $password, $email );
		// Set the nickname
		wp_update_user(
			array(
				'ID'		=>	$user_id,
				'nickname'	=>	$email
			)
		);

		update_field('company', $company_id, "user_".$user_id);
		update_field('first_name', $fname, "user_".$user_id);
		update_field('last_name', $lname, "user_".$user_id);
		update_field('position', $position, "user_".$user_id);
		update_field('mobile', $mobile, "user_".$user_id);
		update_field('nationality', $nationality, "user_".$user_id);
		update_field('french', $french, "user_".$user_id);
		update_field('contact_id', $contact_id, "user_".$user_id);
		update_field('corporate_account_id', $corporate_account_id, "user_".$user_id);

		// Set the role
		$user = new WP_User( $user_id );
		$user->set_role( 'subscriber' );

		if( strlen($pic)>0 )
		{
			$post_id = wp_insert_post(array (
				'post_type' => 'post_team',
				'post_title' => $email . 'post_team',
				'post_status' => 'trash',
				'comment_status' => 'closed',
				'ping_status' => 'closed'
				));

			$photo = new WP_Http();
			$photo = $photo->request( $pic );

			$attachment = wp_upload_bits( $email . time() . '.jpg', null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );

			$filetype = wp_check_filetype( basename( $attachment['file'] ), null );

			$postinfo = array(
				'post_mime_type'	=> $filetype['type'],
				'post_title'		=> $email . ' photograph',
				'post_content'	=> '',
				'post_status'	=> 'inherit',
				);
			$filename = $attachment['file'];
			$attach_id = wp_insert_attachment( $postinfo, $filename, $post_id );

			update_field('user_picture', $attach_id, "user_".$user_id);
		}
	}
}

function get_sector_id($sector)
{
	global $wpdb;

	$post_sector = $wpdb->get_results("
		SELECT
			*
		FROM
			wp_xpksy4skky_posts
		WHERE
			post_title LIKE '%{$sector}%'
			AND post_type LIKE 'sector'
			AND post_status LIKE 'publish'
		LIMIT 1
		");

	foreach ($post_sector as $row_sector) {
		return $row_sector->ID;
		break;
	}
}

// Add help text to right of screen in a metabox
function wptutsplus_metabox_top_right()
{
	add_meta_box( 'after-title-help', 'Users of Company', 'wptutsplus_top_right_help_metabox_content', 'company', 'side', 'high' );
}

// callback function to populate metabox
function wptutsplus_top_right_help_metabox_content()
{
	global $post, $wpdb;

	$id = $post->ID;

	$user_query = $wpdb->get_results("
		SELECT
			* 
		FROM
			`wp_xpksy4skky_usermeta` 
		WHERE
			`meta_key` LIKE  'company'
			AND `meta_value` LIKE  '{$id}'
		LIMIT 4
		");

	foreach ($user_query as $row)
	{
		$user_id = $row->user_id;
		$user_data = get_userdata( $user_id );

		$paid_unpaid = get_field('paid_unpaid', 'user_'.$user_id );

		if( !isset($paid_unpaid) || empty($paid_unpaid) || strlen($paid_unpaid) == 0 ) {
			$paid_unpaid = "not set/unpaid";
		}
		
		echo "<a href='" . get_edit_user_link( $user_id ) . "' target='_blank'>" .  $user_data->user_email  . "</a> - " . $paid_unpaid;
		echo "<br><br>";
	}
}

add_action( 'add_meta_boxes', 'wptutsplus_metabox_top_right' );

function fbg_event_categories()
{
	$categories = get_terms( array( 'taxonomy' => 'tribe_events_cat', 'parent' => 0, 'hide_empty'    => false ) );
	$html = '';

	foreach( $categories as $category)
	{
         $url = $_SERVER['REQUEST_URI'];

         if(ICL_LANGUAGE_CODE=='fr')   {
              $eventButtonLink = "Voir";
          }
          else{
              $eventButtonLink = "Join the Event";
          }
		$html .= '<div class="fbg-cat-block"><div class="fbg-cat-inner">';
		$html .= '<div class="fbg-cat-img">'.do_shortcode(sprintf('[wp_custom_image_category term_id="%s"]',$category->term_id)).'</div>';
		$html .= '<div class="fbg-cat-title">'.$category->name.'</div>';
		$html .= '<div class="fbg-cat-btn">
						<div class="dt">
							<div class="dtc">
								<a href="'.get_term_link($category->term_id).'" class="button button-round">' . $eventButtonLink . '</a>
							</div>
						</div>
					</div>';
		$html .= '</div></div>';
	}
	return $html;
}

add_shortcode( 'event-categories', 'fbg_event_categories' );

add_theme_support( 'post-thumbnails' );
add_image_size( 'fdg-image', 480, 480, true );
add_filter( 'image_size_names_choose', 'wpshout_custom_sizes' );

function wpshout_custom_sizes( $sizes )
{
    return array_merge( $sizes, array(
        'fdg-image' => __( 'Medium 480x480' ),
    ) );
}

function woo_custom_order_button_text()
{
    return __( 'PROCEED', 'woocommerce' ); 
}
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' ); 

function tml_action_url( $url, $action, $instance ) {
	if ( 'register' == $action ){
        if(ICL_LANGUAGE_CODE=='fr')   {
            $url = 'http://fbgabudhabi.com/home/select-plan/';
        }
        else{
            $url = 'http://fbgabudhabi.com/en/home/select-plan/';
        }
    }
	return $url;
}
add_filter( 'tml_action_url', 'tml_action_url', 10, 3 );

function filter_site_upload_size_limit( $size )
{
    $size = 1024 * 7000;
    return $size;
}

add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

function suggestion_profile($type="list")
{
	global $wpdb;

	$user_id = $user_item = $date_reg = $date = $user_shortcode_block = "";

	$result = $wpdb->get_results("
		SELECT 
			wp_users.ID as ID,
			wp_users.user_registered as user_registered
		FROM
			wp_xpksy4skky_users wp_users
		ORDER BY RAND()"
	);

	$count_wm=0;
	
	foreach ($result as $row)
	{
        $adminuser = false;
        $resultadmin = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='wp_xpksy4skky_capabilities' AND meta_value !='a:1:{s:13:\"administrator\";b:1;}' ");	

		foreach ($resultadmin as $rowadmin) {	
			$adminuser = true;
		}

		if( ! $adminuser ) {
			continue;
		} 
		
        $approved = false;
		$result9 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='pw_user_status' AND meta_value='approved' ");

		foreach ($result9 as $row9) {
			$approved = true;
		}

		$user_f_un="";
		if( ! $approved ) {
			continue;
		}

		$paid_unpaid_wm = false;
		$result22 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='paid_unpaid' AND meta_value='paid' ");

		foreach ($result22 as $row22) {
			$paid_unpaid_wm=true;
		}

		$user_id_wm = $row->ID;
		if ( ! $paid_unpaid_wm ) {
			continue;
		}

		$resultOverview = $wpdb->get_results(" SELECT `user_id` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='members_overview' AND meta_value='a:1:{i:0;s:7:\"Display\";}' ");

        if( empty($resultOverview) ) {
            continue;
        }

		$current_user_id = get_current_user_id();
		if ($current_user_id <> "")
		{
			$result70 = $wpdb->get_results(" SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$current_user_id}' AND meta_key='paid_unpaid' AND meta_value='paid'");

			foreach ($result70 as $row70) {
				$user_id = $user_id_wm;
			}
		} else {
			$result377 = $wpdb->get_results(" SELECT `user_id` FROM wp_xpksy4skky_usermeta WHERE user_id='{$row->ID}' AND meta_key='members_overview' AND meta_value='a:1:{i:0;s:7:\"Display\";}' ");

			if (empty($result377) ) {
				continue;
			}

			$user_id = $result377[0]->user_id;
		}

		$date_reg = $row->user_registered;
		$date = (current_time('timestamp')-strtotime($date_reg))/86400;
		$first_name="";
		$result1 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='first_name'");

		foreach ( $result1 as $row1 ) {
			$first_name = $row1->meta_value;
		}

		$last_name="";
		$result3 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='last_name'");	
		
		foreach ($result3 as $row3) {
			$last_name = $row3->meta_value;
		}

		$badge_name="";
		$result4 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_name'");	
		
		foreach ($result4 as $row4) {
			$badge_name = $row4->meta_value;
		}

		$result8 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_color'");	
		foreach ($result8 as $row8) {
			$badge_color = $row8->meta_value;
		}

		$result9 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='badge_background'");
		foreach ($result9 as $row9) {
			$badge_background = $row9->meta_value;
		}

		$user_picture="";
		$result5 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='user_picture'");	
		
		foreach ($result5 as $row5) {
			$user_picture = $row5->meta_value;
		}

		$user_image = content_url()."/uploads/2017/01/new-FBG-logo_transparent.png";
		$result2 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_postmeta WHERE post_id='{$user_picture}' AND meta_key='_wp_attached_file'");

		foreach ($result2 as $row2) {
			$user_image = content_url()."/uploads/".$row2->meta_value;
		}


		$position = "";
		$resPosition = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='position'");	
		
		foreach ( $resPosition as $rowPos ) {
			$position = $rowPos->meta_value;
		}

        $count_wm++;
		if ($count_wm == 10) {
			break;
		}

		$full_name = $first_name." ".$last_name;

       if( ICL_LANGUAGE_CODE == 'fr' ) {
           $strNew = "Nouveau";
       } else {
            $strNew = "New";
       }

		$corner_badge="";	
		if ( $date < 31 )
		{
			$corner_badge=<<<EOF
			<div class="m_features_name">
				<p class="m_right_date m_right_corner_feature bg_white_f" href="#"><span style="color:white!important; font-weight: bold!important;">{$strNew}</span></p>
			</div>
			<style>
				.bg_white_f:after {
					border-right: 118px solid #0290dc;
				}				
			</style>
EOF;
		}

		if ( $badge_name <> '' )
		{
            if ( ICL_LANGUAGE_CODE == 'fr' ) {
                if ( $badge_name == 'Featured' ) {
                    $badge_name = "Mis en Avant";
                }
            }

			$corner_badge=<<<EOF
			<div class="m_features_name">
				<p class="m_right_date m_right_corner_feature bg_white_f" href="#" style=""><span style="color:{$badge_color}!important; font-weight: bold!important;">{$badge_name}</span></p>
			</div>
			<style>
			.bg_white_f:after {
				border-right: 118px solid {$badge_background};
			}				
			</style>
EOF;
		} 

        if ( ICL_LANGUAGE_CODE == 'fr' ) {
             $link_used = get_site_url() . "/annuaire-en-ligne/users-details/?uid=" . $user_id;
        } else {
		    $link_used = get_site_url() . "/en/online-directory/users-details/?uid=" . $user_id;
        }

		$final_link = $company_name_c = $company_name_wm = "";
		$company_id_c = 0;

		$result15 = $wpdb->get_results("SELECT `meta_value` FROM wp_xpksy4skky_usermeta WHERE user_id='{$user_id}' AND meta_key='company'");	
		foreach ($result15 as $row15) {
			$company_id_c = $row15->meta_value;
		}

		$result16 = $wpdb->get_results("SELECT `post_title` FROM wp_xpksy4skky_posts WHERE ID='{$company_id_c}'");	
		foreach ($result16 as $row16) {
			$company_name_c = $row16->post_title;
		}

		$link_company_wm = get_permalink($company_id_c);

		if ($company_name_c == "Individual") {
			$company_name_wm = $company_name_c="";
		} else if (strlen($link_company_wm) > 0 && $company_id_c > 0) {
			$company_name_wm = $company_name_c;
			$final_link = "<a href='{$link_company_wm}'><p style='font-size: 14px; height: 32px; color: #0291dd;'>{$company_name_wm}</p></a>";
		}

		$user_item = <<<EOF
		<div class="item">
			<div class="set_bg_for_item">
				<img src="{$user_image}" alt="client">
			</div>
			{$corner_badge}
			<div class="m_desc_of_position">
				<div class="m_name_of_person">
					<a href="{$link_used}"><p>{$full_name}</p></a>
				</div>
				<div class="occupation_position_ahead m_b_20">
					<p style="height: 32px;">{$position}</p>
				</div>
				<div class="occupation_position_ahead m_b_20">
					{$final_link}
				</div>
			</div>
		</div>
EOF;
	
		if ( $type == "shortcode" ) {
			$user_shortcode_block .= $user_item;
		} else {
			echo $user_item;
		}
	}

	if ( $type == "shortcode" ) {
		return $user_shortcode_block;
	}
}

function suggested_profiles_title($atts = [])
{
	$suggested_prof_title="";
	$title = "Members Overview";

	if ( isset($atts['title']) )
	{
		$title = $atts['title'];
	}

	$suggested_prof_title = "
	<div class='m_events_in_future set_different_color_for_right_triangle_2'>
		<h2>" . $title . "</h2>
	</div>";

	if ( is_front_page() )
	{
		return $suggested_prof_title;
	} else {
		$suggested_prof_title = "<div class='m_set_mt_20'>" . $suggested_prof_title . "</div>";
		echo $suggested_prof_title;
	}
}

add_shortcode( 'suggested_profiles_title', 'suggested_profiles_title' );

function suggested_profiles_slider($atts = [])
{
	global $wpdb;
	$suggested_prof = "<div class='m_slider_on_page set_different_color_for_right_triangle'>
		<div class='owl_carousel_slider'>" . suggestion_profile('shortcode') . "</div>
	</div>";

	if ( is_front_page() ) {
		return $suggested_prof;
	} else {
		echo $suggested_prof;
	}
}

add_shortcode( 'suggested_profiles_slider', 'suggested_profiles_slider' );