<?php
	/**
	 * This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff.
	 *
	 * See https://github.com/moritzjacobs/wordpress-boilerplate for more documentation
	 *
	 * @author     Moritz Jacobs
	 * @link       https://github.com/moritzjacobs/wordpress-boilerplate
	 */
 
	class WPInstall {

	/**
	 * Wordpress.org API for retreiving fresh security keys
	 *
	 * (default value: "https://api.wordpress.org/secret-key/1.1/salt/")
	 *
	 * @var string
	 * @access private
	 */
	private $_sec_api_url = "https://api.wordpress.org/secret-key/1.1/salt/";


	/**
	 * Main function for processing the installation
	 * 
	 * @access public
	 * @param string $lang (default: "en")
	 * @param string $core_name (default: "core")
	 * @param string $content_name (default: "wp-content")
	 * @param string $runtimes_str (default: "live)
	 * @param mixed staging
	 * @param mixed local"
	 * @return void
	 */
	public function __construct($lang = "en", $core_name = "core", $content_name = "wp-content", $runtimes_str = "", $upload_name = '', $version = '') {
		// Init vars
		$this->notice_cnt = 0;
		$this->error_cnt = 0;

		// Configure download link
		$baseName = $version == 'latest' ? 'latest' : "wordpress-{$version}";
		$langExtension = $lang == 'en' ? '' : "-${lang}";
		$subdomain = $lang == 'en' ? '' : "${lang}.";
		$this->_wp_zip_url = "https://${subdomain}.wordpress.org/${baseName}${langExtension}.zip";
		$this->debug($this->_wp_zip_url);

		$runtimes = array_map('trim',
		                      explode(",",
		                              $runtimes_str == '' ? 'local, live' : 'local, live, ' . $runtimes_str));

		$this->head("Let's go!");

		if ($core_name == '') { 		$core_name = 'core'; }
		if ($content_name == '') { 		$content_name = 'wp-content'; }
		$custom_upload_dir = !($upload_name == $content_name.'/uploads' || $upload_name == '');
		$upload_name = $custom_upload_dir ? $upload_name : 'wp-content/uploads';

		$this->debug('language: <code>' .$lang.'</code><br>
			core dir: <code>' . $core_name . '</code><br>
			content dir: <code>' . $content_name . '</code><br>
			upload dir: <code>' . $upload_name . '</code><br>
			runtime configs: <code>' . $runtimes_str . '</code><br>
		');

		// init cURL
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
		
		// display current host runtime
		$this->runtime_info();
		$this->hr();
		
		// process core
		$this->download_core($core_name);
		
		// change absolute paths to new core dir
		$this->log("changing index.php, .htaccess and .gitigore");
		$this->set_core_path("index.php", $core_name);
		$this->set_core_path(".htaccess", $core_name);
		$this->sar_in_file(".gitignore", "wp-content/", $content_name."/");
		
		// process runtimes
		$switch = $this->create_runtimes($runtimes);
		$this->log("Creating runtime config: local");
		$this->set_salts('wp-config-local.php');
		$this->log("Creating runtime config: live");
		$this->set_salts('wp-config-live.php');

		// make wp-config
		$this->prepare_wp_config($core_name, $content_name, $lang, $switch, $upload_name);

		// rename wp-content
		$rename_wp_cont = $content_name!= "wp-content" && file_exists(__DIR__.DIRECTORY_SEPARATOR."wp-content") && is_dir(__DIR__.DIRECTORY_SEPARATOR."wp-content");
		if($rename_wp_cont) {
			$this->log("attempt to rename wp-content");
			if (!rename(__DIR__.DIRECTORY_SEPARATOR."wp-content", __DIR__.DIRECTORY_SEPARATOR.$content_name)) {
				$this->error("notice: unable to rename wp-content folder");
			}
		}

		// create file in upload folder
		if ($custom_upload_dir) {
			$upload_dir_name = __DIR__.DIRECTORY_SEPARATOR.$upload_name;
			if (!file_exists($upload_dir_name)) {
			    mkdir($upload_dir_name, 0777, true);
			}
			$this->write_to_file($upload_dir_name.DIRECTORY_SEPARATOR."index.php", '<?php // Silence is golden.');
		}
		
		// english lamguage Wordpress *has no translation files*
		if($lang != "en") {
			$this->copy_languages($core_name, $content_name);
		}
		curl_close($this->curl);

		// display the number of errors and notices
		$conclusion = $this->notice_cnt . " notices, ".$this->error_cnt. " errors!";
		if ($this->error_cnt == 0) {
			$this->notice($conclusion);
		} else {
			$this->error($conclusion);
		}

		$this->head("Installation finished!");
		$this->log("Go ahead and edit your runtime configs!");
		$this->log("<hr>");
		$this->log("<a href=\"/\" class=\"btn btn-primary\">then continue to install Wordpress</a>");
	}




	/**
	 * print to screen
	 * 
	 * @access private
	 * @param mixed $str
	 * @param bool $spinner (default: true)
	 * @return void
	 */
	private function log($str, $spinner = true) {
		echo "<div".($spinner ? " class='spinner'>" : ">") . ($str) . "</div>";
		@flush();
		@ob_flush();
	}

	/**
	 * display horizontal line
	 * 
	 * @access private
	 * @return void
	 */
	private function hr() {
		echo "<hr>";
		@flush();
		@ob_flush();
	}


	/**
	 * display debug info
	 * 
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function debug($str) {
		$this->log("<span style='color:blue'>$str</span>", false);
	}


	/**
	 * display head
	 * 
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function head($str) {
		$this->log("<span style='color:blue;font-weight:bold'>$str <hr></span>", false);
	}


	/**
	 * display notice
	 * 
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function notice($str) {
		$this->notice_cnt++;
		$this->log("<span style='color:orange;font-weight:bold'>".$str. "</span>", false);
	}


	/**
	 * display error and die()
	 * 
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function error($str) {
		$this->error_cnt++;
		$this->notice($str);
		echo "<span style='color:red;font-weight:bold'>";
		die("-- FAILED! --</span>");
	}



	/**
	 * write to a file without overwriting (= force option)
	 * 
	 * @access private
	 * @param mixed $file
	 * @param mixed $content
	 * @param bool $force (default: false)
	 * @return void
	 */
	private function write_to_file($file, $content, $force = false) {
		if (!$force && file_exists($file)) {
			$this->notice("Refusing to overwrite ".$file);
			return false;
		}
		return file_put_contents($file, $content);
	}


	/**
	 * Migrate translation files from the core
	 * 
	 * @access private
	 * @param mixed $core_name
	 * @param mixed $content_name
	 * @return void
	 */
	private function copy_languages($core_name, $content_name) {
		// Is there already a core?
		if ($this->core_exists($core_name)) {
			$source = __DIR__.DIRECTORY_SEPARATOR.$core_name.DIRECTORY_SEPARATOR."wp-content/languages";
			$dest = __DIR__.DIRECTORY_SEPARATOR.$content_name.DIRECTORY_SEPARATOR."languages";

			if (file_exists($dest)) {
				$this->notice("Skipping languages...");
				return false;
			}
			if (!file_exists($source)) {
				$this->notice("Core has no translation files");
				return false;
			}
			
			// recursive copy
			mkdir($dest, 0755);
			foreach (
				$iterator = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
					\RecursiveIteratorIterator::SELF_FIRST) as $item
			) {
				if ($item->isDir()) {
					mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				} else {
					copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				}
			}
		} else {
			$this->notice("Can't find translation files?");
		}
	}




	/**
	 * Make a new wp-config and change some things
	 * 
	 * @access private
	 * @param mixed $core_name
	 * @param mixed $content_name
	 * @param string $lang (default: "en")
	 * @param string $switch (default: "")
	 * @return void
	 */
	private function prepare_wp_config($core_name, $content_name, $lang = "en", $switch = "", $upload_name) {
		$this->log("Creating wp-config");
		// get wp-config-sample
		$wp_config = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."_wp-config-SAMPLE.php");
		
		// set table prefix
		$rnd_prefix = str_split("abcdefghijklmnopqrstuvwxyz");
		shuffle($rnd_prefix);
		$rnd_prefix = join(array_slice($rnd_prefix, 0, 3));
		$wp_config = str_replace('{{TABLE_PREFIX}}', $rnd_prefix.'_', $wp_config);

		// change core and content path
		$wp_config = str_replace("{{WP_CORE_DIR}}", $core_name, $wp_config);
		$wp_config = str_replace("{{CONTENT_DIR}}", $content_name, $wp_config);
		$wp_config = str_replace("{{UPLOAD_DIR}}", $upload_name, $wp_config);
		if ($upload_name != $content_name.'/uploads') {
			$wp_config = str_replace("{{USE_UPLOAD_DIR}}", 'true', $wp_config);
		} else {
			$wp_config = str_replace("{{USE_UPLOAD_DIR}}", 'false', $wp_config);
		}
		
		// add environment runtimes switch
		$wp_config = str_replace('// {{RUNTIME_SWITCH}}', $switch, $wp_config);

		// write file
		if ($this->write_to_file(__DIR__.DIRECTORY_SEPARATOR."wp-config.php", $wp_config)) {
			$this->debug("Your table prefix is: " . $rnd_prefix . "_");
		}

		$this->hr();
	}



	/**
	 * Download a new set of security keys from the wordpress.org API
	 * 
	 * @access private
	 * @return void
	 */
	private function get_sec_keys() {
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($this->curl, CURLOPT_URL, $this->_sec_api_url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		$sec_keys = curl_exec($this->curl);

		if (!$sec_keys) {
			$this->error("notice: ".curl_error($this->curl));
		}

		return $sec_keys;
	}

	private function set_salts($filename) {
		$tmp = file_get_contents($filename);
		$rt_file_content = $tmp;
		// add new security key
		$sec_keys = $this->get_sec_keys();
		$rt_file_content = preg_replace("/(define\( ?'(AUTH_KEY|SECURE_AUTH_KEY|LOGGED_IN_KEY|NONCE_KEY|AUTH_SALT|SECURE_AUTH_SALT|LOGGED_IN_SALT|NONCE_SALT)(.*\n))/", '', $rt_file_content);
		$rt_file_content = str_replace("// {{SECURITY_KEYS}}", $sec_keys . "// {{SECURITY_KEYS}}", $rt_file_content);
		$this->write_to_file($filename, $rt_file_content, true);
		return $sec_keys;
	}



	/**
	 * Display info about the predicted runtime for our defaults of "dev", "local", "staging" and "preview"
	 * 
	 * @access private
	 * @return void
	 */
	private function runtime_info() {
		switch (true) {
			case stristr($_SERVER['SERVER_NAME'], "dev"):
			case stristr($_SERVER['SERVER_NAME'], "local"):
				$current_rt = "local";
				break;
			case stristr($_SERVER['SERVER_NAME'], "staging"):
			case stristr($_SERVER['SERVER_NAME'], "preview"):
				$current_rt = "staging";
				break;
			default:
				$current_rt = "live";
		}
		$this->debug("Current runtime is '" . $current_rt . "'");
	}



	/**
	 * handle the runtime config creation.
	 * 
	 * @access private
	 * @param mixed $runtimes
	 * @return void
	 */
	private function create_runtimes($runtimes) {
		$this->log("Creating runtimes:");

		// load local runtime template
		$tmp = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."_wp-config-ENV-SAMPLE.php");
		$rt_file_name = __DIR__.DIRECTORY_SEPARATOR."wp-config-local.php";
		$this->write_to_file($rt_file_name, $tmp);

		if (empty($runtimes) || $runtimes == '') {
			return;
		}
		
		$else = '';
		foreach ($runtimes as $rt_name) {
			// switch case for wp-config
			$else .= "\n\t".'case host_contains("'.$rt_name.'"): '."\n\t\t".'$runtime_env = "'.$rt_name.'"; '."\n\t\t".'break; ';

			// create wp-config-runtime
			$rt_file_content = $tmp;
			$this->log("Creating runtime config: " . $rt_name);
			// replace strings and write file
			$rt_file_content = str_replace("// {{SECURITY_KEYS}}", $this->get_sec_keys()."// {{SECURITY_KEYS}}", $rt_file_content);
			$rt_file_content = str_replace("local_", $rt_name.'_', $rt_file_content);
			$rt_file_name = __DIR__.DIRECTORY_SEPARATOR."wp-config-".$rt_name.".php";
			$this->write_to_file($rt_file_name, $rt_file_content);
		}
		
		return $else;
	}


	/**
	 * does a core already exist?
	 * 
	 * @access private
	 * @param string $core_name (default: "core")
	 * @return void
	 */
	private function core_exists($core_name = "core") {
		$core_dir = __DIR__.DIRECTORY_SEPARATOR.$core_name;
		return file_exists($core_dir.DIRECTORY_SEPARATOR."wp-includes");
	}


	/**
	 * open a file, search and replace
	 * 
	 * @access private
	 * @param mixed $file
	 * @param mixed $search
	 * @param mixed $replace
	 * @return void
	 */
	private function sar_in_file($file, $search, $replace) {
		$content = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$file);
		$content = str_replace($search, $replace, $content);
		$this->write_to_file(__DIR__.DIRECTORY_SEPARATOR.$file, $content, true);
		
	}


	/**
	 * Replace default core dir
	 * 
	 * @access private
	 * @param mixed $file
	 * @param mixed $core_name
	 * @return void
	 */
	private function set_core_path($file, $core_name) {
		$this->sar_in_file($file, "core/", $core_name."/");
	}



	/**
	 * download the Wordpress core
	 * 
	 * @access private
	 * @param mixed $core_name
	 * @param mixed $core_dest (default: __DIR__)
	 * @return void
	 */
	private function download_core($core_name, $core_dest = __DIR__) {
		
		// we already have a core
		if ($this->core_exists($core_name)) {
			$this->notice("Skipping core download, Wordpress already exists...");
			$this->hr();
			return false;
		}

		// make tmp
		$zip_tmp = tempnam(sys_get_temp_dir(), "zip");
		$zip_res = fopen($zip_tmp, "w");

		// download zip file
		curl_setopt($this->curl, CURLOPT_URL, $this->_wp_zip_url);
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_FILE, $zip_res);

		$this->log("Start download from ". $this->_wp_zip_url);

		if (!curl_exec($this->curl)) {
			$this->error("cURL: The language code is probably at fault for this...<br>".curl_error($this->curl));
		}

		// unzip
		$zip = new ZipArchive;
		$this->log("Start unzipping core");
		if ($zip->open($zip_tmp) != "true") {
			$this->error("notice: Unable to open zip File");
		}

		$zip->extractTo($core_dest);
		$zip->close();

		// rename core dir as wished
		if (!rename(__DIR__.DIRECTORY_SEPARATOR."wordpress", __DIR__.DIRECTORY_SEPARATOR.$core_name)) {
			$this->error("notice: unable to rename core folder");
		}
		
		$this->hr();
	}
}
