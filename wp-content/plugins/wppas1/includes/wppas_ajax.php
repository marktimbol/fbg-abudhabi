<?php
/**
 * Lightweight frontend ajax alternative to admin-ajax.php
 * Modified from admin-ajax.php @ v4.4.1
 * https://codepad.co/snippet/Kd6owEuP
 * http://coderrr.com/create-your-own-admin-ajax-php-type-handler/
 * http://webcache.googleusercontent.com/search?q=cache:wfLZ7-hO5TsJ:solislab.com/blog/5-tips-for-using-ajax-in-wordpress/+&cd=1&hl=en&ct=clnk
 */


/** Executing AJAX process. */
define( 'DOING_AJAX', true );


/*
 * Optionally get even more lightweight and require only 
 * what you need after wp-load.php
 *
 * https://core.trac.wordpress.org/browser/branches/3.4/wp-settings.php#L99
 */
// define('SHORTINIT', true);


/** 
 * Load WordPress Bootstrap
 * assumes this file (frontend-ajax.php) is in theme root. Adjust as needed
 */
require_once( '../../../wp-load.php' );
// if SHORTINIT add your require_once()'s here


/** Allow for cross-domain requests (from the frontend). */
send_origin_headers();


/** Require an action parameter */
if ( empty( $_REQUEST['action'] ) )
	die( '0' );

	
/** Setup headers */
@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );


/** Send a HTTP header to disable content type sniffing in browsers which support it. */
send_nosniff_header();
/** Set headers to prevent caching for browsers. */
nocache_headers();


/**
 * Optionally allow only specific actions 
 */
/*
$approved_actions = array(
    'action_one',
    'action_two',
);

if( ! in_array( esc_attr( trim( $_REQUEST['action'] ) ), $approved_actions ) )
    die( '0' );
*/


/** Register Ajax calls. */
if ( is_user_logged_in() )
{
	do_action( 'wppas_ajax_' . $_REQUEST['action'] );
}
else
{
	do_action( 'wppas_ajax_nopriv_' . $_REQUEST['action'] );
}

/** Default status */
die( '0' );
?>