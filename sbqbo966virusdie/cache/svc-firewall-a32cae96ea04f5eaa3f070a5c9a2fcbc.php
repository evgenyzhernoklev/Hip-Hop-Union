<?php defined('SVC_HOST') || exit(); define('SVC_CLIENTLIB', '1.5.4'); define('STAMPFORMAT', 'Y-m-d H:i:s'); $slashes = function_exists('preg_match') && preg_match('/%(2f|5c)/i', $_SERVER['QUERY_STRING']); foreach (array_keys($_GET) as $_) if ((strlen($_) > 3) && (substr($_, 0, 3) != 'svc') && is_string($_GET[$_])) { if ($slashes) $_GET[$_] = strtr($_GET[$_], array('%2f' => '/', '%2F' => '/', '%5c' => '\\', '%5C' => '\\')); inlineDecode($_GET[$_]); }; unset($slashes); function svcDataQuery($svc = '', $section = '', $params = NULL, $options = NULL, &$cached = NULL) { if (!is_array($options)) $options = array(); $options['gzip'] = isset($options['gzip']) && $options['gzip']; $options['json'] = isset($options['json']) && $options['json']; $options['cacheReload'] = isset($options['cacheReload']) && $options['cacheReload']; $options['cacheTime'] = isset($options['cacheTime']) ? abs((int)$options['cacheTime']) : 300; $options['cacheFile'] = isset($options['cacheFile']) ? (string)$options['cacheFile'] : ''; $options['cacheClean'] = isset($options['cacheClean']) && $options['cacheClean']; $cacheable = $options['cacheTime'] && strlen($options['cacheFile']); $cached = $cacheable && !$options['cacheReload'] && is_file($options['cacheFile']) && filesize($options['cacheFile']) && (filemtime($options['cacheFile']) + $options['cacheTime'] >= time()); if ($cached) if (is_string($rawdata = file_get_contents($options['cacheFile'])) && (($data = svcDataQueryDecode($rawdata, $options['gzip'], $options['json'])) !== FALSE)) { $options['cacheClean'] && @unlink($options['cacheFile']); return $data; } else { @unlink($options['cacheFile']); }; $cached = FALSE; $url = SVC_QDATA.(strlen($svc) ? $svc.'/' : '').(strlen($section) ? $section.'.php' : '').'?'.SVC_QBASE.(is_array($params) && $params ? '&'.http_build_query($params) : (is_string($params) && strlen($params) ? '&'.$params : '')); $rawdata = defined('SVC_USECURL') && SVC_USECURL && curl_setopt($GLOBALS['svcCURL'], CURLOPT_URL, $url) ? curl_exec($GLOBALS['svcCURL']) : @file_get_contents($url, 0, $GLOBALS['svcContext']); if (!is_string($rawdata) || (($data = svcDataQueryDecode($rawdata, $options['gzip'], $options['json'])) === FALSE)) return FALSE; if ($cacheable) if ($options['cacheClean'] || (@file_put_contents($options['cacheFile'], $rawdata, LOCK_EX) !== strlen($rawdata))) if (is_file($options['cacheFile'])) @unlink($options['cacheFile']); return $data; }; function svcDataQueryDecode($data, $gzip = TRUE, $json = TRUE) { if (!is_string($data)) return FALSE; if ($gzip) if (!is_string($data = @gzinflate($data))) return FALSE; if ($json) { $data = @json_decode($data, TRUE); if (($data === FALSE) || ($data === NULL)) return FALSE; }; return $data; }; function inlineDecode(&$s) { $pref = (string)substr($s, 0, 5); if (!$p = strpos($pref, ':')) return TRUE; $pref = substr($pref, 0, $p); switch ($pref) { case 'B64': $s = base64_decode(substr($s, $p + 1)); return is_string($s); case 'HEX': $s = hex2bin(substr($s, $p + 1)); return is_string($s); case 'JSON': $s = json_decode(substr($s, $p + 1), TRUE); return !is_null($s); }; return TRUE; }; function formatDirName($path, $cDir = './', $rootDir = '/', $strict = FALSE) { $path = strtr(trim($path), '\\', '/'); $drive = ''; if (($_ = strpos($path, ':')) !== FALSE) { $drive = substr($path, 0, $_ + 1); $path = substr($path, $_ + 1); }; $root = strlen($path) && ($path[0] == '/') ? '/' : ''; $path = explode('/', trim($path, '/')); $ret = array(); foreach ($path as $part) if (strlen($part) && ($part != '.')) if (($part == '..') && ($strict || ($ret && (end($ret) != '..')))) array_pop($ret); else $ret[] = $part; $ret = $root.implode('/', $ret); if (!strlen($ret)) return $drive.$cDir; elseif ($ret == '/') return $drive.$rootDir; else return $drive.$ret.'/'; }; function splitTextLines($text, $skipEmpty = TRUE, $trimLines = TRUE, $addSplitChars = NULL) { $tr = array("\r" => ''); if (is_string($addSplitChars)) for ($i = 0, $l = strlen($addSplitChars); $i < $l; ++$i) $tr[$addSplitChars[$i]] = "\n"; $textTr = strtr($text, $tr); if (!( $skipEmpty || $trimLines )) return explode("\n", $textTr); $ret = array(); foreach (explode("\n", $textTr) as $v) { if ($trimLines) $v = trim($v); if (!$skipEmpty || strlen($v)) $ret[] = $v; }; return $ret; }; function removeDir($entry, &$counter = NULL, &$size = NULL, $leaveTopDir = FALSE) { if (!strlen($entry)) return FALSE; if (!is_dir($entry) || is_link($entry)) { ++$counter; $size += (float)filesize($entry); return unlink($entry); }; $entry .= '/'; if (!$dh = opendir($entry)) return FALSE; $err = FALSE; while (($obj = readdir($dh)) !== FALSE) if (($obj !== '.') && ($obj !== '..')) if (!removeDir($entry.$obj, $counter, $size, FALSE)) $err = TRUE; closedir($dh); if (!$leaveTopDir && !$err) if (!rmdir($entry)) $err = TRUE; return !$err; }; function file_safe_rewrite($filename, $data, $lock = FALSE, $context = NULL) { if (!is_string($data)) return FALSE; clearstatcache(); $exists = is_file($filename); if ($exists) { $fmode = (int)fileperms($filename); $backup = $filename.'.tmp'.rand(100, 999); if (!rename($filename, $backup)) return FALSE; }; if (file_put_contents($filename, $data, $lock ? LOCK_EX : 0, $context) === strlen($data)) { if ($exists) { unlink($backup); $fmode && chmod($filename, $fmode); }; return TRUE; } else { is_file($filename) && unlink($filename); if ($exists) { rename($backup, $filename); $fmode && chmod($filename, $fmode); }; return FALSE; }; }; function sortFileList($a, $b) { $ad = $a[0][strlen($a[0]) - 1] == '/'; $bd = $b[0][strlen($b[0]) - 1] == '/'; if ($ad && $bd) return strcmp($a[0], $b[0]); elseif ($ad) return -1; elseif ($bd) return 1; $_ = strcmp(pathinfo($a[0], PATHINFO_EXTENSION), pathinfo($b[0], PATHINFO_EXTENSION)); if ($_) return $_; else return strcmp($a[0], $b[0]); }; function getUserInfo($uid, $part = 'name', $default = '') { if (function_exists('posix_getpwuid') && is_int($uid)) { $user = posix_getpwuid($uid); return isset($user[$part]) ? $user[$part] : $default; } else return $default; }; function getGroupInfo($gid, $part = 'name', $default = '') { if (function_exists('posix_getgrgid') && is_int($gid)) { $group = posix_getgrgid($gid); return isset($group[$part]) ? $group[$part] : $default; } else return $default; }; function shortNumber($number, $precision = 2, $delimiter = ' ', $base = 1024) { $prefix = array('', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'); $number = (float)$number; $pow = $number ? min((int)log(abs($number), $base), count($prefix) - 1) : 0; return round($number / pow($base, $pow), $precision).$delimiter.$prefix[$pow]; }; function shortNumberParse($str, $base = 1024) { $str = strtoupper(trim((string)$str)); $num = (float)$str; if (!$num) return $num; $pow = array('K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5, 'E' => 6, 'Z' => 7, 'Y' => 8); for ($i = strlen($str) - 1; $i >= 0; --$i) if (isset($pow[$str[$i]])) $num *= pow($base, $pow[$str[$i]]); elseif (is_numeric($str[$i])) break; return $num; }; function fw_rating($ipf, $rating = NULL) { $f = FW_PATH_IPDB.'/'.$ipf; if (is_array($rating)) return file_put_contents($f, implode(',', $rating), LOCK_EX); if ( is_file($f) && is_string($r = file_get_contents($f)) && (count($r = explode(',', $r)) === 2) && ($r[1] > 0) ) { $r[0] = (int)$r[0]; $r[1] = (float)$r[1]; return $r; }; return array(0, 0.0); }; function fw_daystat($stat = NULL) { $date = date('Ymd'); $file = FW_PATH_LOGDB.'/'.substr($date, -2); if (is_array($stat) && $stat) { file_put_contents($file, implode(',', $stat), LOCK_EX); return $stat; } else { is_file($file) && is_string($r = file_get_contents($file)) && ($r = explode(',', $r)) && ($r[0] == $date) && (count($r) === 8) || ($r = array($date, 0, 0, 0, 0, 0, 0, 0)); return $r; }; }; function fw_init($base, $setEnv) { define('FW_VERSION', '$FW_VERSION'); define('FW_UPDATE_HOST', '$FW_UPDATE_HOST'); define('FW_PATH_BASE', $base); define('FW_PATH_SVCDIR', basename(dirname($base))); define('FW_PATH_BLOCKDB', FW_PATH_BASE.'/blocked'); define('FW_PATH_SCOREDB', FW_PATH_BASE.'/scoredb'); define('FW_PATH_LOGDB', FW_PATH_BASE.'/logdb'); define('FW_PATH_IPDB', FW_PATH_BASE.'/ipdb'); define('FW_PATH_IPWL', FW_PATH_BASE.'/ipwl'); define('FW_PATH_IPBL', FW_PATH_BASE.'/ipbl'); define('FW_TIME', time()); if ($setEnv) { define('FW_ERRORLEVEL', (int)error_reporting(0)); @date_default_timezone_set(is_string($_ = date_default_timezone_get()) && strlen($_) ? $_ : 'UTC'); ini_set('pcre.backtrack_limit', 10000000); } else { define('FW_ERRORLEVEL', (int)error_reporting()); }; }; function fw_deinit() { error_reporting(FW_ERRORLEVEL); return TRUE; }; function fw_iptofile($ip) { return trim(preg_replace('/[^a-z\d]+/i', '-', (string)$ip), '-'); }; function fw_logscorediff($ipf, $args) { if (!is_array($args)) return FALSE; $dir = FW_PATH_SCOREDB; if (!( is_dir($dir) || mkdir($dir, 0771, TRUE) )) return FALSE; if (!$args) return !is_file($dir.'/'.$ipf) || unlink($dir.'/'.$ipf); return file_put_contents($dir.'/'.$ipf, implode(':', $args)."\n", FILE_APPEND | LOCK_EX) !== FALSE; }; function fw_logblocked($ipf, $args) { if (!is_array($args)) return FALSE; $dir = FW_PATH_BLOCKDB.'/'.date('Ymd'); if (!( is_dir($dir) || mkdir($dir, 0771, TRUE) )) return FALSE; $s = implode(',', $args).','. $_SERVER['REQUEST_METHOD'].','. base64_encode((($_ = strpos($_SERVER['REQUEST_URI'], '?')) !== FALSE) ? substr($_SERVER['REQUEST_URI'], 0, $_) : $_SERVER['REQUEST_URI']).','. base64_encode($_SERVER['QUERY_STRING']).','; if ($_POST) { $_ = ''; foreach (array_keys($_POST) as $k) $_ .= $k.'='.(is_string($_POST[$k]) ? strlen($_POST[$k]) : (is_array($_POST[$k]) ? count($_POST[$k]) : '')).'&'; $s .= base64_encode(substr($_, 0, -1)); }; $s .= ','; if (is_file(FW_PATH_SCOREDB.'/'.$ipf)) { if (is_array($rows = file(FW_PATH_SCOREDB.'/'.$ipf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))) foreach ($rows as $row) $s .= $row.','; unlink(FW_PATH_SCOREDB.'/'.$ipf); }; $s .= "\n"; $new = !is_file($dir.'/'.$ipf); file_put_contents($dir.'/'.$ipf, $s, FILE_APPEND | LOCK_EX); return $new; }; function fw_file_safe_rewrite($filename, $data, $lock = FALSE, $context = NULL) { if (!is_string($data)) return FALSE; clearstatcache(); $exists = is_file($filename); if ($exists) { $fmode = (int)fileperms($filename); $backup = $filename.'.tmp'.rand(100, 999); if (!rename($filename, $backup)) return FALSE; }; if (file_put_contents($filename, $data, $lock ? LOCK_EX : 0, $context) === strlen($data)) { if ($exists) { unlink($backup); $fmode && chmod($filename, $fmode); }; return TRUE; } else { is_file($filename) && unlink($filename); if ($exists) { rename($backup, $filename); $fmode && chmod($filename, $fmode); }; return FALSE; }; }; function fw_removedir($entry, $leavetopdir = FALSE) { if (!strlen($entry)) return FALSE; if (!is_dir($entry) || is_link($entry)) return unlink($entry); $entry .= '/'; if (!$dh = opendir($entry)) return FALSE; $err = FALSE; while (($obj = readdir($dh)) !== FALSE) if (($obj != '.') && ($obj != '..')) if (!fw_removedir($entry.$obj, FALSE)) $err = TRUE; closedir($dh); if (!$leavetopdir) if (!rmdir($entry)) $err = TRUE; return !$err; }; class cmsDetect{const version='1.0.8';public static $cmsData=array('joomla'=>array('n'=>'Joomla!','t'=>'.:..','d'=>'components:includes:language:libraries:media:modules:plugins/system:templates','f'=>array(array('/index.php',''),array('includes/defines.php','/JPATH/'),array('includes/framework.php','/JPATH/'))),'wordpress'=>array('n'=>'Wordpress','t'=>'.:..','d'=>'wp-admin:wp-content:wp-includes','f'=>array(array('/index.php','/wp[_\-]/i'),array('wp-config.php','/wp[_\-]/i'),array('wp-blog-header.php','/wp[_\-]/i'),array('wp-load.php','/wp-config/'))),'dle'=>array('n'=>'Datalife engine','t'=>'.','d'=>'engine/classes:engine/inc:engine/modules:language:templates','f'=>array(array('/index.php','/dle_|datalife/i'),array('engine/init.php','/ENGINE_DIR/'))),'drupal-6-7'=>array('n'=>'Drupal 6.x/7.x','t'=>'.','d'=>'includes:modules:themes','f'=>array(array('index.php','/drupal/i'),array('includes/common.inc','/drupal/i'),array('includes/session.inc','/drupal/i'),array('includes/bootstrap.inc','/drupal/i'))),'drupal-8'=>array('n'=>'Drupal 8.x','t'=>'.','d'=>'core/includes:core/modules:core/themes:core/vendor','f'=>array(array('core/vendor/autoload.php',''),array('core/includes/common.inc','/drupal/i'),array('core/includes/bootstrap.inc','/drupal/i'),array('index.php','/DrupalKernel/i'))),'modx-evolution'=>array('n'=>'ModX Evolution','t'=>'.','d'=>'assets/plugins:assets/snippets:assets/templates','f'=>array(array('index.php','/\$modx->/i'))),'modx-revolution'=>array('n'=>'ModX Revolution','t'=>'.','d'=>'assets:connectors:core/cache:core/model/modx','f'=>array(array('core/model/modx/modx.class.php',''),array('index.php','/MODX_CORE_PATH.+modx/is'))),'bitrix'=>array('n'=>'1C Bitrix','t'=>'.','d'=>'bitrix/admin:bitrix/components:bitrix/modules:bitrix/php_interface','f'=>array(array('bitrix/php_interface/dbconn.php','/BX_/'),array('bitrix/modules/main/include/prolog_before.php','/BX_/'))),'yii2'=>array('n'=>'Yii framework 2.x','t'=>'.:..','d'=>'config:controllers:models:runtime:vendor:views','f'=>array(array('vendor/autoload.php',''),array('/index.php','/[^a-z\d]yii[^a-z\d]/i'))),'opencart'=>array('n'=>'OpenCart','t'=>'.','d'=>'catalog/controller:catalog/model:catalog/view:system/engine','f'=>array(array('/index.php','/[^a-z\d]DIR_SYSTEM[^a-z\d]/i'),array('system/engine/action.php','/[^a-z\d]DIR_APPLICATION[^a-z\d]/'),array('system/startup.php','/[^a-z\d]DIR_SYSTEM[^a-z\d]/'))),'cscart'=>array('n'=>'CS.cart','t'=>'.','d'=>'app/addons:app/controllers:app/functions:design:var','f'=>array(array('/index.php','/define\s*\(\s*[\'"]AREA[\'"]/'),array('config.php','/[\'"]BOOTSTRAP[\'"]/'),array('init.php','/DIR_ROOT.+fn_init_/s'))),'netcat'=>array('n'=>'NetCat','t'=>'.','d'=>'netcat/modules:netcat/require:netcat/system|netcat_files','f'=>array(array('/index.php','/NETCAT.+vars\.inc\.php/s'),array('vars.inc.php','/\$NC_.+\$NC_|netcat/s'))),'prestashop'=>array('n'=>'PrestaShop','t'=>'.','d'=>'classes:config:controllers:img:localization:modules','f'=>array(array('classes/PrestaShopAutoload.php',''),array('/index.php','/config\.inc\.php/'),array('config/defines.inc.php','/(_PS_[A-Z]+_DIR_.+){5,}/sU'),array('config/config.inc.php','/_PS_[A-Z]+_DIR_.+Configuration::get\([\'"]PS_/isU'))),'hostcms'=>array('n'=>'HostCms','t'=>'.','d'=>'hostcmsfiles:modules/core:templates','f'=>array(array('/index.php','/bootstrap\.php.+hostcms/is'),array('bootstrap.php','/HOSTCMS/'))),'umicms'=>array('n'=>'UMI.CMS','t'=>'.','d'=>'classes/modules:classes/system:libs/root-src:js:styles','f'=>array(array('classes/modules/system.php','/\sumi[a-z\d]+[:\(\)]/i'),array('classes/modules/core.php','/\sumi[a-z\d]+[:\(\)]/i'),array('libs/system.php','/\sumi[a-z\d]+[:\(\)]/i'),array('libs/config.php','/CURRENT_WORKING_DIR.*\sumi[a-z\d]+[:\(\)]/is'))),'amirocms'=>array('n'=>'Amiro.CMS','t'=>'.','d'=>'_admin/includes:_img:_local','f'=>array(array('/index.php',''),array('pages.php',''),array('ami_service.php','/\$AMI_.+AMI_Service.+AMI::/s'),array('cm_ini.php','/AMI_Service/i'))),'magento'=>array('n'=>'Magento','t'=>'.','d'=>'app/code:app/design:lib:media:var/cache','f'=>array(array('/index.php','/MAGENTO.+Mage\.php.+Mage:/is'),array('app/Mage.php','/class\s+Mage/i'))),'cmsmadesimple'=>array('n'=>'CMS Made Simple','t'=>'.','d'=>'lib/classes:modules:plugins:uploads','f'=>array(array('lib/autoloader.php','/function\s+cms_autoloader\(/i'),array('lib/classes/class.CmsApp.php','/class\s+CmsApp/i'),array('fileloc.php','/_LOCATION[\'"]/i'),array('index.php','/cmsms\(\)->/i'),array('include.php','/cmsms\(\)->/i'))),'xenforo'=>array('n'=>'XenForo forum','t'=>'.','d'=>'data:library/XenForo:styles','f'=>array(array('library/config.php','/\$config/i'),array('css.php','/XenForo.*XenForo/isU'),array('proxy.php','/XenForo.*XenForo/isU'),array('admin.php','/XenForo.*XenForo/isU'),array('library/XenForo/Autoloader.php','/XenForo.*XenForo/isU'))),'codeigniter'=>array('n'=>'CodeIgniter','t'=>'.','d'=>'application/config:application/controllers:application/models:application/views:system/core:system/database','f'=>array(array('system/core/CodeIgniter.php','/CodeIgniter/i'),array('index.php','/system_path.*application_folder.*BASEPATH.*CodeIgniter/is'))),'phpbb'=>array('n'=>'PhpBB forum','t'=>'.','d'=>'phpbb/config:phpbb/controller:phpbb/template:includes:language','f'=>array(array('phpbb/class_loader.php','/phpbb/i'),array('viewforum.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('viewtopic.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('search.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('index.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('common.php','/IN_PHPBB.+(phpbb_.+){5,}/s'))),'phpbb_old'=>array('n'=>'PhpBB forum (old version)','t'=>'.','d'=>'styles:includes:language:cache','f'=>array(array('viewforum.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('viewtopic.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('search.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('index.php','/IN_PHPBB.+(phpbb_.+){5,}/s'),array('common.php','/IN_PHPBB.+(phpbb_.+){5,}/s'))),'webasyst'=>array('n'=>'Webasyst','t'=>'.','d'=>'wa-apps:wa-config:wa-content:wa-system/api:wa-system/controller:wa-system/view','f'=>array(array('wa-system/autoload/waAutoload.class.php','/class\s+waAutoload/i'),array('index.php','/wa/i'),array('wa-config/SystemConfig.class.php','/wa/i'))),'webasyst-old'=>array('n'=>'Webasyst (old)','t'=>'.','d'=>'kernel/classes:kernel/includes:published:system','f'=>array(array('kernel/wbsinit.php','/WBS_.+\$wbs_/is'),array('system/init.php','/WBS_.+Wbs::/is'),array('index.php','/WebAsyst/i'))),'stressweb'=>array('n'=>'Stressweb','t'=>'.','d'=>'application/account:cache:config:dev:module:templates','f'=>array(array('dev/boot.php',''),array('application/main.php','/[^\.]STRESSWEB/i'),array('index.php','/[^\.]STRESSWEB/i'))),'diafan'=>array('n'=>'DiafanCMS','t'=>'.','d'=>'adm/includes:userfiles:includes/database:includes/cache:cache:modules:plugins','f'=>array(array('includes/diafan.php','/DIAFAN/i'),array('includes/core.php','/DIAFAN/i'),array('adm/index.php','/DIAFAN/i'),array('index.php','/DIAFAN/i'))),'vtiger'=>array('n'=>'Vtiger CRM','t'=>'.','d'=>'vtlib/Vtiger:modules/Vtiger:storage','f'=>array(array('index.php','/vtiger/i'),array('includes/main/WebUI.php','/class\s+Vtiger_WebUI/i'),array('vtlib/Vtiger/Module.php','/class\s+Vtiger_Module/i'))),'koobi'=>array('n'=>'Koobi CMS','t'=>'.','d'=>'class:functions:inc:system:templates','f'=>array(array('index.php','/koobi/i'),array('inc/init.php','#BASEDIR\s*\.\s*[\'"]/class/tpl/Koobi\.class\.php#i'))),'simpla'=>array('n'=>'Simpla','t'=>'.','d'=>'simpla:api:design:payment:view','f'=>array(array('view/View.php','/Simpla/i'),array('view/IndexView.php','/function\s+fetch\(/i'),array('index.php','/\$view->fetch\(\)/i'),array('api/Simpla.php','/class\s+Simpla\s*\{/i'))),'vipbox'=>array('n'=>'VipBox (Engio)','t'=>'.','d'=>'core/functions:core/objects:admin/config:languages:plugins:templates:upload','f'=>array(array('core/common.php','/ENGIO.+PHPSM_ROOT_PATH/sU'),array('admin/index.php','/ENGIO.+PHPSM_ROOT_PATH/sU'),array('index.php','/ENGIO.+PHPSM_ROOT_PATH/sU'),array('core/config.php',''))));public static function detect(){$result=FALSE;foreach(self::$cmsData as $cmsID=>&$cms){if(!(isset($cms['n'])&&is_string($cms['n'])))$cms['n']=$cmsID;if(!(isset($cms['t'])&&is_string($cms['t'])&&strlen($cms['t'])))$cms['t']='.';if(!(isset($cms['d'])&&is_string($cms['d'])))$cms['d']='';if(!(isset($cms['f'])&&is_array($cms['f'])))$cms['f']=array();foreach(explode(':',$cms['t']) as $target){if(!(strlen($target)&&is_dir($target)&&is_readable($target)))continue;$d=1;if(strlen($cms['d']))foreach(explode('|',$cms['d']) as $dirs){$d=1;foreach(explode(':',$dirs)as$dir)if(strlen($dir)&&(!is_dir($target.'/'.$dir))){$d=0;break;};};if(!$d)continue;$path='';foreach($cms['f'] as $file){$path=(($file[0][0]=='/')?'.':$target.'/').$file[0];if(!is_file($path))continue 2;if(strlen($file[1])){if(!is_readable($path))continue 2;$text=@file_get_contents($path);if(!(is_string($text)&&preg_match($file[1],$text)))continue 2;};};$result=array('cms'=>$cmsID,'title'=>$cms['n'],'incfile'=>strlen($path)?$path:'index.php');break 2;};};unset($cms);return $result;}}; $task = isset($_GET['task']) ? trim($_GET['task']) : ''; $fw_directory = strtr(realpath('.'), '\\', '/').'/'.SVC_CDIR.'/firewall'; $fw_filepath = $fw_directory.'/index.php'; $fw_callcmd = '<'.'?php defined(\'FW_FILEPATH\')||define(\'FW_FILEPATH\',\''.addslashes($fw_filepath).'\')&&is_file(FW_FILEPATH)&&(include(FW_FILEPATH));?'.'>'; $fw_callcmd_pcre = '/<\?(php)?\s*defined\([\'"]FW_FILEPATH[\'"]\)\s*(OR|\|\|)\s*define\([\'"]FW_FILEPATH[\'"].{20,1024}\((include|require)\s*\(?FW_FILEPATH\)?\);\s*\?>/is'; $fw_installed = is_file($fw_filepath); fw_init($fw_directory, FALSE); if ($task === 'logs') { if (!$fw_installed) return ERR_SVC + 7; $result = array(); if (is_dir(FW_PATH_LOGDB)) { if (!is_array($files = @scandir(FW_PATH_LOGDB))) return ERR_SVC + 4; foreach ($files as $file) if ( (strlen($file) == 2) && ($file[0] != '.') && ((int)$file > 0) && ((int)$file <= 31) && (filesize(FW_PATH_LOGDB.'/'.$file) < 100) && is_string($_ = @file_get_contents(FW_PATH_LOGDB.'/'.$file)) && ($_ = explode(',', $_)) && (strlen($k = array_shift($_)) == '8') && (int)$k && $_ ) $result[$k] = $_; ksort($result); }; } elseif ($task === 'dayblocked') { if (!$fw_installed) return ERR_SVC + 7; $day = isset($_GET['day']) ? abs((int)$_GET['day']).'' : ''; if (strlen($day) !== 8) return ERR_SVC + 11; $result = array(); if (is_dir($dir = FW_PATH_BLOCKDB.'/'.$day) && is_array($ips = @scandir($dir))) foreach ($ips as $ip) if ($ip[0] != '.') $result[] = array(strtr($ip, '-', '.'), is_file($file = $dir.'/'.$ip) ? count(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) : 0); } elseif ($task === 'blockedip') { if (!$fw_installed) return ERR_SVC + 7; $ip = isset($_GET['ip']) ? fw_iptofile($_GET['ip']) : ''; if (!strlen($ip)) return ERR_SVC + 10; $day = isset($_GET['day']) ? abs((int)$_GET['day']).'' : ''; if (strlen($day) !== 8) return ERR_SVC + 11; $result = is_file($file = FW_PATH_BLOCKDB.'/'.$day.'/'.$ip) ? @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : array(); if (!is_array($result)) return ERR_SVC + 9; } elseif (in_array($task, array('bl_add', 'wl_add', 'bl_del', 'wl_del'))) { if (!$fw_installed) return ERR_SVC + 7; $ip = isset($_GET['ip']) ? fw_iptofile($_GET['ip']) : ''; if (!strlen($ip)) return ERR_SVC + 10; $dir = in_array($task, array('bl_add', 'bl_del')) ? FW_PATH_IPBL : FW_PATH_IPWL; $file = $dir.'/'.$ip; $result = array( 'task' => $task, 'ip' => $_GET['ip'].'', ); if (in_array($task, array('bl_add', 'wl_add'))) { if (is_file(FW_PATH_IPBL.'/'.$ip)) return ERR_SVC + 12; elseif (is_file(FW_PATH_IPWL.'/'.$ip)) return ERR_SVC + 13; if (!( is_dir($dir) || @mkdir($dir, 0771) )) return ERR_SVC + 8; if (@file_put_contents($file, '') === FALSE) return ERR_SVC + 8; } else { if (is_file($file) && !@unlink($file)) return ERR_SVC + 8; }; } elseif (in_array($task, array('bl_get', 'wl_get'))) { if (!$fw_installed) return ERR_SVC + 7; $result = array( 'b' => is_dir(FW_PATH_IPBL) ? @scandir(FW_PATH_IPBL) : array(), 'w' => is_dir(FW_PATH_IPWL) ? @scandir(FW_PATH_IPWL) : array(), ); if (!( is_array($result['b']) && is_array($result['w']) )) return ERR_SVC + 9; array_shift($result['b']); array_shift($result['b']); array_shift($result['w']); array_shift($result['w']); } elseif (in_array($task, array('status', 'enable', 'disable', 'remove'))) { $fw_cms = ''; $fw_targetfile = isset($_GET['targetfile']) ? trim($_GET['targetfile']) : ''; if (!strlen($fw_targetfile)) if ($fw_cms = cmsDetect::detect()) { $fw_targetfile = $fw_cms['incfile']; $fw_cms = $fw_cms['cms'].' / '.$fw_cms['title']; } else return ERR_SVC + 1; if (!( strlen($fw_targetfile) && is_file($fw_targetfile) && is_readable($fw_targetfile) && is_string($fw_targettext = @file_get_contents($fw_targetfile)) )) return ERR_SVC + 2; ini_set('pcre.backtrack_limit', 10000000); $fw_enabled = preg_match($fw_callcmd_pcre, $fw_targettext) && TRUE; $result = array( 'cms' => $fw_cms, 'targetfile' => $fw_targetfile, 'filepath' => $fw_filepath, 'installed' => $fw_installed ? 1 : 0, 'enabled' => $fw_enabled ? 1 : 0, 'enable' => 0, 'disable' => 0, 'remove' => 0, ); if ($task === 'enable') { $update = svcDataQuery(SVC_SVC, '', NULL, array()); if (!is_string($update)) return ERR_SVC + 5; if ( (strlen($update) < 4096) || !strpos($update, 'FW_FILEPATH') ) return ERR_SVC + 6; if (!( (is_dir($fw_directory) || mkdir($fw_directory, 0771)) && @file_safe_rewrite($fw_filepath, $update) )) return ERR_SVC + 4; if ( $fw_enabled || @file_safe_rewrite($fw_targetfile, $fw_callcmd.$fw_targettext, TRUE) ) $result['enable'] = 1; else return ERR_SVC + 3; } elseif (in_array($task, array('disable', 'remove'))) { if ( !$fw_enabled || is_string($fw_targettext = preg_replace($fw_callcmd_pcre, '', $fw_targettext)) && strlen($fw_targettext) && @file_safe_rewrite($fw_targetfile, $fw_targettext, TRUE) ) $result['disable'] = 1; else return ERR_SVC + 3; if ($task === 'remove') if ( !$fw_installed || @removeDir($fw_directory) ) $result['remove'] = 1; else return ERR_SVC + 4; }; } else return ERR_SVC + 0; echo json_encode($result); ?>