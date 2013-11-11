<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<link rel="shortcut icon" href="/images/favicon.ico" />
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
<h1><?php echo $heading;?></h1>
<form action="/udlevering/dag/" method="post">
Vis ordreliste: <select name="divisionday">
<?= $sel ?>
</select> <input type="submit" value="Vis" class="form_button"></form>
<br>
<?= $medlemordreliste ?>
<br>
<table><tr valign="top"><td valign="top">Registrer kontant-ordrer:<br>
<?= $cashsel ?>
</td><td valign="top"><form action="/kontantordrer/annuller" method="post">Annuller kontant-ordre:<br>
Ordrenr.: <input type="text" name="orderno" size="5" maxlength="5"> <input type="submit" value="Annuller" class="form_button">
</form></td></tr>
<tr><td valign="top"><a href="/blivmedlem/index/<?= $this->session->userdata('uid') ?>">Registrering af kontant-indmelding af nyt medlem</a></td><td valign="top">&nbsp;</td></tr>
<tr><td valign="top"><a href="/medlemmer/skiftafdeling/">Flyt medlem til anden afdeling</a></td><td valign="top">&nbsp;</td></tr>
</td></tr></table>
<br>


<?php

if (is_array($orderlist))
{
	if (count($orderlist) > 0)
	{
		echo ("<!-- ");
		print_r($orderlist);
		echo ("-->");
		echo ('<h2>' . $orderlist[0]['name'] . ' ' . $orderlist[0]['pickupdate'] .'</h2>');
	}
	echo ('
		<table class="posts">
			<tr class="odd">
				<td><strong>Beskrivelse</strong></td>
				<td><strong>Medlem</strong></td>
				<td><strong>Navn</strong></td>
				<td><strong>Email</strong></td>
				<td><strong>Tlf.</strong></td>
			</tr>
	');

	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($orderlist as $order)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '			<td>'.$order['quant']. ' '.$order['measure']. ' ' .  $order['txt'].'</td>'."\n";
		echo '			<td>'.$order['uid'].'</td>'."\n";
		echo '			<td>'.$order['firstname'].' ' . $order['middlename'].' ' . $order['lastname'].'</td>'."\n";
		echo '			<td><a href="mailto:'.$order['email'].'">'.$order['email'].'</a></td>'."\n";
		echo '			<td>'.$order['tel'].'</td>'."\n";
		echo "		</tr>\n";
		$count++;
	}
	echo ("	</table>\n");
}
?>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>