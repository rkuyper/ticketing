<?php
mb_language('uni');
mb_internal_encoding('UTF-8');
if ($textmode)
	header('Content-Type: text/plain; charset=UTF-8');
elseif ($xmlmode)
	header('Content-Type: text/xml; charset=UTF-8');
else
	header('Content-Type: text/html; charset=UTF-8');
$config['sqlhost'] = 'localhost';

$config['sqluser'] = 'deb8401_eng';
$config['sqlpass'] = 'thekABa57uXuwr';
$config['sqldb'] = 'deb8401_chibiengine';
$config['smartypath'] = '/usr/home/deb8401/smarty/libs/Smarty.class.php';
$config['sitepath'] = '/usr/home/deb8401/domains/chibicon.nl/public_html/';

/*$config['sqluser'] = 'root';
$config['sqlpass'] = 'chibilocal37';
$config['sqldb'] = 'chibiengine';
$config['smartypath'] = '/Users/Rutger/Sites/smarty/libs/Smarty.class.php';
$config['sitepath'] = '/Users/Rutger/Sites/ChibiEngine/';*/

$file = fopen('./includes/maxtickets.txt','r');
$config['maxtickets']=fread($file,4);
fclose($file);
//$config['maxtickets'] = 2000;
$config['ticketprijslaag'] = 15;
$config['ticketprijshoog'] = 17.5;
$config['ticketprijsomslag'] = '2010-04-10 00:00:01';

$config['eventmode'] = false;

require_once($config['smartypath']);



class chibismarty extends Smarty
{
	function chibismarty()
	{
		global $config;
        $this->Smarty();

        $this->template_dir = $config['sitepath'] . 'smarty/templates/';
        $this->compile_dir  = $config['sitepath'] . 'smarty/templates_c/';
        $this->config_dir   = $config['sitepath'] . 'smarty/configs/';
        $this->cache_dir    = $config['sitepath'] . 'smarty/cache/';

        $this->caching = false;
   }
}

class chibisql extends mysqli
{
	function chibisql()
	{
		global $config;
		$this->mysqli($config['sqlhost'], $config['sqluser'], $config['sqlpass'], $config['sqldb']);
		$stmt = $this->prepare("SET NAMES utf8");
		$stmt->execute();
		$stmt->close();
		if (mysqli_connect_errno()) { 
   			echo 'Er is een databasefout opgetreden. Probeert u het later nog eens.'; 
   			die;
		} 
	}
}

function fullname($user)
{
	if ($user['prefixes']) return $user['firstname'] . ' ' . $user['prefixes'] . ' ' . $user['surname'];
	else return $user['firstname'] . ' ' . $user['surname'];
}

function contains_bad_str($str_to_test) {
  $bad_strings = array(
                "content-type:"
                ,"mime-version:"
                ,"multipart/mixed"
				,"Content-Transfer-Encoding:"
                ,"bcc:"
				,"cc:"
				,"to:"
  );
  
  foreach($bad_strings as $bad_string) {
    if(eregi($bad_string, strtolower($str_to_test))) {
      echo "$bad_string found. Suspected injection attempt - mail not being sent.";
      exit;
    }
  }
}

function contains_newlines($str_to_test) {
   if(preg_match("/(%0A|%0D|\\n+|\\r+)/i", $str_to_test) != 0) {
     echo "newline found in $str_to_test. Suspected injection attempt - mail not being sent.";
     exit;
   }
} 

