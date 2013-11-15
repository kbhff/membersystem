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
		<title>KBHFF - kreditér medlem</title> 
	</head>
	<body>
	
	<?php 
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
	
	<form action="./krediter" method="post" class="fc_form"> 
	
		<fieldset style="width:1000px;">
		<legend>Kreditér medlem</legend>
		<div id="message"><?php if (isset ($message)) echo $message; ?></div>
		<input type="hidden" name="timestamp" value="<?php echo time(); ?>">
			<ol>
				<li>
					<label>Navn: </label>
					<input name="name" type="text" value="<?php echo $name; ?>" class="memberform_input_field" />
				</li>
				<li>
					<input type="submit" value="Søg medlem" class="form_button"/>				
				</li>
			</ol>
			<br />
<?php
	$classes = Array('even', 'odd');
	$translate = Array('', 'Nej', 'Ja');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	if (isset($posts))
	{
		echo '			<table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;">ID</td>'."\n";
		echo '				<td style="width:200px;">Navn</td>'."\n";
		echo '				<td style="width:200px;">E-mail</td>'."\n";
		echo '				<td style="width:50px;">Aktiv?</td>'."\n";
		echo '				<td style="width:100px;">Oprettet</td>'."\n";
		echo '				<td style="width:100px;"">Sidste login</td>'."\n";
		echo '				<td style="width:100px;">Beløb</td>'."\n";
		echo '				<td style="width:200px;">Begrundelse</td>'."\n";
		echo '			</tr>'."\n";
			
		foreach ($posts as $post)
		{
			echo '			<tr class="'.$classes[$count%2].'"'.">\n";
			echo '				<td>'.$post['id'].'</td>'."\n";
			echo '				<td>'.$post['name'].'</td>'."\n";
			echo '				<td>'.$post['email'].'</td>'."\n";
			echo '				<td>'.$translate[$post['active']].'</td>'."\n";
			echo '				<td>'.danish_date_format($post['created']).'</td>'."\n";
			echo '				<td>'.danish_date_format($post['last_login']).'</td>'."\n";
			echo '				<td><input type="text" name="id-'.$post['id'].'" size="3" /> kr.</td>'."\n";
			echo '				<td><textarea cols="30" rows="2" name="explanation-'.$post['id'].'"></textarea></td>'."\n";
			echo '			</tr>'."\n";
			$count++;
		}
		echo '			</table>'."\n";
		echo '			<br />'."\n";
		echo '			<li>'."\n";
		echo '				<input type="submit" value="Kreditér" class="form_button"/>	'."\n";
		echo '			</li>'."\n";
	}
?>
		</fieldset>
	</form>
	</body>
</html>