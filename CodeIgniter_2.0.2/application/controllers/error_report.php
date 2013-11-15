<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<?
	include("/www/medlem.kbhff.dk/ressources/.mysql_common.php");
	include("/www/medlem.kbhff.dk/ressources/.library.php");
	include("/www/medlem.kbhff.dk/ressources/.kvittering.php");
	include("/www/medlem.kbhff.dk/ressources/.sendmail.php");
?>
<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<title>Online betaling: Trin 5 af 5</title>
</head>
<body>
<br>
<?
if ($errorcode == 14)
{
	echo ("<h2>Fejl: Ordre $OrderID er allerede betalt.</h2><br>\n");
} else {
	echo ("<h2>Betaling <strong>ikke</strong> gennemf&oslash;rt for ordre $OrderID .</h2><br>\n");
}
?>

<br>
<strong>&Aring;rsag:</strong><br>
<br>
<?

/* Fra Dandomain, http://help.dandomain.dk/paynet/DK/, 20jun2010

-1 = Der kunne ikke skabes kontakt til NETS (sker hvis NETS er nede)

  0 = Vellykket transaktion
  1 = Ugyldigt kreditkortnummer
  2 = Ugyldigt beløb
  3 = OrderID mangler eller er ugyldig
  4 = NETS afvisning -  Se NETS ActionCodes
  5 = Serverfejl hos DanDomain eller NETS
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
18 = Fejl i recurring customer - tjek her fejlkode fra NETS

200 = Disse autorisationer er foretaget via Pay Webservice

*/


$errordes[0] = "Merchant/forretningsnummer ugyldigt";
$errordes[1] = "Ugyldigt kreditkortnummer";
$errordes[2] = "Ugyldigt beløb";
$errordes[3] = "OrderID mangler eller er ugyldig";
$errordes[4] = "NETS afvisning - (Oftest - ugyldig kortdata, spærret kort osv...). Se NETS ActionCodes";
$errordes[5] = "Intern server fejl hos DanDomain eller NETS";
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

echo ("<strong>$errordes[$errorcode]</strong>\n");;

if ($errorcode == 14)
{

} else {

	echo ("<em>Kreditkort ikke accepteret eller forkert indtastning:</em><br>\n");
	senderrormail("Betalingsfejl, eventordre $order");
	echo ("<h2>Fors&oslash;g betaling af denne ordre igen: <a href=\"http://$payserver/onlinebet3.php?orderkey=$SessionID&orderno=$OrderID\">klik her</a></h2>    <br>\n");
	}


?>
<br>
   
<br clear="all">
Hvis du bliver afvist uden grund, kan det v&aelig;re at NETS har driftsproblemer og
vi kan kun opfordre til at pr&oslash;ve igen senere. Selve transaktionen ligger udenfor vores dom&aelig;ne,
og vi kan derfor ikke g&oslash;re noget.
<br>		
<br>		
<br>		
		
