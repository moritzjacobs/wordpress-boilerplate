<?php
/**
 * Plugin Name: Neonpastell Wordpress Bootstrap Plugins
 * Description: —
 * Author: Moritz Jacobs
 * Version: 1.0
 */
 


new BootstrapPlugins();

class BootstrapPlugins {
	public function __construct() {
		
		add_action('admin_init', function(){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$plugins = get_plugins();
			
			foreach($plugins as $path=>$plugin_data) {
				if (strpos($path, '_')!== 0) {
					activate_plugin($path, true);
				} else {
					deactivate_plugins($path);
				}
			}
		});
	}
}

