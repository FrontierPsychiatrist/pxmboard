<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: user suche =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="usersearch"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
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
</xsl:template>
</xsl:stylesheet>