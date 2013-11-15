<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)

class medlemmer extends CI_Controller {

	protected $viewdata;

    function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('menu');
		$this->load->model('account');
		$this->load->library('session');
		$this->load->model('permission');
		$this->load->helper('danish_date');
		$this->load->helper('date');
        $this->load->library('javascript');
		$this->load->model('Memberinfo');
		if (! intval($this->session->userdata('uid')) > 0)
			redirect(base_url().'index.php/');
	}
	
	function index($division = 0)
	{
		//We detect a repost - discard
		if ($this->session->userdata('timestamp') > 0 && $this->session->userdata('timestamp') == $this->input->post('timestamp'))
		{
			redirect(base_url().'index.php/medlemmer');	
			exit();
		}
		
		$permissions = $this->session->userdata('permissions');
		$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $division);
		$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $division);


		if (! (($p_administrator) || ($p_kassemester)))
			redirect(base_url().'index.php/logud');

		if ($this->input->post('credit'))
		{
			if (($p_administrator) || ($p_kassemester))
				$this->_credit_to_members();
			else
				redirect(base_url().'index.php/logud');
		}

		if ($this->input->post('debit'))
		{
			if (($p_administrator) || ($p_kassemester))
				$this->_debit_to_members($division);
			else
				redirect(base_url().'index.php/logud');
		}
$this->viewdata['division'] = $division;
$this->viewdata['title'] = 'Medlemsh&aring;ndtering';
$this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
$js =$this->jquery->corner('#tt');
$this->javascript->output($js);
$this->javascript->compile();

		$this->viewdata['name'] = $this->input->post('name');
		
		if ($this->input->post('name') != '')
		{
			$this->viewdata['members'] = $this->Memberinfo->search_member($this->input->post('name'));
		}	
		else
		{
			$this->viewdata['members'] = $this->Memberinfo->get_members($division);
		}
		$this->load->view('v_memberlist', $this->viewdata);

	}
	
	function skiftafdeling($newdivision = 0,$puid = 0)
	{
		if ($this->uri->segment(4) > 0)
		{
			$division = $this->uri->segment(3);
			$puid = $this->uri->segment(4);
			$medlem = $this->Memberinfo->retrieve_by_medlemsnummer($puid);
	
			$data = array(
	               'title' => 'KBHFF Administrationsside',
	               'heading' => 'Flyt medlem til anden afdeling',
	               'content' => '',
				   'message' => '',
				   'debug' => $this->db->last_query(),
				   'medlem' => $medlem['firstname'] .' ' . $medlem['middlename'] .' ' . $medlem['lastname'],
				   'divisionselectall' =>	$this->_divisionselect(0,1),
	          );
			$this->load->view('v_movemember', $data);
		} 
		if ($this->uri->segment(3) > 0)
		{
			$division = $this->uri->segment(3);
			$newdivision = $this->input->post('newdivision');
			$puid = $this->input->post('puid');
			$medlem = $this->Memberinfo->retrieve_by_medlemsnummer($puid);
			$medlemnavn = $medlem['firstname'] .' ' . $medlem['middlename'] .' ' . $medlem['lastname'];
			if ($this->_movemember($puid, $division,$newdivision))
			{
				      $message = $medlemnavn . ' er flyttet fra ' . $this->_divisionname($division) . ' til ' . $this->_divisionname($newdivision) . '.'; 
			} else {
				      $message = 'FEJL: Medlem IKKE flyttet';				
			}
			$data = array(
	               'title' => 'KBHFF Administrationsside',
	               'heading' => 'Medlem flyttet til anden afdeling',
	               'content' => '',
				   'message' => $message,
				   'divisionselectall' =>	$this->_divisionselect(0,1),
	          );
			$this->load->view('v_movemember', $data);
		} else {
			// search
			$divisionexists = (int)$this->input->post('divisionexists');
			$divisionexistsname = $this->_divisionname($divisionexists);

			$data = array(
	               'title' => 'KBHFF Administrationsside',
	               'heading' => 'Flyt medlem til anden afdeling',
	               'content' => '',
				   'divisionname' => $divisionexists,
				   'divisionexists' => $divisionexists,
				   'divisionselectall' =>	$this->_divisionselect(0,1),
				   'divisionname' => $divisionexistsname, 
	          );

			if ($this->input->post('srch') > '')
			{
				$data['members'] = $this->Memberinfo->search_member($this->input->post('srch'),$divisionexists);
				$data['message'] = "S&oslash;gt p&aring; '" . $this->input->post('srch') . "' i " . $divisionexistsname;
				
			} else {
				$data['message'] = '';
			}

			$this->load->view('v_movemember', $data);
		}
		
	}
	
	function export()
	{
		/*
		// Nice little snippet, if ever wanting to export a search
		$members = array();
		foreach($_POST as $key => $val) 
		{
			$matches = array();
			if(preg_match('/id-([0-9]+)/', $key, $matches)) 
			{
				if (intval(substr($key, 3)) > 0)
				{
					array_push($members, intval(substr($key, 3)));
				}
			}
		}
		$comma_separated = implode(",", $members);
		*/
		$members = $this->Memberinfo->get_members_receiving_newsletters();

		header('Content-type: application/octet-stream');
		header('Content-Disposition: attachment; filename="kbhff_eksport_'.date('dmy_His').'.csv');
		$data='Navn;E-mail-adresse'."\n";
		foreach($members as $member)
		{
			$data .= utf8_decode($member['name']).';';		
			$data .= utf8_decode($member['email'])."\n";		
		}
		echo $data; 
	}
	
	private function _movemember($puid, $division,$newdivision)
	{
		// update division
		$this->db->set('division', $newdivision);  
		$this->db->where('member', $puid);
		$this->db->where('division', $division);
		$this->db->update('division_members');
		if ($this->db->affected_rows()==0)
		{
			return false;
		} else {
			// move groups etc for old division
			$this->db->set('department', $newdivision);  
			$this->db->set('note', 'mov');  
			$this->db->where('puid', $puid);
			$this->db->where('department', $division);
			$this->db->update('groupmembers');

			$this->db->set('department', $newdivision);  
			$this->db->where('puid', $puid);
			$this->db->where('department', $division);
			$this->db->update('roles');
		
			// log
			// creator member type text created 
			$this->db->set('creator', $this->session->userdata('uid'));
			$this->db->set('member', $puid);
			$this->db->set('type', 'adminlog');
			$this->db->set('text', 'flyttet fra '.$this->_divisionname($division).' til '.$this->_divisionname($newdivision));
			$this->db->set('created', 'now()', FALSE);
			$this->db->insert('log');
			return true;
		}		
	}
	
	private function _divisionname($division)
	{
			$this->db->select('name');
			$this->db->from('divisions');
			$this->db->where('uid', (int)$division); 
			$query = $this->db->get();
			$row = $query->row();
			if ($query->num_rows() > 0)
			{
				return $row->name;
			} else {
				return 'Mangler';
			}
	}

	function _divisionselect($default=0, $showall = 1)
	{

		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		if ($showall == 0)
		{
			$this->db->where('divisions.type', 'aktiv'); 
			$this->db->where('divisions.webmembers', 'Y'); 
		}
		if ($showall == 2)
		{
			$this->db->where('divisions.type', 'aktiv'); 
		}
		$this->db->order_by('divisions.name'); 
		$query = $this->db->get();
		$return = '<option value=""></option>' ."\n";
		$row = $query->row();
		foreach ($query->result() as $row)
		{
			if ($row->uid == $default)
			{
					$return .= '<option value="' . $row->uid . '" selected>'. $row->name . "</option>\n";
			} else {
					$return .= '<option value="' . $row->uid . '">'. $row->name . "</option>\n";
			}
		}
		return $return;
	}

	function _debit_to_members($division)
	{
		$permissions = $this->session->userdata('permissions');
		$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $division);
		$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $division);
		
		if (! (($p_administrator) || ($p_kassemester)))
			redirect(base_url().'index.php/logud');
