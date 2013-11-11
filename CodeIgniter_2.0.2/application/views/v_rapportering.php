<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" charset="utf-8" src="/ressources/jquery.form.js"></script>
<link rel="STYLESHEET" type="text/css" href="/ressources/1st.datepick.css">
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
<?php 
if (date("Y-m-d") == $pickupdayexpl)
{
		echo ('<h1>' . $heading . ' ' . $pickupdayexpl .' (i dag)</h1>');
} else{
		echo ('<h1>' . $heading . ' ' . $pickupdayexpl .' <span style="color: Red;">(NB: Ikke i dag)</span></h1>');
}
echo $content;
?>
<?php if ($pickupday > ''):?>

	<form action="<?php echo site_url(); ?>/rapportering/kassemester" method="post" class="fc_form"> 
<input type="hidden" name="status" value="update">
<input type="hidden" name="division" value="<?=$division?>">
<input type="hidden" name="pickupday" value="<?=$pickupday?>">
<table>
<?php 
	$var = Array();
	$note = Array();
	foreach ($data as $d)
	{
		$var[$d['field']] = $d['data'];
		if (isset($d['note']))
		{
			$note[$d['field']] = $d['note'];
		}
	}
	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($fields as $s)
	{
		if ($s['editable'] == 'Y')
		{
			$disabled = '';
			$star = '';
			$class = '';
		} else {
			$disabled = ' readonly';
			$star = '<sup>*</sup>';
			$class = ' class = "noborder"';
		}
		if (isset($var[$s['uid']]))
		{
			$value = $var[$s['uid']];
		} else {
			$value = 0;
		}
		echo ('<tr><td>'. $s['comment'] . $star . '</td><td align="right"><input type="text" name="f' . $s['uid'] . '" value="'.number_format($value,2,',','').'" size="8" maxlength="8"'.$disabled.$class.'></td><td>');
		if ($s['noterequired'] == 'Y')
		{
			if (isset($note[$s['uid']]))
			{
				$value = $note[$s['uid']];
			} else {
				$value = '';
			}
			echo('Note: <input type="text" name="note' . $s['uid'] .'" size="20" maxlength="100" value="'.$value.'">');
		}
		echo ("</td></tr>\n");
		$count++;
	}
?>
</table><br>
<sup>*</sup>: Data kommer fra systemet.<br><br>
<input type="submit" value="Opdater" class="form_button">
</form>
<br>
<?
echo "<table>";
echo '<tr><td colspan="2"><strong>Indg&aring;et til afdelingen i uge ' . $weektotals[0]['weekno'] . '</strong></td></tr>';
echo '<tr><td width="275">' . $weektotals[0]['status1'] . '</td><td align="right">' . number_format($weektotals[0]['Total'],2,',','.') . ' kr.</td></tr>';
echo '<tr><td>' . $weektotals[1]['status1'] . '</td><td align="right">' . number_format($weektotals[1]['Total'],2,',','.') . ' kr.</td></tr>';
echo "</table>";
?>
<br>
	<form action="<?php echo site_url(); ?>/rapportering/kassemesteruge" method="post" class="fc_form"> 
<input type="hidden" name="status" value="update">
<input type="hidden" name="division" value="<?=$division?>">
Rapport over indg&aring;ende penge:<br>
V&aelig;lg fra-dato: <input type="text" name="fromd" size="10" maxlength="10"> Kl. <input type="text" name="fromt" value="00:00" size="5" maxlength="5"><br>
V&aelig;lg til-dato: <input type="text" name="tod" size="10" maxlength="10"> Kl. <input type="text" name="tot" value="00:00" size="5" maxlength="5"><br>
</form>

<?php else: ?>
V&aelig;lg dato.<br>
<?php endif; ?>
<form action="<?php echo site_url(); ?>rapportering/kassemester/<?php echo $division; ?>" method="post" class="fc_form"> 
V&aelig;lg dag:<select name="pickupday">
<?php 
	foreach ($afhentningsdage as $afhentningsdag)
	{
		echo '<option value="'.$afhentningsdag['uid'].'">'.$afhentningsdag['pickupdate'].'</option>'."\n";
	}
?>
</select>
<input type="submit" value="V&aelig;lg dag" class="form_button">
</form>

<br>
</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>