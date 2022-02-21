<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cSearchProfile.php");
require_once(INCLUDEDIR."/cSearchProfileList.php");
require_once(INCLUDEDIR."/cMessageSearchList.php");
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
 * search messages
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/04/09 09:14:04 $
 * @version $Revision: 1.18 $
 */
class cActionMessagesearch extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		$iIdBoard = 0;
		if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){
			$iIdBoard = $objActiveBoard->getId();
		}

		$iIdUser = 0;
		$iLastOnline = 0;
		$arrUser = array();
		if($objActiveUser = &$this->m_objConfig->getActiveUser()){
			$iIdUser = $objActiveUser->getId();
			$iLastOnline = $objActiveUser->getLastOnlineTimestamp();
			// private messages
			include_once(INCLUDEDIR."/cPrivateInboxList.php");
			$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId());
			$arrUser = array("user"=>array("newprivmsgs"=>strval($objPrivateMessageList->countUnread())));
		}

		// init search data
		$objSearch = new cSearchProfile();
		if(!$objSearch->loadDataById($this->m_objInputHandler->getIntFormVar("searchid",TRUE,TRUE,TRUE))){
			$objSearch->setIdUser($iIdUser);
			$objSearch->setSearchMessage($this->m_objInputHandler->getStringFormVar("smsg","searchstring",TRUE,TRUE,"trim"));
			$objSearch->setSearchUser($this->m_objInputHandler->getStringFormVar("susr","nickname",TRUE,TRUE,"trim"));
			$objSearch->setBoardIds($this->m_objInputHandler->getArrFormVar("sbrdid",TRUE,TRUE,TRUE,"intval"));
			$objSearch->setSearchDays($this->m_objInputHandler->getIntFormVar("days",TRUE,TRUE,TRUE));
			$objSearch->setTimestamp($this->m_objConfig->getAccessTimestamp());
		}

		$objSearchProfileList = new cSearchProfileList();

		if(strlen($objSearch->getSearchMessage())<1 && strlen($objSearch->getSearchUser())<1){

			// display the search form
			$this->_initSearchForm($iIdBoard,$arrUser,$objSearchProfileList);
		}
		else{
			$objError = NULL;

			$this->m_objTemplate = &$this->_getTemplateObject("messagelist");

			// messagelist
			$objMessageSearchList = new cMessageSearchList($objSearch,$this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat());

			// execute search
			$objMessageSearchList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getMessageHeaderPerPage());

			if($objMessageSearchList->getItemCount()<=500) {
				// insert profile into search table if not already stored
				if($objSearch->getId()<1){
					$objSearch->insertData();
				}
			}
			else {
				$objError = new cError(19);				// too many results
			}
			if(is_object($objError)){
				// display the search form
				$this->_initSearchForm($iIdBoard,$arrUser,$objSearchProfileList);
				$this->m_objTemplate->addData($dummy = array("error"=>$objError->getDataArray()));
			}
			else{
				// display the result
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array_merge($arrUser,
																						   array("previd"		=>$objMessageSearchList->getPrevPageId(),
																		   						 "nextid"		=>$objMessageSearchList->getNextPageId(),
																								 "curid"		=>$objMessageSearchList->getCurPageId(),
																								 "count"		=>$objMessageSearchList->getPageCount(),
																								 "items"		=>$objMessageSearchList->getItemCount(),
																								 "searchprofile"=>$objSearch->getDataArray($this->m_objConfig->getTimeOffset(),
																								 										   $this->m_objConfig->getDateFormat())),
																						   $this->getBannerCode($iIdBoard))));
				$this->m_objTemplate->addData($dummy = array("msg"=>$objMessageSearchList->getDataArray()));
			}
		}

		$objParser = new cParser();	// dummy parser

		// installed boards
		$objBoardList = new cBoardList();
		$objBoardList->loadBasicData();
		$this->m_objTemplate->addData($dummy = array("boards"=>array("board"=>$objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																										  $this->m_objConfig->getDateFormat(),
																										  $iLastOnline,
																										  $objParser))));
	}

	/**
	 * init the search form
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return void
	 */
	function _initSearchForm($iIdBoard,$arrUser,$objSearchProfileList){

		// load recent searchprofiles
		$objSearchProfileList->loadData();

		$this->m_objTemplate = &$this->_getTemplateObject("messagesearch");
		$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array_merge($arrUser,
																				   $this->getBannerCode($iIdBoard))));

		$this->m_objTemplate->addData($dummy = array("searchprofiles"=>array("searchprofile"=>$objSearchProfileList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																																  $this->m_objConfig->getDateFormat()))));
	}
}
?>