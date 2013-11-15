<?php

class Report extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	
	function getdata($pickupday,$division, $type)
	{
		$this->db->select('field');
		$this->db->select('data');
		$this->db->select('note');
		$this->db->from('report_data');
		$this->db->from('pickupdates');
		$this->db->where('report_data.date',$pickupday); 
		$this->db->where('pickupdates.uid',$pickupday); 
		$this->db->where('pickupdates.division',$division); 
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$ret = $query->result_array();
		}
		if ($type = 'kassemester')
		{
		/*
		Kasse-difference, start 6 ->5
		Observeret kasse-difference, slut 31 ->9
		Kontant indmeldte nye medlemmer 7 ->6
		Dagens kontantordrer, grøntsagsposer 33->11
		Dagens kontantordrer, frugtposer 34->12
		*/
			// $ret[5]['data'] = 
			// $ret[9]['data'] = 
			$ret[6]['data'] = $this->_dagens_kontantsalg($this->get_pu_date($pickupday), $division, FF_MEMBERSHIP);
			$ret[6]['field'] = 7;
			$ret[11]['data'] = $this->_dagens_kontantsalg($this->get_pu_date($pickupday), $division, FF_GROCERYBAG);
			$ret[11]['field'] = 33;
			$ret[12]['data'] = $this->_dagens_kontantsalg($this->get_pu_date($pickupday), $division, FF_FRUITBAG);
			$ret[12]['field'] = 34;
		}
		return $ret;
	}

	function _dagens_kontantsalg($date,$division, $item)
	{
		$kontant = '';
			$query = $this->db->query('SELECT sum( ff_orderlines.amount ) AS amountsum
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND ff_orderhead.status1 = "kontant"
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ' . (int)$item . ' 
AND ff_items.producttype_id = ff_producttypes.id
AND ff_items.division = ' . (int)$division . ' 
AND ff_orderhead.created like "' . $date . '%"
GROUP BY year(ff_orderlines.created),month(ff_orderlines.created),day(ff_orderlines.created)
ORDER BY ff_orderlines.created');
			if ($query->num_rows() > 0)
			{
			$row = $query->row();
			return $row->amountsum;
			}
	}

	function save_form_data($pickupday, $type)
	{
		$this->db->select('name');
		$this->db->select('comment');
		$this->db->select('sort');
		$this->db->select('uid');
		$this->db->select('editable');
		$this->db->select('noterequired');
		$this->db->from('reportfields');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$tmp ='f' . $row->uid;
				$tmp2 = $this->input->post($tmp);
				$ntmp ='note' . $row->uid;
				$ntmp2 = $this->input->post($ntmp);
				if (isset($tmp2));
				{
					$this->_update_form_field($row->uid,$pickupday,$tmp2,$ntmp2);
				}
			}
		}
	// special functions
	if ($type == 'kassemester')
	{
		$open_diff = $this->input->post('f2') - $this->input->post('f1');
		$this->_update_form_field(6,$pickupday,$open_diff,$this->input->post('note6'));
		$end_diff = 
			(float)$this->input->post('f3') 
			- (
			(float)$this->input->post('f2') 
			+(float)$this->input->post('f4') 
			+(float)$this->input->post('f5')
			+(float)$this->input->post('f7')
			+(float)$this->input->post('f9')
			-(float)$this->input->post('f8')
			-(float)$this->input->post('f32')
			+(float)$this->input->post('f33')
			+(float)$this->input->post('f34')
			);
		$this->_update_form_field(31,$pickupday,$end_diff,$this->input->post('note31'));
	}

	}
	function _update_form_field($field,$pickupday,$data, $note)
	{
	//	$number = floatval(str_replace(',', '.', str_replace('.', '', $data)));
		$val = str_replace(',','.',$data);
		$query = $this->db->query('replace into `ff_report_data` set `data` = ' . $val . ', `creator` = ' . (int)$this->session->userdata('uid') . ', note = "'.addslashes($note).'", `changed` = now(), `date` = ' . (int)$pickupday . ', `field` = ' . (int)$field);
	}

	function pickupdates($division)
	{
		$this->db->select("division, pickupdate, date_format(`ff_itemdays`.`lastorder`,'%d/%m %k:%i') as lastorder, uid, date_format(`j2`.`lastorder`,'%d/%m %k:%i') as flastorder", FALSE);
		$this->db->from('pickupdates');
		$this->db->from('itemdays');
		$this->db->join('itemdays as j2', 'j2.item = ' . FF_FRUITBAG . ' AND j2.pickupday = ff_itemdays.pickupday', 'left');
		$this->db->where('ff_pickupdates.uid', 'ff_itemdays.pickupday', FALSE); 
		$this->db->where('itemdays.item', FF_GROCERYBAG); 
		$this->db->where('division', (int)$division); 
		$this->db->where('pickupdate <= curdate()', NULL, FALSE); 
		$this->db->order_by('pickupdate','desc'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	function get_pu_date($pickupday)
	{
			$this->db->select('pickupdate');
			$this->db->from('pickupdates');
			$this->db->where('uid', (int)$pickupday); 
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$row = $query->row();
				return $row->pickupdate;
			}
	}
	
	
} // model Report 
