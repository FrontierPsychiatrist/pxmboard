<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: private message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
	<script type="text/javascript">
		function delmsg() {ldelim}
			result = confirm("Soll diese Nachricht geloescht werden?");
  			if(result == true) location.href="pxmboard.php?type={$config.type}&mode=privatemessagedelete&msgid={$msg.id}{$config.sid}";
		{rdelim}
</script>
</head>
<body>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
{if $config.type == 'outbox'}
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=inbox{$config.sid}">inbox</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=outbox{$config.sid}">outbox</a></td>
{else}
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=inbox{$config.sid}">inbox</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=outbox{$config.sid}">outbox</a></td>
{/if}
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b><a href="pxmboard.php?mode=userprofile&usrid={$msg.user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$msg.user.nickname}</a></b>
	{if $msg.user.email != ''}
	&nbsp;(<a href="mailto:{$msg.user.email}">{$msg.user.email}</a>)
	{/if}
	am {$msg.date} Uhr</td></tr></table></td>
</tr>
<tr class="bg2">
	<td id="norm" colspan="2">Thema: <b>{$msg.subject}</b></td>
</tr>
<tr class="bg2">
	<td colspan="2" id="norm">{$msg._body}
	{if $config.usesignatures>0}
	<br>{$msg.user._signature}
	{/if}
	</td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm">&lt; <a href="pxmboard.php?mode=privatemessagelist&type={$config.type}{$config.sid}">zurück</a> | {if $config.type == 'inbox'}<a href="pxmboard.php?mode=privatemessageform&type=outbox&toid={$msg.user.id}&pmsgid={$msg.id}{$config.sid}">auf diese nachricht antworten</a> | {/if}<a href="#" onclick="delmsg(); return false;">nachricht löschen</a> &gt;</td>
</tr>
</table>
</body>
</html>