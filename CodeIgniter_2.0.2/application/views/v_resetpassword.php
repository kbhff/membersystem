<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Medlemsside</title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript" src="/ressources/mootools-core-1.3.2-full-compat.js"></script>
<script type="text/javascript">
$(document).ready(function(){

// bind form using ajaxForm 
$('#PWchangeForm').ajaxForm(options); 

   $("input, textarea").addClass("idle");
          $("input, textarea").focus(function(){
              $(this).addClass("activeField").removeClass("idle");
   }).blur(function(){
				$(this).removeClass("activeField").addClass("idle");
   });


});

</script>

</script>
</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span>
<button class="form_button" style="float: right; margin-top:33px;" onClick="window.location.href='http://kbhff.dk';">G&Aring; TIL KBHFF</button>
<img src="/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
	<?php 
//		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
<h1>Reset kodeord</h1>
<span id="inputArea">
<span class="note">Angiv her dit nye kodeord</span><br>
<br>
<form action="/login/savepassword" method="post" id="PWchangeForm" class="PWchangeForm"> 

<input type="hidden" name="user_activation_key" value="<?php echo $user_activation_key; ?>"/>
<input type="hidden" name="medlemsnummer" id="medlemsnummer" value="<?php echo $medlemsnummer; ?>"/>
<table>
<tr><td>Kodeord:</td><td><input type="password" name="pw1" size="30" maxlength="30"></td></tr>
<tr><td>Gentag kodeord:</td><td><input type="password" name="pw2" size="30" maxlength="30"></td></tr>
</table>
<br>
<br>
<input type="submit" value="Opdater" class="form_button">
</form><br><br clear="all">

</span>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>