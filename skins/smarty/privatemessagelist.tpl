<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: private message index =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
<script type="text/javascript">
  function delmsg()
{literal}  {{/literal}
  	result = confirm("Sollen alle Nachrichten geloescht werden?");
  	if(result == true) location.href="pxmboard.php?type={$config.type}&mode=privatemessagedelete&msgid=-1{$config.sid}";
{literal}  }{/literal}
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
	<th id="header">thema</th><th id="header">{if $config.type == 'outbox'}empf&auml;nger{else}autor{/if}</th><th id="header">datum</th>
</tr>
{foreach $msg as $_msg}
<tr class="bg2">
<td id="norm"><a href="pxmboard.php?mode=privatemessage&type={$config.type}&msgid={$_msg.id}{$config.sid}">{$_msg.subject}</a></td><td id="norm">
<span class="{if $_msg.user.highlight == 1}highlight{/if}">
{$_msg.user.nickname}
</span>
</td><td align="right" id="norm">{$_msg.date}</td>
</tr>
{/foreach}
<tr class="bg1">
<td align="center" colspan="3" id="norm">
{if $config.previd != ''}
	<a href="pxmboard.php?mode=privatemessagelist&type={$config.type}&page={$config.previd}{$config.sid}">prev</a> |
{else}
	- |
{/if}
{if $msg}<a href="#" onclick="delmsg(); return false;">alle nachrichten löschen</a> |{/if}
{if $config.nextid != ''}
	<a href="pxmboard.php?mode=privatemessagelist&type={$config.type}&page={$config.nextid}{$config.sid}">next</a>
{else}
	-
{/if}
</td>
</tr>
</table>
</body>
</html>