<?php
require_once(INCLUDEDIR."/cSearchProfile.php");
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
 * searchprofilelist handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/03 11:27:17 $
 * @version $Revision: 1.4 $
 */
class cSearchProfileList{

	var	$m_arrSearchProfiles;			// SearchProfiles

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cSearchProfileList(){
		$this->m_arrSearchProfiles = array();
	}

	/**
	 * get data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function loadData(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT se_id,se_userid,se_message,se_nickname,se_days,se_tstmp FROM pxm_search ORDER BY se_tstmp DESC", 10)){

			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objSearchProfile = new cSearchProfile();

				$objSearchProfile->setId($objResultRow->se_id);
				$objSearchProfile->setIdUser($objResultRow->se_userid);
				$objSearchProfile->setSearchMessage($objResultRow->se_message);
				$objSearchProfile->setSearchUser($objResultRow->se_nickname);
				$objSearchProfile->setSearchDays($objResultRow->se_days);
				$objSearchProfile->setTimestamp($objResultRow->se_tstmp);

				$this->m_arrSearchProfiles[] = $objSearchProfile;
			}
			$objResultSet->freeResult();
		}
		else{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @return array member variables
	 */
	function &getDataArray($iTimeOffset,$sDateFormat){

		$arrOutput = array();
		reset($this->m_arrSearchProfiles);
		while(list(,$objSearchProfile) = each($this->m_arrSearchProfiles)){
			$arrOutput[] = $objSearchProfile->getDataArray($iTimeOffset,$sDateFormat);
		}
		return $arrOutput;
	}

	/**
	 * get the timestamp of the last search
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @return integer timestamp of the last search
	 */
	function getLastProfileTimestamp(){
		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT MAX(se_tstmp) as lasttstmp FROM pxm_search")){

			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->lasttstmp;
			}
		}
		return 0;
	}
}
?>