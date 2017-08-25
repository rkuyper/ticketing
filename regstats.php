<?php
require_once('./includes/ChibiEngine.php');

if (!check_admin()) die;

$sql = new chibisql;

$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE state='Onbevestigd' UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE state='Onbevestigd'))table1");
$stmt->execute();
$stmt->bind_result($unconfirmed);
$stmt->fetch();
$stmt->close();

$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE state='Bevestigd' OR state='Gewaarschuwd' UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE state='Bevestigd' OR state='Gewaarschuwd'))table1");
$stmt->execute();
$stmt->bind_result($confirmed);
$stmt->fetch();
$stmt->close();

$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE (state='Betaald' OR state='Con-ticket') AND ((regdate<? AND paydate <= (regdate + INTERVAL 31 DAY)) OR paydate<(? - INTERVAL 1 DAY)) UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE (state='Betaald' OR state='Con-ticket') AND ((regdate<? AND paydate <= (regdate + INTERVAL 31 DAY)) OR paydate<(? - INTERVAL 1 DAY))))table1");
$stmt->bind_param('ssss',$config['ticketprijsomslag'],$config['ticketprijsomslag'],$config['ticketprijsomslag'],$config['ticketprijsomslag']);
$stmt->execute();
$stmt->bind_result($paidlow);
$stmt->fetch();
$stmt->close();

$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE (state='Betaald' OR state='Con-ticket') UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE (state='Betaald' OR state='Con-ticket')))table1");
$stmt->execute();
$stmt->bind_result($paidtotal);
$stmt->fetch();
$stmt->close();
$paidhigh = $paidtotal - $paidlow;

$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE state='Ingecheckt' UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE state='Ingecheckt'))table1");
$stmt->execute();
$stmt->bind_result($checkedin);
$stmt->fetch();
$stmt->close();


$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE gender='Man' AND NOT (state='Onbevestigd' OR state='Inactief')  UNION ALL SELECT ticketnumber FROM ticketsextra WHERE gender='Man' AND NOT ticketnumber IN (SELECT ticketnumber FROM tickets WHERE state='Onbevestigd' OR state='Inactief'))table1");
$stmt->execute();
$stmt->bind_result($male);
$stmt->fetch();
$stmt->close();

$total=$confirmed + $paidlow + $paidhigh + $checkedin;
$female=$total-$male;


$smarty = new chibismarty;
$smarty->assign('unconfirmed',$unconfirmed);
$smarty->assign('confirmed',$confirmed);
$smarty->assign('paidlow',$paidlow);
$smarty->assign('paidhigh',$paidhigh);
$smarty->assign('checkedin',$checkedin);
$smarty->assign('male',$male);
$smarty->assign('female',$female);
$smarty->assign('total',$total);
$smarty->display('regstats.tpl');
?>