<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
switch ($_SERVER['SERVER_NAME']) {

  case "local.aeg.com":
    define( 'DB_NAME',     'aeg' );
    define( 'WP_SITEURL',  'http://local.aeg.com' );
    define( 'WP_HOME', 'http://local.aeg.com' );
    define( 'DB_USER',     'root' );
    define( 'DB_PASSWORD', 'root' );
    define( 'DB_HOST',     'localhost' );

  case "aeg.nowwhat.hk":
    define( 'DB_NAME',     'nowwhat_aeg' );
    define( 'WP_SITEURL',  'http://aeg.nowwhat.hk' );
    define( 'WP_HOME', 'http://aeg.nowwhat.hk' );
    define( 'DB_USER',     'nowwhat' );
    define( 'DB_PASSWORD', '20273214' );
    define( 'DB_HOST',     'localhost' );

  case "www.asianeus.org":
  case "asianeus.org":
    define( 'DB_NAME',     'asianeus_wp' );
    define( 'WP_SITEURL',  'http://www.asianeus.org' );
    define( 'WP_HOME', 'http://www.asianeus.org' );
    define( 'DB_USER',     'asianeus' );
    define( 'DB_PASSWORD', 'aegWIZ#02-06' );
    define( 'DB_HOST',     'localhost' );
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '1fy2(5#$i0{{-Zp/.}x7N9f+3n3HZfXH%*>S2^t|Pu+V/i-]A Zz3qrPa9<;Vr!6');
define('SECURE_AUTH_KEY',  '|}}U/a(B7z|!-@|Dw_NQLJsLe;~-$wo?,8jnhm<:]):$__I)tb^c++<x3d|nUv>v');
define('LOGGED_IN_KEY',    'q}aHsa%tpa#_ga,TN|T1QO_bnNCR:9YyIl*[/PDw:0e]-bN:CmisYWERzcmXN@nx');
define('NONCE_KEY',        '6NKt:YE0z1IE6_/]L6LL]tuX|B-I.@0_gSI6D{(%V`f$gXk1FhyVzF~1!HK`6_$g');
define('AUTH_SALT',        'ccx[B{_0f|rO@w%u 0D#xekwb|NH^gf<5Lo,;^5_,s~HaL;Q7v VO2;n3N/f%5|:');
define('SECURE_AUTH_SALT', '@@tR@u(k355Z*++Ur^:||=*1|V;UImKfNh|n+ac%-l;-->t|e(|C7#}*xC^CPS1_');
define('LOGGED_IN_SALT',   ':S$_E)5W;_Q-z1q4|L|]5|#~WG`JyHRsjd|Fz9pJs6ASYJ~CRCm-+h/FX0aVJM|R');
define('NONCE_SALT',       'TUChBa@-{0+5J){Z9@=vVkT]G]?$?J^Jfeo~oEfL;{BQj^xMxq5h~{iy`d3yPi}@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'aeg_';

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
define('WP_DEBUG', false);

define('WP_ENV', 'development');

//define('WP_ENV', 'production');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
