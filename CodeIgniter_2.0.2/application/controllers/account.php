<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
// echo $this->db->last_query();
		return;
	}

	function add_debit($puid, $amount, $comment, $authorized_by, $method)
	{
		$data = array('puid' => $puid, 'authorized_by' => $authorized_by, 'comment' => $comment, 'amount' => $amount, 'method' => $method);
		$this->db->insert('transactions', $data);
// echo $this->db->last_query();
		return;
	}

	function get_transactions($puid)
	{
		$sql = '
		SELECT "Kreditering" AS type, "" AS external_id, "" AS method, CONCAT("Krediteret af: ", authorized_by) AS authorized_by, comment, "" AS item, 
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method = "kontant"
		
		UNION

		SELECT "Indbetaling" AS type, "" AS external_id, "" AS method, CONCAT("Ordre: ", comment) AS authorized_by, "" AS comment, 
		(SELECT CONCAT(units, " ", measure, " ", ff_producttypes.explained)
		FROM (ff_items, ff_producttypes) 
		WHERE ff_items.id = item
		AND ff_producttypes.id = ff_items.producttype_id) AS item,
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method = "online"
		AND ff_transactions.comment > ""

		UNION
		
		SELECT "Køb" AS type, trans_id, CONCAT(method, comment), "" AS authorized_by, "" AS comment, "" AS item, 
		amount, UNIX_TIMESTAMP(created) AS created, WEEK(created) AS weeknumber
		FROM (ff_transactions)
		WHERE puid = '.$puid.'
		AND ff_transactions.method <> "kontant"

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