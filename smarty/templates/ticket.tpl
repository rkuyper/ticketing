<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='nl'>
<head>
<title>
Chibicon - Toegangsbewijs
</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='description' content='Chibicon is een jaarlijkse conventie rond Japanse popcultuur. Anime, manga, games en cosplay: het is er allemaal!' />
<meta name='keywords' content='chibicon, chibi, con, japan, popcultuur, anime, manga, games, cosplay, conventie, utrecht, galgenwaard' />
<link rel='stylesheet' type='text/css' href='./styles/ticket.css' />
</head>
<body>
{if $mode == 'valid'}
	{section name='tickets' loop=$name}
		{if $smarty.section.tickets.index != 0 && $smarty.section.tickets.index%2 == 0}
			<div class='ticketbreek'>
		{else}
			<div class='ticket'>
		{/if}
		<img class='ticketlogo' src='./images/chibilogo.png' />
		<h1>Chibicon 2010 - Entreebewijs</h1>
		<p>
			<img class='barcode' src='./images/barcode/image.php?code={$ticketnumber}0{$smarty.section.tickets.index+1}' />
		</p>
		<p>
			Naam: {$name[tickets]|escape}<br />
			Registratienummer: {$ticketnumber}-{$smarty.section.tickets.index+1}
		</p>
		<div id='smallprint'>
		<p>
			Dit toegangsbewijs is persoonsgebonden en niet overdraagbaar. Bij aankoop van dit toegangsbewijs bent u reeds akkoord gegaan met onze huisregels, zoals te lezen op onze website.
		</p>
		<p>
			Dit toegangsbewijs is nodig om de conventie binnen te komen. Wij verzoeken u dan ook vriendelijk om dit bewijs geprint met u mee te nemen. Indien u onder geen mogelijkheid toegang heeft tot een printer, kunt u ook met uw registratienummer binnenkomen. Hierdoor zal de toegang echter wel trager verlopen.
		</p>
		</div>
		</div>
	{/section}
{else}
	<p>
		Er is een fout opgetreden.
	</p>
{/if}
</body>
</html>