<? # $Id: ok_hidden.php 180 2011-01-25 14:38:06Z torsten $ ?>
<?
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
 
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");
?>
<?
	include("../../ressources/.mysql_common.php");
	include("../../ressources/.library.php");
	include("../../ressources/.kvittering.php");
	include("../../ressources/.sendmail.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<head>
		<title>OK Hidden</title>
</head>
<body>

<html>

<?
$today = strftime("%Y-%m-%d", time());

//GET /ressources/admin/ok_hidden.php?transact=19194924&OrderID=108&CardType=Visa%2Fdankort&Attempts=1&AttemptsFromIPToday=1&Cardnumber=XXXXXXXXXXXX0121 
//			senderrormail("debug 334khidden - '$OrderID', '$transact', '$Cardnumber'");
$tmp = getreceiptstatus($OrderID);
if (getreceiptstatus($OrderID)> '')	// ff_orderhead.status3
{
	// echo ("'$tmp' kvittering sendt");
} else {
	// echo ("'$tmp' sender kvittering");
	$SessionID = '';
	$kvit = kvitgetorderhead($OrderID, $SessionID, $transact, $Cardnumber);
	$emailkvittering = kvitgetorderlines($OrderID, $SessionID, $kvit['kvittering']);
	sendreceipt($emailkvittering, $OrderID, $kvit['email'], $kvit['firstname'], $kvit['middlename'], $kvit['lastname']);
	updatetransactions($OrderID, $transact, $kvit['uid']);
}

updateorderhead($OrderID, $transact, $Cardnumber, ' hidden');
setpaid($OrderID);

?>

</body>
</html>


<?





function setpaid($orderno)
{
	global $db_conn;

	$orderno = doubleval($orderno);
	$query = "update ff_orderhead 
	set status1 = 'nets' 
	where orderno = $orderno 
	limit 1
	";
    $result = doquery ($query);

} // End setpaid



function capturedandomain($orderno,$amount)
{
	global $db_conn;
	$orderno = doubleval($orderno);
	$amount = doubleval($amount);
	$amountstr = str_replace('.',',',$amount);
$captureurl = "http://pay.dandomain.dk/remotecapture.asp?username=nnn&password=xxx&capture=1&orderid=$orderno&amount=$amountstr&DoAmountCheck=1&ShowStatusCodes=1";

$ch = curl_init($captureurl); 
// use output buffering instead of returntransfer -itmaybebuggy 
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($ch, CURLOPT_URL,$captureurl);
   $retrievedhtml = curl_exec ($ch);
// echo ("<p>debug: $captureurl<br>result: '$retrievedhtml'</p>");
   curl_close ($ch);

   

// if you intend to print this page with meta tags, better clear out any expiration tag 
//    $result = preg_replace('/(?s)<meta http-equiv="Expires"[^>]*>/i', '', $retrievedhtml); 
// for now I just want what is between the body tags so need 
// somehow cut the header footer    
$bodyandend = stristr($retrievedhtml,"<body"); 
// not needed- $positionstartbodystring = strlen($retrievedhtml)-strlen($bodyandend); 
$positionendstartbodytag = strpos($bodyandend,">") + 1; 
// got to change all to lowercase temporarily 
// because end body may be upperlowercasemix 
// to bad strirstr does not exist 
$temptofindposition=strtolower($bodyandend); 
$positionendendbodytag=strpos($temptofindposition,"</body"); 
//now to get the endbetween body tags 
$grabbedbody=substr($bodyandend, 
     $positionendstartbodytag, 
           $positionendendbodytag); 
//be sure to fix syntax broke by display on phpwebsite... like above line 

// Special case: Dandomain does not use <body>
	return $retrievedhtml ;
	
} // End capturedandomain


?>
