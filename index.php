<?php
require_once './lib/WPInstall.php'
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="./styles/style.css" type="text/css" media="screen">
		<title>Install Wordpress</title>
	</head>
	<body>

		<?php if (empty($_POST["go"])): ?>

			<h1>wordpress-boilerplate</h1>
			<p>This is a boilerplate and installation script for Wordpress with the WP core as a dependency and some other stuff.</p>

			<h2>Instructions</h2>
			<ol>
				<li>Customize settings and run installer</li>
				<li>Then delete this installer directory!</li>
				<li>Double check <code>wp-config.php</code> and edit your runtime configs</li>
				<li>Continue with the usual wordpress install in <code>http://your-host/install.php</code></li>
			</ol>

			<hr>

			<?php $offline = !@fopen("http://www.google.com:80/", "r");?>
			<?php if ($offline): ?>
				<button class="btn btn-disabled" type="submit">Check your internet connection</button>
			<?php else: ?>
				<h2>Settings</h2>
				<form method="post" action="">
					<div class="form-group">
						<label for="lang">Language code (e.g. en or de)</label>
						<select class="form-control" name="lang">
							<option value="de_DE_formal">German (formal)</option>
							<option value="de_DE">German (informal)</option>
							<option value="en">English (American)</option>
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
						<label for="core_dir">Core dir name</label>
						<input type="text" id="core_dir" class="form-control" value="core" name="core_dir" placeholder="use default: core">
					</div>
					<div class="form-group">
						<label for="content_dir">content dir</label>
						<input type="text" id="content_dir" class="form-control" value="site" name="content_dir" placeholder="use default: site">
					</div>
					<div class="form-group">
						<label for="upload_dir">upload dir</label>
						<input type="text" id="upload_dir" class="form-control" value="file" name="upload_dir" placeholder="use default: file">
					</div>
					<div class="form-group">
						<label for="runtimes">runtime environments (comma separated)</label>
						<input type="text" class="form-control" value="local, staging, live" name="runtimes" placeholder="use default: local, staging, live">
					</div>

					<button class="btn btn-primary" type="submit" name="go" value="go">Click here to start the installation</button>
				</form>
			<?php endif;?>

		<?php else: ?>
			<div class="monospaced">
				<?php
					$wpi = new WPInstall();
					$wpi->install(__DIR__ . DIRECTORY_SEPARATOR . '..',
						$_POST["lang"],
						$_POST["core_dir"],
						$_POST["content_dir"],
						$_POST["runtimes"],
						$_POST["upload_dir"],
						$_POST["version"])
					?>
				<hr><span style='color:red;font-weight:bold'>
				Don't forget to delete the setup dir!<br>

			</div>
		<?php endif?>
</body>
</html>
