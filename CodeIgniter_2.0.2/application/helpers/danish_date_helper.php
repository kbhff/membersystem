<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function danish_date_format($unix_timestamp, $show_time = FALSE)
{
 if ($show_time)
  $dateformat = "%d/%m-%Y kl. %H:%i";
 else
  $dateformat = "%d/%m-%Y";
 
 return mdate($dateformat, $unix_timestamp);
}
?>