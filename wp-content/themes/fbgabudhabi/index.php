<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
	<div id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php get_template_part( 'templates/blog', 'layout' ); ?>
	</div>
	<?php do_action( 'avada_after_content' ); ?>
<?php get_footer(); ?>