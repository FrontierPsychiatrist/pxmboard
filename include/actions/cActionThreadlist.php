<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cThreadList.php");
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
 * show the thread list for a board
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.9 $
 */
 class cActionThreadlist extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
		if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){
			$iIdBoard = $objActiveBoard->getId();

			$this->m_objTemplate = &$this->_getTemplateObject("threadlist");

			$sSortMode = $this->m_objInputHandler->getStringFormVar("sort","sortmode",TRUE,TRUE,"trim");
			if(!empty($sSortMode)){
				$this->m_objConfig->setThreadListSortMode($sSortMode);
			}
			$iTimeSpan = $this->m_objInputHandler->getIntFormVar("date",TRUE,TRUE,TRUE);
			if($iTimeSpan>0){
				$objActiveBoard->setThreadListTimeSpan($iTimeSpan);
			}

			$objThreadList = new cThreadList($iIdBoard,$this->m_objConfig->getThreadListSortMode(),$this->m_objConfig->getAccessTimestamp() - $objActiveBoard->getThreadListTimeSpan()*86400 + $this->m_objConfig->getTimeOffset()*3600);
			$objThreadList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getThreadsPerPage());

			$iLastOnline = 0;
			$arrUser = array();
			if($objActiveUser = &$this->m_objConfig->getActiveUser()){
				$iLastOnline = $objActiveUser->getLastOnlineTimestamp();
				// private messages
				include_once(INCLUDEDIR."/cPrivateInboxList.php");
				$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId());
				$arrUser = array("user"=>array("newprivmsgs"=>strval($objPrivateMessageList->countUnread())));
			}
			$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array_merge($arrUser,
																					   array("previd"=>$objThreadList->getPrevPageId(),
						 																	 "nextid"=>$objThreadList->getNextPageId()),
												 									   $this->getBannerCode($iIdBoard))));

			$this->m_objTemplate->addData($dummy = array("threads"=>$objThreadList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																	   							$this->m_objConfig->getDateFormat(),
																							    $iLastOnline)));
			$objParser = new cParser();	// dummy parser

			// installed boards
			$objBoardList = new cBoardList();
			$objBoardList->loadBasicData();
			$this->m_objTemplate->addData($dummy = array("boards"=>array("board"=>$objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																											  $this->m_objConfig->getDateFormat(),
																											  $iLastOnline,
																											  $objParser))));
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));	// missing board id
	}
}
?>