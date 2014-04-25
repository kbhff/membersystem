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
<h3><?php echo $msg;?></h3>

<?

	if ( (sizeof($bagdays) == 0) || ( (sizeof($bagcollectdays) == 0) ))
	{
		echo ("Der er ingen kommende udleveringsdage.");	
	} else {
	
	echo <<< END
<table border="1" cellspacing="0" cellpadding="4" class="posts" style="border: 1px; border-color: Silver; border-style: solid; border-collapse: collapse;">
<tr class="odd">
<td rowspan=2><strong>Afhentningsdag</strong></td>
END;
	echo ('<td colspan="' . sizeof($bagdays). '"><div align="center"><strong>Deadlines</strong></div></td></tr>'."\n".'<tr class="odd">');
	$cols = array();
	foreach ($bagdays as $bagday)
	{
		echo '<td><strong>' .$bagday['explained'] . "</strong></td>\n";
		$cols[$bagday['id']] = '';
	}

	$classes = Array('even', 'odd');
	$count = 0;
	$days=0;
	$savday = $bagcollectdays['0']['pickupdate'];
	while ($count <= sizeof($bagcollectdays))
	{
		if (@$bagcollectdays[$count]['pickupdate'] <> $savday)
		{
			if ($count > 0)
			{
				echo "		</tr>\n";
			}
			$savday = @$bagcollectdays[$count]['pickupdate'];
			echo '		<tr class="'.$classes[$days%2].'"'.">\n		";
			echo '			<td>' . $bagcollectdays[$count-1]['pickupdate']. '</td>'."\n";
			foreach ($bagdays as $bagday)
			{
				echo ('<td>');
				if ($cols[$bagday['id']] > '')
				{
					echo $cols[$bagday['id']];
				} else {
					echo '&nbsp;';
				}
				$cols[$bagday['id']] = '';
				echo ('</td>');
			}
			$days++;
		}
		foreach ($bagdays as $bagday)
		{
			$var = 'p' . $bagday['id'] . 'e';
			if (@$bagcollectdays[$count][$var] > '')
			{
				$cols[$bagday['id']] = '<a href="/admin/liste/delete/' . @$bagcollectdays[$count]['division'] . '/' . @$bagcollectdays[$count]['uid']  . '/' . $bagday['id']  .'"><strong title="Slet">&times;</strong></a> ';
				$cols[$bagday['id']] .= @$bagcollectdays[$count]['lastorder'] . "\n";
			}
		}
		$count++;
	}
		echo <<< END
		</tr>
</table>
<p>Slet-funktionen &times;</strong> har kun effekt hvis der IKKE er ordrer for posen p&aring; den angivne dag.<br>
Kontakt support (<a mailto="it@kbhff.dk">it@kbhff.dk</a>) hvis det er tilf&aelig;ldet.</p>
END;
}
?>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>