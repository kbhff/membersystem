<?

// Date in the past
$this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// always modified
$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
// HTTP/1.1
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
// HTTP/1.0
$this->output->set_header("Pragma: no-cache"); 


$SessionID = $this->input->get_post('SessionID', true);

?>
<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Betalingssystem</title>
<html>
<head>
<style type="text/css">

body {
	background-color: White;
	border: 0;
	margin: 40px;
	font-family: Helvetica, Arial, Verdana;
	line-height: 1.625 em;
	font-size: 10pt;
	color: #4F5155;
}

p, body, table {
	color : Black;
	font-size: 95%; 
	margin-top : 3px;
}

.secureinfo, .secureinfo td p, .secureinfo td {
	font-size: 85%; 
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 5px solid Black;
	font-size: 16px;
	font-weight: bold;
	margin: 24px 0 2px 0;
	padding: 5px 0 0px 0;
}

h2 {
	font-size : 14pt;
	font-weight : bold;
	margin-bottom : 0px;
	text-transform : none;
}

h3 {
	font-size : 11pt;
	font-weight : bold;
	margin-bottom : 0px;
}

#tt {
	background-color: White;
	font-weight: normal;
	width: 800px;
 	overflow: auto;
	padding: 0px;
	display: block;
	margin-left: auto;
	margin-right: auto;
}

#bottomhr {
	color: Black; 
	width:800px; 
	border-width: 1px;
	margin-left: auto;
	margin-right: auto;
}

#menu li,  .form_button, .login_button, .submit_button
{
	color: White;
	background: #000;
	font-family: Helvetica, Arial, Verdana;
	font-size: 11pt;
	text-transform: UPPERCASE;
	text-decoration: none;
	display: inline;
	margin: 0 2px 0 0;
	padding: 8px 12px;
	border:1px;
	border-style:solid;
	font-weight: bold;
	cursor:pointer;cursor:hand
}	

#title {
color: Black;
font-size: 22pt;
line-height: 28pt;
cursor:pointer;cursor:hand
}
#green {
	color:#3E8E17;
}
</style>
</head>

<body bgcolor="#FFFFFF">
<span id="tt">
<span ID="title" style="float: left;" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span><br><br>
<img src="https://pay.dandomain.dk/securetunnel-bin.asp?url=http://<?= getenv("SERVER_NAME") ?>/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
<form method="post" action="https://pay.dandomain.dk/securecapture.asp" name="Form" autocomplete="off">
<input type="hidden" name="AddFormPostVars" value="1">

<?
if ($member)
{
	$memberurl = 'm/';
} else {
	$memberurl = '';
}
$currency = "208";
$ChecksumSecretKey = 'ft6/-sqRR';
$CheckSum = md5($orderno."+".$total."+".$ChecksumSecretKey."+".$currency);
$sOKURL = "http://".getenv("SERVER_NAME")."/pay/ok/$SessionID/$memberurl"; // Returnerer hvis indbetalingen gik godt
$sFAILURL = "http://".getenv("SERVER_NAME")."/pay/fail/"; // Returnerer hvis inbetalingen fejlede
?>

<input type="hidden" name="OKURL" title="OKURL" value="<?= $sOKURL ?>" >
<input type="hidden" name="FAILURL" title="FAILURL" value="<?= $sFAILURL ?>" > 
<input type="hidden" name="TestMode" value="0">
<input type="hidden" name="checksum" value="<?=$CheckSum?>">
<input type="hidden" name="SessionID" value="<?=$SessionID?>">
<input type="hidden" name="OrderID" value="%%OrderID%%">
<input type="hidden" name="CurrencyID" value="%%CurrencyID%%">
<input type="hidden" name="Amount" value="%%Amount%%">
<input type="hidden" name="MerchantNumber" value="%%MerchantNumber%%">
<input type="hidden" name="ReferenceText" value="KBHFF ref.: TA">
<input type="hidden" name="instantcapture" value="1">

