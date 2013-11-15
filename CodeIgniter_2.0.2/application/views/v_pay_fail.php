<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
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
<h1><?php echo $heading;?></h1>
<strong>Betaling af ordre nr. <?= $orderno ?> mislykkedes!</strong><br><br>
Det kan skyldes at du har tastet forkert kort-information (udl&oslash;bsdato eller lignende).<br>
<br>
Pr&oslash;v igen - evt. med et andet kort:<br>
<form action="https://pay.dandomain.dk/securetunnel.asp" method="post" name="Form" id="Form" autocomplete="off">
<input type="hidden" name="AddFormPostVars" value="1">
<input type="hidden" name="MerchantNumber" value="5407664">
<input type="hidden" name="OrderID" value="<?= $orderno ?>">
<br>
Ordre <?= $orderno ?>, total at betale: <?= $amount ?> DKK<br><br>
<?
$amount = number_format($amount,2,',','');
?>
<input type="hidden" name="Amount" value="<?= $amount ?>">
<input type="hidden" name="CurrencyID" value="208">
<input type="hidden" name="TunnelURL" value="http://<?= getenv("SERVER_NAME") ?>/pay/pay4">

<input type="hidden" name="SessionID" value="<?= $orderkey ?>">
<input type="submit" value="Til betaling"  class="form_button">
</form>
</span>
<hr align="left" id="bottomhr">

</body>
</html>