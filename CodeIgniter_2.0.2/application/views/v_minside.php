<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
	
<?php echo isset($library_src) ? $library_src : ''; ?>
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
<h1><?php echo $heading;?></h1>
<span id="rightcol">
<h3>Min kontakt-information</h3>
Medlemsnummer <?= $adress['uid']?><br>
<?= $adress['firstname']?> <?= $adress['middlename']?> <?= $adress['lastname']?><br>
<?= $adress['adr1']?> <?= $adress['adr2']?> <?= $adress['streetno']?>  <?= $adress['floor']?> <?= $adress['door']?><br>
<?= $adress['adr3']?> <?= $adress['zip']?> <?= $adress['city']?><br>
<?= $adress['tel']?> <?= $adress['tel2']?><br>
<?= $adress['email']?><br>
<br>
<a href="/afdelingsinfo/grupper/<?= $divisioninfo['division']?>">N&oslash;glepersoner i min afdeling</a><br>
<?php if (count($permissions) > 0):?>
<h3>Mine arbejdsroller:</h3>
<? 
/*
echo("<!--\n");
print_r($permissions);
echo("-->\n");
*/
// Udskriv tildelte roller
while (list($division, $values) = each($permissions)) { 
	if (array_key_exists('afdelingsnavn', $permissions[$division]))
	{
		echo ("<strong>" . $permissions[$division]['afdelingsnavn'] . ":</strong><br>\n");
		while (list($role, $level) = each($values)) { 
			if (is_numeric($level))
			{
				echo ("$role  <!-- level $level --><br>\n");
			}
		}
	}
}
echo ("<h3>Mine arbejdsgrupper:</h3>\n");
// Udskriv tildelte roller
reset($permissions);
while (list($division, $values) = each($permissions)) { 
	if (array_key_exists('afdelingsnavn', $permissions[$division]))
	{
		echo ("<strong>" . $permissions[$division]['afdelingsnavn'] . ":</strong><br>\n");
		while (list($role, $level) = each($values)) { 
			if ($level == 'Y')
			{
				echo ("$role  <!-- level $level --><br>\n");
			}
		}
	}
}

?>
<?php else: ?>
Du er ikke tildelt nogle arbejdsroller.<br>
<?php endif; ?>

<br>
</span>
<table><tr><td valign="top" width="300"><span class="mypagenote">
<?php if (is_array($pickups)):?>
Hej <?= $adress['firstname'] ?>,<br>
der er udlevering af varer:<br>
<?php foreach ($pickups as $item):?>

<?php echo '- ' . $item['pickupdate'] . '<br>';?> 

<?php endforeach;?>
</span>
<br>
Vil du bestille?<br>
<br>
<button onClick="window.location.href='/bestilling';"  class="form_button">Bestil og Betal</button><br>
<br>
Du kan betale med:<br>
<img src="/images/dankort.gif" alt="Du kan betale med Visa/Dankort eller Dankort" title="Du kan betale med Visa/Dankort eller Dankort"> Dankort &middot; Visa/Dankort<br>
<a href="/minside/betingelser">L&aelig;s handelsbetingelser</a><br>

<?php else: ?>
Der er pt. ingen udlevering af varer planlagt.<br>
<?php endif; ?>
<a href="http://kbhff.wikispaces.com/Medlemssystem+guide" target="_new">Vejledning til medlemssystemet.</a><br>
</td><td valign="top" class="mypagenews">
<h2>Nyheder fra KBHFF<br><?= $divisioninfo['name']?></h2><hr class="mypagenewshr">
<?
foreach ($newsletters as $newsletter)
{
	echo ('<a href="/minside/nyhedsbrev/'.$newsletter['uid'].'"><b>' . $newsletter['subject'].'</b><br>'.substr($newsletter['content'],0,160) . '[...] <i>l&aelig;s mere</i></a><br>Nyhed postet '.$newsletter['date'] . '<br><br>');
}
?>
<a href="/afdelingsinfo/grupper/<?= $divisioninfo['division']?>"><strong>N&oslash;glepersoner i <?= $divisioninfo['name']?></strong></a><br><br>
</td><td>&nbsp;&nbsp;</td></tr></table>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>