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
	<legend>Ordreoversigt for medlem <?php echo $id;?></legend>

<?php 
	$link = site_url('minside/mine_ordrer/');

?>
	<br />
	<br />
	<table class="posts">
		<tr class="odd">
			<td style="width:150px;"><strong>Afhentningsdato</strong></td>
			<td style="width:400px;"><strong>Skift afhentningsdato</strong></td>
			<td style="width:300px;"><strong>Bestilling</strong></td>
			<td style="width:100px;"><strong>Ordre</strong></td>
		</tr>
<?php
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($transactions as $transaction)
	{
		echo '		<tr class="'.$classes[$count%2].'"'.">\n		";
		echo '	<td><strong>'.$transaction['pickupdate'].'</strong></td><td>';
		
		if ($transaction['itemid'] == FF_FRUITBAG)
		{
			$testary = $possibledatesf;
		} else {
			$testary = $possibledates;
		}
		if (is_array($testary)&&($transaction['cancel']>0))
		{
			echo ('Skift til <form action="/minside/mine_ordrer/'. $id.'" method="post"><input type="hidden" name="orderlineid" value="'.$transaction['orderlineid'].'"><input type="hidden" name="orderno" value="'.$transaction['orderno'].'"><input type="hidden" name="presdate" value="'.$transaction['uid'].'"><input type="hidden" name="item" value="'.$transaction['itemid'].'"><select name="newdate">');
			foreach ($testary as $date)
			{
				if ($date['item'] == $transaction['itemid'])
				{
					if ($transaction['pickupdate'] == $date['pickupdate'])
					{
						$sel = ' selected';
					} else {
						$sel = '';
					}
					echo('<option value="'. $date['uid'] .'"' . $sel . '>'.$date['name'] .' '. $date['pickupdate'] .'</option>');
				}
			}
			echo ('</select><input type="submit" value="Skift dato"></form>');
		} else {
			echo ("(Kan ikke skiftes)");
		}
		echo '</td>'."\n";
		echo '			<td>'.$transaction['quant'].' ' . $transaction['measure'].' ' . $transaction['explained']. '<br>' .$transaction['division']. '</td>'."\n";
		echo '			<td>'.$transaction['orderno']. ' (' .$transaction['status1'].')</td>'."\n";
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
