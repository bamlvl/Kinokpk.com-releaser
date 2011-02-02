<?php
/**
 * Installer for 3.30
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
define('ROOT_PATH',str_replace('install','',dirname(__FILE__)));

define("REL_CACHEDRIVER",'native');

if ($_GET['setlang']) {
	setcookie('lang',(string)$_GET['setlang']);
	print('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><a href="index.php">Продолжить / Continue / Продовжити</a></html>');
	die();
}
if (!$_COOKIE['lang'] || (strlen($_COOKIE['lang'])>2)) {
	print("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /></head><h1>Выберите язык / Choose a language / Виберіть мову: <a href=\"index.php?setlang=ru\">Русский</a>, <a href=\"index.php?setlang=en\">English</a>, <a href=\"index.php?setlang=ua\">Український</a></h1></html>");
	die();
}

require_once(ROOT_PATH.'include/bittorrent.php');

$step = (int)$_GET['step'];

$REL_CACHE->set('system','seorules',array());
/* @var object links parser/adder/changer for seo */
require_once(ROOT_PATH . 'classes/seo/seo.class.php');
$REL_SEO = new REL_SEO();

$REL_CONFIG['lang'] = substr(trim((string)$_COOKIE['lang']),0,2);
$REL_CONFIG['static_language'] = 'ru=install/lang/ru.lang,en=install/lang/en.lang,ua=install/lang/ua.lang';
/* @var object language system */
require_once(ROOT_PATH . 'classes/lang/lang.class.php');
$REL_LANG = new REL_LANG($REL_CONFIG);
//var_dump($REL_LANG->lang);
function headers2() {
	global $step, $REL_LANG;
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>'.$REL_LANG->_("Kinokpk.com releaser 3.30 installer").', '.$REL_LANG->_("step").': '.$step.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>');

	if (ini_get("register_globals")) die('<font color="red" size="20">'.$REL_LANG->_("Turn off register globals, noob!").'</font>');

}

function footers() {
	global $REL_LANG;
	print('<hr /><div align="right">'.$REL_LANG->_("Kinokpk.com releaser 3.30 installer").'</div></body></html>');
}

function cont($step) {
	global $REL_LANG;
	print '<a href="index.php?step='.$step.'">'.$REL_LANG->_("Continue").'</a>';

}

function hr() {
	print '<hr/>';
}

headers2();

if (!$step) {
	print $REL_LANG->_("You must agree with the following GNU GPLv3 licence to continue");
	hr();
	print('<iframe width="100%" height="300px" src="gnu.html">GNU</iframe>');
	hr();
	print $REL_LANG->_("Please do not forget to create <b>empty</b> database before installation");
	hr();
	print $REL_LANG->_("Next step will check your system and file/folder permissions to be ready for installation");
	hr();
	cont(1);
}



