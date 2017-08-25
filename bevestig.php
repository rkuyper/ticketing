<?php
require_once('./includes/ChibiEngine.php');

$ticketnumber=$_GET['nr'];

$sql = new chibisql;
$smarty = new chibismarty;

$stmt = $sql->prepare('SELECT emailaddress,firstname,prefixes,surname,state FROM tickets WHERE ticketnumber=?');
$stmt->bind_param('i',$ticketnumber);
$stmt->execute();
$stmt->bind_result($user['emailaddress'],$user['firstname'],$user['prefixes'],$user['surname'],$state);

if ($stmt->fetch())
{
	$stmt->close();
	
	if ($state == 'Con-ticket')
	{
		$stmt = $sql->prepare("UPDATE tickets SET state='Betaald' WHERE ticketnumber=?");
		$stmt->bind_param('i',$ticketnumber);
		$stmt->execute();
		$stmt->close();
		
		$extra['ticketnumber']=$ticketnumber;
		$extra['hash']=sha1($ticketnumber*3*7*4*2);
		chibimail('conbevestigingsmail.tpl',$user,$extra);
		$smarty->assign('mode','conbevestigd');
	}
	elseif ($state == 'Onbevestigd')
	{
		$stmt = $sql->prepare("UPDATE tickets SET state='Bevestigd' WHERE ticketnumber=?");
		$stmt->bind_param('i',$ticketnumber);
		$stmt->execute();
		$stmt->close();
		
		$stmt = $sql->prepare('SELECT count(*)+1 FROM ticketsextra WHERE ticketnumber=?');
		$stmt->bind_param('i',$ticketnumber);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		
		$ticketprijs=ticketprice();
		$totaalprijs=$ticketprijs * $count;
		
		$extra=array('ticketnumber' => $ticketnumber,
			'aantaltickets' => $count,
			'ticketprijs' => $ticketprijs,
			'totaalprijs' => $totaalprijs);
		chibimail('bevestigingsmail.tpl',$user,$extra);
		$smarty->assign('mode','bevestigd');
	}
	else
		$smarty->assign('mode','reedsbevestigd');
}
else
{
	$stmt->close();
	$smarty->assign('mode','invalid');
}

$sql->close();

$smarty->display('bevestig.tpl');

?>