<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: confirm =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
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
	<td align="center" id="header">forum: bestätigung</td>
</tr>
<tr class="bg2">
	<td align="center" id="norm">ihre nachricht wurde abgeschickt - vielen dank</td>
</tr>
<tr class="bg1">
	<td align="center" id="norm"><a href="mailto:{$config.webmaster}">mail webmaster</a></td>
</tr>
</table>
</body>
</html>