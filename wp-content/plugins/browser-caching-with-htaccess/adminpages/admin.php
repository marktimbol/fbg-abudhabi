<?php 

function bch_caching_submenu() {
	add_submenu_page(
			'tools.php',
			'Browser Caching',
			'Browser Caching',
			'manage_options',
			'bch_caching_submenu',
			'bch_gen_ad_pa');
}

add_action('admin_menu', 'bch_caching_submenu');

/* ------------------------------------------------------------------------ *
 * Stylesheet
 * ------------------------------------------------------------------------ */
// get styles
function bch_load_admin_style() {
	wp_enqueue_style(
			'admin_caching_style',
			plugins_url('css/admin.css', dirname(__FILE__)));
}
// load stylesheet
add_action ('init', 'bch_load_admin_style');

/* ------------------------------------------------------------------------ *
 * JavaScript
 * ------------------------------------------------------------------------ */
function bch_load_javascript_fkt() {
		wp_enqueue_script(
				'admin_caching_script',
				plugins_url( 'js/function.js', dirname(__FILE__)),
				array( 'jquery' )
				);
}
add_action( 'init', 'bch_load_javascript_fkt' );

function bch_gen_ad_pa() {

/*
 * HEADLINE VARIABLES
 */
	$headline = "Browser Caching with .htaccess";
	$modify = "Do you want to modify the Expires time span before activating Browser Caching?";
	$not_modifiy = "Activate without modification";

/*
 * MODIFIED VALUES
 */

	if( @$_POST['modified_hidden_bch'] == 'Y') {
// CSS
// number at css field
	$css_number = $_POST['css_number'];
	update_option('css_number', $css_number);
// month or week at css field
	$css_time = $_POST['css_time'];
	update_option('css_time', $css_time);
// JAVASCRIPT
// number at js field
	$js_number = $_POST['js_number'];
	update_option('js_number', $js_number);
// month or week at js field
	$js_time = $_POST['js_time'];
	update_option('js_time', $js_time);
// HTML
// number at html field
	$html_number = $_POST['html_number'];
	update_option('html_number', $html_number);
// month or week at html field
	$html_time = $_POST['html_time'];
	update_option('html_time', $html_time);
// JPEG
// number at jpeg field
	$jpeg_number = $_POST['jpeg_number'];
	update_option('jpeg_number', $jpeg_number);
//month or week at jpeg field
	$jpeg_time = $_POST['jpeg_time'];
	update_option('jpeg_time', $jpeg_time);
// PNG
// number at png field
	$png_number = $_POST['png_number'];
	update_option('png_number', $png_number);
// month or week at png field
	$png_time = $_POST['png_time'];
	update_option('png_time', $png_time);
	}

/*
 * CALL BCH_HTACCESS FUNCTION
 */
	if ( isset($_POST ['submit1']) ) {
		$do = bch_htaccess();
	}
/*
 * CALL BCH_MODIFIED_CACHING FUNCTION
 */
	if ( isset($_POST ['submit3']) ) {
		$do = bch_modified_caching();
	}

/*
 * ADMIN PAGE
 */
	?>
	<div class="col-1">
	<div style="width:800px; padding:10px 20px; background-color:#eee; font-size:.95em; font-family:Georgia;margin:20px">
	<h1><?php echo $headline; ?></h1>
	</bR>
	<h3><?php echo $modify; ?></h3><br><br>
	<input class="button button-primary" type="submit" name="modify" id="modify_button" value="modify Expires" />
	</form>
	<div id="modify_form" class="mod">
	<form id="modify" method="post" action="">
	<input type="hidden" name="modified_hidden_bch" value="Y">
	<p><b>CSS:</b> &nbsp;ExpiresByType text/css "access plus <input type="number" name="css_number" min="1" max="9" required>
	<input type="radio" name="css_time" value="week" checked>Week
	<input type="radio" name="css_time" value="month">Month
	"</p>
	<p><b>JacaScript:</b> &nbsp;ExpiresByType text/javascript "access plus <input type="number" name="js_number" min="1" max="9" required>
	<input type="radio" name="js_time" value="week">Week
	<input type="radio" name="js_time" value="month" checked>Month
	"</p>
	<p>
	<b>HTML:</b> &nbsp;ExpiresByType text/html "access plus <input type="number" name="html_number" min="1" max="9" required>
	<input type="radio" name="html_time" value="week" >Week
	<input type="radio" name="html_time" value="month" checked>Month
	"</p>
	<p>
	<b>JPEG:</b> &nbsp;ExpiresByType image/jpeg "access plus <input type="number" name="jpeg_number" min="1" max="9" required>
	<input type="radio" name="jpeg_time" value="week" >Week
	<input type="radio" name="jpeg_time" value="month" checked>Month
	"</p>
	<p>
	<b>PNG:</b> &nbsp;ExpiresByType image/png "access plus <input type="number" name="png_number" min="1" max="9" required>
	<input type="radio" name="png_time" value="week" >Week
	<input type="radio" name="png_time" value="month" checked>Month
	"</p>
	<input class="button button-primary" type="submit" name="submit3" id="modified_cach_submit" value="activate modified Browser Caching" />
	</form>
	<h4>NOTE:</h4>
	<p>If you already activated Browser Caching before, you can not modify the values here!</p>
	<p>Before modifying the values you have to delete the whole Browser Caching block from your .htaccess.</p>
	<hr class="line">
	</hr>
	</div><br><br><br>
	<h3><?php echo $not_modifiy; ?></h3>
	<form id="options_form" method="post" action="">
	<input type="hidden" name="hidden_bch" value="Y">
	<div class="submit">
	<input class="button button-primary" type="submit" name="submit1" id="cach_submit" value="activate Browser Caching" />
	</div>
	</form>
	<div class="col-2">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="SH6LZUSYAYAH8">
			<input type="image" src="https://www.paypalobjects.com/en_US/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>


	<p>You can check if you are already using Browser caching in your .htaccess file. You find it in your root directory, normally it should be placed here: <?php echo get_home_url() .'/.htaccess'; ?></p>
	<p>You can also click the button below to ckeck you .htaccess file</p>
	<p><b>Search for code like this:</b></p>
	<P style="color:green">ExpiresActive On</P>
	<P style="color:green">ExpiresByType text/css "access plus 1 month"</P>
	<p style="color:green">ExpiresByType text/javascript "access plus 1 month"</p>
	</bR>
	<P>If you find something like this, there is no need to activate Browser Caching again!</P>

	<h3>Check your .htacceess file...</h3>
	<form id="open_form" method="post" action="">
	<div class="submit">
	<input class="button button-primary" type="submit" name="submit2" id="cach_submit" value="check .htaccess file" />
	</div>
	</form>
    <p>If you like to speed up your page even more, check out the <a href="https://wordpress.org/plugins/optimizer-for-faster-websites/" target="_blank" title="Wordpress Plugin Optimizer For Faster Websites">Optimizer For Faster Websites Plugin</a>.</p>
	<p>It does not only provide Browser Caching but also GZIP Compression!</p>
	</div>
<?php 	
/*
 * CALL BCH_OPEN FUNCTION
 */
	if ( isset($_POST ['submit2']) ) {
		echo "<h2>The Code below shows the actual impact of your .htaccess file</h2>";
		$do = bch_open();
		if($do){
			echo "Can not open file!";
			exit();
			}	
	}
}
?>