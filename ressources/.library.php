<? #$Id: .persons_common.php 88 2010-02-09 12:30:48Z torsten $

function sendreceipt($emailkvittering, $OrderID, $email, $firstname, $middlename, $lastname)
{
//	global $db_conn, $transact, $today, $firstname, $middlename, $lastname, $email, $Attempts;


$subject = "Kvittering for betaling til KBHFF, ordre $OrderID";
if ($middlename == '')
{
	$mname = " ";
} else {
	$mname = " $middlename ";
}

$mailcontent = 	"Kære $firstname$mname$lastname\n"
			.	$emailkvittering
			.	"\nMed venlig hilsen\nKBHFF web-robotten";

$fromaddress = "dontreply@KBHFF.dk";
sendenkeltmail ($subject,$mailcontent,$email, $fromaddress);

} // End sendreceipt


function updateorderhead($orderno, $transact, $Cardnumber = '', $from = '')
// 'ok' version
{
	global $db_conn;
	$transact = doubleval($transact);
	$orderno = doubleval($orderno);
	if ($Cardnumber > '')
	{
		$query = "update ff_orderhead 
		set status1 = 'nets', status2 = '" . addslashes($Cardnumber) ."', status3 = 'ok$from', cc_trans_date = now(), changed= now(), cc_trans_no = '$transact' 
		where orderno = $orderno
		limit 1
		";
	} else{
		$query = "update ff_orderhead 
		set status1 = 'nets', status3 = 'ok$from', cc_trans_date = now(), changed= now(), cc_trans_no = '$transact' 
		where orderno = $orderno
		limit 1
		";
	}
			if(!($result = @mysql_query($query, $db_conn)))
			{
				senderrormail("$orderno, $today, $transact updateorderhead error");
			}

} // End updateorderhead

function updatetransactions($OrderID, $transact, $puid)
{
	global $db_conn;
	$transact = doubleval($transact);
	$OrderID = doubleval($OrderID);
	$puid = doubleval($puid);
	$query = 'select cc_trans_amount from ff_orderhead where orderno = ' . $OrderID;
		if(!($result = @mysql_query($query, $db_conn)))
			{
				senderrormail("updatetransactions getamount error");
			}
		if (mysql_num_rows($result)>0)
	{
        $row = mysql_fetch_row($result);
        $amount = doubleval($row[0]);
	} 
	
	$query = "select puid from ff_transactions 
	where 
	$puid = $puid and amount = $amount and orderno = $OrderID 
	";
		if(!($result = @mysql_query($query, $db_conn)))
			{
				senderrormail("updatetransactions getamount error");
			}
		if (mysql_num_rows($result) == 0)
	{
		$query = "insert into ff_transactions 
		(puid, amount, authorized_by, orderno, method, trans_id, item, created)
		values
		($puid, $amount, 0, $OrderID , 'nets', $transact, 0, now())
		";
	
		if(!($result = @mysql_query($query, $db_conn)))
		{
			senderrormail("updatetransactions error\n$query");
		}
	} else {
		senderrormail("updatetransactions transaction fandtes \n$query");
	}



} // End updatetransactions


function updateorderamount($orderno, $amount)
{
	global $db_conn;
	$orderno = doubleval($orderno);
	$query = "update ff_orderhead 
	set changed = now(), cc_trans_amount = " . doubleval($amount)  . "
	where orderno = $orderno
	limit 1
	";
			if(!($result = @mysql_query($query, $db_conn)))
			{
				senderrormail("$orderno, $amount  updateorderamount error\n$query");
			}

} // End updateorderamount

function senderrormail($msg)
{
	if (getenv("SERVER_NAME") == 'kbhff.skrig.dk')
	{
		$subject = "KBHFF FEJL i Betalingssystem";
		$mailcontent = 	"ERROR: $msg\n";
	} else {
		$subject = "LIVE KBHFF FEJL i Betalingssystem";
		$mailcontent = 	"LIVE ERROR: $msg\n";
	}
	$fromaddress = "From: \"KBHFF ERROR\" <dontreply@KBHFF.dk>\r\nReply-to: dontreply@KBHFF.dk";
	$commandline = "-fwebdontreply@KBHFF.dk";
	$debugemail = "\"KBHFF\" <webmaster@arendrup.dk>";
	$mail_val=mail ($debugemail, $subject, $mailcontent, $fromaddress, $commandline);

} // end senderrormail()


