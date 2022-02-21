<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"><xsl:comment/></script>
	<xsl:if test="config/admin=1 or config/mod=1">
	<script type="text/javascript" src="js/admin.js"><xsl:comment/></script>
	</xsl:if>
</head>
<body>
<table border="0" cellspacing="2" cellpadding="5" width="775">
<xsl:apply-templates select="msg"/>
<tr class="bg1">
<td align="center" id="norm">
<xsl:choose>
	<xsl:when test="not(config/previd='')">
		<a href="pxmboard.php?mode=messagelist&amp;thrdid={config/thrdid}&amp;brdid={config/board/id}&amp;page={config/previd}{config/sid}">prev</a> |
	</xsl:when>
	<xsl:otherwise>
		- |
	</xsl:otherwise>
</xsl:choose>
<xsl:if test="config/count > 0">
<xsl:call-template name="page">
	<xsl:with-param name="x" select="1"/>
</xsl:call-template>
 |
</xsl:if>
<xsl:choose>
	<xsl:when test="not(config/nextid='')">
		<a href="pxmboard.php?mode=messagelist&amp;thrdid={config/thrdid}&amp;brdid={config/board/id}&amp;page={config/nextid}{config/sid}">next</a>
	</xsl:when>
	<xsl:otherwise>
		-
	</xsl:otherwise>
</xsl:choose>
</td>
</tr>
</table>
</body>
</html>
</xsl:template>

<xsl:template match="msg">
<tr class="bg1">
	<td id="norm"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b>
	<xsl:choose>
	<xsl:when test="user/id>0">
		<a href="pxmboard.php?mode=userprofile&amp;brdid={/pxmboard/config/board/id}&amp;usrid={user/id}{/pxmboard/config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="user/nickname"/></a>
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="user/nickname"/>
	</xsl:otherwise>
	</xsl:choose>
	</b>
	<xsl:if test="user/email!=''">
	<xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text>(<a href="mailto:{user/email}"><xsl:value-of select="user/email"/></a>)
	</xsl:if>
	am <xsl:value-of select="date"/> Uhr</td>
	<xsl:if test="/pxmboard/config/admin=1 or /pxmboard/config/moderator=1">
		<form>
		<td align="right">
			<select onchange="adminaction(this.value,{/pxmboard/config/board/id},{id})">
			<option value="">ip: <xsl:value-of select="ip"/></option>
			<xsl:if test="replyto/id>0">
				<option value="deletemessage">löschen</option>
				<option value="deletesubthread">subthread löschen</option>
				<option value="extractsubthread">subthread extrahieren</option>
			</xsl:if>
			</select>
		</td>
		</form>
	</xsl:if>
	</tr></table></td>
</tr>
<tr class="bg2">
	<td id="norm">Thema: <b><xsl:value-of select="subject"/></b></td>
</tr>
<tr class="bg2">
	<td id="norm"><xsl:value-of select="body" disable-output-escaping="yes"/>
	<xsl:if test="config/usesignatures=1">
	<br/><xsl:value-of select="user/signature" disable-output-escaping="yes"/>
	</xsl:if>
	</td>
</tr>
<tr class="bg1">
<td align="center" id="norm">&lt;
<xsl:if test="/pxmboard/config/logedin=1">
	 <a href="pxmboard.php?mode=privatemessageform&amp;brdid={/pxmboard/config/board/id}&amp;msgid={id}&amp;toid={user/id}{/pxmboard/config/sid}" target="_blank" onclick="window.open(this,'myboard','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">private nachricht schreiben</a> |
<xsl:if test="user/id=/pxmboard/config/user/id or /pxmboard/config/admin=1 or /pxmboard/config/moderator=1">
	<a href="pxmboard.php?mode=messagenotification&amp;brdid={/pxmboard/config/board/id}&amp;msgid={id}{/pxmboard/config/sid}">mailbenachrichtigung
	<xsl:choose>
	<xsl:when test="notification=1"> deaktivieren</xsl:when>
	<xsl:otherwise> aktivieren</xsl:otherwise>
	</xsl:choose>
	</a> |
</xsl:if>
</xsl:if>
 <a href="pxmboard.php?mode=messageform&amp;brdid={/pxmboard/config/board/id}&amp;msgid={id}{/pxmboard/config/sid}">auf diese nachricht antworten</a> &gt;</td></tr>
</xsl:template>

<xsl:template name="page">
	<xsl:param name="x" select="x"/>
	<xsl:if test="/pxmboard/config/count >= $x">
		<xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text>
		<xsl:choose>
		<xsl:when test="/pxmboard/config/curid = $x">
			<u><b><xsl:value-of select="$x"/></b></u>
		</xsl:when>
		<xsl:otherwise>
			<a href="pxmboard.php?mode=messagelist&amp;thrdid={/pxmboard/config/thrdid}&amp;brdid={/pxmboard/config/board/id}&amp;page={$x}{/pxmboard/config/sid}"><xsl:value-of select="$x"/></a>
		</xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="page">
			<xsl:with-param name="x" select="$x + 1"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>
</xsl:stylesheet>