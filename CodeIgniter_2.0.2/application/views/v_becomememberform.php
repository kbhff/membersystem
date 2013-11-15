<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
		<title>KBHFF - bliv medlem</title> 

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
<br clear="all">	
	<?php 
//		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
<br>	
	<form action="<?php echo site_url(); ?>blivmedlem" method="post" class="fc_form"> 
	
		<fieldset>
		<legend>Bliv medlem - indtast medlemsinformation - trin 2/5</legend>
		<div id="message">
				<?php if (isset ($message)) echo $message; ?>
				
		</div>
		<div id="form_errors">
		<?php if (isset ($errors)) echo $errors; ?>
		
		</div>
			<ol>
				<li>
				<label>Hvilken afdeling<em>*</em></label>
				<select name="division" class="memberform_input_field"><?=$divisionselect?></select><br>
				(Er afdelingen ikke i listen, sker tilmelding ved personligt fremm&oslash;de)
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
					<label>Adresse 1</label>
					<input name="adr1" type="text" value="<?php echo $adr1; ?>" class="memberform_input_field" /> 
				</li>
				<li>
					<label>Vejnavn</label>
					<input name="adr2" type="text" value="<?php echo $adr2; ?>" class="memberform_input_field" /> 
				</li>
				<li>
					<label>Husnummer</label>
					<input name="streetno" type="text" value="<?php echo $streetno; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Etage</label>
					<select name="floor" class="memberform_select">
						<option value=""<?php if ($floor === '') echo ' selected="selected"'; ?>></option>
						<option value="kld."<?php if ($floor === 'kld.') echo ' selected="selected"'; ?>>K&aelig;lderen</option>
						<option value="st."<?php if ($floor === 'st.') echo ' selected="selected"'; ?>>Stuen</option>
						<option value="1"<?php if ($floor === '1') echo ' selected="selected"'; ?>>1. sal</option>
						<option value="2"<?php if ($floor === '2') echo ' selected="selected"'; ?>>2. sal</option>
						<option value="3"<?php if ($floor === '3') echo ' selected="selected"'; ?>>3. sal</option>
						<option value="4"<?php if ($floor === '4') echo ' selected="selected"'; ?>>4. sal</option>
						<option value="5"<?php if ($floor === '5') echo ' selected="selected"'; ?>>5. sal</option>
						<option value="6"<?php if ($floor === '6') echo ' selected="selected"'; ?>>6. sal</option>
						<option value="7"<?php if ($floor === '7') echo ' selected="selected"'; ?>>7. sal</option>
						<option value="8"<?php if ($floor === '8') echo ' selected="selected"'; ?>>8. sal</option>
						<option value="9"<?php if ($floor === '9') echo ' selected="selected"'; ?>>9. sal</option>
						<option value="10"<?php if ($floor === '10') echo ' selected="selected"'; ?>>10. sal</option>
						<option value="11"<?php if ($floor === '11') echo ' selected="selected"'; ?>>11. sal</option>
						<option value="12"<?php if ($floor === '12') echo ' selected="selected"'; ?>>12. sal</option>
						<option value="13"<?php if ($floor === '13') echo ' selected="selected"'; ?>>13. sal</option>
					</select>
				</li>
				<li>
					<label>D&oslash;r</label>
					<select name="door" class="memberform_select">
						<option value=""<?php if ($door === '') echo ' selected="selected"'; ?>></option>
						<option value="tv."<?php if ($door === 'tv.') echo ' selected="selected"'; ?>>Til venstre</option>
						<option value="mf."<?php if ($door === 'mf.') echo ' selected="selected"'; ?>>Midt for</option>
						<option value="th."<?php if ($door === 'th.') echo ' selected="selected"'; ?>>Til h&oslash;jre</option>
					</select>
				</li>
				<li>
					<label>Adresse 2</label>
					<input name="adr3" type="text" value="<?php echo $adr3; ?>" class="memberform_input_field" /> 
				</li>
				<li>
					<label>Postnummer</label>
					<input name="zip" type="text" value="<?php echo $zip; ?>" size="4" maxlength="4" />
				</li>
				<li>
					<label>Bynavn</label>
					<input name="city" type="text" value="<?php echo $city; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Telefonnummer 1<em>*</em></label>
					<input name="tel" type="text" value="<?php echo $tel; ?>" size="9" maxlength="8" />
				</li>
				<li>
					<label>Telefonnummer 2</label>
					<input name="tel2" type="text" value="<?php echo $tel2; ?>" size="9" maxlength="8" />
				</li>
				<li>
					<label>E-mail-adresse<em>*</em></label>
					<input name="email" type="text" value="<?php echo $email; ?>" class="memberform_input_field" />
				</li>				
				<li>
					<label>V&aelig;lg kodeord<em>*</em></label>
					<input name="password" type="password" value="" class="memberform_input_field" />
				</li>
				<li>
					<label>Gentag kodeord<em>*</em></label>
					<input name="password_confirmed" type="password" value="" class="memberform_input_field" />
				</li>
				<li>
					<label>Nyhedsbreve</label>
<?
				if ($privacy == 'Y')
				{
					echo ('<span class="memberform_input_field"><input type="checkbox" name="privacy" value="Y" checked> Ja tak</span><br>' . "\n");
				} else {
					echo ('<span class="memberform_input_field"><input type="checkbox" name="privacy" value="Y"> Ja tak</span><br>' . "\n");
				}
?>
<br>
KBHFF sender en gang imellem nyhedsbreve ud. Det kan v&aelig;re invitationer, praktisk information etc. Hvis ikke du siger Ja her, skal du selv holde dig orienteret via hjemmesiden.
					
				</li>
				<li>
					<input type="submit" value="Opret og g&aring; til betaling" class="form_button" />
				</li>
			</ol>
		<br />
		<div id="form_remarks">
			Felter markeret med <em>*</em> skal udfyldes. Resten af felterne er valgfrie at udfylde.<br />
			De ekstra informationer vil muligvis blive brugt af KBHFF til at kunne yde dig en bedre service.<br />
			Vi v&aelig;rner om dine data og kunne aldrig dr&oslash;mme om at give dine informationer v&aelig;k til tredjepart!
		</div>
		</fieldset>
	</form>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>
