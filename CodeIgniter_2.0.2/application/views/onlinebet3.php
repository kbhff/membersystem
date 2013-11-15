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
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>

<h1>Online betaling: Trin 3 af 5</h1>
<img src="/images/dankort.gif" alt="Du kan betale med Visa/Dankort eller Dankort" title="Du kan betale med Visa/Dankort eller Dankort">
<br clear="all">
<p>Vi modtager f&oslash;lgende kort: Dankort, Visa/Dankort.</p>
<p>Dette er hvad du er ved at bestille. Se det hele igennem.
N&aring;r du klikker p&aring; "Til betaling" kommer betalings-siden frem hos pay.dandomain.dk, hvor du
med krypteret, sikker SSL-kommunikation angiver dit kreditkortnummer og accepterer betaling.</p>
<p>Du f&aring;r bekr&aelig;ftelse p&aring; ordren pr. email, og betalingen h&aelig;ves derefter.
Bekr&aelig;ftelse bliver ikke sendt fysisk, s&aring; denne email er bevis for din betaling.</p>

<?

// Print all orderdetails and calculate sum
setlocale(LC_MONETARY, 'da_DK');
$MerchantID = "5407664";
$xMerchantID = "1234567";
$currency = "208";

echo ("<strong>Ordre nr. $orderno, trin 3/5</strong><br>\n");
?>
<form action="https://pay.dandomain.dk/securetunnel.asp" method="post" name="Form" id="Form" autocomplete="off">
<input type="hidden" name="AddFormPostVars" value="1">
<input type="hidden" name="MerchantNumber" value="<?=$MerchantID?>">
<input type="hidden" name="OrderID" value="<?=$orderno?>">
<?= $status ?><br>
<table class="picuplist">
<tr class="theader">
<td>Afdeling</td>
<td>Dato</td>
<td colspan="2">&nbsp;</td>
<td align="right">Pris</td>
</tr>
<?php foreach ($pickuplines as $item):?>
<tr class="picuplist">
<td><?=$item['name'] ?></td>
<td><?=$item['pickupdate'] ?>&nbsp;</td>
<td><?=$item['quant'] ?></td><td><?=$item['measure'] ?> <?=$item['explained'] ?></td>
<td align="right"><?=number_format($item['amount'],2,',','') ?></td>
</tr>
<?php endforeach;?>
<?
$Amount = number_format($total,2,',','');
echo ("<tr><td colspan=\"4\"><strong>Total</strong></td><td><strong>$Amount</strong></td></tr>\n");
?>
</table>
<br>
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