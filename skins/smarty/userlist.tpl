<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: gefundene user =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
</head>
<body>
<center>
<table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: gefundene user</td>
</tr>
{foreach from=$user item=_user key=key name=userlist}
	{if $smarty.foreach.userlist.last}
		{if $key mod 2}
			<td id="norm"><a href="pxmboard.php?mode=userprofile&brdid={$config.board.id}&usrid={$_user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$_user.nickname}</a></td>
		{else}
			<tr class="bg2">
			<td colspan="2" align="center" id="norm"><a href="pxmboard.php?mode=userprofile&brdid={$config.board.id}&usrid={$_user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$_user.nickname}</a></td>
		{/if}
		</tr>
	{else}
		{if $key mod 2}
			<td id="norm"><a href="pxmboard.php?mode=userprofile&brdid={$config.board.id}&usrid={$_user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$_user.nickname}</a></td>
			</tr>
		{else}
			<tr class="bg2">
			<td id="norm"><a href="pxmboard.php?mode=userprofile&brdid={$config.board.id}&usrid={$_user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$_user.nickname}</a></td>
		{/if}
	{/if}
{/foreach}
<tr class="bg1">
<td align="center" id="norm">
{if $config.previd != ''}
	<a href="pxmboard.php?mode=usersearch&brdid={$config.board.id}&nick={$config.nickname}&page={$config.previd}{$config.sid}">prev</a>
{else}
	-
{/if}
</td>
<td align="center" id="norm">
{if $config.nextid != ''}
	<a href="pxmboard.php?mode=usersearch&brdid={$config.board.id}&nick={$config.nickname}&page={$config.nextid}{$config.sid}">next</a>
{else}
	-
{/if}
</td>
</tr>
</table>
</center>
</body>
</html>