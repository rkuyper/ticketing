<?php
require_once('./includes/ChibiEngine.php');

$mode=$_POST['mode'];

$smarty = new chibismarty;

if ($mode == 'submit')
{
	$emailaddress = $_POST['emailaddress'];
	
	$sql = new chibisql;
	$sql2 = new chibisql;
	
	$stmt = $sql->prepare('SELECT ticketnumber,state,regdate FROM tickets WHERE emailaddress=?');
	$stmt->bind_param('s',$emailaddress);
	$stmt->execute();
	$stmt->bind_result($ticketnumber,$state,$regdate);
	
	$stmt2 = $sql2->prepare('SELECT count(*) FROM ticketsextra WHERE ticketnumber=?');
	
	$anyresult = false;	
	while ($stmt->fetch())
	{
		$anyresult = true;
		
		if ($state != 'Inactief')
			$ticketprijs=ticketprice($regdate);
		else
			$ticketprijs = ticketprice();
			
		$stmt2->bind_param('i',$ticketnumber);
		$stmt2->execute();
		$stmt2->bind_result($aantalextra);
		$stmt2->fetch();
		
		$totaalprijs=$ticketprijs * ($aantalextra + 1);
		
		$tickets[]=array('ticketnumber' => $ticketnumber,
						'state' => $state,
						'totaalprijs' => $totaalprijs,
						'hash' => sha1($ticketnumber*3*7*4*2));
	}
	
	$stmt2->close();
	$stmt->close();
	
	if ($anyresult)
	{
		$stmt = $sql->prepare('SELECT firstname,prefixes,surname FROM tickets WHERE emailaddress=?');
		$stmt->bind_param('s',$emailaddress);
		$stmt->execute();
		$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname']);
		$stmt->fetch();
		$stmt->close();
		
		$user['emailaddress'] = $emailaddress;
		
		chibimail('ticketstatusmail.tpl',$user,$tickets);
		
		$smarty->assign('mode','valid');
	}
	else
		$smarty->assign('mode','invalid');
	
	$sql->close();
	$sql2->close();
}

$smarty->display('ticketstatus.tpl');
?>