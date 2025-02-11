<?php

if ( file_exists( dirname( __FILE__ ) . '/gd-config.php' ) ) {
	require_once( dirname( __FILE__ ) . '/gd-config.php' );
	define( 'FS_METHOD', 'direct' );
	define( 'FS_CHMOD_DIR', ( 0705 & ~ umask() ) );
	define( 'FS_CHMOD_FILE', ( 0604 & ~ umask() ) );
}
 //Added by WP-Cache Manager
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);


define('WP_MEMORY_LIMIT', '512M');
define( 'WP_POST_REVISIONS', false );
define( 'WP_POST_REVISIONS', 0 );
define('ICL_REMOTE_WPML_CONFIG_DISABLED', false);




/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') $_SERVER['HTTPS']='on';

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv('DB_DATABASE'));

/** MySQL database username */
define('DB_USER', getenv('DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('DB_PASSWORD'));

/** MySQL hostname */
define('DB_HOST', "localhost");

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'r)=$;A>X$%B/Bc.umc}  q,M1F2W~LZ#l _Qf>*3GP>~jfV[>x`55pw&4=XjRzG_');
define('SECURE_AUTH_KEY',  ']>mmC~(A}59k@qZQL;n)N,uFdcNq$[JFP%r& 3dhC(pwOp`]~_RyD9C4=pHUT]Nu');
define('LOGGED_IN_KEY',    'yW6ndBu6]_<-`Wt*-|e#`A%(I:cplmiIn3nE^tMJbmpz6A?FWF2G$V;_ 2|M=,mE');
define('NONCE_KEY',        '6dCIOQ_r<GD&dg3[-#{^^CyxW@c5mC5%hN4L^--xv4@_7&U#+J?N otzmYml/TYL');
define('AUTH_SALT',        'm*(+k@}=xSOy}zu$_v1_aa$,X&r9)FkzBrG.5B~ER!z~qtU(w9i.a[el(i90ojzR');
define('SECURE_AUTH_SALT', '^v; 4z{@XSb2g~ah4s-WDuu+AnQSt-g0^PV+%KAQyjh008=%dxC=0xp.}iQVtRmz');
define('LOGGED_IN_SALT',   '8]<[(%ZzDwXW87vJ@_vh@d@DiJP,7FEUCi}/$IQ.8TuK@[`{Klbyb5x[_.Pe^ZY*');
define('NONCE_SALT',       '#NVADl7wrm!`>LCu<O4:e+~}GJ_XQfGY)SrjK89p!B!QFpd$0x^WcZo4=>*XemI%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = "wp_xpksy4skky_";
// define( 'FORCE_SSL_LOGIN', 1 );
// define( 'FORCE_SSL_ADMIN', 1 );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
