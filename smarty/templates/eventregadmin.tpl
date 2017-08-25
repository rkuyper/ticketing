{include file='header.tpl' title='Eventregistratie Admin' mode='admin'}
<p>
	<a href='eventregadmin.php'>Main</a>
</p>
<table>
{assign var='lasteventid' value=-10}
{assign var='lastreserve' value=0}
{section name='sregs' loop=$regs}
	{if $regs[sregs].eventid != $lasteventid}
		{assign var='lasteventid' value=$regs[sregs].eventid}
		{assign var='lastreserve' value=0}
		<tr><td> </td><td> </td><td> </td></tr>
		<tr><td><b>{$regs[sregs].eventname|escape}</b></td><td> </td><td> </td></tr>
	{/if}
	{if $regs[sregs].reserve != $lastreserve}
		{assign var='lastreserve' value=$regs[sregs].reserve}
		<tr><td><b>Reserve</b></td><td> </td><td> </td></tr>
	{/if}
		
	<tr><td>{$regs[sregs].name|escape}</td><td><a href='eventregadmin.php?id={$regs[sregs].eventid}&amp;ticketnumber={$regs[sregs].ticketnumber}&amp;ticketid={$regs[sregs].ticketid}&amp;mode=edit'>Bekijk</a></td><td><a href="javascript:deletebox('eventregadmin.php?id={$regs[sregs].eventid}&amp;ticketnumber={$regs[sregs].ticketnumber}&amp;ticketid={$regs[sregs].ticketid}&amp;mode=delete','de registratie van {$regs[sregs].name|escape} voor {$regs[sregs].eventname|escape}');">Verwijder</td></tr>
{/section}
</table>

{include file='footer.tpl' mode='admin'}