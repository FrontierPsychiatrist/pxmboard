<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: user suche =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php?mode=usersearch&brdid={$config.board.id}{$config.sid}" method="post">
<center>
<table border="0" cellspacing="2" cellpadding="5">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: user suche</td>
</tr>
<tr class="bg2">
	<td id="norm">nickname</td><td id="input"><input type="text" name="nick" size="30" maxlength="30"/></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table>
</center>
</form>
</body>
</html>