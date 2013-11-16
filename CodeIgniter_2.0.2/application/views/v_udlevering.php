<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/kbhff_2012.css" type="text/css" media="screen" />
<?php echo isset($library_src) ? $library_src : ''; ?>
<script type="text/javascript" src="/ressources/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="/ressources/jquery.form.js"></script>
<link rel="shortcut icon" href="/images/favicon.ico" />

<script type="text/javascript"> 
// prepare the form when the DOM is ready 

var SMS_scriptUrl = "/ressources/ajax/smsscript.php";
var Email_scriptUrl = "/ressources/ajax/emailscript.php";

$(document).ready(function() { 

	$.ajaxSetup ({  
	    cache: false  
	});

    var options = { 
        target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 
		dataType:		'json'
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#udlever').ajaxForm(options); 
}); 
 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
$('#waiting').show(500);
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
//    alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 

$('#waiting').hide(500);
var displaymessage = responseText.msg;
$('#message').removeClass().addClass((responseText.error === true) ? 'error' : 'success')
.text(displaymessage).show(500);
	newid = 'uid' + responseText.uid;
	var tmp = document.getElementById(newid);
	tmp.style.display = "none";

	var nytxt = document.getElementById("afhentet");
	updtxt = responseText.receipt + nytxt.innerHTML;
	nytxt.innerHTML = updtxt;

	// grønt
	var numudleveret = document.getElementById("numudleveret");
	numudleveret.innerHTML = responseText.numudleveret;
	var numikkeudleveret = document.getElementById("numikkeudleveret");
	tmp = responseText.numtotalorders - responseText.numudleveret;
	numikkeudleveret.innerHTML = tmp;
	// frugt
	var numudleveretf = document.getElementById("numudleveretf");
	numudleveretf.innerHTML = responseText.numudleveretf;
	var numikkeudleveretf = document.getElementById("numikkeudleveretf");
	tmp = responseText.numtotalordersf - responseText.numudleveretf;
	numikkeudleveretf.innerHTML = tmp;
	
	if (responseText.error === true) $('#udlever').show(500);

} 

	function sendsms(afdeling,sms, navn)
	{
		$.getScript(SMS_scriptUrl + '?sms=' + sms + '&navn=' + encodeURI(navn) + '&afd=' + encodeURI(afdeling), function(){
		newid = 'sms' + sms;
		var tmp = document.getElementById(newid);
		tmp.innerHTML = 'sendt';
//		tmp.style.display = "none";
		});
	}

	function sendemail(afdeling,email, medlem, navn)
	{
		$.getScript(Email_scriptUrl + '?email=' + encodeURI(email) + '&navn=' + encodeURI(navn) + '&afd=' + encodeURI(afdeling), function(){
		newid = 'email' + medlem;
		var tmp = document.getElementById(newid);
		tmp.innerHTML = 'sendt';
//		tmp.style.display = "none";
		});
	}

</script> 

<style type="text/css">
	.list {
		background-color: transparent;
		border: 0px;
		clear:both; 
		font-size:12px; 
		font-family: Helvetica, Arial, Verdana;
	}
	.listcell, .listcell A {
		color: Black;
	}
	.zsubmitcell {
		border: 0px;
		width: 75px;
		height:20px;
		font-size: 2px;
	}
	.note {
		color: Red;
	}

	.success {
		width: 298px;
		background: #a5e283;
		border: #337f09 1px solid;
		padding: 5px;
	}

	.error {
		width: 298px;
		background: #ea7e7e;
		border: #a71010 1px solid;
		padding: 5px;
	}

	#message {
		position: fixed;
		top: 135px;
		left: 280px;
	}
	#waiting {
		position: fixed;
		top: 160px;
		left: 120px;
		height: 100px;
		width: 150px;
	}

	#subm {
		background-image: url(/images/udlever2.png);
		border: none;
		font-size: 0px;
		color: transparent;
		background-color: transparent;
		border: 0px;
		height: 20px;
		width: 75px;
		background-repeat: no-repeat;
	}

	.but {
		border: none;
	}

	.smsbut {
		padding: 3px;
		border: 1px;
		border-color: black;
		border-style: solid;
		background-color: White;
	}
	.emailbut {
		padding: 3px;
		border: 1px;
		border-color: black;
		border-style: solid;
		background-color: #f5fffa;
	}
	even, tr even {
		background-color: transparent;
	}
	odd, tr odd {
		background-color: #FBEBCF;
	}

	#title {
		font-size: 20px;
	}
	
	H1 {
		font-size: 15px!important;
	}
</style>

</head>
<body>
<div id="message" style="display: none;"></div>
<div id="waiting" style="display: none;"><img src="/images/ajax-loader.gif" alt="Henter..." width="32" height="32" border="0" align="left"> &Oslash;jeblik...</div>
<span id="tt">
<span ID="title" style="float: left;" onClick="window.location.href='/minside/';" title="Til min forside">K&Oslash;BENHAVNS 
F&Oslash;DEVAREF&AElig;LLESSKAB <span id="green">/ MEDLEMSSYSTEM</span></span><br clear="all">
	<?php 
		echo getMenu(site_url(), $this->session->userdata('permissions'), $this->session->userdata('uid')); 
	?>
