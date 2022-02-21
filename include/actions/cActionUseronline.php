<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cUserOnlineList.php");
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
 * which users are online at the moment?
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.10 $
 */
class cActionUseronline extends cAction{

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
		}
		else{
			$iIdBoard = 0;
		}

		$iLastOnline = 0;
		$bIsAdmin = FALSE;
		$arrUser = array();
		if($objActiveUser = &$this->m_objConfig->getActiveUser()){
			$iLastOnline = $objActiveUser->getLastOnlineTimestamp();
			$bIsAdmin = $objActiveUser->isAdmin();
			// private messages
			include_once(INCLUDEDIR."/cPrivateInboxList.php");
			$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId());
			$arrUser = array("user"=>array("newprivmsgs"=>strval($objPrivateMessageList->countUnread())));
		}

		$this->m_objTemplate = &$this->_getTemplateObject("useronline");

		// userlist
		$objUserOnlineList = new cUserOnlineList($bIsAdmin,$this->m_objConfig->getAccessTimestamp() - $this->m_objConfig->getOnlineTime());
		$objUserOnlineList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getUserPerPage());

		$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array_merge($arrUser,
																				   array("previd"	=>$objUserOnlineList->getPrevPageId(),
																   						 "nextid"	=>$objUserOnlineList->getNextPageId()),
																				   $this->getBannerCode($iIdBoard))));

		$this->m_objTemplate->addData($dummy = array("user"=>$objUserOnlineList->getDataArray()));

		// load visibility count
		$this->m_objTemplate->addData($dummy = array("users"=>$objUserOnlineList->getVisibilityDataArray()));
	}
}
?>