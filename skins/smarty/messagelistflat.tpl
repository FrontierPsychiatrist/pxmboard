<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
	{if $config.admin == 1 or $config.mod == 1}
	<script type="text/javascript" src="js/admin.js"></script>
	{/if}
</head>
<body>
<table border="0" cellspacing="2" cellpadding="5" width="775">
{foreach from=$msg item=msg}
	<tr class="bg1">
		<td id="norm"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b>
		{if $msg.user.id > 0}
		<a href="pxmboard.php?mode=userprofile&brdid={$config.board.id}&usrid={$msg.user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$msg.user.nickname}</a>
		{else}
		{$msg.user.nickname}
		{/if}
		</b>
		{if $msg.user.email != ""}
		&nbsp;(<a href="mailto:{$msg.user.email}">{$msg.user.email}</a>)
		{/if}
		am {$msg.date} Uhr</td>
		{if $config.admin == 1 or $config.moderator == 1}
		<form><td align="right">
			<select onchange="adminaction(this.value,{$config.board.id},{$msg.id})">
			<option value="">ip: {$msg.ip}</option>
			{if $msg.replyto.id>0}
				<option value="deletemessage">l&ouml;schen</option>
				<option value="deletesubthread">subthread l&ouml;schen</option>
				<option value="extractsubthread">subthread extrahieren</option>
			{/if}
			</select>
		</td></form>
		{/if}
		</tr></table></td>
	</tr>
	<tr class="bg2">
	<td id="norm">Thema: <b>{$msg.subject}</b></td>
	</tr>
	<tr class="bg2">
		<td colspan="2" id="norm">{$msg._body}
		{if $config.usesignatures>0}
		<br>{$msg.user._signature}
		{/if}
		</td>
	</tr>
	<tr class="bg1">
	<td align="center" id="norm">&lt;
	{if $config.logedin == 1}
		 <a href="pxmboard.php?mode=privatemessageform&brdid={$config.board.id}&msgid={$msg.id}&toid={$msg.user.id}{$config.sid}" target="_blank" onclick="window.open(this,'myboard','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">private nachricht schreiben</a> |
	{if $msg.user.id == $config.user.id or $config.admin == 1 or $config.moderator == 1}
		<a href="pxmboard.php?mode=messagenotification&amp;brdid={$config.board.id}&amp;msgid={$msg.id}{$config.sid}">mailbenachrichtigung
		{if $msg.notification == 1} deaktivieren
		{else} aktivieren
		{/if}
		</a> |
	{/if}
	{/if}
	 <a href="pxmboard.php?mode=messageform&brdid={$config.board.id}&msgid={$msg.id}{$config.sid}">auf diese nachricht antworten</a> &gt;</td></tr>
{/foreach}
<tr class="bg1">
	<td align="center" id="norm">
	{if $config.previd != ''}
		<a href="pxmboard.php?mode=messagelist&thrdid={$config.thrdid}&brdid={$config.board.id}&page={$config.previd}{$config.sid}">prev</a> |
	{else}
		- |
	{/if}
	{if $config.count > 0}
		{section name=page start=1 loop=$config.count}
			{if $config.curid == $smarty.section.page.index}
				<u><b>{$smarty.section.page.index}</b></u>
			{else}
				<a href="pxmboard.php?mode=messagelist&thrdid={$config.thrdid}&brdid={$config.board.id}&page={$smarty.section.page.index}{$config.sid}">{$smarty.section.page.index}</a>
			{/if}
		{/section}
		{if $config.curid == $config.count}
			<u><b>{$config.count}</b></u>
		{else}
			<a href="pxmboard.php?mode=messagelist&thrdid={$config.thrdid}&brdid={$config.board.id}&page={$config.count}{$config.sid}">{$config.count}</a>
		{/if}
		 |
	{/if}
	{if $config.nextid != ''}
		<a href="pxmboard.php?mode=messagelist&thrdid={$config.thrdid}&brdid={$config.board.id}&page={$config.nextid}{$config.sid}">next</a>
	{else}
		-
	{/if}
	</td>
</tr>
</table>
</body>
</html>