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
 * session and cookie support
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/08/04 18:21:52 $
 * @version $Revision: 1.9 $
 */
class cSession{
	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSessionName name of the session / cookie name
	 * @return void
	 */
	function __construct($sSessionName){
		session_name($sSessionName);

		// security: bind this session to this instance of the script!!!
		$arrSessionParams = session_get_cookie_params();
		session_set_cookie_params($arrSessionParams["lifetime"],
								  $_SERVER["PHP_SELF"],
								  $arrSessionParams["domain"],
								  $arrSessionParams["secure"]);
	}

	/**
	 * is session data available?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean session data available / not available
	 */
	function sessionDataAvailable(){
		return (isset($_COOKIE[session_name()]) || isset($_POST[session_name()]) || isset($_GET[session_name()]));
	}

	/**
	 * start the session
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function startSession(){
		@session_start();
		// security: don't allow sessions to be used in different board instances!!!
		$sScript = $this->getSessionVar("script");
		if (strcasecmp($sScript, $_SERVER["PHP_SELF"]) != 0) {
			$_SESSION = array();
			$this->setSessionVar("script", $_SERVER["PHP_SELF"]);
		}
	}

	/**
	 * get the session id<br>(only available if cookies are disabled)
	 *
 	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string session id
	 * @see cSession::getSessionId()
	 */
	function getSid(){
		return defined("SID")?SID:"";
	}

	/**
	 * get the session id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string session id
	 * @see cSession::getSid()
	 */
	function getSessionId(){
		return session_id();
	}

	/**
	 * get the session name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string session name
	 */
	function getSessionName(){
		return session_name();
	}

	/**
	 * store the session data and end the session
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function writeCloseSession(){
		session_write_close();
	}

	/**
	 * destroy the session
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function destroySession(){
		$_SESSION = array();
		return @session_destroy();
	}

	/**
	 * get the value for a session variable
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @return mixed value of the variable
	 */
	function &getSessionVar($sVarName){
		$mValue = NULL;
		if(isset($_SESSION[$sVarName])){
			$mValue = $_SESSION[$sVarName];
		}
		return $mValue;
	}

	/**
	 * set the value of a session variable
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @param mixed $mVarValue value of the variable
	 * @return void
	 */
	function setSessionVar($sVarName,$mVarValue){
		if($mVarValue !== NULL){
			$_SESSION[$sVarName] = $mVarValue;
		}
		else{
			unset($_SESSION[$sVarName]);
		}
	}

	/**
	 * get the value of a cookie
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @return string value of the variable
	 */
	function &getCookieVar($sVarName){
		$sValue = "";
		if (isset($_COOKIE[$sVarName])) $sValue = $_COOKIE[$sVarName];

		return $sValue;
	}

	/**
	 * set a cookie
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @param string $sVarValue value of the variable
	 * @param integer $iExpireDate when expires the cookie? (unix timestamp)
	 * @return void
	 */
	function setCookieVar($sVarName,$sVarValue,$iExpireDate){
		if(strlen($sVarValue)>0){
			setcookie($sVarName,$sVarValue,$iExpireDate);
		}
		else setcookie($sVarName,"",$iExpireDate);
	}
}
?>