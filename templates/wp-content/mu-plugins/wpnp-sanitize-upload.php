<?php
/*
Plugin Name: Sanitize Uploads
Plugin URI:
Description: Sanitizes all filenames on upload
Version: 1.0.0
Author: Neonpastell GmbH
Author URI: http://www.neonpastell.de
 */

if (!class_exists('NPWP_Sanitize_uploads')):

	class NPWP_Sanitize_uploads {

		public static function get_instance() {
			static $instance = null;

			if (null === $instance) {
				$instance = new self();
				add_filter('sanitize_file_name', array(__CLASS__, 'sanitize_filename_on_upload'), 10);
			}

			return $instance;
		}

		/**
		 * An empty constructor
		 */
		public function __construct() { /* Purposely do nothing here */}

		function sanitize_filename_on_upload($filename) {
			$explodedFilename = explode('.', $filename);
			$ext = end($explodedFilename);
			// Replace all special characters
			$replace = array(
				' ' => '_',
				'Ä' => 'Ae',
				'Ö' => 'Oe',
				'Ü' => 'Ue',
				'ä' => 'ae',
				'ö' => 'oe',
				'ü' => 'ue',
				'ß' => 'ss',
			);
			$sanitized = strtr(substr($filename, 0, -(strlen($ext) + 1)), $replace);
			// Replace all other weird characters
			$sanitized = preg_replace('/[^a-zA-Z0-9-_.]/', '', $sanitized);
			// Replace dots inside filename
			$sanitized = str_replace('.', '-', $sanitized);
			return strtolower($sanitized . '.' . $ext);
		}

	}

	NPWP_Sanitize_uploads::get_instance();

endif;
