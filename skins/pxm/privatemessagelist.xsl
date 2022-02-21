<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: private message index =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript">
	<xsl:comment>
	 function delmsg(){
	  result = confirm("Sollen alle Nachrichten geloescht werden?");
	  if(result == true) location.href="pxmboard.php?type=<xsl:value-of select="config/type"/><xsl:value-of select="config/sid"/><![CDATA[&mode=privatemessagedelete&msgid=-1]]>";
	 }
	</xsl:comment>
	</script>
</head>
<body>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
<xsl:choose>
	<xsl:when test="config/type='outbox'">
		<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&amp;type=inbox{config/sid}">inbox</a></td>
		<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&amp;type=outbox{config/sid}">outbox</a></td>
	</xsl:when>
	<xsl:otherwise>
		<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&amp;type=inbox{config/sid}">inbox</a></td>
		<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&amp;type=outbox{config/sid}">outbox</a></td>
	</xsl:otherwise>
</xsl:choose>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<th id="header">thema</th><th id="header">
	<xsl:choose>
	<xsl:when test="config/type='outbox'">empfänger</xsl:when>
	<xsl:otherwise>autor</xsl:otherwise>
	</xsl:choose>
	</th><th id="header">datum</th>
</tr>
	<xsl:apply-templates select="msg"/>
<tr class="bg1">
<td align="center" colspan="3" id="norm">
<xsl:choose>
	<xsl:when test="not(config/previd='')">
		<a href="pxmboard.php?mode=privatemessagelist&amp;type={config/type}&amp;page={config/previd}{config/sid}">prev</a> |
	</xsl:when>
	<xsl:otherwise>
		- |
	</xsl:otherwise>
</xsl:choose>
<xsl:if test="msg"><a href="#" onclick="delmsg(); return false;">alle nachrichten löschen</a> |</xsl:if>
<xsl:choose>
	<xsl:when test="not(config/nextid='')">
		<a href="pxmboard.php?mode=privatemessagelist&amp;type={config/type}&amp;page={config/nextid}{config/sid}">next</a>
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
<tr class="bg2">
<td id="norm"><a href="pxmboard.php?mode=privatemessage&amp;msgid={id}&amp;type={/pxmboard/config/type}{/pxmboard/config/sid}"><xsl:value-of select="subject"/></a></td><td id="norm">

<xsl:choose>
	<xsl:when test="user/highlight=1">
		<span class="highlight"><xsl:value-of select="user/nickname"/></span>
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="user/nickname"/>
	</xsl:otherwise>
</xsl:choose>

</td><td align="right" id="norm"><xsl:value-of select="date"/></td>
</tr>
</xsl:template>
</xsl:stylesheet>