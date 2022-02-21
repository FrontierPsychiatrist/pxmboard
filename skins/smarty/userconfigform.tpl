<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>-= board: userconfig =-</title>
	<link rel="stylesheet" type="text/css" href="css/pxmboard.css"/>
</head>
<body>
<form action="pxmboard.php" method="post">
{$config._sidform}
<input type="hidden" name="mode" value="userconfigsave"/>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userprofileform{$config.sid}">userprofil</a></td>
	<td class="bg2" align="center" id="norm"><a href="pxmboard.php?mode=userchangepwd{$config.sid}">passwort</a></td>
	<td class="bg1" align="center" id="norm"><a href="pxmboard.php?mode=userconfigform{$config.sid}">einstellungen</a></td>
</tr>
</table>
<table cellspacing="2" cellpadding="5" border="0" width="480">
<tr class="bg1">
	<td colspan="2" align="center" id="header">board: userconfig für {$user.nickname}</td>
</tr>
<tr class="bg2">
	<td id="norm">skin</td><td id="input"><select name="skinid" size="1">
	<option value="0">default</option>
{foreach from=$skin item=skin}
	<option value="{$skin.id}"{if $skin.id == $user.skin} selected="selected"{/if}>{$skin.name}</option>
{/foreach}
	</select>
	</td>
</tr>
<tr class="bg2">
	<td id="norm">private mail adresse</td><td id="input"><input type="text" name="email" value="{$user.privatemail}" size="30" maxlength="100"/></td>
</tr>
<tr class="bg2">
	<td id="norm">oberes frame (in %)</td><td id="input"><input type="text" name="ft" value="{$user.ft}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">unteres frame (in %)</td><td id="input"><input type="text" name="fb" value="{$user.fb}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">sortiermodus</td><td id="input"><select name="sort" size="1">
		<option value=""{if $user.sort == ''} selected="selected"{/if}>default</option>
		<option value="thread"{if $user.sort == 'thread'} selected="selected"{/if}>thread</option>
		<option value="last"{if $user.sort == 'last'} selected="selected"{/if}>last reply</option>
	</select></td>
</tr>
<tr class="bg2">
	<td id="norm">timeoffset (in stunden)</td><td id="input"><input type="text" name="toff" value="{$user.toff}" size="2" maxlength="2"/></td>
</tr>
<tr class="bg2">
	<td id="norm">zeige bilder?</td>
	<td id="input"><input type="checkbox" name="pimg" value="1"{if $user.pimg == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg2">
	<td id="norm">grafische smilies?</td>
	<td id="input"><input type="checkbox" name="repl" value="1"{if $user.repl == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg2">
	<td id="norm">speichere zugangsdaten in cookie?</td>
	<td id="input"><input type="checkbox" name="cookie" value="1"{if $config.cookie == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg2">
	<td id="norm">sichtbar in who's online?</td>
	<td id="input"><input type="checkbox" name="visible" value="1"{if $user.visible == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg2">
	<td id="norm">benachrichtigung über neue private nachrichten per email?</td>
	<td id="input"><input type="checkbox" name="privnotification" value="1"{if $user.privnotification == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg2">
	<td id="norm">signaturen anzeigen?</td>
	<td id="input"><input type="checkbox" name="showsignatures" value="1"{if $user.showsignatures == 1} checked="checked"{/if}/></td>
</tr>
<tr class="bg1">
	<td id="norm" colspan="2" align="center"><input type="submit" value="abschicken"/></td>
</tr>
</table>
</form>
</body>
</html>