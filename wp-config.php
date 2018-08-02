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
define('DB_NAME', 'db_arena_wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'HGF09)2Q^u7pKyB)Yl`!<n*WB};Vp|JKXlY`r/r[%/ uzy2(--Vc|{id$;E_IhV6');
define('SECURE_AUTH_KEY',  '81d19`l+dNe6!m.?r8$(J![Jm%9mOR}9kMb]2_BJj6Mu^P!*IY0+P?BxJ>ZpUx`}');
define('LOGGED_IN_KEY',    '`R$[jknF)CT5Q?1={bN7B=ogpM(gsrwO]t{F#:V3IW.{3A;u0.g`!r$S,`cd8xT)');
define('NONCE_KEY',        'K(^of Fmer6B_RG1QS(wUklD3cU1H~&^laz[lK?YH>18MA%ZDk`7fT5Pr-Y8I}qI');
define('AUTH_SALT',        'JkCh|zg`*4T6V9W_{~*+2AfYc.Rr*K]9/38QKUTJ]}vAsW0{>yg0{wP% Lm0R~/5');
define('SECURE_AUTH_SALT', '+RNukh}Rj817tqGi`Y4-^, FM=}}}jz0@|^kLF8*^T@2vB.]^;Xys2_3&8b!b%XT');
define('LOGGED_IN_SALT',   'ma@2=jDh0>?WVh2y]qU2H0Hj7)H+w7MnE+zPNd51%6`2vqtOiRJ*kVmOghp!9E!i');
define('NONCE_SALT',       'oG)jl]o{!^33LA&HF*v./$A W@TG|c.8lWm51T!^|jwSOr0U9@l=pq:6v_:m598f');

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
set_time_limit (700);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


// ** FTP CONFIGURACIÓN PARA AUTO-FTP ** //
define( 'FS_METHOD', 'direct' );
define( 'FS_CHMOD_DIR', 0777 );
define( 'FS_CHMOD_FILE', 0777 );
