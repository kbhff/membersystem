<?
require_once(dirname(__FILE__) . "/../global_config.php");
setlocale(LC_CTYPE, 'da_DK');

if (!$db_conn = @mysql_connect(GLOBAL_DB_HOST, GLOBAL_DB_USER, GLOBAL_DB_PASS))
{
	echo "Kunne ikke tilsluttes databasen<br>";
	exit;
};
@mysql_select_db(GLOBAL_DB_NAME);


// error_reporting  (E_ERROR | E_WARNING | E_PARSE);
error_reporting  (E_ALL & ~E_NOTICE);

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
