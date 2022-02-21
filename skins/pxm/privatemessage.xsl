<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: private message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"><xsl:comment/></script>
	<script type="text/javascript">
	<xsl:comment>
		function delmsg(){
			result = confirm("Soll diese Nachricht geloescht werden?");
			if(result == true) location.href="pxmboard.php?type=<xsl:value-of select="config/type"/><![CDATA[&mode=privatemessagedelete&msgid=]]><xsl:value-of select="msg/id"/>";
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
	<td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b><a href="pxmboard.php?mode=userprofile&amp;usrid={msg/user/id}{config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="msg/user/nickname"/></a></b>
	<xsl:if test="msg/user/email!=''">
	<xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text>(<a href="mailto:{msg/user/email}"><xsl:value-of select="msg/user/email"/></a>)
	</xsl:if>
	am <xsl:value-of select="msg/date"/> Uhr</td></tr></table></td>
</tr>
<tr class="bg2">
	<td id="norm" colspan="2">Thema: <b><xsl:value-of select="msg/subject"/></b></td>
</tr>
<tr class="bg2">
	<td colspan="2" id="norm"><xsl:value-of select="msg/body" disable-output-escaping="yes"/>
	<xsl:if test="config/usesignatures=1">
	<br/><xsl:value-of select="msg/user/signature" disable-output-escaping="yes"/>
	</xsl:if>
	</td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm">&lt; <a href="pxmboard.php?mode=privatemessagelist&amp;type={config/type}{config/sid}">zurück</a> | <xsl:if test="config/type='inbox'"><a href="pxmboard.php?mode=privatemessageform&amp;type=outbox&amp;toid={msg/user/id}&amp;pmsgid={msg/id}{config/sid}">auf diese nachricht antworten</a> | </xsl:if><a href="#" onclick="delmsg(); return false;">nachricht löschen</a> &gt;</td>
</tr>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>