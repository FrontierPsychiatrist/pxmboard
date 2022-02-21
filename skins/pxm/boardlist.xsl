<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"/>
   	<meta http-equiv="Pragma" content="no-cache"/>
   	<meta http-equiv="expires" content="0"/>
	<title>-= board: index =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/pxm.js"><xsl:comment/></script>
	<xsl:if test="config/admin=1">
	<script type="text/javascript">
	<xsl:comment>
		function boardstate(brdid){
			result = confirm("Soll der Status des Forums "+boardid+" geaendert werden?");
			if(result == true) location.href="pxmboard.php?brdid=<xsl:value-of select="config/board/id"/><xsl:value-of select="config/sid"/><![CDATA[&mode=boardchangestatus&id="+brdid;]]>
		}
	</xsl:comment>
	</script>
	</xsl:if>
</head>
<body>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header"><img src="images/logo.gif" width="250" height="80"/></th><th id="header" colspan="2"><xsl:value-of disable-output-escaping="yes" select="config/banner/code"/></th>
</tr>
<tr class="bg2" align="center" valign="middle">
<xsl:choose>
	<xsl:when test="config/logedin=1">
		<td id="norm">Herzlich Willkommen <xsl:value-of select="config/user/nickname"/>.<br/>Du hast <a href="pxmboard.php?mode=privatemessagelist{config/sid}" target="_blank" onclick="window.open(this,'pxm_mailbox','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;"><xsl:value-of select="config/user/newprivmsgs"/> neue private Nachricht(en)</a></td>
		<td id="norm">Neuestes Mitglied im Forum: <a href="pxmboard.php?mode=userprofile&amp;usrid={newestmember/user/id}{config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="newestmember/user/nickname"/></a></td>
		<td id="norm"><a href="pxmboard.php?mode=logout{config/sid}" target="_parent">logout</a></td>
	</xsl:when>
	<xsl:otherwise>
		<td id="norm">Neuestes Mitglied im Forum: <a href="pxmboard.php?mode=userprofile&amp;usrid={newestmember/user/id}{config/sid}" target="_blank" onclick="openProfile(this);return false;"><xsl:value-of select="newestmember/user/nickname"/></a></td>
		<td id="norm"><a href="pxmboard.php?mode=usersendpwd{config/sid}">passwort zusenden</a></td>
		<td id="norm"><a href="pxmboard.php?mode=userregistration{config/sid}">registrieren</a></td>
	</xsl:otherwise>
</xsl:choose>
</tr>
</table>
<br/>
<table border="0" cellpadding="5" cellspacing="2" width="900">
<tr class="bg1">
	<th id="header">st</th><th id="header">name</th><th id="header">thema</th><th id="header">letzte nachricht</th><th id="header">moderator(en)</th>
<xsl:if test="config/admin=1">
	<th id="header">admin</th>
</xsl:if>
</tr>
	<xsl:apply-templates select="boards/board"/>
<xsl:if test="config/admin=1">
	<tr class="bg1"><td id="norm" align="center" colspan="6">&lt; <a href="pxmboard.php?mode=admboardform{config/sid}" target="admin">board hinzufügen</a> | <a href="pxmboard.php?mode=admframe{config/sid}" target="admin">weitere Funktionen</a> ></td></tr>
</xsl:if>
<xsl:if test="config/logedin=0">
<form action="pxmboard.php" method="post">
<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
<input type="hidden" name="mode" value="login"/>
<input type="hidden" name="brdid" value="{config/board/id}"/>
	<tr class="bg1"><td id="norm" colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr valign="top"><td id="norm">nickname</td><td id="input"><input type="text" name="nick" size="30" maxlength="30"/></td>
																	     <td id="norm">passwort</td><td id="input"><input type="password" name="pass" size="20" maxlength="20"/></td>
																	     <td align="center" id="norm"><input type="submit" value="login"/></td></tr></table></td></tr>
</form>
<xsl:for-each select="error">
	<tr class="bg1"><td id="norm" align="center" colspan="5">fehler <xsl:value-of select="id"/>: <xsl:value-of select="text"/></td></tr>
</xsl:for-each>
</xsl:if>
<tr><td id="norm" align="center" colspan="5"><xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text></td></tr>
<tr class="bg1"><th id="header" colspan="5">neueste beiträge</th></tr>
<xsl:apply-templates select="newestmessages/msg"/>
<tr><td id="norm" align="center" colspan="5"><br/>powered by <a href="http://www.pxmboard.de" target="_blank">pxmboard</a></td></tr>
</table>
</body>
</html>
</xsl:template>

<xsl:template match="board">
<tr class="bg2" valign="top">
<xsl:choose>
<xsl:when test="active=1">
	<td id="norm">
	<xsl:choose>
	<xsl:when test="/pxmboard/config/admin=1">
		<a href="#" onclick="boardstate({id});return false;"><img src="images/open.gif" border="0" width="15" height="15"/></a>
	</xsl:when>
	<xsl:otherwise>
		<img src="images/open.gif" width="15" height="15"/>
	</xsl:otherwise>
	</xsl:choose>
	</td><td id="norm"><a href="pxmboard.php?mode=board&amp;brdid={id}{/pxmboard/config/sid}"><xsl:value-of select="name"/></a></td>
</xsl:when>
<xsl:otherwise>
	<td id="norm">
	<xsl:choose>
	<xsl:when test="/pxmboard/config/admin=1">
		<a href="#" onclick="boardstate({id});return false;"><img src="images/closed.gif" border="0" width="15" height="15"/></a>
	</xsl:when>
	<xsl:otherwise>
		<img src="images/closed.gif" width="15" height="15"/>
	</xsl:otherwise>
	</xsl:choose>
	</td><td id="norm"><xsl:value-of select="name"/></td>
</xsl:otherwise>
</xsl:choose>
<td id="norm"><xsl:value-of select="desc"/></td><td align="center" id="norm"><xsl:value-of select="lastmsg"/></td><td id="norm">
<xsl:for-each select="moderator">
<xsl:value-of select="nick"/><br/>
</xsl:for-each>
</td>
<xsl:if test="/pxmboard/config/admin=1">
	<td id="norm" align="center"><a href="pxmboard.php?mode=admboardform&amp;id={id}{/pxmboard/config/sid}" target="admin">edit</a></td>
</xsl:if>
</tr>
</xsl:template>

<xsl:template match="msg">
<tr class="bg2" valign="top">
	<td id="norm" colspan="5"><table width="100%">
		<tr>
			<td id="norm"><a href="pxmboard.php?mode=board&amp;brdid={thread/brdid}&amp;thrdid={thread/id}&amp;msgid={id}{/pxmboard/config/sid}"><xsl:value-of select="subject"/></a> von
			<xsl:choose>
			<xsl:when test="user/highlight=1">
				<span class="highlight"><xsl:value-of select="user/nickname"/></span>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="user/nickname"/>
			</xsl:otherwise>
			</xsl:choose> am <xsl:value-of select="date"/> Uhr</td>
		</tr>
	</table></td>
</tr>
</xsl:template>
</xsl:stylesheet>