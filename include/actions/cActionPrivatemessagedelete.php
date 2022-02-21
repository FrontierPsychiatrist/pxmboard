<?php
require_once(INCLUDEDIR."/actions/cAction.php");
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
 * delete private messages
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.5 $
 */
class cActionPrivatemessagedelete extends cAction{

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

			$bSuccess = FALSE;

			$sType = $this->m_objInputHandler->getStringFormVar("type","type",TRUE,TRUE);

			if(($iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE)) > 0){
				include_once(INCLUDEDIR."/cPrivateMessage.php");
				$objPrivateMessage = new cPrivateMessage();
				$objPrivateMessage->setAuthorId($objActiveUser->getId());
				$objPrivateMessage->setDestinationUserId($objActiveUser->getId());
				$objPrivateMessage->setId($iMessageId);
				if($objPrivateMessage->deleteData()){
					$bSuccess = TRUE;
				}
			}
			else{
				if($sType === "inbox"){
					include_once(INCLUDEDIR."/cPrivateInboxList.php");
					$objPrivateMessageList = new cPrivateInboxList($objActiveUser->getId());
				}
				else{
					include_once(INCLUDEDIR."/cPrivateOutboxList.php");
					$objPrivateMessageList = new cPrivateOutboxList($objActiveUser->getId());
				}
				if($objPrivateMessageList->deleteData()){
					$bSuccess = TRUE;
				}
			}
			if($bSuccess){
				if($sType !== "inbox" && $sType !== "outbox"){
					$sType = "inbox";
				}
				$this->m_objTemplate = &$this->_getTemplateObject("privatemessagedeleteconfirm");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type"=>$sType)));
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(13));// could not delete data
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
	}
}
?>