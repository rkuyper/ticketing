<?php
require_once('./includes/ChibiEngine.php');
require_once('./includes/eventfunctions.php');

if (!check_admin('events')) die;

$sql = new chibisql;
$smarty = new chibismarty;

$mode = $_REQUEST['mode'];

if ($mode == 'submit' || $mode == 'edit')
	$event= load_event($_REQUEST['id'],$sql);

if ($mode == 'submit')
{	
	$values = load_fields($event['amfields']);
	$stmt = $sql->prepare('UPDATE eventregs SET `values`=? WHERE ticketnumber=? AND ticketid=?');
	$stmt->bind_param('sii',$values,$_POST['ticketnumber'],$_POST['ticketid']);
	$stmt->execute();
	$stmt->close();
	
	$mode='edit';
}
		

if ($mode == 'delete')
{
	$stmt = $sql->prepare('DELETE FROM eventregs WHERE ticketnumber=? AND ticketid=? AND eventid=?');
	$stmt->bind_param('iii',$_GET['ticketnumber'],$_GET['ticketid'],$_GET['id']);
	$stmt->execute();
	$stmt->close();
	
	schuif_reserve_door($sql);
	
	$mode='';
}

if ($mode == 'edit')
{
	if ($_REQUEST['ticketid'] == 1)
	{
		$stmt = $sql->prepare('SELECT firstname,prefixes,surname,emailaddress FROM tickets WHERE ticketnumber=?');
		$stmt->bind_param('i',$_REQUEST['ticketnumber']);
	}
	else
	{
		$stmt = $sql->prepare("SELECT firstname,prefixes,surname,emailaddress FROM ticketsextra,
		(SELECT emailaddress FROM tickets WHERE ticketnumber=?)table1
		 WHERE ticketnumber=? AND id=?");
		$stmt->bind_param('iii',$_REQUEST['ticketnumber'],$_REQUEST['ticketnumber'],$_REQUEST['ticketid']);
	}
	$stmt->execute();
	$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$user['emailaddress']);
	$stmt->fetch();
	$stmt->close();
	
	$stmt=$sql->prepare('SELECT `values` FROM eventregs WHERE ticketid=? AND ticketnumber=? AND eventid=?');
	$stmt->bind_param('iii',$_REQUEST['ticketid'],$_REQUEST['ticketnumber'],$_REQUEST['id']);
	$stmt->execute();
	$stmt->bind_result($values);
	$stmt->fetch();
	$stmt->close();
	
	$values = explode('|',$values);
	
	
	$registratie = array('values' => $values,
		'name' => fullname($user),
		'emailaddress' => $user['emailaddress'],
		'ticketnumber' => $_REQUEST['ticketnumber'],
		'ticketid' => $_REQUEST['ticketid']);
		
	$smarty->assign('registratie', $registratie);
	$smarty->assign('event',$event);
	$smarty->assign('mode','admin');
	$smarty->display('eventform.tpl');
	
	
}

else
{
	$stmt = $sql->prepare("SELECT firstname,prefixes,surname,table1.ticketnumber,table1.id,eventid,name,reserve FROM
	(SELECT firstname,prefixes,surname,ticketnumber,'1' AS id FROM tickets UNION SELECT firstname,prefixes,surname,ticketnumber,id FROM ticketsextra)table1,
	eventregs,events WHERE eventregs.ticketnumber=table1.ticketnumber AND ticketid=table1.id AND events.id=eventid ORDER BY name,reserve ASC,table1.ticketnumber,table1.id");
	$stmt->execute();
	$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$user['ticketnumber'],$user['ticketid'],$eventid,$eventname,$reserve);
	
	while ($stmt->fetch())
	{
		$regs[] = array('name' => fullname($user),
			'ticketnumber' => $user['ticketnumber'],
			'ticketid' => $user['ticketid'],
			'eventid' => $eventid,
			'eventname' => $eventname,
			'reserve' => $reserve);
	}
		
	$stmt->close();
	
	$smarty->assign('regs',$regs);
	$smarty->display('eventregadmin.tpl');
}


$sql->close();
?>