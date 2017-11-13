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
define('AUTH_KEY',         '(J(wU|A@TdMwZ/sp]e``E{/ dMp1&:/]VF][VOCmT`#?YgS/XBJQ&JOl9Um>wpnV');
define('SECURE_AUTH_KEY',  'n |+X ^XEO|PfDV-y~>?j[k*};MIUKIN|,E7+5b[;|E4^Jaj[yW]Jju`M&@~=Dc0');
define('LOGGED_IN_KEY',    '!||=MN/`ej&rR~mYm;/`B7^BrQxQ$pQ67d^z /kI&g]jJ2QA5}`,?ftoOGzGcb!y');
define('NONCE_KEY',        'b=>$FOe:;ZK-KoR>1N&h>vrCz@B-ueY=Xo|u.&!GqWD^(2vy|QW oO4!<lZDb.3U');
define('AUTH_SALT',        'x:Adp%v09z}e}/k}1VV&El`<89805SB.Dlzeyat_;|!sO<-{}cl*d.F94h3=G%/,');
define('SECURE_AUTH_SALT', 'P _]Zg+u-KfU7|.wjr&%ANyaR]`zCTc=vMzM@Z[*Yo&tyzM+Q,p}5R<M`t1Kj8L%');
define('LOGGED_IN_SALT',   'ef$R#PWd$-VL~{i{mw5nJ8pxesE3F4u+OT)9&e;Oi5C90-h5^VJof0>1Y4gJvK%=');
define('NONCE_SALT',       ',CS(lJw+Zs.O:tEX/)FTAY]f,&_nr,g&WMFnmBMdt_-~2Fu<~o|PG#B5|ZB5Rg-<');

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
