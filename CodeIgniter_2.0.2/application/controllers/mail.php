<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->helper('menu');
		$this->load->helper('url');
        $this->load->library('javascript');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
    }

    function index() {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');


		$createsel = '';
		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		$this->db->order_by('divisions.name');
		$query = $this->db->get();

		if (intval($this->session->userdata('uid')) < 10)	// member is superadmin
		{
				$createsel .= '<option value="999">Alle afdelinger' . "</option>\n";
		}

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
			$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $row['uid']);
			if ($p_administrator)
			{
				$createsel .= '<option value="' . $row['uid'] . '">' . $row['name'] . "</option>\n";
			}
		}
		if (! $createsel)
			redirect(base_url().'minside/index');

		$data = array(
               'title' => 'KBHFF Massemail',
               'heading' => 'KBHFF Massemail',
			   'subject' => 'Nyt fra KBHFF',
               'createsel' => $createsel,
               'subsel' => $this->_createsubsel(),
          );

		$this->load->view('v_mail', $data);
    }

    function send() {
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$noprivacy = $this->input->post('noprivacy');
		$division = $this->input->post('division');
		$message = $this->input->post('message');
		$subject = $this->input->post('subject');
		$subsel = $this->input->post('subsel');
		$divisionname = $this->_divisionname($division);

		$permissions = $this->session->userdata('permissions');
		$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $division);
		if (! $p_administrator)
			redirect(base_url().'minside/index');

  set_time_limit(300);

$this->load->library('email');

$config['protocol'] = 'smtp';
$config['smtp_user'] = GLOBAL_SMTP_USER;
$config['smtp_pass'] = GLOBAL_SMTP_PASS;
$config['smtp_port'] = '465';
$config['crlf'] = '\r\n';
$config['newline'] = '\r\n';
$config['mailtype'] = 'html';
$config['smtp_host'] = 'ssl://' + GLOBAL_SMTP_HOST;
$config['bcc_batch_mode'] = TRUE;
$config['bcc_batch_size'] = 80;
$this->email->initialize($config);
$this->email->from('robot@medlem.kbhff.dk', 'KBHFF');

if ($noprivacy == 'Y')
{
//	$privsel = ' AND ((ff_persons.privacy <> "Y") or (ff_persons.privacy is null )) ';
	$privsel = '';
} else {
	$privsel = ' AND ff_persons.privacy = "Y" ';
}

if ($subsel == 'a')
{
	$descr = 'alle medlemmer i' . " $divisionname";
	$select = ' where ff_persons.email > "" ' . $privsel . ' AND ff_persons.active = "yes" AND ff_division_members.member = ff_persons.uid AND ff_division_members.division = ' . (int)$division;
}

if (substr($subsel,0,1) == 'c')
{
	$id = substr($subsel,1);
	$descr = 'alle medlemmer i "' . $this->_getlist('c',$id) . "\", $divisionname";
	$select =  ', ff_roles where ff_persons.email > "" ' . $privsel . '       AND ff_persons.uid = ff_roles.puid        AND ff_roles.role = '.$id.'         AND ff_roles.expires > now()        AND ff_roles.status = "aktiv"        AND ff_roles.department = ff_division_members.division';
}

if (substr($subsel,0,1) == 'g')
{
	$id = substr($subsel,1);
	$descr = 'alle medlemmer i "' . $this->_getlist('g',$id) . "\", $divisionname";
	$select =  ', ff_groupmembers where ff_persons.email > "" ' . $privsel .' AND ff_persons.uid = ff_groupmembers.puid AND ff_groupmembers.group = '.$id.' AND ff_groupmembers.expires > now() AND ff_groupmembers.status = "aktiv" AND ff_groupmembers.department = ff_division_members.division';
}

	$q = 'SELECT email FROM ff_persons, ff_division_members' . $select . ' AND ff_persons.active = "yes" AND ff_division_members.member = ff_persons.uid AND ff_division_members.division = ' . (int)$division;
	$q .= ' union ';
	$q .= 'SELECT alias FROM ff_persons, ff_division_members, ff_mail_aliases' . $select . ' AND ff_mail_aliases.puid = ff_persons.uid AND ff_persons.active = "yes" AND ff_division_members.member = ff_persons.uid AND ff_division_members.division = ' . (int)$division;

