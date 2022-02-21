<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: userprofil =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="3" align="center" id="header">board: userprofil für <xsl:value-of select="user/nickname"/>
	<xsl:if test="user/status!=1"> (gesperrt)</xsl:if>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">vorname</td><td id="norm"><xsl:value-of select="user/fname"/></td><td rowspan="5" id="norm">
<xsl:choose>
	<xsl:when test="user/pic=''">
		<img src="images/empty.gif" width="100" height="150"/>
	</xsl:when>
	<xsl:otherwise>
		<img src="{config/propicdir}{user/pic}"/>
	</xsl:otherwise>
</xsl:choose>
</td>
</tr>
<tr class="bg2">
	<td id="norm">nachname</td><td id="norm"><xsl:value-of select="user/lname"/></td>
</tr>
<tr class="bg2">
	<td id="norm">wohnort</td><td id="norm"><xsl:value-of select="user/city"/></td>
</tr>
<tr class="bg2">
	<td id="norm">anzahl der nachrichten</td><td id="norm"><xsl:value-of select="user/msgquan"/></td>
</tr>
<tr class="bg2">
	<td id="norm">mitglied seit</td><td id="norm"><xsl:value-of select="user/regdate"/></td>
</tr>
<tr class="bg2">
	<td id="norm">email</td><td colspan="2" id="norm"><a href="mailto:{user/email}"><xsl:value-of select="user/email"/></a></td>
</tr>
<tr class="bg2">
	<td id="norm">icq</td><td colspan="2" id="norm"><xsl:value-of select="user/icq"/></td>
</tr>
<tr class="bg2">
	<td id="norm">homepage</td><td colspan="2" id="norm"><a href="{user/url}" target="_blank"><xsl:value-of select="user/url"/></a></td>
</tr>
<tr class="bg2">
	<td id="norm">hobbys</td><td colspan="2" id="norm"><pre><xsl:value-of select="user/hobby"/></pre></td>
</tr>
<tr class="bg2">
	<td id="norm">letztes update</td><td colspan="2" id="norm"><xsl:value-of select="user/lchange"/></td>
</tr>
<tr class="bg1">
	<td colspan="3" align="center" id="norm">
<xsl:choose>
	<xsl:when test="config/logedin=1">
		<a href="pxmboard.php?mode=privatemessageform&amp;brdid={config/board/id}&amp;toid={user/id}{config/sid}">private nachricht schreiben</a>
	</xsl:when>
	<xsl:otherwise>
		-
	</xsl:otherwise>
</xsl:choose>
	</td>
</tr>
<xsl:if test="config/admin=1">
	<tr class="bg1">
		<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=admuserform&amp;brdid={config/board/id}&amp;usrid={user/id}{config/sid}" target="admin">userdaten editieren</a></td>
	</tr>
</xsl:if>
<xsl:if test="config/moderator=1 and (user/status=1 or user/status=4)">
	<xsl:choose>
		<xsl:when test="user/status=1">
			<tr class="bg1">
				<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=userchangestatus&amp;brdid={config/board/id}&amp;usrid={user/id}{config/sid}">user sperren</a></td>
			</tr>
		</xsl:when>
		<xsl:when test="user/status=4">
			<tr class="bg1">
				<td colspan="3" align="center" id="norm"><a href="pxmboard.php?mode=userchangestatus&amp;brdid={config/board/id}&amp;usrid={user/id}{config/sid}">user freigeben</a></td>
			</tr>
		</xsl:when>
	</xsl:choose>
</xsl:if>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>