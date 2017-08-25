<?php
require_once('../includes/ChibiEngine.php');

$sql = new chibisql;

$stmt = $sql->prepare("SELECT tickets.ticketnumber,firstname,prefixes,surname,emailaddress FROM tickets WHERE state='Inactief'");
$stmt->execute();
$stmt->bind_result($ticketnumber,$firstname,$prefixes,$surname,$emailaddress);

$result = array();

while ($stmt->fetch())
	$result[] = array('firstname' => $firstname,
		'prefixes' => $prefixes,
		'surname' => $surname,
		'emailaddress' => $emailaddress,
		'ticketnumber' => $ticketnumber);
		
$stmt->close();

foreach ($result AS $user)
{	
	$extra=array('ticketnumber' => $user['ticketnumber']);
	chibimail('mailinactief.tpl',$user,$extra);
}

$sql->close();
?>