<h1>Betaling af ordre <?=$orderno?>, trin 4/5</h1>
<table width="550" border="0" cellspacing="1" cellpadding="4">
  	<tr> 
    <td  colspan="2">Indtast Deres kortnummer, kontrolcifre og udl&oslash;bs-m&aring;ned/-&aring;r. Bel&oslash;bet som er angivet er det bel&oslash;b som h&aelig;ves p&aring; Deres kreditkort. Oplysninger indtastet p&aring; denne side, sendes krypteret til NETS.<br><br></td>
  	</tr>
  	
  	<tr> 
    <td><b>Bel&oslash;b som h&aelig;ves :</b></td>
    <td  align="left">&nbsp;<b>%%Amount%%&nbsp;DKK</b></td>
  	</tr>

  	<tr> 
    <td  width="364"><b>Kortnummer :</b></td>
    <td  width="686" align="left">&nbsp;<input type="text" name="CardNumber" size="20" maxlength="50"></td>
  	</tr>
  	</tr>

  	<tr> 
    <td  width="364"><b>Kontrolcifre :</b></td>
    <td  width="686" align="left">&nbsp;<input type="text" name="CardCVC" size="3" maxlength="3">&nbsp;</td>
  	</tr>

  	<tr> 
    <td  width="364"><b>Udl&oslash;bsdato(m&aring;ned/&aring;r) :</b></td>
    <td  width="686" align="left">&nbsp;
  			<select name="ExpireMonth" class="textboxgray"  style="width: 40px;">
    			<?
    			  for($month = 1; $month < 13; $month++) { 
    			    echo '<option value="'.$month.'">'.sprintf("%02u",$month).'</option>'; 
    			  }
    			?>
  			</select>
  			<select name="ExpireYear" class="textboxgray" style="width: 40px;">
    			<?
    				$thisyear = strftime("%y");
    				for($year = $thisyear; $year < ($thisyear + 12); $year++) {
    				  echo '<option value="'.$year.'">'.sprintf("%02u",$year).'</option>';
    				}
    			?>
  			</select>
	<br><br>
    </td>
  	</tr>
    
  	<tr> 
    <td  colspan="2"><img src="https://pay.dandomain.dk/images/kort-dankort.gif" border="0">
Dankort &middot; Visa/Dankort
	</td>
    </tr>
    
    <tr> 
    <td  colspan="2"><input type="submit" class="submit_button" value="Godkend betaling"></td>
    </tr>
    
</table>
</form>
<br>
<table class="secureinfo" width="350" border="1" cellpadding="5" bgcolor="#F0F7FF"><tr><td>
<h3>Sikkerhed</h3>
<p>Information p&aring; denne side bliver krypteret og er helt sikker. Dit kreditkortnummer kendes
ikke af KBHFF, og kan ikke misbruges.<br>
Adressen til siden er "http<strong>s</strong>://pay.dandomain.dk/[...]", 
hvor "s"-et betyder, at information p&aring; siden er SSL-krypteret.
Din browser skal vise en h&aelig;ngel&aring;s som bevis p&aring; SSL-kryptering: <img src="https://pay.dandomain.dk/securetunnel-bin.asp?url=http://<?= getenv("SERVER_NAME") ?>/images/ssl_indicator.gif" alt="SSL Kryptering" width="12" height="15" border="0" align="middle"></p>
<br>

</td></tr></table>
</span>
<hr align="left" id="bottomhr">
</body>
</html>

<?

function getorderkey($orderno)
{
	global $db_conn;
	$orderno = doubleval($orderno);


	$query = "select 
	orderkey
	from ff_orderhead
	where orderno = $orderno
	";
    if(!($result = @mysql_query($query, $db_conn)))
    {
		echo "<strong>Error:</strong> ";
		echo mysql_errno($db_conn);
		echo " -  ";
		echo mysql_error($db_conn);
		exit;
    }

     if (mysql_num_rows($result)>0) {
		$row = mysql_fetch_row($result);
		}

return $row[0];

} // end orderkey


?>