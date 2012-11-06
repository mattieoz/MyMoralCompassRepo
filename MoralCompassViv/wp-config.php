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
define('AUTH_KEY',         'lrQ1Nk Rp/+X??_&p1PK 32moCHOM&`+s Vm!jNT&x-@EZJnJ/2#Th:Ns0-m~RNc');
define('SECURE_AUTH_KEY',  'EqX*#9nVb^oy^aQ{aJ04.m@3XG}y|DMT+D:3g/GBz-6#}5D.Jo-{.Vq%r+@*W26Y');
define('LOGGED_IN_KEY',    '$Hw=9wFrK}6|@H!CC+ZEu< 8^0I6Z{*Ou)^O;S!>^,.hG@]<6SX6#$xUuDSE]g3d');
define('NONCE_KEY',        'zf.zPR>u)+78*_q1Ddb?.1O>&i/#ns;c}:QuU5U,WiPdS.q]V08`*;Y6nq[:IZHD');
define('AUTH_SALT',        'If@jp#PK,EZ.l:D4`_Bck5@:AtWO!vyK/(j.uNZhi7*xPp(T<AtRLeRdXLB6Ew``');
define('SECURE_AUTH_SALT', 'tK>#jcX)rvxc.%MX5,5{^ho,%%{ie?W6Q,a7}z{*QjxmVH}nK!{$q1Aq<x]%OF%F');
define('LOGGED_IN_SALT',   '7;~s;kJI(#h[2/sI`IybQC(BGdAT03 v%!:vo4wEX&(/No[RO{z84[=Q*6Oe8YB1');
define('NONCE_SALT',       'mUKpM`j5}-ua`?o%>dsic4WLR@uIpfvf373AI7HXx]Nxo@}Yt9g,#@`U[WF1O@`F');

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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
