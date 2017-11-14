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
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/brodriguez/public_html/geoplantilla/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'geoplantilla');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'p3lk4x');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '`_-hMWTvQ}^UR-256=n+Z+9Ee^wb$?=|)tZ%xjMh=5 qc0$=Cd_1~EVd4!2p(; X');
define('SECURE_AUTH_KEY',  '|g$$gnIn ~K/lKM=lN-ZV+MIMp?IwEz!qf6vjX!h(}f^z5nYc!~q|tAoK=)!WJVv');
define('LOGGED_IN_KEY',    'I*`DNypSH-:Ti)gtNDcC&R)2RKd?@%5)|yQ53x3<Y&iww.U>o450#my|5Q3K+-4`');
define('NONCE_KEY',        'ahA,fasQil/6U&|9/RX87b5G;9+XK!%Sm^{)3h<knLgXWX0L=xrZ[wf)59@I|s6v');
define('AUTH_SALT',        'k3q5H+KSx(R&QJtaj.D+SgMCs .g@:Nt.UI^H^eBbu(U/+Mt=c]{7-T6|Po?3~/_');
define('SECURE_AUTH_SALT', 'm@D EwcaT:*$+(<^T[7!vwq&91S|-+&_Cg,GN=^IoP$eF7Owr`z35tvY{~%MD]q*');
define('LOGGED_IN_SALT',   'Z5Ht[F!2oked`9F$Br9XB|L;@Ik* ROojHVY.Fpj&yW-+ZJ[Sw$A+zoiX--,GrW6');
define('NONCE_SALT',       'a})53E(Cceh<#vnM!,2iaVGOS:~ygw=MyOMwUp}UV2J16rc>g#VjM3vsR+P10dkQ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
define('FS_METHOD','direct');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
