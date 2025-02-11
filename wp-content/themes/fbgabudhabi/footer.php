<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<?php do_action( 'avada_after_main_content' ); ?>

</div>  <!-- fusion-row -->
</div>  <!-- #main -->

<?php do_action( 'avada_after_main_container' ); ?>

<?php global $social_icons; ?>

<?php if ( false !== strpos( Avada()->settings->get( 'footer_special_effects' ), 'footer_sticky' ) ) : ?>
</div><style>
body .newsletter .form-group, body .newsletter .form-submit{
   max-width:18% !important;
}
</style>

<?php endif; ?>

<?php
			/**
			 * Get the correct page ID.
			 */
			// $c_page_id = Avada()->get_page_id();
			$c_page_id = get_the_ID();
			?>

			<?php
			/**
			 * Only include the footer.
			 */
			?>
			<?php if ( ! is_page_template( 'blank.php' ) ) : ?>
				<?php $footer_parallax_class = ( 'footer_parallax_effect' == Avada()->settings->get( 'footer_special_effects' ) ) ? ' fusion-footer-parallax' : ''; ?>

				<div class="fusion-footer<?php echo $footer_parallax_class; ?>">

					<?php
					/**
					 * Check if the footer widget area should be displayed.
					 */
					?>
					<?php if ( ( Avada()->settings->get( 'footer_widgets' ) && 'no' != get_post_meta( $c_page_id, 'pyre_display_footer', true ) ) || ( ! Avada()->settings->get( 'footer_widgets' ) && 'yes' == get_post_meta( $c_page_id, 'pyre_display_footer', true ) ) ) : ?>
						<?php $footer_widget_area_center_class = ( Avada()->settings->get( 'footer_widgets_center_content' ) ) ? ' fusion-footer-widget-area-center' : ''; ?>

						<footer class="fusion-footer-widget-area fusion-widget-area<?php echo $footer_widget_area_center_class; ?>">
							<div class="fusion-row">
								<div class="fusion-columns fusion-columns-<?php echo Avada()->settings->get( 'footer_widgets_columns' ); ?> fusion-widget-area">
									<?php
									/**
									 * Check the column width based on the amount of columns chosen in Theme Options.
									 */
									$column_width = ( '5' == Avada()->settings->get( 'footer_widgets_columns' ) ) ? 2 : 12 / Avada()->settings->get( 'footer_widgets_columns' );
									?>

									<?php
									/**
									 * Render as many widget columns as have been chosen in Theme Options.
									 */
									?>
									<?php for ( $i = 1; $i < 7; $i++ ) : ?>
										<?php if ( $i <= Avada()->settings->get( 'footer_widgets_columns' ) ) : ?>
											<div class="fusion-column<?php echo ( Avada()->settings->get( 'footer_widgets_columns' ) == $i ) ? ' fusion-column-last' : ''; ?> col-lg-<?php echo $column_width; ?> col-md-<?php echo $column_width; ?> col-sm-<?php echo $column_width; ?>">
												<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'avada-footer-widget-' . $i ) ) : ?>
													<?php
													/**
													 * All is good, dynamic_sidebar() already called the rendering.
													 */
													?>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endfor; ?>

									<div class="fusion-clearfix"></div>
								</div> <!-- fusion-columns -->
							</div> <!-- fusion-row -->
						</footer> <!-- fusion-footer-widget-area -->
					<?php endif; // End footer wigets check. ?>

					<?php
                    if(ICL_LANGUAGE_CODE=='fr') {
                        include_once "news-letter-fr.php";
                    }
                    else{
                        include_once "news-letter-en.php";
                    }

					/**
					 * Check if the footer copyright area should be displayed.
					 */
					?>
					<?php if ( ( Avada()->settings->get( 'footer_copyright' ) && 'no' != get_post_meta( $c_page_id, 'pyre_display_copyright', true ) ) || ( ! Avada()->settings->get( 'footer_copyright' ) && 'yes' == get_post_meta( $c_page_id, 'pyre_display_copyright', true ) ) ) : ?>
						<?php $footer_copyright_center_class = ( Avada()->settings->get( 'footer_copyright_center_content' ) ) ? ' fusion-footer-copyright-center' : ''; ?>

						<footer id="footer" class="fusion-footer-copyright-area<?php echo $footer_copyright_center_class; ?>">
							<div class="fusion-row">
								<div class="fusion-copyright-content">

									<?php
									/**
									 * Footer Content (Copyright area) avada_footer_copyright_content hook.
									 *
									 * @hooked avada_render_footer_copyright_notice - 10 (outputs the HTML for the Theme Options footer copyright text)
									 * @hooked avada_render_footer_social_icons - 15 (outputs the HTML for the footer social icons)..
									 */
									do_action( 'avada_footer_copyright_content' );
									?>

								</div> <!-- fusion-fusion-copyright-content -->
								<div class="m_footer_pay_links">
									<p>Payment Options:</p>
									<ul class="m_list_footer_pay">
										<li><span class="m_cash_of_the">Cash at the</span><span class="m_cash_of_the_office">Office</span></li>
										<li><i class="fa fa-paypal" aria-hidden="true"></i></a></li>
										<li><img src="https://www.fbgabudhabi.com/wp-content/themes/Avada/assets/images/visa-mastercard1.png" alt=""></li>
										<li><img src="https://www.fbgabudhabi.com/wp-content/themes/Avada/assets/images/visa-mastercard2.png" alt=""></li>
									</ul>
								</div>
							</div> <!-- fusion-row -->
						</footer> <!-- #footer -->
					<?php endif; // End footer copyright area check. ?>
				</div> <!-- fusion-footer -->
			<?php endif; // End is not blank page check. ?>
		</div> <!-- wrapper -->

		<?php
		/**
		 * Check if boxed side header layout is used; if so close the #boxed-wrapper container.
		 */
		?>
		<?php if ( ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && 'default' == get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) ) || 'boxed' == get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) ) && 'Top' != Avada()->settings->get( 'header_position' ) ) : ?>
		</div> <!-- #boxed-wrapper -->
	<?php endif; ?>

	<a class="fusion-one-page-text-link fusion-page-load-link"></a>

	<!-- W3TC-include-js-head -->

	<?php wp_footer(); ?>

	<?php // echo "<link rel='stylesheet' href='".content_url()."/themes/Avada/assets/css/owl.carousel.css'>"; ?>
	<?php // echo "<link rel='stylesheet' href='".content_url()."/themes/Avada/assets/css/owl.theme.css'>"; ?>
	<?php // echo "<script type='text/javascript' src='".content_url()."/themes/Avada/assets/js/owl.carousel.min.js'></script>"; ?>
    <style>
             .fusion-layout-column.fusion-spacing-yes { margin-right:0px; } 

        .fusion-layout-column.fusion-spacing-yes.fusion-one-fourth {
            width: 100%;
        } 

        
    .owl-next{
        position: absolute;
        top: 63px;
        right: 15px;
        background: url(https://www.fbgabudhabi.com/wp-content/uploads/2017/01/m-right-arrow.png)!important;
        display: block;
        width: 35px;
        height: 48px;
        margin: 0;
        padding: 0;
        border-radius: 0 !important;
        opacity: 1 !important;
         text-indent: -9999px;
    }
    .owl-prev{

        position: absolute;
        top: 63px;
        left: 15px;
        background: url(https://www.fbgabudhabi.com/wp-content/uploads/2017/01/m-left-arrow.png)!important;
        display: block;
        width: 35px;
        height: 48px;
        margin: 0;
        padding: 0;
        border-radius: 0 !important;
        opacity: 1 !important;
        text-indent: -9999px;
        }

        .owl-theme .owl-controls .owl-page.active span{
          background-color: #da003c !important;
        }
        .owl-theme .owl-controls .owl-page span{
          background: #0291dd !important;
        }
    </style>
	<script>
	jQuery(document).ready(function(){
		//jQuery("body.home .upcoming-events").find(".fusion-one-fourth").removeClass("fusion-spacing-yes fusion-one-fourth");
		//jQuery("body.home .upcoming-events").find(".fusion-clearfix").remove();
		/*jQuery("body.home .upcoming-events").each(function(){
			var imgH = (jQuery(this).find(".tribe-events-event-image").width()*56.11)/100;
			jQuery(this).find(".tribe-events-event-image").css({"height":imgH});
		});*/

       
		jQuery("body.home .upcoming-events").owlCarousel({
			items : 4,
			itemsCustom : false,
			itemsDesktop : [1199,4],
			itemsDesktopSmall : [979,3],
			itemsTablet : [768,2],
			itemsMobile : [479,1],
			slideSpeed : 700,
			navigation : true,
			pagination : true,
			autoHeight : false,
			autoPlay : false,
			lazyLoad : true
		});
	});
	</script>
	<?php
		/**
		 * Echo the scripts added to the "before </body>" field in Theme Options
		 */
		echo Avada()->settings->get( 'space_body' );
	?>
	</body>
	</html>
