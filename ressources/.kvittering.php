<?  #$Id: .kvittering.php 101 2010-03-19 09:32:20Z torsten $

function kvitgetorderhead($OrderID, $orderkeycheck, $transact, $Cardnumber = '')
{
	global $db_conn;
	$orderno = doubleval($OrderID);
	$orderkey = addslashes($orderkey);
			$query = "select ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.sex, ff_persons.adr1, ff_persons.adr2, ff_persons.streetno, ff_persons.floor, ff_persons.adr3, ff_persons.zip, ff_persons.city, ff_persons.country, ff_persons.languagepref, ff_persons.tel, ff_persons.tel, ff_persons.email, ff_persons.birthday, ff_persons.password, ff_persons.status1, ff_persons.status2, ff_persons.status3, ff_persons.rights, ff_persons.privacy, ff_persons.ownupdate, ff_persons.created, ff_persons.changed, ff_persons.uid , ff_orderhead.orderno, ff_orderhead.puid, ff_orderhead.status1, ff_orderhead.cc_trans_no, ff_orderhead.cc_trans_amount, ff_orderhead.changed   from ff_orderhead, ff_persons where ff_orderhead.orderno = $orderno and ff_orderhead.orderkey = '$orderkey' and ff_orderhead.puid = ff_persons.uid ";
			$query = "select ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.sex, ff_persons.adr1, ff_persons.adr2, ff_persons.streetno, ff_persons.floor, ff_persons.adr3, ff_persons.zip, ff_persons.city, ff_persons.country, ff_persons.languagepref, ff_persons.tel, ff_persons.tel, ff_persons.email, ff_persons.birthday, ff_persons.password, ff_persons.status1, ff_persons.status2, ff_persons.status3, ff_persons.rights, ff_persons.privacy, ff_persons.ownupdate, ff_persons.created, ff_persons.changed, ff_persons.uid , ff_orderhead.orderno, ff_orderhead.puid, ff_orderhead.status1, ff_orderhead.status2, ff_orderhead.cc_trans_no, ff_orderhead.cc_trans_amount, date_format(ff_orderhead.changed, '%e-%c-%Y') , orderkey  from ff_orderhead, ff_persons where ff_orderhead.orderno = $orderno  and ff_orderhead.puid = ff_persons.uid ";
			if(!($result = @mysql_query($query, $db_conn)))
			{
				senderrormail("Order-lookup error! '$OrderID', '$orderkey', '$cc_trans_amount', '$transact', '$Cardnumber'");
			}

			if (mysql_num_rows($result)>0)
			{
		        $row = mysql_fetch_row($result);
				$firstname = $row[0];
				$middlename = $row[1];
				$lastname = $row[2];
				$sex = $row[3];
				$adr1 = $row[4];
				$adr2 = $row[5];
				$streetno = $row[6];
				$floor = $row[7];
				$adr3 = $row[8];
				$zip = $row[9];
				$city = $row[10];
				$country = $row[11];
				$languagepref = $row[12];
				$tel = $row[13];
				$tel2 = $row[14];
				$email = $row[15];
				$birthday = $row[16];
//				$club = $row[17];
				$password = $row[17];
				$status1 = $row[18];
				$status2 = $row[19];
				$status3 = $row[20];
				$rights = $row[21];
				$privacy = $row[22];
				$ownupdate = $row[23];
				$created = $row[24];
				$changed = $row[25];
				$uid = $row[26];
				$personuid = $row[27];
				$orderno = $row[28];
				$orderheadstatus1 = $row[29];
				$orderheadstatus2 = $row[30];
//				$transact = $row[31];
				$cc_trans_amount = $row[32];
				$date = $row[33];
				$orderkey = $row[34];
/*
				if ($orderkey <> $orderkeycheck)
			{
				senderrormail("Fejl i ordre '$OrderID', orderkey: '$orderkey' orderkeycheck: '$orderkeycheck'");
				echo("Ordre $OrderID eksisterer ikke! <!--\n$query \n-->");
				exit;
			}
*/
			} else {
				echo("Ordre $OrderID eksisterer ikke! <!--\n$query \n-->");
				exit;
			}


if ($adr1 >"")
{
	$adr1 = $adr1 . " ";
}

if ($adr3 >"")
{
	$adr3 = $adr3 . " ";
}

if ($floor >"")
{
	$floor = ", " . $floor;
}

if ($transact > '')
{
$emailkvittering = "
Ordrenummer $OrderID:
Betaling: $cc_trans_amount kr.
Betalingsdato: $date
NETS transaktionsnummer: $transact
Kortnummer: $Cardnumber
";

} else {
$emailkvittering = "
Ordrenummer $OrderID:
Betaling: $cc_trans_amount kr.
Betalingsdato: $date
Betaling: kontant
";
}

if ($middlename == '')
{
	$mname = " ";
} else {
	$mname = " $middlename ";
}
$emailkvittering .= "
Betaler: Medlem $uid
$firstname$mname$lastname
$adr1$adr2 $streetno$floor
$adr3$zip $city $country
$email
";

$return = array();
$return['kvittering'] = $emailkvittering;
$return['uid'] = $uid;
$return['email'] = $email;
$return['firstname'] = $firstname;
$return['middlename'] = $middlename;
$return['lastname'] = $lastname;
return $return;

} // End getorderhead


function kvitgetorderlines($orderno, $orderkey, $emailkvittering)
{
	global $db_conn;

	$emailkvittering .= "\nOrdredetaljer:\n";
	
	$orderno = doubleval($orderno);
	$puid = doubleval($puid);
	$item = addslashes($item);

	$query = "SELECT ff_orderlines.orderno, ff_orderlines.orderkey, ff_orderlines.item, ff_orderlines.quant, ff_orderlines.iteminfo, ff_orderlines.puid, ff_orderlines.amount, ff_orderlines.vat_amount, ff_orderlines.status1, ff_orderlines.status2, ff_orderlines.status3, ff_orderlines.created, ff_orderlines.changed, ff_orderlines.uid, ff_divisions.name, ff_pickupdates.pickupdate, ff_producttypes.explained
FROM (
ff_orderlines, ff_producttypes, ff_divisions, ff_items
)
LEFT JOIN (
ff_pickupdates
) ON ( ff_pickupdates.uid = ff_orderlines.iteminfo)
WHERE orderno = $orderno
AND ff_items.id = ff_orderlines.item
AND ff_items.producttype_id = ff_producttypes.id
AND ff_items.division = ff_divisions.uid
ORDER BY puid, ff_pickupdates.pickupdate, ff_producttypes.explained ";

// DEBUG
// echo ("<!--\n$query\n -->");
    if(!($result = @mysql_query($query, $db_conn)))
    {
		echo("Fejl: $orderno - getorderlines");
    }
	 $no = mysql_num_rows($result);
       while ($row = mysql_fetch_row($result))
		{
			$orderno = $row[0];
			$orderkey = $row[1];
			$item = $row[2];
			$quant = $row[3];
			$iteminfo = $row[4];
			$puid = $row[5];
			$amount = $row[6];
			$vat_amount = $row[7];
			$status1 = $row[8];
			$status2 = $row[9];
			$status3 = $row[10];
			$created = $row[11];
			$changed = $row[12];
			$uid = $row[13];
			$division = $row[14];
			$pickupdate = $row[15];
			$explained = $row[16];


				$emailkvittering .=  "$division $pickupdate: $quant $explained";
				if ($amount > 0)
				{
					$emailkvittering .= " $amount $vatinfo\n";
				} else {
					$emailkvittering .= "\n";
				}

     }
	return $emailkvittering;

} // End kvitgetnormorderlines






?>
