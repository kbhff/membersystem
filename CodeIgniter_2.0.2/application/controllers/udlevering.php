<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Udlevering extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
    }

    function dag() {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');

		
		$createsel = '';
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
		if ($this->uri->segment(3) > 0)
		{
			$divisionday = $this->uri->segment(3);
		} else {
			$divisionday = $this->input->post('divisionday');
		}


		if ($divisionday > 0)
		{

			$pickupdate = $this->_pickupday($divisionday);
			$division = $this->_division($divisionday);
			$divisionname = $this->_divisionname($division);

			$bagdays = '';
			$q2 = $this->db->query('select id, explained from ff_producttypes where bag = "Y" order by sortkey');
			$bagdays = $q2->result_array();
	
			$query = $this->db->query('SELECT 
			ff_orderlines.item as article, ff_pickupdates.pickupdate as pickupdate, ff_divisions.name as name, ff_items.units, ff_items.measure, ff_producttypes.explained as txt, ff_orderlines.quant,
			ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_persons.uid as medlem, ff_orderhead.status1, ff_orderhead.orderno, ff_orderlines.uid,ff_membernote.note
			FROM (ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons)
			LEFT JOIN ff_membernote on (ff_membernote.puid = ff_persons.uid)
			WHERE ff_orderlines.orderno = ff_orderhead.orderno 
			AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
			AND (ff_orderlines.status2 <> "udleveret")
			AND ff_orderlines.item = ff_items.id
			AND ff_items.producttype_id = ff_producttypes.id 	
			AND ff_orderlines.iteminfo = ff_pickupdates.uid
			AND ff_divisions.uid = ff_pickupdates.division
			AND ff_pickupdates.division = ff_items.division
			AND ff_orderlines.puid = ff_persons.uid
			AND ff_pickupdates.uid = ' . (int)$divisionday . '
			ORDER BY ff_persons.firstname
			');
			$orderlist = $query->result_array();
			$query = $this->db->query('SELECT 
			ff_orderlines.item as article, ff_pickupdates.pickupdate as pickupdate, ff_divisions.name as name, ff_items.units, ff_items.measure, ff_producttypes.explained as txt, ff_orderlines.quant,
			ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_persons.uid as medlem, ff_orderhead.status1, ff_orderhead.orderno, ff_orderlines.uid,ff_membernote.note
			FROM (ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons)
			LEFT JOIN ff_membernote on (ff_membernote.puid = ff_persons.uid)
			WHERE ff_orderlines.orderno = ff_orderhead.orderno 
			AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
			AND (ff_orderlines.status2 = "udleveret")
			AND ff_orderlines.item = ff_items.id
			AND ff_items.producttype_id = ff_producttypes.id 	
			AND ff_orderlines.iteminfo = ff_pickupdates.uid
			AND ff_divisions.uid = ff_pickupdates.division
			AND ff_pickupdates.division = ff_items.division
			AND ff_orderlines.puid = ff_persons.uid
			AND ff_pickupdates.uid = ' . (int)$divisionday . '
			ORDER BY ff_persons.firstname
			');
			$orderlistcollected = $query->result_array();

		} else {
			$orderlist = '';
		}

		$viewdata = array(
               'title' => 'Udlevering af poser',
               'heading' => 'Udlevering af poser',
               'divisionday' => (int)$divisionday,
               'divisionname' => $divisionname,
               'pickupdate' => $pickupdate,
			   'sel' => $createsel,
			   'orderlist' => $orderlist,
			   'orderlistcollected' => $orderlistcollected,
			   'bagdays' => $bagdays,
          );

		foreach ($bagdays as $bagday)
		{
			$viewdata['count' . $bagday['id']] = $this->_getcount($divisionday, 'ikkeudleveret', $bagday['id']);
			$viewdata['udlev' . $bagday['id']] = $this->_getcount($divisionday, 'udleveret', $bagday['id']);
			$viewdata['total' . $bagday['id']] = $this->_getcount($divisionday, 'total', $bagday['id']);
		}

		$this->load->view('v_udlevering', $viewdata);
    }

    function annuller() {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');

		if ($this->uri->segment(3) > 0)
		{
			$divisionday = $this->uri->segment(3);
		} 

		if ($this->uri->segment(4) > 0)
		{
			$orderlineuid = (int)$this->uri->segment(4);
		} 

		if (($divisionday)&&($orderlineuid))
		{
			$data = array(
			               'status2' => ''
			            );
			
			$this->db->where('uid', $orderlineuid);
			$this->db->where('iteminfo', $divisionday);
			$this->db->update('orderlines', $data);
 			redirect('/udlevering/dag/' . $divisionday);
		}
		redirect('/kassemester/');
	}	

	function _getcount($divisionday, $status, $item)	
	{
		switch ($status) {
			case 'udleveret' :
				$limit = 'AND ff_orderlines.status2 = "udleveret"';
				break;
			case 'ikkeudleveret' :
				$limit = 'AND ff_orderlines.status2 <> "udleveret"';
				break;
			case 'total' :
				$limit = '';
				break;
		}

		$query = $this->db->query('SELECT sum( ff_orderlines.quant ) as sum, ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained
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
		AND ff_items.producttype_id = ' . (int)$item . '
		AND ff_items.producttype_id = ff_producttypes.id
		AND ff_orderlines.iteminfo = ff_pickupdates.uid
		AND ff_divisions.uid = ff_pickupdates.division
		AND ff_pickupdates.division = ff_items.division
		' . $limit . '
		AND ff_pickupdates.uid = ' . (int)$divisionday . ' 
		GROUP BY ff_orderlines.item
		ORDER BY ff_orderlines.item');
		
		$row = $query->row();
		if ($query->num_rows() > 0)
		{
		return ($row->sum);
		} else {
			return 0;
		}
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

	private function _pickupday($divisionday)
	{
			$this->db->select('pickupdate');
			$this->db->from('pickupdates');
			$this->db->where('uid', (int)$divisionday); 
			$query = $this->db->get();
			$row = $query->row();
			return $row->pickupdate;
	}
	
	
	
} // class Udlevering 

/* End of file udlevering.php */
/* Location: ./application/controllers/udlevering.php */