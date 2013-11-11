<?php
	unset($_GET["_"]);
	require_once("class.inputfilter_clean.php");

$tags = '';
$attr = '';
$tag_method = 0;
$attr_method = 0;
$xss_auto = 1;
$myFilter = new InputFilter($tags, $attr, $tag_method, $attr_method, $xss_auto);

$email = $myFilter->process($_GET["email"]);
$navn = $myFilter->process($_GET["navn"]);
$afd = $myFilter->process($_GET["afd"]);


echo ("alert('Sendt besked til $email');");

?>
