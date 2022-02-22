<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: userprofil =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="3" align="center" id="header">board: userprofil für {$user.nickname}
	{if $user.status != 1} (gesperrt){/if}
	</td>
</tr>
<tr class="bg2">
	<td id="norm">vorname</td><td id="norm">{$user.fname}</td><td rowspan="5" id="norm">
	{if $user.pic == ''}
		<img src="images/empty.gif" width="100" height="150"/>	
	{else}
		<img src="{$config.propicdir}{$user.pic}"/>
	{/if}
</td>
</tr>
<tr class="bg2">
	<td id="norm">nachname</td><td id="norm">{$user.lname}</td>
</tr>
<tr class="bg2">
	<td id="norm">wohnort</td><td id="norm">{$user.city}</td>
</tr>
<tr class="bg2">
	<td id="norm">anzahl der nachrichten</td><td id="norm">{$user.msgquan}</td>
</tr>
<tr class="bg2">
	<td id="norm">mitglied seit</td><td id="norm">{$user.regdate}</td>
</tr>
<tr class="bg2">
	<td id="norm">email</td><td colspan="2" id="norm"><a href="mailto:{$user.email}">{$user.email}</a></td>
</tr>
<tr class="bg2">
	<td id="norm">icq</td><td colspan="2" id="norm">{$user.icq}</td>
</tr>
<tr class="bg2">
	<td id="norm">homepage</td><td colspan="2" id="norm"><a href="{$user.url}" target="_blank">{$user.url}</a></td>
</tr>
<tr class="bg2">
	<td id="norm">hobbys</td><td colspan="2" id="norm"><pre>{$user.hobby}</pre></td>
</tr>
<tr class="bg2">
	<td id="norm">letztes update</td><td colspan="2" id="norm">{$user.lchange}</td>
</tr>
<tr class="bg1">
	<td colspan="3" align="center" id="norm">
	{if $config.logedin == 1}
		<a href="pxmboard.php?mode=privatemessageform&brdid={$config.board.id}&toid={$user.id}{$config.sid}">private nachricht schreiben</a>
	{else}
		-
	{/if}
	</td>
</tr>
{if $config.admin == 1}
<tr class="bg1">
	<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=admuserform&brdid={$config.board.id}&usrid={$user.id}{$config.sid}" target="admin">userdaten editieren</a></td>
</tr>
{/if}
{if $config.moderator == 1}
	{if $user.status == 1}
		<tr class="bg1">
			<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=userchangestatus&brdid={$config.board.id}&usrid={$user.id}{$config.sid}">user sperren</a></td>
		</tr>
	{elseif $user.status == 4}
		<tr class="bg1">
			<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=userchangestatus&brdid={$config.board.id}&usrid={$user.id}{$config.sid}">user freigeben</a></td>
		</tr>
	{/if}
{/if}
</table>
</body>
</html>