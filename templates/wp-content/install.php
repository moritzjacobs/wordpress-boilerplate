<?php

/**
 * Plug installer function that generates sample pages and set our default options instead
 */

function wp_install_defaults() {
	global $WPNP_CORE_NAME, $WPNP_UPLOAD_NAME;

	// home = https://env.example.com
	update_option('home', str_replace("/".$WPNP_CORE_NAME, "/", site_url()));

	// upload_path = /var/www/$WPNP_UPLOAD_NAME
	update_option('upload_path', dirname(ABSPATH). "/" . $WPNP_UPLOAD_NAME);

	// upload_url_path = https://env.example.com/$WPNP_UPLOAD_NAME
	update_option('upload_url_path', home_url() . "/" . $WPNP_UPLOAD_NAME);

	return null;
}