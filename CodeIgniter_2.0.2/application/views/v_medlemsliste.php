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
	<table class="posts">
		<tr class="odd">
			<td style="width:22px;"><strong>#</strong></td>
			<td style="width:200px;"><strong>Navn</strong></td>
			<td style="width:200px;"><strong>Email</strong></td>
			<td style="width:150px;"><strong>Sidste login</strong></td>
			<td style="width:75px;"><strong>Oprettet</strong></td> 
		</tr>
<?php
		
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($content as $member)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '	<td>'.$member['uid'].'</td>'."\n";
		echo '			<td>'.$member['firstname'].' ' .$member['middlename'].' ' .$member['lastname'].'</td>'."\n";
		echo '			<td><a href="mailto:'.$member['email'].'">'.$member['email'].'</a></td>'."\n";
		echo '			<td>'.		(danish_date_format($member['last_login'], TRUE)).'</td>'."\n";
		echo '			<td>'.		(danish_date_format($member['created'])).'</td>'."\n";
		echo "		</tr>\n";
		$count++;
	}
?>
	</table>
</span>

<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>
