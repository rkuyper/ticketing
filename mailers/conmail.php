<?php

require_once('../includes/ChibiEngine.php');

$smarty = new chibismarty;

$sql = new chibisql;
$stmt = $sql->prepare("SELECT firstname,prefixes,surname,emailaddress,ticketnumber FROM tickets WHERE state='Con-ticket'");
$stmt->execute();
$stmt->bind_result($firstname,$prefixes,$surname,$emailaddress,$ticketnumber);

$count = 0;
while ($stmt->fetch())
{
	$extra['ticketnumber']=$ticketnumber;
	$user = array('firstname' => $firstname,
		'prefixes' => $prefixes,
		'surname' => $surname,
		'emailaddress' => $emailaddress);
	chibimail('conmail.tpl',$user,$extra);
	$count++;
}

$stmt->close();
$sql->close();

echo "$count mails verstuurd.";
?>