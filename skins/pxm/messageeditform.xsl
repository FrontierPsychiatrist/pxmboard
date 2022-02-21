<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: edit =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/edit.js"><xsl:comment/></script>
</head>
<body>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="messageeditsave"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
<input name="msgid" type="hidden" value="{msg/id}"/>
<table border="0" cellspacing="2" cellpadding="2" width="900">
<xsl:for-each select="error">
	<tr class="bg1">
		<td id="norm">fehler <xsl:value-of select="id"/></td>
		<td id="norm"><xsl:value-of select="text"/></td>
	</tr>
</xsl:for-each>
<tr class="bg1">
	<td id="norm" align="right">titel</td>
	<td id="input"><input name="subject" type="text" size="61" maxlength="56" value="{msg/subject}" tabindex="30001"/></td>
</tr>
<tr class="bg2" valign="top">
	<td id="norm" align="right">nachricht</td>
	<td id="input"><textarea cols="95" rows="12" name="body" wrap="physical" tabindex="30002"><xsl:value-of select="msg/body" disable-output-escaping="yes"/></textarea></td>
</tr>
<tr class="bg1">
	<td><xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text></td>
	<td align="center" id="norm">
	<table border="0" width="90%">
	<tr>
	<script type="text/javascript">
	<xsl:text disable-output-escaping="yes"><![CDATA[<!--
		if(isSelectionSupported()) document.write("<td><input type=\"button\" value=\" b \" onclick=\"formatText('b')\" tabindex=\"30004\"> <input type=\"button\" value=\" i \" onclick=\"formatText('i')\" tabindex=\"30005\"> <input type=\"button\" value=\" u \" onclick=\"formatText('u')\" tabindex=\"30006\"> <input type=\"button\" value=\" s \" onclick=\"formatText('s')\" tabindex=\"30007\"> <input type=\"button\" value=\"link\" onclick=\"createLink()\" tabindex=\"30008\"> <input type=\"button\" value=\"img\" onclick=\"createImgLink()\" tabindex=\"30009\"></td>");
	//-->]]></xsl:text>
	</script>
	<td align="right"><input type="submit" value="abschicken" tabindex="30003"/></td>
	</tr>
	</table></td>
</tr>
</table>
</form>
</body>
</html>
</xsl:template>
</xsl:stylesheet>