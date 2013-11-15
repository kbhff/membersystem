<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
		<title>KBHFF - medlemmer</title> 
		<script type="text/javascript">
$(function () { // this line makes sure this code runs on page load
	$('.checkall').click(function () {
		$(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', true);
	});
	$('.checknone').click(function () {
		$(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', false);
	});
});
		</script>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
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
		
	<fieldset>
	<legend>Medlemsliste</legend>
	<form action="/medlemmer<? echo ('/index/' . $division); ?>" name="search_in_members" method="post" class="fc_form"> 
	<br />
		<div id="message">
				<?php if (isset ($message)) echo $message; ?>
		</div>
		<div id="form_errors">
			<?php if (isset ($errors)) echo $errors; ?>
		</div>

		<ol>
			<li type="square">
				<input name="name" type="text" value="<?php echo $name; ?>" class="memberform_input_field" />
				<input type="submit" value="Søg medlem" class="form_button"/>
			</li>
		</ol>
		<br />
	</form>

	<br />

	<form action="/medlemmer<? echo ('/index/' . $division); ?>" name="members" method="post" id="memberform">
	<input type="hidden" name="timestamp" value="<?php echo time(); ?>">

	<table class="posts">
		<tr class="odd">
			<td style="width:22px;">#</td>
			<td style="width:22px;">Vælg</td>
			<td style="width:200px;">Navn</td>
			<td style="width:50px;">Email</td>
			<td style="width:60px;">Tlf.</td>
<!--			<td style="width:150px;">Sidste login</td>
			<td style="width:75px;">Oprettet</td> -->
		</tr>
<?php
		
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($members as $member)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '	<td>'.($count+1).'</td>'."\n";
		echo '			<td><input type="checkbox" name="uid-'.$member['uid'].'" /></td>'."\n";
		echo '			<td>'.$member['name'].'</td>'."\n";
		echo '			<td><a href="mailto:'.$member['email'].'">mail</a></td>'."\n";
		echo '			<td>'.$member['tel'].'</td>'."\n";
//		echo '			<td>'.		(danish_date_format($member['last_login'], TRUE)).'</td>'."\n";
//		echo '			<td>'.		(danish_date_format($member['created'])).'</td>'."\n";
		echo "		</tr>\n";
		$count++;
	}
?>
	</table>
	<a href="#" class="checkall" name="checkall">Vælg alle</a> / <a href="#" class="checknone" name="checknone">Vælg ingen</a>
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
	<br />
	</form>
	</fieldset>
</span>

<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
	</body>
</html>