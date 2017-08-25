<?php

require_once('./includes/ChibiEngine.php');
session_start();

$smarty = new chibismarty;

$mode = @$_REQUEST['mode'];

$sql = new chibisql;
	
$stmt = $sql->prepare("SELECT count(*) FROM (SELECT ticketnumber FROM tickets WHERE NOT state='Inactief' UNION ALL SELECT ticketnumber FROM ticketsextra WHERE ticketnumber IN (SELECT ticketnumber FROM tickets WHERE NOT state='Inactief'))table1");
$stmt->execute();
$stmt->bind_result($regcount);
$stmt->fetch();
$stmt->close();
	    
if ($regcount >= $config['maxtickets']) $smarty->display('uitverkocht.tpl');

//De gebruiker wil gegevens wijzigen
elseif ($mode == 'wijzig')
{
	$user = $_SESSION['user'];
	$smarty->assign('user',$user);
	$smarty->display('registratieformulier.tpl');
}

//Er zijn gegevens ingevoerd via het formulier en deze moeten gecontroleerd.
elseif ($mode == 'submit')
{
	$dob1 = @$_POST['dob1'];
	$dob2 = @$_POST['dob2'];
	if (strlen($dob1) == 1) $dob1 = "0$dob1";
	if (strlen($dob2) == 1) $dob2 = "0$dob2";

	$user=array('firstname' => @$_POST['firstname'],
		'prefixes' => @$_POST['prefixes'],
		'surname' => @$_POST['surname'],
		'address' => @$_POST['address'],
		'postalcode' => str_replace(' ','',@$_POST['postalcode']),
		'city' => @$_POST['city'],
		'country' => @$_POST['country'],
		'dob1' => $dob1,
		'dob2' => $dob2,
		'dob3' => @$_POST['dob3'],
		'gender' => @$_POST['gender'],
		'emailaddress' => @$_POST['emailaddress'],
		'rules' => @$_POST['rules'],
		'amount' => @$_POST['amount'],
		'transport' => @$_POST['transport']);
	
	$smarty->assign('user',$user);
	
	//Alles klopt
	if ($user['firstname'] && $user['surname'] && $user['address'] && $user['postalcode'] && $user['city'] && $user['country'] && ($user['dob1'] > 0) && ($user['dob1'] < 32) && ($user['dob2'] > 0) && ($user['dob2'] < 13) && ($user['dob3'] > 1900) && $user['gender'] && check_email($user['emailaddress']) && $user['rules'] && $user['amount'] && $user['transport'])
	{
		$_SESSION['user']=$user;
		
		if ($user['amount'] > 1)
		{
			$smarty->assign('tickets',$_SESSION['tickets']);
			$smarty->display('extratickets.tpl');
		}
		else
			$smarty->display('bevestiggegevens.tpl');
	}
		
	//Er mist iets, dus laat het formulier zien met de ingevulde gegevens.
	else
	{
		if (!$user['firstname']) $inv['firstname']=true;
		if (!$user['surname']) $inv['surname']=true;
		if (!$user['address']) $inv['address']=true;
		if (!$user['postalcode']) $inv['postalcode']=true;
		if (!$user['city']) $inv['city']=true;
		if (!$user['country']) $inv['country']=true;
		if (!$user['gender']) $inv['gender']=true;
		if (!$user['transport']) $inv['transport']=true;
		if (!check_email($user['emailaddress'])) $inv['emailaddress']=true;
		if (!(($user['dob1'] > 0) && ($user['dob1'] < 32) && ($user['dob2'] > 0) && ($user['dob2'] < 13) && ($user['dob3'] > 1900))) $inv['dob']=true;
		
		$smarty->assign('inv',$inv);
		$smarty->assign('invalid',true);
		$smarty->display('registratieformulier.tpl');
	}
}

