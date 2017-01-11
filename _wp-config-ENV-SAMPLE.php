<?php

define( 'DB_HOST', 		'localhost' );
define( 'DB_NAME', 		'local_db_name' );
define( 'DB_USER', 		'local_db_user' );
define( 'DB_PASSWORD', 	'local_db_password' );
/* MySQL database table prefix */
$table_prefix  = '{{TABLE_PREFIX}}';

/* Authentication Unique Keys and Salts. */
/* https://api.wordpress.org/secret-key/1.1/salt/ */
// {{SECURITY_KEYS}}

/* show error */
define( 'WP_DEBUG', true );