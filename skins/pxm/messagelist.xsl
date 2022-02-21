<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board: gefundene nachrichten =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript">
	<xsl:comment>
	  function ld(brd,trd,msg){
	   	if (parent.middle) parent.middle.location.href="pxmboard.php?mode=thread&amp;brdid="+brd+"&amp;thrdid="+trd<xsl:value-of select="config/sid"/>+"#p"+msg;
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
			<xsl:when test="config/timespan=1">
				<option value="1" selected="selected">1 tag</option>
			</xsl:when>
			<xsl:otherwise>
				<option value="1">1 tag</option>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=7">
				<option value="7" selected="selected">7 tage</option>
			</xsl:when>
			<xsl:otherwise>
				<option value="7">7 tage</option>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=14">
				<option value="14" selected="selected">14 tage</option>
			</xsl:when>
			<xsl:otherwise>
				<option value="14">14 tage</option>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=30">
				<option value="30" selected="selected">30 tage</option>
			</xsl:when>
			<xsl:otherwise>
				<option value="30">30 tage</option>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="config/timespan=365">
				<option value="365" selected="selected">1 jahr</option>
			</xsl:when>
			<xsl:otherwise>
				<option value="365">1 jahr</option>
			</xsl:otherwise>
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
<tr><td colspan="8" align="center"><br/><table cellspacing="2" cellpadding="5" border="0">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board:  <xsl:value-of select="config/items"/> gefundene nachrichten</td>
</tr>
	<xsl:apply-templates select="msg"/>
<tr class="bg1">
	<td colspan="2" align="center" id="norm">
	<xsl:choose>
		<xsl:when test="config/previd!=''">
			<a href="pxmboard.php?mode=messagesearch&amp;brdid={config/board/id}&amp;searchid={config/searchprofile/id}&amp;page={config/previd}{config/sid}">prev</a> |
		</xsl:when>
		<xsl:otherwise>
			- |
		</xsl:otherwise>
	</xsl:choose>
	<xsl:if test="config/count > 0">
	<xsl:call-template name="page">
		<xsl:with-param name="x" select="1"/>
	</xsl:call-template>
	 |
	</xsl:if>
	<xsl:choose>
		<xsl:when test="config/nextid!=''">
			<a href="pxmboard.php?mode=messagesearch&amp;brdid={config/board/id}&amp;searchid={config/searchprofile/id}&amp;page={config/nextid}{config/sid}">next</a>
		</xsl:when>
		<xsl:otherwise>
			-
		</xsl:otherwise>
	</xsl:choose>
	</td>
</tr>
</table></td></tr></table>
</body>
</html>
</xsl:template>

<xsl:template match="msg">
<tr class="bg2">
	<td id="norm" colspan="2"><a href="pxmboard.php?mode=message&amp;brdid={boardid}&amp;msgid={id}{/pxmboard/config/sid}" target="bottom" onclick="ld({boardid},{threadid},{id})"><xsl:value-of select="subject"/></a> von
	<xsl:choose>
		<xsl:when test="user/highlight=1">
			<span class="highlight"><xsl:value-of select="user/nickname"/></span>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="user/nickname"/>
		</xsl:otherwise>
	</xsl:choose>
	am <xsl:value-of select="date"/>
	<xsl:if test="score>0">
		(Relevanz: <xsl:value-of select="score"/>)
	</xsl:if></td>
</tr>
</xsl:template>

<xsl:template name="page">
	<xsl:param name="x" select="x"/>
	<xsl:if test="/pxmboard/config/count >= $x">
		<xsl:text disable-output-escaping="yes"><![CDATA[&nbsp;]]></xsl:text>
		<xsl:choose>
		<xsl:when test="/pxmboard/config/curid = $x">
			<u><b><xsl:value-of select="$x"/></b></u>
		</xsl:when>
		<xsl:otherwise>
			<a href="pxmboard.php?mode=messagesearch&amp;brdid={/pxmboard/config/board/id}&amp;searchid={/pxmboard/config/searchprofile/id}&amp;page={$x}{/pxmboard/config/sid}"><xsl:value-of select="$x"/></a>
		</xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="page">
			<xsl:with-param name="x" select="$x + 1"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>
</xsl:stylesheet>