function chibimail($template,$user,$extra='',$sender='Chibicon <tickets@chibicon.nl>')
{
		$smarty = new chibismarty;
		$name = fullname($user);
		$smarty->assign('name', $name);
		$smarty->assign('extra',$extra);
		$body = $smarty->fetch($template);

		// the subject is on the first line, so parse that out
		$lines = explode("\n", $body);
		$subject = trim(array_shift($lines));
		$body = join("\n", $lines);
		
		$sendto = $name . '<' . $user['emailaddress'] . '>';
 
 		//Beveiliging
 		
 		contains_bad_str($user['emailaddress']);
		contains_bad_str($name);
		contains_bad_str($body);

		contains_newlines($user['emailaddress']);
		contains_newlines($name);
		$extraheaders = 'From: ' . $sender . "\nBcc: chibicon.backup@gmail.com";
		mb_send_mail($sendto, $subject, $body, $extraheaders);
}

function check_admin($type='')
{
	session_start();
	if ($type)
		return (($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) && $_SESSION[$type]);
	else return ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']);
}

function check_email($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
 	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) return false;
 	// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
 	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
 		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) return false;
 	}
 	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
 		$domain_array = explode(".", $email_array[1]);
 		if (sizeof($domain_array) < 2) return false; // Not enough parts to domain
		for ($i = 0; $i < sizeof($domain_array); $i++) {
 			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) return false;
 		}
	}
	return true;
}

function ticketprice($regdate='',$aantaltickets=1)
{
	global $config;
	if ($regdate)
		$regdate = strtotime($regdate);
	else
		$regdate=time();
		
	if ($regdate < strtotime($config['ticketprijsomslag']))
		return $aantaltickets * $config['ticketprijslaag'];
	else
		return $aantaltickets * $config['ticketprijshoog'];
}

function dopayments($topay_array,$sql)
{
	$stmt = $sql->prepare("UPDATE tickets SET state='Betaald',paydate=? WHERE ticketnumber=?");

	foreach($topay_array AS $topay)
	{
		$stmt->bind_param('si',$topay['date'],$topay['ticketnumber']);
		$stmt->execute();
	}
	
	$stmt->close();
	
	$stmt = $sql->prepare('SELECT emailaddress,firstname,prefixes,surname FROM tickets WHERE ticketnumber=?');
	
	foreach($topay_array AS $topay)
	{
		$stmt->bind_param('i',$topay['ticketnumber']);
		$stmt->execute();
		$stmt->bind_result($user['emailaddress'],$user['firstname'],$user['prefixes'],$user['surname']);
		$stmt->fetch();
	
		$extra['ticketnumber']=$topay['ticketnumber'];
		$extra['hash']=sha1($topay['ticketnumber']*3*7*4*2);
		chibimail('mailbetaald.tpl',$user,$extra);
	}
	
	$stmt->close();
}

function schuif_reserve_door($sql)
{
	$stmt = $sql->prepare("SELECT name,id,maxpart,aantalpart,aantalreserve FROM events,(SELECT eventid,count(*) AS aantalpart FROM eventregs WHERE reserve=FALSE GROUP BY eventid UNION SELECT id,'0' AS aantalpart FROM events)table1,(SELECT eventid,count(*) AS aantalreserve FROM eventregs WHERE reserve=TRUE GROUP BY eventid UNION SELECT id,'0' AS aantalpart FROM events)table2 WHERE id=table1.eventid AND id=table2.eventid GROUP BY id");
	$stmt->execute();
	$stmt->bind_result($name,$id,$maxpart,$aantalpart,$aantalreserve);
	
	$results = array();
	
	while ($stmt->fetch())
		$results[] = array('name' => $name,'id' => $id, 'maxpart' => $maxpart,'aantalpart' => $aantalpart,'aantalreserve' => $aantalreserve);
	
	$stmt->close();
	
	foreach ($results as $result)
	{
		while ($result['aantalpart'] < $result['maxpart'] && $result['aantalreserve'] > 0)
		{
			$stmt = $sql->prepare('SELECT ticketnumber,ticketid FROM eventregs WHERE eventid=? AND reserve=TRUE LIMIT 1');
			$stmt->bind_param('i',$result['id']);
			$stmt->execute();
			$stmt->bind_result($ticketnumber,$ticketid);
			$stmt->fetch();
			$stmt->close();
			
			$stmt = $sql->prepare('UPDATE eventregs SET reserve=FALSE WHERE ticketnumber=? AND ticketid=? AND eventid=?');
			$stmt->bind_param('iii',$ticketnumber,$ticketid,$result['id']);
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
				
			$extra = array('eventname' => $result['name']);
				
			chibimail('uitreservemail.tpl',$user,$extra,'Chibicon <events@chibicon.nl>');
			
			$body = "Reserve doorgeschoven\nEvent: " . $result['name'] . "\nTicketnummer:$ticketnumber\nVolgnummer: $ticketid\nNaam: " . fullname($user) . "\nE-mailadres: " . $user['emailaddress'];
			mb_send_mail('events@chibicon.nl', 'Reserve doorgeschoven', $body, "From: Chibicon <events@chibicon.nl>" );
			$result['aantalreserve'] -= 1;
			$result['aantalpart'] += 1;
		}
	}
}

