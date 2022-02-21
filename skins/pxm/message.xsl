<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: message =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"><xsl:comment/></script>
	<xsl:if test="(config/admin=1 or config/mod=1) and msg/replyto/id>0">
	<script type="text/javascript" src="js/admin.js"><xsl:comment/></script>
	</xsl:if>
</head>
<body>
<xsl:if test="msg">
<table border="0" cellspacing="2" cellpadding="5" width="900">
<tr class="bg1">
	<td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td id="norm"><b>
	<xsl:choose>
	<xsl:when test="msg/user/id>0">
		<a href="pxmboard.php?mode=userprofile&amp;brdid={config/board/id}&amp;usrid={msg/user/id}{config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="msg/user/nickname"/></a>
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="msg/user/nickname"/>
	</xsl:otherwise>
	</xsl:choose>
	</b>
	<xsl:if test="msg/user/email!=''">
	<xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text>(<a href="mailto:{msg/user/email}"><xsl:value-of select="msg/user/email"/></a>)
	</xsl:if>
	am <xsl:value-of select="msg/date"/> Uhr</td>
	<xsl:if test="config/admin=1 or config/moderator=1">
		<form>
		<td align="right">
			<select onchange="adminaction(this.value,{config/board/id},{msg/id})">
			<option value="">ip: <xsl:value-of select="msg/ip"/></option>
			<xsl:if test="msg/replyto/id>0">
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
<xsl:choose>
<xsl:when test="msg/replyto/id>0">
	<td id="norm">Thema: <b><xsl:value-of select="msg/subject"/></b></td>
	<td id="norm">Antwort auf: <a href="pxmboard.php?mode=message&amp;brdid={config/board/id}&amp;msgid={msg/replyto/id}{config/sid}"><b><xsl:value-of select="msg/replyto/subject"/></b></a> von <b><xsl:value-of select="msg/replyto/user/nickname"/></b></td>
</xsl:when>
<xsl:otherwise>
	<td id="norm" colspan="2">Thema: <b><xsl:value-of select="msg/subject"/></b></td>
</xsl:otherwise>
</xsl:choose>
</tr>
<tr class="bg2">
	<td colspan="2" id="norm"><xsl:value-of select="msg/body" disable-output-escaping="yes"/>
	<xsl:if test="config/usesignatures=1">
	<br/><xsl:value-of select="msg/user/signature" disable-output-escaping="yes"/>
	</xsl:if>
	</td>
</tr>
<tr class="bg1">
<td colspan="2" align="center" id="norm">&lt;
<script type="text/javascript">
<xsl:text disable-output-escaping="yes"><![CDATA[<!--
  	if(parent.frames.length < 3) {
		document.write("<a href=\"pxmboard.php?mode=board&amp;brdid=]]></xsl:text><xsl:value-of select="config/board/id"/>&amp;thrdid=<xsl:value-of select="msg/thread/id"/>&amp;msgid=<xsl:value-of select="msg/id"/><xsl:value-of select="config/sid"/><xsl:text disable-output-escaping="yes"><![CDATA[\">Frameset laden</a> | ");
	}
//-->]]></xsl:text>
</script>
<xsl:if test="config/logedin=1">
	 <a href="pxmboard.php?mode=privatemessageform&amp;brdid={config/board/id}&amp;msgid={msg/id}&amp;toid={msg/user/id}{config/sid}" target="_blank" onclick="window.open(this,'myboard','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">private nachricht schreiben</a> |
<xsl:if test="msg/user/id=config/user/id or config/admin=1 or config/moderator=1">
	<a href="pxmboard.php?mode=messagenotification&amp;brdid={config/board/id}&amp;msgid={msg/id}{config/sid}">mailbenachrichtigung
	<xsl:choose>
	<xsl:when test="msg/notification=1"> deaktivieren</xsl:when>
	<xsl:otherwise> aktivieren</xsl:otherwise>
	</xsl:choose>
	</a> |
</xsl:if>
<xsl:if test="config/admin=1 or config/moderator=1 or config/edit=1">
	 <a href="pxmboard.php?mode=messageeditform&amp;brdid={config/board/id}&amp;msgid={msg/id}{config/sid}">nachricht editieren</a> |
</xsl:if>
</xsl:if>
 <a href="pxmboard.php?mode=messageform&amp;brdid={config/board/id}&amp;msgid={msg/id}{config/sid}">auf diese nachricht antworten</a> &gt;</td></tr>
</table>
</xsl:if>
</body>
</html>
</xsl:template>
</xsl:stylesheet>