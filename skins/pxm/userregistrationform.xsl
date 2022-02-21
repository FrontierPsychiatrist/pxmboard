<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: registration =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<center>
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="userregistration"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
<table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: registration</td>
</tr>
<tr class="bg2">
	<td id="norm">nickname</td><td id="input"><input type="text" name="nick" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">email</td><td id="input"><input type="text" name="email" vcard_name="vCard.Email" size="30" maxlength="100"/></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="header">zusätzliche informationen</td>
</tr>
<tr class="bg2">
	<td id="norm">vorname</td><td id="input"><input type="text" name="fname" vcard_name="vCard.FirstName" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">nachname</td><td id="input"><input type="text" name="lname" vcard_name="vCard.LastName" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">wohnort</td><td id="input"><input type="text" name="city" vcard_name="vCard.Home.City" size="30" maxlength="30"/></td>
</tr>
<tr class="bg2">
	<td id="norm">öffentliche email</td><td id="input"><input type="text" name="pubemail" size="30" maxlength="100"/></td>
</tr>
<tr class="bg2">
	<td id="norm">homepage</td><td id="input"><input type="text" name="url" vcard_name="vCard.Homepage" size="30" maxlength="50"/></td>
</tr>
<tr class="bg2">
	<td id="norm">icq</td><td id="input"><input type="text" name="icq" size="30" maxlength="10"/></td>
</tr>
<tr class="bg2">
	<td id="norm">hobbys</td><td id="input"><textarea cols="29" rows="5" name="hobby" wrap="physical"><xsl:comment/></textarea></td>
</tr>
<tr class="bg2">
	<td id="norm">signatur</td><td id="input"><textarea cols="29" rows="3" name="signature" wrap="physical"><xsl:comment/></textarea></td>
</tr>
<tr class="bg1">
	<td colspan="2" align="center" id="norm"><input type="submit" value="absenden"/></td>
</tr>
</table>
</form>
</center>
</body>
</html>
</xsl:template>
</xsl:stylesheet>