elseif ($step==1){



	print "<h1 align=\"center\">{$REL_LANG->_('Installer is checking your settings')}</h1><hr/>";

	print "PHP {$REL_LANG->_('Version')} >= 5.2.3: ".(((version_compare(PHP_VERSION,"5.2.3",'>'))||(version_compare(PHP_VERSION,"5.2.3") === 1))?$REL_LANG->_('Supported'):$REL_LANG->_('Not supported'))."<br/>";

	print "MySQL {$REL_LANG->_('available')}: ".(function_exists("mysql_connect")?$REL_LANG->_('Supported'):$REL_LANG->_('Not supported'))."<br/>";

	print "Zlib {$REL_LANG->_('available')}: ".(extension_loaded("zlib")?$REL_LANG->_('Supported'):$REL_LANG->_('Not supported'))."<br/>";

	print "Safe Mode <b>{$REL_LANG->_('disabled')}</b>: ".(ini_get("safe_mode")?$REL_LANG->_('No'):$REL_LANG->_('Yes'))."<br/>";

	print "Iconv {$REL_LANG->_('available')}: ".(function_exists("iconv")?$REL_LANG->_('Supported'):$REL_LANG->_('Not supported'))."<hr/>";

	print "PHP-XML {$REL_LANG->_('available')}: ".(class_exists("DOMDocument")?$REL_LANG->_('Supported'):$REL_LANG->_('Not supported'))."<hr/>";



	$important_files = array(

	ROOT_PATH.'torrents/',

	ROOT_PATH.'avatars/',

	ROOT_PATH.'cache/',

	ROOT_PATH.'Sitemap.xml',

	ROOT_PATH.'include/secrets.php',

	ROOT_PATH.'graffities/'

	);

	print($REL_LANG->_('Folder/files permission checking...'));
	hr();

	foreach($important_files as $file){



		if(!file_exists($file) || !is_writable($file)){

			print "$file: {$REL_LANG->_('Invalid chmod')} ({$REL_LANG->_('Please make sure that you set chmod 666 to files and 777 to folders, or these files or folders exist')})<br/>";

		}

		elseif(is_writable($file)){

			print "$file:  {$REL_LANG->_('Okay, writeable/accessed')}<br/>";

		}

	}
	hr();

	print $REL_LANG->_('If one of these parametres is not supported, marked as invalid, it is not recommended to continue. Fix this issues, <a href="javascript:history.go(-1);">go back check again</a>');
	hr();
	print $REL_LANG->_("Next step will ask you to configure database connection");
	hr();
	cont(2);

}



elseif ($_GET['step'] == 2) {

	print "<h1 align=\"center\">{$REL_LANG->_('Database connection setting')}</h1><hr/>";

	print '<form action="index.php?step=3" method="POST">

<table><tr><td>'.$REL_LANG->_('Host').'</td><td><input type="text" name="mysql_host" value="localhost"></td></tr>

<tr><td>'.$REL_LANG->_('Database name').'</td><td><input type="text" name="mysql_db"></td></tr>

<tr><td>'.$REL_LANG->_('Database user').'</td><td><input type="text" name="mysql_user"></td></tr>

<tr><td>'.$REL_LANG->_('User password').'</td><td><input type="password" name="mysql_pass"></td></tr>

<tr><td>'.$REL_LANG->_('Database charset').'</td><td><input type="text" name="mysql_charset" value="utf8"></td></tr>

<tr><td>'.$REL_LANG->_('Cookie secret (used to make your site hash unique)').'</td><td><input type="text" name="cookie_secret"></td></tr>

<tr><td colspan="2"><input type="submit" value="'.$REL_LANG->_('Continue').'"></td></tr></table></form>';

	print $REL_LANG->_("On next step installer will try to connect to database, install tables and save configuration");
	hr();

}



