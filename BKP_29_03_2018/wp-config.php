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

/**
 * Load configuration
 */
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv('DB_NAME'));

/** MySQL database username */
define('DB_USER', getenv('DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('DB_PASSWORD'));

/** MySQL hostname */
define('DB_HOST', getenv('DB_HOST'));

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
define('AUTH_KEY',         'n5zwymtbrdlx8xqskx7qg0rgxzgrepg5ox6dbdgqrd7r9dxl6c1fls1msjokcsax');
define('SECURE_AUTH_KEY',  'utye2x4le8iybni6ymjhfrboegtd1damebfwtss12pr5ra849bqe2pjyxnacsw9h');
define('LOGGED_IN_KEY',    'qvdaid8ltcczf2i00ohhml5xsbvsh4z3kzde0gz9e2h9vqj8wnijnl6zlgqtce8f');
define('NONCE_KEY',        'dmhltfrnkmeil0m0eev2sc2lurmtjj6lzfvkg0juquze981fjt12oqhnnflgprnh');
define('AUTH_SALT',        'em4vng0tcaq12qkccrsntmltzv1kx1va37nnpabvymbesaxamu6azuvff8ojagsp');
define('SECURE_AUTH_SALT', 'cxxqhli9h9sdwm5talc69t2dgnoaxjy9pregmrfyapfs7n0khuyavbewovkauueu');
define('LOGGED_IN_SALT',   'rcl8adjq15vhpdxaoqpn15uokwxxp5gwh1ewidnu0jjpnugfpimxnsoziufgdknr');
define('NONCE_SALT',       'c68uc729kxhfdrf9gfqu8awujhagm97ajkq7rjxigcj53svlf4uh7i1dpkleya6z');

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
define('WP_DEBUG', getenv('WP_DEBUG'));

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
