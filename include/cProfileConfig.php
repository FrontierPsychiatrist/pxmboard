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
 * handles the user profile configuration
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.8 $
 */
class cProfileConfig{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cProfileConfig(){
	}

	/**
	 * get the installed addional profile slots
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array installed additional profile slots
	 */
	function &getSlotList(){

		global $objDb;

		$arrProfileSlots = array();

		if($objResultSet = &$objDb->executeQuery("SELECT pa_name,pa_type,pa_length FROM pxm_profile_accept")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				if(strlen($objResultRow->pa_name)>0){
					$arrProfileSlots[$objResultRow->pa_name] = array($objResultRow->pa_type,$objResultRow->pa_length);
				}
			}
			$objResultSet->freeResult();
		}
		return $arrProfileSlots;
	}

	/**
	 * delete profile slots
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrProfileSlots installed additional profile slots
	 * @return boolean success / failure
	 */
	function deleteSlots(&$arrProfileSlots){

		global $objDb;

		$arrExistingProfileSlots = &$this->getSlotList();
		foreach($arrProfileSlots as $sSlotName){
			if(preg_match("/^[a-zA-Z]+$/",$sSlotName) && isset($arrExistingProfileSlots[$sSlotName])){
				if($objDb->executeQuery("DELETE FROM pxm_profile_accept WHERE pa_name='".addslashes($sSlotName)."'")){
					$objDb->executeQuery("ALTER TABLE pxm_user DROP u_profile_$sSlotName");
				}
			}
		}
		return TRUE;
	}

	/**
	 * add an attribute to the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSlotName name of the new profile slot
	 * @param string $sSlotType type of the new profile slot (s = string, a = text, i = integer)
	 * @param integer $iSlotSize size of the new profile slot
	 * @return boolean success / failure
	 */
	function addSlot($sSlotName,$sSlotType,$iSlotSize = -1){
		global $objDb;

		$bReturn = FALSE;
		$arrProfileSlots = &$this->getSlotList();
		if(preg_match("/^[a-zA-Z]+$/",$sSlotName) && !isset($arrProfileSlots[$sSlotName])){

			$iSlotSize = intval($iSlotSize);

			$sQuery = "ALTER TABLE pxm_user ADD u_profile_$sSlotName ";
			switch($sSlotType){
				case 'i' 	:	$iSlotSize = 0;
								$sQuery .= $objDb->getMetaType("integer");
								break;
				case 's' 	:	
				case 'a' 	:	if($iSlotSize<=0){
									$iSlotSize = 1;
								}
								else if($iSlotSize>60000){
									$iSlotSize = 60000;
								}
								$sQuery .= $objDb->getMetaType("string",$iSlotSize);
								break;
				default	 	:	$type = "s";
								$iSlotSize = 255;
								$sQuery .= $objDb->getMetaType("string",$iSlotSize);
			}
			if($objDb->executeQuery($sQuery)){
				if($objDb->executeQuery("INSERT INTO pxm_profile_accept (pa_name,pa_type,pa_length) VALUES ('".addslashes($sSlotName)."','".addslashes($sSlotType)."',$iSlotSize)")){
					$bReturn = TRUE;
				}
			}
		}
		return $bReturn;
	}
}
?>