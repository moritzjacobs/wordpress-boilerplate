<?php

define('DB_HOST', 		'localhost');
define('DB_NAME', 		'db_name');
define('DB_USER', 		'db_user');
define('DB_PASSWORD', 	'db_password');

/* MySQL database table prefix */
$table_prefix  = '{{TABLE_PREFIX}}';

/* Authentication Unique Keys and Salts. */
/* https://api.wordpress.org/secret-key/1.1/salt/ */
// {{SECURITY_KEYS}}

define('WP_DEBUG', false);
define('WP_LOCAL_DEV', false);