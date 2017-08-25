<?php
require_once('./includes/ChibiEngine.php');
require_once('./includes/recaptchalib.php');
$publickey = '6LfxzwsAAAAAACG0F6E6mAerimtN1_JLM-kMR6j_';
$privatekey = '6LfxzwsAAAAAAKoH-qjERyD-hKn-_K5rVnE72BBg';

$mode = $_POST['mode'];

$smarty = new chibismarty;

if ($mode == 'submit')
{
	$name=$_POST['name'];
	$body=$_POST['body'];
	$recipient=$_POST['recipient'];
	$emailaddress=$_POST['emailaddress'];
	$resp = recaptcha_check_answer($privatekey,
		$_SERVER['REMOTE_ADDR'],
		$_POST['recaptcha_challenge_field'],
		$_POST['recaptcha_response_field']);
	if ($name && $body && $recipient && check_email($emailaddress) && $resp->is_valid)
	{
		$sender = $name . ' <' . $emailaddress . '>';
		switch ($recipient)
		{
			//Algemeen
			case 1: $recipientmail='rutger.kuyper@chibicon.nl'; break;
			//Dealers
			case 2: $recipientmail='charrel.hoekzema@chibicon.nl'; break;
			//Events
			case 3: $recipientmail='rianne.bouwman@chibicon.nl'; break;
			//Gophers
			case 4: $recipientmail='raymond.willemsen@chibicon.nl'; break;
			//Kaartverkoop
			case 5: $recipientmail='rutger.kuyper@chibicon.nl'; break;
			//Pers
			//case 6: $recipientmail='kenneth.timan@chibicon.nl'; break;
			case 6: $recipientmail='rutger.kuyper@chibicon.nl'; break;
			//Sponsoring
			case 7: $recipientmail='dale.hullegien@chibicon.nl'; break;
			//Stewards
			case 8: $recipientmail='murat.karaagac@chibicon.nl'; break;
			default: $recipientmail='rutger.kuyper@chibicon.nl'; break;
		}
		
		//Beveiliging
 		
 		contains_bad_str($emailaddress);
		contains_bad_str($name);
		contains_bad_str($body);

		contains_newlines($emailaddress);
		contains_newlines($name);
					
		mb_send_mail($recipientmail, 'Contactformulier', $body, "From: $sender" );
		if ($recipientmail != 'rutger.kuyper@chibicon.nl') 
			mb_send_mail('rutger.kuyper@chibicon.nl', 'Contactformulier' . $recipientmail, $body, "From: $sender" );
		$smarty->assign('mode','submit');
	}
	else
	{
		$smarty->assign('recaptcha', recaptcha_get_html($publickey,$resp->error));
		$mail = array('name' => $name,
			'body' => $body,
			'recipient' => $recipient,
			'emailaddress' => $emailaddress,
			'lang' => $_POST['lang']);
			
		$smarty->assign('mail',$mail);
		$smarty->assign('mode','invalid');
	}
}
else
{
	$smarty->assign('recaptcha', recaptcha_get_html($publickey)); 
	$mail = array('recipient' => $_GET['recipient'],
		'lang' => $_GET['lang']);
		
	$smarty->assign('mail',$mail);
	$smarty->assign('mode','geen');
}
$smarty->display('contact.tpl');
?>