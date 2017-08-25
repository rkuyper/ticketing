<?php
require_once('./includes/ChibiEngine.php');
require_once('./includes/eventfunctions.php');
session_start();

$eventid=$_REQUEST['id'];
$mode=$_POST['mode'];

$sql = new chibisql;

$stmt=$sql->prepare('SELECT count(*) FROM eventregs WHERE eventid=?');
$stmt->bind_param('i',$eventid);
$stmt->execute();
$stmt->bind_result($amount);
$stmt->fetch();
$stmt->close();

$event= load_event($eventid,$sql);

$smarty = new chibismarty;
$smarty->assign('event',$event);

if ($amount < ($event['maxpart'] + $event['maxreserve']) && $mode == 'submit')
{
	if ($_SESSION['values'])
		$ticketnumber = $_SESSION['ticketnumber'];
	else
		$ticketnumber = $_POST['ticketnumber'];
	
	if ($_SESSION['emailaddress'])
		$emailaddress = $_SESSION['emailaddress'];
	else
		$emailaddress = $_POST['emailaddress'];
		
	$ticketid = $_POST['ticketid'];
	if (!$ticketid)
		$ticketid='%';
		
	$stmt = $sql->prepare('SELECT count(*) FROM tickets WHERE ticketnumber=? AND emailaddress=?');
	$stmt->bind_param('is',$ticketnumber,$emailaddress);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	
	$stmt = $sql->prepare('SELECT state FROM tickets WHERE ticketnumber=? AND emailaddress=?');
	$stmt->bind_param('is',$ticketnumber,$emailaddress);
	$stmt->execute();
	$stmt->bind_result($state);
	$stmt->fetch();
	$stmt->close();
	
	if (($count == 1) && ($state != 'Inactief'))
	{
		$stmt = $sql->prepare('SELECT count(*) FROM ticketsextra WHERE ticketnumber=? AND id LIKE ?');
		$stmt->bind_param('is',$ticketnumber,$ticketid);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		
		if ($count == 0 || (($ticketid != '%') && $count == 1))
		{
			if ($count == 0)
				$ticketid=1;
				
			$stmt = $sql->prepare('SELECT count(*) FROM eventregs WHERE eventid=? AND ticketnumber=? AND ticketid=?');
			$stmt->bind_param('iii',$eventid,$ticketnumber,$ticketid);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();
		
			if ($count == 0)
			{
				if ($_SESSION['values'])
					$values = $_SESSION['values'];
				else
					$values = load_fields($event['amfields']);
				
				if ($amount >= $event['maxpart'])
					$reserve = true;
				else
					$reserve = false;
				
				$stmt = $sql->prepare('INSERT INTO eventregs VALUES(?,?,?,?,?)');
				$stmt->bind_param('iiiss',$eventid,$ticketnumber,$ticketid,$values,$reserve);
				$stmt->execute();
				$stmt->close();
				
				if ($ticketid == 1)
				{
					$stmt = $sql->prepare('SELECT firstname,prefixes,surname,emailaddress FROM tickets WHERE ticketnumber=?');
					$stmt->bind_param('i',$ticketnumber);
				}
				else
				{
					$stmt = $sql->prepare('SELECT ticketsextra.firstname,ticketsextra.prefixes,ticketsextra.surname,emailaddress FROM tickets,ticketsextra WHERE ticketsextra.ticketnumber=tickets.ticketnumber AND ticketsextra.ticketnumber=? and id=?');
					$stmt->bind_param('ii',$ticketnumber,$ticketid);
				}
				
				$stmt->execute();
				$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$user['emailaddress']);
				$stmt->fetch();
				$stmt->close();
				
				$extra = array('eventname' => $event['name'],'reserve' => $reserve);
				
				chibimail('eventmail.tpl',$user,$extra,'Chibicon <events@chibicon.nl>');
				
				$body = "Nieuwe registratie\nEvent: " . $event['name'] . "\nTicketnummer:$ticketnumber\nVolgnummer: $ticketid\nNaam: " . fullname($user) . "\nE-mailadres: " . $user['emailaddress'] . "\nReserve: ";
				if ($reserve)
					$body .= 'Ja';
				else
					$body .= 'Nee';
				mb_send_mail('events@chibicon.nl', 'Nieuwe registratie', $body, "From: Chibicon <events@chibicon.nl>" );
				
				session_unset();
				session_destroy();
				
				$smarty->assign('mode','reggelukt');
			}
			else
				$smarty->assign('mode','reedsgereg');
		}
		else
		{
			$stmt = $sql->prepare("SELECT firstname,prefixes,surname,'1' AS id FROM tickets WHERE ticketnumber=? UNION SELECT firstname,prefixes,surname,id FROM ticketsextra WHERE ticketnumber=? ORDER BY id");
			$stmt->bind_param('ii',$ticketnumber,$ticketnumber);
			$stmt->execute();
			$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$id);
			
			while ($stmt->fetch())
				$tickets[] = array('name' => fullname($user),
					'id' => $id);
					
			$stmt->close();
					
			$_SESSION['values']=load_fields($event['amfields']);
			$_SESSION['emailaddress']=$event['emailaddress'];
			$_SESSION['ticketnumber']=$event['ticketnumber'];
					
			$smarty->assign('event',$event);
			$smarty->assign('tickets',$tickets);
			$smarty->display('event_kiesticketid.tpl');
		}
	}
	else
	{
		if ($state == 'Inactief')
			$smarty->assign('mode','inactief');
		else
			$smarty->assign('mode','invalid');
	}
}
elseif ($amount >= $event['maxpart'])
{
	if ($event['maxpart'] == 0) 
		$smarty->assign('mode','notstarted');			
	elseif ($amount >= ($event['maxpart'] + $event['maxreserve']))
		$smarty->assign('mode','vol');
	else
		$smarty->assign('mode','reserve');
}
else
{
	session_unset();
	session_destroy();
}

$sql->close();

$smarty->display('event.tpl');
?>