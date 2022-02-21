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
 * run db clean tool
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/31 11:24:52 $
 * @version $Revision: 1.8 $
 */
class cAdminActionDbclean extends cAdminAction{

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

			$bDeleteNoUserMsgs = ($this->m_objInputHandler->getIntFormVar("nousr",TRUE,TRUE,TRUE)>0);
			$iAccessTime = $this->m_objConfig->getAccessTimestamp();

			$objBoardList = new cBoardList();
			$arrClosedBoardIds = $objBoardList->closeAllBoards(); 	// close boards

// delete moderators with invalid boardid or userid ///////////////////////////

			if($this->m_objInputHandler->getIntFormVar("nomod",TRUE,TRUE,TRUE)>0){
				if ($objResultSet = &$objDb->executeQuery("SELECT DISTINCT mod_userid FROM pxm_moderator LEFT JOIN pxm_user ON mod_userid=u_id WHERE u_id IS NULL")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("DELETE FROM pxm_moderator WHERE mod_userid=$objResultRow->mod_userid");
					}
					$this->m_sOutput .= "<h3>moderatores with invalid userid deleted</h3>";
				}

				if($objResultSet = &$objDb->executeQuery("SELECT DISTINCT mod_boardid FROM pxm_moderator LEFT JOIN pxm_board ON mod_boardid=b_id WHERE b_id IS NULL")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("DELETE FROM pxm_moderator WHERE mod_boardid=$objResultRow->mod_boardid");
					}
					$this->m_sOutput .= "<h3>moderatores with invalid boardid deleted</h3>";
				}
			}

// delete threads with invalid boardid ////////////////////////////////////////

			if($this->m_objInputHandler->getIntFormVar("nobrd",TRUE,TRUE,TRUE)>0){
				if($objResultSet = &$objDb->executeQuery("SELECT t_id FROM pxm_thread LEFT JOIN pxm_board ON t_boardid=b_id WHERE b_id IS NULL")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("DELETE FROM pxm_message WHERE m_threadid=$objResultRow->t_id");
						$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id=$objResultRow->t_id");
					}
					$this->m_sOutput .= "<h3>threads with invalid boardid deleted</h3>";
				}
			}

// delete messages with invalid users /////////////////////////////////////////

			if($bDeleteNoUserMsgs>0){
				if($objResultSet = &$objDb->executeQuery("SELECT m_id FROM pxm_message LEFT JOIN pxm_user ON m_userid=u_id WHERE m_userid>0 AND u_id IS NULL")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("DELETE FROM pxm_message WHERE m_id=$objResultRow->m_id");
					}
					$this->m_sOutput .= "<h3>threads with invalid userid deleted</h3>";
				}
			}

// delete empty threads ///////////////////////////////////////////////////////

			if($this->m_objInputHandler->getIntFormVar("nomsg",TRUE,TRUE,TRUE)>0){
				if($objResultSet = &$objDb->executeQuery("SELECT t_id FROM pxm_thread LEFT JOIN pxm_message ON t_id=m_threadid WHERE m_threadid IS NULL AND t_lastmsgtstmp<".($iAccessTime-300))){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id=$objResultRow->t_id");
					}
					$this->m_sOutput .= "<h3>empty threads deleted</h3>";
				}
			}

// restore data ///////////////////////////////////////////////////////////////

			if(($this->m_objInputHandler->getIntFormVar("restrd",TRUE,TRUE,TRUE)>0) || $bDeleteNoUserMsgs){

// restore threads with more or less than 1 root message //////////////////////

				if($objResultSet = &$objDb->executeQuery("SELECT m_threadid,COUNT(*) AS cou FROM pxm_message WHERE m_parentid=0 GROUP BY m_threadid HAVING COUNT(*)!=1")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						if($objResultRow->cou !== 1){
							if($objResultSet = &$objDb->executeQuery("SELECT m_id FROM pxm_message WHERE m_threadid=$objResultRow->m_threadid ORDER BY m_tstmp ASC")){
								if($objResultRow2 = $objResultSet->getNextResultRowObject()){
									if($objResultRow->cou<1){
										$objDb->executeQuery("UPDATE pxm_message SET m_parentid=0 WHERE m_id=$objResultRow2->m_id");
									}
									else{
										$objDb->executeQuery("UPDATE pxm_message SET m_parentid=$objResultRow2->m_id WHERE m_id!=$objResultRow2->m_id AND m_parentid=0 AND m_threadid=$objResultRow->m_threadid");
									}
								}
							}
						}
					}
				}

