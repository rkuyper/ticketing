<?php
require_once('./includes/ChibiEngine.php');

$mode=$_POST['mode'];

$smarty = new chibismarty;

if ($mode == 'submit')
{
	$ticketnumber = $_POST['ticketnumber'];
	$emailaddress = $_POST['emailaddress'];
	
	$sql = new chibisql;
	
	$stmt = $sql->prepare('SELECT count(*) FROM tickets WHERE ticketnumber=? AND emailaddress=?');
	$stmt->bind_param('is',$ticketnumber,$emailaddress);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	
	if ($count == 1)
	{
		$stmt = $sql->prepare("SELECT firstname,prefixes,surname,state,'1' AS id FROM tickets WHERE ticketnumber=? UNION SELECT firstname,prefixes,surname,'' AS state,id FROM ticketsextra WHERE ticketnumber=? ORDER BY id");
		$stmt->bind_param('ii',$ticketnumber,$ticketnumber);
		$stmt->execute();
		$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$state,$id);
		
		while ($stmt->fetch())
			$tickets[]=array('name' => fullname($user),
				'state' => $state,
				'id' => $id);
				
		$stmt->close();
		
		$stmt = $sql->prepare("SELECT table1.id,eventid,name FROM
	(SELECT '1' AS id UNION SELECT id FROM ticketsextra WHERE ticketnumber=?)table1,
	eventregs,events WHERE ticketnumber=? AND ticketid=table1.id AND events.id=eventid ORDER BY table1.id,name");
		$stmt->bind_param('ii',$ticketnumber,$ticketnumber);
		$stmt->execute();
		$stmt->bind_result($ticketid,$eventid,$eventname);
		
		while($stmt->fetch())
			$events[]=array('ticketid' => $ticketid,
				'eventid' => $eventid,
				'eventname' => $eventname);
			
		$stmt->close();
		
		$smarty->assign('mode','valid');
		$smarty->assign('tickets',$tickets);
		$smarty->assign('events',$events);
		$smarty->assign('ticketnumber',$ticketnumber);
		$smarty->assign('hash',sha1($ticketnumber*3*7*4*2));
		$smarty->assign('aantaltickets',count($tickets));
	}
	else
		$smarty->assign('mode','invalid');
	
	$sql->close();
}
else
	$smarty->assign('mode','geen');

$smarty->display('account.tpl');
?>