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
 * holds the validation rules for string input
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:01:45 $
 * @version $Revision: 1.2 $
 */
class cStringValidations{

	var $m_arrStringLegth;					// max length for string input

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){

		$this->m_arrStringLegth = array("boardmode" 		=> 30,
										"boardname" 		=> 100,
										"boarddescription" 	=> 255,
										"sortmode" 			=> 20,
										"sortdirection" 	=> 4,
										"subject" 			=> 90,
										"body" 				=> 60000,
										"searchstring" 		=> 30,
										"nickname" 			=> 30,
										"password" 			=> 20,
										"email" 			=> 100,
										"city"				=> 30,
										"firstname" 		=> 30,
										"lastname" 			=> 30,
										"signature" 		=> 100,
										"character" 		=> 1,
										"key" 				=> 32,
										"dateformat" 		=> 30,
										"notification" 		=> 2000,
										"quotesubject" 		=> 10,
										"directory" 		=> 100,
										"banner" 			=> 60000,
										"dbattributename" 	=> 15,
										"badword" 			=> 20,
										"textsearch" 		=> 20,
										"textreplace" 		=> 255,
										"skinvalue" 		=> 255,
										"error" 			=> 255,
										"type"	 			=> 255,
										);
	}

	/**
	 * validate a boardmode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue boardmode
	 * @return string boardmode
	 */
	function validateBoardmode($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["boardmode"]);
	}

	/**
	 * validate a boardname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue boardname
	 * @return string boardname
	 */
	function validateBoardname($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["boardname"]);
	}

	/**
	 * validate a boarddescription
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue boarddescription
	 * @return string boarddescription
	 */
	function validateBoarddescription($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["boarddescription"]);
	}

	/**
	 * validate a sortmode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue sortmode
	 * @return string sortmode
	 */
	function validateSortmode($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["sortmode"]);
	}

	/**
	 * validate a sortdirection
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue sortdirection
	 * @return string sortdirection
	 */
	function validateSortdirection($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["sortdirection"]);
	}

	/**
	 * validate a subject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue subject
	 * @return string subject
	 */
	function validateSubject($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["subject"]);
	}

	/**
	 * validate a body
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue body
	 * @return string body
	 */
	function validateBody($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["body"]);
	}

	/**
	 * validate a searchstring
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue searchstring
	 * @return string searchstring
	 */
	function validateSearchstring($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["searchstring"]);
	}

	/**
	 * validate a nickname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue nickname
	 * @return string nickname
	 */
	function validateNickname($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["nickname"]);
	}

	/**
	 * validate a password
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue password
	 * @return string password
	 */
	function validatePassword($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["password"]);
	}

	/**
	 * validate a email
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue email
	 * @return string email
	 */
	function validateEmail($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["email"]);
	}

	/**
	 * validate a city
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue city
	 * @return string city
	 */
	function validateCity($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["city"]);
	}

	/**
	 * validate a firstname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue firstname
	 * @return string firstname
	 */
	function validateFirstname($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["firstname"]);
	}

	/**
	 * validate a lastname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue lastname
	 * @return string lastname
	 */
	function validateLastname($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["lastname"]);
	}

	/**
	 * validate a signature
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue signature
	 * @return string signature
	 */
	function validateSignature($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["signature"]);
	}

	/**
	 * validate a character
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue character
	 * @return string character
	 */
	function validateCharacter($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["character"]);
	}

	/**
	 * validate a key
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue key
	 * @return string key
	 */
	function validateKey($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["key"]);
	}

	/**
	 * validate a dateformat
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue dateformat
	 * @return string dateformat
	 */
	function validateDateformat($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["dateformat"]);
	}

	/**
	 * validate a notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue notification
	 * @return string notification
	 */
	function validateNotification($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["notification"]);
	}

	/**
	 * validate a quotesubject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue quotesubject
	 * @return string quotesubject
	 */
	function validateQuotesubject($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["quotesubject"]);
	}

	/**
	 * validate a directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue directory
	 * @return string directory
	 */
	function validateDirectory($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["directory"]);
	}

	/**
	 * validate a banner
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue banner
	 * @return string banner
	 */
	function validateBanner($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["banner"]);
	}

	/**
	 * validate a dbattributename
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue dbattributename
	 * @return string dbattributename
	 */
	function validateDbattributename($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["dbattributename"]);
	}

	/**
	 * validate a badword
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue badword
	 * @return string badword
	 */
	function validateBadword($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["badword"]);
	}

	/**
	 * validate a textsearch
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue textsearch
	 * @return string textsearch
	 */
	function validateTextsearch($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["textsearch"]);
	}

	/**
	 * validate a textreplace
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue textreplace
	 * @return string textreplace
	 */
	function validateTextreplace($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["textreplace"]);
	}

	/**
	 * validate a skinvalue
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue skinvalue
	 * @return string skinvalue
	 */
	function validateSkinvalue($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["skinvalue"]);
	}

	/**
	 * validate an error
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue error
	 * @return string error
	 */
	function validateError($sValue){
		return $this->_cutString($sValue,$this->m_arrStringLegth["error"]);
	}

	/**
	 * validate a type
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValue type
	 * @return string type
	 */
	function validateType($sValue){
		$sValue = $this->_cutString($sValue,$this->m_arrStringLegth["type"]);
		if(!preg_match("/^[a-zA-Z]+$/",$sValue)){
			$sValue = "";
		}
		return $sValue;
	}

	/**
	 * get the max length for a validation
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sValidationType validationtype
	 * @return string value
	 */
	function getLength($sValidationType){
		return $this->m_arrStringLegth[$sValidationType];
	}

	/**
	 * cut the string to the specified length
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sValue value
	 * @param integer $iLength length
	 * @return string value
	 */
	function _cutString($sValue,$iLength){
		if(($iLength>0) && (strlen($sValue)>$iLength)){
			$sValue = substr($sValue,0,$iLength);
		}
		return $sValue;
	}
}
?>