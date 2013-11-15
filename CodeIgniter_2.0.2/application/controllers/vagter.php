<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vagter extends CI_Controller {

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
/*
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
*/
		$permissions = $this->session->userdata('permissions');

		$createsel = '';
		$cashsel = '';
		$medlemordreliste = '';
		$dagenssalg = '';
		$this->db->protect_identifiers('pickupdates', TRUE);		
		$this->db->protect_identifiers('divisions', TRUE);		
		$this->db->select('divisions.uid as uid,divisions.name, pickupdates.pickupdate, pickupdates.uid as pickupdateuid');
		$this->db->from('divisions');
		$this->db->from('pickupdates');
		$this->db->where('divisions.uid = ff_pickupdates.division'); 
		$this->db->where('pickupdates.pickupdate >= curdate()'); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
			$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $row['uid']);
			$p_infovagt   = $this->Memberinfo->checkpermission($permissions, 'Info + lukkevagt', $row['uid']);

			if (($p_administrator) || ($p_kassemester)|| ($p_infovagt))
			{
				$createsel .= '<option value="' . $row['pickupdateuid'] . '">' . $row['name'] . '-' . $row['pickupdate']. "</option>\n";
			}
		}

		$this->db->protect_identifiers('divisions', TRUE);		
		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		$this->db->order_by('divisions.name'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
			$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $row['uid']);
			if (($p_administrator) || ($p_kassemester))
			{
				$cashsel .= '<a href="/kontantordrer/index/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
				$cashsel .= "<!--\n (($p_administrator) || ($p_kassemester)) \n-->";
			}
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
               'heading' => 'KBHFF Administrationsside: Infovagt',
			   'sel' => $createsel,
			   'orderlist' => $orderlist,
               'cashsel' => $cashsel,
               'dagenssalg' => $dagenssalg,
			   'medlemordreliste' => $medlemordreliste,
          );

		$this->load->view('v_info', $data);
    }

    function definer($division=0) {
/*
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
*/
		$permissions = $this->session->userdata('permissions');

		$createsel = '';
		$cashsel = '';
		$medlemordreliste = '';
		$dagenssalg = '';
		$this->db->select('divisions.uid as uid');
		$this->db->from('divisions');
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
		}
/*
		if (! $p_administrator)
			redirect('/minside');		
*/
		$this->db->select('id');
		$this->db->distinct();
		$this->db->from('teams');
		$this->db->order_by('id'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$counter = 1;
			$this->db->select('name');
			$this->db->from('teams');
			$this->db->where('id',$row['id']); 
			$this->db->order_by('name'); 
			$query2 = $this->db->get();
			foreach ($query2->result_array() as $row2)
			{
				$team[$row['id']][] = $row2['name'];
				$counter++;
			}
		}
		
		$counter = 1;
		$this->db->select('name');
		$this->db->select('auth');
		$this->db->select('uid');
		$this->db->from('chore_types');
		$this->db->order_by('name'); 
		$query = $this->db->get();
		$chores = $query->result_array();

		$divisionname = $this->_divisionname($division);
		$data = array(
               'title' => 'KBHFF Administrationsside',
               'heading' => 'KBHFF Administrationsside: Definer vagter for ' .$divisionname,
			   'division' => $divisionname,
			   'team' => $team,
			   'chores' => $chores,
          );

		$this->load->view('v_vagt_definition', $data);
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

	
	
	
} // class Vagter 

/* End of file vagter.php */
/* Location: ./application/controllers/infovagt.php */