<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: passwort ändern =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="userchangepwd"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{$config.sid}">userprofil</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{$config.sid}">passwort</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{$config.sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: passwort ändern für {$config.user.nickname}</td>
</tr>
<tr class="bg2">
	<td id="norm">neues passwort 1</td><td id="input"><input type="password" name="pwd" size="20" maxlength="20"/></td>
</tr>
<tr class="bg2">
	<td id="norm">neues passwort 2</td><td id="input"><input type="password" name="pwdc" size="20" maxlength="20"/></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table>
</form>
</body>
</html>