<?
if (date("Y-m-d") == $pickupdate)
{
		echo ('<h1>' . $divisionname . ' ' . $pickupdate .' (i dag)</h1>');
} else{
		echo ('<h1>' . $divisionname . ' ' . $pickupdate .' <span style="color: Red;">(NB: Det er ikke i dag)</span></h1>');
}
?>		
<form id="udlever" action="/ressources/regudlever.php" method="get"> 
<input type="hidden" name="divisionday" value="<?=$divisionday?>">

<?php

if (is_array($orderlist))
{
	if (count($orderlist) > 0)
	{
//		echo ("<!-- ");
//		print_r($orderlist);
//		print_r($orderlistcollected);
//		echo ($sel);
//		echo ("-->");
//		echo ('<h2>' . $orderlist[0]['name'] . ' ' . $orderlist[0]['pickupdate'] .'</h2>');
		echo $totalorder;
		echo ("<h2>Gr&oslash;ntsagsposer: <span id=\"numikkeudleveret\">$numikkeudleveret</span> ikke afhentet<br>");
		echo ("Frugtposer: <span id=\"numikkeudleveretf\">$numikkeudleveretf</span> ikke afhentet</h2>");
		echo ('<table>');
	}

	$classes = Array('even', 'odd');
	$count = 0;
	foreach ($orderlist as $order)
	{
		echo '		<tr id="uid' . $order['uid'] . '" class="'.$classes[$count%2].'"'.">\n		";
//		echo '		<tr id="uid' . $order['uid'] . '" class="list"'.">\n		";
		if (date("Y-m-d") == $pickupdate)
		{
			echo '			<td class="submitcell"><input type="submit" name="submitbutton" id="subm" value="Udlever' . $order['uid'] .'"></td>'."\n";
		}

		echo '			<td class="listcell"><b>'.$order['medlem']. ' ' . $order['firstname'].' ' . $order['middlename'].' ' . $order['lastname'].'</b></td>'."\n";
		echo '			<td class="listcell">'.$order['quant']. ' '.$order['measure']. ' ' .  $order['txt'].'</td>'."\n";
		echo '			<td class="listcell">'.$order['orderno']. ', ' .$order['status1']. '</td>'."\n";
		if (date("Y-m-d") == $pickupdate)
		{
/*
			if ($order['email'] > ' ')
			{
				echo '			<td class="listcell"><div class="emailbut" id="email' . (int)$order['medlem'] . '" onClick="Javascript:sendemail(' . "'" . $orderlist[0]['name'] . "', '" . $order['email'] ."', " . (int)$order['medlem'] .", '" . $order['firstname'].' ' . $order['middlename'].' ' . $order['lastname']."');" .'">Email</div></td>'."\n";
			} else {
				echo '			<td class="listcell">-</td>'."\n";
			}
*/
			if ($order['tel'] > ' ')
			{
				echo '			<td class="listcell"><div class="smsbut" id="sms' . (int)$order['tel'] . '" onClick="Javascript:sendsms(' . "'" . $orderlist[0]['name'] . "'," . (int)$order['tel'] .", '" . $order['firstname'].' ' . $order['middlename'].' ' . $order['lastname']."');" .'">SMS</div></td>'."\n";
			} else {
				echo '			<td class="listcell">-</td>'."\n";
			}
//			echo '			<td class="submitcell"><input type="submit" name="submitbutton" id="subm" value="Udlever' . $order['uid'] .'"></td>'."\n";
		}
		echo "		</tr>\n";
		if ($order['note'] > '')
		{
			echo ('<tr class="'.$classes[$count%2].'"><td colspan = "4" class="listcell"><em>Note: ' .$order['note'] . '</em></td></tr>');
		}
		$count++;
	}
	echo ("	</table>\n");
}
?>
</form>
<p><span class="note" id="output1"></span></p>
<h2>Gr&oslash;ntsagsposer: <span id="numudleveret"><?=$numudleveret?></span> afhentet<br>
Frugtposer <span id="numudleveretf"><?=$numudleveretf?></span> afhentet</h2>

<span id="afhentet"><?
	foreach ($orderlistcollected as $order)
	{
		if (date("Y-m-d") == $pickupdate)
		{
			echo "<a href=\"/udlevering/annuller/$divisionday/" . $order['uid'] . '" class="but"><img src="/images/del_but2.png" alt="Annuller denne udlevering" title="Annuller denne udlevering" width="15" height="15" border="0"></a> ';
		}
		echo 'Medlem ' . $order['medlem'] . ': ' . $order['firstname'].' ' . $order['middlename'].' ' . $order['lastname'];
		echo ', afhentet ' . $order['quant']. ' '.$order['measure']. ' ' .  $order['txt']."<br>\n";
	}

?></span>

</span>
<hr align="left" id="bottomhr">
<?php echo isset($script_head) ? $script_head : ''; ?>
<?php echo isset($script_foot) ? $script_foot : ''; ?>
</body>
</html>