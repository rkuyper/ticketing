{include file='header.tpl' title='ChibiCheck'}
<p>
<form action='chibicheck.php' method='post'>
<script type='text/javascript'>
window.onload=function()
{literal}
{
{/literal}
document.getElementById('ticketstring').focus();
{if $displayresult}
document.getElementById('sound1').Play();
{/if}
{literal}
}
{/literal}
</script>

Registratienummer:<input type='text' maxlength='10' size='10' name='ticketstring' id='ticketstring' /><input type='submit' value='Check in' />
</form>
</p>

{if $displayresult == 'success'}
	<p style='color:green'>
	Incheck geslaagd! {$fullname} met registratienummer {$ticketstring} succesvol ingecheckt.
	</p>
{/if}

{if $displayresult == 'duplicate'}
	<p style='color:red'>
	Incheck mislukt! {$fullname} met registratienummer {$ticketstring} is reeds ingecheckt om {$checkintime}.
	</p>
{/if}

{if $displayresult == 'invalid'}
	<p style='color:red'>
	Incheck mislukt! Registratienummer {$ticketstring} is onbekend.
	</p>
{/if}

{if $displayresult == 'invalidformat'}
	<p style='color:red'>
	Incheck mislukt! Het registratienummer is niet in een goed formaat ingevoerd. Controleer de invoer op typfouten.
	</p>
{/if}

{include file='footer.tpl'}