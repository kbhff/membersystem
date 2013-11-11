<?php error_reporting(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="da"
    lang="da"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:media="http://search.yahoo.com/mrss/"> 
	<head> 
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/fc.css" type="text/css" media="screen" />
		<title>KBHFF - bliv medlem</title> 
	</head>
	<body>
	
	<form action="./blivmedlem" method="post" class="fc_form"> 
		
		<fieldset>
		<legend>Bliv medlem - indtast medlemsinformation - trin 1/2</legend>
		<div id="form_errors">
		<?php echo $errors; ?>
		</div>
			<ol>
				<li>
					<label>Fornavn<em>*</em></label>
					<input name="sir_name" type="text" value="<?php echo $sir_name; ?>" class="memberform_input_field"/>
				</li>
				<li>
					<label>Efternavn<em>*</em></label>
					<input name="last_name" type="text" value="<?php echo $last_name; ?>" class="memberform_input_field"/>
				</li>
				<li>
					<label>Vejnavn</label>
					<input name="street_name" type="text" value="<?php echo $street_name; ?>" class="memberform_input_field"/>
				</li>
				<li>
					<label>Husnummer</label>
					<input name="house_number" type="text" value="<?php echo $house_number; ?>" class="memberform_input_field"/>
				</li>
				<li>
					<label>Etage</label>
					<select name="floor" class="memberform_select">
						<option value=""<?php if ($floor === '') echo ' selected="selected"'; ?>></option>
						<option value="kld."<?php if ($floor === 'kld.') echo ' selected="selected"'; ?>>Kælderen</option>
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
					<label>Placering</label>
					<select name="placement" class="memberform_select">
						<option value=""<?php if ($placement === '') echo ' selected="selected"'; ?>></option>
						<option value="tv."<?php if ($placement === 'tv.') echo ' selected="selected"'; ?>>Til venstre</option>
						<option value="mf."<?php if ($placement === 'mf.') echo ' selected="selected"'; ?>>Midt for</option>
						<option value="th."<?php if ($placement === 'th.') echo ' selected="selected"'; ?>>Til højre</option>
					</select>
				</li>
				<li>
					<label>Postnummer</label>
					<input name="zip_code" type="text" value="<?php echo $zip_code; ?>" size="4" maxlength="4" />
				</li>
				<li>
					<label>Bynavn</label>
					<input name="city" type="text" value="<?php echo $city; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Mobilnummer</label>
					<input name="phone_number" type="text" value="<?php echo $phone_number; ?>" size="9" maxlength="8" />
				</li>
				<li>
					<label>Nyheds-emails?</label>
					<?php echo form_radio('receiving_newsletters', 'yes', ($receiving_newsletters == 'yes')); ?>Ja
					<?php echo form_radio('receiving_newsletters', 'no', ($receiving_newsletters == 'no')); ?>Nej
				</li>
				<li>
					<label>E-mail-adresse<em>*</em></label>
					<input name="email" type="text" value="<?php echo $email; ?>" class="memberform_input_field" />
				</li>
				<li>
					<label>Kodeord<em>*</em></label>
					<input name="password" type="password" value="" class="memberform_input_field" />
				</li>
				<li>
					<label>Kodeord (gentaget)<em>*</em></label>
					<input name="password_confirmed" type="password" value="" class="memberform_input_field" />
				</li>
				<li>
					<input type="submit" value="Indsend medlemsinformationer" class="form_button"/>
				</li>
			</ol>
			<br />
			<div id="form_remarks">
				Felter markeret med <em>*</em> skal udfyldes. Resten af felterne er valgfrie at udfylde.<br />
				De ekstra informationer vil muligvis blive brugt af KBHFF til at kunne yde dig en bedre service.<br />
				Vi værner om dine data og kunne aldrig drømme om at give dine informationer væk til tredjepart!
			</div>
		</fieldset>
	</form>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>