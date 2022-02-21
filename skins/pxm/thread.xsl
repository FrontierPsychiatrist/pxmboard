<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"/>
   	<meta http-equiv="Pragma" content="no-cache"/>
   	<meta http-equiv="expires" content="0"/>
	<title>-= board: thread =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<xsl:if test="config/admin=1 or config/moderator=1">
	<script type="text/javascript" src="js/admin.js"><xsl:comment/></script>
	</xsl:if>
</head>
<body>
<xsl:if test="thread">
<table cellspacing="0" cellpadding="0" border="0" width="900">
	<tr class="bg1"><td valign="middle"><table cellspacing="2" cellpadding="5" border="0" width="100%"><tr><td id="norm"><a href="pxmboard.php?mode=message&amp;brdid={config/board/id}&amp;msgid={thread/msg/id}{config/sid}" target="bottom" name="p{thread/msg/id}"><xsl:value-of select="thread/msg/subject"/></a> von
<xsl:choose>
	<xsl:when test="thread/msg/user/highlight=1">
		<span class="highlight"><xsl:value-of select="thread/msg/user/nickname"/></span>
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="thread/msg/user/nickname"/>
	</xsl:otherwise>
</xsl:choose>
	am <xsl:value-of select="thread/msg/date"/> Uhr</td>
	<td align="right"><a href="pxmboard.php?mode=messagelist&amp;brdid={config/board/id}&amp;thrdid={thread/id}{config/sid}" target="flatview" onclick="window.open(this,'flatview','width=800,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">flatview</a></td>
<xsl:if test="config/admin=1 or config/moderator=1">
		<form><td align="right">
		<select onchange="adminaction(this.value,{config/board/id},{thread/id})">
			<option value="">bitte Option wählen</option>
			<option value="">------------------------</option>
			<option value="threadstatus"><xsl:choose>
											<xsl:when test="thread/active=1">schliessen</xsl:when>
											<xsl:otherwise>öffnen</xsl:otherwise>
										 </xsl:choose></option>
			<option value="fixthread"><xsl:choose>
											<xsl:when test="thread/fixed=1">lösen</xsl:when>
											<xsl:otherwise>fixieren</xsl:otherwise>
										 </xsl:choose></option>
			<option value="movethread">verschieben</option>
			<option value="deletethread">löschen</option>
		</select>
		</td></form>
</xsl:if>
	</tr></table></td></tr>
	<xsl:apply-templates select="thread/msg/msg"/>
</table>
</xsl:if>
</body>
</html>
</xsl:template>

<xsl:template match="msg">
	<tr class="bg2"><td valign="middle"><table cellspacing="0" cellpadding="0" border="0"><tr><td><xsl:value-of select="img" disable-output-escaping="yes"/></td><td id="norm">
	<xsl:variable name="own">
	<xsl:choose>
		<xsl:when test="/pxmboard/config/user/id>0 and user/id=/pxmboard/config/user/id">own</xsl:when>
	</xsl:choose>
	</xsl:variable>
	<span class="{$own}">
	<a href="pxmboard.php?mode=message&amp;brdid={/pxmboard/config/board/id}&amp;msgid={id}{/pxmboard/config/sid}" target="bottom" name="p{id}"><xsl:value-of select="subject"/></a> von
	<xsl:choose>
		<xsl:when test="user/highlight=1">
		<span class="highlight"><xsl:value-of select="user/nickname"/></span>
		</xsl:when>
		<xsl:otherwise>
		<xsl:value-of select="user/nickname"/>
		</xsl:otherwise>
	</xsl:choose>
	am <xsl:value-of select="date"/> Uhr
	<xsl:if test="/pxmboard/config/logedin=1 and new=1">
		(neu)
	</xsl:if>
	</span>
	</td></tr></table></td></tr>
	<xsl:apply-templates select="msg"/>
</xsl:template>
</xsl:stylesheet>