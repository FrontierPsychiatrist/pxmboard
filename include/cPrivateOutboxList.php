<?php
require_once(INCLUDEDIR."/cPrivateMessageList.php");
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
 * private message outbox handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.3 $
 */
class cPrivateOutboxList extends cPrivateMessageList{

	/**
	 * get the query
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string query
	 */
	function _getQuery(){
		$sQuery = "SELECT p_id,p_subject,p_tstmp,u_id,u_nickname,u_highlight,p_fromstate FROM pxm_priv_message,pxm_user WHERE ";
		$sQuery .= "p_touserid=u_id AND p_fromuserid=$this->m_iUserId AND p_fromstate!=".cMessageStates::messageDeleted();
		$sQuery .= " ORDER BY p_tstmp DESC";
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

		$this->m_arrResultList[] = array("id"		=>$objResultRow->p_id,
										 "subject"	=>$objResultRow->p_subject,
										 "date"		=>date($this->m_sDateFormat,($objResultRow->p_tstmp+$this->m_iTimeOffset)),
										 "read"		=>($objResultRow->p_fromstate==cMessageStates::messageRead()?"1":"0"),
										 "user"		=>array("id"		=>$objResultRow->u_id,
															"nickname"	=>$objResultRow->u_nickname,
															"highlight"	=>$objResultRow->u_highlight));
	}

	/**
	 * delete data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function deleteData(){

		global $objDb;

		// set the message to deleted if we are the author
		$objDb->executeQuery("UPDATE pxm_priv_message SET p_fromstate=".cMessageStates::messageDeleted()." WHERE p_fromuserid=$this->m_iUserId");

		// remove all deleted messages from db
		$objDb->executeQuery("DELETE FROM pxm_priv_message WHERE p_tostate=".cMessageStates::messageDeleted()." AND p_fromstate=".cMessageStates::messageDeleted());

		return TRUE;
	}
}
?>