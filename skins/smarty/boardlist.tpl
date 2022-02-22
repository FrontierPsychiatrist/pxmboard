<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"/>
   	<meta http-equiv="Pragma" content="no-cache"/>
   	<meta http-equiv="expires" content="0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: index =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
	{if $config.admin == 1}
	<script type="text/javascript">
	<!--
		function boardstate(boardid){ldelim}
	  		result = confirm("Soll der Status des Forums "+boardid+" geaendert werden?");
  			if(result == true) location.href="pxmboard.php?brdid={$config.board.id}&mode=boardchangestatus{$config.sid}&id="+boardid;
  		{rdelim}
  	//-->
	</script>
	{/if}
</head>
<body>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header"><img src="images/logo.gif" width="250" height="80"/></th><th id="header" colspan="2">{$config.banner._code}</th>
</tr>
<tr class="bg2" align="center" valign="middle">
{if $config.logedin == 1}
		<td id="norm">Herzlich Willkommen {$config.user.nickname}.<br>Du hast <a href="pxmboard.php?mode=privatemessagelist{$config.sid}" target="_blank" onclick="window.open(this,'pxm_mailbox','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">{$config.user.newprivmsgs} neue private Nachricht(en)</a></td>
		<td id="norm">Neuestes Mitglied im Forum: <a href="pxmboard.php?mode=userprofile&usrid={$newestmember.user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$newestmember.user.nickname}</a></td>
		<td id="norm"><a href="pxmboard.php?mode=logout{$config.sid}" target="_parent">logout</a></td>
{else}
		<td id="norm">Neuestes Mitglied im Forum: <a href="pxmboard.php?mode=userprofile&usrid={$newestmember.user.id}{$config.sid}" target="_blank" onclick="openProfile(this);return false;">{$newestmember.user.nickname}</a></td>
		<td id="norm"><a href="pxmboard.php?mode=usersendpwd{$config.sid}">passwort zusenden</a></td>
		<td id="norm"><a href="pxmboard.php?mode=userregistration{$config.sid}">registrieren</a></td>
{/if}
</tr>
</table>
<br>
<table border="0" cellpadding="5" cellspacing="2" width="900">
<tr class="bg1">
	<th id="header">st</th><th id="header">name</th><th id="header">thema</th><th id="header">letzte nachricht</th><th id="header">moderator(en)</th>
{if $config.admin == 1}
	<th id="header">admin</th>
{/if}
</tr>

{foreach from=$boards.board item=board}
<tr class="bg2" valign="top">
{if $board.active == 1}
	<td id="norm">
	{if $config.admin == 1}
		<a href="#" onclick="boardstate({$board.id});return false;"><img src="images/open.gif" border="0" width="15" height="15"/></a>
	{else}
		<img src="images/open.gif" width="15" height="15"/>
	{/if}
	</td><td id="norm"><a href="pxmboard.php?mode=board&brdid={$board.id}{$config.sid}">{$board.name}</a></td>
{else}
	<td id="norm">
	{if $config.admin == 1}
		<a href="#" onclick="boardstate({$board.id});return false;"><img src="images/closed.gif" border="0" width="15" height="15"/></a>
	{else}
		<img src="images/closed.gif" width="15" height="15"/>
	{/if}
	</td><td id="norm">{$board.name}</td>
{/if}
<td id="norm">{$board.desc}</td><td align="center" id="norm">{$board.lastmsg}</td><td id="norm">
{foreach from=$board.moderator item=moderator}
{$moderator.nickname}<br>
{/foreach}
</td>
{if $config.admin == 1}
	<td id="norm" align="center"><a href="pxmboard.php?mode=admboardform&id={$board.id}{$config.sid}" target="admin">edit</a></td>
{/if}
</tr>
{/foreach}

{if $config.admin == 1}
	<tr class="bg1"><td id="norm" align="center" colspan="6">&lt; <a href="pxmboard.php?mode=admboardform{$config.sid}" target="admin">board hinzufügen</a> | <a href="pxmboard.php?mode=admframe{$config.sid}" target="admin">weitere Funktionen</a> ></td></tr>
{/if}
{if $config.logedin == 0}
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="login"/>
<input type="hidden" name="brdid" value="{$config.board.id}"/>
	<tr class="bg1"><td id="norm" colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr valign="top"><td id="norm">nickname</td><td id="input"><input type="text" name="nick" size="30" maxlength="30"/></td>
																	     <td id="norm">passwort</td><td id="input"><input type="password" name="pass" size="20" maxlength="20"/></td>
																	     <td align="center" id="norm"><input type="submit" value="login"/></td></tr></table></td></tr>
</form>
{if $error}
	<tr class="bg1"><td id="norm" align="center" colspan="5">fehler {$error.id}: {$error.text}</td></tr>
{/if}
{/if}
<tr><td id="norm" align="center" colspan="5">&nbsp;</td></tr>
<tr class="bg1"><th id="header" colspan="5">neueste beitr&auml;ge</th></tr>
{foreach from=$newestmessages.msg item=msg}
<tr class="bg2" valign="top">
	<td id="norm" colspan="5"><table width="100%">
		<tr>
			<td id="norm"><a href="pxmboard.php?mode=board&brdid={$msg.thread.brdid}&thrdid={$msg.thread.id}&msgid={$msg.id}{$config.sid}">{$msg.subject}</a> von
			<span class="{if $msg.user.highlight == 1}highlight{/if}">
			{$msg.user.nickname}
			</span>
			am {$msg.date} Uhr</td>
		</tr>
	</table></td>
</tr>
{/foreach}
<tr><td id="norm" align="center" colspan="5"><br>powered by <a href="http://www.pxmboard.de" target="_blank">pxmboard</a></td></tr>
</table>
</body>
</html>