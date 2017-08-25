{include file='header.tpl' title='Registratiestatus'}
{if $mode == 'invalid'}
	<p>Het opgegeven e-mailadres is bij ons niet bekend. Weet je niet meer op welk e-mailadres je jezelf hebt geregistreerd, of is je e-mailadres veranderd? Neem dan <a href='./contact.php?recipient=5'>contact</a> met ons op.</p>

{elseif $mode == 'valid'}
	<p>
	De status van jouw registratie(s) is per e-mail naar jou verstuurd.
	</p>
	
	<p>
	Heb je onze e-mail na een half uur nog niet ontvangen? Controleer dan eerst goed of het bericht niet in jouw spam-map beland is. Als je de e-mail echt niet hebt ontvangen kun je het beste <a href='./contact.php?recipient=5'>contact</a> met ons opnemen.
	</p>
	
{else}
<form method='post' action='ticketstatus.php'>
		<p>
		Ben je jouw kaartje kwijt, heb je onze e-mail met jouw kaartje nooit ontvangen of wil je gewoon weten wat de status van jouw registratie is? Vul dan hieronder jouw e-mailadres in, dan ontvang je van ons een e-mail met daarin de status van jouw registratie (plus een link naar jouw kaartje, mocht deze al betaald zijn).
		</p>
		
		<p>
		<input type='hidden' name='mode' value='submit' />
		E-mailadres: <input type='text' name='emailaddress' /><br />
		<input type='submit' value='Verstuur' /></p></form>
{/if}

{include file='footer.tpl'}