function create_password($str = '')
{
	$password = md5(uniqid(rand(), true));
//	$password = crypt($password,$str);
	$password = str_replace("l","",$password);
	$password = str_replace("1","",$password);
	$password = str_replace("O","",$password);
	$password = str_replace("0","",$password);
	$password = substr($password . $password,2,10);
	return ($password);
}

function createperson($email, $password, $firstname, $middlename, $lastname, $sex, $adr1, $adr2, $streetno, $floor, $adr3, $zip, $city, $country, $languagepref, $tel, $mobil, $email, $birthday, $club, $status1 ='', $status2='', $status3='')
{
	global $db_conn;

	$now_time = time();
	$created = strftime("%Y-%m-%d", $now_time);
	$changed = strftime("%Y-%m-%d", $now_time);
	$rights = 100000;
	$firstname = addslashes($firstname);
	$middlename = addslashes($middlename);
	$lastname = addslashes($lastname);
	$sex = addslashes($sex);
	$adr1 = addslashes($adr1);
	$adr2 = addslashes($adr2);
	$streetno = addslashes($streetno);
	$floor = addslashes($floor);
	$adr3 = addslashes($adr3);
	$zip = addslashes($zip);
	$city = addslashes($city);
	$country = addslashes($country);
	$languagepref = addslashes($languagepref);
	$tel = addslashes($tel);
	$mobil = addslashes($mobil);
	$email = addslashes($email);
	$club = addslashes($club);
	$password = addslashes($password);
	$status1 = addslashes($status1);
	$status2 = addslashes($status2);
	$status3 = addslashes($status3);
	$rights = addslashes($rights);
	$privacy = addslashes($privacy);

// Trim blanks
$firstname = trim($firstname);
$middlename = trim($middlename);
$lastname = trim($lastname);
$sex = trim($sex);
$adr1 = trim($adr1);
$adr2 = trim($adr2);
$streetno = trim($streetno);
$floor = trim($floor);
$adr3 = trim($adr3);
$zip = trim($zip);
$city = trim($city);
$country = trim($country);
$languagepref = trim($languagepref);
$tel = trim($tel);
$mobil = trim($mobil);
$email = trim($email);
$club = trim($club);
$password = trim($password);
$status1 = trim($status1);
$status2 = trim($status2);
$status3 = trim($status3);
$rights = trim($rights);
$privacy = trim($privacy);

$firstname = my_ucwords($firstname, $is_name=true);
$middlename = my_ucwords($middlename, $is_name=true);
$lastname = my_ucwords($lastname, $is_name=true);

if ($country == '')
{
	$country = 'DK';
}

	if ($password == "")
	{
		$password = create_password("$firstname$email");
	}
	$fields = "firstname, middlename, lastname, sex, adr1, adr2, streetno, floor, adr3, zip, city, country, languagepref, tel, mobil, email, birthday, club, password, status1, status2, status3, rights, privacy, ownupdate, created, changed, uid";
	$values = "'$firstname', '$middlename', '$lastname', '$sex', '$adr1', '$adr2', '$streetno', '$floor', '$adr3', '$zip', '$city', '$country', '$languagepref', '$tel', '$mobil', '$email', '$birthday', '$club', '$password', '$status1', '$status2', '$status3', '$rights', '$privacy', '$ownupdate', '$created', '$changed', '$uid'";
	$query = "insert into ff_persons ($fields) values ($values)";
	$query = mac2ibm($query);
    if(!($result = @mysql_query($query, $db_conn)))
    {
	    echo("Error : $errstr\n");
        exit;
    }

	// get puid
	$query = "SELECT LAST_INSERT_ID()";
    if(!($result = @mysql_query($query, $db_conn)))
    {
	    echo("Error : $errstr\n");
        exit;
    }
		if (mysql_num_rows($result)>0)
	{
        $row = mysql_fetch_row($result);
        $uid = doubleval($row[0]);
		return($uid);
	} 
	// end get puid
	
}

