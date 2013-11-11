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
<script src="https://tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script type="text/javascript" src="/ressources/tinymce/da.js"></script>
<script type="text/javascript">

tinymce.init({
	selector: "textarea",
	entity_encoding : "raw",
	plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"visualblocks code fullscreen",
		"table contextmenu paste"
	],
		image_list: []
,
	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	autosave_ask_before_unload: false
});
<!-- /TinyMCE -->
</script>
<link rel="STYLESHEET" type="text/css" href="/ressources/1st.datepick.css">
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
<h1><?php echo $heading;?> TEST</h1>
<form action="" method="post">
Afdeling: <select name="division">
<?= $createsel ?>
</select>, gruppe: <select name="subsel">
<?= $subsel ?>
</select><br>
Emne: <input type="text" name="subject" size="50" maxlength="50" value="<?=$subject ?>"><br>
Besked:<br>
<textarea cols="40" rows="15" name="message"></textarea><br>
<input type="checkbox" name="noprivacy" value="Y"> Medtag ogs&aring; medlemmer der ikke har sagt "Ja tak" til  Nyhedsbreve.<br>
<br>
<input type="submit" value="Send (uden at der sker noget)" class="form_button"><br>
</form>
<br>
<br>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>