//Er zijn extra tickets doorgegeven
elseif ($mode == 'extrasubmit')
{
	$extravalid = true;
	for ($i=2;$i<=$_SESSION['user']['amount'];$i++)
	{
		$dob1 = @$_POST["dob1$i"];
		$dob2 = @$_POST["dob2$i"];
		if (strlen($dob1) == 1) $dob1 = "0$dob1";
		if (strlen($dob2) == 1) $dob2 = "0$dob2";

		$tickets[$i] = array('firstname' => @$_POST["firstname$i"],
			'prefixes' => @$_POST["prefixes$i"],
			'surname' => @$_POST["surname$i"],
			'dob1' => $dob1,
			'dob2' => $dob2,
			'dob3' => @$_POST["dob3$i"],
			'gender' => @$_POST["gender$i"]);
		
		if (!($tickets[$i][firstname] && $tickets[$i][surname] && ($tickets[$i][dob1] > 0) && ($tickets[$i][dob1] < 32) && ($tickets[$i][dob2] > 0) && ($tickets[$i][dob2] < 13) && ($tickets[$i][dob3] > 1900) && $tickets[$i][gender]))
			$extravalid = false;
	}
	
	$smarty->assign('tickets',$tickets);
	$smarty->assign('user',$_SESSION['user']);
	
	//Alles klopt.
	if ($extravalid)
	{
		$_SESSION['tickets']=$tickets;		
		$smarty->display('bevestiggegevens.tpl');
	}
	
	//Er mist iets, dus laat het formulier zien met de ingevulde gegevens.
	else
	{
		for ($i=2;$i<=$_SESSION['user']['amount'];$i++)
		{
			if (!$tickets[$i]['firstname']) $inv[$i]['firstname']=true;
			if (!$tickets[$i]['surname']) $inv[$i]['surname']=true;
			if (!$tickets[$i]['gender']) $inv[$i]['gender']=true;
			if (!(($tickets[$i]['dob1'] > 0) && ($tickets[$i]['dob1'] < 32) && ($tickets[$i]['dob2'] > 0) && ($tickets[$i]['dob2'] < 13) && ($tickets[$i]['dob3'] > 1900))) $inv[$i]['dob']=true;
		}
		
		$smarty->assign('inv',$inv);
		$smarty->assign('invalid',true);
		$smarty->display('extratickets.tpl');
	}
}

//Voer de registratie in (controleer of de sessie niet verlopen is!).
elseif ($mode == 'submit2' && $_SESSION['user']) {
	$user = $_SESSION['user'];
				
	$stmt = $sql->prepare('SELECT count(*) AS count FROM tickets WHERE ticketnumber=?');
	$count=1;
	
	//Bepaal een niet-gebruikt ticketnummer
	while ($count != 0)
	{
		if ($config['eventmode'])
			$ticketnumber=rand(10900000,10999999);
		else
			$ticketnumber=rand(10000000,10899999);
			
		$stmt->bind_param('i',$ticketnumber);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
	}
	$stmt->close();
	
	$dob="$user[dob3]-$user[dob2]-$user[dob1]";
	
	
	if ($config['eventmode'])
		$stmt = $sql->prepare("INSERT INTO tickets VALUES (?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,'','Con-ticket')");
	else
		$stmt = $sql->prepare("INSERT INTO tickets VALUES (?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,'','Onbevestigd')");
		
	$stmt->bind_param('isssssssssss',$ticketnumber,$user['firstname'],$user['prefixes'],$user['surname'],$user['address'],$user['postalcode'],$user['city'],$user['country'],$dob,$user['gender'],$user['emailaddress'],$user['transport']);
	$stmt->execute();
	$stmt->close();
	
	if ($user['amount'] >1)
	{
		$tickets = $_SESSION['tickets'];
		$stmt = $sql->prepare('INSERT INTO ticketsextra VALUES (?,?,?,?,?,?,?)');
		
		for ($i=2;$i<=$user['amount'];$i++)
		{
			//Controleer op skippen van extra ticketstap
			if (!$tickets[$i]) die;
			
			if ($tickets[$i]['gender'] == 'Man')
				$gender = 'm';
			else
				$gender = 'f';
			$dob=$tickets[$i]['dob3']. '-' . $tickets[$i]['dob2'] . '-' . $tickets[$i]['dob1'];
			
			$stmt->bind_param('iisssss',$ticketnumber,$i,$tickets[$i]['firstname'],$tickets[$i]['prefixes'],$tickets[$i]['surname'],$dob,$tickets[$i]['gender']);
			$stmt->execute();
		}
		
		$stmt->close();
	}

	session_unset();
	session_destroy();
	
	/*if ($config['eventmode'])
	{
		$name[0]=fullname($user);
		for ($i=2;$i<=$user['amount'];$i++)
			$name[]=fullname($tickets[$i]);
			
		$smarty->assign('name',$name);
		$smarty->assign('ticketnumber',$ticketnumber);
		$smarty->assign('mode','valid');
		$smarty->display('ticket.tpl');
	}
	else
	{*/
		$extra['ticketnumber']=$ticketnumber;
		chibimail('registratiemail.tpl',$user,$extra);
		$smarty->assign('emailaddress',$user[emailaddress]);
		$smarty->display('registratiegelukt.tpl');
	//}
}

//Toon leeg registratieformulier	
else
{
	session_unset();
	session_destroy();
	$smarty->display('registratieformulier.tpl');
}

$sql->close();
?>