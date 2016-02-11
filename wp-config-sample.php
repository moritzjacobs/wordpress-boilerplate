<?php
function host_contains($str) {
	return stristr($_SERVER['SERVER_NAME'], $str);
}

// ========================================================
// Figure out environment and load wp-config-* accordingly.
// add additional runtimes here; default is "live"
// ========================================================

switch(true) {
	case host_contains("local"):
		$runtime_env = "local";
		break;
	case host_contains("staging"):
	case host_contains("preview"):
		$runtime_env = "staging";
		break;
	default:
		$runtime_env = "live";
}

// {{RUNTIME_SWITCH}}

define('WP_SERVER_ENVIRONMENT', $runtime_env);

define('WP_CORE_DIRNAME', "wordpress-core-dependency");
define('WP_CONTENT_DIRNAME', "wp-content");

// =================================================
// Custom Content Directory (change if renamed)
// =================================================
define('WP_CONTENT_URL', 'http://'.$_SERVER['SERVER_NAME'].'/'.WP_CONTENT_DIRNAME);
define('WP_CONTENT_DIR', __DIR__.'/'.WP_CONTENT_DIRNAME);
define('WP_ROOT', __DIR__."/".WP_CORE_DIRNAME);

// ================================================
// You almost certainly do not want to change these
// ================================================
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// These are a fallback, re-define them in your environment wp-config.php's!
// ==============================================================

// {{SECURITY_KEYS}}

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================

// {{TABLE_PREFIX}}

// ================================
// Language
// Leave blank for American English
// ================================
define('WPLANG', 'de_DE');

// ===========
// Hide errors
// ===========
ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', true);

// ===================================================
// Load database info and local development parameters
// ===================================================
if (file_exists(dirname(__FILE__) . "/wp-config-$runtime_env.php")) {
	define('WP_LOCAL_DEV', true);
	include(dirname(__FILE__) . "/wp-config-$runtime_env.php");
}

// ======================================
// Load a Memcached config if we have one
// ======================================
if (file_exists(dirname(__FILE__) . '/memcached.php'))
	$memcached_servers = include(dirname(__FILE__) . '/memcached.php');

// ===================
// Bootstrap WordPress
// ===================
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/'.WP_CORE_DIRNAME.'/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
