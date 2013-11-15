<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
	
<script type="text/javascript" charset="utf-8" src="/ressources/jquery-1.6.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript" charset="utf-8" src="/ressources/kontantordrer.js"></script>
<style type="text/css">
	#title {
		font-size: 20px;
	}
	
	H1 {
		font-size: 15px!important;
	}
</style>
</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS 
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span><br clear="all">
	<?php 
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
echo ('<!--');
print_r($pickups);
echo ('-->');

	?>
<h3>Registrering af kontantordrer, <?= $divisionname ?></h3>
<?
if ($message > '')
{
	echo ('<div id="message">' . $message . $kvitol . '</div>');
}
if ($errors > '')
{
	echo ('<div id="form_errors">' . $errors. '</div>');
}

?>
<?php if (is_array($pickups)):?>

	<form action="/kontantordrer<? echo ('/index/' . $division); ?>" name="search_in_members" method="post" class="fc_form"> 
				<input name="name" type="text" value="<?php echo $name; ?>" class="memberform_input_field" />
				<input type="submit" value="Søg medlem"  class="form_button"/>
	</form>

	<form action="/kontantordrer<? echo ('/index/' . $division) . '/ordre/1'; ?>" name="memberform" onSubmit="return checkdata()" method="post" id="memberform">
	<input type="hidden" name="timestamp" value="<?php echo time(); ?>">
	<input type="hidden" name="division" value="<?php echo $division; ?>">
<?php 
$count = 0;
$prevday = '';
foreach ($pickups as $item)
{
	$count++;
	if ($item['pickupdate'] <> $prevday)
	{
		echo ('<b>'. $item['pickupdate'] ."</b><br>\n"); 
	}
	echo ('<input type="hidden" name="item[]" value="' . $item['itemdayitem']. '">' . "\n");
	echo ('<input type="hidden" name="pickupday[]" value="' . $item['uid']. '">' . "\n");
	echo ('<select name="quant[]">' . $bag_quantity . '</select> ' . $item['explained']);
	if ($item['cancel'] < 0)
	{
		echo (' <span style="color: Red;">NB: Efter deadline!</span><br>');
	} else {
		echo ('<br>');
	}
	$prevday = $item['pickupdate'];
}		
	echo ('<input type="hidden" name="items" value="' . $count. '">');

	$classes = Array('even', 'odd');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	if (isset($members))
	{
		echo '			<br><table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;">V&aelig;lg</td>'."\n";
		echo '				<td style="width:35px;">Medlem</td>'."\n";
		echo '				<td style="width:300px;">Navn</td>'."\n";
		echo '				<td style="width:100px;">E-mail</td>'."\n";
		echo '				<td style="width:100px;">Telefon</td>'."\n";
		echo '			</tr>'."\n";
			
		$classes = Array('even', 'odd');
		$count = 0;
		foreach ($members as $member)
		{
			echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
			echo '			<td><input type="radio" name="puid" value="'.$member['uid'].'" /></td>'."\n";
			echo '			<td>'.$member['uid'].'</td>'."\n";
			echo '			<td>'.$member['name'].'</td>'."\n";
			echo '			<td><a href="mailto:'.$member['email'].'">mail</a></td>'."\n";
			echo '			<td>'.$member['tel'].' ' . $member['tel2'].'</td>'."\n";
			echo "		</tr>\n";
			$count++;
		}
	}
?>
	</table>
	<br>
	<input type="submit" value="Registrer kontantordre" name="kontantordre"  class="form_button"/>
	</form>


<?php else: ?>
Der er pt. ingen udlevering af varer planlagt.
<?php endif; ?>
<br>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>