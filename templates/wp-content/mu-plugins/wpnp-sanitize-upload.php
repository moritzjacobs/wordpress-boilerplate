<?php
/*
Plugin Name: Sanitize Uploads
Plugin URI:
Description: Sanitizes all filenames on upload
Version: 1.1.0
Author: Neonpastell GmbH
Contributors: Yeah GbR
Author URI: http://www.neonpastell.de
 */

add_filter('sanitize_file_name', 'npwp_sanitize_filename_on_upload', 10);

function npwp_sanitize_filename_on_upload($filename) {
	$pathinfo = pathinfo($filename);
	$ext = $pathinfo["extension"];
	$name = $pathinfo["filename"];

	// Replace all special characters
	$replace = array(
		' ' => '_',
		'Ä' => 'Ae',
		'Ö' => 'Oe',
		'Ü' => 'Ue',
		'ä' => 'ae',
		'ö' => 'oe',
		'ü' => 'ue',
		'Ä' => 'Ae',
		'Ö' => 'Oe',
		'Ü' => 'Ue',
		'ä' => 'ae',
		'ö' => 'oe',
		'ü' => 'ue',
		'ß' => 'ss',
	);
	$sanitized = strtr($name, $replace);
	// Replace all other weird characters
	$sanitized = preg_replace('/[^a-zA-Z0-9-_.]/', '', $sanitized);
	// Replace dots inside filename
	$sanitized = str_replace('.', '-', $sanitized);
	return strtolower($sanitized . '.' . $ext);
}
