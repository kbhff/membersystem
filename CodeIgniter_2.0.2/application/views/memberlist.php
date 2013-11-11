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
		<script type="text/javascript" src="<?php echo base_url(); ?>ressources/mootools-core-1.3.js"></script>
		<title>KBHFF - medlemmer</title> 
		<script type="text/javascript">
		window.addEvent('domready', function(){
			$('checkall').addEvent('click',function (e) {
				$$('#memberform input[type=checkbox]').each(function(check) {
					check.checked = true;
				});
			});
			$('checknone').addEvent('click',function (e) {
				$$('#memberform input[type=checkbox]').each(function(check) {
					check.checked = false;
				});
			});
		})
		window.addEvent('load', function(){
			$('export').addEvent('click',function (e) {
					document.forms['members'].action = './medlemmer/export/';
					var amount = 0;
					$$('#memberform input[type=checkbox]').each(function(check) {
						if (check.checked)
							amount++;
					});
					document.forms['members'].submit();
					document.forms['members'].action = './medlemmer/';
			});
		})
		</script>
	</head>
	<body>
	<?php 
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
	<form action="./medlemmer" name="search_in_members" method="post" class="fc_form"> 
		
	<fieldset style="width:800px;">
	<legend>Medlemsliste</legend>
	<br />
		<div id="message">
				<?php if (isset ($message)) echo $message; ?>
		</div>
		<div id="form_errors">
			<?php if (isset ($errors)) echo $errors; ?>
		</div>

		<ol>
			<li>
				<input name="name" type="text" value="<?php echo $name; ?>" class="memberform_input_field" />
				<input type="submit" value="Søg medlem" class="form_button"/>
			</li>
		</ol>
		<br />
	</form>

	<br />

	<form action="./medlemmer" name="members" method="post" id="memberform">
	<input type="hidden" name="timestamp" value="<?php echo time(); ?>">

	<table class="posts" style="width:800px;">
		<tr class="odd">
			<td style="width:22px;">#</td>
			<td style="width:22px;">Vælg</td>
			<td style="width:200px;">Navn</td>
			<td style="width:200px;">Email</td>
			<td style="width:60px;">Tlf.</td>
			<td style="width:150px;">Sidste login</td>
			<td style="width:75px;">Oprettet</td>
		</tr>
<?php
		
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($members as $member)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '	<td>'.($count+1).'</td>'."\n";
		echo '			<td><input type="checkbox" name="id-'.$member['id'].'" /></td>'."\n";
		echo '			<td>'.$member['name'].'</td>'."\n";
		echo '			<td><a href="mailto:'.$member['email'].'">'.$member['email'].'</a></td>'."\n";
		echo '			<td>'.$member['phone_number'].'</td>'."\n";
		echo '			<td>'.		(danish_date_format($member['last_login'], TRUE)).'</td>'."\n";
		echo '			<td>'.		(danish_date_format($member['created'])).'</td>'."\n";
		echo "		</tr>\n";
		$count++;
	}
?>
	</table>
	<a href="#" id="checkall">Vælg alle</a> / <a href="#" id="checknone">Vælg ingen</a>
	<br /><br /><br /><br />
	<div id="credit">
		Kreditér / indsæt <input type="text" name="credit-amount" size="3" /> kr. på de valgte medlemmers konti  
		<br />
		Begrundelse for indsættelse af penge:
		<br />
		<textarea cols="30" rows="2" name="credit-explanation"></textarea>		
		<br />
		<input type="submit" value="Kreditér" name="credit"  class="form_button"/>
	</div>
	<br />
	<br />
	<div id="debit">
		Debitér / træk <input type="text" name="debit-amount" size="3" /> kr. fra de valgte medlemmers konti  
		<br />
		Begrundelse for at trække penge:
		<br />
		<textarea cols="30" rows="2" name="debit-explanation"></textarea>		
		<br />
		<input type="submit" value="Debitér" name="debit"  class="form_button"/>
	</div>
	</form>
	<br />
	<a href="#" id="export">Fra hele medlemsbasen, eksportér de som ønsker nyhedsbrev til CSV/Excel fil</a>
	</fieldset>
	</body>
</html>