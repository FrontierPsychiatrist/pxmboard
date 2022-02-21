<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"/>
   	<meta http-equiv="Pragma" content="no-cache"/>
   	<meta http-equiv="expires" content="0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: index =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"></script>
	<script type="text/javascript">
	<!--
		function ld(trd,msg) {ldelim}
		  	if(parent.middle) {ldelim}
		  		var location = "pxmboard.php?mode=thread&brdid={$config.board.id}{$config.sid}&thrdid="+trd+"{$config.sid}";
		  		if(msg>0) location = location + "#p"+msg;
				parent.middle.location.href = location;
			{rdelim}
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
</table>
<br>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header">st</th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=subject{$config.sid}">thema</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=nickname{$config.sid}">autor</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=thread{$config.sid}">datum</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=views{$config.sid}">view</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=replies{$config.sid}">#</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort=last{$config.sid}">letzter beitrag</a></th>
</tr>

{foreach $threads as $thread}
<tr class="bg2">
{if $thread.fixed == 1}
	<td align="center"><img src="images/fixed.gif" width="15" height="15"/></td>
{elseif $thread.active == 1}
	<td align="center"><img src="images/open.gif" width="15" height="15"/></td>
{else}
	<td align="center"><img src="images/closed.gif" width="15" height="15"/></td>
{/if}
<td id="norm"><a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$thread.id}{$config.sid}" target="bottom" onclick="ld({$thread.threadid},0)">{$thread.subject}</a></td><td id="norm">
{if $thread.user.id > 0}<a href="pxmboard.php?mode=userprofile&usrid={$thread.user.id}{$config.sid}" target="_blank" onClick="openProfile(this);return false;">{/if}
<span class="{if $thread.user.highlight == 1}highlight{/if}">{$thread.user.nickname}</span>
{if $thread.user.id > 0}</a>{/if}
</td>
<td align="right" id="norm">{$thread.date}</td>
<td align="right" id="norm">{$thread.views}</td>
<td align="center" id="norm"><a href="pxmboard.php?mode=thread&brdid={$config.board.id}&thrdid={$thread.threadid}{$config.sid}" target="middle">{$thread.msgquan}</a></td>
<td align="right" id="norm"><a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$thread.lastid}{$config.sid}" target="bottom" onclick="ld({$thread.threadid},{$thread.lastid})">{$thread.lastdate}</a></td>
</tr>
{/foreach}

<tr class="bg1">
<td align="center" colspan="7" id="norm">
{if $config.previd != ''}
	<a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort={$config.sort}&page={$config.previd}{$config.sid}">prev</a> |
{else}
	- |
{/if}
{if $config.nextid != ''}
	<a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}&date={$config.timespan}&sort={$config.sort}&page={$config.nextid}{$config.sid}">next</a>
{else}
	-
{/if}
</td>
</tr>
</table>
</body>
</html>