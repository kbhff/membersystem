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
<?php echo $heading;?>:
<?php
echo ('<h2>' .$newsletter[0]['date'] . ': ' . $newsletter[0]['subject'] . '</h2>' . "\n\n");
echo (nl2br($newsletter[0]['content']));

// start of archive
if (is_array($newsletterarchive))
{
	echo ('<br><br><strong>Arkiv:</strong><br>');
	echo ('
		<table class="posts">
			<tr class="odd">
				<td><strong>Dato</strong></td>
				<td><strong>Emne</strong></td>
				<td><strong>Uddrag</strong></td>
				<td><strong>L&aelig;s</strong></td>
			</tr>
	');

	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($newsletterarchive as $newsletter)
	{
		echo '		<tr  valign="top" class="'.$classes[$count%2].'"'.">\n		";
		echo ('<td><i>'.$newsletter['date'] . '</i></td><td>'.$newsletter['subject'].'</td><td>' . substr($newsletter['content'],0,160) . '[...]</td><td><a href="/minside/nyhedsbrev/'.$newsletter['uid'].'">l&aelig;s</a></td>');
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