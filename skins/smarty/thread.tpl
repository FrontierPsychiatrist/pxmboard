{function name="message"}
<tr class="bg2"><td valign="middle"><table cellspacing="0" cellpadding="0" border="0">
<tr><td>{$msg._img}</td>
<td id="norm">
	<span class="{if $config.user.id > 0 && $msg.user.id == $config.user.id}own{/if}">
	<a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$msg.id}{$config.sid}" target="bottom" name="p{$msg.id}">{$msg.subject}</a> von
	<span class="{if $msg.user.highlight == 1}highlight{/if}">
	{$msg.user.nickname}
	</span>
	am {$msg.date} Uhr
	{if $config.logedin == 1 && $msg.new == 1} (neu){/if}
	</span>
</td></tr></table></td></tr>
	{foreach from=$msg.msg item=msgpart}
		{call name="message" msg=$msgpart}
	{/foreach}
{/function}
<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"/>
   	<meta http-equiv="Pragma" content="no-cache"/>
   	<meta http-equiv="expires" content="0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: thread =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	{if $config.admin == 1 || $config.moderator == 1}
	<script type="text/javascript" src="js/admin.js"></script>
	{/if}
</head>
<body>
{if $thread.msg[0]}
<table cellspacing="0" cellpadding="0" border="0" width="900">
	<tr class="bg1"><td valign="middle"><table cellspacing="2" cellpadding="5" border="0" width="100%"><tr><td id="norm">
	<span class="{if $config.user.id > 0 && $thread.msg[0].user.id == $config.user.id}own{/if}">
	<a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$thread.msg[0].id}{$config.sid}" target="bottom" name="p{$thread.msg[0].id}">{$thread.msg[0].subject}</a> von
	<span class="{if $thread.msg[0].user.highlight == 1}highlight{/if}">
	{$thread.msg[0].user.nickname}
	</span>
	am {$thread.msg[0].date} Uhr
	</span>
	</td>
	<td align="right"><a href="pxmboard.php?mode=messagelist&brdid={$config.board.id}&thrdid={$thread.id}{$config.sid}" target="flatview" onclick="window.open(this,'flatview','width=800,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">flatview</a></td>
	{if $config.admin == 1 || $config.moderator == 1}
		<form><td align="right">
		<select onchange="adminaction(this.value,{$config.board.id},{$thread.id})">
			<option value="">bitte Option w&auml;hlen</option>
			<option value="">------------------------</option>
			<option value="threadstatus">{if $thread.active == 1}schliessen{else}&ouml;ffnen{/if}</option>
			<option value="fixthread">{if $thread.fixed == 1}l&ouml;sen{else}fixieren{/if}</option>
			<option value="movethread">verschieben</option>
			<option value="deletethread">l&ouml;schen</option>
		</select>
		</td></form>
	{/if}
	</tr></table></td></tr>
	{foreach from=$thread.msg[0].msg item=msgpart}
		{call name="message" msg=$msgpart}
	{/foreach}
</table>
{/if}
</body>
</html>