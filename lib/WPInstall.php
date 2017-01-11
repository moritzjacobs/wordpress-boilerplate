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
	/** Wordpress.org API for retreiving fresh security keys */
	const SecApiUrl = "https://api.wordpress.org/secret-key/1.1/salt/";

	const RootDir = __DIR__.DIRECTORY_SEPARATOR."..";

	private $notice_cnt = 0;
	private $error_cnt = 0;
	/**
	 * Main function for processing the installation
	 * 
	 * @param string $lang (default: "en")
	 * @param string $core_name (default: "core")
	 * @param string $content_name (default: "wp-content")
	 * @param string $runtimes_str (default: "live)
	 */
	public function __construct($lang = "en", $core_name = "core", $content_name = "wp-content", $runtimes_str = "", $upload_name = '', $version = '') {
		// prepare args
		$runtimes = array_map('trim',
		                      explode(",",
		                              $runtimes_str == '' ? 'local, live' : 'local, live, ' . $runtimes_str));
		$core_name = $core_name == '' ? 'core' : $core_name;
		$content_name = $content_name == '' ? 'wp-content' : $content_name;
		$upload_name = $upload_name == '' ? $content_name.DIRECTORY_SEPARATOR.'uploads' : $upload_name;

		$this->debug('language: <code>' .$lang.'</code><br>
			core dir: <code>' . $core_name . '</code><br>
			content dir: <code>' . $content_name . '</code><br>
			upload dir: <code>' . $upload_name . '</code><br>
			runtime configs: <code>' . $runtimes_str . '</code><br>
		');

		$this->head("Let's go!");

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

		$destDir = WPInstall::RootDir.DIRECTORY_SEPARATOR.'wordpress';
		$templateDir = WPInstall::RootDir.DIRECTORY_SEPARATOR.'templates';

		// process core
		$this->debug("Download from ". self::wp_download_url($version, $lang));
		$this->download_core(self::wp_download_url($version, $lang), $core_name, $destDir);
		
		// change absolute paths to new core dir
		$this->log("changing index.php, .htaccess and .gitignore");
		$this->sar_in_file($templateDir.DIRECTORY_SEPARATOR."index.php",
		                   $destDir.DIRECTORY_SEPARATOR."index.php",
		                   "core/",
		                   $core_name."/");
		$this->sar_in_file($templateDir.DIRECTORY_SEPARATOR.".htaccess",
		                   $destDir.DIRECTORY_SEPARATOR.".htaccess",
		                   "core/",
		                   $core_name."/");
		$this->sar_in_file($templateDir.DIRECTORY_SEPARATOR."gitignore",
		                   $destDir.DIRECTORY_SEPARATOR.".gitignore",
		                   "wp-content/",
		                   $content_name."/");

		$switch = $this->create_wp_environments($destDir, $runtimes);
		$this->create_wp_config($destDir, $core_name, $content_name, $switch, $upload_name);
		$this->copy_wp_content($destDir, $content_name);
		$this->create_upload_dir($destDir, $upload_name);
		
		// english language Wordpress *has no translation files*
		if ($lang != "en") {
			$this->copy_languages($destDir, $core_name, $content_name);
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

	static private function wp_download_url($version, $lang) {
		$baseName = $version == 'latest' ? 'latest' : "wordpress-{$version}";
		$langExtension = $lang == 'en' ? '' : "-${lang}";
		$subdomain = $lang == 'en' ? '' : "${lang}.";
		return "https://${subdomain}wordpress.org/${baseName}${langExtension}.zip";
	}

	/**
	 * print to screen
	 * 
	 * @param string $str
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
	 * @param string $str
	 * @return void
	 */
	private function debug($str) {
		$this->log("<span style='color:blue'>$str</span>", false);
	}

	/**
	 * display head
	 * 
	 * @param string $str
	 * @return void
	 */
	private function head($str) {
		$this->log("<span style='color:blue;font-weight:bold'>$str <hr></span>", false);
	}

	/**
	 * display notice
	 * 
	 * @param string $str
	 * @return void
	 */
	private function notice($str) {
		$this->notice_cnt++;
		$this->log("<span style='color:orange;font-weight:bold'>".$str. "</span>", false);
	}

	/**
	 * display error and die()
	 * 
	 * @param string $str
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
	 * @param string $file
	 * @param string $content
	 * @param bool $force (default: false)
	 * @return bool
	 */
	private function write_to_file($file, $content, $force = false) {
		if (!$force && file_exists($file)) {
			$this->notice("Refusing to overwrite ".$file);
			return false;
		}
		return is_int(file_put_contents($file, $content));
	}

	private function copy_wp_content($destDir, $content_name) {
		$wpContentDir = $destDir.DIRECTORY_SEPARATOR.$content_name;

		if (!is_dir($wpContentDir)) {
			$this->log("attempt to copy wp-content to ${wpContentDir}");
			self::copy_tree(self::RootDir.DIRECTORY_SEPARATOR.'templates/wp-content', $wpContentDir);
		}
		else {
			$this->notice("wp-content dir '${wpContentDir}' already exists");
		}
	}

	private function create_upload_dir($destDir, $upload_name) {
		$upload_dir_name = $destDir.DIRECTORY_SEPARATOR.$upload_name;
		if (!file_exists($upload_dir_name)) {
			// TODO review permissions
			mkdir($upload_dir_name, 0777, true);
		}
		$indexFile = $upload_dir_name.DIRECTORY_SEPARATOR."index.php";
		if (!file_exists($indexFile)) {
			$this->write_to_file($indexFile, '<?php // Silence is golden.');
		}
	}
	/**
	 * Migrate translation files from the core
	 * 
	 * @access private
	 * @param string $destDir
	 * @param string $core_name
	 * @param string $content_name
	 * @return void
	 */
	private function copy_languages($destDir, $core_name, $content_name) {
		// Is there already a core?
		if ($this->core_exists($destDir, $core_name)) {
			$source = $destDir.DIRECTORY_SEPARATOR
				.$core_name.DIRECTORY_SEPARATOR
				."wp-content".DIRECTORY_SEPARATOR
				."languages";
			$dest = $destDir.DIRECTORY_SEPARATOR
				.$content_name.DIRECTORY_SEPARATOR
				."languages";

			if (file_exists($dest)) {
				$this->notice("Skipping languages...");
			}
			else if (!file_exists($source)) {
				$this->notice("Core has no translation files");
			}
			else {
				self::copy_tree($source, $dest);
			}
		}
		else {
			$this->notice("Can't find translation files?");
		}
	}

	/**
	 * Make a new wp-config and change some things
	 * 
	 * @access private
	 * @param string $core_name
	 * @param string $content_name
	 * @param string $switch (default: "")
	 * @return void
	 */
	private function create_wp_config($destDir, $core_name, $content_name, $switch = "", $upload_name) {
		$this->log("Creating wp-config");
		// get wp-config-sample
		$wp_config = file_get_contents(WPInstall::RootDir.DIRECTORY_SEPARATOR."_wp-config-SAMPLE.php");
		
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
		$this->write_to_file($destDir.DIRECTORY_SEPARATOR."wp-config.php", $wp_config);

		$this->hr();
	}

	/**
	 * Download a new set of security keys from the wordpress.org API
	 * 
	 * @access private
	 * @return string
	 */
	private function get_sec_keys() {
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($this->curl, CURLOPT_URL, self::SecApiUrl);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		$sec_keys = curl_exec($this->curl);

		if (!$sec_keys) {
			$this->error("notice: ".curl_error($this->curl));
			die();
		}

		return $sec_keys;
	}

	/**
	 * Display info about the predicted runtime for our defaults of "dev", "local", "staging" and "preview"
	 * 
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
	 * @return string
	 */
	static private function create_table_prefix() {
		$rnd_prefix = str_split("abcdefghijklmnopqrstuvwxyz");
		shuffle($rnd_prefix);
		return join('', array_slice($rnd_prefix, 0, 3));
	}

	/**
	 * handle the runtime config creation.
	 * 
	 * @param string $destDir
	 * @param array $runtimes
	 * @return string
	 */
	private function create_wp_environments($destDir, $runtimes) {
		$this->log("Creating runtimes:");
		// load local runtime template
		$template = file_get_contents(WPInstall::RootDir.DIRECTORY_SEPARATOR."_wp-config-ENV-SAMPLE.php");

		$else = '';
		foreach ($runtimes as $rt_name) {
			// switch case for wp-config
			$else .= "\n\t".'case host_contains("'.$rt_name.'"): '."\n\t\t".'$runtime_env = "'.$rt_name.'"; '."\n\t\t".'break; ';

			// create wp-config-runtime
			$this->log("Creating runtime config: " . $rt_name);
			// replace strings and write file
			$rt_file_content = str_replace("// {{SECURITY_KEYS}}", $this->get_sec_keys(), $template);
			$rt_file_content = str_replace("local_", $rt_name.'_', $rt_file_content);
			$rt_file_content = str_replace('{{TABLE_PREFIX}}', WPInstall::create_table_prefix().'_', $rt_file_content);

			$this->write_to_file($destDir.DIRECTORY_SEPARATOR."wp-config-".$rt_name.".php",
			                     $rt_file_content);
		}
		
		return $else;
	}

	/**
	 * does a core already exist?
	 *
	 * @access private
	 * @param string $downloadsDir
	 * @param string $core_name
	 * @return bool
	 */
	private function core_exists($downloadsDir, $core_name) {
		return file_exists($downloadsDir.DIRECTORY_SEPARATOR.$core_name.DIRECTORY_SEPARATOR."wp_includes");
	}

	/**
	 * open a file, search and replace
	 * 
	 * @param string $src       Source file
	 * @param string $dest      Destionation file
	 * @param string $search
	 * @param string $replace
	 * @return void
	 */
	private function sar_in_file($src, $dest, $search, $replace) {
		$this->write_to_file($dest,
		                     str_replace($search, $replace, file_get_contents($src)),
		                     true);
	}

	/**
	 * download the Wordpress core
	 * 
	 * @param string $url
	 * @param string $core_name
	 * @param string $destDir
	 * @return void
	 */
	private function download_core($url, $core_name, $destDir) {
		$wordpressDir = $destDir.DIRECTORY_SEPARATOR.$core_name;

		if ($this->core_exists($destDir, $core_name)) {
			// TODO how and when will this cache be invalidated
			$this->notice("Skipping core download, Wordpress already exists...");
			$this->hr();
		}
		else {
			$zip_tmp = tempnam(sys_get_temp_dir(), "zip");
			$zip_res = fopen($zip_tmp, "w");

			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, false);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->curl, CURLOPT_FILE, $zip_res);
			$this->log("Start download from ". $url);

			if (!curl_exec($this->curl)) {
				$this->error("cURL: The language code is probably at fault for this...<br>".curl_error($this->curl));
				die();
			}
			else {
				$zip = new ZipArchive;
				$this->log("Start unzipping core");
				if ($zip->open($zip_tmp)) {
					$zip->extractTo($destDir);
					$zip->close();

					// rename core dir as wished
					if (!rename($destDir.DIRECTORY_SEPARATOR."wordpress", $wordpressDir)) {
						$this->error("notice: unable to rename core folder");
					}
					$this->hr();
				}
				else {
					$this->error("notice: Unable to open zip File");
				}
			}
		}
	}

	/**
	 * Copy files recursively
	 *
	 * Likely based on http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
	 *
	 * @param string $source
	 * @param string $dest
	 */
	static private function copy_tree($source, $dest) {
		mkdir($dest, 0755);
		foreach (
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST) as $item
		) {
			if ($item->isDir()) {
				/* @var RecursiveDirectoryIterator $iterator */
				mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
			else {
				copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}
}
