<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->helper('menu');
		$this->load->library('session');
		$this->load->helper('url');
        $this->load->library('javascript');
		$this->load->helper('html');
    }

    function index() {
        $data = array();

        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();

		$this->load->view('v_login', $data);
	
    }

    function glemtpassword() {
        $data = array();

        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();

		$this->load->view('v_glemtpassword', $data);
	
    }
	
    function resendpassword() {
		$email = $this->input->post('email');
		include_once("ressources/.sendmail.php");
		$msg = '';
		$email = trim($email);
		if (strlen($email) <4)
		{
				$msg = "E-mail adresse ikke gyldig.<br>\n";
		}
			
		$msg = "E-mail adresse ikke fundet.<br>\n";	// default message
		if (strpos($email,'@')>=1)
		{	
			$this->load->model('Personsmodel');
			$data = $this->Personsmodel->retrieve_by_email($email);
			$no = sizeof($data);			
			switch($no)
			{
			    case 1;
					$firstname = $data[0]['firstname'];
					$middlename = ''; 
					if ($data[0]['middlename'] != '')
					{
						$middlename = ' ' . $data[0]['middlename']; 
					}
					$lastname = $data[0]['lastname'];
					$email = $data[0]['email'];
					$medlemsnummer = $data[0]['uid'];
					$data = $this->Personsmodel->set_user_activation_key($medlemsnummer);
					$user_activation_key = $data[0]['user_activation_key'];
					$this->_sendinfomail($email, $firstname, $middlename, $lastname, $medlemsnummer, $user_activation_key);
					$msg = 'Email med instruktioner er fremsendt til ' . $email;
				break;
			    case 2;
			        $msg = 'Fejl: Der er ' . $no . ' medlemmer med samme mailadresse.<br>Det kan give problemer - tal med webmaster.';
			    break;
			    default;
			        $msg = 'Fejl i email / ikke fundet.';
			    break;
			}
		}

		$data = array(
               'title' => 'Fremsendelse af login information',
               'heading' => 'Fremsendelse af login information til<br>' . $email,
               'content' => $msg
          );
		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();

		$this->load->view('page', $data);
	
	}
	
    function resetpassword() {
		$medlemsnummer = $this->uri->segment(3);
		$user_activation_key = $this->uri->segment(4);
			$data = array(
	               'medlemsnummer' => $medlemsnummer,
	               'user_activation_key' => $user_activation_key,
	          );

        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();

		$this->load->view('v_resetpassword', $data);
	
    }
	

    function savepassword() {
	
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//		$js =$this->jquery->corner('#tt_del');
//        $this->javascript->output($js);
        $this->javascript->compile();

		$pw1 = $this->input->post('pw1');
		$pw2 = $this->input->post('pw2');
		$user_activation_key = $this->input->post('user_activation_key');
		$medlemsnummer = $this->input->post('medlemsnummer');

		if ($pw1 === $pw2)
		{
				$sql = 'update ' . $this->db->protect_identifiers('persons', TRUE) . '
				SET user_activation_key = "", password = "' . addslashes(md5($pw1)) .'"
				WHERE uid = ' . doubleval($medlemsnummer) . ' and
				user_activation_key = "' . addslashes($user_activation_key) . '"';
				$query = $this->db->query($sql);
				$data = array(
	               'title' => 'Adgangskode skiftet',
	               'heading' => 'Adgangskode skiftet',
	               'content' => 'Din nye adgangskode er gemt, og du skal nu logge ind med den.<br>', 
	          );
		} else {
			$data = array(
	               'title' => 'Fejl: Adgangskode IKKE skiftet',
	               'heading' => 'Fejl: Adgangskode IKKE skiftet',
	               'content' => 'Dine to indtastninger af adgangskode stemte ikke overens. Pr&oslash;v igen:<br>' .
					'<a href="https://medlem.kbhff.dk/login/resetpassword/' . doubleval($medlemsnummer) . '/' . $user_activation_key . '">' .
					"Skift adgangskode</a>\n\n"
	          );
		}
		$this->load->view('page', $data);
	
    }
	



	private function _sendinfomail($email, $firstname, $middlename, $lastname, $medlemsnummer, $user_activation_key)
	{
		$subject = "Adgangskode til KBHFF";
		$mailcontent = 	"K&aelig;re $firstname$middlename $lastname\n\n"
					.	"Du (eller en anden p&aring; dine vegne) har bedt om at f&aring; nulstillet\n"
					.	"kodeordet til KBHFF medlemssektionen.\n\n"
					.	"Adresse: https://medlem.kbhff.dk/\n"
					.	"Dit medlemsnummer: $medlemsnummer\n"
					.	"Hvis du skal nulstille dit kodeord, skal du besøge følgende adresse. Ellers kan du bare ignorere denne e-mail, så vil intet ske.\n\n"
					.	'<a href="https://medlem.kbhff.dk/login/resetpassword/' . $medlemsnummer . '/' . $user_activation_key . '">'
					.	'https://medlem.kbhff.dk/login/resetpassword/' . $medlemsnummer . '/' . $user_activation_key . "</a>\n\n"
					.	"Med venlig hilsen\n"
					.	"KBHFF web-robotten";
		
		sendenkeltmail ($subject,$mailcontent,$email, 'robot@medlem.kbhff.dk', "$firstname $middlename $lastname");
	}
	
} // class login 

/* End of file login.php */
/* Location: ./application/controllers/login.php */