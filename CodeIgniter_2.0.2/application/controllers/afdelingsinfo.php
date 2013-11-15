<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Afdelingsinfo extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->helper('danish_date');
		$this->load->helper('date');
		$this->load->library('session');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
		$this->load->model('Personsmodel');
    }

    function index() {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');

		$content = '';
		
		$data = array(
               'title' => 'KBHFF Afdelingsside',
               'heading' => 'KBHFF Afdelingsside',
               'content' => $content,
               'excelsel' => $excelsel,
               'createsel' => $createsel,
               'adminsel' => $adminsel,
               'cashsel' => $cashsel,
               'dagenssalg' => $dagenssalg,
          );

		$this->load->view('v_afdeling', $data);
    }


	function grupper($division)
	{
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();
		$arbejdsgruppe = '';
		$ressourcegruppe = '';
		$afdelingsgruppe = '';
		$roles = '';

		if ($this->uri->segment(3) > 0)
		{
			$division = (int)$this->uri->segment(3);
			$divisionname = $this->_divisionname($division);
			$this->db->select('groups.name');
			$this->db->select('groups.type');
			$this->db->select('persons.firstname');
			$this->db->select('persons.middlename');
			$this->db->select('persons.lastname');
			$this->db->select('groupmembers.puid as member');
			$this->db->from('groups');
			$this->db->join('(ff_groupmembers,ff_persons)', 'ff_groupmembers.group = ff_groups.uid and ff_groupmembers.status = "aktiv" and ff_persons.uid = ff_groupmembers.puid and ff_groupmembers.department = ' . $division .' ', 'left');  
			$this->db->order_by('type'); 
			$this->db->order_by('name'); 
			$query = $this->db->get();
			$debug1 = $this->db->last_query();
			$arbejdsgruppe = $query->result_array();


			$this->db->select('chore_types.name as name');
			$this->db->select('chore_types.uid');
			$this->db->select('divisions.name as divisionname');
			$this->db->select('divisions.uid as division');
			$this->db->select('persons.firstname');
			$this->db->select('persons.middlename');
			$this->db->select('persons.lastname');
			$this->db->select('roles.puid as member');
			$this->db->from('chore_types');
			$this->db->join('(ff_roles, ff_persons, ff_division_members, ff_divisions)', 'roles.role = chore_types.uid and ff_roles.status = "aktiv" and ff_divisions.uid = ff_roles.department and ff_roles.department = ff_division_members.division and ff_roles.puid = ff_persons.uid and ff_persons.uid = ff_division_members.member and ff_division_members.division =' . $division, 'left');
			$this->db->where('chore_types.auth >', 0); 
			$this->db->order_by('divisions.name'); 
			$this->db->order_by('chore_types.name'); 
			$query = $this->db->get();
			$roles = $query->result_array();
			$debug1 = $this->db->last_query();

			$posts = array();
			$data = array(
	               'title' => 'KBHFF Afdelingsside',
	               'heading' => 'N&oslash;glepersoner i ' . $divisionname,
	               'content' => 'S&aring; ved du hvem du skal kontakte...<br>',
				   'divisionname' => $divisionname,
				   'debug1' => $debug1,
				   'arbejdsgruppe' => $arbejdsgruppe,
				   'ressourcegruppe' => $ressourcegruppe,
				   'afdelingsgruppe' => $afdelingsgruppe,
				   'roles' => $roles,
				   'posts' => $posts,
	          );
	
			$this->load->view('v_afdeling', $data);
		} else {
			$data = array(
	               'title' => 'KBHFF Afdelingsside',
	               'heading' => 'N&oslash;glepersoner i afdelingen',
	               'content' => 'S&aring; ved du hvem du skal kontakte...<br>Afdeling ikke valgt<br>',
	          );
			$this->load->view('v_afdeling', $data);
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


	
} // class Afdelingsinfo 

	include("ressources/.sendmail.php");

/* End of file afdelingsinfo.php */
/* Location: ./application/controllers/afdelingsinfo.php */
