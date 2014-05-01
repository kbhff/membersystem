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


	function grupper($division = 0)
	{
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$this->db->select('groups.name');
		$this->db->select('groups.type as type');
		$this->db->select('groups.contactmail');
		$this->db->select('groups.maillist');
		$this->db->select('groups.wiki');
		$this->db->select('groups.samba');
		$this->db->select('persons.firstname');
		$this->db->select('persons.middlename');
		$this->db->select('persons.lastname');
		$this->db->select('persons.email');
		$this->db->select('persons.tel');
		$this->db->select('groupmembers.puid as member');
		$this->db->select('groupmembers.department as division');
		$this->db->select('divisions.name as divisionname');
		$this->db->from('groups');
		$this->db->from('divisions');
		$this->db->join('(ff_groupmembers,ff_persons)', 'ff_groupmembers.group = ff_groups.uid and ff_groupmembers.status = "aktiv" and ff_persons.uid = ff_groupmembers.puid', 'left');  
		$this->db->where('common', 'Y'); 
		$this->db->where('groups.active', 'Y'); 
		$this->db->where('groupmembers.department = ff_divisions.uid'); 
		$this->db->order_by('type'); 
		$this->db->order_by('name'); 
		$this->db->order_by('firstname'); 
		$query = $this->db->get();
		$debug1 = $this->db->last_query();
		$commongruppe = $query->result_array();

		if ($this->uri->segment(3) > 0)
		{

			$division = (int)$this->uri->segment(3);
			$divisionname = $this->_divisionname($division);
			$this->db->select('groups.name');
			$this->db->select('groups.type as type');
			$this->db->select('groups.contactmail');
			$this->db->select('groups.maillist');
			$this->db->select('groups.wiki');
			$this->db->select('groups.samba');
			$this->db->select('persons.firstname');
			$this->db->select('persons.middlename');
			$this->db->select('persons.lastname');
			$this->db->select('persons.email');
			$this->db->select('persons.tel');
			$this->db->select('groupmembers.puid as member');
			$this->db->select('groupmembers.department as division');
			$this->db->from('groups');
			$this->db->join('(ff_groupmembers,ff_persons)', 'ff_groupmembers.group = ff_groups.uid and ff_groupmembers.status = "aktiv" and ff_persons.uid = ff_groupmembers.puid and ff_groupmembers.department = ' . $division .' ', 'left');  
			$this->db->where('common', 'N'); 
			$this->db->where('groups.active', 'Y'); 
			$this->db->order_by('type'); 
			$this->db->order_by('name'); 
			$this->db->order_by('firstname'); 
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
			$this->db->select('persons.email');
			$this->db->select('persons.tel');
			$this->db->select('roles.puid as member');
			$this->db->from('chore_types');
			$this->db->join('(ff_roles, ff_persons, ff_division_members, ff_divisions)', 'roles.role = chore_types.uid and ff_roles.status = "aktiv" and ff_divisions.uid = ff_roles.department and ff_roles.department = ff_division_members.division and ff_roles.puid = ff_persons.uid and ff_persons.uid = ff_division_members.member and ff_division_members.division =' . $division, 'left');
			$this->db->where('chore_types.auth >', 0); 
			$this->db->order_by('divisions.name'); 
			$this->db->order_by('chore_types.name'); 
			$this->db->order_by('firstname'); 
			$query = $this->db->get();
			$roles = $query->result_array();
			$debug2 = $this->db->last_query();
				


			$data = array(
	               'title' => 'KBHFF Afdelingsside',
	               'heading' => 'N&oslash;glepersoner i ' . $divisionname,
	               'content' => '',
				   'divisionname' => $divisionname,
				   'division' => $division,
				   'debug1' => $debug1,
				   'arbejdsgruppe' => $arbejdsgruppe,
				   'commongruppe' => $commongruppe,
				   'roles' => $roles,
	          );
	
			$this->load->view('v_afdeling', $data);
		} else {
			$data = array(
	               'title' => 'KBHFF Afdelingsside',
	               'heading' => 'N&oslash;glepersoner i f&aelig;lles arbejdsgrupper',
	               'content' => '',
				   'divisionname' => '',
				   'division' => 0,
				   'debug1' => $debug1,
				   'arbejdsgruppe' => '',
				   'commongruppe' => $commongruppe,
				   'roles' => '',
	          );
			$this->load->view('v_afdeling', $data);
		}
		
	}

	function groupmembers()
	{
		$key = $this->uri->segment(3);
		$this->db->select('groups.name');
		$this->db->select('groups.type as type');
		$this->db->select('persons.firstname');
		$this->db->select('persons.middlename');
		$this->db->select('persons.lastname');
		$this->db->select('persons.email');
		$this->db->select('persons.tel');
		$this->db->select('groupmembers.puid as member');
		$this->db->select('groupmembers.department as division');
		$this->db->select('divisions.name as divisionname');
		$this->db->from('groups');
		$this->db->from('divisions');
		$this->db->join('(ff_groupmembers,ff_persons)', 'ff_groupmembers.group = ff_groups.uid and ff_groupmembers.status = "aktiv" and ff_persons.uid = ff_groupmembers.puid', 'left');  
		$this->db->where('common', 'Y'); 
		$this->db->where('key', $key); 
		$this->db->where('groupmembers.department = ff_divisions.uid'); 
		$this->db->order_by('type'); 
		$this->db->order_by('name'); 
		$this->db->order_by('firstname'); 
		$query = $this->db->get();
		$debug2 = $this->db->last_query();

		echo ('<script language="JavaScript" type="text/javascript">' ."\n");
		echo ('document.write("<table>");' ."\n");
		foreach ($query->result_array() as $row)
		{
			echo('document.write("<tr><td>' . $row['firstname'] . ' ' .$row['middlename'] . ' ' . $row['lastname']. ' (' . $row['divisionname'] .')</td></tr>");' ."\n");
		}
		echo ('document.write("</table>");' ."\n");
		echo  "\n</script>";
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
