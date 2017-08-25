{include file='header.tpl' title='Nieuws' mode='index'}
{section name='snieuws' loop=$nieuws}
		<h2><a href='{$nieuws[snieuws].href}'>{$nieuws[snieuws].subject}</a></h2>
		<span class='newstime'>{$nieuws[snieuws].time}</span><br /><br />
		{$nieuws[snieuws].body}
		<br /><span class='commentslink'>{$nieuws[snieuws].link}</span>
{/section}	
{include file='footer.tpl'}