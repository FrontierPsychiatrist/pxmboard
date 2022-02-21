<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cPrivateInboxList.php");
require_once(INCLUDEDIR."/cPrivateOutboxList.php");
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
 * list of private messages
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.6 $
 */
class cActionPrivatemessagelist extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if($objActiveUser = &$this->m_objConfig->getActiveUser()){

			$this->m_objTemplate = &$this->_getTemplateObject("privatemessagelist");

			$sType = $this->m_objInputHandler->getStringFormVar("type","type",TRUE,TRUE);
			if($sType === "outbox"){
				// outbox
				$sType = "outbox";
				$objPrivateMessageList = new cPrivateOutboxList($objActiveUser->getId(),$this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat());
			}
			else{
				// inbox
				$sType = "inbox";
				$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId(),$this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat());
			}
			$objPrivateMessageList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getPrivateMessagesPerPage());

			$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("previd"	=>$objPrivateMessageList->getPrevPageId(),
																	   			 "nextid"	=>$objPrivateMessageList->getNextPageId(),
																				 "type"		=>$sType)));
			$this->m_objTemplate->addData($dummy = array("msg"=>$objPrivateMessageList->getDataArray()));
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));// not loged in
	}
}
?>