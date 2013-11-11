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
<p>Der er mange forskellige arbejdsgrupper i KBHFF og de laver meget forskellige ting. Kan du noget specielt - er du f.eks. 
god til at skrive, fotografere, styre regnskab, bruge h&aelig;nderne, arrangere udflugter eller noget helt sjette - er det m&aring;ske dig 
vi mangler i en af arbejdsgrupperne. Vi vil generelt meget gerne v&aelig;re flere i arbejdsgrupperne, da opgaverne stiger i takt
 med medlemstallet. Har du en id&eacute; til et fedt projekt eller arrangement er du ogs&aring; velkommen til at starte en ny fast gruppe
 eller ad-hoc-gruppe.</p>
 
<p>Lokale arbejdsgrupper<br>
 De lokale arbejdsgrupper i afdelingerne s&oslash;rger for at afdelingen fungerer b&aring;de praktisk og administrativt, herunder 
den daglige drift af vores butikker, lokale &oslash;konomi og medlemslister.</p>
<?php echo $content;?>
<?php
	$classes = Array('even', 'odd');

if (isset($roles))
{
	echo ('<h3>Arbejdsroller i ' . $divisionname."</h3>\n");
	$tab = 'zg_left_col';
	$prevname = '';
	$counter = 0;
	$gspan = '';
	foreach ($roles as $gruppe)
	{
		if ($gruppe['name']<> $prevname) {
			echo($gspan);
			echo('<span class="' .$tab . '">');
			echo('<b>' . $gruppe['name'] . '</b><br>');
			$comma = "";
			$gspan = "</span><br>\n";
		}
		echo ($comma . $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']);
		$comma = ", ";
		$prevname = $gruppe['name'];
	}
}

if (isset($arbejdsgruppe))
{
	$tab = 'zg_left_col';
	$prevtype = '';
	$counter = 0;
	foreach ($arbejdsgruppe as $gruppe)
	{
		if ($gruppe['type']<> $prevtype) {
			echo ("<br clear=\"all\">\n<br>\n");
			echo ('<h3>' . $gruppe['type'] . "r</h3>\n"); // 'r' to pluralize
			$prevtype = $gruppe['type'];
			$gspan = '';
			$comma = "";
			$prevname = '';
		}
		if ($gruppe['name']<> $prevname) {
			echo($gspan);
			echo('<span class="' .$tab . '">');
			echo('<b>' . $gruppe['name'] . '</b><br>');
			$comma = "";
			$gspan = "</span><br>\n";
/*
			if ($counter%2) 
			{
				echo ("<br clear=\"all\">\n");
				$tab = 'zg_left_col';
			} else {
				$tab = 'zg_right_col';
			}
			$counter++;
*/
		}
		echo ($comma . $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']);
		$comma = ", ";
		$prevname = $gruppe['name'];
	}
}


?>
<br>
<br>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>