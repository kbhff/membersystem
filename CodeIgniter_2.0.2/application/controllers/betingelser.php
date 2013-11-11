<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Betingelser extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
        $this->load->library('javascript');
    }

    function index() {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();
$medlemsnummer = 1;
		$this->load->model('Memberinfo');
		$divisioninfo = $this->Memberinfo->division_info($medlemsnummer);
		$adress = $this->Memberinfo->retrieve_by_medlemsnummer($medlemsnummer);
		$firstname = $adress['firstname'];
		$data = array(
               'title' => 'Din personlige KBHFF-side',
               'heading' => 'Betingelser',
               'divisioninfo' => $divisioninfo,
			   'adress' => $adress,
			   'pickups' => $pickups,
          );

		$this->load->view('page', $data);
	
    }

	
} // class minside 

/* End of file minside.php */
/* Location: ./application/controllers/minside.php */