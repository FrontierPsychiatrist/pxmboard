<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: userconfig =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="userconfigsave"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}">userprofil</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{config/sid}">passwort</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{config/sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: userconfig für <xsl:value-of select="user/nickname"/></td>
</tr>
<tr class="bg2">
	<td id="norm">skin</td><td id="input"><select name="skinid" size="1">
	<option value="0">default</option>
<xsl:for-each select="skin">
<xsl:choose>
	<xsl:when test="id=/pxmboard/user/skin">
		<option value="{id}" selected="selected"><xsl:value-of select="name"/></option>
	</xsl:when>
	<xsl:otherwise>
		<option value="{id}"><xsl:value-of select="name"/></option>
	</xsl:otherwise>
</xsl:choose>
</xsl:for-each>
	</select>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">private mail adresse</td><td id="input"><input type="text" name="email" value="{user/privatemail}" size="30" maxlength="100"/></td>
</tr>
<tr class="bg2">
	<td id="norm">oberes frame (in %)</td><td id="input"><input type="text" name="ft" value="{user/ft}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">unteres frame (in %)</td><td id="input"><input type="text" name="fb" value="{user/fb}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">sortiermodus</td><td id="input"><select name="sort" size="1">
	<xsl:choose>
	<xsl:when test="user/sort=''">
		<option value="" selected="selected">default</option>
	</xsl:when>
	<xsl:otherwise>
		<option value="">default</option>
	</xsl:otherwise>
	</xsl:choose>
	<xsl:choose>
	<xsl:when test="user/sort='thread'">
		<option value="thread" selected="selected">thread</option>
	</xsl:when>
	<xsl:otherwise>
		<option value="thread">thread</option>
	</xsl:otherwise>
	</xsl:choose>
	<xsl:choose>
	<xsl:when test="user/sort='last'">
		<option value="last" selected="selected">last reply</option>
	</xsl:when>
	<xsl:otherwise>
		<option value="last">last reply</option>
	</xsl:otherwise>
	</xsl:choose>
	</select></td>
</tr>
<tr class="bg2">
	<td id="norm">timeoffset (in stunden)</td><td id="input"><input type="text" name="toff" value="{user/toff}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">zeige bilder?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="user/pimg=1">
			<input type="checkbox" name="pimg" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="pimg" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">grafische smilies?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="user/repl=1">
			<input type="checkbox" name="repl" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="repl" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">speichere zugangsdaten in cookie?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="config/cookie=1">
			<input type="checkbox" name="cookie" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="cookie" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">sichtbar in who's online?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="user/visible=1">
			<input type="checkbox" name="visible" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="visible" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">benachrichtigung über neue private nachrichten per email?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="user/privnotification=1">
			<input type="checkbox" name="privnotification" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="privnotification" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">signaturen anzeigen?</td>
	<td id="input">
		<xsl:choose>
		<xsl:when test="user/showsignatures=1">
			<input type="checkbox" name="showsignatures" value="1" checked="checked"/>
		</xsl:when>
		<xsl:otherwise>
			<input type="checkbox" name="showsignatures" value="1"/>
		</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr class="bg1">
	<td id="norm" colspan="2" align="center"><input type="submit" value="abschicken"/></td>
</tr>
</table>
</form>
</body>
</html>
</xsl:template>
</xsl:stylesheet>