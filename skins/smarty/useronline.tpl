<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: who's online =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
</head>
<body>
<center>
<table border="0" cellspacing="2" cellpadding="2">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: who's online</td>
</tr>
<tr class="bg2">
	<td colspan="2" align="center" id="header">{$users.all} Benutzer online ({$users.visible} sichtbar - {$users.invisible} versteckt)</td>
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
	<a href="pxmboard.php?mode=useronline&brdid={$config.board.id}&page={$config.previd}{$config.sid}">prev</a>
{else}
	-
{/if}
</td>
<td align="center" id="norm">
{if $config.nextid != ''}
	<a href="pxmboard.php?mode=useronline&brdid={$config.board.id}&page={$config.nextid}{$config.sid}">next</a>
{else}
	-
{/if}
</td>
</tr>
</table>
</center>
</body>
</html>