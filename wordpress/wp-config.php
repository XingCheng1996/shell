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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'xingcheng');

/** MySQL database password */
define('DB_PASSWORD', 'xingcheng');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define("FS_METHOD","direct");

define("FS_CHMOD_DIR", 0777);

define("FS_CHMOD_FILE", 0777);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'A@y837b{u)lV%,]f L N5rL3Bt}b6h}Hidx5wcYYoHX^[bC^.V/6LsGv7QHE=e=Q');
define('SECURE_AUTH_KEY',  'K>TVHZ~k:/ckae=`rxQe%po+b&(B}e^K]b]3fj%<T<`cd+q5HS!{c1~eNiG+(uI5');
define('LOGGED_IN_KEY',    'eK]SG@eW<06qM$TW(+.jgw;9kI6dBIqPRu}WZT.^!}pCRMAc/+NEsw4sK=XoQg,<');
define('NONCE_KEY',        '=1Uf*d:Zj)&?79~X-)l?JiD}S!B8>@Ve52)Ynw.lp2<P=zA^K}EMh*Z?XU 93)6f');
define('AUTH_SALT',        '9IjYrw}DA^e(+p:`~N~v_VO.yE3rX<b}#Oi<=D:VxzkHJ*aZ.gPf>i l@oO/;SH.');
define('SECURE_AUTH_SALT', ';QA_tp]9ECWR8mR@wDVt2i anEr7!u&v%cVrkP)O=iunFpqMh,vxf1J(s]o0a!<)');
define('LOGGED_IN_SALT',   'M40aUT4PL M>^Wj`2P[&c*>!b`^und9[>?OmLYW@~Rr5A/FsEeMSt0:=_%|fm%gB');
define('NONCE_SALT',       '/;8K]>y/vvT&f1r!R7H>Yk=qD)NRbtpQY}PwQ1#REU?6|6$QP|pqKa2XQ:.% OK#');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_1';

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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