// restore messages without thread ////////////////////////////////////////////

				if($objResultSet = &$objDb->executeQuery("SELECT DISTINCT m_threadid FROM pxm_message LEFT JOIN pxm_thread ON m_threadid=t_id WHERE t_id IS NULL AND m_parentid=0 AND m_tstmp<".($iAccessTime-300))){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("INSERT INTO pxm_thread VALUES()");
						$objDb->executeQuery("UPDATE pxm_message SET m_threadid=".$objDb->getInsertID("pxm_thread","t_id")." WHERE m_threadid=$objResultRow->m_threadid");
					}
				}

// restore messages without parentmessage /////////////////////////////////////

				if($objResultSet = &$objDb->executeQuery("SELECT DISTINCT m1.m_threadid AS threadid,m1.m_parentid AS parentid FROM pxm_message AS m1 LEFT JOIN pxm_message AS m2 ON m1.m_parentid=m2.m_id WHERE m2.m_id IS NULL AND m1.m_parentid>0")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						if($objDb->executeQuery("SELECT m_id FROM pxm_message WHERE m_threadid=$objResultRow->threadid AND m_parentid=0")){
							if($objResultRow2 = $objResultSet->getNextResultRowObject()){
								$objDb->executeQuery("UPDATE pxm_message SET m_parentid=$objResultRow2->m_id WHERE m_parentid=$objResultRow->parentid");
							}
						}
					}
				}

// update last msgid and date /////////////////////////////////////////////////

				if($objResultSet = &$objDb->executeQuery("SELECT t_id,t_lastmsgid,t_lastmsgtstmp,m_id,m_tstmp FROM pxm_message,pxm_thread WHERE m_threadid=t_id ORDER BY t_id,m_tstmp DESC")){

					$iThreadId = -1;

					while($objResultRow = $objResultSet->getNextResultRowObject()){
						if($iThreadId != $objResultRow->t_id){
							$iThreadId = $objResultRow->t_id;

							if(($objResultRow->t_lastmsgid != $objResultRow->m_id) || ($objResultRow->t_lastmsgtstmp != $objResultRow->m_tstmp)){
								$objDb->executeQuery("UPDATE pxm_thread SET t_lastmsgid=$objResultRow->m_id,t_lastmsgtstmp=$objResultRow->m_tstmp WHERE t_id=$objResultRow->t_id");
							}
						}
					}
				}

				if($objResultSet = &$objDb->executeQuery("SELECT t_boardid,MAX(t_lastmsgtstmp) AS bmsgdate FROM pxm_thread GROUP BY t_boardid")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("UPDATE pxm_board SET b_lastmsgtstmp=$objResultRow->bmsgdate WHERE b_id=$objResultRow->t_boardid");
					}
				}

// update msg quantity /////////////////////////////////////////////////////////

				if($objResultSet = &$objDb->executeQuery("SELECT m_threadid,COUNT(*) AS cou FROM pxm_message GROUP BY m_threadid")){
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						$objDb->executeQuery("UPDATE pxm_thread SET t_msgquantity=".($objResultRow->cou-1)." WHERE t_id=$objResultRow->m_threadid");
					}
					$this->m_sOutput .= "<h3>messages and threads restored</h3>";
				}
			}
			$objBoardList->openBoards($arrClosedBoardIds);			// open boards
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>