<?
require('../global_config.php');
setlocale(LC_CTYPE, 'da_DK');

if (!$db_conn = @mysql_connect(GLOBAL_DB_HOST, GLOBAL_DB_USER, GLOBAL_DB_PASS))
{
	echo "Kunne ikke tilsluttes databasen<br>";
	exit;
};
@mysql_select_db(GLOBAL_DB_NAME);


// error_reporting  (E_ERROR | E_WARNING | E_PARSE);
error_reporting  (E_ALL & ~E_NOTICE);

function error_handler ($level, $message, $file, $line, $context) {

if (!($level & error_reporting())) return;

$file = str_replace ("/srv/www/htdocs/events4u/","",$file);
$advarsel = "Error";
if ($level == 1) { $advarsel = "Fejl"; }
if ($level == 2) { $advarsel = "Advarsel"; }
if ($level == 4) { $advarsel = "Syntaksfejl"; }
if ($level == 8) { $advarsel = "Mulig fejl"; }

if ($level == 16) { $advarsel = "CORE_ERROR"; }
if ($level == 32) { $advarsel = "CORE_WARNING"; }
if ($level == 64) { $advarsel = "COMPILE_ERROR"; }
if ($level == 128) { $advarsel = "COMPILE_WARNING"; }
if ($level == 256) { $advarsel = "USER_ERROR"; }
if ($level == 512) { $advarsel = "USER_WARNING"; }
if ($level == 1024) { $advarsel = "USER_NOTICE"; }
if ($level == 2047) { $advarsel = "ERROR"; }

echo <<<_END_

<strong>$advarsel</strong> i $file, linie $line.<br>
Fejlen rapporteres som: $message<br>

_END_;

}

set_error_handler ('error_handler');


function mac2ibm ($str )
{
	$str = str_replace("æ", "Ê",$str);
	$str = str_replace("ø", "¯",$str);
	$str = str_replace("å", "Â",$str);
	$str = str_replace("Æ", "∆",$str);
	$str = str_replace("Ø", "ÿ",$str);
	$str = str_replace("Å", "≈",$str);
	$str = str_replace("”", "&#39;",$str);
	$str = str_replace("É", "…",$str);
	$str = str_replace("ä", "‰",$str);
	$str = str_replace("ö", "ˆ",$str);
	$str = str_replace("Ä", "ƒ",$str);
	$str = str_replace("Ö", "÷",$str);
	$str = str_replace("ü", "¸",$str);
	$str = str_replace("Ü", "‹",$str);
	$str = str_replace("é", "È",$str);

	return ( $str );
}


function dooption($opt, $leg, $d_leg = '', $class = '')
	{
		if (func_num_args() == 4)
		{
			$class= " class=\"$class\"";
		} else {
			$class= "";
		}

		echo ("\t\t\t\t<option value=\"$leg\"$class");
		if ($opt == $leg)
		{
			echo (' selected="selected"');
		}

		if (func_num_args() > 2)
		{
			echo (">$d_leg</option>\n");
		} else {
			echo (">$leg</option>\n");
		}
	}

function doquery($query)
{
global $db_conn;
    if(!($result = @mysql_query($query, $db_conn)))
    {
		echo "<strong>Error:</strong> ";
		echo mysql_errno($db_conn);
		echo " -  ";
		echo mysql_error($db_conn);
		exit;
    }
    return $result;
}


?>