/*
		echo 'debit';
		print_r ($_POST);
		echo $this->input->post('debit-amount'); 
		echo $this->input->post('debit-explanation'); 
*/		
		$debits = 0;
		$method = 'admin';
		foreach($_POST as $key => $val) 
		{
			$matches = array();
			if(preg_match('/uid-([0-9]+)/', $key, $matches)) 
			{
				if (intval(substr($key, 4)) > 0)
				{
					if (intval($this->input->post('debit-amount') > 0))
					{
						$this->account->add_debit($matches[1], intval($this->input->post('debit-amount')), $this->input->post('debit-explanation'), $this->session->userdata('uid'), $method);
						$debits += 1;
						$this->viewdata['message'] = $debits. ' debitering(er) er registreret';
						$this->session->set_userdata('timestamp', $this->input->post('timestamp'));
					}
					else
					{
						$this->viewdata['errors'] = 'Debit-beløbet er negativt. Det skal være positivt. Intet foretaget.';
						return;
					}
				}
			}
		}
		if ($debits == 0)
			$this->viewdata['errors'] = 'Du skal vælge mindst ét medlem fra listen. Intet foretaget.';
			
		return;		
	}
	
	function _credit_to_members()
	{
		$credits = 0;
		foreach($_POST as $key => $val) 
		{
			$matches = array();
			if(preg_match('/uid-([0-9]+)/', $key, $matches)) 
			{
				if (intval(substr($key, 4)) > 0)
				{
					if (intval($this->input->post('credit-amount') > 0))
					{
						$this->account->add_credit($matches[1], intval($this->input->post('credit-amount')), $this->input->post('credit-explanation'), $this->session->userdata('uid'));
						$credits += 1;
						$this->viewdata['message'] = $credits. ' kreditering(er) er registreret';
						$this->session->set_userdata('timestamp', $this->input->post('timestamp'));
					}
					else
					{
						$this->viewdata['errors'] = 'Kredit-beløbet er negativt. Det skal være positivt. Intet foretaget.';
						return;
					}
				}
			}
		}
		if ($credits == 0)
			$this->viewdata['errors'] = 'Du skal vælge mindst ét medlem fra listen. Intet foretaget.';
			
		return;		
	}
}
?>