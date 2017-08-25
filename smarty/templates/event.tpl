{include file='header.tpl' title=$event.name}
{$event.pagecontents|parbr}

{if $mode == 'notstarted'}
	<p><b>De registratie voor deze activiteit is nog niet geopend. Zodra de registratie begint, zal dit op onze website aangekondigd worden.</b></p>
{elseif $mode == 'vol'}
	<p><b>De deelnemerslimiet voor deze activiteit is bereikt. Registratie is daarom niet meer mogelijk.</b></p>
{elseif $mode == 'reggelukt'}
	<p>Je hebt je succesvol geregistreerd voor {$event.name|escape}. Als er problemen zijn of we andere informatie van jou nodig hebben, zullen we contact met je opnemen.</p>
{elseif $mode == 'invalid'}
	<p>Het opgegeven registratienummer is incorrect of het e-mailadres komt niet overeen met degene die bij de registratie gebruikt is. Ga terug en verbeter dit.</p>
{elseif $mode == 'inactief'}
	<p>Deze registratie is niet op tijd betaald en staat daarom op inactief. Om je te kunnen registreren voor een activiteit moet je eerst voor je kaartje betalen.</p>
{elseif $mode == 'reedsgereg'}
	<p>Je hebt je al geregistreerd voor deze activiteit. Er is per persoon maar 1 registratie mogelijk.</p>
{else}
	<br />
	<br />
	<h1>Registratieformulier</h1>
	{if $mode == 'reserve'}
		<p>
			<b>Deze activiteit zit vol, maar er is nog plek op de reservelijst. Via onderstaand formulier kun je jezelf op de reservelijst laten zetten. Als je mee kunt doen ontvang je vanzelf bericht van ons.</b>
		</p>
	{/if}
	<p>
		Met behulp van het onderstaande formulier kun je jezelf registreren voor deze activiteit. Hiervoor heb je jouw unieke registratienummer en het daaraan gekoppelde e-mailadres nodig. Registratienummer vergeten? Neem dan <a href='./contact.php?recipient=5'>contact</a> met ons op.
	</p>
	{include file='eventform.tpl' event=$event mode='normal'} 
	{/if}

{include file='footer.tpl' pageid=$event.id}