<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: reply =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/edit.js"><xsl:comment/></script>
</head>
<body>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="messagesave"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
<input name="msgid" type="hidden" value="{msg/id}"/>
<table border="0" cellspacing="2" cellpadding="2" width="900">
<tr class="bg1">
<td colspan="2" align="center" id="norm"><font size="2">
<xsl:choose>
	<xsl:when test="msg/id>0">
	<b> Antwort auf den Beitrag "<xsl:value-of select="msg/subject"/>" posten:</b>
	</xsl:when>
	<xsl:otherwise>
	<b> Neuen Thread erstellen:</b>
	</xsl:otherwise>
</xsl:choose>
</font></td>
</tr>
<xsl:for-each select="error">
	<tr class="bg1">
		<td id="norm" align="right">fehler <xsl:value-of select="id"/></td>
		<td id="norm"><xsl:value-of select="text"/></td>
	</tr>
</xsl:for-each>
<xsl:if test="config/logedin!=1 and (config/guestpost or config/quickpost)">
<tr class="bg1">
	<td id="norm">nickname</td>
	<td id="input"><input name="nick" type="text" size="30" maxlength="30" tabindex="30001"/></td>
</tr>
	<xsl:if test="config/quickpost">
<tr class="bg1">
	<td id="norm">passwort</td>
	<td id="input"><input name="pass" type="password" size="20" maxlength="20" tabindex="30002"/></td>
</tr>
	</xsl:if>
	<xsl:if test="config/guestpost">
<tr class="bg1">
	<td id="norm">email</td>
	<td id="input"><input name="pubemail" type="text" size="61" maxlength="100" tabindex="30003"/></td>
</tr>
	</xsl:if>
</xsl:if>

<xsl:choose>
	<xsl:when test="pmsg">
	<tr class="bg2">
		<td id="norm" colspan="2">Thema: <b><xsl:value-of select="pmsg/subject"/></b><input name="subject" type="hidden" value="{msg/subject}"/></td>
	</tr>
	<tr class="bg2">
		<td colspan="2" id="norm"><xsl:value-of select="pmsg/body" disable-output-escaping="yes"/><xsl:text disable-output-escaping="yes"><![CDATA[<input name="body" type="hidden" value="]]></xsl:text><xsl:value-of select="msg/body" disable-output-escaping="yes"/><xsl:text disable-output-escaping="yes"><![CDATA["/>]]></xsl:text></td>
	</tr>
	<tr class="bg1">
		<td colspan="2" align="center" id="norm"><input type="checkbox" name="notification" value="1" tabindex="30006"/> mailbenachrichtigung? <input type="submit" value="abschicken" tabindex="30004"/> <input type="submit" name="edit_x" value="editieren" tabindex="30005"/></td>
	</tr>
	</xsl:when>

	<xsl:otherwise>
	<tr class="bg1">
		<td id="norm">titel</td>
		<td id="input"><input name="subject" type="text" size="61" maxlength="56" value="{msg/subject}" tabindex="30004"/></td>
	</tr>
	<tr class="bg2" valign="top">
		<td id="norm">nachricht</td>
		<td id="input"><textarea cols="95" rows="12" name="body" wrap="physical" tabindex="30005"><xsl:value-of select="msg/body" disable-output-escaping="yes"/></textarea></td>
	</tr>
	<tr class="bg1">
		<td><xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text></td>
		<td colspan="3" align="center" id="norm">
		<table border="0" width="90%">
		<tr>
		<td>
		<script type="text/javascript">
		<xsl:text disable-output-escaping="yes"><![CDATA[<!--
			if(isSelectionSupported()) {
				document.write("<input type=\"button\" value=\" b \" onclick=\"formatText('b')\" tabindex=\"30008\"> <input type=\"button\" value=\" i \" onclick=\"formatText('i')\" tabindex=\"30009\"> <input type=\"button\" value=\" u \" onclick=\"formatText('u')\" tabindex=\"30010\"> <input type=\"button\" value=\" s \" onclick=\"formatText('s')\" tabindex=\"30011\"> <input type=\"button\" value=\"link\" onclick=\"createLink()\" tabindex=\"30012\"> <input type=\"button\" value=\"img\" onclick=\"createImgLink()\" tabindex=\"30013\">");
			}
		//-->]]></xsl:text>
		</script>
		</td>
		<td align="right"><input type="checkbox" name="notification" value="1" tabindex="30014"/> mailbenachrichtigung? <input type="submit" value="abschicken" tabindex="30006"/> <input type="submit" name="preview_x" value="preview" tabindex="30007"/></td>
		</tr>
		</table></td>
	</tr>
	</xsl:otherwise>
</xsl:choose>
</table>
</form>
</body>
</html>
</xsl:template>
</xsl:stylesheet>