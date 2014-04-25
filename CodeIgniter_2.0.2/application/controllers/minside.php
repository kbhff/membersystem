<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minside extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('Permission');
		$this->load->helper('danish_date');
		$this->load->helper('date');
		$this->load->model('Memberinfo');
    }

    function index() {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();
//		$medlemsnummer = $this->input->get_post('user');
		$medlemsnummer = intval($this->session->userdata('uid'));
//		$this->load->model('Memberinfo');
		$divisioninfo = $this->Memberinfo->division_info($medlemsnummer);
		$adress = $this->Memberinfo->retrieve_by_medlemsnummer($medlemsnummer);
		$firstname = $adress['firstname'];
		$pickups = $this->Memberinfo->pickups_by_division($divisioninfo['division']);
		$permissions = $this->session->userdata('permissions');
		$newsletters = $this->_latest_newsletters($divisioninfo['division']);
		$newsletter = $this->_latest_newsletter($divisioninfo['division']);
		$data = array(
               'title' => 'Din personlige KBHFF-side',
               'heading' => 'Personlig KBHFF-side for ' . $firstname,
               'divisioninfo' => $divisioninfo,
			   'adress' => $adress,
			   'pickups' => $pickups,
			   'permissions' => $permissions,
			   'newsletter' => $newsletter,
			   'newsletters' => $newsletters,
          );

		$this->load->view('v_minside', $data);
    }

    function login() 
	{
		$this->load->model('Memberinfo');
		$user = $this->input->post('user');
		$pw = $this->input->post('pw');
		$phash = $this->input->post('phash');
		$hts = $this->input->post('hts');
		$return_code = $this->Memberinfo->validate_login($this->input->post('user'), $this->input->post('pw'), $this->input->post('hts'));

		if ($return_code === 'OK')
		{
				$this->session->set_userdata('uid', $this->input->post('user'));
				$temp = $this->Memberinfo->get($this->input->post('user'));
				//We cache name and permissions in the session so we don't have to look ip up constantly			
				$this->session->set_userdata(('name'), $temp[0]["firstname"].' '.$temp[0]["middlename"].' '.$temp[0]["lastname"]);
				$temp = $this->Permission->get($this->input->post('user'));
				$this->session->set_userdata(('permissions'), $temp);
		} 
		
		echo $return_code;
    }


    function betingelser() {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();
		$betingelser = $this->load->view('v_betingelser', '', true);
		$data = array(
               'title' => 'KBHFF online handelsbetingelser',
               'heading' => 'KBHFF online handelsbetingelser',
               'content' => $betingelser
          );

		$this->load->view('page', $data);
	
    }

    function kontoinfo($id =0, $sort = 'stigende') {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();
		$this->load->model('Account');
		if ($id == 0)
		{
			$id = $this->session->userdata('uid');
		}
		$balance = $this->Account->get_balance($id);
		setlocale(LC_MONETARY, 'da_DK');
		$balance = number_format($balance,2,',','.');

		$posts = $this->Account->get_transactions($id);
		$post_to_view = Array();
		$sum = 0;
		for ($i = 0; $i < count($posts); $i++)
		{
			$sum += $posts[$i]['amount'];
			$post_to_view[$i]['orderno'] = $posts[$i]['orderno'];
			$post_to_view[$i]['type'] = $posts[$i]['type'];
			$post_to_view[$i]['item_text'] = $posts[$i]['item'];
			$post_to_view[$i]['time'] = danish_date_format($posts[$i]['created']);
			$post_to_view[$i]['weeknumber'] = $posts[$i]['weeknumber'];
			$post_to_view[$i]['external_id'] = $posts[$i]['external_id'];
			$post_to_view[$i]['payment_method'] = $posts[$i]['method'];
			$post_to_view[$i]['credit_comment'] = $posts[$i]['comment'];
			$post_to_view[$i]['amount'] = $posts[$i]['amount'];
			$post_to_view[$i]['authorized_by'] = $posts[$i]['authorized_by'];
			$post_to_view[$i]['sub_sum'] = $sum;
		}
		$viewdata['transactions'] = $post_to_view;
		$viewdata['balance'] = $this->Account->get_balance($id);
		$viewdata['sort_desc'] = TRUE;
		$viewdata['id'] = $id;
		
		if ($sort == 'stigende')
			$viewdata['sort_desc'] = FALSE;
		$viewdata['heading'] = 'Transaktioner';
		$viewdata['title'] = 'Transaktioner';
		$viewdata['admin'] = FALSE;

		$this->load->view('v_transactions', $viewdata);
	
    }

    function mine_ordrer($id =0) {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();
		$this->load->model('Account');
		if ($id == 0)
		{
			$id = $this->session->userdata('uid');
		}
	
		if ($this->uri->segment(3) > 0)
		{
			$permissions = $this->session->userdata('permissions');
			if ( $this->Memberinfo->checkpermission($permissions, 'Administrator', $this->session->userdata('uid')))
			{
				$id = $this->uri->segment(3);
			} else {
				$id = $this->session->userdata('uid');
			}
			if ($this->input->post('orderno'))
			{
				$this->_change_pickupdate($id,$this->input->post('orderno'),$this->input->post('orderlineid'),$this->input->post('item'),$this->input->post('presdate'),$this->input->post('newdate'));
			}
		}
		$posts = $this->Account->get_future_orders($id);
		
		$q2 = $this->db->query('select id, explained from ff_producttypes where bag = "Y" order by sortkey');
		$bagdays = $q2->result_array();
		foreach ($bagdays as $bagday)
		{
			$viewdata['pd' . $bagday['id']] = $this->Account->get_open_pickupdays($id, $item = $bagday['id']);
		}

		$post_to_view = Array();
		for ($i = 0; $i < count($posts); $i++)
		{
			$post_to_view[$i]['orderno'] = $posts[$i]['orderno'];
			$post_to_view[$i]['quant'] = $posts[$i]['quant'];
			$post_to_view[$i]['measure'] = $posts[$i]['measure'];
			$post_to_view[$i]['explained'] = $posts[$i]['explained'];
			$post_to_view[$i]['uid'] = $posts[$i]['uid'];
			$post_to_view[$i]['pickupdate'] = $posts[$i]['pickupdate'];
			$post_to_view[$i]['status1'] = $posts[$i]['status1'];
			$post_to_view[$i]['division'] = $posts[$i]['name'];
			$post_to_view[$i]['cancel'] = $posts[$i]['cancel'];
			$post_to_view[$i]['itemid'] = $posts[$i]['itemid'];
			$post_to_view[$i]['orderlineid'] = $posts[$i]['orderlineid'];
		}
		$viewdata['transactions'] = $post_to_view;
		$viewdata['id'] = $id;
		$viewdata['heading'] = 'Mine bestillinger';
		$viewdata['title'] = 'Mine bestillinger';
		$viewdata['admin'] = FALSE;

		$this->load->view('v_future_orders', $viewdata);
	
    }
	
	function _latest_newsletter($division)
	{
		$this->db->select('date_format(ff_massmail_log.sent,"%e/%c/%Y") as date, massmail_log.subject, massmail_log.content, massmail_log.uid', FALSE);
		$this->db->from('massmail_log');
		$this->db->where('massmail_log.division', (int)$division); 
		$this->db->where('massmail_log.group = "a"'); 
		$this->db->order_by('sent','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return ($query->result_array());
	}

	function _latest_newsletters($division)
	{
		$this->db->select('date_format(ff_massmail_log.sent,"%e/%c/%Y") as date, massmail_log.subject, massmail_log.content, massmail_log.uid', FALSE);
		$this->db->from('massmail_log');
		$this->db->where('massmail_log.division', (int)$division); 
		$this->db->where('massmail_log.group = "a"'); 
		$this->db->order_by('sent','desc');
		$this->db->limit(3);
		$query = $this->db->get();
		return ($query->result_array());
	}

	function nyhedsbrev($uid)
	{
		$this->db->select('date_format(ff_massmail_log.sent,"%e/%c/%Y") as date, massmail_log.subject, massmail_log.content, massmail_log.uid, massmail_log.division', FALSE);
		$this->db->from('massmail_log');
		$this->db->where('massmail_log.uid', (int)$uid); 
		$this->db->where('massmail_log.group = "a"'); 
		$this->db->order_by('sent','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$newsletter = $query->result_array();

		$this->db->select('date_format(ff_massmail_log.sent,"%e/%c/%Y") as date, massmail_log.subject, massmail_log.content, massmail_log.uid', FALSE);
		$this->db->from('massmail_log');
		$this->db->where('massmail_log.division', (int)$newsletter['0']['division']); 
		$this->db->where('massmail_log.group = "a"'); 
		$this->db->order_by('sent','desc');
//		$this->db->limit(1,10);
		$query = $this->db->get();
		$newsletterarchive = $query->result_array();

		$data = array(
               'title' => 'Nyhedsbreve',
               'heading' => 'Nyhedsbreve',
			   'newsletter' => $newsletter,
			   'newsletterarchive' => $newsletterarchive,
          );

		$this->load->view('v_nyhedsbrev', $data);
	}

	private function _change_pickupdate($puid,$orderno,$orderlineid,$item,$presdate,$newdate)
	{
		$sql = 'update ff_orderlines set changed = now(), iteminfo = '.(int)$newdate.' where orderno = '.(int)$orderno.' and uid = ' . (int)$orderlineid. ' and puid = '.(int)$puid.' and iteminfo = "'.$presdate.'"';
		$query = $this->db->query($sql);
	}
} // class minside 

/* End of file minside.php */
/* Location: ./application/controllers/minside.php */
