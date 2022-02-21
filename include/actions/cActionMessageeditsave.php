<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cBoardMessage.php");
require_once(INCLUDEDIR."/cBadwordList.php");
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
 * saves a message or show preview
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/20 18:57:40 $
 * @version $Revision: 1.10 $
 */
class cActionMessageeditsave extends cAction{

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

			$iBoardId = $objActiveBoard->getId();

			if($objActiveUser = &$this->m_objConfig->getActiveUser()){

				$bAdminMode = ($objActiveUser->isAdmin() || $objActiveUser->isModerator($iBoardId));

				if($bAdminMode || $objActiveBoard->isActive()){

					if($objActiveUser->isEditAllowed()){
	
						$iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE,TRUE);
						$sSubject = $this->m_objInputHandler->getStringFormVar("subject","subject",TRUE,FALSE,"trim");
						$sBody = $this->m_objInputHandler->getStringFormVar("body","body",TRUE,FALSE,"rtrim");
	
						if(empty($sSubject)){							// missing subject
							$objError = new cError(7);
							$this->m_objTemplate = &$this->_getTemplateObject("messageeditform");
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
							$this->m_objTemplate->addData($dummy = array("error"=>array($objError->getDataArray())));
							$this->m_objTemplate->addData($dummy = array("msg"=>array("subject"	=>$sSubject,
														  	 	 					  "_body"	=>htmlspecialchars($sBody))));
						}
						else{
							if($iMessageId>0){
	
								$objBoardMessage = new cBoardMessage();
	
								if($objBoardMessage->loadDataById($iMessageId,$iBoardId)){
									if($bAdminMode || $objBoardMessage->isThreadActive()){									
										if($bAdminMode || ($objActiveUser->getId() == $objBoardMessage->getAuthorId())){
											if($bAdminMode || $objBoardMessage->getReplyQuantity()<1){

												include_once(INCLUDEDIR."/cNotification.php");
												$objNotification = new cNotification();
												$objNotification->loadDataById(10);
	
												// replace badwords
												$objBadwordList = new cBadwordList();
												$arrBadwords = &$objBadwordList->getList();
												$sSubject = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sSubject);
												$sBody = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sBody);
	
												$objBoardMessage->setSubject($sSubject);
												$objBoardMessage->setBody($sBody.str_replace(array("%date%","%nickname%"),array(date($this->m_objConfig->getDateFormat(),$this->m_objConfig->getAccessTimestamp()),$objActiveUser->getNickName()),$objNotification->getMessage()));
												$iReturn = $objBoardMessage->updateData();
												if($iReturn==0){
													$this->m_objTemplate = &$this->_getTemplateObject("messageeditconfirm");
													$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
													$this->m_objTemplate->addData($dummy = array("msg"=>array("id"		=>$iMessageId,
																						  	 				  "subject"	=>$sSubject,
																						 	 				  "thread"	=>array("id"=>$objBoardMessage->getThreadId()))));
												}
												else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError($iReturn));
											}
											else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(17));// replies exist
										}
										else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));// forbidden
									}
									else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(9));// thread closed
								}
								else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(6));		// invalid msg id
							}
							else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(6));			// invalid msg id
						}
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));// forbidden
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(18));// board closed
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));			// missing board id
	}
}
?>