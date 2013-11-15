<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)

class Kontaktinfo extends CI_Controller {

	function __construct()
	{
        parent::__construct();
		$this->load->helper('menu');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Memberinfo');
		if (!intval($this->session->userdata('uid')) > 0)
		{
			redirect('./');	
		}
		$viewdata = Array();
	}

	function uid($uid)
	{
		$pw_edit = TRUE;
		$admin = FALSE;
		//If the memberid does not belong to the logged in member, the member must have at least writing permission
		if ($this->session->userdata('uid') != intval($uid))
		{
			$permissions = $this->session->userdata('permissions');
			$divisions = $this->Memberinfo->get_user_division($uid);
			$admin = FALSE;
			if (is_array($divisions))
			{
				foreach ($divisions as $row)
				{
					if ($this->Memberinfo->checkpermission($permissions, 'Administrator', $row['division']))
					{
						$admin = TRUE;
					}
				}
			}
			if (!$admin)
			{
				redirect(base_url().'index.php/logud');
				exit();
			}
			$pw_edit = FALSE;
		}
		$temp = $this->Memberinfo->get($uid);
		$viewdata = $temp[0];
		$viewdata['mail_aliases'] = $this->_getaliases($uid);
		$viewdata['pw_edit'] = $pw_edit;
		$viewdata['admin'] = $admin;
		$this->load->view('v_kontaktinfoform', $viewdata);
	}

	private function _getaliases($uid)
	{
			$this->db->select('alias');
			$this->db->from('persons');
			$this->db->from('mail_aliases');
			$this->db->where('uid', $uid); 
			$this->db->where('uid = puid'); 
			$query = $this->db->get();
			$row = $query->result_array();
			return $row;
	}
	

}
/* End of file kontaktinfo.php */
/* Location: ./controllers/kontaktinfo.php */