elseif ($_GET['step'] == 3) {



	$mysql_host=$_POST['mysql_host'];

	$mysql_user=$_POST['mysql_user'];

	$mysql_db=$_POST['mysql_db'];

	$mysql_pass=$_POST['mysql_pass'];

	$mysql_charset=$_POST['mysql_charset'];

	$secret = $_POST['cookie_secret'];





	print $REL_LANG->_('Testing database connection...');
	hr();
	/* @var database object */
	require_once(ROOT_PATH . 'classes/database/database.class.php');
	$REL_DB = new REL_DB($mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset);

	print $REL_LANG->_('Installing releaser tables...');
	hr();

	$strings = file(ROOT_PATH."install/install.sql");

	$query = '';

	$query = '';
	foreach ($strings AS $string)
	{
		if (preg_match("/^\s?#/", $string) || !preg_match("/[^\s]/", $string))
		continue;
		else
		{
			$query .= $string;
			if (preg_match("/;\s?$/", $query))
			{
				$REL_DB->query($query) or die($REL_LANG->_("SQL error happened").' ['.mysql_errno().']: ' . mysql_error(). ',<hr/>'.$REL_LANG->_("Query").': '.$query.'<hr/>'.$REL_LANG->_('Empty database and <a href="javascript:history.go(-1);">try again</a> please'));
				$query = '';
			}
		}
	}

	print$REL_LANG->_('Tables installed');
	hr();
	print $REL_LANG->_('Saving configuration...');
	hr();

	$dbconfig = <<<HTML
<?php

/**

 * Passwords. Just for fun

 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html

 * @package Kinokpk.com releaser

 * @author ZonD80 <admin@kinokpk.com>

 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com

 * @link http://dev.kinokpk.com

 */



if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");



\$mysql_host = '$mysql_host';

\$mysql_user = '$mysql_user';

\$mysql_pass = '$mysql_pass';

\$mysql_db = '$mysql_db';

\$mysql_charset = '$mysql_charset';


define("COOKIE_SECRET",'$secret');
/**
 * Set cache driver, available "native" and "memcached" now
 * @var string
 */
define("REL_CACHEDRIVER",'native');
?>
HTML;



	if (file_put_contents(ROOT_PATH.'include/secrets.php', $dbconfig)) {
		print $REL_LANG->_('Configuration saved to file');
	} else {
		print $REL_LANG->_('Configuration DOES NOT saved to file due write error');
	}
	hr();
	print $REL_LANG->_("Next step will ask you to configure required parametres");
	hr();
	cont(4);

}



elseif($step==4) {

	/* @var database object */
	require_once(ROOT_PATH . 'classes/database/database.class.php');
	$REL_DB = new REL_DB($mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset);

	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$REL_DB->query("UPDATE cache_stats SET cache_value=".sqlesc(trim((string)$_POST['url']))." WHERE cache_name='defaultbaseurl'");
		print $REL_LANG->_('Site URL saved');
		hr();

	} else {
		print $REL_LANG->_('Please set/verify parametres, required for normal functionality');
		hr();
		print '<form action="?step=4" method="POST"><table>';
		print "<tr><td>{$REL_LANG->_('Site URL, without ending slash, e.g. http://www.torrentsbook.com')}</td><td><input type=\"text\" name=\"url\" value=\"http://{$_SERVER['HTTP_HOST']}\"></td></tr>";
		print '<tr><td colspan="2"><input type="submit" value="'.$REL_LANG->_('Save').'"/></td></tr></table></form>';
		footers();
		die();
	}
	print $REL_LANG->_("Next step will install languages");
	hr();
	cont(5);
}

elseif ($step==5) {
	/* @var database object */
	require_once(ROOT_PATH . 'classes/database/database.class.php');
	$REL_DB = new REL_DB($mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset);

	$REL_LANG->import_langfile(ROOT_PATH.'install/lang/import/en.lang','en');
	$REL_LANG->import_langfile(ROOT_PATH.'install/lang/import/ru.lang','ru');
	print $REL_LANG->_('Languages installed');
	hr();
	print $REL_LANG->_("Next step will clear caches and finalize update");
	hr();
	cont(6);
}

elseif($step==6) {
	$REL_CACHE->clearAllCache();
	print $REL_LANG->_('<h1>Installation complete. Please set chmod 0644 to "include/secrets.php" and remove "install" and "update" folders from your server for your safety</h1>');
	hr();
	print $REL_LANG->_('First registered user becomes System Operator. By default, CAPTCHA and other features deactivated, visit <b>configadmin.php</b>. For better perfomance, set up cronjobs via cron using <b>cronadmin.php</b>. To increase maximum availabe user notifications set <br/><pre>group_concat_max_len</pre> value in your mysql configuration at least to 1Mbyte');
	hr();
	print $REL_LANG->_("Donate to project:");
	?>
<p><pre>Вы всегда можете помочь материально создателю движка (по вашему желанию), реквизиты:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.деньги: 41001423787643,
Paypal: zond80@gmail.com</pre></p>
<hr />
<div align="right"><i>С уважением, разработчики Kinokpk.com releaser</i></div>
	<?php
}


footers();

?>