<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rapportering extends CI_Controller {

    function __construct()
    {
        parent::__construct();
//        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
		$this->load->model('Report');
    }

    function index() {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		

//        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
//        $this->javascript->compile();
		$permissions = $this->session->userdata('permissions');

		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		$this->db->order_by('divisions.name'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
			$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $row['uid']);
			if (($p_administrator) || ($p_kassemester))
			{
				$sel .= '<a href="/rapportering/kassemester/' . $row['uid'] . '">Dagrapport, ' .$row['name'] . "</a><br>\n";
			}
		}
		

		$data = array(
               'title' => 'KBHFF Administrationsside',
               'heading' => 'KBHFF Administrationsside: Rapportering',
			   'content' => 'Test af rapportering af dagens salg, kassebev&aelig;gelser m.m.<br>' . $sel,
          );

		$this->load->view('page', $data);
    }

	function kassemester()
	{

		$pickupday = $this->input->post('pickupday');
		if ($this->input->post('status') == 'update')
		{
			$this->Report->save_form_data($pickupday, 'kassemester');
		}

		if ($this->uri->segment(3) > 0)
		{
			$division = $this->uri->segment(3);
		}	else    {
			$division = $this->input->post('division');
		}
			$this->db->select('name');
			$this->db->select('comment');
			$this->db->select('sort');
			$this->db->select('uid');
			$this->db->select('editable');
			$this->db->select('noterequired');
			$this->db->from('reportfields');
			$this->db->where('type', 'kassemester'); 
			$this->db->order_by('sort'); 
			$query = $this->db->get();
			$fields = $query->result_array();
			
			$data = $this->Report->getdata($pickupday,$division,'kassemester');

		
		
		$data = array(
               'title' => 'KBHFF Administrationsside',
               'heading' => $this->_divisionname($division),
			   'content' => 'Test af rapportering af dagens salg, kassebev&aelig;gelser m.m. Bel&oslash;b angives med komma som decimaltegn.<br>',
			   'afhentningsdage' => $this->Report->pickupdates($division),
			   'division' =>$division,
			   'pickupday' => $pickupday,
			   'pickupdayexpl' => $this->Report->get_pu_date($pickupday),
			   'fields' => $fields,
			   'data' => $data,
			   'weektotals' => $this->_weektotals($division, $this->Report->get_pu_date($pickupday)),
          );

		$this->load->view('v_rapportering', $data);
	}
	
	function statistik()
	{

		$this->db->select('name,  uid');
		$this->db->from('divisions');
		$this->db->where('type', 'aktiv'); 
		$this->db->order_by('name'); 
		$query = $this->db->get();
		$divisions = $query->num_rows();
		
		foreach ($query->result_array() as $row)
		{
			$data[$row['uid']]['name'] = $row['name'];	
			$data[$row['uid']]['count'] = $this->_getno('active',$row['uid']);	
			$data[$row['uid']]['M'] = $this->_getno('active',$row['uid'],'M');	
			$data[$row['uid']]['F'] = $this->_getno('active',$row['uid'],'F');	
			$data[$row['uid']]['active'] = $this->_getno('active',$row['uid'],'', 'yes');	
			$data[$row['uid']]['lastmonth'] = $this->_getno('active',$row['uid'],'', '', 'yes');	
			$data[$row['uid']]['privacy'] = $this->_getno('active',$row['uid'],'', '', '','yes');	
			$data[$row['uid']]['email'] = $this->_getno('active',$row['uid'],'', '', '','','yes');	
			$data[$row['uid']]['kollektiver'] = $this->_getkollektiver($row['uid']);	
			$data[$row['uid']]['nets'] = $this->_getsale('nets',$row['uid']);	
			$data[$row['uid']]['kontant'] = $this->_getsale('kontant',$row['uid']);	
			$data[$row['uid']]['medlemssystem'] = $this->_getsale('',$row['uid']);	
			$data[$row['uid']]['purchaselastmonth'] = $this->_getsale('',$row['uid'],'yes');	
		}
		$this->_savehistory($data); 
		
		$data = array(
               'title' => 'KBHFF Statistik',
               'heading' => 'KBHFF Statistik',
			   'content' => 'N&oslash;gletal for de forskellige afdelinger i KBHFF<br>',
			   'divisions' => $divisions,
			   'data' => $data,
          );

		$this->load->view('v_statistik', $data);
	}

	private function _savehistory($ary)
	{
			$this->db->query('replace ff_statistics_log (ary, date) values ("' . mysql_real_escape_string(serialize($ary)) . '", curdate())');
	}
	
	private function _getno($type,$division, $sex = '', $activated = '', $lastlogin = '', $privacy = '', $email = '')
	{
			$this->db->select('uid');
			$this->db->from('persons');
			$this->db->from('division_members ');
			$this->db->where('active', 'yes'); 
			if ($sex > '')
			{
				$this->db->where('sex', $sex); 
			}
			if ($activated > '')
			{
				$this->db->where('password > ""');
			}
			if ($lastlogin > '')
			{
				$this->db->where('last_login > DATE_SUB(curdate(), INTERVAL 31 DAY)');
			}
			if ($privacy > '')
			{
				$this->db->where('privacy', 'y'); 
			}
			if ($email > '')
			{
				$this->db->where('email > ""');
			}
		
			$this->db->where('division', $division); 
			$this->db->where('member', 'uid', false); 
			$query = $this->db->get();
			$row = $query->row();
// echo ('<!--' . $this->db->last_query() . '-->');
			return $query->num_rows();
	}

	private function _getkollektiver($division)
	{
			$this->db->select('puid');
			$this->db->from('persons_info');
			$this->db->from('persons');
			$this->db->from('division_members');
			$this->db->where('active', 'yes'); 
			$this->db->where('uid', 'puid', false); 
			$this->db->where('member', 'puid', false); 
			$this->db->where('kollektiv > ""');
			$this->db->where('division', $division); 
			$query = $this->db->get();
			$row = $query->row();
 // echo ("\n".'<!--' . $this->db->last_query() . '-->');
			return $query->num_rows();
	}

	private function _getsale($type,$division, $month = '')
	{
			$this->db->select('orderlines.puid');
			$this->db->from('orderhead');
			$this->db->from('orderlines');
			$this->db->from('division_members');
			$this->db->where('member', 'ff_orderlines.puid', false); 
			$this->db->where('division', $division); 
			if ($month > '')
			{
				$this->db->where('orderhead.created > DATE_SUB(curdate(), INTERVAL 31 DAY)');
			}
			$this->db->where('ff_orderhead.orderno', 'ff_orderlines.orderno', false); 
			$this->db->distinct();
			if ($type == 'nets')
			{
				$this->db->where('ff_orderhead.status1', 'nets'); 
			}
			if ($type == 'kontant')
			{
				$this->db->where('ff_orderhead.status1', 'kontant'); 
			}
			if ($type == '')
			{
				$where = "(ff_orderhead.status1='kontant' OR ff_orderhead.status1='nets')";
				$this->db->where($where);
			}
			$query = $this->db->get();
			$row = $query->row();
  echo ("\n".'<!--' . $this->db->last_query() . '-->');
			return $query->num_rows();
	}

	private function _weektotals($division, $date)
	{
	
	$query = $this->db->query('SELECT
week(ff_orderlines.created,3) as weekno,
ff_orderhead.status1,
count(*) as Antal,
sum(ff_orderlines.amount) as Total 
FROM 
	(ff_orderlines, 
	ff_orderhead, 
	ff_items, 
	ff_producttypes, 
	ff_pickupdates
	)
WHERE 
ff_orderlines.orderno = ff_orderhead.orderno 
AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id 	
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_pickupdates.division = ff_items.division
and ff_pickupdates.division = ' . (int)$division . ' 
group by weekno,ff_orderhead.status1
having weekno = week("' . $date . '",3)');
		$weektotals = $query->result_array();
		
		return $weektotals;
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
	
	
	
} // class Rapportering 

/* End of file rapportering.php */
/* Location: ./application/controllers/rapportering.php */