<?php
/**
 * PXMBoard Forum software
 * Copyright (C) 2001 by Torsten Rentsch <forum@torsten-rentsch.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */

/**
 * pxmboard mainfile
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/04/09 23:59:13 $
 * @version $Revision: 1.12 $
 */

// disable E_STRICT errors for PHP5 compatibility
if(defined('E_STRICT')) {
    error_reporting(error_reporting()&~E_STRICT);
}

define("INCLUDEDIR","include");

if(file_exists("pxmboard-config.php")){
	include_once("pxmboard-config.php");
}
else{
	die("This board is not properly installed!<br />\nrun install.php first");
}

require_once(INCLUDEDIR."/dblayer/cDBFactory.php");
require_once(INCLUDEDIR."/cInputHandler.php");
require_once(INCLUDEDIR."/cConfig.php");
require_once(INCLUDEDIR."/cBoard.php");
require_once(INCLUDEDIR."/cError.php");

set_magic_quotes_runtime(0);

// initialise random number generator
list($iMicroSeconds,$iSeconds) = explode(" ",microtime());
mt_srand((float)$iSeconds+((float)$iMicroSeconds*100000));

// establish db connection
// global access to dbconnection as emulation for a singleton
if(!$objDb = &cDBFactory::getDBObject($arrDatabase["type"])){
	die("invalid db driver");
}
if(!$objDb->connectDBServer($arrDatabase["host"],$arrDatabase["user"],$arrDatabase["pass"],$arrDatabase["name"])){
	die("couldn't connect to server");
}

$objInputHandler = new cInputHandler();

// try to initialise the active board
$objActiveBoard = new cBoard();
if(!$objActiveBoard->loadDataById($objInputHandler->getIntFormVar("brdid",TRUE,TRUE,TRUE))){
	$objActiveBoard = NULL;
}

// load general configuration
$objConfig = new cConfig($objActiveBoard,$arrTemplateTypes);

// switch board modes
$sBoardMode = $objInputHandler->getStringFormVar("mode","boardmode",TRUE,TRUE,"trim");

$sPath = "";
$bAdmin = FALSE;
$arrBoardMode = array();
if(preg_match("/^(adm)?([a-zA-Z]+)$/",$sBoardMode,$arrBoardMode)){
	if(!empty($arrBoardMode[1])){
		$sClassName = "cAdminAction";
		$sPath = "admin/";
		$bAdmin = TRUE;
	}
	else{
		$sClassName = "cAction";
	}
	$sClassName .= ucfirst(strtolower($arrBoardMode[2]));
}
else{
	$sClassName = "cActionLogin";							// default mode
}

// include action class and instantiate object
if(file_exists(INCLUDEDIR."/actions/$sPath$sClassName.php")){
	include_once(INCLUDEDIR."/actions/$sPath$sClassName.php");
	$objAction = new $sClassName($objConfig);
}
else{														// invalid action -> show error
	include_once(INCLUDEDIR."/actions/cActionError.php");
	$objAction = new cActionError($objConfig);
	$bAdmin = FALSE;
}

// login the user by ticket
$objAction->loginUserByTicket();
// execute the pre-actions
$objAction->doPreActions();
// initialize the skin
if(!$bAdmin && !$objConfig->initSkin()){
	die("no valid template found");
}
// do the action
$objAction->performAction();
// execute the post-actions
$objAction->doPostActions();

// close the session before parsing the template to unlock the sessionfile
$objSesion = &$objConfig->getSession();
$objSesion->writeCloseSession();

// output the result
echo $objAction->getOutput();
?>