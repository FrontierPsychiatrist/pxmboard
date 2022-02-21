<?php
require_once(INCLUDEDIR."/cUser.php");
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
 * user profile handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/06/18 14:36:19 $
 * @version $Revision: 1.10 $
 */
class cUserProfile extends cUser{

	var $m_iLastUpdateTimestap;		// timestamp of last profileupdate
	var	$m_arrAddFields;			// additional profile fields
	var $m_arrAddData;				// additional profile data

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrAddFields additional profile fields
	 * @return void
	 */
	function __construct($arrAddFields = array()){

		parent::__construct();

		$this->m_iLastUpdateTimestap = 0;
		$this->m_arrAddFields = &$arrAddFields;
		$this->m_arrAddData = array();
	}

	/**
	 * initalize the member variables with the resultset from the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){

		cUser::_setDataFromDb($objResultRow);

		$this->m_sSignature	= $objResultRow->u_signature;
		$this->m_iLastUpdateTimestap = intval($objResultRow->u_profilechangedtstmp);

		foreach($this->m_arrAddFields as $sFieldName => $arrFieldAttributes){
			$sResultVarName = "u_profile_".$sFieldName;
			$this->m_arrAddData[$sFieldName] = ($arrFieldAttributes[0]=='i'?intval($objResultRow->$sResultVarName):$objResultRow->$sResultVarName);
		}

		return TRUE;
	}

	/**
	 * get additional database attributes for this object (template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database attributes for this object
	 */
	 function _getDbAttributes(){

	 	$sAddDbFields = "";
	 	foreach(array_keys($this->m_arrAddFields)as $sFieldName){
			$sAddDbFields .= ",u_profile_".$sFieldName;
		}
	 	return cUser::_getDbAttributes().",u_signature,u_profilechangedtstmp".$sAddDbFields;
	}

	/**
	 * update data in database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function updateData(){

		global $objDb;
		$sAddUpdateQuery = "";

		foreach($this->m_arrAddData as $sFieldName => $mData){
			if(is_integer($mData)){
				$sAddUpdateQuery .= "u_profile_".$sFieldName."=".$mData.",";
			}
			else{
				$sAddUpdateQuery .= "u_profile_".$sFieldName."='".addslashes($mData)."',";
			}
		}

		if(!$objDb->executeQuery("UPDATE pxm_user SET u_signature='".addslashes($this->m_sSignature)."',".
													 "u_firstname='".addslashes($this->m_sFirstName)."',".
													 "u_lastname='".addslashes($this->m_sLastName)."',".
													 "u_city='".addslashes($this->m_sCity)."',".
													 "u_publicmail='".addslashes($this->m_sPublicMail)."',".
													 $sAddUpdateQuery.
													 "u_profilechangedtstmp=".$this->m_iLastUpdateTimestap.
								" WHERE u_id=".$this->m_iId)){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * get last update timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer last update timestamp
	 */
	function getLastUpdateTimestamp(){
		return $this->m_iLastUpdateTimestap;
	}

	/**
	 * set last update timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iLastUpdateTimestap last update timestamp
	 * @return void
	 */
	function setLastUpdateTimestamp($iLastUpdateTimestap){
		$this->m_iLastUpdateTimestap = intval($iLastUpdateTimestap);
	}

	/**
	 * get additional data element
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sElementName name of the additional data element
	 * @return mixed element
	 */
	function getAdditionalDataElement($sElementName){
		if(isset($this->m_arrAddData[$sElementName])){
			return $this->m_arrAddData[$sElementName];
		}
		return NULL;
	}

	/**
	 * set additional data element
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sElementName name of the additional data element
	 * @param mixed $mElementValue element
	 * @return void
	 */
	function setAdditionalDataElement($sElementName,$mElementValue){
		if(isset($this->m_arrAddFields[$sElementName])){
			if($this->m_arrAddFields[$sElementName][0]=='i'){
				$mElementValue = intval($mElementValue);
			}
			$this->m_arrAddData[$sElementName] = $mElementValue;
		}
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param object $objParser message parser (for signature)
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,&$objParser){
		return array_merge(cUser::getDataArray($iTimeOffset,$sDateFormat,$objParser),
						   array("lchange"	=>	(($this->m_iLastUpdateTimestap>0)?date($sDateFormat,($this->m_iLastUpdateTimestap+$iTimeOffset)):0)),
						   $this->m_arrAddData);
	}
}
?>