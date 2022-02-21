<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: gefundene user =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"><xsl:comment/></script>
</head>
<body>
<center>
<table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: gefundene user</td>
</tr>
	<xsl:apply-templates select="user"/>
<tr class="bg1">
<td align="center" id="norm">
<xsl:choose>
	<xsl:when test="config/previd!=''">
		<a href="pxmboard.php?mode=usersearch&amp;brdid={/pxmboard/config/board/id}&amp;nick={config/nickname}&amp;page={config/previd}{config/sid}">prev</a>
	</xsl:when>
	<xsl:otherwise>
		-
	</xsl:otherwise>
</xsl:choose>
</td>
<td align="center" id="norm">
<xsl:choose>
	<xsl:when test="config/nextid!=''">
		<a href="pxmboard.php?mode=usersearch&amp;brdid={/pxmboard/config/board/id}&amp;nick={config/nickname}&amp;page={config/nextid}{config/sid}">next</a>
	</xsl:when>
	<xsl:otherwise>
		-
	</xsl:otherwise>
</xsl:choose>
</td>
</tr>
</table>
</center>
</body>
</html>
</xsl:template>

<xsl:template match="user">
<xsl:if test="position() mod 2 = 1">
<xsl:choose>
	<xsl:when test="position()=last()">
	<tr class="bg2">
		<td colspan="2" align="center" id="norm"><a href="pxmboard.php?mode=userprofile&amp;brdid={/pxmboard/config/board/id}&amp;usrid={id}{/pxmboard/config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="nickname"/></a><xsl:if test="status!=1"> (gesperrt)</xsl:if></td>
	</tr>
	</xsl:when>
	<xsl:otherwise>
	<tr class="bg2">
		<td id="norm" width="50%"><a href="pxmboard.php?mode=userprofile&amp;brdid={/pxmboard/config/board/id}&amp;usrid={id}{/pxmboard/config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="nickname"/></a><xsl:if test="status!=1"> (gesperrt)</xsl:if></td>
		<td id="norm" width="50%"><a href="pxmboard.php?mode=userprofile&amp;brdid={/pxmboard/config/board/id}&amp;usrid={following-sibling::user/id}{/pxmboard/config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="following-sibling::user/nickname"/></a><xsl:if test="following-sibling::user[position()=1]/status!=1"> (gesperrt)</xsl:if></td>
	</tr>
	</xsl:otherwise>
</xsl:choose>
</xsl:if>
</xsl:template>
</xsl:stylesheet>