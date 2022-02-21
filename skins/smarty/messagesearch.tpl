<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: message suche =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header" colspan="2"><img src="images/logo.gif" width="250" height="80"/></th><th id="header" colspan="6">{$config.banner._code}</th>
</tr>
<tr class="bg2" align="center" valign="middle">
	<form action="pxmboard.php" method="get">
	{$config._sidform}
	<input type="hidden" name="mode" value="threadlist"/>
	<td id="norm"><select name="brdid" size="1">
{foreach from=$boards.board item=board}
{if $board.active == 1}
	{if $config.board.id == $board.id}
		<option value="{$board.id}" selected="selected">{$board.name}</option>
	{else}
		<option value="{$board.id}">{$board.name}</option>
	{/if}
{/if}
{/foreach}
	</select>
	<select name="date" size="1">
		<option value="1" {if $config.timespan == 1}selected="selected"{/if}>1 tag</option>
		<option value="7" {if $config.timespan == 7}selected="selected"{/if}>7 tage</option>
		<option value="14" {if $config.timespan == 14}selected="selected"{/if}>14 tage</option>
		<option value="30" {if $config.timespan == 30}selected="selected"{/if}>30 tage</option>
		<option value="365" {if $config.timespan == 365}selected="selected"{/if}>1 jahr</option>
	</select>
	<input type="image" src="images/go.gif" border="0"/></td></form>
	<td id="norm"><a href="pxmboard.php?mode=useronline&brdid={$config.board.id}{$config.sid}" target="bottom">who's online</a></td>
	<td id="norm"><a href="pxmboard.php?mode=usersearch&brdid={$config.board.id}{$config.sid}" target="bottom">user</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}{$config.sid}">suche</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messageform&brdid={$config.board.id}{$config.sid}" target="bottom">thread erstellen</a></td>
{if $config.logedin == 1}
	<td id="norm"><a href="pxmboard.php?mode=privatemessagelist{$config.sid}" target="_blank" onclick="window.open(this,'pxm_mailbox','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">mailbox</a></td>
	<td id="norm"><a href="pxmboard.php?mode=userprofileform{$config.sid}" target="_blank" onclick="window.open(this,'pxm_setup','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">setup</a></td>
	<td id="norm"><a href="pxmboard.php?mode=logout{$config.sid}" target="_parent">logout</a></td>
{else}
		<td id="norm"><a href="pxmboard.php?mode=login{$config.sid}" target="_parent">login</a></td>
		<td id="norm" colspan="2"><a href="pxmboard.php?mode=userregistration{$config.sid}" target="bottom">registrieren</a></td>
{/if}
</tr>
<tr><td colspan="8" align="center"><br><form action="pxmboard.php" method="get">
{$config._sidform}
<input type="hidden" name="mode" value="messagesearch"/>
<input type="hidden" name="brdid" value="{$config.board.id}"/>
<table border="0" cellspacing="2" cellpadding="5">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: message suche</td>
</tr>
{if $error}
	<tr class="bg1"><td colspan="2" align="center" id="norm">fehler {$error.id}: {$error.text}</td></tr>
{/if}
<tr class="bg2">
	<td id="norm">suche in nachricht nach</td><td id="input"><input type="text" name="smsg" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">suche nachrichten von</td><td id="input"><input type="text" name="susr" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">suche in forum</td><td id="input"><select name="sbrdid[]" size="1">
		<option value="0">alle foren</option>
{foreach from=$boards.board item=board}
{if $board.active == 1}
	{if $config.board.id == $board.id}
		<option value="{$board.id}" selected="selected">{$board.name}</option>
	{else}
		<option value="{$board.id}">{$board.name}</option>
	{/if}
{/if}
{/foreach}
	</select></td>
</tr>
<tr class="bg2">
	<td id="norm">innerhalb der letzten</td><td id="input"><select name="days" size="1">
		<option value="30">30 tage</option>
		<option value="90" selected="selected">90 tage</option>
		<option value="180">180 tage</option>
		<option value="365">365 tage</option>
		<option value="0">komplett</option>
	</select></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table></form></td></tr>
<tr><td colspan="8" align="center">
<table border="0" cellspacing="2" cellpadding="5">
<tr class="bg1">
	<td align="center" id="header">board: das interessiert unsere nutzer</td>
</tr>
{foreach from=$searchprofiles.searchprofile item=searchprofile}
<tr class="bg2">
	<td id="norm"><a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}&searchid={$searchprofile.id}{$config.sid}">
	{if !$searchprofile.searchstring}
	Nachrichten
	{else}
	{$searchprofile.searchstring}
	{/if}
	{if $searchprofile.nickname}
	von
	{$searchprofile.nickname}
	{/if}
	</a>
	{if $searchprofile.days>0}
	innerhalb der letzten {$searchprofile.days} tage
	{/if}
	gesucht am {$searchprofile.date}
	</td>
</tr>
{/foreach}
</table>
</td></tr></table>
</body>
</html>