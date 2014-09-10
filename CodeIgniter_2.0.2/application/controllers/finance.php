<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends CI_Controller {

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

		if (! $this->Memberinfo->checkgrouppermission($permissions, utf8_encode('Fælles økonomigruppe')))
		redirect('/minside');		

		$dagenssalg .= '<a href="/finance/dagens_salg/0">Alle' . "</a><br>\n";
		$finansrapport = '';
		$this->db->select('divisions.uid as uid,divisions.name, pickupdates.pickupdate, pickupdates.uid as pickupdateuid');
		$this->db->from('divisions');
		$this->db->from('pickupdates');
		$this->db->where('divisions.uid = ff_pickupdates.division'); 
		$this->db->where('pickupdates.pickupdate >= curdate()'); 
		$this->db->order_by('pickupdates.pickupdate'); 
		$query = $this->db->get();


		$this->db->select('divisions.name, divisions.uid');
		$this->db->from('divisions');
		$this->db->order_by('divisions.name'); 
		$query = $this->db->get();

		foreach ($query->result_array() as $row)
		{
			$dagenssalg .= '<a href="/admin/dagens_salg/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
			$finansrapport .= '<a href="/rapportering/kassemester/' . $row['uid'] . '">' .$row['name'] . "</a><br>\n";
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
               'heading' => 'KBHFF Administrationsside: &Oslash;konomigruppen',
               'dagenssalg' => $dagenssalg,
			   'finansrapport' => $finansrapport,
          );

		$this->load->view('v_finance', $data);
    }

	function dagens_salg($division=5,$dag = 0)
	{
		if ($this->uri->segment(3) > 0)
		{
			$division = $this->uri->segment(3);
		} else {
			$division = $this->input->post('division');
		}

		if ($this->uri->segment(4) > 0)
		{
			$dag = $this->uri->segment(4);
		} else {
			$dag = $this->input->post('dag');
		}

		if ($division > 0)
		{
			$this->db->select("division, pickupdate as pickupdatesort, date_format(`ff_pickupdates`.`pickupdate`,'%d/%m/%Y') as pickupdate, date_format(`ff_itemdays`.`lastorder`,'%d/%m/%Y %k:%i') as lastorder, uid", FALSE);
			$this->db->from('pickupdates');
			$this->db->from('itemdays');
			$this->db->where('ff_pickupdates.uid', 'ff_itemdays.pickupday', FALSE);
			$this->db->where('itemdays.item', FF_GROCERYBAG);
			$this->db->where('division', (int)$division);
			$this->db->where('pickupdate <= curdate()');
			$this->db->order_by('pickupdatesort', 'desc');
			$divisionname = $this->_divisionname((int)$division);
		} else {
			$this->db->select("0 as division, '' as lastorder, pickupdate, uid FROM `ff_pickupdates` where pickupdate <= curdate() group by pickupdate order by pickupdate desc", FALSE);
			$divisionname = 'Alle afdelinger';
		}
		$query = $this->db->get();
		$afhentningsdage = $query->result_array();

		if ($dag == 0)
		{

			$content = $divisionname . ': Dagens salg<br>';
			$data = array(
	               'title' => 'KBHFF Administrationsside',
	               'heading' => $divisionname . ': Dagens salg',
	               'content' => $content,
				   'kontant' => '',
				   'modtagnekontanter' => '',
				   'nets' => '',
				   'ikkeafhentet' => '',
	               'afhentningsdage' => $afhentningsdage,
	          );

			$this->load->view('v_dagens_salg_finans', $data);
		} else {
			$this->db->select('pickupdate');
			$this->db->from('pickupdates');
			$this->db->where('uid', (int)$dag);
			$query = $this->db->get();
			$row = $query->row();
			$dagsdato = $row->pickupdate;
			$modtagnekontanter = $this->_dagens_modtagne_kontanter_total($dagsdato);
			$ikkeafhentet = $this->_ikkeafhentet_total($dagsdato);
			$kontant = $this->_dagens_kontantsalg_total($dagsdato);
			$nets = $this->_dagens_netssalg_total($dagsdato);
			$content = "$divisionname: Dagens salg $dagsdato<br>";

			$data = array(
	               'title' => 'KBHFF Administrationsside',
	               'heading' => "$divisionname: Dagens salg $dagsdato",
	               'content' => $content,
	               'afhentningsdage' => $afhentningsdage,
				   'kontant' => $kontant,
				   'modtagnekontanter' => $modtagnekontanter,
				   'ikkeafhentet' => $ikkeafhentet,
				   'nets' => $nets,
	          );
			$this->load->view('v_dagens_salg_finans', $data);
		}

	}
	
	function ordreliste($divisionday = 0) {
		require_once 'Spreadsheet/Excel/Writer.php';

		if ($this->uri->segment(3) > 0)
		{
			$divisionday = $this->uri->segment(3);
		} else {
			$divisionday = $this->input->post('divisionday');
		}

		// Create a workbook
		$workbook = new Spreadsheet_Excel_Writer();
	
		$this->load->helper('date');
		$now = time();

		$time = unix_to_human($now, FALSE, 'eu'); // Euro time with seconds		
		$division = $this->_division($divisionday);
		$divisionname = $this->_divisionname($division);

		$excelfile = 'Ordreliste_' . $time . '_' . $divisionname . '.xls';

		// sending HTTP headers
		$workbook->send($excelfile);

		$formatbold = $workbook->addFormat();
		$formatbold->setBold(600);
		$workbook->setCustomColor(22, 10, 110, 10);

		$rowformat1 =& $workbook->addFormat(array('Size' => 10,'Color' => '22'));
		$rowformat2 =& $workbook->addFormat(array('Size' => 10,'Color' => 'black'));
									  
		// Creating a worksheet
		$tabname = 'Ordreliste, ' . $division . ' ' . unix_to_human(time(), FALSE, 'eu');
		$worksheet =& $workbook->addWorksheet('Ordreliste, ' . utf8_decode($divisionname) );

		$worksheet->setColumn(1,1,11);	// date
		$worksheet->setColumn(2,2,6);	// date
		$worksheet->setColumn(3,3,22);	// item
		$worksheet->setColumn(8,8,25);	// name
		$worksheet->setColumn(10,10,70);	// email
		
		// Creating a title
	   	$worksheet->write(0, 0, 'Afd.' ,$formatbold);    
	   	$worksheet->write(0, 1, 'Dato' ,$formatbold);    
	   	$worksheet->write(0, 2, 'Vare' ,$formatbold);    
	   	$worksheet->write(0, 3, 'Beskrivelse' ,$formatbold);    
	   	$worksheet->write(0, 4, 'Antal' ,$formatbold);    
	   	$worksheet->write(0, 5, 'Ordre.' ,$formatbold);    
	   	$worksheet->write(0, 6, 'Betaling' ,$formatbold);    
	   	$worksheet->write(0, 7, 'Beløb' ,$formatbold);    
	   	$worksheet->write(0, 8, 'Navn' ,$formatbold);    
	   	$worksheet->write(0, 9, 'Mobil' ,$formatbold);    
	   	$worksheet->write(0, 10, 'Email' ,$formatbold);    
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
		
		$currentrow = 1;
		foreach ($query->result() as $row)
		{
			$dynformat = alternator('rowformat1', 'rowformat2');
			$format = $$dynformat;
			$worksheet->write($currentrow, 0, utf8_decode("$row->name"),$format);
			$worksheet->write($currentrow, 1, utf8_decode("$row->pickupdate"),$format);
			$worksheet->write($currentrow, 2, utf8_decode("$row->article"),$format);
			$worksheet->write($currentrow, 3, utf8_decode("$row->txt"),$format);
			$worksheet->write($currentrow, 4, utf8_decode("$row->quant"),$format);
			$worksheet->write($currentrow, 5, utf8_decode("$row->orderno"),$format);
			$worksheet->write($currentrow, 6, utf8_decode("$row->status1"),$format);
			$worksheet->write($currentrow, 7, utf8_decode("$row->amount"),$format);
			if ($row->middlename > '')
			{
				$name = $row->firstname  . ' ' . $row->middlename . ' ' . $row->lastname ;
			} else {
				$name = $row->firstname  . ' ' . $row->lastname ;
			}
			$worksheet->write($currentrow, 8, utf8_decode("$name"),$format);
			$worksheet->write($currentrow, 9, utf8_decode("$row->tel"),$format);
			$worksheet->write($currentrow, 10, utf8_decode("$row->email"),$format);
			$currentrow++;
		}
		$finalExcelRow = $currentrow;	// rowstart in Excel is 1, so is correct after ++
		$worksheet->write($currentrow, 0, utf8_decode("SUM"),$formatbold);
		$formula = '=SUM(E2:E'.$finalExcelRow.')';
		$worksheet->writeFormula($currentrow,4,$formula);
		$formula = '=SUM(H2:H'.$finalExcelRow.')';
		$worksheet->writeFormula($currentrow,7,$formula);
		

		// Let's send the file
		$workbook->close();
	}

	function medlemordreliste($division = 0) {
$time_start = microtime(true);
apache_setenv('KeepAliveTimeout', 60);
apache_setenv('Timeout', 60);
apache_setenv('no-gzip', 0);
ini_set('zlib.output_compression', 1);

error_reporting(E_ALL);

 ini_set('display_errors','On');
 
ini_set('error_log','/var/log/php.log'); #linux
  set_time_limit(300);
  
		ini_set('max_execution_time', 300); 
/*
  @apache_setenv('no-gzip', 1);
     @ini_set('zlib.output_compression', 0);
     @ini_set('implicit_flush', 1);
     for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
     ob_implicit_flush(1);
*/
 		require_once 'Spreadsheet/Excel/Writer.php';

		if ($this->uri->segment(3) > 0)
		{
			$division = $this->uri->segment(3);
		} else {
			$division = $this->input->post('division');
		}

		// Create a workbook
//		$workbook = new Spreadsheet_Excel_Writer('/www/kbhff.skrig.dk/excel/test.xls');
		$workbook = new Spreadsheet_Excel_Writer();
	
		$this->load->helper('date');
		$now = time();

		$time = unix_to_human($now, FALSE, 'eu'); // Euro time with seconds		
		$divisionname = $this->_divisionname($division);

		$excelfile = 'Medlemmer_ordrer_' . $time . '_' . utf8_decode($divisionname) . '.xls';

		// sending HTTP headers
		$workbook->send($excelfile);

		$formatbold = $workbook->addFormat();
		$formatbold->setBold(600);
		$workbook->setCustomColor(12, 218, 254, 218);
		$format_our_green =& $workbook->addFormat();
		$format_our_green->setFgColor(12);

		
		
		$rowformat1 =& $workbook->addFormat(array('Size' => 10,'Color' => 'black'));
		$rowformat2 =& $workbook->addFormat(array('Size' => 10,'Color' => 'black','FgColor' => '12'));
									  
		// Creating a worksheet
		$tabname = 'Oversigt, ' . $division . ' ' . unix_to_human(time(), FALSE, 'eu');
		$worksheet =& $workbook->addWorksheet('Oversigt, ' . utf8_decode($divisionname) );

		$worksheet->setColumn(0,0,10);	// medlemsnummer
		$worksheet->setColumn(1,1,30);	// navn
		$worksheet->setColumn(2,12,11);	// orderdate
		
		// Creating a title
	   	$worksheet->write(0, 0, 'Medlem' ,$formatbold);    
	   	$worksheet->write(0, 1, 'Navn' ,$formatbold);    

		$query = $this->db->query('SELECT uid, firstname, middlename, lastname 
		FROM ff_persons, ff_division_members
		WHERE ff_persons.uid = ff_division_members.member
		AND ff_division_members.division = ' . (int)$division .
		' ORDER BY ff_persons.firstname');
		
		$currentrow = 1;
		foreach ($query->result() as $row)
		{
			$dynformat = alternator('rowformat1', 'rowformat2');
			$format = $$dynformat;
			$worksheet->write($currentrow, 0, utf8_decode("$row->uid"),$format);
			if ($row->middlename > '')
			{
				$name = $row->firstname  . ' ' . $row->middlename . ' ' . $row->lastname ;
			} else {
				$name = $row->firstname  . ' ' . $row->lastname ;
			}
			// get member orderdetails
			$datequery = $this->db->query('SELECT uid, pickupdate FROM ff_pickupdates WHERE division = ' . (int)$division .
			' and datediff(now(),pickupdate) < 100  ORDER BY pickupdate ');
			$ordercolstart = 2;
			$count = 0;
			foreach ($datequery->result() as $pickupdate)
			{
				if ($currentrow == 1)	// only print coltitles first time
				{
					$worksheet->write(0, $ordercolstart + $count, $pickupdate->pickupdate,$formatbold);
				}
				$order = $this->_get_member_dateorder($row->uid, $pickupdate->uid);
				if ($order > 0)
				{
					$worksheet->write($currentrow, $ordercolstart + $count, $order,$format);
				} else {
					$worksheet->write($currentrow, $ordercolstart + $count, '',$format);
				}
				$count++;
			}
			$worksheet->write($currentrow, 1, utf8_decode("$name"),$format);
			$currentrow++;
		}
		$finalExcelRow = $currentrow;	// rowstart in Excel is 1, so is correct after ++
		$worksheet->write($currentrow, 0, utf8_decode("SUM"),$formatbold);
	
		$tempcol = 3;	
		$tempmaxcol = $ordercolstart + $count;
		while ($tempcol <= $tempmaxcol)
		{ 
			if ($tempcol>26)
			{
				$formula = '=SUM(A' . $this->_numtochars($tempcol) . '2:A'. $this->_numtochars($tempcol) .$finalExcelRow.')';
			} else {
				$formula = '=SUM(' . $this->_numtochars($tempcol) . '2:'. $this->_numtochars($tempcol) .$finalExcelRow.')';
			}
			$worksheet->writeFormula($currentrow,$tempcol-1,$formula);
			$tempcol++;
		}
		
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		$worksheet->write($currentrow+1, 1, $time);
		$x = memory_get_peak_usage(true) ;
		$x2 = memory_get_peak_usage() ;
		$worksheet->write($currentrow+2, 1, $x);
		$worksheet->write($currentrow+3, 1, $x2);

		// Let's send the file
		$workbook->close();
	}

	function _dagens_netssalg_total($day)
	{
		$nets = '';
			$query = $this->db->query('SELECT sum( ff_orderlines.quant ) AS quantsum , sum( ff_orderlines.amount ) AS amountsum , ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained, ff_orderlines.created
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND ff_orderhead.status1 = "nets"
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_divisions.uid = ff_pickupdates.division
AND ff_pickupdates.division = ff_items.division
AND ff_pickupdates.pickupdate = "' . addslashes($day) . '"
GROUP BY ff_orderlines.item
ORDER BY ff_producttypes.explained');
			$row = $query->row();
			if ($query->num_rows() > 0)
			{
				$quantsum = 0;
				$amountsum = 0;
				$nets = '<table>';
				foreach ($query->result() as $row)
				{
					$nets .= '<tr><td>On-line k&oslash;b</td><td width="150">varenummer ' . $row->item .'</td><td align="right">' . $row->quantsum . '</td><td width="200">' . $row->measure . '&nbsp;&nbsp;&nbsp;' . $row->explained . '</td><td align="right">'. $row->amountsum . ' kr.</td></tr>';
					$quantsum += $row->quantsum;
					$amountsum += $row->amountsum;
				}
				$nets .= '<tr><td><strong>Total</strong></td><td width="150">-</td><td align="right"><b>' . $quantsum . '</b></td><td width="200">&nbsp;</td><td align="right"><b>'. number_format($amountsum, 2, ',', '.') . ' kr.</b></td></tr>';
				$nets .= '</table>';
			} else {
				$nets = 'Intet on-linesalg<br>';
			}
		return $nets;
	}

		function _dagens_kontantsalg_total($day)
	{
		$kontant = '';
			$query = $this->db->query('SELECT sum( ff_orderlines.quant ) AS quantsum , sum( ff_orderlines.amount ) AS amountsum , ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained, DATE_FORMAT(ff_orderlines.created,"%d-%m-%Y") as created
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND ff_orderhead.status1 = "kontant"
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_divisions.uid = ff_pickupdates.division
AND ff_pickupdates.division = ff_items.division
AND ff_pickupdates.pickupdate = "' . addslashes($day) . '"
GROUP BY year(ff_orderlines.created),month(ff_orderlines.created),day(ff_orderlines.created),ff_producttypes.explained
ORDER BY ff_orderlines.created,ff_producttypes.explained');

			$row = $query->row();
			if ($query->num_rows() > 0)
			{
				$quantsum = 0;
				$amountsum = 0;
				$kontant = '<table>';
				foreach ($query->result() as $row)
				{
					$kontant .= '<tr><td>' . $row->created . '</td><td width="150">varenummer ' . $row->item .'</td><td align="right">' . $row->quantsum . '</td><td width="200">' . $row->measure . '&nbsp;&nbsp;&nbsp;' . $row->explained . '</td><td align="right">'. $row->amountsum . ' kr.</td></tr>';
					$quantsum += $row->quantsum;
					$amountsum += $row->amountsum;
				}
				$kontant .= '<tr><td><strong>Total</strong></td><td width="150">-</td><td align="right"><b>' . $quantsum . '</b></td><td width="200">&nbsp;</td><td align="right"><b>'. number_format($amountsum, 2, ',', '.') . ' kr.</b></td></tr>';
				$kontant .= '</table>';
			} else {
				$kontant = 'Intet kontantsalg.<br>';
			}
		return $kontant;
	}

	function _dagens_modtagne_kontanter_total($day)
	{
		$kontantermodtaget  = '';
			$query = $this->db->query('SELECT sum( ff_orderlines.quant ) AS quantsum , sum( ff_orderlines.amount ) AS amountsum , ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained, DATE_FORMAT(ff_orderlines.created,"%d-%m-%Y") as created
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND ff_orderhead.status1 = "kontant"
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id ' . 
'AND DATE_FORMAT(ff_orderhead.created,"%Y-%m-%d") = "' . addslashes($day) . '" ' .
'GROUP BY year(ff_orderlines.created),month(ff_orderlines.created),day(ff_orderlines.created),ff_orderlines.item
ORDER BY ff_orderlines.created, ff_orderlines.item');

			$row = $query->row();
			if ($query->num_rows() > 0)
			{
				$quantsum = 0;
				$amountsum = 0;
				$kontantermodtaget = '<table>';
				foreach ($query->result() as $row)
				{
					$kontantermodtaget .= '<tr><td>' . $row->created . '</td><td width="150">varenummer ' . $row->item .'</td><td align="right">' . $row->quantsum . '</td><td width="200">' . $row->measure . '&nbsp;&nbsp;&nbsp;' . $row->explained . '</td><td align="right">'. $row->amountsum . ' kr.</td></tr>';
					$quantsum += $row->quantsum;
					$amountsum += $row->amountsum;
				}
				$kontantermodtaget .= '<tr><td><strong>Total</strong></td><td width="150">-</td><td align="right"><b>' . $quantsum . '</b></td><td width="200">&nbsp;</td><td align="right"><b>'. number_format($amountsum, 2, ',', '.') . ' kr.</b></td></tr>';
				$kontantermodtaget .= '</table>';
			} else {
				$kontantermodtaget = 'Intet kontantsalg.<br>';
			}
		return $kontantermodtaget;
	}

	function _ikkeafhentet_total($date)
	{
		$query = $this->db->query('SELECT
		ff_pickupdates.pickupdate, ff_orderlines.puid, ff_persons.firstname, ff_persons.middlename, ff_persons.lastname,ff_orderlines.orderno, ff_orderlines.quant, ff_orderlines.item, ff_items.units, ff_items.measure, ff_producttypes.explained
FROM ff_orderlines, ff_orderhead, ff_items, ff_producttypes, ff_pickupdates, ff_divisions, ff_persons
WHERE ff_orderlines.orderno = ff_orderhead.orderno
AND ff_orderlines.item = ff_items.id
AND ff_items.producttype_id = ff_producttypes.id
AND ff_orderlines.iteminfo = ff_pickupdates.uid
AND ff_divisions.uid = ff_pickupdates.division
AND ff_pickupdates.division = ff_items.division
		AND ff_orderlines.puid = ff_persons.uid
		AND ((ff_orderhead.status1 = "kontant") or (ff_orderhead.status1 = "nets"))
		AND ff_orderlines.status2 <> "udleveret"
		AND ff_pickupdates.pickupdate = "' . addslashes($date) . '" ');
		$row = $query->row();
		$ikkeafhentet = '';
		if ($query->num_rows() > 0)
		{
			$ikkeafhentet = '<table cellspacing="4">';
			foreach ($query->result() as $row)
			{
				$ikkeafhentet .= '<tr><td>Ordre ' . $row->orderno . '</td><td>' . $row->quant .' '. $row->measure . ' ' . $row->explained . '</td><td>medlem ' . $row->puid . '</td><td>' . $row->firstname . ' ' . $row->middlename . ' ' . $row->lastname . '</td></tr>';
			}
			$ikkeafhentet .= '<table>';
		} else {
			$ikkeafhentet = 'Alt afhentet.<br>';
		}
		return $ikkeafhentet;

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

} // class finance 

/* End of file finance.php */
/* Location: ./application/controllers/finance.php */