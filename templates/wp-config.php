<?php

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * Determine environment
 */

// First try a file 'wp-config-env.php'
if (file_exists(__DIR__ . '/wp-config-environment.php')) {
	require_once __DIR__ . '/wp-config-environment.php';
}

if (!defined('WP_SERVER_ENVIRONMENT') && isset($_SERVER['WP_SERVER_ENVIRONMENT'])) {
	// If not set, try an environment variable WP_SERVER_ENVIRONMENT
	define('WP_SERVER_ENVIRONMENT', $_SERVER['WP_SERVER_ENVIRONMENT']);
} else {
	// Use 'local' as fallback
	define('WP_SERVER_ENVIRONMENT', 'local');
}

// Include environment specific configs
include dirname(__FILE__) . '/wp-config-' . WP_SERVER_ENVIRONMENT . '.php';

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * Custom directory structure
 */

$WPNP_CORE_NAME = '{{WP_CORE_DIR}}'; // core
$WPNP_CONTENT_NAME = '{{CONTENT_DIR}}'; // site
$WPNP_UPLOAD_NAME = '{{UPLOAD_DIR}}';

define('WP_CONTENT_URL', "https://" . $_SERVER['HTTP_HOST'] . '/' . $WPNP_CONTENT_NAME);
define('WP_CONTENT_DIR', __DIR__ . '/' . $WPNP_CONTENT_NAME);
define('WP_ROOT', __DIR__ . '/' . $WPNP_CORE_NAME);

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * Shared configuration, most propbably unchanged.
 * If this changes for one environment, move it to ALL the wp-config-*.php file
 */

// Database
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');

// MySQL database user tables
// define('CUSTOM_USER_TABLE', $table_prefix . 'accounts');
// define('CUSTOM_USER_META_TABLE', $table_prefix . 'accountsmeta');

// Server settings
// define('WP_MEMORY_LIMIT', '128M');
// define('WP_MAX_MEMORY_LIMIT', '256M');

// Misc. Wordpress behaviour
define('WP_DEFAULT_THEME', 'blank-theme');
define('AUTOMATIC_UPDATER_DISABLED', true);
define('WP_AUTO_UPDATE_CORE', 'minor');

// skip wp-content when upgrading to a new wp version
define('CORE_UPGRADE_SKIP_NEW_BUNDLED', true);

// disable alle file modifincations for core, plugin and theme
// define('DISALLOW_FILE_MODS', false);
//
// DB repair: ../wp-admin/maint/repair.php
// define('WP_ALLOW_REPAIR', true);

// define('AUTOSAVE_INTERVAL', '180');
// define('WP_POST_REVISIONS', '5');
// define('MEDIA_TRASH', true);
// define('EMPTY_TRASH_DAYS', '15');
// define('WP_ALLOW_MULTISITE', false);
// define('WP_CACHE', false);
// define('COMPRESS_CSS', true);
// define('COMPRESS_SCRIPTS', true);
// define('ENFORCE_GZIP', true);

// SSL
define('FORCE_SSL_LOGIN', true);
define('FORCE_SSL_ADMIN', true);

// disable plugin/theme editor
define('DISALLOW_FILE_EDIT', true);

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * Initial PHP settings and Wordpress bootstrap
 */

/* WordPress debug mode */
if (WP_DEBUG || WP_LOCAL_DEV) {
	@ini_set('log_errors', 'On');
	@ini_set('display_errors', 'On');
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', true);
	define('SCRIPT_DEBUG', true);
	define('SAVEQUERIES', false);
} else {
	@ini_set('log_errors', 'Off');
	@ini_set('display_errors', 'Off');
	define('WP_DEBUG_LOG', false);
	define('WP_DEBUG_DISPLAY', false);
	define('SCRIPT_DEBUG', false);
	define('SAVEQUERIES', false);
}

if (!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/' . $wp_core_dir . '/');
}

require_once ABSPATH . 'wp-settings.php';
