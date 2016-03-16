<?php

define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', '{{RT_NAME}}_db_name' );
define( 'DB_USER', '{{RT_NAME}}_db_user' );
define( 'DB_PASSWORD', '{{RT_NAME}}_db_password' );

// {{SECURITY_KEYS}}

// ===========
// Hide errors
// ===========
ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', false);
define( 'SAVEQUERIES', false );
define( 'WP_DEBUG', false );