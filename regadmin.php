<?php
$mode = $_REQUEST['mode'];

if ($mode == 'genmails') $textmode = true;
require_once('./includes/ChibiEngine.php');

if (!check_admin('registrations')) die;

$sql = new chibisql;
$smarty = new chibismarty;

if ($mode == 'genmails')
{	
	$ticketnumber = $_GET['ticketnumber'];
	
	$stmt = $sql->prepare('SELECT firstname,prefixes,surname,regdate,aantaltickets,state FROM (SELECT count(*)+1 AS aantaltickets FROM ticketsextra WHERE ticketnumber=?)table1,tickets WHERE ticketnumber=?');
	$stmt->bind_param('ii',$ticketnumber,$ticketnumber);
	$stmt->execute();
	$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$regdate,$count,$state);
	$stmt->fetch();
	$stmt->close();
		
	if ($state != 'Inactief')
		$ticketprijs=ticketprice($regdate);
	else
		$ticketprijs = ticketprice();
	$totaalprijs=$ticketprijs * $count;
	$extra=array('ticketnumber' => $_GET['ticketnumber'],
		'aantaltickets' => $count,
		'ticketprijs' => $ticketprijs,
		'totaalprijs' => $totaalprijs,
		'hash' => sha1($_GET['ticketnumber']*3*7*4*2));
	$name = fullname($user);
	
	$smarty->assign('name', $name);
	$smarty->assign('extra',$extra);
	$smarty->display('registratiemail.tpl');
	echo "\n\n\n";
	$smarty->display('bevestigingsmail.tpl');
	echo "\n\n\n";
	$smarty->display('mailbetaald.tpl');
}

elseif ($mode == 'pay')
{
	$ticketnumber = $_GET['ticketnumber'];
	$date = $_GET['date'];
	
	dopayments(array(array('ticketnumber' => $ticketnumber,'date' => $date)),$sql);
	
	$mode='search';
}

elseif ($mode == 'edited')
{
	if ($_POST['id'] == 1)
	{
		$stmt = $sql->prepare('UPDATE tickets SET firstname=?,prefixes=?,surname=?,address=?,postalcode=?,city=?,country=?,dob=?,emailaddress=?,gender=? WHERE ticketnumber=?');
		$stmt->bind_param('ssssssssssi',$_POST['firstname'],$_POST['prefixes'],$_POST['surname'],$_POST['address'],$_POST['postalcode'],$_POST['city'],$_POST['country'],$_POST['dob'],$_POST['emailaddress'],$_POST['gender'],$_POST['ticketnumber']);
	}
	else
	{
		$stmt = $sql->prepare('UPDATE ticketsextra SET firstname=?,prefixes=?,surname=?,dob=?,gender=? WHERE ticketnumber=? AND id=?');
		$stmt->bind_param('sssssii',$_POST['firstname'],$_POST['prefixes'],$_POST['surname'],$_POST['dob'],$_POST['gender'],$_POST['ticketnumber'],$_POST['id']);
	}
	$stmt->execute();
	$stmt->close();
	
	$mode='search';
}


elseif ($mode == 'delete')
{
	$ticketnumber = $_GET['ticketnumber'];
	$id = $_GET['id'];
	
	if ($id == 1)
		doinactief(array($ticketnumber),$sql,true);		
	
	else
	{
		$stmt = $sql->prepare('SELECT count(*) FROM eventregs WHERE ticketnumber=? AND ticketid=?');
		$stmt->bind_param('ii',$ticketnumber,$id);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		if ($count != 0)
		{
			$body = "Registratienummer $ticketnumber heeft zich met volgnummer $id geregistreerd voor één of meerdere activiteiten maar heeft dit volgnummer laten annuleren. Alle registraties voor activiteiten met dit registratienummer SAMEN MET DIT VOLGNUMMER zijn geannuleerd.";
			mb_send_mail('events@chibicon.nl', 'Registratie(s) verwijderd', $body, "From: Chibicon <events@chibicon.nl>" );
		}
		
		$stmt = $sql->prepare('DELETE FROM ticketsextra WHERE ticketnumber=? AND id=?');
		$stmt->bind_param('ii',$ticketnumber,$id);
		$stmt->execute();
		$stmt->close();
		
		$stmt = $sql->prepare('DELETE FROM eventregs WHERE ticketnumber=? AND ticketid=?');
		$stmt->bind_param('ii',$ticketnumber,$id);
		$stmt->execute();
		$stmt->close();
	}
	

}

