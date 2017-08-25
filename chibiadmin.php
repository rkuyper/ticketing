<?php
require_once('./includes/ChibiEngine.php');
session_start();

$mode=$_REQUEST['mode'];
$smarty = new chibismarty;

if ($mode == 'submit')
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$sql = new chibisql;
	
	$stmt = $sql->prepare('SELECT username,count(*),website,registrations,events FROM admins WHERE username=? AND password=? GROUP BY username');
	$stmt->bind_param('ss',$username,hash('ripemd160',$password,true));
	$stmt->execute();
	$stmt->bind_result($username,$count,$website,$registrations,$events);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	

	if ($count == 1)
	{
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['website'] = $website;
		$_SESSION['registrations'] = $registrations;
		$_SESSION['events'] = $events;
		$smarty->assign('mode','index');
	}
	else
		$smarty->assign('mode','invalid');
}

elseif ($mode == 'logout')
{
	session_unset();
	session_destroy();
	$smarty->assign('mode','logout');
}

elseif ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR'])
	$smarty->assign('mode','index');
	
else
	$smarty->assign('mode','geen');

$smarty->display('chibiadmin.tpl');
?>