<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" src="/ressources/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="/ressources/jquery.form.js"></script>
<link rel="shortcut icon" href="/images/favicon.ico" />

<style type="text/css">
	.list {
		background-color: transparent;
		border: 0px;
		clear:both; 
		font-size:12px; 
		font-family: Helvetica, Arial, Verdana;
	}
	.listcell, .listcell A {
		color: Black;
	}
	.zsubmitcell {
		border: 0px;
		width: 75px;
		height:20px;
		font-size: 2px;
	}
	.note {
		color: Red;
	}

	.success {
		width: 298px;
		background: #a5e283;
		border: #337f09 1px solid;
		padding: 5px;
	}

	.error {
		width: 298px;
		background: #ea7e7e;
		border: #a71010 1px solid;
		padding: 5px;
	}

	#message {
		position: fixed;
		top: 135px;
		left: 280px;
	}
	#waiting {
		position: fixed;
		top: 160px;
		left: 120px;
		height: 100px;
		width: 150px;
	}

	#subm {
		background-image: url(/images/udlever2.png);
		border: none;
		font-size: 0px;
		color: transparent;
		background-color: transparent;
		border: 0px;
		height: 20px;
		width: 75px;
		background-repeat: no-repeat;
	}

	.but {
		border: none;
	}

	.smsbut {
		padding: 3px;
		border: 1px;
		border-color: black;
		border-style: solid;
		background-color: White;
	}
	.emailbut {
		padding: 3px;
		border: 1px;
		border-color: black;
		border-style: solid;
		background-color: #f5fffa;
	}
	even, tr even {
		background-color: transparent;
	}
	odd, tr odd {
		background-color: #FBEBCF;
	}

	#title {
		font-size: 20px;
	}
	
	H1 {
		font-size: 15px!important;
	}
</style>

</head>
<body>
<div id="message" style="display: none;"></div>
<div id="waiting" style="display: none;"><img src="/images/ajax-loader.gif" alt="Henter..." width="32" height="32" border="0" align="left"> &Oslash;jeblik...</div>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS 
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span><br clear="all">
	<?php 
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>

<?php

		echo '<h1>' . $heading . '</h1>';
		echo $totalorder;
?>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>