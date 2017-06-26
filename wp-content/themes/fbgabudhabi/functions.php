<?php

require('helper.php');

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
		$output .= '<div class="fusion-image-carousel overlay-cards fusion-image-carousel-auto our-team-carousel">'
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
	$suggested_prof = "<div class='m_slider_on_page set_different_color_for_right_triangle members-overview-slider'>
		<div class='owl_carousel_slider'>" . suggestion_profile('shortcode') . "</div>
	</div>";

	if ( is_front_page() ) {
		return $suggested_prof;
	} else {
		echo $suggested_prof;
	}
}

add_shortcode( 'suggested_profiles_slider', 'suggested_profiles_slider' );

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

function fbg_fusion_events_shortcode_v2( $args, $content = '' )
{
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

		// $events = fusion_builder_cached_query( $args );
		$events = new WP_Query( $args );

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

add_action('init', 'fbg_fusion_events_v2');

function fbg_fusion_events_v2() {
	remove_shortcode( 'fusion_events' );
	add_shortcode( 'fusion_events', 'fbg_fusion_events_shortcode_v2' );
}

// [user_datail_search]
add_shortcode( 'user_datail_search', 'user_datail_search' );
function user_datail_search ($atts = []) {
	user_datail_search_fs($atts);
}

// [user_detail_title]
add_shortcode( 'user_detail_title', 'user_detail_title' );
function user_detail_title($atts = []) {
	user_detail_title_fs($atts);
}

// [user_detail_image]
add_shortcode( 'user_detail_image', 'user_detail_image' );
function user_detail_image($atts = []) {
	user_detail_image_fs($atts);
}

// [user_detail_name]
add_shortcode( 'user_detail_name', 'user_detail_name' );
function user_detail_name($atts = []) {
	user_detail_name_fs($atts);
}

// [user_detail_details]
add_shortcode( 'user_detail_details', 'user_detail_details' );
function user_detail_details($atts = []) {
	user_detail_details_fs($atts);
}

// [user_detail_company]
add_shortcode( 'user_detail_company', 'user_detail_company' );
function user_detail_company($atts = []) {
	user_detail_company_fs($atts);
}

// [companies_form_search]
add_shortcode( 'companies_form_search', 'companies_form_search' );
function companies_form_search($atts = []) {
	companies_form_search_fs($atts);
}

// [companies_page_companies_show]
add_shortcode( 'companies_page_companies_show', 'companies_page_companies_show' );
function companies_page_companies_show($atts = []) {
	companies_page_companies_show_fs($atts);
}

// [companies_page_title]
add_shortcode( 'companies_page_title', 'companies_page_title' );
function companies_page_title($atts = []) {
	companies_page_title_fs($atts);
}

// [companies_page_letters]
add_shortcode( 'companies_page_letters', 'companies_page_letters' );
function companies_page_letters($atts = []) {
	companies_page_letters_fs($atts);
}