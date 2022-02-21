<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cBoardMessage.php");
require_once(INCLUDEDIR."/parser/cMessageQuoteParser.php");
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
 * display the message form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/20 18:57:40 $
 * @version $Revision: 1.10 $
 */
class cActionMessageform extends cAction{

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
		
			if($objActiveBoard->isActive()){

				$iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE,TRUE);
	
				$arrAdditionalConfig = array("quickpost" => $this->m_objConfig->useQuickPost(),
											 "guestpost" => $this->m_objConfig->useGuestPost());
				$iLastOnline = 0;
				if($objActiveUser = &$this->m_objConfig->getActiveUser()){
					$iLastOnline = $objActiveUser->getLastOnlineTimestamp();
				}
	
				if($iMessageId>0){
					$objMessage = new cBoardMessage();
					if($objMessage->loadDataById($iMessageId,$objActiveBoard->getId())){
	
						if($objMessage->isThreadActive()){
							$this->m_objTemplate = &$this->_getTemplateObject("messageform");
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray($arrAdditionalConfig));
	
							// parse the message body
							$objMessageQuoteParser = new cMessageQuoteParser();
							$objMessageQuoteParser->setQuoteChar($this->m_objConfig->getQuoteChar());
	
							$this->m_objTemplate->addData($dummy = array("msg"=>$objMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																										  $this->m_objConfig->getDateFormat(),
																										  $iLastOnline,
																										  $this->m_objConfig->getQuoteSubject(),
																										  $objMessageQuoteParser)));
						}
						else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(9));	// thread closed
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(6));		// invalid msg id
				}
				else{
					$this->m_objTemplate = &$this->_getTemplateObject("messageform");
					$this->m_objTemplate->addData($this->m_objConfig->getDataArray($arrAdditionalConfig));
				}
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(18));// board closed
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));		// missing board id
	}
}
?>