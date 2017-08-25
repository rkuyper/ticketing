<?php
require_once('./includes/ChibiEngine.php');

function update_banned_ip($sql,$ip,$bancount)
{
	if ($bancount < 5)
	{
		if ($bancount > 0)
		{
			$stmt = $sql->prepare("UPDATE banned_ip SET count = ? + 1 WHERE ip=INET_ATON(?)");
			$stmt->bind_param('is',$bancount,$ip);
		}
		else
		{
			$stmt = $sql->prepare("INSERT INTO banned_ip VALUES(INET_ATON(?),1)");
			$stmt->bind_param('s',$ip);
		}
		$stmt->execute();
		$stmt->close();
	}
}

$ticketnumber=@$_GET['n'];
$hash=@$_GET['h'];

$sql = new chibisql;
$smarty = new chibismarty;

$ip = $_SERVER['REMOTE_ADDR'];

$stmt = $sql->prepare("SELECT count FROM banned_ip WHERE ip=INET_ATON(?)");
$stmt->bind_param('s',$ip);
$stmt->execute();
$stmt->bind_result($bancount);
if (!$stmt->fetch())
	$bancount = 0;
$stmt->close();

$stmt = $sql->prepare("SELECT firstname,prefixes,surname,state,'1' AS id FROM tickets WHERE ticketnumber=? UNION SELECT firstname,prefixes,surname,'',id FROM ticketsextra WHERE ticketnumber=? ORDER BY id");
$stmt->bind_param('ii',$ticketnumber,$ticketnumber);
$stmt->execute();
$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$state,$id);
if ($stmt->fetch() && $bancount < 5 && sha1($ticketnumber*3*7*4*2) == $hash)
{
	if ($state == 'Betaald')
	{
		$name[0]=fullname($user);
		while ($stmt->fetch())
			$name[]=fullname($user);

		$smarty->assign('name',$name);
		$smarty->assign('ticketnumber',$ticketnumber);
		$smarty->assign('mode','valid');
		$stmt->close();
	}
	else
	{
		$stmt->close();
		update_banned_ip($sql,$ip,$bancount);
		$smarty->assign('mode','invalid');
	}
}
else
{
	$stmt->close();
	update_banned_ip($sql,$ip,$bancount);
	$smarty->assign('mode','invalid');
}

$sql->close();

$smarty->display('ticket.tpl');
?>