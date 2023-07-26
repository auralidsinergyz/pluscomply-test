<?php

/** Enable W3 Total Cache */

define('WP_CACHE', true); // Added by W3 Total Cache



define('WP_MEMORY_LIMIT', '1016M');
define('WP_AUTO_UPDATE_CORE', false);// This setting was defined by WordPress Toolkit to prevent WordPress auto-updates. Do not change it to avoid conflicts with the WordPress Toolkit auto-updates feature.
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/#j%5R;_4Qk=`kzjiyMD-4.Afv*w]y11;Zx_.OJSLj]qUrg|}RJ.axo{{9h/%Enr');
define('SECURE_AUTH_KEY',  '}4d&:V,2N#:6Q+~yA_EGj&tS}YEBf8ds|cAy`Go!rKM{rdk*-o#].;$J/Krq|L*S');
define('LOGGED_IN_KEY',    '-Ijcn4HS4ij2rh$ %|x:>al(>6|a,Ke_aw6Wk0,)Hy++<4eo%Y6]V+0ny+:#`+Hf');
define('NONCE_KEY',        'E+$-ne|N%?:Nyy8[7!r7w]C8pLyjlapEHwkQmp*Th@/!p]2e^uF^$(cl`05xK]IL');
define('AUTH_SALT',        'lD.g.n.71m!LQ+{U!gknksk$cY:y`hc}afL(,Y 4B,eQrldUk+CWb)y:K,<R5!|1');
define('SECURE_AUTH_SALT', ']p7xAJF4L4Gc$30M*C4^1_Hv@@ygzrn&E(br#a$ifXf@)>U#OKPmy92FJ@)HaNTE');
define('LOGGED_IN_SALT',   'AUIS07{~.^_s(R Aq)#]Hg[HZ[2<Q-YTJJ_re|7m+-W[}*cTHtqk!S^M+g*>T:@+');
define('NONCE_SALT',       '#HYl_m/ 65t>goV%gTAj.)fZI9-UmozR.2B*{*+[0fj I@it$r!75iW064O}bYv*');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '1h36eo_';


define('WP_ALLOW_MULTISITE', true);

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('FORCE_SSL_LOGIN', true);
define('FORCE_SSL_ADMIN', true);

//define('FS_METHOD', 'direct');
