{include file='header.tpl' title='Anime-reviews'}

{section name='sart' loop=$artikelen}
		<h2><a href='{$artikelen[sart].href}'>{$artikelen[sart].subject}</a></h2>
		<span class='newstime'>{$artikelen[sart].time}</span><br /><br />
		{$artikelen[sart].body}
		<br /><span class='commentslink'><a href='{$artikelen[sart].href}'>Lees verder / reageer</a></span>
{/section}	

{include file='footer.tpl' mode='artikelen'}