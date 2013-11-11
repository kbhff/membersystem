<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leverandor extends CI_Controller {

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
//		if (! intval($this->session->userdata('uid')) > 0)
//			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();


		$content = '';
		
		$data = array(
               'title' => 'KBHFF Leverand&oslash;rside',
               'heading' => 'KBHFF Leverand&oslash;rside',
               'content' => $content,
          );

		$this->load->view('v_leverandor', $data);
    }

	
} // class Leverandor 



/* End of file leverandor.php */
/* Location: ./application/controllers/leverandor.php */
