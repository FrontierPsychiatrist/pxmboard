<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: confirm =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}">userprofil</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{config/sid}">passwort</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{config/sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td align="center" id="header">forum : bestätigung</td>
</tr>
<tr class="bg2">
	<td align="center" id="norm">ihre daten wurden gespeichert</td>
</tr>
<tr class="bg1">
	<td align="center" id="norm"><a href="mailto:{config/webmaster}">mail webmaster</a></td>
</tr>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>