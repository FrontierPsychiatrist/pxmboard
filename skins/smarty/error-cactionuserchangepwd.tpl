<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: error =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<center>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{$config.sid}">userprofil</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{$config.sid}">passwort</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{$config.sid}">einstellungen</a></td>
</tr>
<tr class="bg1">
	<td colspan="3" align="center" id="header">forum : fehler</td>
</tr>
<tr class="bg2">
	<td colspan="3" align="center" id="norm">{$error.text}</td>
</tr>
<tr class="bg1">
	<td colspan="3" align="center" id="norm"><a href="mailto:{$config.webmaster}">mail webmaster</a></td>
</tr>
</table>
</center>
</body>
</html>