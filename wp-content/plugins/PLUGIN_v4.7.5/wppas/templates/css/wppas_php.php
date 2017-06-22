<?php
header("Content-type: text/css; charset: UTF-8");

$wpproads_adzone_class = get_option('wpproads_adzone_class', 'wppaszone');
?>


/* ----------------------------------------------------------------
 * ADZONES
 * ---------------------------------------------------------------- */
.<?php echo $wpproads_adzone_class; ?> img {
	max-width: 100%;
	height:auto;
}
.<?php echo $wpproads_adzone_class; ?> {overflow:hidden; visibility: visible !important; display: inherit !important; }
.pas_fly_in .<?php echo $wpproads_adzone_class; ?> {visibility: hidden !important; }
.pas_fly_in.showing .<?php echo $wpproads_adzone_class; ?> {visibility: visible !important; }

.wppasrotate, .<?php echo $wpproads_adzone_class; ?> li { margin: 0; padding:0; list-style: none; }
.rotating_paszone > .pasli { visibility:hidden; }
.<?php echo $wpproads_adzone_class; ?> .jshowoff .wppasrotate .pasli { visibility: inherit; }