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
	private $noticeCnt = 0;
	private $errorCnt = 0;

	public function __construct() {
		// init cURL
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
	}

	/**
	 * Install a Wordpress instance into a directory
	 *
	 * @param string $destDir Absolute path of installation direcctory
	 * @param string $lang (default: "en")
	 * @param string $coreName (default: "core")
	 * @param string $contentName (default: "site")
	 * @param string $runtimes_str (default: "local, live")
	 */

	function install($destDir, $lang = "en", $coreName = "core", $contentName = "site", $runtimes_str = "local, live", $upload_name = "files", $version = "latest") {
		$runtimes = array_map("trim", explode(",", $runtimes_str));
		$templateDir = realpath(dirname(__DIR__) . "/templates");

		$this->debug("language: <code>" . $lang . "</code><br>
			core dir: <code>" . $coreName . "</code><br>
			content dir: <code>" . $contentName . "</code><br>
			upload dir: <code>" . $upload_name . "</code><br>
			runtime configs: <code>" . $runtimes_str . "</code><br>
		");

		$this->head("Let’s go!");
		$this->hr();

		// process core
		$this->debug("Download from " . self::wpDownloadUrl($version, $lang));
		$this->downloadCore(self::wpDownloadUrl($version, $lang), $coreName, $destDir);

		// process templates
		$this->log("changing index.php, .htaccess and .gitignore");

		$this->writeTemplate(
			$templateDir . "/index.php",
			$destDir . "/index.php",
			array(
				"core/" => $coreName . "/",
			)
		);

		$this->writeTemplate(
			$templateDir . "/htaccess",
			$destDir . "/.htaccess",
			array(
				"{{CORE_DIR_NAME}}" => $coreName,
				"{{UPLOAD_DIR_PATH}}" => $upload_name,
			)
		);

		$this->writeTemplate(
			$templateDir . "/gitignore",
			$destDir . "/.gitignore",
			array(
				"wp-content/" => $contentName . "/",
			)
		);

		$this->writeTemplate(
			$templateDir . "/wp-config-environment.php",
			$destDir . "/wp-config-environment.php"
		);

		$this->writeTemplate(
			$templateDir . "/wp-config.php",
			$destDir . "/wp-config.php",
			array(
				"{{WP_CORE_DIR}}" => $coreName,
				"{{CONTENT_DIR}}" => $contentName,
				"{{UPLOAD_DIR}}" => $upload_name,
			)
		);

		// make env's
		$switch = $this->createWpEnvironments($destDir, $runtimes);

		// make wp-content from template
		$this->copyWpContent($destDir, $contentName);

		// create upload dir
		$this->createUploadDir($destDir, $upload_name);

		// english language Wordpress *has no translation files*
		if ($lang != "en") {
			$this->copyLanguages($destDir, $coreName, $contentName);
		}

		curl_close($this->curl);

		// display the number of errors and notices
		$conclusion = $this->noticeCnt . " notices, " . $this->errorCnt . " errors!";
		if ($this->errorCnt == 0) {
			$this->notice($conclusion);
		} else {
			$this->error($conclusion);
		}

		$this->head("Installation finished!");
		$this->log("Go ahead and edit your runtime configs!");
		$this->log("<hr>");
		$this->log("<a href=\"/\" class=\"btn btn-primary\">continue to Wordpress installation screen</a>");
	}

	/**
	 * generate URL for wp.zip download
	 * @param  [type] $version (default: "latest")
	 * @param  [type] $lang    [description]
	 * @return [type]          [description]
	 */
	static private function wpDownloadUrl($version = "latest", $lang = "en") {
		$baseName = $version == "latest" ? "latest" : "wordpress-{$version}";
		$langExtension = $lang == "en" ? "" : "-${lang}";
		$subdomain = $lang == "en" ? "" : "${lang}.";
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
		echo "<div" . ($spinner ? " class='spinner'>" : ">") . ($str) . "</div>";
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
		$this->log("<span style='color:blue; font-weight:bold'>$str <hr></span>", false);
	}

	/**
	 * display notice
	 *
	 * @param string $str
	 * @return void
	 */
	private function notice($str) {
		$this->noticeCnt++;
		$this->log("<span style='color:orange; font-weight:bold'>" . $str . "</span>", false);
	}

	/**
	 * display error and die()
	 *
	 * @param string $str
	 * @return void
	 */
	private function error($str) {
		$this->errorCnt++;
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
	private function writeToFile($file, $content, $force = false) {
		if (!$force && file_exists($file)) {
			$this->notice("Refusing to overwrite " . $file);
			return false;
		}
		return is_int(file_put_contents($file, $content));
	}

	/**
	 * copy wp-content from template without overwriting existing dir
	 */
	private function copyWpContent($destDir, $contentName) {
		$wpContentDir = $destDir . "/" . $contentName;

		if (!is_dir($wpContentDir)) {
			$this->log("attempt to copy wp-content to ${wpContentDir}");
			self::copyDirectoryTree(dirname(__DIR__) . "/templates/wp-content", $wpContentDir);
		} else {
			$this->notice("wp-content dir '${wpContentDir}' already exists");
		}
	}

	private function createUploadDir($destDir, $upload_name) {
		$uploadDirName = $destDir . "/" . $upload_name;
		if (!file_exists($uploadDirName)) {
			mkdir($uploadDirName, 0755, true);
		}

		$indexFile = $uploadDirName . "/index.php";
		if (!file_exists($indexFile)) {
			$this->writeToFile($indexFile, "<?php // Silence is golden.");
		}
	}
	/**
	 * Migrate translation files from the core
	 *
	 * @access private
	 * @param string $destDir
	 * @param string $coreName
	 * @param string $contentName
	 * @return void
	 */
	private function copyLanguages($destDir, $coreName, $contentName) {
		// Is there already a core?
		if ($this->coreExists($destDir, $coreName)) {
			$source = $destDir . "/" . $coreName . "/" . "wp-content" . "/" . "languages";
			$dest = $destDir . "/" . $contentName . "/" . "languages";

			if (file_exists($dest)) {
				$this->notice("Skipping languages...");
			} else if (!file_exists($source)) {
				$this->notice("Core has no translation files");
			} else {
				self::copyDirectoryTree($source, $dest);
			}
		} else {
			$this->notice("Can’t find translation files?");
		}
	}

	/**
	 * Download a fresh set of security keys from the wordpress.org API
	 *
	 * @access private
	 * @return string
	 */
	private function getSecurityKeys() {
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($this->curl, CURLOPT_URL, self::SecApiUrl);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		$sec_keys = curl_exec($this->curl);

		if (!$sec_keys) {
			$this->error("notice: " . curl_error($this->curl));
			die();
		}

		return $sec_keys;
	}

	/**
	 * @return string
	 */
	static private function generateTablePrefix() {
		$rndPrefix = str_split("abcdefghijklmnopqrstuvwxyz");
		shuffle($rndPrefix);
		return join("", array_slice($rndPrefix, 0, 3));
	}

	/**
	 * handle the runtime config creation.
	 *
	 * @param string $destDir
	 * @param array $runtimes
	 * @return string
	 */
	private function createWpEnvironments($destDir, $runtimes) {
		$this->log("Creating runtimes:");
		// load local runtime template
		$template = file_get_contents(dirname(__DIR__) . "/_wp-config-ENV-SAMPLE.php");

		$else = "";
		foreach ($runtimes as $rt_name) {
			// switch case for wp-config
			$else .=
				"\n\t" .
				"case host_contains('" . $rt_name . "'): " .
				"\n\t\t" . "\$runtime_env = '" . $rt_name . "'; " .
				"\n\t\t" . "break; ";

			// create wp-config-runtime
			$this->log("Creating runtime config: " . $rt_name);
			// replace strings and write file
			$rt_file_content = str_replace("// {{SECURITY_KEYS}}", $this->getSecurityKeys(), $template);
			$rt_file_content = str_replace("local_", $rt_name . "_", $rt_file_content);
			$rt_file_content = str_replace("{{TABLE_PREFIX}}", WPInstall::generateTablePrefix() . "_", $rt_file_content);

			$this->writeToFile($destDir . "/wp-config-" . $rt_name . ".php",
				$rt_file_content);
		}

		return $else;
	}

	/**
	 * does a core already exist?
	 *
	 * @access private
	 * @param string $destDir
	 * @param string $coreName
	 * @return bool
	 */
	private function coreExists($destDir, $coreName) {
		return file_exists($destDir . "/" . $coreName);
	}

	/**
	 * Write a template file with given data
	 *
	 * @param string $src       Source file
	 * @param string $dest      Destionation file
	 * @param array $data       Array with search => replacement values
	 * @return void
	 */
	private function writeTemplate($src, $dest, $data = array()) {
		$content = file_get_contents($src);
		foreach ($data as $search => $replace) {
			$content = str_replace($search, $replace, $content);
		}
		$this->writeToFile($dest, $content, true);
	}

	/**
	 * download the Wordpress core
	 *
	 * @param string $url
	 * @param string $coreName
	 * @param string $destDir
	 * @return void
	 */
	private function downloadCore($url, $coreName, $destDir) {
		$wordpressDir = $destDir . "/" . $coreName;

		if ($this->coreExists($destDir, $coreName)) {
			$this->notice("Skipping core download, Wordpress already exists in this location...");
			$this->hr();
		} else {
			$zip_tmp = tempnam(sys_get_temp_dir(), "wordpressZip");
			$zip_res = fopen($zip_tmp, "w");

			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, false);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->curl, CURLOPT_FILE, $zip_res);
			$this->log("Start download from " . $url);

			if (!curl_exec($this->curl)) {
				$this->error("cURL: The language code is probably at fault for this...<br>" . curl_error($this->curl));
				die();
			} else {
				$zip = new ZipArchive;
				$this->log("Start unzipping core");
				if ($zip->open($zip_tmp)) {
					$zip->extractTo($destDir);
					$zip->close();

					// rename core dir as requested
					if (!rename($destDir . "/wordpress", $wordpressDir)) {
						$this->error("notice: unable to rename core folder");
					}
					$this->hr();
				} else {
					$this->error("notice: Unable to open zip File");
				}
			}
		}
	}

	/**
	 * Copy files recursively
	 *
	 * based on http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
	 *
	 * @param string $source
	 * @param string $dest
	 */
	static private function copyDirectoryTree($source, $dest) {
		mkdir($dest, 0755);
		foreach (
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST) as $item
		) {
			if ($item->isDir()) {
				/* @var RecursiveDirectoryIterator $iterator */
				mkdir($dest . "/" . $iterator->getSubPathName());
			} else {
				copy($item, $dest . "/" . $iterator->getSubPathName());
			}
		}
	}
}
