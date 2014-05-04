<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getMenu($siteURL, $permissions, $uid)
{
$extra_menu = '';
$admin = $info = $butik = $indkob = $kasse = $finance = false;

if (is_array($permissions))
{
	while (list($division, $values) = each($permissions)) { 
		while (list($role, $level) = each($values)) { 
			if (is_numeric($level))
			{
				if ($role == 'Administrator') {
					$admin = true;
				}
				if ($role == 'Kassemester') {
					$kasse = true;
				}
				if ($role == 'Info + lukkevagt') {
					$info = true;
				}
			} 
	
			if ($level == 'Y')
			{
				if ($role == 'Butiksgruppe') {
					$butik = true;
				}
				if ($role == utf8_encode('Fælles indkøbsgruppe')) {
					$indkob = true;
				}
				if ($role == utf8_encode('Fælles økonomigruppe')) {
					$finance = true;
				}
			}
		}
	}
}
/*
if ($butik)
{
$extra_menu .= '<li class="extra">
    <a href="'.$siteURL.'butik">Butik</a> 
   </li>';
}
*/

if ($admin)
{
$extra_menu .= '<li class="extra"><a href="'.$siteURL.'admin">Admin</a></li>';
}

if ($indkob)
{
$extra_menu .= '<li class="extra"><a href="'.$siteURL.'indkob">Indk&oslash;b</a></li>';
}

if ($finance)
{
$extra_menu .= '<li class="extra"><a href="'.$siteURL.'finance">&Oslash;konomi</a></li>';
}

if ($info)
{
$extra_menu .= '<li class="extra"><a href="'.$siteURL.'infovagt">Infovagt</a></li>';
}

if ($kasse)
{
$extra_menu .= '<li class="extra"><a href="'.$siteURL.'kassemester">Kassemester</a></li>';
}


 return '
 <div id="menu">
  <ul>
   <li><a href="'.$siteURL.'logud">Log ud</a></li><li><a href="'.$siteURL.'minside">Min side</a></li><li><a href="'.$siteURL.'minside/mine_ordrer/">Mine ordrer</a></li><li><a href="'.$siteURL.'kontaktinfo/uid/'.$uid.'/">Min kontaktinfo</a></li><li><a href="http://kbhff.wikispaces.com/Vagtplan" target="_blank">Mine vagter</a></li>   
  </ul>
  <ul>
     '.$extra_menu.'
  </ul>
 </div>
';
}
?>