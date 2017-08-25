<?php
require_once('./includes/ChibiEngine.php');

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
{			
	$pagecontents = "PRODUCTION ENVIRONMENT!";
}
else
{
	require_once("/usr/home/deb8401/domains/chibicon.nl/public_html/forum/SSI.php");
	$nieuws = ssi_boardNews(null,3,null,1000,'array');
}

//Verwijder de target="_blank"
$arraylength = count($nieuws);
for ($i=0;$i < $arraylength;$i++)
	$nieuws[$i]['body'] = str_replace(' target="_blank"','',$nieuws[$i]['body']);

$smarty = new chibismarty;
$smarty->assign('nieuws',$nieuws);
$smarty->display('index.tpl');
?>