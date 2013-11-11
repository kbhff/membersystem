<? # $Id: error_hidden.php 131 2010-04-16 10:33:55Z torsten $ ?>
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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<?
	include("../../ressources/.mysql_common.php");
	include("../../ressources/.library.php");
	include("../../ressources/.kvittering.php");
	include("../../ressources/.sendmail.php");

?>
	<head>
		<title>Error Hidden</title>
</head>
<body>
<?

$today = strftime("%Y-%m-%d", time());

$errordes[0] = "Merchant/forretningsnummer ugyldigt";
$errordes[1] = "Ugyldigt kreditkortnummer";
$errordes[2] = "Ugyldigt beløb";
$errordes[3] = "OrderID mangler eller er ugyldig";
$errordes[4] = "PBS afvisning - (Oftest - ugyldig kortdata, spærret kort osv...). Se PBS ActionCodes";
$errordes[5] = "Intern server fejl hos DanDomain eller PBS";
$errordes[6] = "E-dankort ikke tilladt. Kontakt DanDomain";
$errordes[7] = "ewire ikke tilladt. Kontakt DanDomain";
$errordes[8] = "3-D Secure ikke tilladt. Kontakt DanDomain";
$errordes[9] = "ExpireMonth/ExpireYear Ugyldig.";
$errordes[10] = "Ugyldig kreditkort type. (Creditcard type does not match CardTypeID)";
$errordes[11] = "Ugyldig Checksum (Checksum mismatch)";
$errordes[12] = "Instant Capture failed";
$errordes[13] = "Recurring payments not allowed";
$errordes[14] = "OrderID must be unique within same date";
$errordes[15] = "Customer number for recurring payment must be unique";
$errordes[16] = "Recurring Subscribtion - max recurring limit reached (Max antal abonnementer nået - Kontakt DanDomain)";
$errordes[17] = "Invalid CurrencyID (Ugyldig valutaid)";

/* Fra Dandomain, http://help.dandomain.dk/paynet/DK/, 20jun2010

-1 = Der kunne ikke skabes kontakt til PBS (sker hvis PBS er nede)

  0 = Vellykket transaktion
  1 = Ugyldigt kreditkortnummer
  2 = Ugyldigt beløb
  3 = OrderID mangler eller er ugyldig
  4 = PBS afvisning -  Se PBS ActionCodes
  5 = Serverfejl hos DanDomain eller PBS
  6 = E-dankort ikke tilladt. Kontakt DanDomain
  7 = ewire ikke tilladt. Kontakt DanDomain
  8 = 3-D Secure ikke tilladt. Kontakt DanDomain
  9 = ExpireMonth/ExpireYear Ugyldig.
10 = Ugyldig kreditkorttype. (Creditcard type does not match CardTypeID)
11 = Ugyldig Checksum
12 = Instant Capture failed
13 = Recurring payments not allowed
14 = OrderID must be unique within same date
15 = Customer number for recurring payment must be unique
16 = Recurring Subscribtion - max recurring limit reached (Max antal abonnementer nået - Kontakt DanDomain)
17 = Invalid CurrencyID (Ugyldig valutaid)
18 = Fejl i recurring customer - tjek her fejlkode fra PBS

200 = Disse autorisationer er foretaget via Pay Webservice

*/
$status =  "$errorcode: " . $errordes[$errorcode];

if (($errorcode <> 4)&&($errorcode <> 2))
{
sendreceipt();
}
?>
</body>
</html>

<?







?>
