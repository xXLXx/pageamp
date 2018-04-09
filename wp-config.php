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
define('DB_NAME', 'pageam5_dhmar2018_209');

/** MySQL database username */
define('DB_USER', 'pageam5_mar209');

/** MySQL database password */
define('DB_PASSWORD', 'J2%R_3~sXJd+');

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
define('AUTH_KEY',         'Z*2)W1>rys2z2T_8U-uRM,OtPN4e7l~zI?/GW,5A)y2;}?TW**oM){lkbmvW<k-^');
define('SECURE_AUTH_KEY',  '>zL*Wc{96:ZA.>joqjpPO-Vz9q2=*Vhf )E9q&5XYUEnR6N9-XH2`0t7]$oOv)=.');
define('LOGGED_IN_KEY',    '<h>-^OT/_ /IYY6hwOY+CL]ny<ixCy+#Q.6%nN5FpzsOHpEb:7:E)(%z,aC8Aw{A');
define('NONCE_KEY',        'WX(a1pD%5Ot;*t9+o>a7vDcIvoHKJ(i!jHH^PuQNe.>, ZWi]l;#~X8k:>F,Y;|]');
define('AUTH_SALT',        '3.PsqoFfMrM>C?*>uoIw3t/?Gn61$g8e{=?Nz]IMzTnN)k&?Rc_%9,2rYF1bvk<;');
define('SECURE_AUTH_SALT', 'f]3Je0y*3(HJl96UR_f5XJKL-~tax-^mc&YkH]1>qM;k/Y@T5naR>5nJ7sr%X_$]');
define('LOGGED_IN_SALT',   '>P;~,{Yb})L#,B$}0V~C=AePJp*53mJ:uf(?u9aRi~HzJ>oEBQiC*iP2:n5l[w0w');
define('NONCE_SALT',       ')),-0BI(r@5]uM:X24Gw{<`m#yr$zpia,m]$?=/1_o05_]FwOA@|uJ+,:?4%oJGX');

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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