function my_ucwords($str, $is_name=false) {
	setlocale(LC_CTYPE, 'da_DK');     
   // exceptions to standard case conversion
   if ($is_name) {
       $all_uppercase = '';
       $all_lowercase = 'De La|De Las|Der|Van De|Van Der|Vit De|Von|Or|And';
   } else {
       // addresses, essay titles ... and anything else
       $all_uppercase = 'Po|Rr|Se|Sw|Ne|Nw';
       $all_lowercase = 'A|And|As|By|In|Of|Or|To';
   }
   $prefixes = 'Mc';
   $suffixes = "'S";

   // captialize all first letters
   $str = preg_replace('/\\b(\\w)/e', 'strtoupper("$1")', strtolower(trim($str)));

   if ($all_uppercase) {
       // capitalize acronymns and initialisms e.g. PHP
       $str = preg_replace("/\\b($all_uppercase)\\b/e", 'strtoupper("$1")', $str);
   }
   if ($all_lowercase) {
       // decapitalize short words e.g. and
       if ($is_name) {
           // all occurences will be changed to lowercase
           $str = preg_replace("/\\b($all_lowercase)\\b/e", 'strtolower("$1")', $str);
       } else {
           // first and last word will not be changed to lower case (i.e. titles)
           $str = preg_replace("/(?<=\\W)($all_lowercase)(?=\\W)/e", 'strtolower("$1")', $str);
       }
   }
   if ($prefixes) {
       // capitalize letter after certain name prefixes e.g 'Mc'
       $str = preg_replace("/\\b($prefixes)(\\w)/e", '"$1".strtoupper("$2")', $str);
   }
   if ($suffixes) {
       // decapitalize certain word suffixes e.g. 's
       $str = preg_replace("/(\\w)($suffixes)\\b/e", '"$1".strtolower("$2")', $str);
   }
   return "$str";
}

function createuniqueorderno($puid, $status1 = 'new')
{
			global $db_conn, $orderkey;
			$personuid = doubleval($personuid);
			$lock = 0;
			while ($lock == 0)
			{
				$query = "select COALESCE(GET_LOCK('kbhff_create_orderno', 1), 0)";
				$result = doquery($query);
		        $row = mysql_fetch_row($result);
		        $lock = $row[0];
				
				if ($lock ==1)
				{	
					$query = "select orderno from ff_orderhead order by orderno desc limit 1 ";
					$result = doquery($query);
		
					if (mysql_num_rows($result)>0)
					{
				        $row = mysql_fetch_row($result);
				        $orderno = doubleval($row[0]);
					} else {
				        $orderno = 0;
					}
		
					// Got orderno
					$status1 = addslashes($status1);
					$orderno++; // Now increase by 1
					$orderkey = uniqid("$orderno");
					$fields = "puid, orderno, orderkey, status1";
					$values = "'$puid', '$orderno', '$orderkey', '$status1'";
					$query = "insert into ff_orderhead ($fields) values ($values)";
					$query = mac2ibm($query);
					$result = doquery($query);
				}
			}

			$query = "select RELEASE_LOCK('kbhff_create_orderno')";
			$result = doquery($query);
			
			$return = array();
			$return['orderno'] = $orderno;
			$return['orderkey'] = $orderkey;
			
			return($return);

} // End createuniqueorderno

function getreceiptstatus($orderno)
{
	global $db_conn;

	$orderno = doubleval($orderno);
	$query = "select status3 from ff_orderhead where orderno = $orderno ";
    if(!($result = @mysql_query($query, $db_conn)))
    {
		senderrormail("$orderno - getreceiptstatus");
    }

	 if (mysql_num_rows($result) > 0)
	 {
		$row = mysql_fetch_row($result);
		$status = $row[0];
		if ($status > "")
		{
			return	"sendt";
		}
	}
} // End getreceiptstatus


?>
