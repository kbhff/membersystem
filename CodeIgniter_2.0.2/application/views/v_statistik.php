<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="da"
    lang="da"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:media="http://search.yahoo.com/mrss/"> 
	<head> 
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>ressources/fc.css" type="text/css" media="screen" />
		<title>KBHFF - statistik</title> 
	</head>
	<body>
<h2>Statistik over afdelinger og medlemmer</h2>	
<p>Taget fra medlemssystemet <?= date("G:s, j/n/Y") ?></p>
<?php
	$classes = Array('even', 'odd');
	$translate = Array('', 'Nej', 'Ja');
	$count = 0;
	//Not pretty, but the source code in the client is pretty :)
	echo ('<pre>');
// print_r($data);
	echo ('</pre>');
	if (isset($data))
	{
		echo '			<table class="posts">'."\n";
		echo '			<tr class="odd">'."\n";
		echo '				<td style="width:35px;"><strong>Afdeling</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Medlemmer</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>M/K<br />beregnet p&aring;<br />fornavn</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Aktiveret<br />login</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Aktiv on-line<br />seneste m&aring;ned</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Oplyst<br />Email</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Ja til<br />nyhedsbreve</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Kollektiver</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Medlemmer<br />k&oslash;bt<br />online</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Medlemmer<br />k&oslash;bt<br />kontant</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>Medlemmer<br />k&oslash;bt<br />ialt</strong></td>'."\n";
		echo '				<td style="width:100px;" align="right"><strong>K&oslash;bt<br />seneste m&aring;ned</strong></td>'."\n";
		echo '			</tr>'."\n";

			$totalcount = 0;
			$totalM  = 0;
			$totalF  = 0;
			$totalactive  = 0;
			$totallastmonth  = 0;
			$totalprivacy  = 0;
			$totalemail  = 0;
			$totalkollektiver  = 0;
			$totalnets  = 0;
			$totalkontant  = 0;
			$totalmedlemssystem  = 0;
			$purchaselastmonth = 0;
		
		
		$counter = 1;			
		while ($counter <= $divisions + 5)
		{
			echo '			<tr class="'.$classes[$count%2].'"'.">\n";
			echo '				<td>'.$data[$counter]['name'].'</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['count'].'</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['M'].'M/' .$data[$counter]['F'].'K<br />'.(int)(100*$data[$counter]['F']/($data[$counter]['F']+$data[$counter]['M'])) .'% K</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['active'].'<br />'.(int)(100*$data[$counter]['active']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['lastmonth'].'<br />'.(int)(100*$data[$counter]['lastmonth']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['email'].'<br />'.(int)(100*$data[$counter]['email']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['privacy'].'<br />'.(int)(100*$data[$counter]['privacy']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['kollektiver'].'<br />'.(int)(100*$data[$counter]['kollektiver']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['nets'].'<br />'.(int)(100*$data[$counter]['nets']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['kontant'].'<br />'.(int)(100*$data[$counter]['kontant']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['medlemssystem'].'<br />'.(int)(100*$data[$counter]['medlemssystem']/$data[$counter]['count']) .'%</td>'."\n";
			echo '				<td align="right">'.$data[$counter]['purchaselastmonth'].'<br />'.(int)(100*$data[$counter]['purchaselastmonth']/$data[$counter]['count']) .'%</td>'."\n";
			echo '			</tr>'."\n";
			$totalcount += $data[$counter]['count'];
			$totalM += $data[$counter]['M'];
			$totalF += $data[$counter]['F'];
			$totalactive += $data[$counter]['active'];
			$totallastmonth += $data[$counter]['lastmonth'];
			$totalprivacy += $data[$counter]['privacy'];
			$totalemail += $data[$counter]['email'];
			$totalkollektiver += $data[$counter]['kollektiver'];
			$totalnets += $data[$counter]['nets'];
			$totalkontant += $data[$counter]['kontant'];
			$totalmedlemssystem += $data[$counter]['medlemssystem'];
			$totalpurchaselastmonth += $data[$counter]['purchaselastmonth'];
			$count++;
			$counter++;
		}
			echo '			<tr class="'.$classes[$count%2].'"'.">\n";
			echo '				<td><strong>KBHFF</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalcount.'</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalM.'M/' .$totalF.'K<br />'.(int)(100*$totalF/($totalF+$totalM)) .'% K</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalactive.'<br />'.(int)(100*$totalactive/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totallastmonth.'<br />'.(int)(100*$totallastmonth/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalemail.'<br />'.(int)(100*$totalemail/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalprivacy.'<br />'.(int)(100*$totalprivacy/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalkollektiver.'<br />'.(int)(100*$totalkollektiver/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalnets.'<br />'.(int)(100*$totalnets/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalkontant.'<br />'.(int)(100*$totalkontant/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalmedlemssystem.'<br />'.(int)(100*$totalmedlemssystem/$totalcount) .'%</strong></td>'."\n";
			echo '				<td align="right"><strong>'.$totalpurchaselastmonth.'<br />'.(int)(100*$totalpurchaselastmonth/$totalcount) .'%</strong></td>'."\n";
		echo '			</table>'."\n";
	}
?>


	</body>
</html>