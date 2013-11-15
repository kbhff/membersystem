<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/movemember.js"></script>
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
<div id="message"><?php if (isset ($message)) echo $message; ?></div>
<?php echo $content;?>
<?php
	$classes = Array('even', 'odd');
	$translate = Array('', 'Nej', 'Ja');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	if (isset($members))
	{
echo <<< END
<form action="/medlemmer/skiftafdeling/$divisionexists" name="movemember" onSubmit="return checkdata()" method="post" id="movemember">
<input type="hidden" name="divisionexists" value="$divisionexists">
END;
		echo '			<br><table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;">V&aelig;lg</td>'."\n";
		echo '				<td style="width:35px;">Medlem</td>'."\n";
		echo '				<td style="width:300px;">Navn</td>'."\n";
		echo '				<td style="width:300px;">E-mail</td>'."\n";
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
			echo '			<td><a href="mailto:'.$member['email'].'">'.$member['email'].'</a></td>'."\n";
			echo '			<td>'.$member['tel'].' ' . $member['tel2'].'</td>'."\n";
			echo "		</tr>\n";
			$count++;
		}
echo <<< END
	</table>
	<br>
	Flyt til: <select name="newdivision" class="memberform_input_field">$divisionselectall</select>
	<br>
	<br>
	<input type="submit" value="Registrer flytning" name="flytning"  class="form_button"/>
	</form>
END;

	}
?>
<br>
<br>
	<form action="/medlemmer/skiftafdeling/" method="post" id="searchmember" name="searchmember" onSubmit="return checksearchdata()" class="fc_form"> 
	
		<fieldset style="width:500px;">
		<legend>S&oslash;g medlem der skal flyttes til anden afdeling</legend>
			<ol>
				<li>
				<label>Er i dag medlem i</label>
				<select name="divisionexists" class="memberform_input_field"><?=$divisionselectall?></select>
				</li>
				<li>
					<label>S&oslash;g: </label>
					<input name="srch" type="text" value="" class="memberform_input_field" />
				</li>
				<li>
					<input type="submit" value="S&oslash;g medlem" class="form_button"/>				
				</li>
			</ol>
		</fieldset>
</form>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>