<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
		<title>Registrering af kontant-indmelding af nyt medlem</title> 

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
<br clear="all">	
	<form action="<?php echo site_url(); ?>blivmedlem/index/<?php echo $admin; ?>" method="post" class="fc_form"> 
	
		<fieldset>
		<legend>Registrering af kontant-indmelding af nyt medlem</legend>
		<div id="message">
				<?php if (isset ($message)) echo $message; ?>
				
		</div>
		<div id="form_errors">
		<?php if (isset ($errors)) echo $errors; ?>
		
		</div>
			<ol>
				<li>
				<label>Hvilken afdeling<em>*</em></label>
				<select name="division" class="memberform_input_field"><?=$divisionselectonline?></select>
				</li>
				<li>
					<label>Fornavn<em>*</em></label>
					<input name="firstname" type="text" value="<?php echo $firstname; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Mellemnavn</label>
					<input name="middlename" type="text" value="<?php echo $middlename; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Efternavn<em>*</em></label>
					<input name="lastname" type="text" value="<?php echo $lastname; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Mobil<em>*</em></label>
					<input name="tel" type="text" value="<?php echo $tel; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>E-mail-adresse<em>*</em></label>
					<input name="email" type="text" value="<?php echo $email; ?>" class="memberform_input_field" />
				</li>				

				<li>
					<input type="submit" value="Opret, der er betalt kontant" class="form_button" />
				</li>
			</ol>
		<br />
		<div id="form_remarks">
			Husk at informere om at medlemmet selv skal udfylde resten af kontakt-informationen!
		</div>
		</fieldset>
	</form>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>
