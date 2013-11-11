<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)
class Logud extends CI_Controller {


    function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('./');		
	}
	
	function index()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		$this->load->helper('url');
		redirect('./login');		
	}
}
/* End of file logud.php */
/* Location: ./controllers/logud.php */