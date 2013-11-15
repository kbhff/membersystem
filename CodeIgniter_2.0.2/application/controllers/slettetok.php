<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OK extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->helper('menu');
		$this->load->helper('url');
        $this->load->library('javascript');
    }

    function index() 
	{
	
		// Date in the past
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		
		// always modified
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		 
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		
		// HTTP/1.0
		header("Pragma: no-cache");
		$OrderID = $this->input->get_post('OrderID', true);
		$SessionID = $this->input->get_post('SessionID', true);
		$Cardnumber = $this->input->get_post('Cardnumber', true);
		$transact = $this->input->get_post('transact', true);
		$Attempts = $this->input->get_post('Attempts', true);
		$cmdline0 = $this->uri->segment(0);
		$cmdline1 = $this->uri->segment(1);
		$cmdline2 = $this->uri->segment(2);
		$cmdline3 = $this->uri->segment(3);

		echo ("<p>OrderID $OrderID SessionID $SessionID Cardnumber $Cardnumber transact $transact Attempts $Attempts</p>");
		echo ("<p>0: $cmdline0</p>");
		echo ("<p>1: $cmdline1</p>");
		echo ("<p>2: $cmdline2</p>");
		echo ("<p>3: $cmdline3</p>");
		echo ("<h3>Kvittering, ordre $OrderID</h3>\n");

		$OrderID = $this->input->get('OrderID', true);
		$SessionID = $this->input->get('SessionID', true);
		$Cardnumber = $this->input->get('Cardnumber', true);
		$transact = $this->input->get('transact', true);
		$Attempts = $this->input->get('Attempts', true);
		echo ("<p>OrderID $OrderID SessionID $SessionID Cardnumber $Cardnumber transact $transact Attempts $Attempts</p>");
		
		$today = strftime("%Y-%m-%d", time());
		$emailkvittering = kvitgetorderhead($OrderID, $orderkey, $cc_trans_amount, $transact, $Cardnumber);
		$emailkvittering = kvitgetorderlines($OrderID);
		$amount = $sanitytotal;
		$kvittering = htmlentities($emailkvittering);
		$kvittering = nl2br($kvittering);
		
		echo ("$kvittering<br>\n");
		
		
		if (getreceiptstatus($OrderID))
		{
			echo ("Kvittering er sendt til $email.");
		} else {
			echo ("Kvittering sendes til $email.");
			senderrormail("ok.php - kvittering ikke sendt - $OrderID, $today, $transact");
			sendreceipt($emailkvittering);
			// Update status for Order
			updateorderhead($OrderID, $today, $transact);
		}
	}

}

/// NON-CODEIGNITER EXTERNAL FUNCTIONS FOLLOWS

	include("/www/medlem.kbhff.dk/ressources/.mysql_common.php");
	include("/www/medlem.kbhff.dk/ressources/.library.php");
	include("/www/medlem.kbhff.dk/ressources/.kvittering.php");
	include("/www/medlem.kbhff.dk/ressources/.sendmail.php");




?>
