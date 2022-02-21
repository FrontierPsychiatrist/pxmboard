<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: message suche =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header" colspan="2"><img src="images/logo.gif" width="250" height="80"/></th><th id="header" colspan="6"><xsl:value-of disable-output-escaping="yes" select="config/banner/code"/></th>
</tr>
<tr class="bg2" align="center" valign="middle">
	<form action="pxmboard.php" method="get">
	<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
	<input type="hidden" name="mode" value="threadlist"/>
	<td id="norm"><select name="brdid" size="1">
<xsl:for-each select="boards/board">
<xsl:if test="active=1">
<xsl:choose>
	<xsl:when test="/pxmboard/config/board/id=id">
		<option value="{id}" selected="selected"><xsl:value-of select="name"/></option>
	</xsl:when>
	<xsl:otherwise>
		<option value="{id}"><xsl:value-of select="name"/></option>
	</xsl:otherwise>
</xsl:choose>
</xsl:if>
</xsl:for-each>
	</select>
	<select name="date" size="1">
		<xsl:choose>
			<xsl:when test="config/timespan=1"><option value="1" selected="selected">1 tag</option></xsl:when>
			<xsl:otherwise><option value="1">1 tag</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=7"><option value="7" selected="selected">7 tage</option></xsl:when>
			<xsl:otherwise><option value="7">7 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=14"><option value="14" selected="selected">14 tage</option></xsl:when>
			<xsl:otherwise><option value="14">14 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=30"><option value="30" selected="selected">30 tage</option></xsl:when>
			<xsl:otherwise><option value="30">30 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=365"><option value="365" selected="selected">1 jahr</option></xsl:when>
			<xsl:otherwise><option value="365">1 jahr</option></xsl:otherwise>
		</xsl:choose>
	</select>
	<input type="image" src="images/go.gif" border="0"/></td></form>
	<td id="norm"><a href="pxmboard.php?mode=useronline&amp;brdid={config/board/id}{config/sid}" target="bottom">who's online</a></td>
	<td id="norm"><a href="pxmboard.php?mode=usersearch&amp;brdid={config/board/id}{config/sid}" target="bottom">user</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messagesearch&amp;brdid={config/board/id}{config/sid}">suche</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messageform&amp;brdid={config/board/id}{config/sid}" target="bottom">thread erstellen</a></td>
<xsl:choose>
	<xsl:when test="config/logedin=1">
		<td id="norm"><a href="pxmboard.php?mode=privatemessagelist{config/sid}" target="_blank" onclick="window.open(this,'pxm_mailbox','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">mailbox</a></td>
		<td id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}" target="_blank" onclick="window.open(this,'pxm_setup','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">setup</a></td>
		<td id="norm"><a href="pxmboard.php?mode=logout&amp;brdid={config/board/id}{config/sid}" target="_parent">logout</a></td>
	</xsl:when>
	<xsl:otherwise>
		<td id="norm"><a href="pxmboard.php?mode=login{config/sid}" target="_parent">login</a></td>
		<td id="norm" colspan="2"><a href="pxmboard.php?mode=userregistration{config/sid}" target="bottom">registrieren</a></td>
	</xsl:otherwise>
</xsl:choose>
</tr>
<tr><td colspan="8" align="center"><br/><form action="pxmboard.php" method="get">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="messagesearch"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
<table border="0" cellspacing="2" cellpadding="5">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: message suche</td>
</tr>
<xsl:for-each select="error">
	<tr class="bg1"><td colspan="2" align="center" id="norm">fehler <xsl:value-of select="id"/>: <xsl:value-of select="text"/></td></tr>
</xsl:for-each>
<tr class="bg2">
	<td id="norm">suche in nachricht nach</td><td id="input"><input type="text" name="smsg" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">suche nachrichten von</td><td id="input"><input type="text" name="susr" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">suche in forum</td><td id="input"><select name="sbrdid[]" size="1">
		<option value="0">alle foren</option>
<xsl:for-each select="boards/board">
<xsl:if test="active=1">
<xsl:choose>
	<xsl:when test="/pxmboard/config/board/id=id">
		<option value="{id}" selected="selected"><xsl:value-of select="name"/></option>
	</xsl:when>
	<xsl:otherwise>
		<option value="{id}"><xsl:value-of select="name"/></option>
	</xsl:otherwise>
</xsl:choose>
</xsl:if>
</xsl:for-each>
	</select></td>
</tr>
<tr class="bg2">
	<td id="norm">innerhalb der letzten</td><td id="input"><select name="days" size="1">
		<option value="30">30 tage</option>
		<option value="90" selected="selected">90 tage</option>
		<option value="180">180 tage</option>
		<option value="365">365 tage</option>
		<option value="0">komplett</option>
	</select></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table></form></td></tr>
<tr><td colspan="8" align="center">
<table border="0" cellspacing="2" cellpadding="5">
<tr class="bg1">
	<td  align="center" id="header">board: das interessiert unsere nutzer</td>
</tr>
<xsl:for-each select="searchprofiles/searchprofile">
<tr class="bg2">
	<td id="norm">
	<a href="pxmboard.php?mode=messagesearch&amp;brdid={/pxmboard/config/board/id}&amp;searchid={id}{/pxmboard/config/sid}">
	<xsl:choose>
	<xsl:when test="searchstring=''">
	Nachrichten
	</xsl:when>
	<xsl:otherwise>
	<xsl:value-of select="searchstring"/>
	</xsl:otherwise>
	</xsl:choose>
	<xsl:if test="nickname!=''">
	von
	<xsl:value-of select="nickname"/>
	</xsl:if>
	</a>
	<xsl:if test="days>0">
	innerhalb der letzten <xsl:value-of select="days"/> tage
	</xsl:if>
	gesucht am <xsl:value-of select="date"/>
	</td>
</tr>
</xsl:for-each>
</table>
</td></tr></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>