<?php
require_once('./includes/ChibiEngine.php');

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
{			
	$pagecontents = "PRODUCTION ENVIRONMENT!";
}
else
{
	require_once("/usr/home/deb8401/domains/chibicon.nl/public_html/forum/SSI.php");
	$artikelen = ssi_boardNews(35,null,null,1000,'array');
}

//Verwijder de target="_blank"
str_replace('target="_blank"','',$artikelen);

$smarty = new chibismarty;
$smarty->assign('artikelen',$artikelen);
$smarty->display('animereviews.tpl');
?>