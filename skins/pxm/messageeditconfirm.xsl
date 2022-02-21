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
<center>
<table cellspacing="0" cellpadding="5" border="0">
<tr class="bg1">
	<td align="center" id="header">forum : best�tigung</td>
</tr>
<tr class="bg2">
	<td id="norm">vielen dank f�r das �ndern ihrer nachricht &quot;<a href="pxmboard.php?mode=message&amp;brdid={config/board/id}&amp;msgid={msg/id}{config/sid}"><xsl:value-of select="msg/subject"/></a>&quot;</td>
</tr>
<tr class="bg2">
	<td id="norm"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}{config/sid}" target="top">lade index neu</a></td>
</tr>
<tr class="bg1">
	<td align="center" id="norm"><a href="mailto:{config/webmaster}">mail webmaster</a></td>
</tr>
</table>
</center>
</body>
</html>
</xsl:template>
</xsl:stylesheet>