function doinactief($todel_array,$sql,$delete=false)
{
	if ($delete)
	{
		$stmt = $sql->prepare("DELETE FROM tickets WHERE ticketnumber=?");
		$stmt2 = $sql->prepare("DELETE FROM ticketsextra WHERE ticketnumber=?"); 
	}
	else
		$stmt = $sql->prepare("UPDATE tickets SET state='Inactief' WHERE ticketnumber=?");

	foreach($todel_array AS $todel)
	{
			$stmt->bind_param('i',$todel);
			$stmt->execute();
			if ($delete)
				{
					$stmt2->bind_param('i',$todel);
					$stmt2->execute();
				}
	}
	
	$stmt->close();
	if ($delete)
		$stmt2->close();
	
	
	$stmt = $sql->prepare('SELECT count(*) FROM eventregs WHERE ticketnumber=?');
	
	foreach($todel_array AS $todel)
	{
		$stmt->bind_param('i',$todel);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		if ($count != 0)
		{
			$body = "Registratienummer $todel heeft zich geregistreerd voor één of meerdere activiteiten maar heeft niet op tijd betaald of heeft zijn registratie geannuleerd, dus zijn registratie(s) is/zijn verwijderd.";
			mb_send_mail('events@chibicon.nl', 'Registratie(s) verwijderd', $body, "From: Chibicon <events@chibicon.nl>" );
		}
	}
	
	$stmt->close();
				
	
	$stmt = $sql->prepare('DELETE FROM eventregs WHERE ticketnumber=?');
		
	foreach($todel_array AS $todel)
	{
		$stmt->bind_param('i',$todel);
		$stmt->execute();
	}
	
	schuif_reserve_door($sql);
	
	$stmt->close();
	
	$stmt = $sql->prepare("SELECT firstname,prefixes,surname,emailaddress,regdate,aantaltickets FROM 
	tickets,
	(SELECT ticketnumber,count(*) AS aantaltickets FROM
		(SELECT ticketnumber FROM tickets UNION ALL SELECT ticketnumber FROM ticketsextra)table1
	GROUP BY ticketnumber)table2
	WHERE tickets.ticketnumber=table2.ticketnumber AND tickets.ticketnumber=?");
	
	if (!$delete)
	{
		foreach($todel_array AS $todel)
		{
			$stmt->bind_param('i',$todel);
			$stmt->execute();
			$stmt->bind_result($user['firstname'],$user['prefixes'],$user['surname'],$user['emailaddress'],$regdate,$count);
			$stmt->fetch();
		
			$ticketprijs=ticketprice();
			$totaalprijs=$ticketprijs * $count;
			$extra=array('ticketnumber' => $todel,
				'aantaltickets' => $count,
				'ticketprijs' => $ticketprijs,
				'totaalprijs' => $totaalprijs);
			
			chibimail('mailinactief.tpl',$user,$extra);
		}
	}
	
	$stmt->close();
}
	
?>