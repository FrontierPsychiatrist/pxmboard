<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: confirm =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<center>
<table cellspacing="0" cellpadding="5" border="0">
<tr class="bg1">
	<td align="center" id="header">forum : bestätigung</td>
</tr>
<tr class="bg2">
	<td id="norm">vielen dank für ihre nachricht &quot;<a href="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$msg.id}{$config.sid}">{$msg.subject}</a>&quot;</td>
</tr>
<tr class="bg2">
	<td id="norm"><a href="pxmboard.php?mode=threadlist&brdid={$config.board.id}{$config.sid}" target="top">lade index neu</a></td>
</tr>
<tr class="bg1">
	<td align="center" id="norm"><a href="mailto:{$config.webmaster}">mail webmaster</a></td>
</tr>
</table>
</center>
</body>
</html>