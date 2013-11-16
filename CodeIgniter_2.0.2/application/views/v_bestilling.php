<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Bestilling</title>
<?
// http://www.jankoatwarpspeed.com/post/2008/07/27/Enhance-your-input-fields-with-simple-CSS-tricks.aspx
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
	
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function(){


   $("input, textarea").addClass("idle");
          $("input, textarea").focus(function(){
              $(this).addClass("activeField").removeClass("idle");
   }).blur(function(){
              $(this).removeClass("activeField").addClass("idle");
   });

});


function calcsum()
{
	sum = 0;
	$('#bestilling').find('input, textarea, select').each(function(x, field) {
	    if (field.name) {
			if (field.name == 'itemamount[]')
			{
				a = field.value;
			}
			if (field.name == 'quant[]')
			{
				q = field.value;
				sum = sum + (a * q);
				a = 0;
			}
	    }                   
	});
	document.forms['bestilling']['amount'].value  = sum;
}


</script>

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
<br clear="all">
<form action="/pay" method="post" name="bestilling" id="bestilling">
<span id="inputArea">
<h1>KBHFF Bestilling</h1>
<h3>Du er logget ind som <?=$this->session->userdata('name')?></h3>
<span id="inputArea">
<!-- <strong>Din saldo er <?= $accountstatus ?> kr.</strong> <span class="note">(<a href="/minside/kontoinfo" target="_blank">se kontoudtog</a>)</span><br> -->
<br>
<strong>Bestilling af poser:</strong><br>
<img src="/images/posebillede.jpg" alt="KBHFF har gode poser!" width="100" height="120" border="0" align="right"><table class="picuplist">
<tr class="theader"><td>Afdeling</td><td>Afhentnings-<br>dato</td><td>Vare</td><td align="right">Kr. /<br>pose</td><td align="right">Tidligere<br>bestilt</td><td align="right">Antal</td><td>Deadline</td></tr>
<?php foreach ($mypickups as $item):?>

<tr class="picuplist"><td><?= $item['name'];?></td><td><?= $item['pickupdate'];?></td><td><?= $item['explained'];?></td>
<td class="rjustify"><input type="hidden" name="item[]" value="<?= $item['id'] ?>"><input type="hidden" name="pickupdate[]" value="<?= $item['uid'] ?>"><input type="hidden" class="itamount" name="itemamount[]" value="<?= $item['amount'];?>"><?= $item['amount'];?></td>
<td class="rjustify inorder"><? 
if ($item['cancel'] <0 ) { 
	echo '&nbsp;</td><td><input type="hidden" name="quant[]" value="0"><span class="disabled">' . $item['quant'] . '</span></td><td><img src="/images/exclamation.png" alt="For sent at &aelig;ndre" width="15" height="15" border="0" align="right"></td>';
	} else {
	echo ($item['quant']) . '</td><td class="rjustify"><select onChange="calcsum()" class="quantity" name="quant[]">' . $bag_quantity . '</select></td><td>' . $item['lastorder'] . '</td>';
	}
?></tr>
<?php endforeach;?>
</table>
<img src="/images/exclamation.png" alt="For sent at &aelig;ndre" width="15" height="15" vspace="2" border="0" align="left">: For sent at &aelig;ndre denne ordre.<br clear="left"><br>
<p>KBHFF's standardvare er en pose med blandet lokalt dyrket &oslash;kologisk frugt og gr&oslash;nt i s&aelig;sonbaseret udvalg. Typisk 6-8 kg i alt. Indholdet varierer fra uge til uge alt afh&aelig;ngig af s&aelig;son og udbud. 
Posen afhentes i dit lokale afhentningssted i den angivne &aring;bningstid. Prisen er 100 DKK. Da varerne bestilles hjem til dig, kan ordren ikke fortrydes efter deadline.</p>


<br>
<!-- Bel&oslash;b til indbetaling p&aring; min konto: <input type="text" name="toaccount" size="3" maxlength="3" onChange="calcsum()"><br> -->
<strong>Total at betale: <input type="text" name="amount" size="4" maxlength="4" readonly class="nofield"><br>
<br>
<input type="submit" value="Til betaling" onFocus="calcsum()" class="form_button">&nbsp;<span id="NoWay"></span>
</form><br><br clear="all">
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>