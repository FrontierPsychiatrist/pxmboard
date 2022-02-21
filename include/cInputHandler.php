<?php
require_once(INCLUDEDIR."/validations/cStringValidations.php");
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
 * handles the input from the web
 * (validators will be separated in validator classes in future versions of this software)
 * 
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/09 19:54:28 $
 * @version $Revision: 1.11 $
 */
class cInputHandler{

	var $m_objStringValidations;			// validation rules for strings

	/**
	 * Constructor
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){
		$this->m_objStringValidations = new cStringValidations();
	}

	/**
	 * get a string variable from the web
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @param string $sValidName name of the validation that should be used
	 * @param boolean $bSearchPost search post vars for this variable
	 * @param boolean $bSearchGet search get vars for this variable
	 * @param string $sAddFunction name of an additional function that should be called (e.g. trim)
	 * @return string value of the variable
	 */
	function &getStringFormVar($sVarName,$sValidName,$bSearchPost,$bSearchGet,$sAddFunction = ""){

		$sValue = "";
		if(($bSearchPost) && isset($_POST[$sVarName])){
			$sValue = $_POST[$sVarName];
		}
		else if(($bSearchGet) && isset($_GET[$sVarName])){
			$sValue = $_GET[$sVarName];
		}

		if(get_magic_quotes_gpc()){
			$sValue = stripslashes($sValue);
		}
		$sValue = str_replace("\r","\n",str_replace("\r\n","\n",$sValue));

		if(strlen($sAddFunction)>0){
			$sValue = @$sAddFunction($sValue);
		}

		if(!empty($sValidName)){
			$sValidationMethodName = "validate".ucfirst(strtolower($sValidName));
			if(method_exists($this->m_objStringValidations,$sValidationMethodName)){
				$sValue = $this->m_objStringValidations->$sValidationMethodName($sValue);
			}
		}
		$sValue = preg_replace("/[^".chr(10).chr(32)."-".chr(255)."]/","?",$sValue);
		return $sValue;
	}

	/**
	 * get a integer variable from the web
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @param boolean $bSearchPost search post vars for this variable
	 * @param boolean $bSearchGet search get vars for this variable
	 * @param boolean $bForcePositive set negative numbers to 0
	 * @return integer value of the variable
	 */
	function &getIntFormVar($sVarName,$bSearchPost,$bSearchGet,$bForcePositive = FALSE){

		$iValue = 0;
		if(($bSearchPost) && isset($_POST[$sVarName])){
			$iValue = intval($_POST[$sVarName]);
		}
		else if(($bSearchGet) && isset($_GET[$sVarName])){
			$iValue = intval($_GET[$sVarName]);
		}
		if(($iValue<0) && ($bForcePositive)){
			$iValue = 0;
		}
		return $iValue;
	}

	/**
	 * get a array variable from the web
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the variable
	 * @param boolean $bSearchPost search post vars for this variable
	 * @param boolean $bSearchGet search get vars for this variable
	 * @param boolean $bForceUnique make the array elements unique
	 * @param string $sAddFunction name of an additional function that should be called (e.g. trim)
	 * @param string $sValidName name of the validation that should be used
	 * @return array value of the variable
	 */
	function &getArrFormVar($sVarName,$bSearchPost,$bSearchGet,$bForceUnique = FALSE,$sAddFunction = "",$sValidName = ""){

		$sValidationMethodName = "";
		if(!empty($sValidName)){
			$sTmpValidationMethodName = "validate".ucfirst(strtolower($sValidName));
			if(method_exists($this->m_objStringValidations,$sTmpValidationMethodName)){
				$sValidationMethodName = $sTmpValidationMethodName;
			}
		}

		$arrValues = array();
		if(($bSearchPost) && isset($_POST[$sVarName])){
			$arrValues = $_POST[$sVarName];
		}
		else if(($bSearchGet) && isset($_GET[$sVarName])){
			$arrValues = $_GET[$sVarName];
		}

		if($sAddFunction || $sValidationMethodName || get_magic_quotes_gpc()){
			while(list($mKey,$mVal) = each($arrValues)){
				if(get_magic_quotes_gpc()){
					$arrValues[$mKey] = stripcslashes($mVal);
				}
				if($sAddFunction){
					$arrValues[$mKey] = @$sAddFunction($mVal);
				}
				if($sValidationMethodName){
					$arrValues[$mKey] = $this->m_objStringValidations->$sValidationMethodName($mVal);
				}
			}
		}
		if($bForceUnique){
			$arrValues = array_unique($arrValues);
		}
		reset($arrValues);
		return $arrValues;
	}

	/**
	 * get a file upload object
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVarName name of the file variable
	 * @return object file upload object
	 */
	function &getFileFormObject($sVarName){
		include_once(INCLUDEDIR."/cFileUpload.php");
		$objFileUpload = new cFileUpload($sVarName);
		return $objFileUpload;
	}

	/**
	 * get the size of an input type
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sVaidatorType type of the variable
	 * @return integer size of an input type
	 */
	function getInputSize($sVaidatorType){
		return $this->m_objStringValidations->getLength($sVaidatorType);
	}
}
?>