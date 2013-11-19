<?php

class Blivmedlem extends CI_Controller {

	function __construct()
	{
        parent::__construct();
		$this->load->model('Memberinfo');
		$this->load->model('Personsmodel');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('menu');
        $this->load->library('javascript');
		$viewdata = Array();
	}
	
	function index()
	{
		$this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
		$this->javascript->output($js);
		$this->javascript->compile();

		$viewdata['admin'] = $this->uri->segment(3);

		$this->form_validation->set_rules('division', 'Afdeling', 'required|min_length[1]');
		$this->form_validation->set_rules('firstname', 'Fornavn', 'required|min_length[2]');
		$this->form_validation->set_rules('lastname', 'Efternavn', 'required|min_length[2]');
	    $this->form_validation->set_rules('adr2', 'Vejnavn', 'callback_street_name_check');
	    $this->form_validation->set_rules('zip', 'Postnummer', 'numeric|exact_length[4]');
	    $this->form_validation->set_rules('city', 'Bynavn', 'callback_city_check');
		$this->form_validation->set_rules('email', 'E-mail-adresse', 'required|valid_email');
		$this->form_validation->set_rules('tel', 'Telefonnummer', 'required|callback_phone_number_check');
		$this->form_validation->set_rules('tel2', 'Telefonnummer 2', 'callback_phone_number_check');
		if ($viewdata['admin'] == 0)
		{
			$this->form_validation->set_rules('password', 'Kodeord', 'callback_password_is_not_null');
			$this->form_validation->set_rules('password_confirmed', 'Kodeord gentaget', 'callback_password_match');
		}
		
		//There's an validation error - reload view but don't loose the data so the user has to retype everything
		if ($this->form_validation->run() == FALSE)
		{
			$viewdata['errors']         = validation_errors();
			$viewdata['division']       = $this->input->post('division');
			$viewdata['firstname']      = $this->input->post('firstname');
			$viewdata['middlename']     = $this->input->post('middlename');
			$viewdata['lastname']       = $this->input->post('lastname');
			$viewdata['adr1']           = $this->input->post('adr1');
			$viewdata['adr2']           = $this->input->post('adr2');
			$viewdata['streetno']       = $this->input->post('streetno');
			$viewdata['floor']          = $this->input->post('floor');
			$viewdata['door']           = $this->input->post('door');
			$viewdata['adr3']           = $this->input->post('adr3');
			$viewdata['zip']            = $this->input->post('zip');
			$viewdata['city']           = $this->input->post('city');
			$viewdata['email']          = $this->input->post('email');
			$viewdata['tel']            = $this->input->post('tel');
			$viewdata['tel2']         = $this->input->post('tel2');
			$viewdata['privacy']         = $this->input->post('privacy');
			$viewdata['divisionselect'] =	$this->_divisionselect($this->input->post('division'),0);
			$viewdata['divisionselectall'] =	$this->_divisionselect($this->input->post('division'),1);
			$viewdata['divisionselectonline'] =	$this->_divisionselect($this->input->post('division'),2);
			if ($viewdata['admin'])
			{
				if ($this->uri->segment(4) == 'exists')
				{
					$this->load->view('v_becomememberform_exists', $viewdata);
				} else {
					$this->load->view('v_becomememberform_kontant', $viewdata);
				}
			} else {
				$this->load->view('v_becomememberform', $viewdata);
			}
		}
		else
		{
			$this->Memberinfo->update_from_post();
			$member_id = $this->Memberinfo->create();
			$viewdata['division']      = $this->input->post('division');
			$viewdata['firstname']     = $this->input->post('firstname');
			$viewdata['middlename']    = $this->input->post('middlename');
			$viewdata['lastname']      = $this->input->post('lastname');
			$viewdata['adr1']          = $this->input->post('adr1');
			$viewdata['adr2']          = $this->input->post('adr2');
			$viewdata['streetno']      = $this->input->post('streetno');
			$viewdata['floor']         = $this->input->post('floor');
			$viewdata['door']          = $this->input->post('door');
			$viewdata['adr3']          = $this->input->post('adr3');
			$viewdata['zip']           = $this->input->post('zip');
			$viewdata['city']          = $this->input->post('city');
			$viewdata['email']         = $this->input->post('email');
			$viewdata['tel']           = $this->input->post('tel');
			$viewdata['tel2']			= $this->input->post('tel2');
			$viewdata['privacy']		= $this->input->post('privacy');
			$viewdata['medlem']        = $member_id;

			if ($viewdata['admin'])
			{
				$this->_activate($member_id);
				$this->_set_division($member_id,$viewdata['division']);
				if ($this->input->post('divisionexists') > 0)
				{
					$this->_change_division($viewdata['admin'],$member_id,$this->input->post('divisionexists'));
				} else {
					$orderinfo = $this->_createorder($member_id, FF_MEMBERSHIP, $this->session->userdata('uid'), 1, 'now()', 'kontant', $viewdata['division']);
				}
				$this->Personsmodel->send_new_membermail($member_id);
				$viewdata['title'] = 'Nyt medlem registreret, kontantbetaling';
				$viewdata['heading'] = 'Nyt medlem registreret, kontantbetaling';
				$viewdata['content'] = '<b>' . $viewdata['firstname'] . ' ' . $viewdata['middlename'] . ' ' . $viewdata['lastname'] . '</b> er nu oprettet som medlem.<br>Medlemsnummer <b>' . $member_id . '</b>.<br>En mail med login-information er sendt til <b>' . $viewdata['email'] .'</b>'; 
				$this->load->view('page', $viewdata);
			} else {
				$orderinfo = $this->_createorder($member_id, FF_MEMBERSHIP, $member_id, 1, 'now()', 'nets', $this->input->post('division'));
				$viewdata['orderno'] = $orderinfo['orderno'];
				$viewdata['orderkey'] = $orderinfo['orderkey'];
				//Vi må hellere lave et redirect så man ikke kan submitte den samme form mange gange?
				$this->load->view('v_becomememberpay', $viewdata);
			}
		}
	}
	
