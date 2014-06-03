<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kassemester extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('javascript');
		$this->load->helper('menu');
		$this->load->helper('url');
		$this->load->model('Permission');
		$this->load->model('Memberinfo');
    }

    function index($divisionday = 0) {
		if (! intval($this->session->userdata('uid')) > 0)
			redirect('/login');		
        $this->jquery->script('/ressources/jquery-1.6.2.min.js', TRUE);
        $this->javascript->compile();

		$permissions = $this->session->userdata('permissions');

		$createsel = '';
		$cashsel = '';
		$medlemordreliste = '';
		$dagenssalg = '';
		$finansrapport = '';
		$nyemedlemmer = '';
		$this->db->select('divisions.uid as uid,divisions.name, pickupdates.pickupdate, pickupdates.uid as pickupdateuid');
		$this->db->from('divisions');
		$this->db->from('pickupdates');
		$this->db->where('divisions.uid = ff_pickupdates.division'); 
		$this->db->where('pickupdates.pickupdate >= curdate()'); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$p_administrator = $this->Memberinfo->checkpermission($permissions, 'Administrator', $row['uid']);
			$p_kassemester   = $this->Memberinfo->checkpermission($permissions, 'Kassemester', $row['uid']);
			$p_infovagt   = $this->Memberinfo->checkpermission($permissions, 'Info + lukkevagt', $row['uid']);

			if (($p_administrator) || ($p_kassemester)|| ($p_infovagt))
			{
				$createsel .= '<option value="' . $row['pickupdateuid'] . '">' . $row['name'] . '-' . $row['pickupdate']. "</option>\n";
			}
		}

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
				$cashsel .= '<a href="/kontantordrer/index/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
				$cashsel .= "<!--\n (($p_administrator) || ($p_kassemester)) \n-->";
				$dagenssalg .= '<a href="/admin/dagens_salg/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
				$medlemordreliste .= 'Excel: alle medlemmer og deres ordrer - <a href="/kassemester/medlemordreliste/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
				$finansrapport .= '<a href="/rapportering/kassemester/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
			}
		}
		
		
		
		
		$divisionday = $this->input->post('divisionday');


		if ($divisionday > 0)
		{

		$query = $this->db->query('SELECT 
		ff_orderlines.item as article, ff_pickupdates.pickupdate as pickupdate, ff_divisions.name as name, ff_items.units, ff_items.measure, ff_producttypes.explained as txt, ff_orderlines.quant,
		ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_persons.uid
		FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons
		WHERE ff_orderlines.orderno = ff_orderhead.orderno 
		AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
		AND ff_orderlines.item = ff_items.id
		AND ff_items.producttype_id = ff_producttypes.id 	
		AND ff_orderlines.iteminfo = ff_pickupdates.uid
		AND ff_divisions.uid = ff_pickupdates.division
		AND ff_pickupdates.division = ff_items.division
		AND ff_orderlines.puid = ff_persons.uid
		AND ff_pickupdates.uid = ' . (int)$divisionday . '
		ORDER BY ff_pickupdates.pickupdate, ff_producttypes.explained
		');
		$orderlist = $query->result_array();

		} else {
			$orderlist = '';
		}


		$data = array(
               'title' => 'KBHFF Administrationsside',
               'heading' => 'KBHFF Administrationsside: Kassemester',
			   'sel' => $createsel,
			   'orderlist' => $orderlist,
               'cashsel' => $cashsel,
               'dagenssalg' => $dagenssalg,
			   'medlemordreliste' => $medlemordreliste,
			   'finansrapport' => $finansrapport,
          );

		$this->load->view('v_kasse', $data);
    }

	
	function ordreliste($divisionday = 0) {
		/** Include PHPExcel */
		require_once 'PHPExcel.php';
//		$cellval = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");
		$this->load->helper('date');

		$locale = 'da';
		date_default_timezone_set('Europe/London');
		$now = Date("H:i d-m-Y");

		if ($this->uri->segment(3) > 0)
		{
			$divisionday = $this->uri->segment(3);
		} else {
			$divisionday = $this->input->post('divisionday');
		}

		$division = $this->_division($divisionday);
		$divisionname = $this->_divisionname($division);

		// Create a workbook
		$objPHPExcel = new PHPExcel();	
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
		$objPHPExcel->getProperties()->setCreator("KBHFF Medlemssystem");
		$objPHPExcel->getProperties()->setLastModifiedBy("KBHFF Medlemssystem $now");
		$objPHPExcel->getProperties()->setTitle( utf8_decode($divisionname) . ' medlemsliste');
		$objPHPExcel->getProperties()->setSubject("Ordreliste");
		$objPHPExcel->getProperties()->setDescription('KBHFF ' . utf8_decode($divisionname) . " ordrer udskrevet $now");
		$objPHPExcel->getProperties()->setKeywords("KBHFF ordreliste");
		$objPHPExcel->getProperties()->setCategory("ordreliste");
		$objPHPExcel->getSheet(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle( substr ( $divisionname . ' ' . Date("H.i d-m-Y"), 0, 31 ));

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->getTabColor()->setRGB('33cc66');


		// Creating a title
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->getStyle('A1:K1')->getFont()->setSize(13)->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
		$objWorksheet->setCellValueByColumnAndRow(0, 1, 'Afd.');
		$objWorksheet->setCellValueByColumnAndRow(1, 1, 'Dato');
		$objWorksheet->setCellValueByColumnAndRow(2, 1, 'Antal');
		$objWorksheet->setCellValueByColumnAndRow(3, 1, 'Varenr.');
		$objWorksheet->setCellValueByColumnAndRow(4, 1, 'Beskrivelse');
		$objWorksheet->setCellValueByColumnAndRow(5, 1, 'Ordre');    
		$objWorksheet->setCellValueByColumnAndRow(6, 1, 'Betaling');    
		$objWorksheet->setCellValueByColumnAndRow(7, 1, 'Beløb');
		$objWorksheet->setCellValueByColumnAndRow(8, 1, 'Navn');    
		$objWorksheet->setCellValueByColumnAndRow(9, 1, 'Mobil');    
		$objWorksheet->setCellValueByColumnAndRow(10, 1, 'Email');    
			
		// Autoset widths
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->getColumnDimension('A')->setAutoSize(true);
		$objWorksheet->getColumnDimension('B')->setAutoSize(true);
		$objWorksheet->getColumnDimension('C')->setAutoSize(true);
		$objWorksheet->getColumnDimension('D')->setAutoSize(true);
		$objWorksheet->getColumnDimension('E')->setAutoSize(true);
		$objWorksheet->getColumnDimension('F')->setAutoSize(true);
		$objWorksheet->getColumnDimension('G')->setAutoSize(true);
		$objWorksheet->getColumnDimension('H')->setAutoSize(true);
		$objWorksheet->getColumnDimension('I')->setAutoSize(true);
		$objWorksheet->getColumnDimension('J')->setAutoSize(true);
		$objWorksheet->getColumnDimension('K')->setAutoSize(true);


		if ($divisionday > 0)
		{
			$select = ' AND ff_pickupdates.uid = ' . (int)$divisionday;
		} else {
			$select = '';
		}
		$query = $this->db->query('SELECT ff_orderlines.item AS article, ff_pickupdates.pickupdate AS pickupdate, ff_divisions.name AS name, ff_items.units, ff_items.measure, ff_producttypes.explained AS txt, ff_orderlines.quant, ff_persons.firstname, ff_persons.middlename, ff_persons.lastname, ff_persons.tel, ff_persons.email, ff_orderhead.orderno, ff_orderhead.status1, ff_orderlines.amount
		FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons
		WHERE ff_orderlines.orderno = ff_orderhead.orderno
		AND (
		(
		ff_orderhead.status1 = "kontant"
		)
		OR (
		ff_orderhead.status1 = "nets"
		)
		)
		AND ff_orderlines.item = ff_items.id
		AND ff_items.producttype_id = ff_producttypes.id
		AND ff_orderlines.iteminfo = ff_pickupdates.uid
		AND ff_pickupdates.division = ff_items.division
		AND ff_divisions.uid = ff_pickupdates.division
		AND ff_orderlines.puid = ff_persons.uid ' . $select .
		' ORDER BY ff_pickupdates.pickupdate, ff_orderhead.orderno');
		
		$rowformat1 = array(
		'font' => array(
			'bold' => false,
			),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' =>  array(
				'rgb' =>  'd9ffe2',
				),
			)
		);

		$rowformat2 = array(
		'font' => array(
			'bold' => false,
			)
		);

		$currentrow = 2;
		foreach ($query->result() as $row)
		{
			if ($row->middlename > '')
			{
				$name = $row->firstname  . ' ' . $row->middlename . ' ' . $row->lastname ;
			} else {
				$name = $row->firstname  . ' ' . $row->lastname ;
			}
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $currentrow, ("$row->name"))
				->setCellValueByColumnAndRow(1, $currentrow, ("$row->pickupdate"))
				->setCellValueByColumnAndRow(2, $currentrow, ("$row->quant"))
				->setCellValueByColumnAndRow(3, $currentrow, ("$row->article"))
				->setCellValueByColumnAndRow(4, $currentrow, ("$row->txt"))
				->setCellValueByColumnAndRow(5, $currentrow, ("$row->orderno"))
				->setCellValueByColumnAndRow(6, $currentrow, ("$row->status1"))
				->setCellValueByColumnAndRow(7, $currentrow, ("$row->amount"))
				->setCellValueByColumnAndRow(8, $currentrow, ($name))
				->setCellValueByColumnAndRow(9, $currentrow, ("$row->tel"))
				->setCellValueByColumnAndRow(10, $currentrow, ("$row->email"));
			$dynformat = alternator('rowformat1', 'rowformat2');
			$format = $$dynformat;
			$objPHPExcel->getActiveSheet()->getStyle('A' . $currentrow .':K' . $currentrow)->applyFromArray($format);
			$currentrow++;
		}

		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		
		// Align
		$objPHPExcel->getActiveSheet()->getStyle('C1:C' . $highestRow)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D1:D' . $highestRow)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('F1:F' . $highestRow)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('H1:H' . $highestRow)
			->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
 
 		// Numberformat
		$objPHPExcel->getActiveSheet()->getStyle('H1:H' . $highestRow)->getNumberFormat()
			->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			
		// Set repeated headers
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

		// Specify printing area
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		$highestColumn = $objWorksheet->getHighestColumn(); 
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . $highestColumn . $highestRow );

		
		// Redirect output to a clients web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="KBHFF ordreliste ' . $divisionname . ' ' . $now .'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	function medlemordreliste($divisionday = 0) {
		$time_start = microtime(true);
		apache_setenv('KeepAliveTimeout', 300);
		apache_setenv('Timeout', 300);
		apache_setenv('no-gzip', 0);
		ini_set('zlib.output_compression', 1);
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		ini_set('error_log','/var/log/php.log'); #linux
		set_time_limit(300);
		ini_set('max_execution_time', 300); 
		
		/** Include PHPExcel */
		require_once 'PHPExcel.php';
