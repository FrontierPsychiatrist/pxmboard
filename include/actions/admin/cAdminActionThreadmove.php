<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cBoardList.php");
require_once(INCLUDEDIR."/parser/cParser.php");
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
 * move a thread to another board
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionThreadmove extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		$this->m_sOutput .= $this->_getHead();

		if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){

			$iBoardId = $objActiveBoard->getId();

			if($objActiveUser = &$this->m_objConfig->getActiveUser() && ($objActiveUser->isAdmin() || $objActiveUser->isModerator($iBoardId))){

				$iDestId = $this->m_objInputHandler->getIntFormVar("destid",TRUE,TRUE,TRUE);
				$iThreadId = $this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE);

				if($iThreadId>0){

					$objParser = new cParser();	// dummy parser

					$objBoardList = new cBoardList();
					$objBoardList->loadBasicData();
					$arrBoards = $objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
															 $this->m_objConfig->getDateFormat(),
															 0,
															 $objParser);
					$bIsValidBoardId = FALSE;

					foreach($arrBoards as $arrBoard){
						if ($arrBoard["id"] == $iDestId){
							$bIsValidBoardId = TRUE;
							break;
						}
					}

					if(!$bIsValidBoardId){
						$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"get\">".$this->_getHiddenField("mode","admthreadmove");
						$this->m_sOutput .= $this->_getHiddenField("brdid",$iBoardId).$this->_getHiddenField("id",$iThreadId);
						$this->m_sOutput .= "move thread to <select name=\"destid\" size=\"1\">\n";
						foreach($arrBoards as $arrBoard){
							$this->m_sOutput .= "<option value=\"".$arrBoard["id"]."\">".$arrBoard["name"]."</option>\n";
						}
						$this->m_sOutput .= "</select> <input type=\"submit\" value=\"go\"></form>\n";
					}
					else{
						include_once(INCLUDEDIR."/cThread.php");

						$objThread = new cThread();
						if($objThread->loadDataById($iThreadId,$iBoardId) &&
							$objThread->moveThread($iDestId)){

							$this->m_sOutput .= "<b>thread moved</b>";
						}
						else{
							$this->m_sOutput .= "<b id=\"e\">could not move thread</b>";
						}
					}
				}
				else $this->m_sOutput .= "<b id=\"e\">invalid threadid</b>";
			}
			else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">no board selected</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>