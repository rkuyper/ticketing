<?php
session_start();
require_once('./includes/ChibiEngine.php');
if (!check_admin('registrations')) die;

$mode =$_REQUEST['mode'];
$smarty = new chibismarty;
$matchresult = array();

if ($mode == 'submit')
{
	preg_match_all('/((.(?!EUR))*?(\d{2})-(\d{2})-(\d{4})(.(?!EUR))*?\D(\d{8})\D(.(?!EUR))*?(\d{1,3}),(\d{2}) EUR|.*? EUR)/s',$_POST['transacties'],$matches,PREG_SET_ORDER);


	$sql = new chibisql;

	$stmt = $sql->prepare("SELECT regdate,state,aantaltickets FROM tickets,
		(SELECT count(*) AS aantaltickets FROM
			(SELECT 1 FROM tickets UNION SELECT id FROM ticketsextra WHERE ticketnumber=?)table1
		)table2
		WHERE ticketnumber=?");

	foreach ($matches AS $i => $match)
	{	
		$matchresults[$i] = array('valid' => false);
		if ($match[7])
		{
			$stmt->bind_param('ii',$match[7],$match[7]);
			$stmt->execute();
			$stmt->bind_result($regdate,$regstate,$aantaltickets);
			if ($stmt->fetch())
			{
				if ($regstate == 'Bevestigd' || $regstate == 'Gewaarschuwd' || $regstate == 'Inactief')
				{
					$paydate = $match[5] . '-' . $match[4] . '-' . $match[3];
					if ($regstate != 'Inactief')
						$totaalprijs=ticketprice($regdate,$aantaltickets);
					else
						$totaalprijs = ticketprice($paydate,$aantaltickets);
					$amount = $match[9]+0.01*$match[10];
					if ($totaalprijs == $amount)
					{
						$matchresults[$i] = array('ticketnumber' => $match[7],
							'amount' => $amount,
							'date' => $paydate,
							'valid' => true);
							
						$foundticketnumber[$i] = $match[7];
						$state[] = 'p';
					}
					else
						$state[] = 'u';
				}
				else
					$state[] = 'r';
			}
			else
				$state[] = 'i';
		}
	
		else $state[] = 'n';
	
		$transactions[] = $match[0];		
	}
	
	$_SESSION['matchresults'] = $matchresults;
	$stmt->close();
	$sql->close();
	
	$smarty->assign('state',$state);
	$smarty->assign('foundticketnumber',$foundticketnumber);
	$smarty->assign('transactions',$transactions);
}

if ($mode == 'dopay')
{
	$sql = new chibisql;
	$matchresults = $_SESSION['matchresults'];
	
	$topay = array();
	
	foreach ($matchresults as $thismatch)
	{
		if ($thismatch['valid'])
			$topay[] = $thismatch;
	}
	
	dopayments($topay,$sql);
	$matchresults = array_reverse($matchresults);
	
	$smarty->assign('matchresults',$matchresults);

	$_SESSION['matchresults'] = '';
}
	$smarty->assign('mode',$mode);
	$smarty->display('import.tpl');
?>