<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: gefundene nachrichten =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript">
	<!--
 		function ld(brd,trd,msg) {ldelim}
	  		if(parent.middle) parent.middle.location.href="pxmboard.php?mode=thread&brdid="+brd+"&thrdid="+trd+"{$config.sid}#p"+msg;
		{rdelim}
	//-->
	</script>
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
<tr><td colspan="8" align="center"><br><table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: {$config.items} gefundene nachrichten</td>
</tr>
{foreach $msg as $_msg}
<tr class="bg2">
	<td id="norm" colspan="2"><a href="pxmboard.php?mode=message&brdid={$_msg.boardid}&msgid={$_msg.id}{$config.sid}" target="bottom" onclick="ld({$_msg.boardid},{$_msg.threadid},{$_msg.id})">{$_msg.subject}</a> von
	<span class="{if $_msg.user.highlight == 1}highlight{/if}">
	{$_msg.user.nickname}
	</span>
	am {$_msg.date}
	{if $_msg.score>0}
	(Relevanz: {$_msg.score})
	{/if}</td>
</tr>
{/foreach}
<tr class="bg1">
	<td colspan="2" align="center" id="norm">
	{if $config.previd != ''}
		<a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}&searchid={$config.searchprofile.id}&page={$config.previd}{$config.sid}">prev</a> |
	{else}
		- |
	{/if}
	{if $config.count > 0}
		{section name=page start=1 loop=$config.count}
			{if $config.curid == $smarty.section.page.index}
				<u><b>{$smarty.section.page.index}</b></u>
			{else}
				<a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}&searchid={$config.searchprofile.id}&page={$smarty.section.page.index}{$config.sid}">{$smarty.section.page.index}</a>
			{/if}
		{/section}
		{if $config.curid == $config.count}
			<u><b>{$config.count}</b></u>
		{else}
			<a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}&searchid={$config.searchprofile.id}&page={$config.count}{$config.sid}">{$config.count}</a>
		{/if}
		 |
	{/if}
	{if $config.nextid != ''}
		<a href="pxmboard.php?mode=messagesearch&brdid={$config.board.id}&searchid={$config.searchprofile.id}&page={$config.nextid}{$config.sid}">next</a>
	{else}
		-
	{/if}
	</td>
</tr>
</table></td></tr></table>
</body>
</html>