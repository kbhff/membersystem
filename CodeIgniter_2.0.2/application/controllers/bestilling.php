<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bestilling extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->helper('select_quantity');
        $this->load->library('javascript');
    }

    function index() {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();
		$this->load->model('Memberinfo');
		$divisioninfo = $this->Memberinfo->division_info($this->session->userdata('uid'));
		$adress = $this->Memberinfo->retrieve_by_medlemsnummer($this->session->userdata('uid'));
		$mypickups = $this->Memberinfo->pickups_by_member($this->session->userdata('uid'));
		$accountstatus = 0;
		$data = array(
               'title' => 'KBHFF bestilling',
               'heading' => $this->session->userdata('name'),
               'content' => $this->session->userdata('uid'),
			   'accountstatus' => $accountstatus,
			   'mypickups' => $mypickups,
			   'divisioninfo' => $divisioninfo,
			   'bag_quantity' => select_quantity(10),
          );

		$this->load->view('v_bestilling', $data);
    }
	
} // class bestilling

/* End of file bestilling.php */
/* Location: ./application/controllers/bestilling.php */