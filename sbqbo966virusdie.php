<?php /**version:'24/2016-09-11',phpversions:'5.2-7.1',user:'shubin.dwws@gmail.com',updated:'2016-09-14'**/ return(list($e,$_,$rr,$rd)=array(0,'',NULL,NULL))&&(version_compare(PHP_VERSION,'5.2','>=')||($e=113)&&0)&&((error_reporting(empty($_GET['cer'])?0:($_GET['cer']=='1'?-1:error_reporting()))||1)&&(ini_set('display_errors',!empty($_GET['cer']))||1)&&(ini_set('display_startup_errors',FALSE)||1)&&(ini_set('html_errors',FALSE)||1)&&((int)ini_get('allow_url_fopen')||function_exists('curl_init'))&&(!(int)ini_get($_='magic_quotes_runtime')||ini_set($_,FALSE)||1)&&(!((int)ini_get('mbstring.func_overload')&3)||mb_internal_encoding('8bit'))&&(ini_set('arg_separator.output','&')||1)&&(@date_default_timezone_set('UTC')||1)||($e=100)&&0)&&((int)ini_get('magic_quotes_gpc')&&($_=create_function('&$v,$i','$v=stripslashes($v);'))&&array_walk_recursive($_GET,$_)&&array_walk_recursive($_COOKIE,$_)||1)&&(define('SVC_CVER',24)&&define('SVC_CKEY','FxoA1h8NQE1K331U')&&define('SVC_HOST','cdn.virusdie.ru/')&&define('SVC_USERAGENT',"sdsnetwork-client/".SVC_CVER)&&define('SVC_CACHETTL',86400)&&define('SVC_HOSTLINK','http://'.SVC_HOST)&&define('SVC_SUBPATH',isset($_SERVER['SCRIPT_NAME'])&&!in_array($_=dirname($_SERVER['SCRIPT_NAME']),array('/','\\','.'))?rtrim(strtr($_,array('\\'=>'/')),'/'):'')&&define('SVC_CFILE',basename(__FILE__))&&define('SVC_CNAME',strtr(pathinfo(SVC_CFILE,PATHINFO_FILENAME),':?*','___'))&&strlen(SVC_CNAME)&&define('SVC_CDIR',SVC_CNAME)&&define('SVC_CCACHE',SVC_CDIR.'/cache')&&define('SVC_CRESTORE',SVC_CDIR.'/restore')&&define('SVC_CBACKUPS',SVC_CDIR.'/backups')&&define('SVC_CUSERINIT',SVC_CDIR.'/userinit.php')&&define('SVC_CTIME',time())&&define('SVC_CGZIP',function_exists('gzinflate')&&(@gzinflate(base64_decode('qwMA'))==='~')?1:0)&&define('ERR_'.'SVC',900)&&define('SVC_CHOST',!empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:(!empty($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_ADDR']))&&define('SVC_UID',isset($_GET['svcuid'])?(string)$_GET['svcuid']:(isset($_COOKIE['svcuid'])?(string)$_COOKIE['svcuid']:''))&&define('SVC_KEY',isset($_GET['svckey'])?(string)$_GET['svckey']:(isset($_COOKIE['svckey'])?(string)$_COOKIE['svckey']:''))&&define('SVC_MAC',isset($_GET['svcmac'])?(string)$_GET['svcmac']:(isset($_COOKIE['svcmac'])?(string)$_COOKIE['svcmac']:''))&&define('SVC_DBI',isset($_GET['svcdbi'])?(string)$_GET['svcdbi']:(isset($_COOKIE['svcdbi'])?(string)$_COOKIE['svcdbi']:''))&&define('SVC_CTR',isset($_GET['ctr'])?(string)$_GET['ctr']:'')&&define('SVC_SVC',isset($_GET['svc'])?strtr((string)$_GET['svc'],'\\/.:?*','______'):'')&&define('SVC_CRC',isset($_GET['crc'])&&(strlen($_GET['crc'])==8)?(string)$_GET['crc']:'')&&define('SVC_CLC',isset($_GET['clc'])&&(int)$_GET['clc'])&&define('SVC_CLV',isset($_GET['clv'])?(int)$_GET['clv']:0)&&define('SVC_DBG',isset($_GET['dbg'])?(string)min(3,abs((int)$_GET['dbg'])):'0')&&define('SVC_SID',isset($_GET['sid'])?(int)$_GET['sid']:0)&&define('SVC_MACDATA',implode(':',array(SVC_SVC,SVC_CRC,SVC_UID,SVC_KEY,SVC_CTR)))&&define('SVC_CSVCCACHE',SVC_CCACHE.'/svc-'.SVC_SVC)&&define('SVC_CPHP',SVC_CSVCCACHE.'-'.md5(implode(':',array(SVC_SVC,SVC_CRC,SVC_UID,SVC_KEY,SVC_DBI,SVC_CVER,SVC_CKEY,SVC_HOST,SVC_SID))).'.php')&&define('SVC_CTRFILE',SVC_CSVCCACHE.'.ctr')&&define('SVC_BETATEST',!empty($_GET['svcbt']))&&define('SVC_COMPATMODE',!empty($_GET['svccm']))&&define('SVC_USECURL',!(int)ini_get('allow_url_fopen'))&&define('SVC_QBASE',http_build_query(array('ctr'=>SVC_CTR,'cfn'=>SVC_CFILE,'clv'=>SVC_CVER,'php'=>(float)PHP_VERSION,'clz'=>SVC_CGZIP,'ref'=>SVC_CHOST,'sid'=>SVC_SID)+(SVC_COMPATMODE?array('svcuid'=>SVC_UID,'svckey'=>SVC_KEY)+(strlen(SVC_DBI)?array('svcdbi'=>SVC_DBI):array()):array())))&&define('SVC_AUTHCOOKIES',http_build_query(array('svcuid'=>SVC_UID,'svckey'=>SVC_KEY)+(strlen(SVC_DBI)?array('svcdbi'=>SVC_DBI):array()),'',";\x20"))&&define('SVC_QCDN',SVC_HOSTLINK.'cdn/?'.SVC_QBASE.'&'.http_build_query(array('svc'=>SVC_SVC,'crc'=>SVC_CRC)))&&define('SVC_QDATA',SVC_HOSTLINK.'data/')&&define('SVC_QUPDATE',SVC_HOSTLINK.'update/?'.SVC_QBASE)||($e=104)&&0)&&(($svcContextOptions=array('http'=>array('method'=>'GET','header'=>implode("\r\n",array('Accept:*'.'/'.'*','Connection:Close','User-Agent:'.SVC_USERAGENT,'Cookie:'.SVC_AUTHCOOKIES,'')),'follow_location'=>1,'max_redirects'=>3,'timeout'=>30,'ignore_errors'=>FALSE,'user_agent'=>SVC_USERAGENT),'ssl'=>array('verify_peer'=>FALSE)))&&($svcContext=stream_context_create($svcContextOptions))&&(SVC_USECURL?($svcCURL=curl_init())&&curl_setopt_array($svcCURL,array(CURLOPT_RETURNTRANSFER=>TRUE,CURLOPT_USERAGENT=>SVC_USERAGENT,CURLOPT_COOKIE=>SVC_AUTHCOOKIES,CURLOPT_HTTPHEADER=>array('Accept:*'.'/'.'*'),CURLOPT_FOLLOWLOCATION=>!strlen((string)ini_get('open_basedir'))&&!(int)ini_get('safe_mode'),CURLOPT_MAXREDIRS=>3,CURLOPT_CONNECTTIMEOUT=>30,CURLOPT_FAILONERROR=>TRUE,CURLOPT_SSL_VERIFYPEER=>FALSE,)):($svcCURL=NULL)||1)||($e=103)&&0)&&(count($_GET)||($e=115)&&0)&&(strlen(SVC_UID)&&strlen(SVC_KEY)||($e=101)&&0)&&((SVC_CLV===SVC_CVER)||($e=105)&&0)&&(is_file(SVC_CFILE)&&(filesize(SVC_CFILE)===9563)||($e=117)&&0)&&(strlen(SVC_SVC)||($e=102)&&0)&&(((strlen(SVC_MAC)==32)||($e=111)&&0)&&(function_exists('hash_hmac')&&!strcmp(hash_hmac('md5',SVC_MACDATA,SVC_CKEY.':'.SVC_CTR),SVC_MAC)||($_=str_pad((strlen(SVC_CKEY.':'.SVC_CTR)>64)?md5(SVC_CKEY.':'.SVC_CTR,TRUE):SVC_CKEY.':'.SVC_CTR,64,"\x00",STR_PAD_RIGHT))&&!strcmp(md5(($_^str_repeat("\x5c",64)).md5(($_^str_repeat("\x36",64)).SVC_MACDATA,TRUE)),SVC_MAC)||($e=111)&&0)&&((strlen(SVC_CTR)>=12)&&(($_=is_file(SVC_CTRFILE)?(string)file_get_contents(SVC_CTRFILE,0,NULL,-1,strlen(SVC_CTR)):'')||1)&&(strcmp(SVC_CTR,str_pad($_,strlen(SVC_CTR),'0',STR_PAD_LEFT))>0)||($e=112)&&0)&&(define('SVC_MACOK',1)||1))&&(!strpos('_12',SVC_DBG,1))&&((is_dir(SVC_CDIR)||is_writable('.')&&@mkdir(SVC_CDIR,0751,TRUE))&&(is_dir(SVC_CCACHE)||@mkdir(SVC_CCACHE,0751,TRUE))||($e=116)&&0)&&((is_file(SVC_CDIR.'/.htaccess')||@file_put_contents(SVC_CDIR.'/.htaccess',"Order\x20Allow,Deny\nDeny\x20From\x20All\n"))||($e=106)&&0)&&(!rand(0,30)&&is_dir(SVC_CCACHE)&&is_array($_=@scandir(SVC_CCACHE))&&@array_walk($_,create_function('$f,$i,$d','($f[0]!=".")&&is_file($p=$d[0]."/".$f)&&((int)filemtime($p)<=$d[1])&&unlink($p);'),array(SVC_CCACHE,SVC_CTIME-SVC_CACHETTL))||1)&&((strlen(SVC_CRC)&&is_file(SVC_CPHP)&&((int)filemtime(SVC_CPHP)>SVC_CTIME-SVC_CACHETTL)&&(touch(SVC_CPHP)||1)&&(define('SVC_CACHED',1)||1))||((define('SVC_CACHED',0)||1)&&(is_string($rr=SVC_USECURL&&curl_setopt($svcCURL,CURLOPT_URL,SVC_QCDN)?curl_exec($svcCURL):@file_get_contents(SVC_QCDN,0,$svcContext))||($e=107)&&0)&&(strlen($rr)||($e=108)&&0)&&((strlen($rr)!=3)||!is_numeric($rr)||($e=(int)$rr)&&0)&&(SVC_CGZIP?(is_string($rd=@gzinflate($rr))&&strlen($rd)?1:($e=108)&&0):(($rd=&$rr)||1))&&((@file_put_contents(SVC_CPHP,$rd,LOCK_EX)===strlen($rd))||($e=106)&&0)))&&(!strpos('_3',SVC_DBG,1))&&((@file_put_contents(SVC_CTRFILE,SVC_CTR,LOCK_EX)===strlen(SVC_CTR))||($e=106)&&0)&&(($rr=$rd=$_=NULL)||1)&&(!is_file(SVC_CUSERINIT)||(include(SVC_CUSERINIT))||($e=114)&&0)&&(is_file(SVC_CPHP)&&($_=(include(SVC_CPHP)))&&@(SVC_CLC&&unlink(SVC_CPHP)&&unlink(SVC_CTRFILE)||1)||($e=109)&&0)&&(!(is_numeric($_)&&(strlen((string)$_)==3))||($e=(int)$_)&&0)||(defined('SVC_DBG')&&(int)SVC_DBG&&defined('SVC_MACOK')&&(strpos('_1',SVC_DBG,1)&&phpinfo()||strpos('_23',SVC_DBG,1)&&(header('Content-Type:text/plain')||1)&&print_r(array('CLIENT'=>SVC_CVER,'STATUS'=>$e,'HOST'=>SVC_CHOST,'UNAME'=>php_uname(),'OS'=>PHP_OS,'PHP'=>PHP_VERSION,'SAPI'=>PHP_SAPI,'PHP_MODULES'=>implode(",\x20",get_loaded_extensions()),'USE_CURL'=>SVC_USECURL,'LAST_ERROR'=>error_get_last(),'CURL_ERROR'=>SVC_USECURL&&(int)curl_errno($svcCURL)?curl_errno($svcCURL).':'.curl_error($svcCURL):'','CWD'=>getcwd(),'__FILE__'=>__FILE__,'PHP_UID'=>function_exists('posix_getuid')?posix_getuid().':'.posix_getgid():'-','CFILE_UID'=>fileowner(SVC_CFILE).':'.filegroup(SVC_CFILE),'CDIR_UID'=>is_dir(SVC_CDIR)?fileowner(SVC_CDIR).':'.filegroup(SVC_CDIR):'-','CDIR_MOD'=>is_dir(SVC_CDIR)?decoct((int)fileperms(SVC_CDIR)&0777).(is_writable(SVC_CDIR)?'':':W'):'-','ROOT_UID'=>fileowner('.').':'.filegroup('.'),'ROOT_MOD'=>decoct((int)fileperms('.')&0777).(is_writable('.')?'':':W'),'CDN_URL'=>SVC_QCDN,'CDN_RESULT'=>gettype($rr).(is_string($rr)?'('.strlen($rr).')':'').':"'.(is_string($rr)?(SVC_CGZIP?'base64:'.base64_encode(substr($rr,0,200)):substr($rr,0,200)).'...':$rr).'"','CDN_DECODED'=>gettype($rd).(is_string($rd)?'('.strlen($rd).')':'').':"'.(is_string($rd)?substr($rd,0,200).'...':$rd).'"','$_GET'=>&$_GET,'$_COOKIE'=>&$_COOKIE,'$_SERVER'=>&$_SERVER,'PHP_INI'=>version_compare(PHP_VERSION,'5.3','<')?ini_get_all():ini_get_all(NULL,FALSE),))||1))||(($e&&($e==115))&&(($_=SVC_HOSTLINK.'splash/?'.http_build_query(array('ref'=>SVC_CHOST,'sid'=>SVC_SID)))&&is_string($rr=SVC_USECURL&&curl_setopt($svcCURL,CURLOPT_URL,$_)?curl_exec($svcCURL):@file_get_contents($_,0,$svcContext))&&strlen($rr)&&(print($rr))||($e=107)&&0)&&(($e=0)||1))||(($e&&in_array($e,array(105,117,402)))&&is_string($rr=SVC_USECURL&&curl_setopt($svcCURL,CURLOPT_URL,SVC_QUPDATE)?curl_exec($svcCURL):@file_get_contents(SVC_QUPDATE,0,$svcContext))&&strlen($rr)&&!strcmp(substr($rr,0,5),'<'.'?'.'php')&&strpos($rr,"'".SVC_CKEY."'")&&($_=strpos($rr,"'SVC_CVER',"))&&((int)ltrim(substr($rr,$_+11,6))===SVC_CLV)&&@(strlen($_=SVC_CFILE.'.temp'.rand(1e3,1e4-1))&&is_writable('.')&&is_writable(SVC_CFILE)&&(file_put_contents($_,$rr)===strlen($rr))&&rename($_,SVC_CFILE)||(!is_file($_)||unlink($_)||1)&&($e=106)&&0)&&($e=110)&&0)||((defined('SVC_EMBEDDED')||printf('%d',$e))&&0)?0:$e;