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
		echo ("<!--");
		print_r($team);
		$models = count($team);
		echo ("-->");

		echo ('<table border="0" cellspacing="5"><tr>');
		echo ('<td valign="top"><strong>System #0 - ingen teams</strong><br>V&aelig;lg: <input type="radio" name="team" value="0"><br></td>');
		if (is_array($team))
		{
			if ($models > 0)
			{
				$model = 1;
				while ($model <= $models)
				{
					echo ('<td valign="top"><strong>System #' . "\n$model" .  '</strong><br>V&aelig;lg: <input type="radio" name="team" value="' . $model . '"><br><br>');
					$classes = Array('even', 'odd');
					$count = 0;
					foreach ($team[$model] as $t)
					{
						echo '			'.$t.'<br>'."\n";
					}
					echo ("</td>\n");
					$model++;
				}
			}
		}
		echo ('</tr></table>');
		if (is_array($chores))
		{
			$count = 1;
			echo '			<strong>Vagter:</strong><br><br>';
			while ($count < 14)
			{
				echo '			#'. $count .': <select name="">';
				echo ('<option value="">V&aelig;lg</option>');
				foreach ($chores as $c)
				{
					echo ('<option value="'.$c['name'].'">'.$c['name']);
					if ($c['auth'] > 0)
					{
						echo (' *');
					}
					echo ('</option>');
				}
				echo ('</select>, ');
				echo ('antal: <select name="num[]"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>, ');
				echo ('tid: <select name="start[]"><option value=""></option><option value="0">hele dagen</option><option value="12;00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option><option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option><option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option><option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option><option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option><option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:00">20:00</option></select> - ');
				echo ('slut: <select name="end[]"><option value=""></option><option value="0">hele dagen</option><option value="12;00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option><option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option><option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option><option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option><option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option><option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:00">20:00</option></select> ');
				echo ('<br><br>' ."\n");
				$count++;
			}
		}
		echo ('</tr></table>* : Kr&aelig;ver godkendelse / at man har v&aelig;ret l&aelig;rling<br>');

?>
<br>
<input type="submit" value="Opdater">
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>