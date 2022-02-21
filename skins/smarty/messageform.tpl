<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: reply =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
	<script type="text/javascript" src="js/edit.js"></script>
</head>
<body>
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="messagesave"/>
<input type="hidden" name="brdid" value="{$config.board.id}"/>
<input name="msgid" type="hidden" value="{$msg.id}"/>
<table border="0" cellspacing="2" cellpadding="2" width="900">
<tr class="bg1">
<td colspan="2" align="center" id="norm"><font size="2">
{if $msg.id > 0}
	<b> Antwort auf den Beitrag "{$msg.subject}" posten:</b>
{else}
	<b> Neuen Thread erstellen:</b>
{/if}
</font></td>
</tr>
{foreach $error as $_error}
	<tr class="bg1">
		<td id="norm" align="right">fehler {$_error.id}</td>
		<td id="norm">{$_error.text}</td>
	</tr>
{/foreach}
{if $config.logedin != 1 && ($config.guestpost || $config.quickpost)}
<tr class="bg1">
	<td id="norm">nickname</td>
	<td id="input"><input name="nick" type="text" size="30" maxlength="30" tabindex="30001"/></td>
</tr>
	{if $config.quickpost}
<tr class="bg1">
	<td id="norm">passwort</td>
	<td id="input"><input name="pass" type="password" size="20" maxlength="20" tabindex="30002"/></td>
</tr>
	{/if}
	{if $config.guestpost}
<tr class="bg1">
	<td id="norm">email</td>
	<td id="input"><input name="pubemail" type="text" size="61" maxlength="100" tabindex="30003"/></td>
</tr>
	{/if}
{/if}

{if $pmsg}
	<tr class="bg2">
		<td id="norm" colspan="2">Thema: <b>{$pmsg.subject}</b><input name="subject" type="hidden" value="{$msg.subject}"/></td>
	</tr>
	<tr class="bg2">
		<td colspan="2" id="norm">{$pmsg._body}<input name="body" type="hidden" value="{$msg._body}"/></td>
	</tr>
	<tr class="bg1">
		<td colspan="2" align="center" id="norm"><input type="checkbox" name="notification" value="1"  tabindex="30006"/> mailbenachrichtigung? <input type="submit" value="abschicken" tabindex="30004"/> <input type="submit" name="edit_x" value="editieren" tabindex="30005"/></td>
	</tr>
{else}
	<tr class="bg1">
		<td id="norm">titel</td>
		<td id="input"><input name="subject" type="text" size="61" maxlength="56" value="{$msg.subject}" tabindex="30004"/></td>
	</tr>
	<tr class="bg2" valign="top">
		<td id="norm">nachricht</td>
		<td id="input"><textarea cols="95" rows="12" name="body" wrap="physical" tabindex="30005">{$msg._body}</textarea></td>
	</tr>
	<tr class="bg1">
	<td>&nbsp;</td>
	<td align="center" id="norm">
	<table border="0" width="90%">
	<tr>
	<td>
	<script type="text/javascript">
	{literal}
	<!--
		if(isSelectionSupported()) {
			document.write("<input type=\"button\" value=\" b \" onclick=\"formatText('b')\" tabindex=\"30008\"> <input type=\"button\" value=\" i \" onclick=\"formatText('i')\" tabindex=\"30009\"> <input type=\"button\" value=\" u \" onclick=\"formatText('u')\" tabindex=\"30010\"> <input type=\"button\" value=\" s \" onclick=\"formatText('s')\" tabindex=\"30011\"> <input type=\"button\" value=\"link\" onclick=\"createLink()\" tabindex=\"30012\"> <input type=\"button\" value=\"img\" onclick=\"createImgLink()\" tabindex=\"30013\">");
		}
	//-->
	{/literal}
	</script>
	</td>
	<td align="right"><input type="checkbox" name="notification" value="1" tabindex="30014"/> mailbenachrichtigung? <input type="submit" value="abschicken" tabindex="30006"> <input type="submit" name="preview_x" value="preview" tabindex="30007"></td>
	</tr>
	</table></td>
	</tr>
{/if}
</table>
</form>
</body>
</html>