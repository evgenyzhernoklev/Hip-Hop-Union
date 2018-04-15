<?php defined('SVC_HOST') || exit(); define('SVC_CLIENTLIB', '1.5.4'); define('STAMPFORMAT', 'Y-m-d H:i:s'); $slashes = function_exists('preg_match') && preg_match('/%(2f|5c)/i', $_SERVER['QUERY_STRING']); foreach (array_keys($_GET) as $_) if ((strlen($_) > 3) && (substr($_, 0, 3) != 'svc') && is_string($_GET[$_])) { if ($slashes) $_GET[$_] = strtr($_GET[$_], array('%2f' => '/', '%2F' => '/', '%5c' => '\\', '%5C' => '\\')); inlineDecode($_GET[$_]); }; unset($slashes); function svcDataQuery($svc = '', $section = '', $params = NULL, $options = NULL, &$cached = NULL) { if (!is_array($options)) $options = array(); $options['gzip'] = isset($options['gzip']) && $options['gzip']; $options['json'] = isset($options['json']) && $options['json']; $options['cacheReload'] = isset($options['cacheReload']) && $options['cacheReload']; $options['cacheTime'] = isset($options['cacheTime']) ? abs((int)$options['cacheTime']) : 300; $options['cacheFile'] = isset($options['cacheFile']) ? (string)$options['cacheFile'] : ''; $options['cacheClean'] = isset($options['cacheClean']) && $options['cacheClean']; $cacheable = $options['cacheTime'] && strlen($options['cacheFile']); $cached = $cacheable && !$options['cacheReload'] && is_file($options['cacheFile']) && filesize($options['cacheFile']) && (filemtime($options['cacheFile']) + $options['cacheTime'] >= time()); if ($cached) if (is_string($rawdata = file_get_contents($options['cacheFile'])) && (($data = svcDataQueryDecode($rawdata, $options['gzip'], $options['json'])) !== FALSE)) { $options['cacheClean'] && @unlink($options['cacheFile']); return $data; } else { @unlink($options['cacheFile']); }; $cached = FALSE; $url = SVC_QDATA.(strlen($svc) ? $svc.'/' : '').(strlen($section) ? $section.'.php' : '').'?'.SVC_QBASE.(is_array($params) && $params ? '&'.http_build_query($params) : (is_string($params) && strlen($params) ? '&'.$params : '')); $rawdata = defined('SVC_USECURL') && SVC_USECURL && curl_setopt($GLOBALS['svcCURL'], CURLOPT_URL, $url) ? curl_exec($GLOBALS['svcCURL']) : @file_get_contents($url, 0, $GLOBALS['svcContext']); if (!is_string($rawdata) || (($data = svcDataQueryDecode($rawdata, $options['gzip'], $options['json'])) === FALSE)) return FALSE; if ($cacheable) if ($options['cacheClean'] || (@file_put_contents($options['cacheFile'], $rawdata, LOCK_EX) !== strlen($rawdata))) if (is_file($options['cacheFile'])) @unlink($options['cacheFile']); return $data; }; function svcDataQueryDecode($data, $gzip = TRUE, $json = TRUE) { if (!is_string($data)) return FALSE; if ($gzip) if (!is_string($data = @gzinflate($data))) return FALSE; if ($json) { $data = @json_decode($data, TRUE); if (($data === FALSE) || ($data === NULL)) return FALSE; }; return $data; }; function inlineDecode(&$s) { $pref = (string)substr($s, 0, 5); if (!$p = strpos($pref, ':')) return TRUE; $pref = substr($pref, 0, $p); switch ($pref) { case 'B64': $s = base64_decode(substr($s, $p + 1)); return is_string($s); case 'HEX': $s = hex2bin(substr($s, $p + 1)); return is_string($s); case 'JSON': $s = json_decode(substr($s, $p + 1), TRUE); return !is_null($s); }; return TRUE; }; function formatDirName($path, $cDir = './', $rootDir = '/', $strict = FALSE) { $path = strtr(trim($path), '\\', '/'); $drive = ''; if (($_ = strpos($path, ':')) !== FALSE) { $drive = substr($path, 0, $_ + 1); $path = substr($path, $_ + 1); }; $root = strlen($path) && ($path[0] == '/') ? '/' : ''; $path = explode('/', trim($path, '/')); $ret = array(); foreach ($path as $part) if (strlen($part) && ($part != '.')) if (($part == '..') && ($strict || ($ret && (end($ret) != '..')))) array_pop($ret); else $ret[] = $part; $ret = $root.implode('/', $ret); if (!strlen($ret)) return $drive.$cDir; elseif ($ret == '/') return $drive.$rootDir; else return $drive.$ret.'/'; }; function splitTextLines($text, $skipEmpty = TRUE, $trimLines = TRUE, $addSplitChars = NULL) { $tr = array("\r" => ''); if (is_string($addSplitChars)) for ($i = 0, $l = strlen($addSplitChars); $i < $l; ++$i) $tr[$addSplitChars[$i]] = "\n"; $textTr = strtr($text, $tr); if (!( $skipEmpty || $trimLines )) return explode("\n", $textTr); $ret = array(); foreach (explode("\n", $textTr) as $v) { if ($trimLines) $v = trim($v); if (!$skipEmpty || strlen($v)) $ret[] = $v; }; return $ret; }; function removeDir($entry, &$counter = NULL, &$size = NULL, $leaveTopDir = FALSE) { if (!strlen($entry)) return FALSE; if (!is_dir($entry) || is_link($entry)) { ++$counter; $size += (float)filesize($entry); return unlink($entry); }; $entry .= '/'; if (!$dh = opendir($entry)) return FALSE; $err = FALSE; while (($obj = readdir($dh)) !== FALSE) if (($obj !== '.') && ($obj !== '..')) if (!removeDir($entry.$obj, $counter, $size, FALSE)) $err = TRUE; closedir($dh); if (!$leaveTopDir && !$err) if (!rmdir($entry)) $err = TRUE; return !$err; }; function file_safe_rewrite($filename, $data, $lock = FALSE, $context = NULL) { if (!is_string($data)) return FALSE; clearstatcache(); $exists = is_file($filename); if ($exists) { $fmode = (int)fileperms($filename); $backup = $filename.'.tmp'.rand(100, 999); if (!rename($filename, $backup)) return FALSE; }; if (file_put_contents($filename, $data, $lock ? LOCK_EX : 0, $context) === strlen($data)) { if ($exists) { unlink($backup); $fmode && chmod($filename, $fmode); }; return TRUE; } else { is_file($filename) && unlink($filename); if ($exists) { rename($backup, $filename); $fmode && chmod($filename, $fmode); }; return FALSE; }; }; function sortFileList($a, $b) { $ad = $a[0][strlen($a[0]) - 1] == '/'; $bd = $b[0][strlen($b[0]) - 1] == '/'; if ($ad && $bd) return strcmp($a[0], $b[0]); elseif ($ad) return -1; elseif ($bd) return 1; $_ = strcmp(pathinfo($a[0], PATHINFO_EXTENSION), pathinfo($b[0], PATHINFO_EXTENSION)); if ($_) return $_; else return strcmp($a[0], $b[0]); }; function getUserInfo($uid, $part = 'name', $default = '') { if (function_exists('posix_getpwuid') && is_int($uid)) { $user = posix_getpwuid($uid); return isset($user[$part]) ? $user[$part] : $default; } else return $default; }; function getGroupInfo($gid, $part = 'name', $default = '') { if (function_exists('posix_getgrgid') && is_int($gid)) { $group = posix_getgrgid($gid); return isset($group[$part]) ? $group[$part] : $default; } else return $default; }; function shortNumber($number, $precision = 2, $delimiter = ' ', $base = 1024) { $prefix = array('', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'); $number = (float)$number; $pow = $number ? min((int)log(abs($number), $base), count($prefix) - 1) : 0; return round($number / pow($base, $pow), $precision).$delimiter.$prefix[$pow]; }; function shortNumberParse($str, $base = 1024) { $str = strtoupper(trim((string)$str)); $num = (float)$str; if (!$num) return $num; $pow = array('K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5, 'E' => 6, 'Z' => 7, 'Y' => 8); for ($i = strlen($str) - 1; $i >= 0; --$i) if (isset($pow[$str[$i]])) $num *= pow($base, $pow[$str[$i]]); elseif (is_numeric($str[$i])) break; return $num; }; class svcRestore { const version = '1.1.2'; protected static $baseDir = '.'; protected static $backupID = 0; protected static $dir = ''; protected static $pathPrefix = ''; protected static $prefixLen = 0; protected static function _delete($dir) { foreach (scandir($dir) as $item) if ($item[0] != '.') @unlink($dir.'/'.$item); return @rmdir($dir) && TRUE; } public static function init($basedir = '.', $time = 0, $pathPrefix = '') { self::$baseDir = rtrim(trim($basedir), '\\/'); if (!strlen(self::$baseDir)) self::$baseDir = '.'; $time = (int)$time; self::$backupID = ($time > 0) && ($time <= time()) ? $time : time(); self::$dir = self::$baseDir.'/'.self::$backupID.'/'; self::$pathPrefix = strlen($pathPrefix) && is_string($pathPrefix = realpath($pathPrefix)) && strlen($pathPrefix) ? rtrim($pathPrefix, '\\/').DIRECTORY_SEPARATOR : ''; self::$prefixLen = strlen(self::$pathPrefix); return self::$backupID; } public static function pushFile($file) { if (!$file = realpath($file)) return FALSE; if (self::$prefixLen && (strlen($file) > self::$prefixLen) && (substr($file, 0, self::$prefixLen) == self::$pathPrefix)) $file = strtr(substr($file, self::$prefixLen), '\\', '/'); else $file = strtr($file, '\\', '/'); if (!( strlen(self::$dir) && (is_dir(self::$dir) || mkdir(self::$dir, 0751, TRUE)) )) return FALSE; $newname = md5($file); $newpath = self::$dir.$newname; $fsize = filesize($file); $mtime = filemtime($file); $fmode = fileperms($file); $ftext = @file_get_contents($file); return @( is_string($ftext) && (strlen($ftext) === $fsize) && is_string($ftext = gzdeflate($ftext)) && (file_put_contents($newpath, $ftext) === strlen($ftext)) && touch($newpath, $mtime) && file_put_contents(self::$dir.'_files.ini', "[$newname]\npath=\"$file\"\nsize=$fsize\ntime=$mtime\nmode=$fmode\ngzip=1\n", FILE_APPEND) && TRUE ); } public static function translateFileName($file) { if (!( strlen(self::$dir) && is_dir(self::$dir) )) return FALSE; return self::$dir.md5($file); } public static function getList($parseItems = FALSE) { $ret = array(); if (!is_dir(self::$baseDir)) return $ret; if (!$dh = @opendir(self::$baseDir)) return $ret; while (is_string($item = readdir($dh))) if ( is_numeric($item) && ($item[0] != '.') && ($item = (int)$item) && is_file(self::$baseDir.'/'.$item.'/_files.ini') ) if ($parseItems) $ret[$item] = self::getItem($item); else $ret[$item] = $item; closedir($dh); return $ret; } public static function getItem($backup) { $backup = (int)$backup; if (!( is_file($f = self::$baseDir.'/'.$backup.'/_files.ini') && is_array($ret = parse_ini_file($f, TRUE)) )) return FALSE; return $ret; } public static function delete($backup) { if (!self::getItem((int)$backup)) return NULL; if (!self::_delete(self::$baseDir.'/'.(int)$backup)) return FALSE; return TRUE; } public static function clean($maxage = 0) { $ret = array(); $maxage = abs((int)$maxage); $time = time(); foreach (self::getList() as $v) if ( !$maxage || ((int)$v <= $time - $maxage) ) if (self::_delete(self::$baseDir.'/'.$v)) $ret[$v] = (int)$v; return $ret; } public static function restore($backup, $file = '') { $backup = (int)$backup; if (!$bdata = self::getItem($backup)) return FALSE; $ret = array('total' => count($bdata), 'restored' => 0, 'files' => array()); if (strlen($file)) { $fileid = md5($file); $bdata = isset($bdata[$fileid]) ? array($fileid => $bdata[$fileid]) : array(); }; foreach ($bdata as $k => $v) if (@( is_file($f = self::$baseDir.'/'.$backup.'/'.$k) && is_string($text = file_get_contents($f)) && (!isset($v['gzip']) || is_string($text = gzinflate($text))) && (strlen($text) === (int)$v['size']) && (is_dir($dir = dirname($v['path'])) || mkdir($dir, 0755, TRUE)) && (file_put_contents($v['path'], $text) === strlen($text)) && touch($v['path'], (int)$v['time']) && chmod($v['path'], (int)$v['mode']) && (filesize($v['path']) === (int)$v['size']) && TRUE )) { ++$ret['restored']; $ret['files'][$k] = $v; }; return $ret; } }; define('DSCAN_FILES', 1); define('DSCAN_DIRFIRST', 2); define('DSCAN_DIRLAST', 4); define('DSCAN_DOTS', 8); define('DSCAN_INCLUDEBASE', 16); define('DSCAN_FOLLOWLINKS', 32); define('DSCAN_NORMAL', DSCAN_FILES | DSCAN_DIRFIRST); class dirScanner { const version = '1.4.2'; protected $basedir = ''; protected $base = FALSE; protected $cd = ''; protected $flags = 0; protected $maxDepth = 0; protected $last = ''; protected $depth = -1; protected $h = array(); public static function create($dir, $flags = DSCAN_NORMAL, $maxDepth = 64) { $class = __CLASS__; $object = new $class(); if ($object->open($dir, $flags, $maxDepth)) return $object; else { unset($object); return FALSE; }; } public function __destruct() { $this->close(); } public function open($dir, $flags = DSCAN_NORMAL, $maxDepth = 64) { $this->close(); if (!strlen($dir)) $dir = './'; elseif ($dir[strlen($dir) - 1] != '/') $dir .= '/'; $this->basedir = ($dir == './') ? '' : $dir; $this->flags = (int)$flags; $this->base = ($this->flags & DSCAN_INCLUDEBASE) > 0; $this->maxDepth = $maxDepth; if ($this->h[0] = @opendir($dir)) { $this->depth = 0; return TRUE; } else { $this->h = array(); return FALSE; }; } public function close() { while ($this->depth >= 0) { $this->h[$this->depth] && closedir($this->h[$this->depth]); $this->depth--; }; $this->reset(); } protected function reset() { $this->basedir = ''; $this->base = FALSE; $this->cd = ''; $this->flags = 0; $this->maxDepth = 0; $this->last = ''; $this->depth = -1; $this->h = array(); } public function cdUp() { if ($this->depth < 0) return FALSE; $this->h[$this->depth] && closedir($this->h[$this->depth]); unset($this->h[$this->depth]); $this->depth--; if ($this->depth < 0) { $this->reset(); return FALSE; }; $this->cd = dirname($this->cd); if (in_array($this->cd, array('.', '/', '\\'))) $this->cd = ''; elseif (strlen($this->cd)) $this->cd .= '/'; return TRUE; } public function cd() { return $this->cd; } public function baseDir() { return $this->basedir; } public function depth() { return $this->depth; } public function last() { return $this->last; } public function isDir() { return strlen($this->last) && ($this->last[strlen($this->last) - 1] == '/'); } public function isLink() { return strlen($this->last) && is_link(($this->base ? '' : $this->basedir).$this->last); } public function read() { if ($this->depth < 0) return FALSE; while (TRUE) if ( !$this->h[$this->depth] || (($name = readdir($this->h[$this->depth])) === FALSE) ) { $cd = $this->cd; if (!$this->cdUp()) return FALSE; if ($this->flags & DSCAN_DIRLAST) return $this->last = ($this->base ? $this->basedir : '').$cd; } elseif (is_dir($this->basedir.$this->cd.$name) && (!is_link($this->basedir.$this->cd.$name) || ($this->flags & DSCAN_FOLLOWLINKS))) { if (($name == '.') || ($name == '..')) if ( ($this->flags & DSCAN_DOTS) && ($this->flags & (DSCAN_DIRFIRST | DSCAN_DIRLAST)) ) return $this->last = ($this->base ? $this->basedir : '').$this->cd.$name.'/'; else continue; $this->depth++; $this->cd .= $name.'/'; if ($this->depth > $this->maxDepth) $this->h[$this->depth] = FALSE; else $this->h[$this->depth] = @opendir($this->basedir.$this->cd); if ($this->flags & DSCAN_DIRFIRST) return $this->last = ($this->base ? $this->basedir : '').$this->cd; } else { if ($this->flags & DSCAN_FILES) return $this->last = ($this->base ? $this->basedir : '').$this->cd.$name; }; } public static function scan(&$list, $baseDir = '', $dirName = '', $flags = DSCAN_NORMAL, $callback = NULL, $filter = NULL) { if (!$cb = isset($callback) && is_callable($callback)) if (!is_array($list)) $list = array(); if (strlen($baseDir) && ($baseDir[strlen($baseDir) - 1] != '/')) $baseDir .= '/'; if (strlen($dirName) && ($dirName[strlen($dirName) - 1] != '/')) $dirName .= '/'; $dir = $baseDir.$dirName; if (!$dh = @opendir(strlen($dir) ? $dir : './')) return FALSE; $ret = 0; while (($name = readdir($dh)) !== FALSE) { $entry = (($flags & DSCAN_INCLUDEBASE) ? $baseDir : '').$dirName.$name; if (($name == '.') || ($name == '..')) { if ( ($flags & DSCAN_DOTS) && ($flags & (DSCAN_DIRFIRST | DSCAN_DIRLAST)) ) ++$ret && ($cb ? $callback($entry.'/') : ($list[] = $entry.'/')); } elseif (is_dir($dir.$name) && (!is_link($dir.$name) || ($flags & DSCAN_FOLLOWLINKS))) { if ($flags & DSCAN_DIRFIRST) ++$ret && ($cb ? $callback($entry.'/') : ($list[] = $entry.'/')); $ret += self::scan($list, $baseDir, $dirName.$name.'/', $flags, $callback, $filter); if ($flags & DSCAN_DIRLAST) ++$ret && ($cb ? $callback($entry.'/') : ($list[] = $entry.'/')); } elseif (!$filter || preg_match($filter, $name)) { if ($flags & DSCAN_FILES) ++$ret && ($cb ? $callback($entry) : ($list[] = $entry)); }; }; closedir($dh); return $ret; } }; define('VDB_TITLE', 0); define('VDB_SIGNATURE', 1); define('VDB_REPLACE', 2); define('VDB_CALLBACK', 3); define('VDB_INCURABLE', 4); define('VDB_DOUBT', 5); define('VDB_LAST', 6); define('VDB_FTYPES', 7); define('VDB_ACK', 8); define('VDB_EXC', 9); define('VDB_SUB', 10); define('VDB_ORDER', 11); define('RET_DETECTED', 1); define('RET_INCURABLE', 2); define('RET_DOUBT', 4); define('RET_REPLACED', 8); define('RET_DELETED', 16); define('RET_EBACKUP', 128); define('RET_EWRITE', 256); define('RET_EDELETE', 512); define('RET_EREAD', 1024); class avScanner { const version = '4.0.6'; const VDBVERSION = 2; protected static $cacheFile = ''; protected static $cacheTime = 0; protected static $vdbHost = ''; protected static $vdbID = 0; protected static $vdbApiKey = ''; protected static $userAgent = 'libavscanner'; public static $vdb = array(); public static function init($options, &$error = NULL) { if (!is_array($options)) { $error = 'Invalid options passed for init()'; return FALSE; }; foreach ($options as $key => $val) self::$$key = $val; if (!strlen(self::$vdbHost)) { $error = 'Invalid vdbHost configuration option'; return FALSE; }; return TRUE; } public static function loadVDB(&$error = NULL) { $vdbCached = strlen(self::$cacheFile) && (int)self::$cacheTime && is_file(self::$cacheFile) && filesize(self::$cacheFile) && (filemtime(self::$cacheFile) + (int)self::$cacheTime >= time()); if ($vdbCached) { $vdbURL = self::$cacheFile; $vdbJSON = file_get_contents($vdbURL); } else { $vdbURL = 'http://'.self::$vdbHost.'/data/rexplacer/vdb.php?'.http_build_query(array( 'apikey' => (string)self::$vdbApiKey, 'vdbid' => (int)self::$vdbID, 'vdbver' => self::VDBVERSION, 'from' => strtr(self::$userAgent, '/', '-'), ), '', '&'); if ((int)ini_get('allow_url_fopen')) { $vdbJSON = file_get_contents($vdbURL, 0, stream_context_create(array('http' => array( 'method' => 'GET', 'header' => implode("\r\n", array( 'Accept: *'.'/'.'*', 'Connection: Close', 'User-Agent: '.self::$userAgent, '' )), 'protocol_version' => 1.1, 'follow_location' => 1, 'max_redirects' => 3, 'timeout' => 30, 'ignore_errors' => FALSE, )))); } elseif (is_callable('curl_init')) { $curl = curl_init(); curl_setopt_array($curl, array( CURLOPT_URL => $vdbURL, CURLOPT_RETURNTRANSFER => TRUE, CURLOPT_USERAGENT => self::$userAgent, CURLOPT_FOLLOWLOCATION => TRUE, CURLOPT_MAXREDIRS => 3, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_FAILONERROR => TRUE, CURLOPT_SSL_VERIFYPEER => FALSE, )); $vdbJSON = curl_exec($curl); curl_close($curl); unset($curl); } else { $error = 'No allow_url_fopen/CURL available'; return FALSE; }; }; if (!( isset($vdbJSON) && is_string($vdbJSON) && strlen($vdbJSON) && strpos(' [{', $vdbJSON[0], 1) && is_array(self::$vdb = @json_decode($vdbJSON, TRUE)) && self::$vdb )) { $error = 'Invalid data received'; return FALSE; }; $vdbCached || strlen(self::$cacheFile) && @file_put_contents(self::$cacheFile, $vdbJSON, LOCK_EX) && chmod(self::$cacheFile, 0666); return self::unpackVDB(self::$vdb, $error); } public static function unpackVDB(&$vdb, &$error = NULL) { if (!( is_array($vdb) && $vdb )) { $error = "VDB is empty or invalid"; return FALSE; }; foreach ($vdb as $sid => &$sign) { if (strlen($sign[VDB_SIGNATURE])) { if ($sign[VDB_SIGNATURE][0] === ':') { $sign[VDB_SIGNATURE] = ':'.pack('H*', substr($sign[VDB_SIGNATURE], 1)); if (strlen($sign[VDB_SIGNATURE]) < 4) { $error = "$sid: Invalid HEX value"; return FALSE; }; } else { if (strlen($sign[VDB_SIGNATURE]) < 4) { $error = "$sid: Invalid PCRE"; return FALSE; }; if (!strpos(' #/~', $sign[VDB_SIGNATURE][0])) { $error = "$sid: Invalid PCRE delimiter"; return FALSE; }; if (!strrpos($sign[VDB_SIGNATURE], $sign[VDB_SIGNATURE][0])) { $error = "$sid: No ending PCRE delimiter"; return FALSE; }; }; }; if (!strlen($sign[VDB_CALLBACK])) $sign[VDB_CALLBACK] = 'cbchecksign'; $sign[VDB_INCURABLE] = (int)$sign[VDB_INCURABLE]; $sign[VDB_DOUBT] = (int)$sign[VDB_DOUBT]; $sign[VDB_LAST] = (int)$sign[VDB_LAST]; $sign[VDB_FTYPES] = isset($sign[VDB_FTYPES]) && strlen($sign[VDB_FTYPES]) ? array_flip(explode(',', $sign[VDB_FTYPES])) : array(); $sign[VDB_ACK] = isset($sign[VDB_ACK]) && strlen($sign[VDB_ACK]) ? explode(',', $sign[VDB_ACK]) : array(); $sign[VDB_EXC] = isset($sign[VDB_EXC]) && strlen($sign[VDB_EXC]) ? explode(',', $sign[VDB_EXC]) : array(); $sign[VDB_SUB] = isset($sign[VDB_SUB]) && (int)$sign[VDB_SUB] ? 1 : 0; $sign[VDB_ORDER] = isset($sign[VDB_ORDER]) ? (int)$sign[VDB_ORDER] : 1000; }; unset($sign); reset($vdb); return TRUE; } public static function file_rewrite($file, $contents) { $mode = (int)fileperms($file); chmod($file, $mode | 0220); $ret = (file_put_contents($file, $contents) === strlen($contents)); chmod($file, $mode); return $ret; } public static function file_unlink($file) { $mode = (int)fileperms($file); chmod($file, $mode | 0220); if (!$ret = unlink($file)) chmod($file, $mode); return $ret; } public static function afterTreatment(&$text, $ftype, $file) { switch ($ftype) { case 'php': $text = preg_replace('/<\?(php)?\s*\?>/', '', $text); break; }; } public static function cbchecksign($sign, &$text, $replace = FALSE, &$match = NULL) { $match = ''; $hex = $sign[VDB_SIGNATURE][0] == ':'; if ($hex) if (strpos($text, substr($sign[VDB_SIGNATURE], 1)) !== FALSE) $match = substr($sign[VDB_SIGNATURE], 1); else return 0; else if (preg_match($sign[VDB_SIGNATURE], $text, $match)) $match = $match[0]; else return 0; if ($sign[VDB_ACK]) foreach ($sign[VDB_ACK] as $sigid) if (isset(self::$vdb[$sigid]) && ($_ = self::$vdb[$sigid][VDB_CALLBACK]) && !self::$_(self::$vdb[$sigid], $text, FALSE)) return 0; if ($sign[VDB_EXC]) foreach ($sign[VDB_EXC] as $sigid) if (isset(self::$vdb[$sigid]) && ($_ = self::$vdb[$sigid][VDB_CALLBACK]) && self::$_(self::$vdb[$sigid], $text, FALSE)) return 0; $error = RET_DETECTED | ($sign[VDB_INCURABLE] ? RET_INCURABLE : 0) | ($sign[VDB_DOUBT] ? RET_DOUBT : 0); if (!$replace || $sign[VDB_INCURABLE] || $sign[VDB_LAST]) return $error; $_ = $hex ? str_replace(substr($sign[VDB_SIGNATURE], 1), $sign[VDB_REPLACE], $text, $count) : preg_replace($sign[VDB_SIGNATURE], $sign[VDB_REPLACE], $text, -1, $count); if ($count && is_string($_)) { $text = $_; $error |= RET_REPLACED; }; return $error; } public static function cbhtaccessredirect($sign, &$text, $replace = FALSE, &$match = NULL) { $result = 0; $match = ''; if (!defined('SVC_CHOST')) return $result; $host = strtolower(SVC_CHOST); if (substr($host, 0, 4) == 'www.') $host = substr($host, 4); if (!strlen($host)) return $result; $buf = substr($text, 0, 512); if (strpos($buf, $splitter = "\n") === FALSE) if (strpos($buf, $splitter = "\r") === FALSE) $splitter = "\n"; unset($buf); $lines = explode($splitter, $text); $nLines = count($lines); $pCond = $pEngine = -1; $detected = $replaced = FALSE; for ($i = 0; $i < $nLines; ++$i) { $line = strtolower(trim($lines[$i])); if (strlen($line) < 11 || ($line[0] == '#')) continue; if (substr($line, 0, 13) == 'rewriteengine') { if ($pEngine < 0) $pEngine = $i; else $lines[$i] = ''; } elseif (substr($line, 0, 11) == 'rewritecond') { if ($pCond < 0) $pCond = $i; } elseif (substr($line, 0, 11) == 'rewriterule') { if ( preg_match('~https?://~', $line) && !strpos($line, $host) && !preg_match('~https?:/+(w+\.)?([\%\$]\d|\%\{HTTP_HOST\})~', $line) ) { $detected = TRUE; $match .= $lines[$i]."\n"; if ($replace && !$sign[VDB_INCURABLE]) { $replaced = TRUE; if ($pCond < 0) { unset($lines[$i]); } else { for ($j = $pCond; $j <= $i; ++$j) unset($lines[$j]); }; }; }; $pCond = -1; } elseif (substr($line, 0, 13) == 'errordocument') { if ( preg_match('~https?://~', $line) && !strpos($line, $host) ) { $detected = TRUE; $match .= $lines[$i]."\n"; if ($replace && !$sign[VDB_INCURABLE]) { $replaced = TRUE; unset($lines[$i]); }; }; }; }; if ($detected) { $result |= RET_DETECTED | ($sign[VDB_INCURABLE] ? RET_INCURABLE : 0) | ($sign[VDB_DOUBT] ? RET_DOUBT : 0); if ($replaced) { $result |= RET_REPLACED; $text = implode($splitter, $lines); }; }; return $result; } }; $return = array( 'threats' => array(), 'errors' => array(), 'dirlist' => array(), 'skipped' => array(), 'stats' => array( 'threats' => 0, 'detectedfiles' => 0, 'checkedfiles' => 0, 'detecteddirs' => 0, 'checkeddirs' => 0, 'errors' => 0, 'treated' => 0, 'backupid' => 0, ), ); $_GET['vdbid'] = isset($_GET['vdbid']) ? abs((int)$_GET['vdbid']) : 0; avScanner::$vdb = svcDataQuery(SVC_SVC, 'vdb', array( 'vdbid' => $_GET['vdbid'], 'vdbver' => 2, 'from' => 'rexplacer', ), array( 'gzip' => SVC_CGZIP, 'json' => TRUE, 'cacheFile' => SVC_CSVCCACHE.'-vdb'.$_GET['vdbid'].'-v2.json', 'cacheClean' => SVC_CLC, )); if (!avScanner::unpackVDB(avScanner::$vdb)) return ERR_SVC + 0; $ignored = svcDataQuery('', 'ignored', array(), array( 'gzip' => SVC_CGZIP, 'json' => TRUE, 'cacheFile' => SVC_CCACHE.'/ignored.json', 'cacheClean' => SVC_CLC, )); if (!is_array($ignored)) return ERR_SVC + 1; $ignored = array_flip($ignored); @set_time_limit(300); ini_set('pcre.backtrack_limit', 20000000); $doSkipF = isset($_GET['skipbyfile']) && strlen($_GET['skipbyfile'] = trim(basename($_GET['skipbyfile']))); $sleep = 0; if (isset($_GET['sleep']) && ($_GET['sleep'] = abs((int)$_GET['sleep']))) $sleep = ($_GET['sleep'] <= 1000) ? ($_GET['sleep'] * 1000) : 1000000; $fileList = !empty($_GET['filelist']) ? splitTextLines($_GET['filelist'], TRUE, TRUE, '|') : array(); $dirList = !$fileList && !empty($_GET['dirlist']) ? splitTextLines($_GET['dirlist'], TRUE, TRUE, '|') : array('.'); $replace = isset($_GET['replace']); $backup = $replace && isset($_GET['backup']); if ($backup) $return['stats']['backupid'] = svcRestore::init(SVC_CRESTORE, $_GET['backup'], '.'); if ($fileList) $ds = FALSE; else $ds = new dirScanner(); $fileTypes = array_flip(array('php', 'inc', 'tpl', 'html', 'htm', 'js', 'htaccess', 'vdbtest')); foreach ($dirList as $dir) { if (connection_aborted()) break; if (!strlen($dir = strtr(trim(trim($dir), '\\/'), '\\', '/'))) continue; $dir .= '/'; if (isset($ignored[$dir])) { $return['skipped'][md5($dir)] = $dir; continue; }; if ($ds && !$ds->open($dir, DSCAN_NORMAL | DSCAN_INCLUDEBASE, isset($_GET['thisdir']) ? 0 : 64)) { $return['errors'][md5($dir)] = array($dir, 0, RET_EREAD); continue; }; while (is_string($file = ($ds ? $ds->read() : array_shift($fileList)))) { if (connection_aborted()) break; if ($ds) { if ($ds->isDir()) { if ( isset($ignored[$file]) || $doSkipF && ($ds->depth() === 1) && is_file($file.$_GET['skipbyfile']) ) { $return['skipped'][md5($file)] = $file; $ds->cdUp(); } else ++$return['stats']['checkeddirs']; continue; }; } else { if (!is_file($file)) continue; }; $ftype = pathinfo($file, PATHINFO_EXTENSION); if (!isset($fileTypes[$ftype])) continue; $mtime = (int)@filemtime($file); $fsize = (int)@filesize($file); $fmode = (int)@fileperms($file) & 0777; if ( ($fsize < 10) || ($fsize > 2097152) || isset($ignored[$file]) ) { $return['skipped'][md5($file)] = $file; continue; }; $ftext = @file_get_contents($file); if ( !is_string($ftext) || (strlen($ftext) < $fsize) ) { $return['errors'][md5($file)] = array($file, $mtime, RET_EREAD); continue; }; ++$return['stats']['checkedfiles']; if ($sleep && (!($return['stats']['checkedfiles'] % 100))) usleep($sleep); $finfected = FALSE; if ($replace) { $faction = 0; $fthreats = array(); foreach (avScanner::$vdb as $sid => $sign) { if ($sign[VDB_SUB]) continue; if ($sign[VDB_FTYPES] && !isset($sign[VDB_FTYPES][$ftype])) continue; $cb = $sign[VDB_CALLBACK]; $result = avScanner::$cb($sign, $ftext, TRUE, $match); if ($result) { $finfected = TRUE; $fthreats[] = array($file, $mtime, $result, $sign[VDB_TITLE], $sid, $fmode, $fsize); $dirName = $ds ? $ds->baseDir().$ds->cd() : formatDirName(pathinfo($file, PATHINFO_DIRNAME), ''); $return['dirlist'][md5($dirName)] = $dirName; if ($result & RET_REPLACED) { $faction = 1; ++$return['stats']['treated']; } elseif ($sign[VDB_LAST]) { if (!$sign[VDB_INCURABLE]) { $faction = 2; ++$return['stats']['treated']; }; break; }; }; }; if ($fthreats) { $fsetbits = $fclearbits = 0; if ($faction) { if ($backup && !svcRestore::pushFile($file)) { $return['errors'][md5($file)] = array($file, $mtime, RET_EBACKUP); $return['stats']['treated'] -= count($fthreats); $fsetbits |= RET_EBACKUP; $fclearbits |= RET_REPLACED; } elseif ($faction === 1) { avScanner::afterTreatment($ftext, $ftype, $file); if (!@avScanner::file_rewrite($file, $ftext)) { $return['errors'][md5($file)] = array($file, $mtime, RET_EWRITE); $return['stats']['treated'] -= count($fthreats); $fsetbits |= RET_EWRITE; $fclearbits |= RET_REPLACED; }; } elseif ($faction === 2) { if (!@avScanner::file_unlink($file)) { $return['errors'][md5($file)] = array($file, $mtime, RET_EDELETE); $return['stats']['treated'] -= count($fthreats); $fsetbits |= RET_EDELETE; } else $fsetbits |= RET_DELETED; }; }; foreach ($fthreats as $fthreat) { $fthreat[2] |= $fsetbits; $fthreat[2] &= ~$fclearbits; $return['threats'][] = $fthreat; }; }; } else { foreach (avScanner::$vdb as $sid => $sign) { if ($sign[VDB_SUB]) continue; if ($sign[VDB_FTYPES] && !isset($sign[VDB_FTYPES][$ftype])) continue; $cb = $sign[VDB_CALLBACK]; $result = avScanner::$cb($sign, $ftext, FALSE, $match); if ($result) { $finfected = TRUE; $return['threats'][] = array($file, $mtime, $result, $sign[VDB_TITLE], $sid, $fmode, $fsize); $dirName = $ds ? $ds->baseDir().$ds->cd() : formatDirName(pathinfo($file, PATHINFO_DIRNAME), ''); $return['dirlist'][md5($dirName)] = $dirName; if ($sign[VDB_LAST]) break; }; }; }; if ($finfected) ++$return['stats']['detectedfiles']; }; }; unset($ds); $return['stats']['threats'] = count($return['threats']); $return['stats']['errors'] = count($return['errors']); $return['stats']['detecteddirs'] = count($return['dirlist']); echo json_encode($return); ?>