<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<!DOCTYPE html>
<?php global $woocommerce; ?>
<html class="<?php echo ( Avada()->settings->get( 'smooth_scrolling' ) ) ? 'no-overflow-y' : ''; ?>" <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	    <?php 
	    	// echo "<link rel='stylesheet' href='".content_url()."/themes/Avada/assets/css/theme-my-login.css'>";
			$is_ipad = (bool) ( isset( $_SERVER['HTTP_USER_AGENT'] ) && false !== strpos( $_SERVER['HTTP_USER_AGENT'],'iPad' ) );
			$viewport = '';

			if ( Avada()->settings->get( 'responsive' ) && $is_ipad ) {
				$viewport .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
			} elseif ( Avada()->settings->get( 'responsive' ) ) {
				if ( Avada()->settings->get( 'mobile_zoom' ) ) {
					$viewport .= '<meta name="viewport" content="width=device-width, initial-scale=1" />';
				} else {
					$viewport .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
				}
			}

			$viewport = apply_filters( 'avada_viewport_meta', $viewport );
			echo $viewport;
			
			wp_head();

			$object_id = get_queried_object_id();
			$c_page_id = get_the_ID();
			// $c_page_id = Avada()->get_page_id();

			$pages_with_css = array('13454', '13750', '13503', '13764', '14918', '13726', '14559', '13760', '13747', '13743', /*'13612',*/ '13745', /*'13739',*/ '13741', '17360' , '16130', '16127', '14692', '16127', '17352');

			if ( in_array($c_page_id, $pages_with_css) )
			{
				echo "<link rel='stylesheet' href='".content_url()."/themes/Avada/assets/css/events.css'>";
				echo "<link rel='stylesheet' href='".content_url()."/themes/Avada/assets/css/lists.css'>";
				echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.3.2/sweetalert2.css">';
				echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/6.3.2/sweetalert2.js"></script>';

				$css_ie=<<<EOF
					<style>	
						body{display: none !important;}
					</style>
EOF;
				echo "
				<!--[if IE]>
					{$css_ie}
				<![endif]-->
				";
			} ?>

			<script type="text/javascript">
				var doc = document.documentElement;
				doc.setAttribute('data-useragent', navigator.userAgent);
			</script>

			<?php 
				echo Avada()->settings->get( 'google_analytics' );
				echo Avada()->settings->get( 'space_head' );
			?>

			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#span_value_77').html('<?php echo $_POST["input_77"] ?>');
				jQuery('#span_value_78').html('<?php echo $_POST["input_78"] ?>');
				jQuery('#span_value_79').html('<?php echo $_POST["input_79"] ?>');
				jQuery('#span_value_83').html('<?php echo $_POST["input_83"] ?>');
				jQuery('#span_value_82').html('<?php echo $_POST["input_82"] ?>');
				jQuery('#span_value_81').html('<?php echo $_POST["input_81"] ?>');
				jQuery('#span_value_80').html('<?php echo $_POST["input_80"] ?>');
			});
			</script>
	</head>

	<?php
		$wrapper_class = '';
		if ( is_page_template( 'blank.php' ) ) {
			$wrapper_class  = 'wrapper_blank';
		}

		if ( 'modern' == Avada()->settings->get( 'mobile_menu_design' ) ) {
			$mobile_logo_pos = strtolower( Avada()->settings->get( 'logo_alignment' ) );
			if ( 'center' == strtolower( Avada()->settings->get( 'logo_alignment' ) ) ) {
				$mobile_logo_pos = 'left';
			}
		}

		$_POST['input_77'] = $_POST['input_6'];
		$_POST['input_78'] = $_POST['input_11'];
		$_POST['input_79'] = $_POST['input_13'];
		$_POST['input_83'] = $_POST['input_24'];
		$_POST['input_82'] = $_POST['input_33'];
		$_POST['input_81'] = $_POST['input_42'];
		$_POST['input_80'] = $_POST['input_51'];
	?>

	<body <?php body_class(); ?>>
		<?php do_action( 'avada_before_body_content' ); ?>
		<?php $boxed_side_header_right = false; ?>
		<?php if ( ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && ( 'default' == get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) || '' == get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) ) ) || 'boxed' == get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) ) && 'Top' != Avada()->settings->get( 'header_position' ) ) : ?>
			<?php if ( Avada()->settings->get( 'slidingbar_widgets' ) && ! is_page_template( 'blank.php' ) && ( 'Right' == Avada()->settings->get( 'header_position' ) || 'Left' == Avada()->settings->get( 'header_position' ) ) ) : ?>
				<?php get_template_part( 'slidingbar' ); ?>
				<?php $boxed_side_header_right = true; ?>
			<?php endif; ?>
			<div id="boxed-wrapper">
			<?php endif; ?>
			<div id="wrapper" class="<?php echo $wrapper_class; ?>">
				<div id="home" style="position:relative;top:1px;"></div>
				<?php if ( Avada()->settings->get( 'slidingbar_widgets' ) && ! is_page_template( 'blank.php' ) && ! $boxed_side_header_right ) : ?>
					<?php get_template_part( 'slidingbar' ); ?>
				<?php endif; ?>
				<?php if ( false !== strpos( Avada()->settings->get( 'footer_special_effects' ), 'footer_sticky' ) ) : ?>
					<div class="above-footer-wrapper">
					<?php endif; ?>

					<?php avada_header_template( 'Below' ); ?>

					<?php if ( ! is_page_template( 'blank.php' ) && 'no' != get_post_meta( $queried_object_id, 'pyre_display_header', true ) ) { ?>
					<header class="top-header">
						<div class="top-header-inner clearfix">
							<div class="top-header-right">
								<?php 
								$languages = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
								$languages_html = '';

								if ( ! is_wp_error( $languages ) && ! empty( $languages ) ) {
									foreach ( $languages as $lang_key => $lang_value ) {
										if ( $lang_value['active'] ) {
											$button_class = 'btn-secondary';
										} else {
											$button_class = 'btn-primary';
										}
										$languages_html .= '<a href="' . $lang_value['url'] . '" class="btn ' . $button_class . '">' . $lang_value['native_name'] . '</a>&nbsp;';
									}
								} else {
	                                if (ICL_LANGUAGE_CODE=='fr') {
	                                	$url = $_SERVER['REQUEST_URI'];
	                                   	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	                                   	$siteUrl = get_site_url();

	                                  	$baseLink = str_replace($siteUrl, "", $actual_link);
	                                  	$engLink = $siteUrl . "/en" . $baseLink;
	                                   	$languages_html .=' <a href=" ' . $engLink . ' " class="btn btn-primary" >English</a>&nbsp;<a href=" ' . $actual_link . ' " class="btn btn-secondary">Français</a>';
	                                } else {
	                                	$url = $_SERVER['REQUEST_URI'];
	                                   	$frLink = str_replace('/en/', "/", $url);
	                                   	$languages_html .=' <a href=" ' . $url . ' " class="btn  btn-secondary" >English</a>&nbsp;<a href=" ' . $frLink . ' " class="btn btn-primary">Français</a>';
	                                }
	                            }
	                            
								?>
								<?php //if ( $languages_html ): ?>
									<div class="top-header-language-switcher">
										<?php echo $languages_html; ?>
	                                    <?php if(ICL_LANGUAGE_CODE=='fr') { ?>
	                                        <a href="/cart"><i class="fa fa-shopping-cart" aria-hidden="true" style="color:#FFF;padding-left:10px;font-size:20px;"></i></a><?
	                                } else {
	                                       ?><a href="/en/cart"><i class="fa fa-shopping-cart" aria-hidden="true" style="color:#FFF;padding-left:10px;font-size:20px;"></i></a><?

	                                }
									?></div>
								<?php //endif; ?>

								<div class="top-header-search dt">
									<div class="dtc">
										<?php
										if(false){
											?><h4><?php _e( 'Recherche', 'fbg' ); ?></h4><?php
										} else {
											?><h4>&nbsp;</h4><?php
										}

										?><form role="search" class="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
										<div class="search-table">
											<div class="search-field">
												<?php if(ICL_LANGUAGE_CODE=='fr') { ?>
												<input type="text" value="" name="s" class="s" placeholder="Rechercher" />
												<?php } else { ?>
												<input type="text" value="" name="s" class="s" placeholder="<?php esc_html_e( 'Search Events, Directory & more', 'fbg' ); ?>" />
												<?php } ?>
											</div>
											<div class="search-button">
												<input type="submit" class="searchsubmit" value="&#xf002;" />
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="top-header-left">
							<div class="top-header-logo-holder">
								<a href="<?php echo home_url(); ?>" class="top-header-logo">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fbg-logo.png" alt="<?php bloginfo( 'name' ); ?>" style="width:90%;padding-left:20px;">
								</a>
							</div>

							<div class="top-header-button">
								<div class="chambres-dans"><span class="color-secondary"><?php 
									if(ICL_LANGUAGE_CODE=='fr') {
										echo '115</span> CHAMBRES DANS <span class="color-secondary">85</span> PAYS'; ?>
									</div><a href="/decouvrir-les-chambres/" class="fusion-button button-flat button-round btn-secondary"><?php echo 'Découvrir les Chambres'; ?></a>
									<?php
									$sinceimg = get_stylesheet_directory_uri().'/images/DEPUIS-1974.png';
								} else {
									_e( '115</span> CHAMBERS IN <span class="color-secondary">85</span> COUNTRIES', 'fbg' ); ?>
								</div><a href="/en/discover-the-chambers/" class="fusion-button button-flat button-round btn-secondary"><?php echo _e( 'Discover the Chambers', 'fbg' ); ?></a>
								<?php
								$sinceimg = get_stylesheet_directory_uri().'/images/since1974.svg';
							} ?>
						</div>
						<div class="header-since">
							<img src="<?php echo $sinceimg; ?>" alt="Since 1974" width="79" height="91" />
						</div>
					</div>
				</div>
				<div class="fusion-clearfix"></div>
			</header>
			<?php } ?>


			<?php if ( 'Left' == Avada()->settings->get( 'header_position' ) || 'Right' == Avada()->settings->get( 'header_position' ) ) : ?>
				<?php avada_side_header(); ?>
			<?php endif; ?>

			<div id="sliders-container">
				<?php
				if ( is_search() ) {
					$slider_page_id = '';
				} else {
					$slider_page_id = '';
					if ( ! is_home() && ! is_front_page() && ! is_archive() && isset( $object_id ) ) {
						$slider_page_id = $object_id;
					}
					if ( ! is_home() && is_front_page() && isset( $object_id ) ) {
						$slider_page_id = $object_id;
					}
					if ( is_home() && ! is_front_page() ) {
						$slider_page_id = get_option( 'page_for_posts' );
					}
					if ( class_exists( 'WooCommerce' ) && is_shop() ) {
						$slider_page_id = get_option( 'woocommerce_shop_page_id' );
					}

					if ( ( 'publish' == get_post_status( $slider_page_id ) && ! post_password_required() ) || ( current_user_can( 'read_private_pages' ) && in_array( get_post_status( $slider_page_id ), array( 'private', 'draft', 'pending' ) ) ) ) {
						avada_slider( $slider_page_id );
					}
				} ?>
			</div>
			<?php if ( get_post_meta( $slider_page_id, 'pyre_fallback', true ) ) : ?>
				<div id="fallback-slide">
					<img src="<?php echo get_post_meta( $slider_page_id, 'pyre_fallback', true ); ?>" alt="" />
				</div>
			<?php endif; ?>
			<?php avada_header_template( 'Above' ); ?>

			<?php if ( has_action( 'avada_override_current_page_title_bar' ) ) : ?>
				<?php do_action( 'avada_override_current_page_title_bar', $c_page_id ); ?>
			<?php else : ?>
				<?php avada_current_page_title_bar( $c_page_id ); ?>
			<?php endif; ?>

			<?php if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'recaptcha_public' ) && Avada()->settings->get( 'recaptcha_private' ) ) : ?>
				<script type="text/javascript">var RecaptchaOptions = { theme : '<?php echo Avada()->settings->get( 'recaptcha_color_scheme' ); ?>' };</script>
			<?php endif; ?>

			<?php if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'gmap_address' ) && Avada()->settings->get( 'status_gmap' ) ) : ?>
				<?php
				$map_popup             = ( ! Avada()->settings->get( 'map_popup' ) )          ? 'yes' : 'no';
				$map_scrollwheel       = ( Avada()->settings->get( 'map_scrollwheel' ) )    ? 'yes' : 'no';
				$map_scale             = ( Avada()->settings->get( 'map_scale' ) )          ? 'yes' : 'no';
				$map_zoomcontrol       = ( Avada()->settings->get( 'map_zoomcontrol' ) )    ? 'yes' : 'no';
				$address_pin           = ( Avada()->settings->get( 'map_pin' ) )            ? 'yes' : 'no';
				$address_pin_animation = ( Avada()->settings->get( 'gmap_pin_animation' ) ) ? 'yes' : 'no';
				?>
				<div id="fusion-gmap-container">
					<?php echo Avada()->google_map->render_map( array(
						'address'                  => Avada()->settings->get( 'gmap_address' ),
						'type'                     => Avada()->settings->get( 'gmap_type' ),
						'address_pin'              => $address_pin,
						'animation'                => $address_pin_animation,
						'map_style'                => Avada()->settings->get( 'map_styling' ),
						'overlay_color'            => Avada()->settings->get( 'map_overlay_color' ),
						'infobox'                  => Avada()->settings->get( 'map_infobox_styling' ),
						'infobox_background_color' => Avada()->settings->get( 'map_infobox_bg_color' ),
						'infobox_text_color'       => Avada()->settings->get( 'map_infobox_text_color' ),
						'infobox_content'          => htmlentities( Avada()->settings->get( 'map_infobox_content' ) ),
						'icon'                     => Avada()->settings->get( 'map_custom_marker_icon' ),
						'width'                    => Avada()->settings->get( 'gmap_dimensions', 'width' ),
						'height'                   => Avada()->settings->get( 'gmap_dimensions', 'height' ),
						'zoom'                     => Avada()->settings->get( 'map_zoom_level' ),
						'scrollwheel'              => $map_scrollwheel,
						'scale'                    => $map_scale,
						'zoom_pancontrol'          => $map_zoomcontrol,
						'popup'                    => $map_popup,
						) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( is_page_template( 'contact-2.php' ) && Avada()->settings->get( 'gmap_address' ) && Avada()->settings->get( 'status_gmap' ) ) : ?>
					<?php
					$map_popup             = ( ! Avada()->settings->get( 'map_popup' ) )          ? 'yes' : 'no';
					$map_scrollwheel       = ( Avada()->settings->get( 'map_scrollwheel' ) )    ? 'yes' : 'no';
					$map_scale             = ( Avada()->settings->get( 'map_scale' ) )          ? 'yes' : 'no';
					$map_zoomcontrol       = ( Avada()->settings->get( 'map_zoomcontrol' ) )    ? 'yes' : 'no';
					$address_pin_animation = ( Avada()->settings->get( 'gmap_pin_animation' ) ) ? 'yes' : 'no';
					?>
					<div id="fusion-gmap-container">
						<?php echo Avada()->google_map->render_map( array(
							'address'                  => Avada()->settings->get( 'gmap_address' ),
							'type'                     => Avada()->settings->get( 'gmap_type' ),
							'map_style'                => Avada()->settings->get( 'map_styling' ),
							'animation'                => $address_pin_animation,
							'overlay_color'            => Avada()->settings->get( 'map_overlay_color' ),
							'infobox'                  => Avada()->settings->get( 'map_infobox_styling' ),
							'infobox_background_color' => Avada()->settings->get( 'map_infobox_bg_color' ),
							'infobox_text_color'       => Avada()->settings->get( 'map_infobox_text_color' ),
							'infobox_content'          => htmlentities( Avada()->settings->get( 'map_infobox_content' ) ),
							'icon'                     => Avada()->settings->get( 'map_custom_marker_icon' ),
							'width'                    => Avada()->settings->get( 'gmap_dimensions', 'width' ),
							'height'                   => Avada()->settings->get( 'gmap_dimensions', 'height' ),
							'zoom'                     => Avada()->settings->get( 'map_zoom_level' ),
							'scrollwheel'              => $map_scrollwheel,
							'scale'                    => $map_scale,
							'zoom_pancontrol'          => $map_zoomcontrol,
							'popup'                    => $map_popup,
							) ); ?>
						</div>
					<?php endif; ?>
					<?php
					$main_css   = '';
					$row_css    = '';
					$main_class = '';

					if ( Avada()->layout->is_hundred_percent_template() ) {
						$main_css = 'padding-left:0px;padding-right:0px;';
						if ( Avada()->settings->get( 'hundredp_padding' ) && ! get_post_meta( $c_page_id, 'pyre_hundredp_padding', true ) ) {
							$main_css = 'padding-left:' . Avada()->settings->get( 'hundredp_padding' ) . ';padding-right:' . Avada()->settings->get( 'hundredp_padding' );
						}
						if ( get_post_meta( $c_page_id, 'pyre_hundredp_padding', true ) ) {
							$main_css = 'padding-left:' . get_post_meta( $c_page_id, 'pyre_hundredp_padding', true ) . ';padding-right:' . get_post_meta( $c_page_id, 'pyre_hundredp_padding', true );
						}
						$row_css    = 'max-width:100%;';
						$main_class = 'width-100';
					}
					do_action( 'avada_before_main_container' );
					?>
					<div id="main" class="clearfix <?php echo $main_class; ?>" style="<?php echo $main_css; ?>">
						<div class="fusion-row" style="<?php echo $row_css; ?>">
