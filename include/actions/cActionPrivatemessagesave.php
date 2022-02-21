<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cPrivateMessage.php");
require_once(INCLUDEDIR."/cBadwordList.php");
require_once(INCLUDEDIR."/cUserConfig.php");
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
 * saves a private message
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.8 $
 */
class cActionPrivatemessagesave extends cAction{

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

					$objDestinationUser = new cUserConfig();
					if($objDestinationUser->loadDataById($iDestinationId)){

						$sSubject = $this->m_objInputHandler->getStringFormVar("subject","subject",TRUE,FALSE,"trim");
						$sBody = $this->m_objInputHandler->getStringFormVar("body","body",TRUE,FALSE,"rtrim");

						if(empty($sSubject)){
							$objError = new cError(7);				// subject invalid
							$this->m_objTemplate = &$this->_getTemplateObject("privatemessageform");
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type"=>"outbox")));
							$this->m_objTemplate->addData($dummy = array("error" 	=>array($objError->getDataArray())));
							$this->m_objTemplate->addData($dummy = array("touser"	=>array("id"		=>$objDestinationUser->getId(),
																					 		"nickname"	=>$objDestinationUser->getNickName())));
							$this->m_objTemplate->addData($dummy = array("msg"		=>array("subject"	=>$sSubject,
														  	 								"_body"		=>htmlspecialchars($sBody))));
						}
						else{
							// replace badwords
							$objBadwordList = new cBadwordList();
							$arrBadwords = &$objBadwordList->getList();
							$sSubject = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sSubject);
							$sBody = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sBody);

							$objPrivateMessage = new cPrivateMessage();
							$objPrivateMessage->setDestinationUserId($objDestinationUser->getId());
							$objPrivateMessage->setAuthor($objActiveUser);
							$objPrivateMessage->setSubject($sSubject);
							$objPrivateMessage->setBody($sBody);
							$objPrivateMessage->setMessageTimestamp($this->m_objConfig->getAccessTimestamp());
							$objPrivateMessage->setIp(getenv("REMOTE_ADDR"));

							$iErrorId = $objPrivateMessage->insertData();
							if($iErrorId==0){
								if($objDestinationUser->sendPrivateMessageNotification() && ($sMail = $objDestinationUser->getPrivateMail())){

									include_once(INCLUDEDIR."/cNotification.php");
									$objPrivateMessageMailSubject = new cNotification();
									$objPrivateMessageMailSubject->loadDataById(11);
									$objPrivateMessageMailBody = new cNotification();
									$objPrivateMessageMailBody->loadDataById(12);

									@mail($sMail,
										  $objPrivateMessageMailSubject->getMessage(),
										  str_replace("%nickname%",$objActiveUser->getNickName(),$objPrivateMessageMailBody->getMessage()),
										  "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster());
								}
								$this->m_objTemplate = &$this->_getTemplateObject("privatemessagesaveconfirm");
								$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type"=>"outbox")));
							}
							else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError($iErrorId));
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