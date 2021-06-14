<?php
/* Check if dev or production env */
$dev = $_ENV['ENVIRONMENT'] === 'development' ? true : false;

/* WordPress general settings */
define('WP_HOME',  'https://' . env('RAILWAY_STATIC_URL'));
define('WP_SITEURL', 'https://' . env('RAILWAY_STATIC_URL'));

$table_prefix = $_ENV['WORDPRESS_TABLE_PREFIX'];

define('WP_DEBUG', $dev);
define('SCRIPT_DEBUG', $dev);
define('WP_DEBUG_LOG', true);

/* Fix SSL redirect issue */
define('FORCE_SSL_ADMIN', true);
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
  $_SERVER['HTTPS'] = 'on';
}

/* Set variables */
foreach ($_ENV as $k => $v) {
  define(str_replace('WORDPRESS_', '', $k), $v);
}

$prodvars = array(
  'COMPRESS_CSS', 'COMPRESS_SCRIPTS', 'ENFORCE_GZIP',
  'WP_AUTO_UPDATE_CORE', 'DISALLOW_FILE_MODS', 'DISALLOW_FILE_EDIT'
);
foreach ($prodvars as $prodvar) {
  define($prodvar, !$dev);
}

/* Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/* Set absolute path to WordPress and load settings */
if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-settings.php';
