<?php
define("INCLUDEDIR","../include");
require_once(INCLUDEDIR."/dblayer/cDBFactory.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
	body,td { font-family:arial,helvetica,sans-serif;}
	h3 { margin-top:4cm; }
	h4 { text-decoration:underline; }
	#e { color:#cc0000; }
	#h { background-color:#cccccc;font-weight:bold;text-align:center; }
	#c { background-color:#eeeeee; }
	a:link { text-decoration:none; }
	a:visited { text-decoration:none; }
	a:active { text-decoration:none; }
//-->
</style>
</head>
<body onload="window.focus()">
<center>
<h2>< PXMBoard: Installation ></h2>
<?php
if(file_exists("../pxmboard-config.php")){
	echo "Board already configured!<br><br>\nDelete \"pxmboard-config.php\" and run install again";
}
else{

$bShowForm = TRUE;
$arrErrors = array();
if(!empty($_POST)){

	if(empty($_POST["dbtype"])) 		$arrErrors[] = "Please select a DB type";
	if(empty($_POST["host"])) 			$arrErrors[] = "Please enter a DB hostname ";
	if(empty($_POST["dbusername"])) 	$arrErrors[] = "Please enter a DB username";
	if(empty($_POST["dbname"])) 		$arrErrors[] = "Please enter a DB name";
	if(empty($_POST["templatetype"])) 	$arrErrors[] = "Please select at least one template type";
	if(empty($_POST["username"])) 		$arrErrors[] = "Please enter the user name for the admin";
	if(empty($_POST["password"])) 		$arrErrors[] = "Please enter the password for the admin";

	if(empty($arrErrors)){
		if(initDb()){
			saveConfigFile();
			$bShowForm = FALSE;
		}
		else{
			$arrErrors[] = "Could not connect to database";
		}
	}
}
?>
<?php
foreach($arrErrors as $sError){
	echo $sError."<br>\n";
}
if(!$bShowForm){
	echo "Installation successful";
}
else{
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="1" id="c">
<tr><td colspan="2" id="h">Database Configuration</td></tr>
<tr><td rowspan="2">DB Type</td><td><input type="radio" name="dbtype" value="MySql" <?php echo getCheckboxStateString("mysqli")?>> MySql</td></tr>
<tr>							<td><input type="radio" name="dbtype" value="PostgreSql" <?php echo getCheckboxStateString("pgsql")?>> PostgreSql</td></tr>

<tr><td>host</td><td><input type="text" name="host" value="localhost"></td></tr>
<tr><td>username</td><td><input type="text" name="dbusername" value=""></td></tr>
<tr><td>password</td><td><input type="password" name="dbpassword" value=""></td></tr>
<tr><td>dbname</td><td><input type="dbname" name="dbname" value="pxmboard"></td></tr>
</table>
<br>
<br>
<table border="1" id="c">
<tr><td colspan="2" id="h">Template Configuration</td></tr>
<tr><td rowspan="3">Template Types</td>	<td><input type="checkbox" name="templatetype[]" value="Smarty" checked="checked"> Smarty</td></tr>
<tr>									<td><input type="checkbox" name="templatetype[]" value="Xslt" <?php echo getCheckboxStateString(array("domxml","xsl"))?>> Xslt</td></tr>
</table>
<br>
<br>
<table border="1" id="c">
<tr><td colspan="2" id="h">master user for the board</td></tr>
<tr><td>username</td><td><input type="text" name="username" value="Webmaster"></td></tr>
<tr><td>password</td><td><input type="password" name="password" value=""></td></tr>
</table>
<br>
<br>
<input type="submit"> <input type="reset">
</form>
<?php
}
}
?>
</center>
</body>
</html>

<?php
function getCheckboxStateString($mExtensionName){
	if(is_string($mExtensionName)){
		$mExtensionName = array($mExtensionName);
	}
	foreach($mExtensionName as $sExtensionName){
		if(extension_loaded($sExtensionName)) return "checked=\"checked\"";
	}
	return "disabled=\"disabled\"";
}

function initDb(){
	if(!$objDbConnection = &cDBFactory::getDBObject($_POST["dbtype"])){
		return false;
	}
	if(!$objDbConnection->connectDBServer($_POST["host"],$_POST["dbusername"],$_POST["dbpassword"],$_POST["dbname"])){
		return false;
	}
	$objDbConnection->executeQuery("INSERT INTO pxm_user (u_nickname,u_password,u_highlight,u_status,u_post,u_edit,u_admin) VALUES ('".addslashes($_POST["username"])."','".addslashes(md5($_POST["password"]))."',1,1,1,1,1)");
	$objDbConnection->disconnectDBServer();
	return true;
}

function saveConfigFile(){
	$fh = fopen("../pxmboard-config.php","w");

	fwrite($fh,"<?php\n");
	fwrite($fh,"\$arrDatabase[\"type\"] = \"".$_POST["dbtype"]."\";\t\t\t\t\t\t// db type ( MySql, PostgreSql (experimental) )\n");
	fwrite($fh,"\$arrDatabase[\"host\"] = \"".$_POST["host"]."\";\t\t\t\t\t// db hostname\n");
	fwrite($fh,"\$arrDatabase[\"user\"] = \"".$_POST["dbusername"]."\";\t\t\t\t\t\t// db username\n");
	fwrite($fh,"\$arrDatabase[\"pass\"] = \"".$_POST["dbpassword"]."\";\t\t\t\t\t\t// db password\n");
	fwrite($fh,"\$arrDatabase[\"name\"] = \"".$_POST["dbname"]."\";\t\t\t\t\t// db name\n\n");
	fwrite($fh,"\$arrTemplateTypes = array(\"".implode("\",\"",$_POST["templatetype"])."\");\t\t// template type ( Smarty, Xslt )\n");
	fwrite($fh,"?>");
	fclose($fh);
}
?>