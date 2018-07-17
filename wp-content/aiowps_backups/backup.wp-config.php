<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


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
 //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/minchev/public_html/pctehnik.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'minchev_service');

/** MySQL database username */
define('DB_USER', 'minchev_user');

/** MySQL database password */
define('DB_PASSWORD', 'musikman80');

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
define('AUTH_KEY',         'fti/!q:Rzk*Y[}O=z[hT3+eVoL#N:lbax-B;Gr=T2zn{V =I8bo@v70Pr{1ZEqOX');
define('SECURE_AUTH_KEY',  ')/(|v,Td|I0Wclst<Cdd(Z`3m}9&oV7lJ8d-n<]F!H@]h-%Fh>Y>=|VJ!e_z^@/B');
define('LOGGED_IN_KEY',    'WSEzP7M)M7gCK]35+-9&$/38$IScgBr9eh#+)~I$-wgpc!>|s3kIBpBZ^Vc3S$~K');
define('NONCE_KEY',        '_<q{.Ql^oDHM/n~d@+q:4ao_@__-(At-m 7.]~hC6C@J28PM936kfO/>zBGR_+.t');
define('AUTH_SALT',        '-<7blD<S)i7H)7m_C.JDB+l+KEXsauSL]RC> _ @%Tss)HU>ziCJ-Y9)|P~4G3:j');
define('SECURE_AUTH_SALT', '}p|dg+/:k}QvA[W&amyPCVq%@i2knAL0NE5ik&6V4zk6;f+Nz%7&$^)6fS fhcxQ');
define('LOGGED_IN_SALT',   'GYC>_Hibc857V3-L]@+DmKs w@jQD<tg]_m!rgROBCU>u47cSqQ|1^gBN_%Svui?');
define('NONCE_SALT',       'dk?j[KA24w4]xJWNENeO644Cl ^9?q tuR,$1M`vxgq&LhWQ.nGm$`nOo8NQ1-59');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'qwbbk_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'bg_BG');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_ALLOW_MULTISITE', false);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define( 'WP_MEMORY_LIMIT', '96M' );

 //Added by WP-Cache Manager
