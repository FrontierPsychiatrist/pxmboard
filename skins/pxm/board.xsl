<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="iso-8859-1"/>
<xsl:template match="pxmboard">
<html>
<head>
	<title>-= board =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript">
	<xsl:comment>
	  window.name = "unnamed";
	</xsl:comment>
	</script>
</head>
<frameset rows="{config/skin/frame_top}%,*,{config/skin/frame_bottom}%" border="1" framespacing="0" frameborder="1">
	<frame src="pxmboard.php?mode=threadlist&amp;brdid={config/board/id}{config/sid}" name="top"/>
	<frame src="pxmboard.php?mode=thread&amp;brdid={config/board/id}&amp;thrdid={config/thrdid}{config/sid}#p{config/msgid}" name="middle"/>
	<frame src="pxmboard.php?mode=message&amp;brdid={config/board/id}&amp;msgid={config/msgid}{config/sid}" name="bottom"/>
</frameset>
<body>
</body>
</html>
</xsl:template>
</xsl:stylesheet>