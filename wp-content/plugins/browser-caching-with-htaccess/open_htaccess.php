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

function bch_open () {

	$file = ABSPATH .'.htaccess';
	if (file_exists($file)) {

		$open = fopen($file, "r");
		if (!$open) {
			chmod($open, 0644);
		}
		while (!feof($open)) {

			$line_of_text = fgets($open);
			print $line_of_text ."<br>";
		}
		fclose($open);
	} else {
		echo '<div style="color:red;">No .htacces file was found in your root directory. Probably your website does not run on an Apache Server.</div>';
		exit();
	}
}
?>