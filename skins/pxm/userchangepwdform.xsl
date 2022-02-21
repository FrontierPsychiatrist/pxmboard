<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: passwort �ndern =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="userchangepwd"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}">userprofil</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=pxmboard{config/sid}">passwort</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{config/sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: passwort �ndern f�r <xsl:value-of select="config/user/nickname"/></td>
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
</xsl:template>
</xsl:stylesheet>