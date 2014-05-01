<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript" src="/ressources/jquery.qtip.js"></script>
<link type="text/css" rel="stylesheet" href="/ressources/jquery.qtip.css" />
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

	.contactinfo {
		color: #009900;
	}
	.contactinfohidden {
		display: none;
	}
	.groupinfo {
		background:#e1fde2;
		display: block;
	}
	.groupinfo A {
		color: #009900;
	}
</style>
<script language="JavaScript" type="text/javascript">

$(document).ready(function () {
    
	$('.contactinfo').each(function() { 
	    $(this).qtip({
	        content: {
	            text: $(this).next('div') 
	        },
         show: 'click',
         hide: 'unfocus'
	    });
	});
    
});
</script>

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

<p>Mangler du p&aring; listen?<br>
Det er din afdelings Administrator, der kan opdatere listen over medlemsskaber.</p> 
<p><strong>Klik p&aring; navnet for at se kontaktinformation</strong></p>
<?php echo $content;?>
<?php
	$classes = Array('even', 'odd');

if (is_array($roles))
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
			echo('<br><b>' . $gruppe['name'] . '</b><br>');
			$comma = "";
			$gspan = "</span><br>\n";
		}
		echo ('<span class="contactinfo">');
		echo ($comma . $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']);
		echo ('</span>'. "\n");
		echo ('<div class="contactinfohidden"><p><b>Kontaktinfo '.$gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname'].'</b><br>Email: <a href="mailto:' . $gruppe['email'] .'">' . $gruppe['email'] . '</a><br>Telefon: ' . $gruppe['tel'] . '</p></div>' . "\n");
		$comma = ", ";
		$prevname = $gruppe['name'];
	}
}

if (is_array($arbejdsgruppe))
{
	$tab = 'zg_left_col';
	$prevtype = '';
	$dist = '';
	$counter = 0;
	foreach ($arbejdsgruppe as $gruppe)
	{
		if ($gruppe['type']<> $prevtype) {
			echo $dist;
			echo ('<h3>' . $gruppe['type'] . "r</h3>\n"); // 'r' to pluralize
			$prevtype = $gruppe['type'];
			$gspan = '';
			$comma = "";
			$prevname = '';
			$dist = ("<br clear=\"all\">\n\n");
		}
		if ($gruppe['name']<> $prevname) {
			echo($gspan);
			echo('<span class="' .$tab . '">');
			echo('<br><b>' . $gruppe['name'] . '</b><br>');
			$comma = "";
			$gspan = "</span><br>\n";
		}
		echo ('<span class="contactinfo">');
		echo ($comma . ''. $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']. '');
		echo ('</span>'. "\n");
		echo ('<div class="contactinfohidden"><p><b>Kontaktinfo '.$gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname'].'</b><br>Email: <a href="mailto:' . $gruppe['email'] .'">' . $gruppe['email'] . '</a><br>Telefon: ' . $gruppe['tel'] . '</p></div>'. "\n");
		$comma = ", ";
		$prevname = $gruppe['name'];
	}
}

if (isset($commongruppe))
{
	$tab = 'zg_left_col';
	$prevtype = '';
	$dist = '';
	$counter = 0;
	foreach ($commongruppe as $gruppe)
	{
		if ($gruppe['type']<> $prevtype) {
			echo $dist;
			echo ('<h2>KBHFF F&aelig;lles ' . $gruppe['type'] . "r</h2>\n"); // 'r' to pluralize
			$prevtype = $gruppe['type'];
			$gspan = '';
			$comma = "";
			$prevname = '';
			$dist = ("<br clear=\"all\">\n");
		}
		if ($gruppe['name']<> $prevname) {
			echo($gspan);
			echo('<span class="' .$tab . '">');
			echo('<br><b>' . $gruppe['name'] . '</b><br>');
			if (($gruppe['contactmail'] > '')||($gruppe['maillist'] > '')||($gruppe['wiki'] > '')||($gruppe['samba'] > ''))
			{
				echo ('<span class="groupinfo">');
				if (($gruppe['contactmail'] > '')||($gruppe['maillist'] > ''))
				{
					if ($gruppe['contactmail'] > '')
					{
						echo ('<a href="mailto:' . $gruppe['contactmail'] . '">Kontaktmail for gruppen</a>&nbsp;&nbsp;' );
					}
					if ($gruppe['maillist'] > '')
					{
						echo ('<a href="mailto:' . $gruppe['maillist'] . '">Mailliste for gruppen</a>' );
					} 
					echo ("<br>");
				}
				if (($gruppe['wiki'] > '')||($gruppe['samba'] > ''))
				{
					if ($gruppe['wiki'] > '')
					{
						echo ('<a href="' . $gruppe['wiki'] . '">Gruppens www</a>&nbsp;&nbsp;' );
					}
					if ($gruppe['samba'] > '')
					{
						echo ('<a href="' . $gruppe['samba'] . '">Gruppens diskussionsforum (SAMBA)</a>' );
					} 
				}
				echo ("</span><br>");
			}
			$comma = "";
			$gspan = "</span><br>\n";
		}
		echo ('<span class="contactinfo">');
		if ($gruppe['division'] == $division)
		{	
			echo ($comma . '<i>'. $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']. ' (' . $gruppe['divisionname'] .')</i>');
			$comma = ", ";
		} else {
			echo ($comma . ''. $gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname']. ' (' . $gruppe['divisionname'] .')');
			$comma = ", ";
		}
		echo ('</span>'. "\n");
		echo ('<div class="contactinfohidden"><p><b>Kontaktinfo '.$gruppe['firstname'] . ' ' .$gruppe['middlename'] . ' ' . $gruppe['lastname'].'</b><br>Email: <a href="mailto:' . $gruppe['email'] .'">' . $gruppe['email'] . '</a><br>Telefon: ' . $gruppe['tel'] . '</p></div>'. "\n");
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