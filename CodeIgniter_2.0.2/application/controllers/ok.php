<? # $Id: ok.php 131 2010-04-16 10:33:55Z torsten $ ?>
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
	include("/www/medlem.kbhff.dk/ressources/.mysql_common.php");
	include("/www/medlem.kbhff.dk/ressources/.library.php");
	include("/www/medlem.kbhff.dk/ressources/.kvittering.php");
	include("/www/medlem.kbhff.dk/ressources/.sendmail.php");

?>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<title>Kvittering for betaling</title>
<?
echo ("<h3>Kvittering</h3>\n");

$today = strftime("%Y-%m-%d", time());
kvitgetorderhead($OrderID);
kvitgetorderlines($OrderID);

$kvittering = htmlentities($emailkvittering);
$kvittering = nl2br($kvittering);

echo ("$kvittering<br>\n");

?>
<br>
<br>
<?

if (getreceiptstatus($OrderID))
{
	echo ("Kvittering er sendt til $email.");
} else {
	echo ("Kvittering sendes til $email.");
	senderrormail("ok.php - kvittering ikke sendt - $OrderID, $today, $transact");
	sendreceipt();
	// Update status for Order
	updateorderhead($OrderID, $today, $transact);
}

?>

<p>Har du sp&oslash;rgsm&aring;l eller rettelser til din registrering, s&aring; send en E-mail til webmaster@kbhff.dk</p>
<br>
<br>

				</td>
			</tr>
	</table>
</body>
</html>

<?



?>
