<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>KBHFF Medlemsside</title>
<?
echo  link_tag('images/favicon.ico', 'shortcut icon', 'image/ico');  
// http://www.jankoatwarpspeed.com/post/2008/07/27/Enhance-your-input-fields-with-simple-CSS-tricks.aspx
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function(){

var options = { 
        target:        '#NoLogin',   // target element(s) to be updated with server response 
        success:       showResponse  // post-submit callback 
    }; 

// bind form using ajaxForm 
$('#login').ajaxForm(options); 

});



// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
	if ( responseText == 'OK' )
	{
		$('#tt').slideUp(0, function() {    
		// Animation complete.		
		document.forms['login'].action = 'minside/index';
		document.forms['login'].submit();
  });
	}
}    
   

</script>

</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span>
<button class="form_button" style="float: right; margin-top:33px;" onClick="window.location.href='http://kbhff.dk';">G&Aring; TIL KBHFF</button>
<img src="/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
<form action="/minside/login" method="post" name="login" id="login">
<h1>KBHFF Medlemsside</h1>
Her kan du opdatere din kontaktinformation og bestille samt betale varer.<br>
Du skal v&aelig;re medlem, og have medlemsnummer og kodeord for at kunne g&aring; videre.<br>
<br>
<span class="c2"><input type="text" name="user" size="5" maxlength="5"></span><span class="c1">Medlemsnummer:</span><br>
<span class="c2"><input type="password" name="pw" id="pwbox" size="15" maxlength="15"></span><span class="c1">Kodeord:</span>
<br>
<input type="submit" class="submit_button" value="Login">&nbsp;<br>
<span id="NoLogin"></span><br clear="all">
<input type="hidden" name="hts" id="hts" value="<?php echo time(); ?>"/>
<span class="note"><a href="http://kbhff.wikispaces.com/Medlemssystem+guide" target="_blank" title="Klik for at se guiden til medlemssystemet">Guide til medlemssystemet</a></span><br>
<span class="note">(<a href="/login/glemtpassword">Glemt medlemsnummer / kodeord? Klik her</a>)</span><br>
<span class="note">(<a href="/blivmedlem/intro">Indmeld dig i KBHFF? Klik her</a>)</span>
</form>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>