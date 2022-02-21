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
	<script type="text/javascript">
	<xsl:comment>
		function ld(trd,msg){
			if (parent.middle) {
				var location = "pxmboard.php?mode=thread&amp;brdid=<xsl:value-of select="config/board/id"/>&amp;thrdid="+trd+"<xsl:value-of select="config/sid"/>";
				if(msg>0) location = location + "#p"+msg;
				parent.middle.location.href = location;
			}
		}
	</xsl:comment>
	</script>
</head>
<body>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header" colspan="2"><img src="images/logo.gif" width="250" height="80"/></th><th id="header" colspan="6"><xsl:value-of disable-output-escaping="yes" select="config/banner/code"/></th>
</tr>
<tr class="bg2" align="center" valign="middle">
	<form action="pxmboard.php" method="get">
	<xsl:value-of disable-output-escaping="yes" select="config/sidform"/>
	<input type="hidden" name="mode" value="threadlist"/>
	<td id="norm"><select name="brdid" size="1">
<xsl:for-each select="boards/board">
<xsl:if test="active=1">
<xsl:choose>
	<xsl:when test="/pxmboard/config/board/id=id">
		<option value="{id}" selected="selected"><xsl:value-of select="name"/></option>
	</xsl:when>
	<xsl:otherwise>
		<option value="{id}"><xsl:value-of select="name"/></option>
	</xsl:otherwise>
</xsl:choose>
</xsl:if>
</xsl:for-each>
	</select>
	<select name="date" size="1">
		<xsl:choose>
			<xsl:when test="config/timespan=1"><option value="1" selected="selected">1 tag</option></xsl:when>
			<xsl:otherwise><option value="1">1 tag</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=7"><option value="7" selected="selected">7 tage</option></xsl:when>
			<xsl:otherwise><option value="7">7 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=14"><option value="14" selected="selected">14 tage</option></xsl:when>
			<xsl:otherwise><option value="14">14 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=30"><option value="30" selected="selected">30 tage</option></xsl:when>
			<xsl:otherwise><option value="30">30 tage</option></xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=365"><option value="365" selected="selected">1 jahr</option></xsl:when>
			<xsl:otherwise><option value="365">1 jahr</option></xsl:otherwise>
		</xsl:choose>
	</select>
	<input type="image" src="images/go.gif" border="0"/></td></form>
	<td id="norm"><a href="pxmboard.php?mode=useronline&amp;brdid={config/board/id}{config/sid}" target="bottom">who's online</a></td>
	<td id="norm"><a href="pxmboard.php?mode=usersearch&amp;brdid={config/board/id}{config/sid}" target="bottom">user</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messagesearch&amp;brdid={config/board/id}{config/sid}">suche</a></td>
	<td id="norm"><a href="pxmboard.php?mode=messageform&amp;brdid={config/board/id}{config/sid}" target="bottom">thread erstellen</a></td>
<xsl:choose>
	<xsl:when test="config/logedin=1">
		<td id="norm"><a href="pxmboard.php?mode=privatemessagelist{config/sid}" target="_blank" onclick="window.open(this,'pxm_mailbox','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">mailbox</a></td>
		<td id="norm"><a href="pxmboard.php?mode=userprofileform{config/sid}" target="_blank" onclick="window.open(this,'pxm_setup','width=500,height=600,scrolling=auto,scrollbars=1,resizable=1');return false;">setup</a></td>
		<td id="norm"><a href="pxmboard.php?mode=logout{config/sid}" target="_parent">logout</a></td>
	</xsl:when>
	<xsl:otherwise>
		<td id="norm"><a href="pxmboard.php?mode=login{config/sid}" target="_parent">login</a></td>
		<td id="norm" colspan="2"><a href="pxmboard.php?mode=userregistration{config/sid}" target="bottom">registrieren</a></td>
	</xsl:otherwise>
</xsl:choose>
</tr>
</table>
<br/>
<table cellspacing="1" cellpadding="5" border="0" width="900">
<tr class="bg1">
	<th id="header">st</th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=subject{config/sid}">thema</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=nickname{config/sid}">autor</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=thread{config/sid}">datum</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=views{config/sid}">view</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=replies{config/sid}">#</a></th>
	<th id="header"><a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort=last{config/sid}">letzter beitrag</a></th>
</tr>
	<xsl:apply-templates select="thread"/>

<tr class="bg1">
<td align="center" colspan="7" id="norm">
<xsl:choose>
	<xsl:when test="not(config/previd='')">
		<a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort={config/sort}&amp;page={config/previd}{config/sid}">prev</a> |
	</xsl:when>
	<xsl:otherwise>
		- |
	</xsl:otherwise>
</xsl:choose>
<xsl:choose>
	<xsl:when test="not(config/nextid='')">
		<a href="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}&amp;date={config/timespan}&amp;sort={config/sort}&amp;page={config/nextid}{config/sid}">next</a>
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

<xsl:template match="thread">
<tr class="bg2">
<xsl:choose>
<xsl:when test="fixed=1">
	<td align="center"><img src="images/fixed.gif" width="15" height="15"/></td>
</xsl:when>
<xsl:when test="active=1">
	<td align="center"><img src="images/open.gif" width="15" height="15"/></td>
</xsl:when>
<xsl:otherwise>
	<td align="center"><img src="images/closed.gif" width="15" height="15"/></td>
</xsl:otherwise>
</xsl:choose>
<td id="norm"><a href="pxmboard.php?mode=message&amp;brdid={/pxmboard/config/board/id}&amp;msgid={id}{/pxmboard/config/sid}" target="bottom" onclick="ld({threadid},0)"><xsl:value-of select="subject"/></a></td><td id="norm">
<xsl:choose>
	<xsl:when test="user/id>0">
	<a href="pxmboard.php?mode=userprofile&amp;usrid={user/id}{config/sid}" target="_blank" onclick="openProfile(this);return false;">
	<xsl:choose>
		<xsl:when test="user/highlight=1">
			<span class="highlight"><xsl:value-of select="user/nickname"/></span>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="user/nickname"/>
		</xsl:otherwise>
	</xsl:choose>
	</a>
	</xsl:when>
	<xsl:otherwise>
	<xsl:choose>
		<xsl:when test="user/highlight=1">
			<span class="highlight"><xsl:value-of select="user/nickname"/></span>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="user/nickname"/>
		</xsl:otherwise>
	</xsl:choose>
	</xsl:otherwise>
	</xsl:choose>
</td>
<td align="right" id="norm"><xsl:value-of select="date"/></td>
<td align="right" id="norm"><xsl:value-of select="views"/></td>
<td align="center" id="norm"><a href="pxmboard.php?brdid={/pxmboard/config/board/id}&amp;mode=thread&amp;thrdid={threadid}{config/sid}" target="middle"><xsl:value-of select="msgquan"/></a></td>
<td align="right" id="norm"><a href="pxmboard.php?mode=message&amp;brdid={/pxmboard/config/board/id}&amp;msgid={lastid}{config/sid}" target="bottom" onclick="ld({threadid},{lastid})"><xsl:value-of select="lastdate"/></a></td>
</tr>
</xsl:template>
</xsl:stylesheet>