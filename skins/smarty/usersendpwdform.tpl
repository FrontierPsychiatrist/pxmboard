<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: passwort zusenden =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<center>
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="usersendpwd"/>
<input type="hidden" name="brdid" value="{$config.board.id}"/>
<table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: passwort zusenden</td>
</tr>
<tr class="bg2">
	<td id="norm">nickname</td><td id="input"><input type="text" name="nick" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">email adr. bei registrierung</td><td id="input"><input type="text" name="email" size="30" maxlength="100"/></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table>
</form>
</center>
</body>
</html>