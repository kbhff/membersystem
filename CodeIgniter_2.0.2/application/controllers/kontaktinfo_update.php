<?php
//Author: Frederik Dam Sunne (frederiksunne@gmail.com)

class Kontaktinfo_update extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->model('Memberinfo');
		$this->load->helper('menu');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('email');
 		if (! intval($this->session->userdata('uid')) > 0)
			redirect('./');	
		$viewdata = Array();
	}
	
	function index()
	{
		//Are we, as admin, editing another member's memberinfo? 
		$viewdata['pw_edit'] = $pw_edit = TRUE;
		$admin = FALSE;
		//We are logged in, meaning we're not a new member

		
		if ($this->session->userdata('uid') > 0)
		{
			//If the memberid does not belong to the logged in member, the member must have at least writing permission
			if ($this->session->userdata('uid') != intval($this->input->post('uid')))
			{

				$permissions = $this->session->userdata('permissions');
				$divisions = $this->Memberinfo->get_user_division(intval($this->input->post('uid')));
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
				} else {
						if ($this->Memberinfo->checkpermission($permissions, 'Administrator', $divisions))
						{
							$admin = TRUE;
						}
				}
				if (!$admin)
				{
					redirect(base_url().'index.php/logud');
					exit();
				}

				$pw_edit = FALSE;
			}

		}		
		
		$this->form_validation->set_rules('firstname', 'Fornavn', 'required|min_length[2]');
		$this->form_validation->set_rules('lastname', 'Efternavn', 'required|min_length[2]');
	    $this->form_validation->set_rules('adr2', 'Vejnavn', 'callback_street_name_check');
	    $this->form_validation->set_rules('zip', 'Postnummer', 'numeric|exact_length[4]');
	    $this->form_validation->set_rules('city', 'Bynavn', 'callback_city_check');
		$this->form_validation->set_rules('email', 'E-mail-adresse', 'required|valid_email');
		$this->form_validation->set_rules('tel', 'Telefonnummer 1', 'callback_phone_number_check');
		$this->form_validation->set_rules('tel2', 'Telefonnummer 2', 'callback_phone_number_check');
		//When we are editing as admin we don't touch the password - so disable the check
		if ($pw_edit)
		{
			$this->form_validation->set_rules('password', 'Kodeord', 'callback_password_is_not_null');
			$this->form_validation->set_rules('password_confirmed', 'Kodeord gentaget', 'callback_password_match');
		}
		
		//There's an validation error - reload view but don't loose the data so the user has to retype everything
		if ($this->form_validation->run() == FALSE)
		{
			$viewdata['errors']       = validation_errors();
			$viewdata['note']         = '';
			if ($admin)
			{
				$viewdata['uid']           = $this->input->post('uid');
				$viewdata['note']         = $this->input->post('note');
			} else {
				$viewdata['uid']           = $this->session->userdata('uid');
			}
			$viewdata['firstname']     = $this->input->post('firstname');
			$viewdata['middlename']     = $this->input->post('middlename');
			$viewdata['lastname']    = $this->input->post('lastname');
			$viewdata['adr1']  = $this->input->post('adr1');
			$viewdata['adr2']  = $this->input->post('adr2');
			$viewdata['streetno'] = $this->input->post('streetno');
			$viewdata['floor']        = $this->input->post('floor');
			$viewdata['door']        = $this->input->post('door');
			$viewdata['adr3']        = $this->input->post('adr3');
			$viewdata['zip']     = $this->input->post('zip');
			$viewdata['city']         = $this->input->post('city');
			$viewdata['email']        = $this->input->post('email');
			$viewdata['tel'] = $this->input->post('tel');
			$viewdata['tel2'] = $this->input->post('tel2');
			$viewdata['privacy']         = $this->input->post('privacy');
			$viewdata['mail_aliases']         = $this->input->post('mail_aliases');
			if ($admin)
			{
				$viewdata['active']         = $this->input->post('active');
			}
			$this->load->view('v_kontaktinfoform', $viewdata);
		}
		//Validation was succesful!
		else
		{
			if ($admin)
			{
				$this->_updatealiases($this->input->post('uid'), $this->input->post('email'), $this->input->post('mail_aliases'));
				$this->Memberinfo->update_note($this->input->post('uid'), $this->input->post('note'), $this->session->userdata('uid'));
			} else {
				$this->_updatealiases($this->session->userdata('uid'), $this->input->post('email'), $this->input->post('mail_aliases'));
			}
			$this->Memberinfo->update_from_post($pw_edit, $admin);
			if ($this->session->userdata('uid') != intval($this->input->post('uid')))
			{
				$this->Memberinfo->update(intval($this->input->post('uid')));
				$viewdata['message'] = 'Medlemmets data er opdateret';
			}
			else
			{
				$this->Memberinfo->update($this->session->userdata('uid'));
				$viewdata['message'] = 'Dine data er opdateret';
			}

			if ($this->password_is_null($this->input->post('password')))
			{
				$viewdata['message'] .= '<br>Kodeord er ikke &aelig;ndret.';
			}

			$this->load->view('v_kontaktinfo_update_succes', $viewdata);
		}		
	}
	
	//Helpers - redundant code! Should be refactored, but can't acts as helper without rewritting since helpers is outside $this scope
	function password_is_null($password)
	{
//		if ($password === 'd41d8cd98f00b204e9800998ecf8427e') // if it's MD5'ed
		if ($password == '')
		{
			$this->form_validation->set_message('password_is_not_null', 'Kodeordet må ikke være tomt');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function password_match($password)
	{
		if ($password <> $this->input->post('password'))
		{
			$this->form_validation->set_message('password_match', 'Kodeordene er ikke identiske');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function phone_number_check($phone_number)
	{
		//The field is optionally
		if ($phone_number == '')
		{
			return TRUE;
		}
		else
		{
			if (strlen($phone_number) !== 8)
			{
				$this->form_validation->set_message('phone_number_check', 'Hvis udfyldt, skal mobilnummer indeholde netop 8 tal');
				return FALSE;			
			}
		}
	}

	function streetname_check($street_name)
	{
		//The field is optionally
		if ($street_name == '')
		{
			return TRUE;
		}
		else
		{
			if (strlen($street_name) < 2)
			{
				$this->form_validation->set_message('street_name_check', 'Hvis udfyldt, skal vejnavn indeholde mere end ét bogstav');
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
	private function _updatealiases($uid, $email, $aliases)
	{
		$this->db->where('puid', $uid); 
		$this->db->delete('mail_aliases'); 
		$mail_aliases = explode("\n",$aliases);
		if (is_array($mail_aliases))
		{
			foreach ($mail_aliases as $alias)
			{
				if (valid_email($alias))
				{
					$this->db->set('puid', $uid);
					$this->db->set('master', $email);
					$this->db->set('alias', $alias);
					$this->db->insert('mail_aliases');
				}
			}
		}
	}
}
/* End of file kontaktinfo_update */
/* Location: ./controllers/kontaktinfo_update */