if ((intval($this->session->userdata('uid'))<10)&&($division == 999))	// member is superadmin & all depts
{
	$descr = 'alle medlemmer i KBHFF';
	$select =  ' where ff_persons.email > "" ' . $privsel ;
	$q = 'SELECT email FROM ff_persons' . $select . ' AND ff_persons.active = "yes"';
	$q .= ' union ';
	$q .= 'SELECT alias FROM ff_persons, ff_mail_aliases' . $select . ' AND ff_mail_aliases.puid = ff_persons.uid AND ff_persons.active = "yes"';
}


$query = $this->db->query($q);
$count = $query->num_rows();

$this->load->dbutil();

// echo $this->db->last_query();
$rec = str_replace('"','',$this->dbutil->csv_from_result($query));
$this->email->bcc($rec);

// $subject = 'Nyt fra KBHFF ' . $this->_divisionname($division);
$this->email->subject($subject);
$htmlmessage = '<html><head></head><body style="background-color: White; font-family: Tahoma, Geneva, Arial, Helvetica, sans-serif; font-size: 10pt;"><table width="600" border="0" cellpadding="20" class="form1" style="background:#C1EBC1; padding: 20px;text-align: left;"><tr><td align="left"><img src="http://medlem.kbhff.dk/images/kbhfflogo.png" align="right"><br /><br clear="all"/>' .nl2br($message) . '<br><i>' . utf8_encode('Denne besked sendes til dig, da du er medlem af KBHFF. Ønsker du ikke at modtage mails, kan du ændre opsætning på medlem.kbhff.dk') . '</i></td></tr></table></body></html>';

// save mail in maillog
	$query = 'INSERT INTO `ff_massmail_log` (`subject` , `content` , `sender` , `division` , `group` ,`privacy` , `num` ) VALUES ("' . addslashes($subject) . '","' . addslashes($message) . '",' . $this->session->userdata('uid') . ',' . (int)$division . ', "' . $subsel  . '" ,"' . addslashes($noprivacy) . '",' . (int)$count . ')';
	$logquery = $this->db->query($query);


$this->email->message($htmlmessage);
$this->email->set_alt_message($message);
$this->email->send();

	if ($noprivacy == 'Y')
	{
		$privacymsg = ' incl. de der ikke har sagt ja til nyhedsbreve.';
	}	else {
		$privacymsg = ', kun de der har sagt ja til nyhedsbreve.';
	}

		$data = array(
               'title' => 'Udsendelse af KBHFF Massemail til ' . $this->_divisionname($division),
               'heading' => 'Udsendelse af KBHFF Massemail til ' . $this->_divisionname($division),
               'content' => 'Besked:<br><pre>' . $subject . "\n" . $message . '</pre><br>Er udsendt til ' . $descr . ', ' . $count . ' stk.'.$privacymsg.'<br>',
          );

		$this->load->view('page', $data);
    }

	private function _getlist($type, $id)
	{
			if ($type == 'g')
			{
				$table = 'ff_groups';
			}
			if ($type == 'c')
			{
				$table = 'ff_chore_types';
			}
			$this->db->select('name');
			$this->db->from($table);
			$this->db->where('uid', (int)$id);
			$query = $this->db->get();
			$row = $query->row();
			return $row->name;
	}

	private function _divisionname($division = 4)
	{
			$this->db->select('name');
			$this->db->from('divisions');
			$this->db->where('uid', (int)$division);
			$query = $this->db->get();
			$row = $query->row();
			return $row->name;
	}

	private function _createsubsel()
	{
			$subsel = '<option value="a">Alle i afdelingen</option>'."\n";
			$subsel .= '<optgroup label="Funktioner">'."\n";
			$this->db->select('name');
			$this->db->select('uid');
			$this->db->from('chore_types');
			$this->db->where('auth > 0');
			$this->db->order_by('name');
			$query = $this->db->get();
			$row = $query->row();
			foreach ($query->result_array() as $row)
			{
				$subsel .= '<option value="c' . $row['uid'] . '">' . $row['name'] . "</option>\n";
			}
			$subsel .= '</optgroup>'."\n";
			$subsel .= '<optgroup label="Afdelingsgrupper">'."\n";

			$this->db->select('name');
			$this->db->select('uid');
			$this->db->from('groups');
			$this->db->where('type','Afdelingsgruppe');
			$this->db->order_by('name');
			$query = $this->db->get();
			$row = $query->row();
			foreach ($query->result_array() as $row)
			{
				$subsel .= '<option value="g' . $row['uid'] . '">' . $row['name'] . "</option>\n";
			}
			$subsel .= '</optgroup>'."\n";
			return $subsel;
	}

} // class Mail

/* End of file mail.php */
/* Location: ./application/controllers/mail.php */
