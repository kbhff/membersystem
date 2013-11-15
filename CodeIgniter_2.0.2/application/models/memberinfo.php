<?php

class Memberinfo extends CI_Model {

	var $firstname, $middlename, $lastname, $adr1, $adr2, $streetno, $floor, $door;
	var $zip, $city, $tel, $tel2, $email;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function division_info($medlemsnummer)
	{
		$return = array();
		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions, division_members');
		$this->db->where('divisions.uid = ff_division_members.division'); 
		$this->db->where('division_members.member', (int)$medlemsnummer); 
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$return['name'] = $row->name;
			$return['division'] = $row->uid;
		} else {
			$return['name'] = 'men ikke tilknyttet afdeling';
			$return['division'] = '0';
		}
		return $return;
	}	

	function pickups_by_division($division)
	{
		$query = $this->db->query("SET lc_time_names = 'da_DK'");
		$this->db->select('pickupdate');
		$this->db->select('date_format(pickupdate,"%W d. %e %M, %Y") as pickupdate', FALSE);
		$this->db->from('pickupdates');
		$this->db->where('pickupdate >= curdate()'); 
		$this->db->where('division', (int)$division); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		} 
	}	

	function pickup_uid_by_division($division)
	{

		$this->db->select('uid');
		$this->db->select('pickupdate');
		$this->db->select('itemdays.item as itemdayitem');
		$this->db->select('producttypes.explained');
		$this->db->select('TIMESTAMPDIFF(minute,now(),ff_itemdays.lastorder) as cancel', false);
		$this->db->from('pickupdates');
		$this->db->join('itemdays', 'ff_itemdays.pickupday = ff_pickupdates.uid','left');
		$this->db->join('producttypes', 'ff_producttypes.id = ff_itemdays.item','left');
		$this->db->join('items', 'ff_items.producttype_id = ff_itemdays.item and ff_items.division =' . (int)$division,'left');
		$this->db->where('pickupdate > curdate()'); 
		$this->db->where('pickupdates.division', (int)$division); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		} 
	}	

	function pickups_by_member($member)
	{
		$return = array();
/*

		$this->db->select('pickupdates.pickupdate, divisions.name, items.amount, pickupdates.uid, timediff(lastorder,curdate()) as cancel, orderlines.quant');
		$this->db->from('pickupdates, division_members, divisions, items');
		$this->db->join('orderlines', 'orderlines.item = pickupdates.uid AND ' . $this->db->protect_identifiers('orderlines', TRUE) . '.item = ' . $this->db->protect_identifiers('pickupdates', TRUE) . '.uid', 'left');
		$this->db->join('orderhead', 'orderhead.orderno = orderlines.orderno AND ((' . $this->db->protect_identifiers('orderhead', TRUE) . '.status1 = "nets") or (' . $this->db->protect_identifiers('orderhead', TRUE) . '.status1 = "kontant")) AND ' . $this->db->protect_identifiers('orderhead', TRUE) . '.puid = ' . $this->db->protect_identifiers('division_members', TRUE) . '.member', 'left');		
		$this->db->where('division_members.member', (int)$member); 
		$this->db->where('pickupdates.division = ' . $this->db->protect_identifiers('division_members', TRUE) . '.division'); 
		$this->db->where('pickupdate >= curdate()'); 
		$this->db->where('divisions.uid = ' . $this->db->protect_identifiers('pickupdates', TRUE) . '.division'); 
		$this->db->where('items.producttype_id', FF_GROCERYBAG); 
		$this->db->where('items.division = ' . $this->db->protect_identifiers('pickupdates', TRUE) . '.division'); 
		$this->db->order_by('pickupdates.pickupdate'); 
*/

$query = $this->db->query('SELECT `ff_pickupdates`.`pickupdate`, `ff_divisions`.`name`, `ff_items`.`amount`, `ff_pickupdates`.`uid`, date_format(`ff_itemdays`.`lastorder`,"%e/%c %k:%i") as lastorder, TIMESTAMPDIFF(minute,now(),lastorder) as cancel, `ff_orderlines`.`quant`, ff_orderlines.orderno, ff_orderlines.status1, ff_orderhead.status1, ff_producttypes.explained,ff_producttypes.id
FROM (`ff_pickupdates`, `ff_division_members`, `ff_divisions`, `ff_items`, `ff_itemdays`, `ff_producttypes`)

LEFT JOIN (`ff_orderlines`,ff_orderhead) ON ( `ff_orderlines`.`iteminfo` = `ff_pickupdates`.`uid` AND `ff_orderlines`.item = `ff_items`.id AND ff_orderlines.puid = ' . (int)$member . ' AND ff_orderlines.item = ff_items.id
AND `ff_orderhead`.`orderno` = `ff_orderlines`.`orderno` AND ((`ff_orderhead`.status1 = "nets") or (`ff_orderhead`.status1 = "kontant")) AND `ff_orderhead`.puid = `ff_division_members`.member )


WHERE `ff_division_members`.`member` = ' . (int)$member . '
AND `ff_pickupdates`.`division` = `ff_division_members`.division
AND `pickupdate` >= curdate()
AND `ff_divisions`.`uid` = `ff_pickupdates`.division
AND `ff_itemdays`.`item` = `ff_items`.`producttype_id`
AND `ff_itemdays`.`pickupday` = `ff_pickupdates`.`uid`
AND `ff_items`.`division` = `ff_pickupdates`.division
AND `ff_producttypes`.`id` = `ff_items`.`producttype_id`
ORDER BY `ff_pickupdates`.`pickupdate`,ff_producttypes.explained ');


		
// echo '<!---';
// print_r($this->db->last_query());
// echo '-->';

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		} 
	}	

	
	function validate_login($user, $pw, $timestamp)
	{
		$this->db->select('uid, password, last_login, active')->from('persons')->where('uid', $user)->limit(1);
		$query = $this->db->get();
		$row = $query->row();

		if($query->num_rows() === 1 && md5($pw) === $row->password && $timestamp > mysql_to_unix($row->last_login))
		{
			if ($row->active == 'yes')
			{
				$this->db->set('last_login', 'NOW()', FALSE);
				$this->db->update('persons', null, "uid = ".$row->uid);
				return 'OK';
			} else {
				if ($row->active === 'no')
				{
			        return "Du har status af inaktiv i Kbhff's medlemssystem. Derfor har du ikke adgang til at logge ind. Hvis du gerne vil v&aelig;re aktiv igen, skal du kontakte din lokalafdeling";
				}
				if ($row->active === 'X')
				{
			        return 'Du har status som udmeldt af KBHFF - kontakt din afdeling hvis du vil meldes ind igen';
				}
			}
		}
		else 
		{
	        return '?? fejl i medlemsnummer eller kodeord';
		}
	}
	

	//Updates this object attributes with the values received from the form
	function update_from_post($password_change = FALSE, $admin = FALSE)
	{
		$this->firstname    = $this->input->post('firstname');
		$this->middlename   = $this->input->post('middlename');
		$this->lastname     = $this->input->post('lastname');
		$this->adr1         = $this->input->post('adr1');
		$this->adr2         = $this->input->post('adr2');
		$this->streetno     = $this->input->post('streetno');
		$this->floor        = $this->input->post('floor');
		$this->door         = $this->input->post('door');
		$this->adr3         = $this->input->post('adr3');
		$this->zip          = $this->input->post('zip');
		$this->city         = $this->input->post('city');
		$this->tel          = $this->input->post('tel');
		$this->tel2       = $this->input->post('tel2');
		$this->email        = $this->input->post('email');
		$this->privacy        = $this->input->post('privacy');
		if ($this->input->post('password') === '')	// It's blank
		{
				$password_change = FALSE;
		}
		
		if ($password_change)
			$this->password = md5($this->input->post('password'));
		
		if ($admin)
			$this->active = $this->input->post('active');
	}
	
	function create()
	{
		$this->db->insert('persons', $this);
		return $this->db->insert_id();
	}

	//Email must be unique to all active members...
	function email_is_unique($email)
	{
		$this->db->select('email')->from('persons')->where('email', $email)->where('active', 'yes')->limit(1);
		$query = $this->db->get();
		return $query->num_rows() === 0;
	}

	function update($uid)
	{
		$this->db->where('uid', $uid);
		$this->db->update('persons', $this); 
	}
	
	function get($uid)
	{
		$this->db->select('persons.*');
		$this->db->select('membernote.note');
		$this->db->join('membernote', 'membernote.puid = ff_persons.uid', 'left');
		$query = $this->db->get_where('persons', array('uid' => $uid), 1);	
		return ($query->result_array());
	}


	function get_records()
	{
		$query = $this->db->get('persons');
		return $query->result();
	}
	
	function add_record($data) 
	{
		$this->db->insert('persons', $data);
		return;
	}
	
	function search_member_org($name)
	{
		$this->db->select("uid, email, active+0 AS active, UNIX_TIMESTAMP(last_login) AS last_login, UNIX_TIMESTAMP(created) AS created, CONCAT(firstname,' ',middlename, ' ',lastname) AS name, tel", false)->from('persons')->like("CONCAT(firstname, ' ',middlename, ' ', lastname)", $name);
		$query = $this->db->get();
		return ($query->result_array());
	}

	function search_member($srch, $division = 0)
	{
		$srch = preg_replace("/ +/", " ", $srch);
		$srchparm = trim($srch);
		$this->db->dbprefix('division_members', TRUE);
		$this->db->dbprefix('persons', TRUE);
		$this->db->select("uid, email, active+0 AS active, UNIX_TIMESTAMP(last_login) AS last_login, UNIX_TIMESTAMP(ff_persons.created) AS created, CONCAT(firstname,' ',middlename, ' ',lastname) AS name, tel, tel2", false);
		$this->db->select('membernote.note');
		$this->db->join('membernote', 'membernote.puid = ff_persons.uid', 'left');
		$this->db->from('persons, division_members');
		$this->db->where('division_members.member = ' . $this->db->protect_identifiers('persons', TRUE) . '.uid'); 
		if ($division > 0)
		{
			$this->db->where('division_members.division', (int)$division); 
		}
		
//		$this->db->like("CONCAT(firstname, ' ', lastname)", $srchparm)->or_like("firstname", $srchparm)->or_like("tel", $srchparm)->or_like("tel2", $srchparm)->or_like("uid", $srchparm);

	$fname = $this->splitsrch($srchparm, 'firstname');
	$mname = $this->splitsrch($srchparm, 'middlename');
	$lname = $this->splitsrch($srchparm, 'lastname');
	
//    "((CONCAT(firstname, ' ', lastname) LIKE '%s%%') OR firstname LIKE '$fname%%' OR middlename LIKE '$mname%%' OR lastname LIKE '$lname%%' OR tel LIKE '%%%s%%' OR tel2 LIKE '%%%s%%' OR uid LIKE '%s')", 
//    "((CONCAT(firstname, ' ', lastname) LIKE '%s%%') OR firstname LIKE '%%%s%%' OR middlename LIKE '%%%s%%' OR lastname LIKE '%%%s%%' OR tel LIKE '%%%s%%' OR tel2 LIKE '%%%s%%' OR uid LIKE '%s')", 
//    "((CONCAT(firstname, ' ', lastname) LIKE '%s%%') OR firstname LIKE '$fname%%' OR middlename LIKE '$mname%%' OR lastname LIKE '$lname%%' OR tel LIKE '%%%s%%' OR tel2 LIKE '%%%s%%' OR uid LIKE '%s')", 
	$this->db->where(sprintf( 
    "((CONCAT(firstname, ' ', lastname) LIKE '%s%%') OR firstname LIKE '$fname' OR middlename LIKE '$mname' OR lastname LIKE '$lname' OR tel LIKE '%%%s%%' OR tel2 LIKE '%%%s%%' OR uid LIKE '%s' OR email LIKE '%s')", 
    $this->db->escape_like_str($srchparm),     
    $this->db->escape_like_str($srchparm),     
    $this->db->escape_like_str($srchparm),     
    $this->db->escape_like_str($srchparm),     
    $this->db->escape_like_str($srchparm)
	), NULL); 

		$query = $this->db->get();
//		echo ("\n<!--\n" . $this->db->last_query() ."-->\n");;
		return ($query->result_array());
	}

	function splitsrch($str,$field)
	{
		$str = str_replace(" ","' or $field like '",$str);
		return $str;
	}

	function get_members($division = 0)
	{
		$this->db->select('uid, CONCAT(`firstname`, " ", `middlename`, " ", `lastname`) AS `name`,');
		$this->db->select('email, tel, UNIX_TIMESTAMP(last_login) AS last_login,');
		$this->db->select('UNIX_TIMESTAMP(`created`) AS `created`', FALSE);
		$this->db->select('membernote.note');
		$this->db->from('persons, division_members');
		$this->db->join('membernote', 'membernote.puid = ff_persons.uid', 'left');
		$this->db->where('division_members.member = ' . $this->db->protect_identifiers('persons', TRUE) . '.uid'); 
		$this->db->where('division_members.division', (int)$division); 
		$this->db->where('active', 'yes');
		$this->db->order_by('name', 'asc');
		$query = $this->db->get();
		return ($query->result_array());
	}

	function get_user_division($uid)
	{
		$this->db->select('division');
		$this->db->from('division_members');
		$this->db->where('member', $uid); 
		$query = $this->db->get();
		return ($query->result_array());
	}


	function update_record($data) 
	{
		$this->db->where('uid', 12);
		$this->db->update('persons', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('persons');
	}
	
	function retrieve_by_medlemsnummer($medlemsnummer)
	{
		$query = $this->db->get_where('persons',array('uid'=>$medlemsnummer));
    	return $query->row_array();	
//		return $query->result_array();
	}

	function checkpermission($permissions, $role, $division = 0)
	{
		if (intval($this->session->userdata('uid'))<10)	// member is superadmin & all depts
		{
			return true;
		}
		if (isset($permissions["$division"]["$role"][0]))
		{
			if ($permissions["$division"]["$role"][0] >=0)
			{
				return $permissions["$division"]["$role"][0];
			} else {
				return false;
			}
		}
		return false;
	}

	function checkgrouppermission($permissions, $group)
	{
		if (isset($permissions['grupper']["$group"][0]))
		{
			if ($permissions['grupper']["$group"][0] == 'Y')
			{
				return 'Y';
			} else {
				return false;
			}
		}
		return false;
	}

	function update_note($puid, $note, $editor) 
	{
		$this->db->select('puid');
		$this->db->from('membernote');
		$this->db->where('puid', (int)$puid); 
		$query = $this->db->get();
		if ($query->num_rows() == 0)
		{
			$this->create_note($puid);
		}
		$this->db->set('note', $note);
		$this->db->set('editedby', (int)$editor);
		$this->db->set('changed', 'now()', FALSE);
		$this->db->where('puid', (int)$puid);
		$this->db->update('membernote');
	
	}

	function create_note($puid) 
	{
		$sql = "INSERT INTO ff_membernote (puid, note, editedby, changed, created) VALUES (" . (int)$puid .",'','0', now(), now())";
		$this->db->query($sql);
	}
	
}