//		$cellval = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");
		$this->load->helper('date');

		$locale = 'da';
		date_default_timezone_set('Europe/London');
		$now = Date("H:i d-m-Y");

		if ($this->uri->segment(3) > 0)
		{
			$division = $this->uri->segment(3);
		} else {
			$divisionday = $this->input->post('divisionday');
			$division = $this->_division($divisionday);
		}

		$divisionname = $this->_divisionname($division);

		// Create a workbook
		$objPHPExcel = new PHPExcel();	
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
		$objPHPExcel->getProperties()->setCreator("KBHFF Medlemssystem");
		$objPHPExcel->getProperties()->setLastModifiedBy("KBHFF Medlemssystem $now");
		$objPHPExcel->getProperties()->setTitle( utf8_decode($divisionname) . ' medlemsliste');
		$objPHPExcel->getProperties()->setSubject("Ordreliste");
		$objPHPExcel->getProperties()->setDescription('KBHFF ' . utf8_decode($divisionname) . " ordrer udskrevet $now");
		$objPHPExcel->getProperties()->setKeywords("KBHFF ordreliste");
		$objPHPExcel->getProperties()->setCategory("ordreliste");
		$objPHPExcel->getSheet(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle( substr ( $divisionname . ' ' . Date("H.i d-m-Y"), 0, 31 ));

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->getTabColor()->setRGB('33cc66');

		$query = $this->db->query('SELECT uid, firstname, middlename, lastname 
		FROM ff_persons, ff_division_members
		WHERE ff_persons.uid = ff_division_members.member
		AND ff_division_members.division = ' . (int)$division .
		' ORDER BY ff_persons.firstname');
		
		$rowformat1 = array(
		'font' => array(
			'bold' => false,
			),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' =>  array(
				'rgb' =>  'd9ffe2',
				),
			)
		);

		$rowformat2 = array(
		'font' => array(
			'bold' => false,
			)
		);

		$datequery = $this->db->query('SELECT uid, pickupdate FROM ff_pickupdates WHERE division = ' . (int)$division .
		' and datediff(now(),pickupdate) < 100  ORDER BY pickupdate ');
		$ordercolstart = 2;
		$count = 0;

		foreach ($datequery->result() as $pickupdate)
		{
			// set col names
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($ordercolstart + $count, 1, $pickupdate->pickupdate);
			$count++;
		}

		$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

		$currentrow = 2;
		foreach ($query->result() as $row)
		{
			if ($row->middlename > '')
			{
				$name = $row->firstname  . ' ' . $row->middlename . ' ' . $row->lastname ;
			} else {
				$name = $row->firstname  . ' ' . $row->lastname ;
			}
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $currentrow, ("$row->uid"))
				->setCellValueByColumnAndRow(1, $currentrow, ($name));

			$count = 0;
			$datequery = $this->db->query('SELECT uid, pickupdate FROM ff_pickupdates WHERE division = ' . (int)$division .
			' and datediff(now(),pickupdate) < 100  ORDER BY pickupdate ');

			foreach ($datequery->result() as $pickupdate)
			{
				$order = $this->_get_member_dateorder($row->uid, $pickupdate->uid);
				if ($order > 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($ordercolstart + $count, $currentrow, ($order));
				} else {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($ordercolstart + $count, $currentrow, (''));
				}
				$count++;
			}
				
			$dynformat = alternator('rowformat1', 'rowformat2');
			$format = $$dynformat;
			$objPHPExcel->getActiveSheet()->getStyle('A' . $currentrow .':' . $highestColumm . $currentrow)->applyFromArray($format);
			$currentrow++;
		}

		$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		
		// Creating a title
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->getStyle('A1:' . $highestColumm . '1')->getFont()->setSize(13)->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
		$objWorksheet->setCellValueByColumnAndRow(0, 1, 'Medlem');
		$objWorksheet->setCellValueByColumnAndRow(1, 1, 'Navn');

		// Autoset widths
		foreach(range('A',$highestColumm) as $columnID) {
    		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        		->setAutoSize(true);
		}

		
		// Align
 
 		// Numberformat
			
		// Set repeated headers
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

		// Specify printing area
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		$highestColumn = $objWorksheet->getHighestColumn(); 
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea('A1:' . $highestColumn . $highestRow );

		
		// Redirect output to a clients web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="KBHFF ' . $divisionname . ' alle ordrer ' . $now .'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	

	private function _get_member_dateorder($member, $date)
	{
		$query = $this->db->query('SELECT ff_orderlines.quant 
		FROM ff_orderlines, ff_orderhead, ff_items
		WHERE ff_orderlines.orderno = ff_orderhead.orderno
		AND (
		(
		ff_orderhead.status1 = "kontant"
		)
		OR (
		ff_orderhead.status1 = "nets"
		)
		)
		AND ff_orderlines.item = ff_items.id 
		AND ff_items.producttype_id = ' . FF_GROCERYBAG . '
		AND ff_orderlines.iteminfo = ' . (int)$date . '
		AND ff_orderlines.puid = ' . $member . '');
		$num = 0;
		foreach ($query->result() as $row)
		{
			$num += $row->quant;
		}
		return $num;
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
	
	private function _division($divisionday)
	{
			$this->db->select('division');
			$this->db->from('pickupdates');
			$this->db->where('uid', (int)$divisionday); 
			$query = $this->db->get();
			$row = $query->row();
			return $row->division;
	}
	
	 private function _numtochars($num,$start=65,$end=90)
	 {
	     $sig = ($num < 0);
	     $num = abs($num);
	     $str = "";
	     $cache = ($end-$start);
	     while($num != 0)
	     {
	         $str = chr(($num%$cache)+$start-1).$str;
	         $num = ($num-($num%$cache))/$cache;
	     }
	     if($sig)
	     {
	         $str = "-".$str;
	     }
	     return $str;
	 }

} // class Kassemester 

/* End of file Kassemester.php */
/* Location: ./application/controllers/Kassemester.php */