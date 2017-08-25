{include file='header.tpl' title='Contact' mode='contact'}

{if $mode == 'submit'}
	<p>
		{if $mail.lang == 'en'}
			Your message has been sent. We try to respond to all messages as quickly as possible. If you haven't recieved a reply within a few days, please contact us again.
		{else}
			Jouw bericht is succesvol verstuurd. We proberen alle berichten zo snel mogelijk te behandelen. Mocht je na enkele dagen nog geen antwoord hebben gekregen willen we je vragen opnieuw contact met ons op te nemen.
		{/if}
	</p>
{else}

{if $mode == 'invalid'}
<p class='invalid'>
	{if $mail.lang == 'en'}
		Please fill out all fields and enter a valid e-mailaddress to send us a message.
	{else}
		Je dient alle velden correct in te vullen en een geldig e-mailadres op te geven om een bericht te kunnen sturen.
	{/if}
	
</p>
{/if}

<p>
	{if $mail.lang == 'en'}
		You can contact us using the form below.
	{else}
		Heb je een vraag die niet op onze website beantwoord wordt? Met behulp van onderstaand formulier kun je deze aan ons stellen.
	{/if}
	
</p>
<form method='post' action='contact.php'>
<p>
	<input type='hidden' name='mode' value='submit' />
	{if $mail.lang == 'en'}
		<input type='hidden' name='lang' value='en' />
	{/if}
</p>
<p>
	{if $mail.lang == 'en'}Your e-mailaddress{else}Jouw e-mailadres{/if}<br />
	<input type='text' name='emailaddress' value='{$mail.emailaddress}' />
</p>
<p>
	{if $mail.lang == 'en'}Your name{else}Jouw naam{/if}<br />
	<input type='text' name='name' value='{$mail.name}' />
</p>
<p>
	{if $mail.lang == 'en'}Message{else}Bericht{/if}<br />
	<textarea name='body' rows='20' cols='60'>{$mail.body}</textarea>
</p>
<p>
{$recaptcha}
</p>
<p>
	<input type='submit' value='{if $mail.lang == 'en'}Send{else}Verstuur{/if}' />
</p>
</form>
{/if}
{include file='footer.tpl'}