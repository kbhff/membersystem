<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)
class Account extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function add_credit($puid, $amount, $comment, $authorized_by)
	{
		$data = array('puid' => $puid, 'authorized_by' => $authorized_by, 'comment' => $comment, 'amount' => $amount);
		$this->db->insert('credits', $data);
		return;
	}

	function add_debit($puid, $amount, $comment, $authorized_by, $method)
	{
		$data = array('puid' => $puid, 'authorized_by' => $authorized_by, 'comment' => $comment, 'amount' => $amount, 'method' => $method);
		$this->db->insert('transactions', $data);
		return;
	}

	function get_transactions($puid)
	{
		$sql = '
		SELECT "Kreditering" AS type, "" AS external_id, "" AS method, CONCAT("Krediteret af: ", authorized_by) AS authorized_by, comment, "" AS item, 
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method = "kreditering"
		
		UNION

		SELECT "Online køb" AS type, "" AS external_id, "" AS method, CONCAT("Ordre: ", orderno) AS authorized_by, "" AS comment, 
		(SELECT CONCAT(units, " ", measure, " ", ff_producttypes.explained)
		FROM (ff_items, ff_producttypes) 
		WHERE ff_items.id = item
		AND ff_producttypes.id = ff_items.producttype_id) AS item,
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method = "nets"
		AND ff_transactions.orderno > ""

		UNION
		
		SELECT "Kontant køb" AS type, "" AS external_id, "" AS method, CONCAT("Ordre: ", orderno) AS authorized_by, "" AS comment, 
		(SELECT CONCAT(units, " ", measure, " ", ff_producttypes.explained)
		FROM (ff_items, ff_producttypes) 
		WHERE ff_items.id = item
		AND ff_producttypes.id = ff_items.producttype_id) AS item,
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method = "kontant"

		ORDER BY created ASC';
		$query = $this->db->query($sql);
		return ($query->result_array());
	}


	function get_posts($member_id)
	{
		$sql = '
		SELECT "Kreditering" AS type, "" AS external_id, "" AS method, CONCAT("Krediteret af: ", authorized_by) AS authorized_by, comment, "" AS item, 
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (fc_credits)
		WHERE member_id = '.$member_id.'
		
		UNION
		
		SELECT "Køb" AS type, "" AS external_id, "" AS method, "" AS authorized_by, "" AS comment, 
		(SELECT CONCAT(units, " ", measure, " ", fc_producttypes.explained)
		FROM (fc_items, fc_producttypes) 
		WHERE fc_items.id = item
		AND fc_producttypes.id = fc_items.producttype_id) AS item,
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (fc_sales)
		WHERE member_id = '.$member_id.'

		UNION

		SELECT "Indbetaling" AS type, external_id, method, "" AS authorized_by, "" AS comment, "" AS item, 
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (fc_payments)
		WHERE member_id = '.$member_id.'

		ORDER BY created ASC';
		$query = $this->db->query($sql);
		return ($query->result_array());
	}
	
	function get_future_orders($member_id)
	{
		$sql = 'SELECT  ff_orderlines.orderno, ff_orderlines.quant, ff_items.measure, ff_producttypes.explained, ff_pickupdates.pickupdate, ff_producttypes.id, ff_pickupdates.uid, ff_orderhead.status1, ff_divisions.name, ff_orderlines.uid as orderlineid, ff_items.producttype_id as itemid, TIMESTAMPDIFF(minute,now(),lastorder) as cancel
		FROM  
		ff_pickupdates,
		ff_orderlines, 
		ff_orderhead,
		ff_persons,
		ff_division_members,
		ff_producttypes,
		ff_items, 
		ff_divisions,
		ff_itemdays		
		WHERE
		ff_persons.uid = ' . (int)$member_id . '
		AND ff_persons.uid = ff_orderlines.puid
		AND ff_division_members.member = ff_persons.uid
		AND ff_orderlines.orderno = ff_orderhead.orderno
		AND ((ff_orderhead.status1 = "nets") OR (ff_orderhead.status1 = "kontant"))
		AND ff_items.id = ff_orderlines.item
		AND ff_items.division = ff_division_members.division
		AND ff_divisions.uid =  ff_division_members.division
		AND ff_items.producttype_id = ff_producttypes.id
		AND ff_pickupdates.uid = ff_orderlines.iteminfo
		AND ff_pickupdates.pickupdate >= curdate()
		AND ff_itemdays.pickupday = ff_pickupdates.uid
		AND ff_itemdays.item = ff_items.producttype_id
		ORDER BY ff_pickupdates.pickupdate
		';
		$query = $this->db->query($sql);
		return ($query->result_array());
	}

	function get_open_pickupdays($puid, $item = FF_GROCERYBAG)
	{
		$sql = 'select ff_pickupdates.uid, ff_pickupdates.pickupdate,ff_divisions.name,ff_itemdays.lastorder, TIMESTAMPDIFF(minute,now(),lastorder) as cancel, ff_itemdays.item from ff_pickupdates, ff_itemdays, ff_division_members, ff_divisions where ff_divisions.uid = ff_division_members.division and ff_division_members.member = ' . $puid . ' and ff_division_members.division = ff_pickupdates.division and ff_pickupdates.uid = ff_itemdays.pickupday and ff_itemdays.lastorder >= now() and ff_itemdays.item =' . (int)$item;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_balance($puid)
	{
		$sql = '
		SELECT SUM(amount) AS amount
		FROM (' . $this->db->protect_identifiers('transactions', TRUE) . ')
		WHERE puid = '. (int)$puid;
		$query = $this->db->query($sql);
		$sums = $query->result_array();
		$total = 0;
		foreach($sums as $sum)
		{
			$total += $sum['amount'];
		}
		return $total;
	}
}
/* End of file account.php */
/* Location: ./models/account.php */