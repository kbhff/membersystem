<?
	include("../ressources/.mysql_common.php");
	require_once("class.inputfilter_clean.php");
?>
<?php 


$tags = '';
$attr = '';
$tag_method = 0;
$attr_method = 0;
$xss_auto = 1;
$myFilter = new InputFilter($tags, $attr, $tag_method, $attr_method, $xss_auto);

// submitbutton=Udlever1

$divisionday = $myFilter->process($_GET["divisionday"]);
$medlem = $myFilter->process($_GET["submitbutton"]);
$medlemsnummer = str_replace('Udlever', '', $medlem);
$return['medlem'] = $medlemsnummer;


$query = 'SELECT 
		ff_orderlines.item as article, ff_pickupdates.pickupdate as pickupdate, ff_divisions.name as name, ff_items.units, ff_items.measure, ff_producttypes.explained as txt, ff_orderlines.quant,
		ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_persons.uid, ff_orderlines.status2, ff_orderlines.uid
		FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons
		WHERE ff_orderlines.orderno = ff_orderhead.orderno 
		AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
		AND ff_orderlines.item = ff_items.id
		AND ff_items.producttype_id = ff_producttypes.id 	
		AND ff_orderlines.iteminfo = ff_pickupdates.uid
		AND ff_divisions.uid = ff_pickupdates.division
		AND ff_pickupdates.division = ff_items.division
		AND ff_orderlines.puid = ff_persons.uid
		AND ff_pickupdates.uid = ' . (int)$divisionday . '
		AND ff_persons.uid = ' . (int)$medlemsnummer . '
		ORDER BY ff_pickupdates.pickupdate, ff_producttypes.explained ';
		
$result = doquery($query);
	$num = mysql_num_rows($result);
     if ($num>0) {
		$row = mysql_fetch_row($result);
		if ($row[13] == 'udleveret')
		{
			$return['error'] = true;
			$return['msg'] = 'Pose allerede udleveret til medlem #' . $medlemsnummer . utf8_encode(",\n $row[7] $row[8] $row[9]");
			$return['receipt'] = utf8_encode("Medlem $row[12], $row[7] $row[8] $row[9], afhentet $row[6] $row[4] $row[5]<br>\n");
		} else {
			$return['error'] = false;
			$return['msg'] = 'Pose udleveret til medlem #' . $medlemsnummer . utf8_encode(",\n $row[7] $row[8] $row[9]") ;
//			$return['receipt'] = utf8_encode("Medlem $row[12], $row[7] $row[8] $row[9], afhentet $row[6] $row[4] $row[5] <a href=\"/udlevering/$divisionday/fortryd/$row[14]\">Fortryd</a><br>\n");
			$return['receipt'] = utf8_encode("Medlem $row[12], $row[7] $row[8] $row[9], afhentet $row[6] $row[4] $row[5] <br>\n");
			setpicked($row[14]);
		}
	} else {
		$return['error'] = true;
		$return['msg'] = 'Der er ingen ordre fra medlem ' . $medlemsnummer ;
	}

	$return['numtotalorders'] = getcount($divisionday, '');
	$return['numudleveret'] = getcount($divisionday, 'udleveret');
	
echo json_encode($return);

function setpicked($uid)
{
	$query = 'update ff_orderlines set status2 = "udleveret" where uid = ' . (int)$uid . ' limit 1';
	$result = doquery($query);
	$num = mysql_affected_rows();
	if ($num <> 1) echo ("<p>Fejl - allerede udleveret / findes ikke</p>");
	
}

function getcount($divisionday, $status)
{
	
	if ($status == 'udleveret')
	{

$query = 'SELECT sum( ff_orderlines.quant ) , ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND (
(
ff_orderhead.status1 = "kontant"
)
OR (
ff_orderhead.status1 = "nets"
)
)
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_divisions.uid = ff_pickupdates.division
AND ff_pickupdates.division = ff_items.division
AND ff_orderlines.status2 = "udleveret"
AND ff_pickupdates.uid = ' . (int)$divisionday . ' ' .
'GROUP BY ff_orderlines.item
ORDER BY ff_orderlines.item';

	} else {
$query = 'SELECT sum( ff_orderlines.quant ) , ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND (
(
ff_orderhead.status1 = "kontant"
)
OR (
ff_orderhead.status1 = "nets"
)
)
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_divisions.uid = ff_pickupdates.division
AND ff_pickupdates.division = ff_items.division
AND ff_pickupdates.uid = ' . (int)$divisionday . ' ' .
'GROUP BY ff_orderlines.item
ORDER BY ff_orderlines.item';
	}
	$result = doquery($query);

	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_row($result);
		$num = $row[0];
	}
	return $num;
}
?>
