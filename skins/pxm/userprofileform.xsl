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
<form enctype="multipart/form-data" action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="userprofilesave"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}">userprofil</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{config/sid}">passwort</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{config/sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: userprofil für <xsl:value-of select="user/nickname"/></td>
</tr>
<tr class="bg2">
	<td id="norm">vorname</td><td id="input"><input type="text" name="fname" value="{user/fname}" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">nachname</td><td id="input"><input type="text" name="lname" value="{user/lname}" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">wohnort</td><td id="input"><input type="text" name="city" value="{user/city}" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">email</td><td id="input"><input type="text" name="email" value="{user/email}" size="30" maxlength="100"/></td>
</tr>
<tr class="bg2">
	<td id="norm">icq</td><td id="input"><input type="text" name="icq" value="{user/icq}" size="30" maxlength="10"/></td>
</tr>
<tr class="bg2">
	<td id="norm">homepage</td><td id="input"><input type="text" name="url" value="{user/url}" size="30" maxlength="50"/></td>
</tr>
<tr class="bg2">
	<td id="norm">hobbys</td><td id="input"><textarea cols="29" rows="5" name="hobby" wrap="physical"><xsl:value-of select="user/hobby"/></textarea></td>
</tr>
<tr class="bg2">
	<td id="norm">signatur</td><td id="input"><textarea cols="29" rows="3" name="signature" wrap="physical"><xsl:value-of select="user/signature"/></textarea></td>
</tr>
<tr class="bg2" valign="top">
	<td id="norm">bild</td><td id="input"><input type="file" name="pic" size="18" maxlength="150"/><br/><input type="checkbox" name="delpic" value="1"/> bild löschen?</td>
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