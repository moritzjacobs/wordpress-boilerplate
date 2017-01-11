<?php
/*
Plugin Name: WP_Server_Environment in Admin Bar
Plugin URI: 
Description: Shows a color coded status of the active server environment in the Wordpress Admin Bar. To use this plugin, you need to add a constant named <code>WP_SERVER_ENVIRONMENT</code> to your <code>wp-config.php</code> file, with one of the following values: <code>local</code>, <code>staging</code>, <code>live</code>
Version: 1.0.1
Author: Neonpastell GmbH
Author URI: http://www.neonpastell.de
*/

if ( ! class_exists( 'NPWP_ENV_Admin_Bar' ) && defined('WP_SERVER_ENVIRONMENT') ) :

class NPWP_ENV_Admin_Bar {

	/**
	 * Handles initializing this class and returning the singleton instance after it's been cached.
	 *
	 * @return null|NPWP_ENV_Admin_Bar
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
			self::_add_actions();
		}

		return $instance;
	}

	/**
	 * An empty constructor
	 */
	public function __construct() { /* Purposely do nothing here */ }

	/**
	 * Handles registering hooks that initialize this plugin.
	 */
	public static function _add_actions() {
		add_action( 'admin_bar_menu', array( __CLASS__, 'npwp_env_in_admin_bar'), 10); // 10|15|25|100
		add_action( 'admin_head', array( __CLASS__, 'npwp_env_in_admin_bar_colorize') ); // add styles to admin head
		add_action( 'wp_head', array( __CLASS__, 'npwp_env_in_admin_bar_colorize') ); // add styles to frontend	
	}

	public static function npwp_env_in_admin_bar( $wp_admin_bar ) {
		if ( current_user_can( 'manage_options' ) && is_admin_bar_showing() ) {
			$args = array(
				'id'    => 'wpnp-env',
				'title' => '' . strtoupper(WP_SERVER_ENVIRONMENT),
				//'parent' => 'top-secondary',
				'html' => 'div',
				'href'  => get_admin_url(),
				'meta'  => array( 'title' => 'WP_SERVER_ENVIRONMENT: ' . WP_SERVER_ENVIRONMENT )
			);
			$wp_admin_bar->add_node( $args );
		}
	}


	public static function npwp_env_in_admin_bar_colorize() {
		if ( current_user_can( 'manage_options' ) && is_admin_bar_showing() ) {
			// add color to env menu
			echo '<style type="text/css">
				#wpadminbar { box-sizing: border-box; }
				#wpadminbar #wp-admin-bar-wpnp-env > .ab-item { color: #eee; }
				#wpadminbar #wp-admin-bar-wpnp-env > .ab-item:before {
					position: relative;
				    float: left;
				    font: 400 20px/1 dashicons;
				    speak: none;
				    padding: 4px 0;
				    -webkit-font-smoothing: antialiased;
				    -moz-osx-font-smoothing: grayscale;
				    background-image: none!important;
				    margin-right: 6px;
				    color: #a0a5aa;
				    color: rgba(240,245,250,.6);
				    position: relative;
				    -webkit-transition: all .1s ease-in-out;
				    transition: all .1s ease-in-out;
				    content: "\f325";
    				top: 2px;
				}
			</style>';

			$style_live = '<style type="text/css">#wpadminbar { border-bottom: 5px solid #C50114; } #wpadminbar #wp-admin-bar-wpnp-env > .ab-item { background: #C50114; !important }</style>';
			$style_staging = '<style type="text/css">#wpadminbar { border-bottom: 5px solid #D18300; } #wpadminbar #wp-admin-bar-wpnp-env > .ab-item { background: #D18300; !important }</style>';
			$style_local = '<style type="text/css">#wpadminbar #wp-admin-bar-wpnp-env > .ab-item { background: #093F11; !important }</style>';
			switch (WP_SERVER_ENVIRONMENT) {
				case 'local':
				case 'dev':
				case 'development':
					echo $style_local;
					break;
				case 'live':
				case 'production':
					echo $style_live;
					break;
				default:
					// do nothing
					echo $style_staging;
			}
		}
	}

}

NPWP_ENV_Admin_Bar::get_instance();

endif;