if ($mode == 'search')
{
	$searchparam = array(
		'ticketnumber' => $_REQUEST['ticketnumber'],
		'regdate' => $_POST['regdate'],
		'state' => $_POST['state'],
		'firstname' => $_POST['firstname'],
		'prefixes' => $_POST['prefixes'],
		'surname' => $_POST['surname'],
		'address' => $_POST['address'],
		'postalcode' => $_POST['postalcode'],
		'city' => $_POST['city'],
		'country' => $_POST['country'],
		'dob' => $_POST['dob'],
		'emailaddress' => $_POST['emailaddress'],
		'gender' => $_POST['gender']);
		$searchparam = preg_replace('/^$/','%',$searchparam);
		  				
	$stmt = $sql->prepare("SELECT tickets.ticketnumber,'1' AS id,firstname,prefixes,surname,address,postalcode,city,country,dob,gender,emailaddress,regdate,state,paydate,aantaltickets FROM 
	tickets,
	(SELECT ticketnumber,count(*) AS aantaltickets FROM
		(SELECT ticketnumber FROM tickets UNION ALL SELECT ticketnumber FROM ticketsextra)table1
	GROUP BY ticketnumber)table2
	
	WHERE tickets.ticketnumber=table2.ticketnumber AND tickets.ticketnumber LIKE ? AND regdate LIKE ? AND state LIKE ? AND firstname LIKE ? AND prefixes LIKE ? AND surname LIKE ? AND address LIKE ? AND postalcode LIKE ? AND city LIKE ? AND country LIKE ? AND dob LIKE ? AND emailaddress LIKE ? AND gender LIKE ?
	
	UNION SELECT ticketnumber,id,firstname,prefixes,surname,'','','','',dob,gender,'','','','','' FROM ticketsextra WHERE ticketnumber LIKE ? AND firstname LIKE ? AND prefixes LIKE ? AND surname LIKE ? AND dob LIKE ? AND gender LIKE ? AND ticketnumber IN (SELECT ticketnumber FROM tickets WHERE regdate LIKE ? AND state LIKE ? AND address LIKE ? AND postalcode LIKE ? AND city LIKE ? AND country LIKE ? AND emailaddress LIKE ?)
	
	ORDER BY ticketnumber,id");
	
	$stmt->bind_param('ssssssssssssssssssssssssss',$searchparam['ticketnumber'],$searchparam['regdate'],$searchparam['state'],$searchparam['firstname'],$searchparam['prefixes'],$searchparam['surname'],$searchparam['address'],$searchparam['postalcode'],$searchparam['city'],$searchparam['country'],$searchparam['dob'],$searchparam['emailaddress'],$searchparam['gender'],$searchparam['ticketnumber'],$searchparam['firstname'],$searchparam['prefixes'],$searchparam['surname'],$searchparam['dob'],$searchparam['gender'],$searchparam['regdate'],$searchparam['state'],$searchparam['address'],$searchparam['postalcode'],$searchparam['city'],$searchparam['country'],$searchparam['emailaddress']);
	$stmt->execute();
	$stmt->bind_result($ticketnumber,$id,$firstname,$prefixes,$surname,$address,$postalcode,$city,$country,$dob,$gender,$emailaddress,$regdate,$state,$paydate,$aantaltickets);
	
	while($stmt->fetch())
	{		
		if ($state != 'Inactief')
			$totaalprijs=ticketprice($regdate,$aantaltickets);
		else
			$totaalprijs = ticketprice('',$aantaltickets);
			
		$results[]=array('ticketnumber' => $ticketnumber,
			'firstname' => $firstname,
			'prefixes' => $prefixes,
			'surname' => $surname,
			'address' => $address,
			'postalcode' => $postalcode,
			'city' => $city,
			'country' => $country,
			'dob' => $dob,
			'gender' => $gender,
			'emailaddress' => $emailaddress,
			'regdate' => $regdate,
			'state' => $state,
			'paydate' => $paydate,
			'aantaltickets' => $aantaltickets,
			'totaalprijs' => $totaalprijs,
			'id' => $id);
	}
	

	$stmt->close();
	
	$smarty->assign('results',$results);
	$smarty->assign('mode','search');
}


$sql->close();
if ($mode != 'genmails') $smarty->display('regadmin.tpl');
?>