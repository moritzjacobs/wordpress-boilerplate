<?php
/* Custom Content Directory (change if renamed) */
$wp_core_dir   = "{{WP_CORE_DIR}}"; /* WP_CORE_DIR */
$content_dir   = "{{CONTENT_DIR}}"; /* CONTENT_DIR */
$use_media_dir = {{USE_UPLOAD_DIR}}; // if you want to use a different media dir /* {{USE_MEDIA_DIR}} */
$media_dir     = "{{UPLOAD_DIR}}"; /* MEDIA_DIR */



/* Figure out, set environment and load wp-config-* accordingly. */
function host_contains($str) {
	return stristr($_SERVER['SERVER_NAME'], $str);
}
switch (true) {
	case host_contains("dev"):
	case host_contains("local"):
	case host_contains("localhost"):
		$runtime_env = "local";
		define( 'WP_LOCAL_DEV', true );
		break; // {{RUNTIME_SWITCH}} 
	default:
		$runtime_env = "live";
		break;
}
define('WP_SERVER_ENVIRONMENT', $runtime_env);
/* MySQL Settings are in the environment specific configs */
include( dirname( __FILE__ ) . '/wp-config-'.$runtime_env.'.php' );


/* MySQL database settings - you most certainly don't want to change these */
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', 'utf8_general_ci' );
/* MySQL database user tables */
//define( 'CUSTOM_USER_TABLE',      $table_prefix . 'accounts' );
//define( 'CUSTOM_USER_META_TABLE', $table_prefix . 'accountsmeta' );



/* WordPress debug mode */
// For debuggin in live environments add the url param: ?wp_debug=true
if ( WP_DEBUG || defined('WP_LOCAL_DEV') || (isset($_GET['wp_debug']) && $_GET['wp_debug'] === 'true')) {
	@ini_set( 'log_errors', 	'On' );
	@ini_set( 'display_errors', 'On' );
	define( 'WP_DEBUG_LOG', 	true );
	define( 'WP_DEBUG_DISPLAY', true );
	define( 'SCRIPT_DEBUG',     true );
	define( 'SAVEQUERIES',      false );
} else {
	@ini_set( 'log_errors', 	'Off' );
	@ini_set( 'display_errors', 'Off' );
	define( 'WP_DEBUG_LOG', 	false );
	define( 'WP_DEBUG_DISPLAY', false );
	define( 'SCRIPT_DEBUG',     false );
	define( 'SAVEQUERIES',      false );
}


/* Using SSL? */
$server_protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https://' : 'http://';
/* custom directory and url structure */
define( 'WP_CONTENT_URL', $server_protocol . $_SERVER['HTTP_HOST'] . '/' . $content_dir );
define( 'WP_CONTENT_DIR', __DIR__ . '/' . $content_dir );
define( 'WP_ROOT',        __DIR__ . '/' . $wp_core_dir );


/* AutoSave Interval. */
define( 'AUTOSAVE_INTERVAL', '180' );
/* Specify maximum number of Revisions. */
define( 'WP_POST_REVISIONS', '5' );
/* Media Trash. */
define( 'MEDIA_TRASH', true );
/* Trash Days. */
define( 'EMPTY_TRASH_DAYS', '15' );


/* Multisite. */
//define( 'WP_ALLOW_MULTISITE', true );


/* PHP Memory */
//define( 'WP_MEMORY_LIMIT', '128M' );
//define( 'WP_MAX_MEMORY_LIMIT', '256M' );


/* WordPress Cache */
//define( 'WP_CACHE', true );


/* SSL */
//define( 'FORCE_SSL_LOGIN', true );
//define( 'FORCE_SSL_ADMIN', true );


/* Compression */
//define( 'COMPRESS_CSS',        true );
//define( 'COMPRESS_SCRIPTS',    true );
//define( 'ENFORCE_GZIP',        true );


/* Set default theme */
define( 'WP_DEFAULT_THEME', 'blank-theme' );


/* Updates */
define( 'AUTOMATIC_UPDATER_DISABLED', FALSE ); 		// enable automatic updater
define( 'CORE_UPGRADE_SKIP_NEW_BUNDLED', TRUE ); 	// skipt wp-content when upgrading to a new wp version
define( 'WP_AUTO_UPDATE_CORE', true ); 				// core updates for development, minor, major
//define( 'DISALLOW_FILE_MODS', true ); 				// disable alle file modifincations for core, plugin and theme
define( 'DISALLOW_FILE_EDIT', true ); 				// disable plugin/theme editor


/* DB repair */
//define('WP_ALLOW_REPAIR', true);					// ../wp-admin/maint/repair.php


/* Load a Memcached config if we have one */
if (file_exists(dirname(__FILE__) . '/memcached.php'))
	$memcached_servers = include(dirname(__FILE__) . '/memcached.php');


/* Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/'.$wp_core_dir.'/');


/* Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');