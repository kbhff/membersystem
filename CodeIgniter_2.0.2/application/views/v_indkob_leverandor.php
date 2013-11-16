<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/sunny/jquery-ui.css" type="text/css" />
<script src="https://jquery-ui.googlecode.com/svn/tags/1.8a1/ui/ui.core.js"></script>
<script src="https://jquery-ui.googlecode.com/svn/tags/1.8a1/ui/ui.datepicker.js"></script>
<script language="JavaScript" type="text/javascript">


$(function() {
	$("#dato").datepicker({
		showWeek: true,
		firstDay:1,
	        monthNames: ['Januar','Februar','Marts','April','Maj','Juni','Juli','August','September','Oktober','November','December'],
	        monthNamesShort: ['Jan','Feb','Mar','Apr','Maj','Jun','Jul','Aug','Sep','Okt','Nov','Dec'],
	        dayNames: ['S&oslash;ndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','L&oslash;rdag'],
	        dayNamesShort: ['S&oslash;n','Man','Tir','Ons','Tor','Fre','L&oslash;r'],
	        dayNamesMin: ['S&oslash;','Ma','Ti','On','To','Fr','L&oslash;'],
	        dateFormat: 'yy-mm-dd',
			weekHeader: 'Uge',
                prevText: '&#x3c;Forrige', prevStatus: '',
                prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                nextText: 'N&aelig;ste&#x3e;', nextStatus: '',
                nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                currentText: 'Idag', currentStatus: '',
                todayText: 'Idag', todayStatus: '',
                clearText: '-', clearStatus: '',
                closeText: 'Luk', closeStatus: '',
                yearStatus: '', monthStatus: '',
                weekText: 'Uge', weekStatus: '',
                dayStatus: 'DD d MM',
                defaultStatus: '',
	    onSelect: function(dateText, inst) {
	        dateFormat: "'Uge '" + $.datepicker.iso8601Week(new Date(dateText)),
	        $(this).val('Uge ' + $.datepicker.iso8601Week(new Date(dateText))+ ', ' + dateText);
		}
	});
	$("#dato2").datepicker({
	    showWeek: true,
		firstDay:1,
	        monthNames: ['Januar','Februar','Marts','April','Maj','Juni','Juli','August','September','Oktober','November','December'],
	        monthNamesShort: ['Jan','Feb','Mar','Apr','Maj','Jun','Jul','Aug','Sep','Okt','Nov','Dec'],
	        dayNames: ['S&oslash;ndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','L&oslash;rdag'],
	        dayNamesShort: ['S&oslash;n','Man','Tir','Ons','Tor','Fre','L&oslash;r'],
	        dayNamesMin: ['S&oslash;','Ma','Ti','On','To','Fr','L&oslash;'],
	        dateFormat: 'dd-mm-yy',
                prevText: '&#x3c;Forrige', prevStatus: '',
                prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                nextText: 'N&aelig;ste&#x3e;', nextStatus: '',
                nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                currentText: 'Idag', currentStatus: '',
                todayText: 'Idag', todayStatus: '',
                clearText: '-', clearStatus: '',
                closeText: 'Luk', closeStatus: '',
                yearStatus: '', monthStatus: '',
                weekText: 'Uge', weekStatus: '',
                dayStatus: 'DD d MM',
                defaultStatus: ''
	});
});


</script>
<link rel="shortcut icon" href="/images/favicon.ico" />
</head>
<body>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS<br>
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ LEVERAND&Oslash;RSYSTEM</span></span>
<img src="/images/banner.jpg" alt="K&oslash;benhavns F&oslash;devare F&aelig;llesskab" width="800" height="188" border="0">
<div id="menu">
  <ul>
   <li>
    <a href="https://medlem.kbhff.dk/logud">Log ud</a> 
   </li>
   <li>
    <a href="#">Tilbud</a> 
   </li>   
   <li>
    <a href="#">Mine Tilbud</a> 
   </li>   
   <li>
    <a href="#">Mine ordrer</a> 
   </li>
   <li>
    <a href="#">Leverand&oslash;rer</a> 
   </li>   
  </ul>
 </div>

<h1><?php echo $heading;?></h1>
<?php echo $content;?>

<h2>Tilbudsliste</h2>
<table border="0" class="pickuplist">
<tr class="theader" id="green"><td><strong>Uge</strong></td><td width="150"><strong>Vare</strong></td><td width="100"><strong>Kvalitet</strong></td><td width="100"><strong>M&aelig;ngde</strong></td><td width="350"><strong>Note</strong></td></tr>
<tr class="odd picuplist"><td>47</td><td>&AElig;bler</td><td>1</td><td>275 kg</td><td>Pakket i 10 kg kasser</td></tr>
<tr class="even picuplist"><td>47</td><td>Jordskokker</td><td>1</td><td>75 kg</td><td>&nbsp;</td></tr>
<tr class="odd picuplist"><td>47</td><td>Selleri</td><td>1/2</td><td>150 kg</td><td>Tilbud inden 2. november</td></tr>
<tr class="even picuplist"><td>51</td><td>Most</td><td>1</td><td>125 liter</td><td>&aelig;ble, solb&aelig;r eller ribs</td></tr>
<tr class="odd picuplist"><td>52</td><td>Kartofler</td><td>1</td><td>200 kg</td><td>&AElig;ggeblomme</td></tr>
</table><br>
<br>
<h2>Opret tilbud</h2>
<form action="/indkob/leverandor/" method="post">
<table>
<tr><td>Tilbud #:</td><td><input type="text" name="nr" size="10" maxlength="10"></td></tr>
<tr><td>Vare:</td><td><input type="text" name="vare" size="40" maxlength="40"></td></tr>
<tr><td valign="top">Beskrivelse:</td><td valign="top"><textarea cols="40" rows="5" name="beskrivelse"></textarea></td></tr>
<tr><td valign="top">Kvalitet:</td><td valign="top"><input type="text" name="kvalitet" size="40" maxlength="40"><br><span class="note">1, 2, 3 klasse - evt. bem.</span></td></tr>
<tr><td>M&aelig;ngde:</td><td><input type="text" name="max" size="40" maxlength="40"></td></tr>
<tr><td>Evt. min. m&aelig;ngde:</td><td><input type="text" name="min" size="40" maxlength="40"></td></tr>
<tr><td valign="top">Pakket:</td><td valign="top"><input type="text" name="pakket" size="40" maxlength="40"><br><span class="note">Hvordan &oslash;nskes varen pakket?</span></td></tr>
<tr><td>Levering:</td><td><input type="text" name="dato" id="dato" size="20" maxlength="20"> 
</table>
<input type="submit" value="Opret tilbud" class="form_button"><br>
</form>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>