<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cUserConfig.php");
require_once(INCLUDEDIR."/cBoardMessage.php");
require_once(INCLUDEDIR."/cMessage.php");
require_once(INCLUDEDIR."/parser/cMessageHtmlParser.php");
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
 * @version $Date: 2006/04/09 20:48:02 $
 * @version $Revision: 1.17 $
 */
class cActionMessagesave extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void$iMessageId$iMessageId
	 */
	function performAction(){
		if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){

			if($objActiveBoard->isActive()){

				$iBoardId = $objActiveBoard->getId();
				$arrErrors = array();

				$iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE,TRUE);
				$bPreviewMode = (strlen($this->m_objInputHandler->getStringFormVar("preview_x","character",TRUE,TRUE))>0);
				$bEditMode = (strlen($this->m_objInputHandler->getStringFormVar("edit_x","character",TRUE,TRUE))>0);
				$sSubject = $this->m_objInputHandler->getStringFormVar("subject","subject",TRUE,FALSE,"trim");
				$sBody = $this->m_objInputHandler->getStringFormVar("body","body",TRUE,FALSE,"rtrim");

				if(!($bPreviewMode || $bEditMode)){

					$objActiveUser = &$this->m_objConfig->getActiveUser();

					if(!is_object($objActiveUser)){

						unset($objActiveUser);		// destroy reference
						$objActiveUser = new cUserConfig();

						if($this->m_objConfig->useQuickPost()||$this->m_objConfig->useGuestPost()){

							$sNickName = $this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,TRUE,"trim");

							if(!empty($sNickName)){
								if($objActiveUser->loadDataByNickName($sNickName)){
									if($this->m_objConfig->useQuickPost()){
										if(!$objActiveUser->validatePassword($this->m_objInputHandler->getStringFormVar("pass","password",TRUE,TRUE,"trim"))){
											$arrErrors[] = new cError(3);	// invalid password
										}
										else if($objActiveUser->getStatus() != cUserStates::userActive()){
											$arrErrors[] = new cError(12);	// not allowed
										}
										else if($this->m_objConfig->getOnlineTime()>0){
											$objActiveUser->updateLastOnlineTimestamp($this->m_objConfig->getAccessTimestamp());
										}
									}
									else{
										$arrErrors[] = new cError(25);		// user already registered
									}
								}
								else{
									if($this->m_objConfig->useGuestPost()){
										$objActiveUser->setPostAllowed(TRUE);
										$objActiveUser->setNickName($sNickName);
										$objActiveUser->setPublicMail($this->m_objInputHandler->getStringFormVar("pubemail","email",TRUE,TRUE,"trim"));
									}
									else{
										$arrErrors[] = new cError(2);		// user unknown
									}
								}
							}
							else $arrErrors[] = new cError(26);				// empty nickname
						}
						else $arrErrors[] = new cError(22);					// not loged in
					}
				}

				if(empty($sSubject)){
					$arrErrors[] = new cError(7);							// missing subject
				}

				if(!empty($arrErrors) || ($bPreviewMode) || ($bEditMode)){
					$this->m_objTemplate = &$this->_getTemplateObject("messageform");

					$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("quickpost" => $this->m_objConfig->useQuickPost(),
																						 "guestpost" => $this->m_objConfig->useGuestPost())));

					if(!empty($arrErrors)){
						$arrErrorArrays = array();
						foreach($arrErrors as $objError){
							$arrErrorArrays[] = $objError->getDataArray();
						}
						$this->m_objTemplate->addData($dummy = array("error"=>$arrErrorArrays));
					}

					if($bPreviewMode){								// add parsed message
						$objMessage = new cMessage();
						$objMessage->setId($iMessageId);
						$objMessage->setSubject($sSubject);
						$objMessage->setBody($sBody);
						$objMessage->setMessageTimestamp($this->m_objConfig->getAccessTimestamp());
						$objMessage->setIp(getenv("REMOTE_ADDR"));

						$objActiveSkin = &$this->m_objConfig->getActiveSkin();

						// parse the message body
						$objMessageHtmlParser = new cMessageHtmlParser();
						$objMessageHtmlParser->setQuoteChar($this->m_objConfig->getQuoteChar());
						$objMessageHtmlParser->setQuotePrefix($objActiveSkin->getQuotePrefix());
						$objMessageHtmlParser->setQuoteSuffix($objActiveSkin->getQuoteSuffix());
						$objMessageHtmlParser->setParseUrl($objActiveBoard->parseUrl());
						$objMessageHtmlParser->setParseImages($this->m_objConfig->parseImages());
						$objMessageHtmlParser->setParseStyle($objActiveBoard->parseStyle());
						if($this->m_objConfig->doTextReplacements()){
							include_once(INCLUDEDIR."/cTextreplacementList.php");
							$objTextreplacementList = new cTextreplacementList();
							$objMessageHtmlParser->setReplacements($objTextreplacementList->getList());
						}
						$this->m_objTemplate->addData($dummy = array("pmsg"=>$objMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																									   $this->m_objConfig->getDateFormat(),
																									   0,
																									   "",
																									   $objMessageHtmlParser)));
					}
					$this->m_objTemplate->addData($dummy = array("msg" => array("id"		=>$iMessageId,
																 				"subject"	=>$sSubject,
																  				"_body"		=>htmlspecialchars($sBody))));
				}
				else{
					if($objActiveUser->isPostAllowed()){

						// replace badwords
						include_once(INCLUDEDIR."/cBadwordList.php");
						$objBadwordList = new cBadwordList();
						$arrBadwords = &$objBadwordList->getList();
						$sSubject = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sSubject);
						$sBody = str_replace($arrBadwords["search"],$arrBadwords["replace"],$sBody);

						$objBoardMessage = new cBoardMessage();

						$objBoardMessage->setBoardId($iBoardId);
						$objBoardMessage->setAuthor($objActiveUser);
						$objBoardMessage->setSubject($sSubject);
						$objBoardMessage->setBody($sBody);
						$objBoardMessage->setMessageTimestamp($this->m_objConfig->getAccessTimestamp());
						$objBoardMessage->setIp(getenv("REMOTE_ADDR"));
						$objBoardMessage->setSendNotification($this->m_objInputHandler->getIntFormVar("notification",TRUE,TRUE,TRUE));

						$iReturn = $objBoardMessage->insertData($iMessageId,
																$this->m_objConfig->getThreadSizeLimit());
						if($iReturn==0){

							// count message for the author
							if($objActiveUser->getId()>0){
								$objActiveUser->incrementMessageQuantity();
							}

							// reply notification
							$objReplyMessage = new cBoardMessage();
							if($objReplyMessage->loadDataById($iMessageId,$iBoardId) && $objReplyMessage->sendNotification()){
								$objReplyAuthor = $objReplyMessage->getAuthor();
								if($objActiveUser->getId() != $objReplyAuthor->getId()) {
									if($objReplyAuthor->loadDataById($objReplyAuthor->getId()) && ($sMail = $objReplyAuthor->getPrivateMail())){
										include_once(INCLUDEDIR."/cNotification.php");
										$objReplyNotificationMailSubject = new cNotification();
										$objReplyNotificationMailSubject->loadDataById(13);
										$objReplyNotificationMailBody = new cNotification();
										$objReplyNotificationMailBody->loadDataById(14);

										@mail($sMail,
											  $objReplyNotificationMailSubject->getMessage(),
											  str_replace(array("%nickname%",
											  					"%subject%",
																"%id%",
																"%replysubject%",
																"%replyid%",
																"%boardid%",
																"%threadid%"),
														  array($objActiveUser->getNickName(),
														  		$objReplyMessage->getSubject(),
																$objReplyMessage->getId(),
																$sSubject,
																$objBoardMessage->getId(),
																$iBoardId,
																$objReplyMessage->getThreadId()),
														  $objReplyNotificationMailBody->getMessage()),
											  "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster());
									}
								}
							}

							$this->m_objTemplate = &$this->_getTemplateObject("messagesaveconfirm");

							$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
							$this->m_objTemplate->addData($dummy = array("msg"=>array("id"		=>$objBoardMessage->getId(),
																					  "subject"	=>$sSubject,
																					  "thread"	=>array("id"=>$objBoardMessage->getThreadId()))));
						}
						else{
							$objError = new cError($iReturn);
							$this->m_objTemplate = &$this->_getTemplateObject("messageform");

							$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("quickpost" => $this->m_objConfig->useQuickPost(),
																						 		 "guestpost" => $this->m_objConfig->useGuestPost())));
							$this->m_objTemplate->addData($dummy = array("error"=>array($objError->getDataArray())));
							$this->m_objTemplate->addData($dummy = array("msg"=>array("id"		=>$iMessageId,
																 					  "subject"	=>$sSubject,
															  	 					  "_body"	=>htmlspecialchars($sBody))));
						}
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));// forbidden
				}
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(18));		// board closed
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));				// missing board id
	}
}
?>