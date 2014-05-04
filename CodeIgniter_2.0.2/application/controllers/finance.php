<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
    }

    function index($divisionday = 0) {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');

		if (! $this->Memberinfo->checkgrouppermission($permissions, utf8_encode('Fælles økonomigruppe')))
		redirect('/minside');		

		$dagenssalg = '';
		$finansrapport = '';
		$this->db->select('divisions.uid as uid,divisions.name, pickupdates.pickupdate, pickupdates.uid as pickupdateuid');
		$this->db->from('divisions');
		$this->db->from('pickupdates');
		$this->db->where('divisions.uid = ff_pickupdates.division'); 
		$this->db->where('pickupdates.pickupdate >= curdate()'); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();


		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		$this->db->order_by('divisions.name'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$dagenssalg .= '<a href="/admin/dagens_salg/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
			$finansrapport .= '<a href="/rapportering/kassemester/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
		}
		
		$divisionday = $this->input->post('divisionday');

		if ($divisionday > 0)
		{

		$query = $this->db->query('SELECT 
		ff_orderlines.item as article, ff_pickupdates.pickupdate as pickupdate, ff_divisions.name as name, ff_items.units, ff_items.measure, ff_producttypes.explained as txt, ff_orderlines.quant,
		ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_persons.uid
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
		ORDER BY ff_pickupdates.pickupdate, ff_producttypes.explained
		');
		$orderlist = $query->result_array();

		} else {
			$orderlist = '';
		}


		$data = array(
               'title' => 'KBHFF Administrationsside',
               'heading' => 'KBHFF Administrationsside: &Oslash;konomigruppen',
               'dagenssalg' => $dagenssalg,
			   'finansrapport' => $finansrapport,
          );

		$this->load->view('v_finance', $data);
    }

	
	function ordreliste($divisionday = 0) {
// to be refactored, same as in finance
	}

	function medlemordreliste($division = 0) {
// to be refactored, same as in finance
	}

	private function _get_member_dateorder($member, $date)
	{
		$query = $this->db->query('SELECT ff_orderlines.quant 
		FROM ff_orderlines, ff_orderhead, ff_items
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
		AND ff_items.producttype_id = ' . FF_GROCERYBAG . '
		AND ff_orderlines.iteminfo = ' . (int)$date . '
		AND ff_orderlines.puid = ' . $member . '');
		$num = 0;
		foreach ($query->result() as $row)
		{
			$num += $row->quant;
		}
		return $num;
	}

	private function _divisionname($division)
	{
			$this->db->select('name');
			$this->db->from('divisions');
			$this->db->where('uid', (int)$division); 
			$query = $this->db->get();
			$row = $query->row();
			return $row->name;
	}
	
	private function _division($divisionday)
	{
			$this->db->select('division');
			$this->db->from('pickupdates');
			$this->db->where('uid', (int)$divisionday); 
			$query = $this->db->get();
			$row = $query->row();
			return $row->division;
	}
	
	 private function _numtochars($num,$start=65,$end=90)
	 {
	     $sig = ($num < 0);
	     $num = abs($num);
	     $str = "";
	     $cache = ($end-$start);
	     while($num != 0)
	     {
	         $str = chr(($num%$cache)+$start-1).$str;
	         $num = ($num-($num%$cache))/$cache;
	     }
	     if($sig)
	     {
	         $str = "-".$str;
	     }
	     return $str;
	 }

} // class finance 

/* End of file finance.php */
/* Location: ./application/controllers/finance.php */