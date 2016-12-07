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
			padding: 20px 30px;
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
			margin-top: 50px;
		}

		input[type=submit] {
			width:
		}

	</style>
	<body>

		<?php if (empty($_POST["go"])): ?>

			<h1>wordpress-boilerplate</h1>
			<p>This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff.</p>
			
			<h2>Instructions</h2>
			<ol>
				<li>Customize settings and run installer</li>
				<li>Then delete <code>_install.php</code>, <code>_wp-config-ENV-SAMPLE.php</code> and <code>wp-config-SAMPLE.php</code></li>
				<li>Double check <code>wp-config.php</code> and edit your runtime configs</li>
				<li>Continue with the usual wordpress install in <code>http://your-host/install.php</code></li>
			</ol>

			<hr>

			<?php $offline = !@fopen("http://www.google.com:80/","r"); ?>
			<?php if($offline): ?>
				<button class="btn btn-disabled" type="submit">Check your internet connection</button>
			<?php else: ?>
				<h2>Settings</h2>
				<form method="post" action="">
					<div class="form-group">
						<label for="lang">Language code (e.g. en or de)</label>
						<select class="form-control" name="lang">
							<option value="en">English (American)</option>
							<option value="de_DE">German</option>
							<option value="af">Afrikaans</option>
							<option value="ak">Akan</option>
							<option value="sq">Albanian</option>
							<option value="arq">Algerian Arabic</option>
							<option value="am">Amharic</option>
							<option value="ar">Arabic</option>
							<option value="hy">Armenian</option>
							<option value="rup_MK">Aromanian</option>
							<option value="frp">Arpitan</option>
							<option value="as">Assamese</option>
							<option value="az">Azerbaijani</option>
							<option value="az_TR">Azerbaijani (Turkey)</option>
							<option value="bcc">Balochi Southern</option>
							<option value="ba">Bashkir</option>
							<option value="eu">Basque</option>
							<option value="bel">Belarusian</option>
							<option value="bn_BD">Bengali</option>
							<option value="bs_BA">Bosnian</option>
							<option value="bre">Breton</option>
							<option value="bg_BG">Bulgarian</option>
							<option value="ca">Catalan</option>
							<option value="bal">Catalan (Balear)</option>
							<option value="ceb">Cebuano</option>
							<option value="zh_CN">Chinese (China)</option>
							<option value="zh_HK">Chinese (Hong Kong)</option>
							<option value="zh_TW">Chinese (Taiwan)</option>
							<option value="co">Corsican</option>
							<option value="hr">Croatian</option>
							<option value="cs_CZ">Czech</option>
							<option value="da_DK">Danish</option>
							<option value="dv">Dhivehi</option>
							<option value="nl_NL">Dutch</option>
							<option value="nl_BE">Dutch (Belgium)</option>
							<option value="dzo">Dzongkha</option>
							<option value="art_xemoji">Emoji</option>
							<option value="en_AU">English (Australia)</option>
							<option value="en_CA">English (Canada)</option>
							<option value="en_NZ">English (New Zealand)</option>
							<option value="en_ZA">English (South Africa)</option>
							<option value="en_GB">English (UK)</option>
							<option value="eo">Esperanto</option>
							<option value="et">Estonian</option>
							<option value="fo">Faroese</option>
							<option value="fi">Finnish</option>
							<option value="fr_BE">French (Belgium)</option>
							<option value="fr_CA">French (Canada)</option>
							<option value="fr_FR">French (France)</option>
							<option value="fy">Frisian</option>
							<option value="fur">Friulian</option>
							<option value="fuc">Fulah</option>
							<option value="gl_ES">Galician</option>
							<option value="ka_GE">Georgian</option>
							<option value="de_DE">German</option>
							<option value="de_CH">German (Switzerland)</option>
							<option value="el">Greek</option>
							<option value="kal">Greenlandic</option>
							<option value="gn">Guaraní</option>
							<option value="gu">Gujarati</option>
							<option value="haw_US">Hawaiian</option>
							<option value="haz">Hazaragi</option>
							<option value="he_IL">Hebrew</option>
							<option value="hi_IN">Hindi</option>
							<option value="hu_HU">Hungarian</option>
							<option value="is_IS">Icelandic</option>
							<option value="ido">Ido</option>
							<option value="id_ID">Indonesian</option>
							<option value="ga">Irish</option>
							<option value="it_IT">Italian</option>
							<option value="ja">Japanese</option>
							<option value="jv_ID">Javanese</option>
							<option value="kab">Kabyle</option>
							<option value="kn">Kannada</option>
							<option value="kk">Kazakh</option>
							<option value="km">Khmer</option>
							<option value="kin">Kinyarwanda</option>
							<option value="ky_KY">Kirghiz</option>
							<option value="ko_KR">Korean</option>
							<option value="ckb">Kurdish (Sorani)</option>
							<option value="lo">Lao</option>
							<option value="lv">Latvian</option>
							<option value="li">Limburgish</option>
							<option value="lin">Lingala</option>
							<option value="lt_LT">Lithuanian</option>
							<option value="lb_LU">Luxembourgish</option>
							<option value="mk_MK">Macedonian</option>
							<option value="mg_MG">Malagasy</option>
							<option value="ms_MY">Malay</option>
							<option value="ml_IN">Malayalam</option>
							<option value="mri">Maori</option>
							<option value="mr">Marathi</option>
							<option value="xmf">Mingrelian</option>
							<option value="mn">Mongolian</option>
							<option value="me_ME">Montenegrin</option>
							<option value="ary">Moroccan Arabic</option>
							<option value="my_MM">Myanmar (Burmese)</option>
							<option value="ne_NP">Nepali</option>
							<option value="nb_NO">Norwegian (Bokmål)</option>
							<option value="nn_NO">Norwegian (Nynorsk)</option>
							<option value="oci">Occitan</option>
							<option value="ory">Oriya</option>
							<option value="os">Ossetic</option>
							<option value="ps">Pashto</option>
							<option value="fa_IR">Persian</option>
							<option value="fa_AF">Persian (Afghanistan)</option>
							<option value="pl_PL">Polish</option>
							<option value="pt_BR">Portuguese (Brazil)</option>
							<option value="pt_PT">Portuguese (Portugal)</option>
							<option value="pa_IN">Punjabi</option>
							<option value="rhg">Rohingya</option>
							<option value="ro_RO">Romanian</option>
							<option value="roh">Romansh Vallader</option>
							<option value="ru_RU">Russian</option>
							<option value="rue">Rusyn</option>
							<option value="sah">Sakha</option>
							<option value="sa_IN">Sanskrit</option>
							<option value="srd">Sardinian</option>
							<option value="gd">Scottish Gaelic</option>
							<option value="sr_RS">Serbian</option>
							<option value="szl">Silesian</option>
							<option value="snd">Sindhi</option>
							<option value="si_LK">Sinhala</option>
							<option value="sk_SK">Slovak</option>
							<option value="sl_SI">Slovenian</option>
							<option value="so_SO">Somali</option>
							<option value="azb">South Azerbaijani</option>
							<option value="es_AR">Spanish (Argentina)</option>
							<option value="es_CL">Spanish (Chile)</option>
							<option value="es_CO">Spanish (Colombia)</option>
							<option value="es_GT">Spanish (Guatemala)</option>
							<option value="es_MX">Spanish (Mexico)</option>
							<option value="es_PE">Spanish (Peru)</option>
							<option value="es_PR">Spanish (Puerto Rico)</option>
							<option value="es_ES">Spanish (Spain)</option>
							<option value="es_VE">Spanish (Venezuela)</option>
							<option value="su_ID">Sundanese</option>
							<option value="sw">Swahili</option>
							<option value="sv_SE">Swedish</option>
							<option value="gsw">Swiss German</option>
							<option value="tl">Tagalog</option>
							<option value="tah">Tahitian</option>
							<option value="tg">Tajik</option>
							<option value="tzm">Tamazight (Central Atlas)</option>
							<option value="ta_IN">Tamil</option>
							<option value="ta_LK">Tamil (Sri Lanka)</option>
							<option value="tt_RU">Tatar</option>
							<option value="te">Telugu</option>
							<option value="th">Thai</option>
							<option value="bo">Tibetan</option>
							<option value="tir">Tigrinya</option>
							<option value="tr_TR">Turkish</option>
							<option value="tuk">Turkmen</option>
							<option value="twd">Tweants</option>
							<option value="ug_CN">Uighur</option>
							<option value="uk">Ukrainian</option>
							<option value="ur">Urdu</option>
							<option value="uz_UZ">Uzbek</option>
							<option value="vi">Vietnamese</option>
							<option value="wa">Walloon</option>
							<option value="cy">Welsh</option>
							<option value="yor">Yoruba</option>
						</select>
					</div>
					<div class="form-group">
						<label for="version">Version (default: 'latest')</label>
						<input type="text" id="version" class="form-control" value="latest" name="version" placeholder="4.4.4">
					</div>
					<div class="form-group">
						<label for="core_dir">Core dir name (default 'core')</label>
						<input type="text" id="core_dir" class="form-control" value="core" name="core_dir" placeholder="use default: core">
					</div>
					<div class="form-group">
						<label for="content_dir">content dir (default 'wp-content')</label>
						<input type="text" id="content_dir" class="form-control" value="wp-content" name="content_dir" placeholder="use default: wp-content">
					</div>
					<div class="form-group">
						<label for="upload_dir">upload dir (default 'wp-content/upload')</label>
						<input type="text" id="upload_dir" class="form-control" value="" name="upload_dir" placeholder="use default: wp-content/uploads">
					</div>
					<div class="form-group">
						<label for="runtimes">additional runtime environments (comma separated)</label>
						<input type="text" class="form-control" value="" name="runtimes" placeholder="staging, preproduction">
					</div>
					
					<button class="btn btn-primary" type="submit" name="go" value="go">Click here to start the installation</button>
				</form>
			<?php endif; ?>

		<?php else: ?>
			<div class="monospaced">
				<?php $wpi = new WPInstall($_POST["lang"], $_POST["core_dir"], $_POST["content_dir"], $_POST["runtimes"], $_POST["upload_dir"], $_POST["version"]); ?>
				<hr><span style='color:red;font-weight:bold'>
				Don't forget to delete the following files<br>
				<ol>
					<li><code>_install.php</code></li>
					<li><code>_wp-config-ENV-SAMPLE.php</code></li>
					<li><code>_wp-config-SAMPLE.php</code></li>
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
		if ($version == 'latest') {
			if($lang == "en") {
				$this->_wp_zip_url = "https://wordpress.org/latest.zip";
			} else {
				$this->_wp_zip_url = "https://".$lang.".wordpress.org/latest-".$lang.".zip";
			}
		} else {
			if($lang == "en") {
				$this->_wp_zip_url = "https://wordpress.org/wordpress-".$version.".zip";
			} else {
				$this->_wp_zip_url = "https://".$lang.".wordpress.org/wordpress-".$version."-".$lang.".zip";
			}
		}
		$this->debug($this->_wp_zip_url);
		// ignore empty runtimes
		if(!empty($runtimes_str)) {
			$runtimes = array_map('trim', explode(",",$runtimes_str));
		} else {
			$runtimes = array();
		}
		
		$this->head("Let's go!");
		if ($upload_name == $content_name.'/uploads' || $upload_name == '') { 
			$custom_upload_dir = false;
		} else {
			$custom_upload_dir = true;
		}
		if ($core_name == '') { 		$core_name = 'core'; }
		if ($content_name == '') { 		$content_name = 'wp-content'; }
		if (!$custom_upload_dir) { 		$upload_name = 'wp-content/uploads'; }
		if ($runtimes_str == '') { 		$runtimes_str = 'local, live'; }
		else { $runtimes_str = 'local, live, ' . $runtimes_str; }
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
