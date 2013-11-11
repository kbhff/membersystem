<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title>Redigering af afdelingsinformation</title>

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
<br>	
	<form action="<?php echo site_url(); ?>admin/afdinfo" method="post" class="fc_form"> 
	<input type="hidden" name="status" value="update">
	<input type="hidden" name="division" value="<?php echo $division; ?>">
		<fieldset>
		<legend>Afdelingsinformation til medlemmer af <?php echo $divisionname; ?></legend>
		<div id="message">
				<?php if (isset ($message)) echo $message; ?>
				
		</div>
		<div id="form_errors">
		<?php if (isset ($errors)) echo $errors; ?>
		
		</div>
			<ol>
				<li>
					<label>Tekst i velkomstmail</label>
					<textarea cols="50" rows="10" name="welcome"><?=$welcome ?></textarea>
				</li>
				<li>
					<label>Generel supportinfo</label>
					<textarea cols="50" rows="6" name="support"><?=$support ?></textarea>
				</li>
				<li>
					<input type="submit" value="Opdater informationer" class="form_button" />
				</li>
			</ol>
		<br />
		</fieldset>
	</form>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>