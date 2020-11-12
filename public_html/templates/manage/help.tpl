{include file='popupHeader.tpl'}

<a href="javascript:close();">Close this Window</a>
<p class=subtitle>Help with... <b>{$helpTitle}</b></p>

<p class=normal>
	{if $noBreaks}
		{$content}
	{else}
		{$content|nl2br}
	{/if}
</p>

<p class=normal><a href="javascript:close();">Close this Window</a></p>

{include file='popupFooter.tpl'}