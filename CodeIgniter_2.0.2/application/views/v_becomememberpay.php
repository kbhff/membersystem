<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Betaling</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php // echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span>
<button class="form_button" style="float: right; margin-top:33px;" onClick="window.location.href='http://kbhff.dk';">G&Aring; TIL KBHFF</button>
<img src="/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
	<?php 
	if ($this->session->userdata('uid') > 0)
	{
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	} else {
echo ('
 <div id="menu">
  <ul>
   <li>
    <a href="/">Log ind</a> 
   </li>
 </div>
');
	}
	?>
<h1>Online betaling: trin 3/5</h3>
<img src="/images/dankort.gif" alt="Du kan betale med Visa/Dankort eller Dankort" title="Du kan betale med Visa/Dankort eller Dankort">
<br clear="all">
<p>Vi modtager f&oslash;lgende kort: Dankort, Visa/Dankort.</p>
<p>Dette er hvad du er ved at betale for.<br>
N&aring;r du klikker p&aring; "Til betaling" kommer betalings-siden frem hos pay.dandomain.dk, hvor du
med krypteret, sikker SSL-kommunikation angiver dit kreditkortnummer og accepterer betaling.</p>
<p>Du f&aring;r bekr&aelig;ftelse p&aring; ordren pr. email, og betalingen h&aelig;ves derefter.
Bekr&aelig;ftelse bliver ikke sendt fysisk, s&aring; denne email er bevis for din betaling.</p>

<?

// Print all orderdetails and calculate sum
setlocale(LC_MONETARY, 'da_DK');
$MerchantID = "5407664";
$currency = "208";

echo ("<strong>Ordre nr. $orderno</strong><br>\n");

if ($admin > 0)
{
	echo ('<form action="/pay/kontant" method="post" name="Form" id="Form" autocomplete="off">' . "\n");
	echo ('<input type="hidden" name="uid" value="' . $medlem . '">' . "\n");
} else {
	echo ('<form action="https://pay.dandomain.dk/securetunnel.asp" method="post" name="Form" id="Form" autocomplete="off">' . "\n");
}

?>
<input type="hidden" name="AddFormPostVars" value="1">
<input type="hidden" name="member" value="Y">
<input type="hidden" name="MerchantNumber" value="<?=$MerchantID?>">
<input type="hidden" name="OrderID" value="<?=$orderno?>">
<?= $status ?><br>
1 stk. medlemsskab af K&oslash;benhavns F&Oslash;DEVAREF&AElig;LLESSKAB<br><br>
<strong>Nyt medlem:</strong> <br>
Medlemsnummer: <?=$medlem?><br>
<?
echo ($firstname . ' ' . $middlename . ' ' . $lastname . ' ' . "<br>\n");
if ($adr1 >'')
{
	echo ($adr1  . ' ' . "<br>\n");
}
echo ($adr2 . ' ' . $streetno . ' ' . $floor . ' ' . $door . ' ' . "<br>\n");
if ($adr3 >'')
{
	echo ($adr3 . ' ' . "<br>\n");
}
if ($city >'')
{
	echo ($zip . ' ' . $city . ' ' . "<br>\n");
}
echo ($email . ' ' . "<br>\n");
?>
<em>Din kontaktinformation kan &aelig;ndres senere.</em><br>
<br>
<?

$total = 100;

$Amount = number_format($total,2,',','');
echo ("<strong>Total at betale: $Amount DKK</strong><br><br>\n");
$ChecksumSecretKey = 'ft6/-sqRR';
$CheckSum = md5($orderno."+".$total."+".$ChecksumSecretKey."+".$currency);
?>
<input type="hidden" name="checksum" value="<?=$CheckSum?>">
<input type="hidden" name="Amount" value="<?=$Amount?>">
<input type="hidden" name="CurrencyID" value="<?=$currency?>">
<input type="hidden" name="TunnelURL" value="http://<?= getenv("SERVER_NAME") ?>/pay/pay4">

<input type="hidden" name="SessionID" value="<?=$orderkey?>">
<input type="submit" value="Til betaling"  class="form_button">
</form>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>