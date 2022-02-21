<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cPrivateMessage.php");
require_once(INCLUDEDIR."/cBoardMessage.php");
require_once(INCLUDEDIR."/cUser.php");
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
 * displays a private message form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.6 $
 */
class cActionprivatemessageform extends cAction{

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
			if($objActiveUser->isPostAllowed()){
				$iLastOnline = $objActiveUser->getLastOnlineTimestamp();
				$iDestinationId = $this->m_objInputHandler->getIntFormVar("toid",TRUE,TRUE,TRUE);
				if($iDestinationId>0){
					$objDestinationUser = new cUser();
					if($objDestinationUser->loadDataById($iDestinationId)){
						$this->m_objTemplate = &$this->_getTemplateObject("privatemessageform");

						if($this->m_objConfig->useSignatures()){
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type"=>"outbox",
																								 "user"=>array("signature" => $objActiveUser->getSignature()))));
						}
						else{
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type"=>"outbox")));
						}

						$this->m_objTemplate->addData($dummy = array("touser"=>array("id"		=>$objDestinationUser->getId(),
																					 "nickname"	=>$objDestinationUser->getNickName())));

						$iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE,TRUE);

						// parse the message body
						$objMessageQuoteParser = new cMessageQuoteParser();
						$objMessageQuoteParser->setQuoteChar($this->m_objConfig->getQuoteChar());

						if($iMessageId>0){
							if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){

								$objMessage = new cBoardMessage();

								if($objMessage->loadDataById($iMessageId,$objActiveBoard->getId())){
									$this->m_objTemplate->addData($dummy = array("msg"=>$objMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																												  $this->m_objConfig->getDateFormat(),
																												  $iLastOnline,
																												  $this->m_objConfig->getQuoteSubject(),
																												  $objMessageQuoteParser)));
								}
							}
						}
						else{
							$iMessageId = $this->m_objInputHandler->getIntFormVar("pmsgid",TRUE,TRUE,TRUE);

							if($iMessageId>0){
								$objPrivateMessage = new cPrivateMessage();
								$objPrivateMessage->setDestinationUserId($objActiveUser->getId());

								if($objPrivateMessage->loadDataById($iMessageId, NULL)){
									$this->m_objTemplate->addData($dummy = array("msg"=>$objPrivateMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																														 $this->m_objConfig->getDateFormat(),
																														 $iLastOnline,
																														 $this->m_objConfig->getQuoteSubject(),
																														 $objMessageQuoteParser)));
								}
							}
						}
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));// invalid user id
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));	// invalid user id
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));		// forbidden
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));			// not loged in
	}
}
?>