<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cBoardList.php");
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
 * delete messages in the selected boards for the selected timespan
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.9 $
 */
class cAdminActionMessagesdelete extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
		global $objDb;

		$this->m_sOutput .= $this->_getHead();

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){

			$this->m_sOutput .= "<h4>messagetool</h4>\n";

			$arrBoardIds = &$this->m_objInputHandler->getArrFormVar("brds",TRUE,TRUE,TRUE,"intval");
			$iTimespan = $this->m_objInputHandler->getIntFormVar("date",TRUE,TRUE,TRUE)*86400;

			if($iTimespan>0){
				if(sizeof($arrBoardIds)>0){

					$objBoardList = new cBoardList();
					$arrClosedBoardIds = $objBoardList->closeAllBoards(); 	// close boards
					if($objResultSet = &$objDb->executeQuery("SELECT t_id FROM pxm_thread WHERE t_boardid IN (".implode(",",$arrBoardIds).") AND t_fixed=0 AND t_lastmsgtstmp<".($this->m_objConfig->getAccessTimestamp()-$iTimespan))){
						$arrThreadIds = array();
						while($objResultRow = $objResultSet->getNextResultRowObject()){
							$arrThreadIds[] = intval($objResultRow->t_id);
						}
						if(sizeof($arrThreadIds)>0){
							$objDb->executeQuery("DELETE FROM pxm_message WHERE m_threadid IN (".implode(",",$arrThreadIds).")");
							$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id IN (".implode(",",$arrThreadIds).")");
							$this->m_sOutput .= "<h3>threads and messages deleted</h3>";
						}
						else{
							$this->m_sOutput .= "<h3>no threads found</h3>";
						}
					}
					$objBoardList->openBoards($arrClosedBoardIds);			// open boards
				}

				if($this->m_objInputHandler->getIntFormVar("priv",TRUE,TRUE)>0){
					$objDb->executeQuery("DELETE FROM pxm_priv_message WHERE p_tstmp<".($this->m_objConfig->getAccessTimestamp()-$iTimespan));
					$this->m_sOutput .= "<h3>private messages deleted</h3>";
				}
			}
			else $this->m_sOutput .= "<h3 id=\"e\">no timespan given</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>