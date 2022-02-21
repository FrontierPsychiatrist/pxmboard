<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cBoardList.php");
require_once(INCLUDEDIR."/cUserStatistics.php");
require_once(INCLUDEDIR."/cMessageStatistics.php");
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
 * show the board list
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.9 $
 */
 class cActionBoardlist extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
		$this->m_objTemplate = &$this->_getTemplateObject("boardlist");

		$iLastOnlineTimestamp = 0;
		if($objActiveUser = &$this->m_objConfig->getActiveUser()){
			$iLastOnlineTimestamp = $objActiveUser->getLastOnlineTimestamp();
			// private messages
			include_once(INCLUDEDIR."/cPrivateInboxList.php");
			$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId());
			$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array_merge(array("user"=>array("newprivmsgs"=>strval($objPrivateMessageList->countUnread()))),
																					   $this->getBannerCode())));
		}
		else{
			$this->m_objTemplate->addData($this->m_objConfig->getDataArray($this->getBannerCode()));
		}

		$objMessageParser = new cParser();	// dummy parser

		// installed boards
		$objBoardList = new cBoardList();
		$objBoardList->loadData();
		$this->m_objTemplate->addData($dummy = array("boards"=>array("board"=>$objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																										  $this->m_objConfig->getDateFormat(),
																										  $iLastOnlineTimestamp,
																										  $objMessageParser))));
		// newest member
		$objStatistics = new cUserStatistics();
		if($objUser = &$objStatistics->getNewestMember()){
			$this->m_objTemplate->addData($dummy = array("newestmember"=>array("user"=>$objUser->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																											  $this->m_objConfig->getDateFormat(),
																											  $objMessageParser))));
		}

		// newest messages
		$arrBoardMessages = array();
		$objStatistics = new cMessageStatistics();
		foreach($objStatistics->getNewestMessages($this->m_objConfig->getAccessTimestamp()-14*24*3600) as $objBoardMessage){
			$arrBoardMessages[] = $objBoardMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																 $this->m_objConfig->getDateFormat(),
																 $iLastOnlineTimestamp,
																 "",
																 $objMessageParser);
		}
		$this->m_objTemplate->addData($dummy = array("newestmessages"=>array("msg"=>$arrBoardMessages)));
	}
}
?>