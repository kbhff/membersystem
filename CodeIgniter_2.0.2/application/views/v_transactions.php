<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
	
<?php echo isset($library_src) ? $library_src : ''; ?>
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
	
	<fieldset class="balance">
	<legend>Transaktioner p&aring; <?php if ($admin) echo $name.'s'; else echo 'min'; ?> konto</legend>
	<!--<div id="saldo">K&oslash;b i alt: <?php echo $balance; ?> kr.</div>-->

<?php 
	$link = site_url('minside/kontoinfo/'.$id.'/stigende');

	if ($this->uri->segment(4) === 'stigende')
		$link = site_url('minside/kontoinfo/'.$id.'/faldende');
?>
	<br />
	<br />
	<table class="posts">
		<tr class="odd">
			<td style="width:150px;"><a href="<?php echo $link; ?>" title="Skift sortering af posterne"><strong>Tidspunkt</strong></a></td>
			<td style="width:50px;"><strong>Type</strong></td>
			<td style="width:100px;"><strong>Tekst</strong></td>
			<td style="width:100px;"><strong>Bem&aelig;rkning</strong></td>
			<td  align="right" style="width:90px;"><strong>Bel&oslash;b</strong></td>
		</tr>
<?php
	if ($sort_desc)
		$transactions = array_reverse($transactions);
		
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($transactions as $transaction)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '	<td>'.$transaction['time'].' (uge '.$transaction['weeknumber'].')</td>'."\n";
		echo '			<td>'.$transaction['type'].'</td>'."\n";
		echo '			<td>'.$transaction['item_text'].$transaction['credit_comment'].$transaction['payment_method'].'</td>'."\n";
		echo '			<td>'.$transaction['external_id'].$transaction['authorized_by'].'</td>'."\n";
		echo '			<td align="right">'.$transaction['amount'].' kr.</td>'."\n";
		echo '<!--			<td align="right">'.$transaction['sub_sum'].' kr.</td>-->'."\n";
		echo "		</tr>\n";
		$count++;
	}
?>
	</table>
	</fieldset>
</span>

<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>
