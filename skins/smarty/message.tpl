<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
	{if ($config.admin == 1 or $config.mod == 1) and $msg.replyto.id>0}
	<script type="text/javascript" src="js/admin.js"></script>
	{/if}
</head>
<body>
{if $msg}
<table border="0" cellspacing="2" cellpadding="5" width="900">
<tr class="bg1">
	<td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b>
	{if $msg.user.id > 0}
	<a href="pxmboard.php?mode=userprofile&usrid={$msg.user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$msg.user.nickname}</a>
	{else}
	{$msg.user.nickname}
	{/if}
	</b>
	{if $msg.user.email != ""}
	&nbsp;(<a href="mailto:{$msg.user.email}">{$msg.user.email}</a>)
	{/if}
	am {$msg.date} Uhr</td>
	{if $config.admin == 1 or $config.moderator == 1}<form>
		<td align="right">
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
{if $msg.replyto.id>0}
	<td id="norm">Thema: <b>{$msg.subject}</b></td>
	<td id="norm">Antwort auf: <a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$msg.replyto.id}{$config.sid}"><b>{$msg.replyto.subject}</b></a> von <b>{$msg.replyto.user.nickname}</b></td>
{else}
	<td id="norm" colspan="2">Thema: <b>{$msg.subject}</b></td>
{/if}
</tr>
<tr class="bg2">
	<td colspan="2" id="norm">{$msg._body}
	{if $config.usesignatures>0}
	<br>{$msg.user._signature}
	{/if}
	</td>
</tr>
<tr class="bg1">
<td colspan="2" align="center" id="norm">&lt;
<script type="text/javascript">
<!--
  	if(parent.frames.length < 3)
{literal}{{/literal}
		document.write("<a href=\"pxmboard.php?mode=board&brdid={$config.board.id}&thrdid={$msg.thread.id}&msgid={$msg.id}\{$config.sid}">Frameset laden</a> | ");
{literal}}{/literal}
//-->
</script>
{if $config.logedin == 1}
	 <a href="pxmboard.php?mode=privatemessageform&brdid={$config.board.id}&msgid={$msg.id}&toid={$msg.user.id}{$config.sid}" target="_blank" onclick="window.open(this,'myboard','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">private nachricht schreiben</a> |
{if $msg.user.id == $config.user.id or $config.admin == 1 or $config.moderator == 1}
	<a href="pxmboard.php?mode=messagenotification&amp;brdid={$config.board.id}&amp;msgid={$msg.id}{$config.sid}">mailbenachrichtigung
	{if $msg.notification == 1} deaktivieren
	{else} aktivieren
	{/if}
	</a> |
{/if}
{if $config.admin == 1 || $config.moderator == 1 || $config.edit == 1}
	 <a href="pxmboard.php?mode=messageeditform&brdid={$config.board.id}&msgid={$msg.id}{$config.sid}">nachricht editieren</a> |
{/if}
{/if}
 <a href="pxmboard.php?mode=messageform&brdid={$config.board.id}&msgid={$msg.id}{$config.sid}">auf diese nachricht antworten</a> &gt;</td></tr>
</table>
{/if}
</body>
</html>