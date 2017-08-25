<?php
require_once('../includes/ChibiEngine.php');

$sql = new chibisql;

$stmt = $sql->prepare("SELECT tickets.ticketnumber,firstname,prefixes,surname,emailaddress,regdate,aantaltickets FROM 
	tickets,
	(SELECT ticketnumber,count(*) AS aantaltickets FROM
		(SELECT ticketnumber FROM tickets UNION ALL SELECT ticketnumber FROM ticketsextra)table1
	GROUP BY ticketnumber)table2
	
	WHERE tickets.ticketnumber=table2.ticketnumber AND state='Inactief'");
$stmt->execute();
$stmt->bind_result($ticketnumber,$firstname,$prefixes,$surname,$emailaddress,$regdate,$count);

$result = array();

while ($stmt->fetch())
	$result[] = array('firstname' => $firstname,
		'prefixes' => $prefixes,
		'surname' => $surname,
		'emailaddress' => $emailaddress,
		'ticketnumber' => $ticketnumber,
		'regdate' => $regdate,
		'count' => $count);
		
$stmt->close();

foreach ($result AS $user)
{	
	$ticketprijs=ticketprice($user['regdate']);
	$totaalprijs=$ticketprijs * $user['count'];
	$extra=array('ticketnumber' => $user['ticketnumber'],
		'aantaltickets' => $user['count'],
		'ticketprijs' => $ticketprijs,
		'totaalprijs' => $totaalprijs);
	
	chibimail('mail_inactief_prijs_omhoog.tpl',$user,$extra);
	
}

$sql->close();
?>