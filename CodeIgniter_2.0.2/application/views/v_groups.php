<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript" src="/ressources/jquery/jquery.datepick.js"></script>
<script type="text/javascript" src="/ressources/jquery/jquery.datepick-da.js"></script>
<link rel="STYLESHEET" type="text/css" href="/ressources/1st.datepick.css">
<link rel="shortcut icon" href="/images/favicon.ico" />
<style type="text/css">
	span.g_left_col{
	    float: left;
	    padding: 5px;
	    width: 230px;
	    border: 0px solid gray;
	}
	
	span.g_right_col{
	    float: right;
	    padding: 5px;
	    width: 230px;
	    border: 0px solid gray;
	}
	
	.gbox {
	width: 490px;
	}
</style>
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
<br>
<?php
	$classes = Array('even', 'odd');
	$translate = Array('', 'Nej', 'Ja');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	if (count($posts) > 0)
	{
		echo '			' . $divisionname . '<br>'."\n";
		echo '			V&aelig;lg et medlem der skal redigeres:<br>'."\n";
		echo '			<table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;">#</td>'."\n";
		echo '				<td style="width:200px;">Navn</td>'."\n";
		echo '				<td style="width:200px;">E-mail</td>'."\n";
		echo '			</tr>'."\n";
			
		foreach ($posts as $post)
		{
			if ($post['active'])
			{
				echo '			<tr class="'.$classes[$count%2].'"'.">\n";
				echo '				<td>'.$post['uid'].'</td>'."\n";
				echo '				<td><a href="./' . $post['uid'] . '">Rediger</a> '.$post['name'].'</td>'."\n";
				echo '				<td>'.$post['email'].'</td>'."\n";
				echo '			</tr>'."\n";
				$count++;
			}
		}
		echo '			</table>'."\n";
		echo '			<br />'."\n";
	}
?>
<?
/*
echo ("<pre>");
print_r($arbejdsgruppe);
print_r($afdelingsgruppe);
print_r($roles);
echo ("</pre>");
*/
	echo ("<div class=\"gbox\">\n");


if (isset($arbejdsgruppe))
{
	echo ('<form action="./" method="post">' . "\n");
	echo ('<input type="hidden" name="status" value="update">' . "\n");
	echo ('<input type="hidden" name="puid" value="'. $puid . '">' . "\n");

	echo ("<h3>Lokale afdelingsgrupper for $medlem</h3>\n");
	
	$counter = 0;
	$prevdivision = '';
	$tab = 'g_left_col';
	foreach ($afdelingsgruppe as $gruppe)
	{
		if ($gruppe['divisionname'] != $prevdivision)
		{
			if ($counter%2) 
			{
				echo ("<br clear=\"all\">\n");
			}
			echo('<b>' . $gruppe['divisionname'] . '</b><br>' . "\n");
			$prevdivision = $gruppe['divisionname'];
		}
		echo('<span class="' .$tab . '">');
		if ($gruppe['member'])
		{
			echo('<input type="checkbox" name="d' . $gruppe['division'] . '-' . $gruppe['uid'] .'" value="Y" checked>');
		} else {
			echo('<input type="checkbox" name="d' . $gruppe['division'] . '-' . $gruppe['uid'] .'" value="Y">');
		}
		echo($gruppe['name'] . "</span>\n");
		$tab = 'g_right_col';
		if ($counter%2) 
		{
			echo ("<br clear=\"all\">\n");
			$tab = 'g_left_col';
		}
		$counter++;
	}
	if ($counter%2) 
	{
		echo ("<br clear=\"all\">\n");
	}

	echo ("<h3>Lokale arbejdsroller for $medlem</h3>\n");
	
	$counter = 0;
	$prevdivision = '';
	$tab = 'g_left_col';
	foreach ($roles as $gruppe)
	{
		if ($gruppe['divisionname'] != $prevdivision)
		{
			if ($counter%2) 
			{
				echo ("<br clear=\"all\">\n");
			}
			echo('<b>' . $gruppe['divisionname'] . '</b><br>' . "\n");
			$prevdivision = $gruppe['divisionname'];
		}
		echo('<span class="' .$tab . '">');
		if ($gruppe['member'])
		{
			echo('<input type="checkbox" name="r' . $gruppe['division'] . '-' . $gruppe['uid'] .'" value="Y" checked>');
		} else {
			echo('<input type="checkbox" name="r' . $gruppe['division'] . '-' . $gruppe['uid'] .'" value="Y">');
		}
		echo($gruppe['name'] . "</span>\n");
		$tab = 'g_right_col';
		if ($counter%2) 
		{
			echo ("<br clear=\"all\">\n");
			$tab = 'g_left_col';
		}
		$counter++;
	}
	if ($counter%2) 
	{
		echo ("<br clear=\"all\">\n");
	}


	echo ("<h3>F&aelig;lles arbejdsgrupper for $medlem</h3>\n");
	$counter = 0;
	$tab = 'g_left_col';
	foreach ($arbejdsgruppe as $gruppe)
	{
		echo('<span class="' .$tab . '">');
		if ($gruppe['member'])
		{
			echo('<input type="checkbox" name="g' . $gruppe['uid'] .'" value="Y" checked>');
		} else {
			echo('<input type="checkbox" name="g' . $gruppe['uid'] .'" value="Y">');
		}
		echo($gruppe['name'] . "</span>\n");
		$tab = 'g_right_col';
		if ($counter%2) 
		{
			echo ("<br clear=\"all\">\n");
			$tab = 'g_left_col';
		}
		$counter++;
	}
	if ($counter%2) 
	{
		echo ("<br clear=\"all\">\n");
	}
	
	echo ("<h3>F&aelig;lles projektgrupper for $medlem</h3>\n");
	
	$counter = 0;
	$tab = 'g_left_col';
	foreach ($projektgruppe as $gruppe)
	{
		echo('<span class="' .$tab . '">');
		if ($gruppe['member'])
		{
			echo('<input type="checkbox" name="g' . $gruppe['uid'] .'" value="Y" checked>');
		} else {
			echo('<input type="checkbox" name="g' . $gruppe['uid'] .'" value="Y">');
		}
		echo($gruppe['name'] . "</span>\n");
		$tab = 'g_right_col';
		if ($counter%2) 
		{
			echo ("<br clear=\"all\">\n");
			$tab = 'g_left_col';
		}
		$counter++;
	}
	echo ("<br>\n");

	echo ("</div>\n");


	echo ('<br><input class="submit_button" type="submit" value="Opdater gruppemedlemskaber">');
	echo ('</form>' . "\n");
}
?>
<br>
<br>
	<form action="/admin/medlemmer/<?=$division?>" method="post" class="fc_form"> 
	
		<fieldset style="width:500px;">
		<legend>S&oslash;g medlem i <?=$divisionname?> afdeling</legend>
			<ol>
				<li>
					<label>Navn: </label>
					<input name="name" type="text" value="" class="memberform_input_field" />
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