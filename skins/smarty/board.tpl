<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript">
		window.name = "unnamed";
	</script>
</head>
<frameset rows="{$config.skin.frame_top}%,*,{$config.skin.frame_bottom}%" border="1" framespacing="0" frameborder="1">
	<frame src="pxmboard.php?mode=threadlist&brdid={$config.board.id}{$config.sid}" name="top"/>
	<frame src="pxmboard.php?mode=thread&brdid={$config.board.id}&thrdid={$config.thrdid}{$config.sid}#p{$config.msgid}" name="middle"/>
	<frame src="pxmboard.php?mode=message&brdid={$config.board.id}&msgid={$config.msgid}{$config.sid}" name="bottom"/>
</frameset>
<body>
</body>
</html>