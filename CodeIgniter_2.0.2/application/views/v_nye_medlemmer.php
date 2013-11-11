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
<?php

if (is_array($medlemmer))
{
	if (count($medlemmer) > 0)
	{
		echo ('
			<table class="posts">
				<tr class="odd">
					<td><strong>Oprettet</strong></td>
					<td><strong>Medlem</strong></td>
					<td><strong>Tlf.</strong></td>
					<td><strong>Email</strong></td>
					<td><strong>Note</strong></td>
				</tr>
		');
	
		$classes = Array('even', 'odd');
		$count = 0;
		$emails = '';
		$delim = '';
		foreach ($medlemmer as $medlem)
		{
			echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
			echo '			<td>'.$medlem['created']. '</td>'."\n";
			echo '			<td>'.$medlem['uid']. ' ' .$medlem['name']. '</td>'."\n";
			echo '			<td>'.$medlem['tel']. '</td>'."\n";
			echo '			<td><a href="mailto:'.$medlem['email']. '">' .$medlem['email']. '</a></td>'."\n";
			echo '			<td>'.$medlem['note']. '</td>'."\n";
			echo "		</tr>\n";
			if ($medlem['email'] > '')
			{
				$emails .= $delim . $medlem['email'];
			}
			$delim = ';';
			$count++;
		}
		echo ("	</table>\n");
		echo ('<br>Kopier nedenst&aring;ende emails til cc eller bcc n&aring;r du vil sende mail til disse nye medlemmer:<br><br><textarea cols="60" rows="10" name="emails">' .$emails . '</textarea>');
	} else {
		echo ('Ingen nye medlemmer i perioden.');
	}
}
?>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>