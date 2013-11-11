<?php
class Permission extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


	function get($member_id)
	{

		$this->db->select('chore_types.name as funktion, divisions.name as afdelingsnavn, divisions.uid as afdelingsno, roles.level as level');
		$this->db->from('roles, chore_types, divisions');
		$this->db->where('roles.puid', (int)$member_id); 
		$this->db->where('chore_types.uid = ' . $this->db->protect_identifiers('roles', TRUE) . '.role'); 
		$this->db->where('divisions.uid = ' . $this->db->protect_identifiers('roles', TRUE) . '.department'); 
		$this->db->where('roles.status = "aktiv"'); 
		$this->db->where('roles.valid_from <= curdate()'); 
		$this->db->where('roles.expires > curdate()'); 
		$this->db->order_by('roles.department'); 

		$query = $this->db->get();
//		$permissions = array($query->num_rows());
		$permissions = array();
		foreach ($query->result_array() as $row)
		{
			$permissions[$row['afdelingsno']][$row['funktion']] = $row['level'];
			$permissions[$row['afdelingsno']]['afdelingsnavn'] = $row['afdelingsnavn'];
		}


		$this->db->select('groups.name as gruppe, divisions.name as gruppenavn, divisions.uid as gruppeno');
		$this->db->from('groups, groupmembers, divisions');
		$this->db->where('groupmembers.puid', (int)$member_id); 
		$this->db->where('groups.uid = ' . $this->db->protect_identifiers('groupmembers', TRUE) . '.group'); 
		$this->db->where('divisions.uid = ' . $this->db->protect_identifiers('groupmembers', TRUE) . '.department'); 
		$this->db->where('groupmembers.status = "aktiv"'); 
		$this->db->where('groupmembers.valid_from <= curdate()'); 
		$this->db->where('groupmembers.expires > curdate()'); 
		$this->db->order_by('groupmembers.department'); 

		$query = $this->db->get();
		foreach ($query->result_array() as $row)
		{
//			$permissions[$row['gruppeno']][$row['gruppe']] = 'Y';
//			$permissions[$row['gruppeno']]['gruppenavn'] = $row['gruppenavn'];
			$permissions['grupper'][$row['gruppe']] = 'Y';
		}

		return ($permissions);
	}
}
/* End of file permission.php */
/* Location: ./models/permission.php */