    function intro() 
	{
		$this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
		$this->javascript->output($js);
		$this->javascript->compile();

		$data = array(
              'title' => 'Bliv medlem',
              'heading' => 'Bliv medlem',
              'content' => '',
         );

		$this->load->view('v_blivmedlem_intro', $data);
	}

	function email_is_unique($email)
	{
		if ($this->Memberinfo->email_is_unique($email))
		{
			return TRUE;
		}
		else 
		{
			$this->form_validation->set_message('email_is_unique', 'E-mail-adressen: '.$email.' er allerede i brug!');
			return FALSE;
		}
	}
	
	//Helpers - redundant code! Should be refactored, but can't acts as helper without rewritting since helpers is outside $this scope
	function password_is_not_null($password)
	{
//		if ($password === 'd41d8cd98f00b204e9800998ecf8427e') // if it's MD5'ed
		if ($password == '')
		{
			$this->form_validation->set_message('password_is_not_null', 'Kodeordet må ikke være tomt');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function password_match($password)
	{
		if ($password !== $_POST['password'])
		{
			$this->form_validation->set_message('password_match', 'Kodeordene er ikke ens');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function phonenumber_check($phonenumber)
	{
		//The field is optionally
		if ($phonenumber == '')
		{
			return TRUE;
		}
		else
		{
			if (strlen($phonenumber) !== 8)
			{
				$this->form_validation->set_message('phonenumber_check', 'Hvis udfyldt, skal mobilnummer indeholde netop 8 tal');
				return FALSE;			
			}
		}
	}

	function streetname_check($streetname)
	{
		//The field is optionally
		if ($streetname == '')
		{
			return TRUE;
		}
		else
		{
			if (strlen($streetname) < 2)
			{
				$this->form_validation->set_message('streetname_check', 'Hvis udfyldt, skal vejnavn indeholde mere end ét bogstav');
				return FALSE;			
			}
		}
	}
	
	function city_check($city)
	{
		//The field is optionally
		if ($city == '')
		{
			return TRUE;
		}
		else
		{
			if (strlen($city) < 2)
			{
				$this->form_validation->set_message('city_check', 'Hvis udfyldt, skal bynavn indeholde mere end ét bogstav');
				return FALSE;			
			}
		}
	}

	function _createorder($uid, $producttype_id, $created_by, $quant, $date, $method, $division)
	{
		$neworder = createuniqueorderno($uid, 'kontant');
		// Create orderlines
		$orderno = $neworder['orderno'];
		$orderkey = $neworder['orderkey'];
	    $price = $this->_getitemprice($division, $producttype_id) ;
		$amount = $quant * $price['price'];
		if ($price['error'] > '')
		{
			$status3 = 'priceerror';
		}
		$itemid = $price['id'];
		$vat_amount = 0; // To be handled elsewhere
	    $this->_insertorderline($orderno, $orderkey, $uid, $itemid, $quant, $amount, $vat_amount,$created_by, $date, $status3) ;
	    $this->_updateorderhead($orderno, $orderkey, $amount, $vat_amount,$status3, $method, $created_by) ;
		$return = Array();
		$return['orderno'] = $orderno;
		$return['orderkey'] = $orderkey;
		return $return;
	}

    function _insertorderline($orderno, $orderkey, $puid, $item, $quant, $amount, $vat_amount,$created_by, $date, $status3) 
	{
		$this->db->set('orderno', $orderno);
		$this->db->set('orderkey', $orderkey);
		$this->db->set('puid', $puid);
		$this->db->set('item', $item);
		$this->db->set('iteminfo', $date);
		$this->db->set('quant', $quant);
		$this->db->set('amount', $amount);
		$this->db->set('vat_amount', $vat_amount);
		$this->db->set('status1', $created_by);
		$this->db->set('status2', '');
		$this->db->set('status3', $status3 .'');
		$this->db->insert('orderlines');
	}

    function _updateorderhead($orderno, $orderkey, $amount, $vat_amount,$status3, $method, $created_by) 
	{
		$data = array(
		               'cc_trans_amount' => $amount,
		               'cc_trans_vat_amount' => $vat_amount,
		               'cc_trans_no' => 0,
		               'status1' => $method,
		               'status2' => '',
		               'status3' => $status3
		            );
		
		$this->db->set('cc_trans_date', 'NOW()', FALSE);  
		$this->db->set('changed', 'NOW()', FALSE);  
		$this->db->where('orderno', $orderno);
		$this->db->where('orderkey', $orderkey);
		$this->db->update('orderhead', $data);
// $debugstr = $this->db->last_query();
// echo ("<!-$debugstr-\n\n->");		
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
	
	function _getitemprice($division, $item = FF_MEMBERSHIP)
	{
		$this->db->select('amount')->select('id')->from('items')->where('division', $division)->where('producttype_id', $item)->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$return['price'] = $row->amount;
			$return['id'] = $row->id;
			$return['error'] = '';
		} else {
			$return['price'] = 0;
			$return['error'] = 'Varen findes ikke!';
		}
		return $return;
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

    function _activate($uid) 
	{
		$this->db->set('active', 'yes');  
		$this->db->set('changed', 'now()', FALSE);  
		$this->db->where('uid', $uid);
		$this->db->update('persons');
	}

    function _set_division($puid,$division)
	{
		$this->db->set('division', $division);
		$this->db->set('member', $puid);
		$this->db->set('start', 'curdate()', FALSE);
		$this->db->set('exit', 'date_add(curdate(), INTERVAL 1 YEAR)', FALSE);
		$this->db->insert('division_members');
	}
	
    function _change_division($admin,$uid,$divisionfrom)
	{
		$this->db->set('status1', 'from ' . $divisionfrom);  
		$this->db->where('uid', $uid);
		$this->db->update('persons');
		$sql = "INSERT INTO ff_log (creator, member, type, text) VALUES ("  . (int)$admin . ',' . (int)$uid .",'adminlog','medlem $uid indmeldt uden betaling')";
		$this->db->query($sql);
		// Later: Add mail to existing division etc.
	}
	
}
	include("ressources/.sendmail.php");
	include("ressources/.mysql_common.php");
	include("ressources/.library.php");

/* End of file blivmedlem.php */
/* Location: ./controllers/blivmedlem.php */