<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function select_quantity($num, $default = 0)
{
		$i = 0;
		$ret = '';
		while ($i <= $num)
		{
			if ($i == $default)
			{
				$ret .= "<option value=\"$i\" selected>$i</option>\n";
			} else {
				$ret .= "<option value=\"$i\">$i</option>\n";
			}
			$i++;
		}
		return $ret;
}
?>