<?php
require_once(INCLUDEDIR."/actions/cAction.php");
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
 * base class for the board admin actions
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
 class cAdminAction extends cAction{

	var $m_sOutput;

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objConfig configuration data of the board
	 * @return void
	 */
	function __construct(&$objConfig){

		parent::__construct($objConfig);
		$this->m_sOutput = "";
	}

	/**
	 * get the output of this action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string output of this action
	 */
	function &getOutput(){
		return $this->m_sOutput;
	}

	/**
	 * get the head for the output
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param boolean $bShowHeadline show the headline?
	 * @return string head
	 */
	function _getHead($bShowHeadline = TRUE){
		$sReturn =  "<html>\n<head>\n";
		$sReturn .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
		$sReturn .= "<style type=\"text/css\">\n<!--\n";
	  	$sReturn .= "	body,td { font-family:arial,helvetica,sans-serif;}\n";
  		$sReturn .= "	h3 { margin-top:4cm; }\n";
	  	$sReturn .= "	h4 { text-decoration:underline; }\n";
  		$sReturn .= "	#e { color:#cc0000; }\n";
	  	$sReturn .= "	#h { background-color:#cccccc;font-weight:bold;text-align:center; }\n";
  		$sReturn .= "	#c { background-color:#eeeeee; }\n";
		$sReturn .= "	a:link { text-decoration:none; }\n";
		$sReturn .= "	a:visited { text-decoration:none; }\n";
		$sReturn .= "	a:active { text-decoration:none; }\n";
		$sReturn .= "//-->\n</style>\n";
		$sReturn .= "</head>\n<body onload=\"window.focus()\">\n<center>";
		if($bShowHeadline) $sReturn .= "<h2>< PXMBoard: Admintool ></h2>";

		return $sReturn;
	}

	/**
	 * get the footer for the output
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string footer
	 */
	function _getFooter(){
		return "</center>\n</body>\n</html>\n";
	}

	/**
	 * get a text input formfield
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sName name of the formfield
	 * @param integer $iMaxLength maximum length of the formfield
	 * @param string $sValue value of the formfield
	 * @param string $sDesc description of the formfield
	 * @return string html formfield
	 */
	function _getTextField($sName,$iMaxLength,$sValue,$sDesc = ""){
		$sReturn = "";
		$iMaxLength = intval($iMaxLength);
		if($iMaxLength>75){
			$iSize = 75;
		}
		else{
			$iSize = $iMaxLength;
		}
		if(!empty($sDesc)){
			$sReturn .= "<tr><td>".htmlspecialchars($sDesc)."</td><td>";
		}
		$sReturn .= "<input type=\"text\" name=\"$sName\" value=\"".htmlspecialchars($sValue)."\" maxlength=\"$iMaxLength\" size=\"$iSize\">";
		if(!empty($sDesc)){
			$sReturn .= "</td></tr>\n";
		}
		return $sReturn;
	}

	/**
	 * get a password input formfield
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sName name of the formfield
	 * @param integer $iMaxLength maximum length of the formfield
	 * @param string $sDesc description of the formfield
	 * @return string html formfield
	 */
	function _getPasswordField($sName,$iMaxLength,$sDesc = ""){
		$sReturn = "";
		$iMaxLength = intval($iMaxLength);
		if($iMaxLength>75){
			$iSize = 75;
		}
		else{
			$iSize = $iMaxLength;
		}
		if(!empty($sDesc)){
			$sReturn .= "<tr><td>".htmlspecialchars($sDesc)."</td><td>";
		}
		$sReturn .= "<input type=\"password\" name=\"$sName\" maxlength=\"$iMaxLength\" size=\"$iSize\">";
		if(!empty($sDesc)){
			$sReturn .= "</td></tr>\n";
		}
		return $sReturn;
	}

	/**
	 * get a hidden formfield
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sName name of the formfield
	 * @param string $sValue value of the formfield
	 * @return string html formfield
	 */
	function _getHiddenField($sName,$sValue){
		return "<input type=\"hidden\" name=\"$sName\" value=\"".htmlspecialchars($sValue)."\">";
	}

	/**
	 * get a checkbox formfield
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sName name of the formfield
	 * @param string $sValue value of the formfield
	 * @param string $sDesc description of the formfield
	 * @param boolean $bIsChecked is the formfield checked? (radio and checkbox only)
	 * @param string $sAdditionalHtml additional html (onclick etc)
	 * @return string html formfield
	 */
	function _getCheckboxField($sName,$sValue,$sDesc = "",$bIsChecked = FALSE,$sAdditionalHtml = ""){
		$sReturn = "";
		if(!empty($sDesc)){
			$sReturn .= "<tr><td>".htmlspecialchars($sDesc)."</td><td>";
		}
		$sReturn .= "<input type=\"checkbox\" name=\"$sName\" value=\"".htmlspecialchars($sValue)."\"".($bIsChecked?" checked":"")." $sAdditionalHtml>";
		if(!empty($sDesc)){
			$sReturn .= "</td></tr>\n";
		}
		return $sReturn;
	}

	/**
	 * get a radio formfield
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sName name of the formfield
	 * @param string $sValue value of the formfield
	 * @param string $sDesc description of the formfield
	 * @param boolean $bIsChecked is the formfield checked? (radio and checkbox only)
	 * @return string html formfield
	 */
	function _getRadioField($sName,$sValue,$sDesc = "",$bIsChecked = FALSE){
		$sReturn = "";
		if(!empty($sDesc)){
			$sReturn .= "<tr><td>".htmlspecialchars($sDesc)."</td><td>";
		}
		$sReturn .= "<input type=\"radio\" name=\"$sName\" value=\"".htmlspecialchars($sValue)."\"".($bIsChecked?" checked":"").">";
		if(!empty($sDesc)){
			$sReturn .= "</td></tr>\n";
		}
		return $sReturn;
	}
}
?>