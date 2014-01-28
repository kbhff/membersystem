<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends CI_Controller {

    function __construct()
    {
		$this->_nocache();
        parent::__construct();
		$this->load->helper('menu');
		$this->load->helper('url');
        $this->load->library('javascript');
    }


    function index() 
	{
	$this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//	$js =$this->jquery->corner('#tt');
//	$this->javascript->output($js);
//	$this->javascript->compile();
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
	$orderno = doubleval($orderno);
	// Print all orderdetails and calculate sum
	setlocale(LC_MONETARY, 'da_DK');
	$total = $this->input->get_post('amount');
	// Create orderhead
	$neworder = createuniqueorderno($this->session->userdata('uid'));
	$orderno = $neworder['orderno'];
	$orderkey = $neworder['orderkey'];
	// Create orderlines
	$item = $this->input->get_post('item');
	$pickupdate = $this->input->get_post('pickupdate');
	$itemamount = $this->input->get_post('itemamount');
	$quant = $this->input->get_post('quant');
	$status = 'Ingen ordrelinier<br>';
	$temp_total = 0;
			$vat_amount = 0;
	if (is_array($item)) // meaning, more than table-line
	{
		while (list($key,$value)=each($item))
		{
			$amount = $quant[$key] * $itemamount[$key];
			$temp_total += $amount;
			if ($quant[$key] > 0)	// TODO: Check for change to existing order
			{
				$status = '<!-- Ordrelinier gemt. -->';
			    $this->_insertpickuporderline($orderno, $orderkey, $item[$key], $this->session->userdata('uid'), $pickupdate[$key], $quant[$key], $amount, $vat_amount) ;
			}
		}

	} else {
			$amount = $this->input->get_post('quant') * $itemamount;
			$temp_total += $amount;
			$qfield = "new$pickupdate";
			if ($qfield > 0)	// TODO: Check for change to existing order
			{
			$status = '<!-- Ordrelinie gemt. -->';
			    $this->_insertpickuporderline($orderno, $orderkey, $item, $this->session->userdata('uid'), $pickupdate, $quant, $amount, $vat_amount) ;
			}
	}
		$pickuplines =  $this->_getpicuporders($orderno) ;

		$data = array(
              'total' => $total,
              'orderkey' => $orderkey,
              'orderno' => $orderno,
              'pickuplines' => $pickuplines,
              'status' => $status,
         );

		// Update orderhead with amount
		updateorderamount($orderno, $total);

		$this->load->view('onlinebet3', $data);
	
	}

    function pay4() 
	{

		$total = $this->input->get_post('Amount');
		$orderkey = $this->input->get_post('SessionID');
		$orderno = $this->input->get_post('OrderID');
		$admin = $this->input->get_post('admin');
		$member = $this->input->get_post('member');
		
		$data = array(
              'total' => $total,
              'orderkey' => $orderkey,
              'orderno' => $orderno,
              'member' => $member,
         );

		$this->load->view('onlinebet4', $data);
	
	}


    function fail() 
	{
// Incoming:
// OrderID SessionID errorcode  ActionCode
	$orderkey = $this->input->get_post('SessionID');
	$orderno = $this->input->get_post('OrderID');

	$orderinfo = $this->_get_orderinfo($orderno);
	$data = array(
              'title' => 'Fejl under fors&oslash;g p&aring; betaling',
              'heading' => 'Fejl under fors&oslash;g p&aring; betaling',
              'orderno' => $orderno,
              'amount' => $orderinfo->cc_trans_amount,
			  'orderkey' => $orderinfo->orderkey,
			  'debug' => $orderinfo,
         );

	$this->load->view('v_pay_fail.php', $data);
	
	}

    function ok($orderkey = '', $member ='') 
	{

		$this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
		$this->javascript->output($js);
		$this->javascript->compile();

		// http://kbhff.skrig.dk/pay/ok?transact=19109947&OrderID=13&Attempts=1&Cardnumber=XXXXXXXXXXXX0121
		$transact = $this->input->get_post('transact', true);
		$OrderID = $this->input->get_post('OrderID', true);
		$Cardnumber = $this->input->get_post('Cardnumber', true);

		$today = strftime("%Y-%m-%d", time());
		$kvit = kvitgetorderhead($OrderID, $orderkey, $transact, $Cardnumber);

		$emailkvittering = kvitgetorderlines($OrderID, $orderkey, $kvit['kvittering']);

		$kvittering = htmlentities($emailkvittering);
		$kvittering = nl2br($kvittering);

		if (getreceiptstatus($OrderID)> '')	// ff_orderhead.status3
		{
			// er sendt
		} else {
			senderrormail("ok - kvittering ikke sendt - $OrderID, $today, $transact");
			sendreceipt($emailkvittering, $OrderID, $kvit['email'], $kvit['firstname'], $kvit['middlename'], $kvit['lastname']);
			// Update status for Order
			updateorderhead($OrderID, $transact, $Cardnumber, ' ok');
			updatetransactions($OrderID, $transact, $kvit['uid']);
		}

		if ($member)
		{
			$membercontent = '<br>Velkommen som nyt medlem!';
			$this->_activate_new_member($OrderID, $kvit['uid']);
		}

	$data = array(
              'title' => 'Registrering af betaling',
              'heading' => 'Registrering af betaling, trin 5/5',
              'content' => '<strong>Ordre ' . $OrderID . ' er betalt.</strong>' . $membercontent . '<br>' 
			  . $kvittering,
         );

	$this->load->view('page_noscript', $data);
	
	}

    function _insertpickuporderline($orderno, $orderkey, $item, $puid, $iteminfo, $quant, $amount, $vat_amount) 
	{
		$itemid = $this->_getitemid($item, $iteminfo);
		$this->db->set('orderno', $orderno);
		$this->db->set('orderkey', $orderkey);
		$this->db->set('puid', $puid);
		$this->db->set('item', $itemid);
		$this->db->set('iteminfo', $iteminfo);
		$this->db->set('quant', $quant);
		$this->db->set('amount', $amount);
		$this->db->set('vat_amount', $vat_amount);
		$this->db->set('status1', '');
		$this->db->set('status2', '');
		$this->db->set('status3', '');
		$this->db->insert('orderlines');
	}
	
    function _getitemid($producttype_id, $itemday) 
	{
		$this->db->select('id')
		->from('ff_items')
		->from('ff_pickupdates')
		;
		$where = 'ff_items.division = ff_pickupdates.division and ff_pickupdates.uid = ' . (int)$itemday . ' and ff_items.producttype_id = ' . (int)$producttype_id  ;
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		$row = $query->row();
		return $row->id;
	}


    function _getorderlines($orderno) 
	{
		$this->db->select('ff_orderlines.orderno, ff_orderlines.item, ff_orderlines.quant, ff_orderlines.amount, ff_items.measure, ff_producttypes.explained')
		->from('ff_orderlines')
		->from('ff_producttypes')
		->from('ff_items')
		;
		$where = "ff_orderlines.orderno = $orderno AND ff_orderlines.item = ff_items.id AND ff_items.producttype_id = ff_producttypes.id";
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		return $query->result_array();
	}

    function _getpicuporders($orderno) 
	{
		$this->db->select('ff_orderlines.item, ff_orderlines.quant, ff_orderlines.amount, ff_pickupdates.division, ff_pickupdates.pickupdate, ff_divisions.name, ff_producttypes.explained, ff_items.measure, pickupdate')
		->from('ff_orderlines')
		->from('ff_pickupdates')
		->from('ff_divisions')
		->from('ff_producttypes')
		->from('ff_items')
		;
		$where = "ff_orderlines.orderno = $orderno 
		AND ff_producttypes.id = ff_items.producttype_id
		AND ff_orderlines.iteminfo = ff_pickupdates.uid 
		AND ff_pickupdates.division = ff_divisions.uid 
		AND ff_items.division = ff_pickupdates.division 
		AND ff_items.id = ff_orderlines.item";
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		return $query->result_array();
	}

    function _activate_new_member($orderno, $puid) 
	{
		// set active
		$this->db->set('active', 'yes');  
		$this->db->set('changed', 'now()', FALSE);  
		$this->db->where('uid', $puid);
		$this->db->update('persons');

		$this->db->select('ff_items.division')
		->from('ff_orderlines')
		->from('ff_producttypes')
		->from('ff_items')
		;
		// determine division
		$where = "ff_orderlines.orderno = $orderno 
		AND ff_items.producttype_id = " . FF_MEMBERSHIP . " 
		AND ff_producttypes.id = ff_items.producttype_id
		AND ff_items.id = ff_orderlines.item";
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		$row = $query->row();
		$division = $row->division;
		// attach membership to division
		$this->db->set('division', $division);
		$this->db->set('member', $puid);
		$this->db->set('start', 'curdate()', FALSE);
		$this->db->insert('division_members');
	}

    function _get_division_from_id($id) 
	{
		$this->db->select('division')
		->from('ff_items');
		$where = 'ff_items.id = ' . (int)$id  ;
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		$row = $query->row();
		return $row->id;
	}

    function _get_orderinfo($orderno) 
	{
		$this->db->select('cc_trans_amount')->select('orderkey')->from('ff_orderhead');
		$where = 'ff_orderhead.orderno = ' . (int)$orderno  ;
		$this->db->where($where, NULL, FALSE);
		$query = $this->db->get();
		$row = $query->row();
		return $row;
	}

	function _nocache()
	{
		// Date in the past
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		// always modified
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		// HTTP/1.0
		header("Pragma: no-cache");
	}
	
}


/// NON-CODEIGNITER EXTERNAL FUNCTIONS FOLLOWS

	include("ressources/.mysql_common.php");
	include("ressources/.library.php");
	include("ressources/.kvittering.php");
	include("ressources/.sendmail.php");




?>
