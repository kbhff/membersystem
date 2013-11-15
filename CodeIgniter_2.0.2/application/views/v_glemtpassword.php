<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Medlemsside</title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function(){

   $("input, textarea").addClass("idle");
          $("input, textarea").focus(function(){
              $(this).addClass("activeField").removeClass("idle");
   }).blur(function(){
              $(this).removeClass("activeField").addClass("idle");
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
//		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
<h1>Glemt medlemsnummer eller kodeord</h1>
<span id="inputArea">
<span class="note">Hvis du er registreret med gyldig email-adresse, vil du kunne f&aring; mail med instruktion i at nulstille dit kodeord.</span><br>
<br>
<form action="/login/resendpassword" method="post" name="LoginForm" id="LoginForm">
Email: <input type="text" name="email" size="30" maxlength="150"><br>
<br>
<br>
<input type="submit" value="Send" class="form_button">
</form><br><br clear="all">

</span>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>