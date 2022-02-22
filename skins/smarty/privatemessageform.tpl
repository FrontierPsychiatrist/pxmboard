<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: private nachricht =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/edit.js"></script>
</head>
<body>
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="privatemessagesave"/>
<input type="hidden" name="toid" value="{$touser.id}"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
{if $config.type == 'outbox'}
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=inbox{$config.sid}">inbox</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=outbox{$config.sid}">outbox</a></td>
{else}
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=inbox{$config.sid}">inbox</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=privatemessagelist&type=outbox{$config.sid}">outbox</a></td>
{/if}
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
{foreach from=$error item=errormsg}
	<tr class="bg1">
		<td id="norm" align="right">fehler {$errormsg.id}</td>
		<td colspan="3" id="norm">{$errormsg.text}</td>
	</tr>
{/foreach}
<tr class="bg1">
	<td id="header" colspan="2" align="center">board: private nachricht für {$touser.nickname}</td>
</tr>
<tr class="bg2">
	<td id="norm">thema</td><td id="input"><input type="text" size="28" maxlength="50" name="subject" value="{$msg.subject}" tabindex="30001"/></td>
</tr>
<tr class="bg2" valign="top">
	<td id="norm">nachricht</td><td id="input"><textarea cols="27" rows="12" name="body" wrap="physical" tabindex="30002">{$msg._body}</textarea></td>
</tr>
<tr class="bg1">
	<td>&nbsp;</td>
	<td align="center" id="norm">
	<table border="0" width="90%">
	<tr>
	<script type="text/javascript">
	<!--
		if(isSelectionSupported()) document.write("<td><input type=\"button\" value=\" b \" onclick=\"formatText('b')\" tabindex=\"30004\"> <input type=\"button\" value=\" i \" onclick=\"formatText('i')\" tabindex=\"30005\"> <input type=\"button\" value=\" u \" onclick=\"formatText('u')\" tabindex=\"30006\"> <input type=\"button\" value=\" s \" onclick=\"formatText('s')\" tabindex=\"30007\"> <input type=\"button\" value=\"link\" onclick=\"createLink()\" tabindex=\"30008\"> <input type=\"button\" value=\"img\" onclick=\"createImgLink()\" tabindex=\"30009\"></td>");
	//-->
	</script>
	<td align="right"><input type="submit" value="abschicken" tabindex="30003"></td>
	</tr>
	</table></td>
</tr>
</table>
</form>
</body>
</html>