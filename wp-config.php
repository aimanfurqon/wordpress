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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sjk_wp_db' );

/** MySQL database username */
define( 'DB_USER', 'tubes_wp' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bz3heNzy~w,b)f>}#e~We@NXJxgt#:`#euiHg@4sX1%qAf+dUb`gTDC: #tU3}.a' );
define( 'SECURE_AUTH_KEY',  'eA`W.5gws`@!V3a?e -H/UWq5tn23@@}MP)jf=ia$}iqjv4*? 5Mq6>8H[aD>s^a' );
define( 'LOGGED_IN_KEY',    '/K|csk5|/D4g/>l47lQL/}j2L2!.g}3,s0.MeaJJ?[Zi[0C-0wp3;vf&^miydR*F' );
define( 'NONCE_KEY',        'o~u;d[6OV|cLDIpM3X,+7JK509STbJT2PxNXUUhQ@*H{saZ1m@Plzy?&e4j, @%4' );
define( 'AUTH_SALT',        's^42_XzDP[L-(pM4n(x_-Z$HtOSitkqFd(PRssClV_w2&E5_qvSFq{{U-F^kzYXT' );
define( 'SECURE_AUTH_SALT', 'js<$#VhZ~FmG0!$ vf*w-p3;kf9UiHF(IA/*RpG^YcqHt<EQE}^;9$F]GC8l*}O7' );
define( 'LOGGED_IN_SALT',   'j:zbD!h[g1#Cg$esw*&/2${,BPk@kxx_)T;=2n!)gny6;FsXNu`ze+RK/ixK9)cB' );
define( 'NONCE_SALT',       'WUKvBGJ&f)+VO);QJ{nGnGYkto*v>DBck(O%2UuRy/s3iU&ql)F$2e#1E(8a#u]+' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
