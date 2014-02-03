<?php
  require_once('../../global_config.php');
	unset($_GET["_"]);
	require_once("class.inputfilter_clean.php");

$tags = '';
$attr = '';
$tag_method = 0;
$attr_method = 0;
$xss_auto = 1;
$myFilter = new InputFilter($tags, $attr, $tag_method, $attr_method, $xss_auto);

$sms = $myFilter->process($_GET["sms"]);
$navn = $myFilter->process($_GET["navn"]);
$afd = $myFilter->process($_GET["afd"]);

$username = GLOBAL_SMS_USER;                      //username used in HQSMS
$password = GLOBAL_SMS_PASSWORD;
$encoding = 'utf8';
$to = '45' . $sms;                      //destination number
$from = urlencode(GLOBAL_SMS_SENDER);                //sender name have to be activated
$message = urlencode("Husk at hente de bestilte varer hos KBHFF i dag! mvh KBHFF " . $afd);
$url = 'https://ssl.hqsms.com/api/sms.do';
$c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, 'username='.$username.'&password='.$password.'&from='.$from.'&to='.$to.'&encoding=' . $encoding . '&message='.$message);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec ($c);
    curl_close ($c);
// echo $content;

	if (substr($content,0,2) == 'OK')
	{
		echo ("alert('Sendt SMS til $navn');");
	} else {
		echo ("Fejl $content');");
	}

?>
