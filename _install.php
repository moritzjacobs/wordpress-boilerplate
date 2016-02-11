<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="public/css/styles.css" type="text/css" media="screen">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<title>Install Wordpress</title>
	</head>
	<style>
		body {
			color: #666;
			padding: 0 30px;
			width: 700px;
			margin: auto;
		}
		
		.form-control {
			max-width: 250px;
		}
		
		.spinner {
			display: block;
		}
		
		@-moz-keyframes spinner { 100% { -moz-transform: rotate(360deg); } }
		@-webkit-keyframes spinner { 100% { -webkit-transform: rotate(360deg); } }
		@keyframes spinner { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
		
		.spinner:after {
			display: none;
			margin-left: 10px;
			content: "\27F3";
			-webkit-animation: spinner 1s linear infinite;
			-moz-animation: spinner 1s linear infinite;
			animation: spinner 1s linear infinite;
			color: #aaa;
			font-size: 1.5em;
		}
		
		.spinner:last-child:after {
			display: inline-block;
		}
		
		.monospaced {
			font-family: monospace;
		}

		input[type=submit] {
			width:
		}

	</style>
	<body>

		<?php if (empty($_POST["go"])): ?>

			<h1>wordpress-boilerplate</h1>
			
			<p>This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff.</p>
			
			<h3>Running the installer will…:</h3>
			
			<ul>
				<li><p>download and unpack the current stable Wordpress core in your language of choice</p></li>
				<li><p>rename the core and <code>wp-content</code> folders</p></li>
				<li><p>set up three runtime configurations (<em>local</em>, <em>staging</em> and <em>live</em>)</p></li>
				<li><p>change the db prefix</p></li>
				<li><p>download fresh security keys.</p></li>
				<li><p>migrate the language files from the core</p></li>
			</ul>
			
			
			<h2>Instructions</h2>
			
			<ol>
				<li>Surf to <a href="http://&lt;your_host">http://&lt;your_host</a>/_install.php></li>
				<li>Follow the instructions</li>
				<li>Delete <code>_install.php</code>, <code>wp-config-runtime-sample.php</code> and <code>wp-config-sample.php</code></li>
				<li>Double check <code>wp-config.php</code></li>
				<li>Edit your runtime configurations</li>
			</ol>

			<?php $offline = !@fopen("http://www.google.com:80/","r"); ?>
			<?php if($offline): ?>
				<button class="btn btn-disabled" type="submit">Check your internet connection</button>
			<?php else: ?>
				<h2>Settings</h2>
				<form method="post" action="">
					<div class="form-group">
						<label for="lang">Language code (e.g. en or de)</label>
						<input type="text" class="form-control" value="en" name="lang">
					</div>
					<div class="form-group">
						<label for="core_dir">Core dir name</label>
						<input type="text" class="form-control" value="core" name="core_dir">
					</div>
					<div class="form-group">
						<label for="content_dir">content dir (default /wp-content)</label>
						<input type="text" class="form-control" value="wp-content" name="content_dir">
					</div>
					<div class="form-group">
						<label for="runtimes">Runtime confogurations (comma separated)</label>
						<input type="text" class="form-control" value="local, staging, live" name="runtimes">
					</div>
					
					<button class="btn btn-primary" type="submit" name="go" value="go">Click here to start the installation</button>
				</form>
			<?php endif; ?>

		<?php else: ?>
			<div class="monospaced">
				<?php $wpi = new WPInstall($_POST["lang"], $_POST["core_dir"], $_POST["content_dir"], $_POST["runtimes"]); ?>
				<hr><span style='color:red;font-weight:bold'>
				Don't forget to delete the following files<br>
				<ol>
					<li><code>_install.php</code></li>
					<li><code>wp-config-runtime-sample.php</code></li>
					<li><code>wp-config-sample.php</code></li>
				</ol>
				
			</div>
		<?php endif ?>
</body>
</html>



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
	 * __construct function.
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
	public function __construct($lang = "en", $core_name = "core", $content_name = "wp-content", $runtimes_str = "live, staging, local") {
		// Init vars
		$this->notice_cnt = 0;
		$this->critical_cnt = 0;
		$this->error_cnt = 0;

		// Configure download link
		// it => https://it.wordpress.org/latest-it_IT.zip
		if($lang == "en") {
			$this->_wp_zip_url = "https://wordpress.org/latest.zip";
		} else {
			$this->_wp_zip_url = "https://".$lang.".wordpress.org/latest-".$lang."_".strtoupper($lang).".zip";
		}
		
		// ignore empty runtimes
		if(!empty($runtimes_str)) {
			$runtimes = array_map('trim', explode(",",$runtimes_str));
		} else {
			$runtimes = array();
		}

		$this->head("Let's go!");
		$this->debug("Config: '".implode("', '", func_get_args()) . "'");
		
		// init cURL
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
		
		$this->runtime_info();
		$this->hr();;

		$this->download_core($core_name);
		$switch = $this->create_runtimes($runtimes);
		$this->prepare_wp_config($core_name, $content_name, $switch);
		
		// rename wp-content
		$rename_wp_cont = $content_name!= "wp-content" && file_exists(__DIR__.DIRECTORY_SEPARATOR."wp-content") && is_dir(__DIR__.DIRECTORY_SEPARATOR."wp-content");
		if($rename_wp_cont) {
			$this->debug("attempt to rename wp-content");
			if (!rename(__DIR__.DIRECTORY_SEPARATOR."wp-content", __DIR__.DIRECTORY_SEPARATOR.$content_name)) {
				$this->error("notice: unable to rename wp-content folder");
			}
		}
		
		// 
		if($lang != "en") {
			$this->copy_languages($core_name, $content_name);
		}
		curl_close($this->curl);

		$conclusion = $this->notice_cnt . " notices, ".($this->critical_cnt + $this->error_cnt). " errors!";
		if ($this->critical_cnt + $this->error_cnt == 0) {
			$this->notice($conclusion);
		} else {
			$this->critical($conclusion);
		}

		$this->head("Installation finished!");

		$this->log("Go ahead and edit your runtime configs!");
	}


	/**
	 * write_to_file function.
	 *
	 * @access private
	 * @param mixed $file
	 * @param mixed $content
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
	 * log function.
	 *
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function log($str, $spinner = true) {
		echo "<div".($spinner ? " class='spinner'>" : ">") . ($str) . "</div>";
		@flush();
		@ob_flush();
	}

	private function hr() {
		echo "<hr>";
		@flush();
		@ob_flush();
	}


	/**
	 * debug function.
	 *
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function debug($str) {
		$this->log("<span style='color:blue'>$str</span>", false);
	}


	/**
	 * head function.
	 *
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function head($str) {
		$this->log("<span style='color:blue;font-weight:bold'>$str <hr></span>", false);
	}


	/**
	 * notice function.
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
	 * critical function.
	 *
	 * @access private
	 * @param mixed $str
	 * @return void
	 */
	private function critical($str) {
		$this->critical_cnt;
		$this->log("<span style='color:red;font-weight:bold'>".$str. "</span>");
	}


	/**
	 * error function.
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
	 * copy_languages function.
	 *
	 * @access private
	 * @param mixed $core_name
	 * @param mixed $content_name
	 * @return void
	 */
	private function copy_languages($core_name, $content_name) {
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
	 * prepare_wp_config function.
	 *
	 * @access private
	 * @return void
	 */
	private function prepare_wp_config($core_name, $content_name, $switch = "") {
		$rnd_prefix = str_split("abcdefghijklmnopqrstuvwxyz");
		shuffle($rnd_prefix);
		$rnd_prefix = join(array_slice($rnd_prefix, 0, 3));
		$wp_config = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."wp-config-sample.php");
		$wp_config = str_replace('// {{TABLE_PREFIX}}', '$table_prefix  = \''.$rnd_prefix.'_\';', $wp_config);

		$sec_keys = $this->get_sec_keys();
		$wp_config = str_replace("// {{SECURITY_KEYS}}", $sec_keys, $wp_config);
		$wp_config = str_replace("wordpress-core-dependency", $core_name, $wp_config);
		$wp_config = str_replace("wp-content", $content_name, $wp_config);
		$wp_config = str_replace('// {{RUNTIME_SWITCH}}', $switch, $wp_config);

		if ($this->write_to_file(__DIR__.DIRECTORY_SEPARATOR."wp-config.php", $wp_config, true)) {
			$this->debug("Your table prefix is: " . $rnd_prefix);
		}

		$this->hr();;
	}


	/**
	 * get_sec_keys function.
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


	/**
	 * runtime_info function.
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
		$this->debug("Current default runtime is '" . $current_rt . "'");
	}


	/**
	 * create_runtimes function.
	 *
	 * @access private
	 * @param mixed $runtimes
	 * @return void
	 */
	private function create_runtimes($runtimes) {
		$this->log("Creating runtimes:");
		$tmp = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."wp-config-runtime-sample.php");
		$switch = "switch(true){\n";
		$use_switch = false;
		foreach ($runtimes as $rt_name) {
			if(!in_array($rt_name, array("local", "staging", "preview", "live"))) {
				$switch .= '
	case host_contains("'.$rt_name.'"):
		$runtime_env = "'.$rt_name.'";
		break;
	';
			$use_switch = true;
			}
			$rt_file_content = $tmp;
			$this->log("Creating runtime config: " . $rt_name);
			$sec_keys = $this->get_sec_keys();

			$rt_file_content = str_replace("// {{SECURITY_KEYS}}", $sec_keys, $rt_file_content);
			$rt_file_content = str_replace("{{RT_NAME}}", $rt_name, $rt_file_content);
			$rt_file_name = __DIR__.DIRECTORY_SEPARATOR."wp-config-".$rt_name.".php";
			$this->write_to_file($rt_file_name, $rt_file_content);
		}
		$switch .= "\n}";
		
		$this->hr();;
		
		return $use_switch ? $switch : "";
	}


	/**
	 * core_exists function.
	 *
	 * @access private
	 * @param string $core_name (default: "core")
	 * @return void
	 */
	private function core_exists($core_name = "core") {
		$core_dir = __DIR__.DIRECTORY_SEPARATOR.$core_name;
		return file_exists($core_dir.DIRECTORY_SEPARATOR."wp-includes");
	}


	private function set_core_path($file, $core_name) {
		$content = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$file);
		$content = str_replace('/core/', '/'.$core_name.'/', $content);
		$this->write_to_file(__DIR__.DIRECTORY_SEPARATOR.$file, $content, true);
	}

	/**
	 * download_core function.
	 *
	 * @access private
	 * @param mixed $core_name
	 * @param mixed $core_dest (default: __DIR__)
	 * @return void
	 */
	private function download_core($core_name, $core_dest = __DIR__) {
		if ($this->core_exists($core_name)) {
			$this->notice("Skipping core download...");
			$this->hr();;
			return false;
		}

		$zip_tmp = tempnam(sys_get_temp_dir(), "zip");
		$zip_res = fopen($zip_tmp, "w");

		// Get The Zip File From Server
		curl_setopt($this->curl, CURLOPT_URL, $this->_wp_zip_url);
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_FILE, $zip_res);

		$this->log("Start download from ". $this->_wp_zip_url);

		if (!curl_exec($this->curl)) {
			$this->error("cURL: The language code is probably at fault for this...<br>".curl_error($this->curl));
		}

		/* Open the Zip file */
		$zip = new ZipArchive;
		$this->log("Start unzipping core");
		if ($zip->open($zip_tmp) != "true") {
			$this->error("notice: Unable to open zip File");
		}
		/* Extract Zip File */
		$zip->extractTo($core_dest);
		$zip->close();

		if (!rename(__DIR__.DIRECTORY_SEPARATOR."wordpress", __DIR__.DIRECTORY_SEPARATOR.$core_name)) {
			$this->error("notice: unable to rename core folder");
		}
		
		
		$this->set_core_path("index.php", $core_name);
		$this->set_core_path("htaccess", $core_name);
		
		$this->hr();;
	}


}
