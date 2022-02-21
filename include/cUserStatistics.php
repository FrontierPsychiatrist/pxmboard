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
 * user statistics
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.6 $
 */
class cUserStatistics{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cUserStatistics(){
	}

	/**
	 * get the amount of registered users
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer amount of registered users
	 */
	function &getMemberCount(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS users FROM pxm_user")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->users;
			}
		}
		return 0;
	}

	/**
	 * get the newest member of the board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object newest member of the board
	 */
	function &getNewestMember(){
		$arrTmp = &$this->_getMembersByAttribute("u_registrationtstmp","DESC",1);
		if(sizeof($arrTmp)>0){
			return $arrTmp[0];
		}
		return NULL;
	}

	/**
	 * get the newest members of the board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array newest members of the board
	 */
	function &getNewestMembers(){
		return $this->_getMembersByAttribute("u_registrationtstmp","DESC",10);
	}

	/**
	 * get the oldest members of the board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array oldest members of the board
	 */
	function &getOldestMembers(){
		return $this->_getMembersByAttribute("u_registrationtstmp","ASC",10);
	}

	/**
	 * get the most active users (most posts)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array most active users (most posts)
	 */
	function &getMostActiveUsers(){
		return $this->_getMembersByAttribute("u_msgquantity","DESC",10);
	}

	/**
	 * get the least active users (most posts)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array least active users (least posts)
	 */
	function &getLeastActiveUsers(){
		return $this->_getMembersByAttribute("u_msgquantity","ASC",10);
	}

	/**
	 * get board members selected by a passed attribute
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sAttribute db attribute
	 * @param string $sOrder order by (asc|desc)
	 * @param integer $iLimit limit the result to x rows
	 * @return array user objects
	 */
	function &_getMembersByAttribute($sAttribute,$sOrder = "ASC",$iLimit = 1){

		global $objDb;
		$arrUsers = array();

		if($objResultSet = $objDb->executeQuery("SELECT u_id,u_nickname,u_city,u_publicmail,u_privatemail,u_registrationtstmp,u_msgquantity,u_highlight,u_highlight,u_status FROM pxm_user WHERE u_status='1' ORDER BY $sAttribute $sOrder",$iLimit)){
			$objUser = new cUser();
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$objUser->setId($objResultRow->u_id);
				$objUser->setNickName($objResultRow->u_nickname);
				$objUser->setCity($objResultRow->u_city);
				$objUser->setPublicMail($objResultRow->u_publicmail);
				$objUser->setRegistrationTimestamp($objResultRow->u_registrationtstmp);
				$objUser->setMessageQuantity($objResultRow->u_msgquantity);
				$objUser->setHighlightUser($objResultRow->u_highlight);
				$objUser->setStatus($objResultRow->u_status);

				$arrUsers[] = $objUser;
			}
			$objResultSet->freeResult();
		}
		return $arrUsers;
	}
}
?>