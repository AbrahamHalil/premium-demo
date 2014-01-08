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


$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define("DB_NAME", trim($url["path"], "/"));
// define("DB_NAME", "heroku_4eed1f5663e035f");

/** MySQL database username */
define("DB_USER", trim($url["user"]));
// define("DB_USER", "b7cfc877a785ae");

/** MySQL database password */
define("DB_PASSWORD", trim($url["pass"]));
// define("DB_PASSWORD", "db3717c2");

/** MySQL hostname */
define("DB_HOST", trim($url["host"]));
// define("DB_HOST", "us-cdbr-east-03.cleardb.com");

/** MySQL database port  */
// define("DB_PORT", trim($url["port"]));

/** Database Charset to use in creating database tables. */
define("DB_CHARSET", "utf8");

/** Allows both foobar.com and foobar.herokuapp.com to load media assets correctly. */
define("WP_SITEURL", "http://" . $_SERVER["HTTP_HOST"]);

/** WP_HOME is your Blog Address (URL). */
// define('WP_HOME', "http://" . $_SERVER["HTTP_HOST"]);

define("FORCE_SSL_LOGIN", getenv("FORCE_SSL_LOGIN") == "true");
define("FORCE_SSL_ADMIN", getenv("FORCE_SSL_ADMIN") == "true");
if ($_SERVER["HTTP_X_FORWARDED_PROTO"] == "https")
  $_SERVER["HTTPS"] = "on";

/** The Database Collate type. Don't change this if in doubt. */
define("DB_COLLATE", "");

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4B&_]8oxyX#f-R<r(C8=cV0f=Io-mTYh6w<7/+qi+tT3ys&,02tRXLhk[EL4m<z;');
define('SECURE_AUTH_KEY',  '?bf+0.15eA2B<{d]- egd|.5wsp:!1f_~&^mPiC|,kR6bqtF<7@V3*dzJfyiK{q&');
define('LOGGED_IN_KEY',    'G}40laJF%t|}$T/rhn`~-(++D~D;oYbNJcJ@8p`Z4EfYB@na!M*B/^^t$+-LBX/3');
define('NONCE_KEY',        ': 8)`)@#,rqD-,=s1:cm>M gl#Mx -dO>h{~{(rl$ci1|njQZUD:Y|Vn`2t7~K=E');
define('AUTH_SALT',        '2 u>+JC9EPGQA1o+U025~`+j!l|k|&-j|5xOvbMc%ZgNu,VNSV!qZx?O^)XwIH|k');
define('SECURE_AUTH_SALT', 'J%f02&|C=Q-U8|tn.alt][&t9fTUY|=DWj(WP5+x80wi|)W-y~8(BeLs/z+{KPp{');
define('LOGGED_IN_SALT',   '+-lhEh2%d8!!x:CqYM^-ot1f+x6tfq(+OkS[m]mOy[TW1.}Pp0sA3Q6|,yP/3Gj}');
define('NONCE_SALT',       'Si; ##G`bto3>x<%&MDYI|_@=hb+bA]Aj-+OX4S_*F?#oXw%x_Is=WwO?N]5y+-L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = "wp_";

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to "de_DE" to enable German
 * language support.
 */
define("WPLANG", "");

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define("WP_DEBUG", false);

/**
 * Enable the WordPress Object Cache
 */
define("WP_CACHE", getenv("WP_CACHE") == "true");

/**
 * Disable the built-in cron job
 */
define("DISABLE_WP_CRON", getenv("DISABLE_WP_CRON") == "true");

/* That"s all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined("ABSPATH") )
  define("ABSPATH", dirname(__FILE__) . "/");

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . "wp-settings.php");
