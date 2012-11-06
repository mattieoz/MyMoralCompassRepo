<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mymoralcompass');

/** MySQL database username */
define('DB_USER', 'mattieoz');

/** MySQL database password */
define('DB_PASSWORD', 'nomahh');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'HDoa(0=tl8m6*#G-dx,Q^Zt3`x/P8%Ah&58bcM{CZ5v3e)yFh-?X/lF*&+0AE3|=');
define('SECURE_AUTH_KEY',  'Q(&M=>5J0JgmB6=%fVL|-0-kL`-nr+f |#o<lA3r17.O1|$tR,<c^td-sW5]7:5V');
define('LOGGED_IN_KEY',    '6@:?a.Z+#iqA*Qswy@|%:*y@hf/-Gi{Oku`PD38aZBvoXza0,2.Mf~=vQC+s78=W');
define('NONCE_KEY',        'na+A7*/UE$0K2H3Iy [T|R!h#-*YliU<FvjvA-JP.iBFi #W8mJvcxf|B,CL6F;8');
define('AUTH_SALT',        '(2.4DVi@H5|A0<IErJpe_1y:w6NYcEMrXS)6_TX#m4.Q({EnG5Zp4(T(y<0)G`~k');
define('SECURE_AUTH_SALT', '0V$}Qexw^L|?y]aR9Z]QOmab;N3QfK?^qX2C;kkc3j5Z eET~bj;v(TtLKM/2oc|');
define('LOGGED_IN_SALT',   'xqov[?jjVRAk@|P>Sa6Nno|P_?sj8M4t*Og74!:k.A?,in;))T; ;*X)/;aj*A]4');
define('NONCE_SALT',       ']ajx6P-~3^-BG/[DwR|0s})(>ef&W&}vZy-zZdF 6-aRz^$-cM)_K3|!r/!|8Xe9');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mc_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
	
if ( !defined('ABSURL'))
	define('ABSURL', 'http://localhost:8380/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
