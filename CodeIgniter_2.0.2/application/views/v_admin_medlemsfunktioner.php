<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
	
<script type="text/javascript" charset="utf-8" src="/ressources/jquery-1.6.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript" charset="utf-8" src="/ressources/kontantordrer.js"></script>
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
<h2><?= $divisionname ?></h2>

	<form action="/admin/medlemmer/<?= $division ?>" name="search_in_members" method="post" class="fc_form"> 
				<input name="name" type="text" value="<?php echo $name; ?>" class="memberform_input_field" />
				<input type="submit" value="Søg medlem" class="form_button"/>
	</form>
<br>

</ul>
<?php
		
	$classes = Array('even', 'odd');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	if (isset($members))
	{
		echo '			<table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;">Rediger</td>'."\n";
		echo '				<td style="width:35px;">ID</td>'."\n";
		echo '				<td style="width:300px;">Navn</td>'."\n";
		echo '				<td style="width:100px;">E-mail</td>'."\n";
		echo '				<td style="width:100px;">Telefon</td>'."\n";
		echo '			</tr>'."\n";
			
		$classes = Array('even', 'odd');
		$count = 0;
		foreach ($members as $member)
		{
			echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
			echo '			<td><a href="/kontaktinfo/uid/'.$member['uid'].'">Info</a>'."\n";
			echo '			<a href="/admin/grupper/'.(int)$division.'/'.$member['uid'].'">Rettigheder</a></td>'."\n";
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
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>