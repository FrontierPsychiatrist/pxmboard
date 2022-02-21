<?php
require_once(INCLUDEDIR."/cScrollList.php");
require_once(INCLUDEDIR."/cUserProfile.php");
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
 * user overview for admins
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.4 $
 */
class cUserAdminList extends cScrollList{

	var $m_iUserStateFilter;	// user state filter
	var $m_sSortAttribute;		// sort attribute
	var $m_sSortDirection;		// sort direction

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iUserStateFilter user state filter
	 * @param string $sSortAttribute sort attribute
	 * @param string $sSortDirection sort direction
	 * @return void
	 */
	function cUserAdminList($iUserStateFilter,$sSortAttribute,$sSortDirection){

		$this->m_iUserStateFilter = intval($iUserStateFilter);
		$this->m_sSortAttribute = $sSortAttribute;
		$this->m_sSortDirection = $sSortDirection;

		cScrollList::cScrollList();
	}

	/**
	 * get the query
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string query
	 */
	function _getQuery(){
		$sQuery = "SELECT u_id,u_nickname,u_registrationtstmp,u_lastonlinetstmp,u_profilechangedtstmp,u_msgquantity,u_status FROM pxm_user";
		if(!empty($this->m_iUserStateFilter)){
			$sQuery .= " WHERE u_status=".$this->m_iUserStateFilter;
		}
		if(!empty($this->m_sSortAttribute)){
			$sQuery .=  " ORDER BY ".$this->m_sSortAttribute." ".$this->m_sSortDirection;
		}
		return $sQuery;
	}

	/**
	 * initalize the member variables with the resultrow from the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){

		$objUser = new cUserProfile();
		$objUser->setId($objResultRow->u_id);
		$objUser->setNickName($objResultRow->u_nickname);
		$objUser->setRegistrationTimestamp($objResultRow->u_registrationtstmp);
		$objUser->setLastOnlineTimestamp($objResultRow->u_lastonlinetstmp);
		$objUser->setLastUpdateTimestamp($objResultRow->u_profilechangedtstmp);
		$objUser->setMessageQuantity($objResultRow->u_msgquantity);
		$objUser->setStatus($objResultRow->u_status);

		$this->m_arrResultList[] = $objUser;
	}
}
?>