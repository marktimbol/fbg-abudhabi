<?php 
/*
 * Browser Caching with .htaccess
 * Copyright (C) 2015  Tobias Merz

 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once( plugin_dir_path( __FILE__ ) . 'adminpages/admin.php');

function bch_modified_caching() {
/*
 * VARIABLES FROM INPUT FIELDS
 */
// CSS
	$css_number = get_option('css_number');
	if ($css_number > 1) {
		$add_s_css = "s";
	}else {
		$add_s_css = "";
	}
	$css_time = get_option('css_time');
	
// JS
	$js_number = get_option('js_number');
	if($js_number > 1) {
		$add_s_js = "s";
	} else {
		$add_s_js = "";
	}
	$js_time = get_option('js_time');
	
// HTML
	$html_number = get_option('html_number');
	if($html_number > 1) {
		$add_s_html = "s";
	} else {
		$add_s_html = "";
	}
	$html_time = get_option('html_time');
	
// JPEG
	$jpeg_number = get_option('jpeg_number');
	if ($jpeg_number > 1) {
		$add_s_jpeg = "s";
	} else {
		$add_s_jpeg = "";
	}
	$jpeg_time = get_option('jpeg_time');

// PNG
	$png_number = get_option('png_number');
	if ($png_number > 1) {
		$add_s_png = "s";
	} else {
		$add_s_png = "";
	}
	$png_time = get_option('png_time');
	
	
/*
 *  INSERTS INTO .HTACCESS
 */
	
	$entry  = "\n";
	$entry .= "\n";
	$entry .= "\n";
	$entry .= '# Browser Caching'. "\n";
	$entry .= '<IfModule mod_expires.c>'. "\n";
	$entry .= 'ExpiresActive On'. "\n";
	$entry .= 'ExpiresByType text/css "access plus ' . $css_number .' '.  $css_time  . $add_s_css. '"'. "\n";
	$entry .= 'ExpiresByType text/javascript "access plus ' . $js_number . ' '. $js_time . $add_s_js .'"'. "\n";
	$entry .= 'ExpiresByType text/html "access plus ' . $html_number .' '. $html_time . $add_s_html. '"'. "\n";
	$entry .= 'ExpiresByType application/javascript "access plus 1 month"'. "\n";
	$entry .= 'ExpiresByType application/x-javascript "access plus 1 month"'. "\n";
	$entry .= 'ExpiresByType application/xhtml-xml "access plus 1 month"'. "\n";
	$entry .= 'ExpiresByType image/gif "access plus 1 month"'. "\n";
	$entry .= 'ExpiresByType image/jpeg "access plus ' . $jpeg_number .' '. $jpeg_time . $add_s_jpeg . '"'. "\n";
	$entry .= 'ExpiresByType image/png "access plus ' . $png_number .' '. $png_time . $add_s_png . '"'. "\n";
	$entry .= 'ExpiresByType image/x-icon "access plus 1 month"'. "\n";
	$entry .= '</IfModule>'. "\n";
	$entry .='# END Caching'. "\n";
	
	$file = ABSPATH .'.htaccess';
	
/*
		 * Check if you are already using BROWSER CACHING	
		 */
		$bc_string = 'ExpiresByType';
		$handle = fopen($file, 'r');
		$valid = false; // init as false
		while (($buffer = fgets($handle)) !== false) {
			if (strpos($buffer, $bc_string) !== false) {
				$valid = TRUE;
				break; // Once you find the string, you should break out the loop.
			}
		}
		fclose($handle);
		if ($valid) {
				echo '<div class="error"><h2 >It seems that you are already using Browser Caching. Please check you\'re .htaccess file.</h2></div>';
		
				} else {
		
			/*
			 * WRITING THE INSERTS OF BROWSER CACHING
			 */
			
				if (file_exists($file)) {
				
					if(is_writable($file)) {
				
						if (!$handle = fopen($file, "a")) {
							echo '<div class="error"><h2>Can not open the file. You have to change file permissions</h2>
						<p>Look how to change file permissions under this <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">WordPress Documentation</a></p></div>';
						}
				
						if( !fwrite($handle, $entry)) {
							echo '<div class="error"><h2>Can not write the file. You have to change file permissions.</h2>
						<p>Look how to change file permissions under this <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">WordPress Documentation</a></p></div>';
						}
				
						echo'
						<div class="updated">
						<h2>PERFECT</h2>
						<h3>You are now using BROWSER CACHING!</h3>
						</div>';
						fclose($handle);
					}else {
						echo '<div class="error"><h2 >Can not write the file. You have to change file permissions.</h2>
						<p>Look how to change file permissions under this <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">WordPress Documentation</a></p>
						<p>After you have changed file permissions try again.</p>
						</div>';
					}
				}else {
					echo '<div class="error"><h2 >No .htacces file was found in your root directory. Probably your website does not run on an Apache Server.</h2></div>';
				}
			}
}
?>