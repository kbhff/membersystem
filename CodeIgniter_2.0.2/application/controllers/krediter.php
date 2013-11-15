<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)

class Krediter extends CI_Controller {

    function __construct()
    {

		parent::Controller();	
		$this->load->helper('menu');
		$this->load->model('member');
		$this->load->model('account');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('danish_date');
		$this->load->helper('date');
		
		if (! intval($this->session->userdata('id')) > 0)
			redirect(base_url().'index.php/logind');

		$permissions = $this->session->userdata('permissions');

		if (!isset($permissions['credit']) || $permissions['credit'] < 2)
			redirect(base_url().'index.php/logud');
	}
	
	function index()
	{
		$viewdata = Array();
		//It's a repost - please ignore...
		if ($this->session->userdata('timestamp') > 0 && $this->session->userdata('timestamp') == $this->input->post('timestamp'))
		{
			redirect(base_url().'index.php/krediter');	
			exit();
		}

		$viewdata['name'] = $this->input->post('name');
		$credits = 0;
		
		foreach($_POST as $key => $val) 
		{
			$matches = array();
			if(preg_match('/id-([0-9]+)/', $key, $matches)) 
			{
				if (intval($this->input->post($key)) > 0)
				{
					$this->account->add_credit($matches[1], intval($this->input->post($key)), $this->input->post('explanation-'.$matches[1]), $this->session->userdata('name'));
					$credits += 1;
					$viewdata['message'] = $credits. ' kreditering(er) er registreret';
					$this->session->set_userdata('timestamp', $this->input->post('timestamp'));
				}
			}
		}
	
		if ($this->input->post('name') != '' && $credits == 0)
		{
			$viewdata['posts'] = $this->member->search_member($this->input->post('name'));
		}
		$this->load->view('v_credit', $viewdata);
	}
}
/* End of file posteringer.php */
/* Location: ./controllers/krediter.php */