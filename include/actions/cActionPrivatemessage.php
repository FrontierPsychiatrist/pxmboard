<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cPrivateMessage.php");
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
 * displays a private message
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.7 $
 */
class cActionPrivatemessage extends cAction{

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
			$iLastOnline = $objActiveUser->getLastOnlineTimestamp();

			$iMessageId = $this->m_objInputHandler->getIntFormVar("msgid",TRUE,TRUE,TRUE);

			if($iMessageId>0){
				$objPrivateMessage = new cPrivateMessage();
				$objPrivateMessage->setAuthorId($objActiveUser->getId());
				$objPrivateMessage->setDestinationUserId($objActiveUser->getId());

				if($objPrivateMessage->loadDataById($iMessageId)){

					$this->m_objTemplate = &$this->_getTemplateObject("privatemessage");

					$sType = $this->m_objInputHandler->getStringFormVar("type","type",TRUE,TRUE);
					if($sType !== "inbox" && $sType !== "outbox"){
						$sType = "inbox";
					}
					$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("type" => $sType)));

					$objActiveSkin = &$this->m_objConfig->getActiveSkin();

					// parse the message body
					$objMessageHtmlParser = new cMessageHtmlParser();
					$objMessageHtmlParser->setQuoteChar($this->m_objConfig->getQuoteChar());
					$objMessageHtmlParser->setQuotePrefix($objActiveSkin->getQuotePrefix());
					$objMessageHtmlParser->setQuoteSuffix($objActiveSkin->getQuoteSuffix());
					$objMessageHtmlParser->setParseUrl($this->m_objConfig->parseUrl());
					$objMessageHtmlParser->setParseImages($this->m_objConfig->parseImages());
					$objMessageHtmlParser->setParseStyle($this->m_objConfig->parseStyle());
					if($this->m_objConfig->doTextReplacements()){
						include_once(INCLUDEDIR."/cTextreplacementList.php");
						$objTextreplacementList = new cTextreplacementList();
						$objMessageHtmlParser->setReplacements($objTextreplacementList->getList());
					}
					$this->m_objTemplate->addData($dummy = array("msg"=>$objPrivateMessage->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																										 $this->m_objConfig->getDateFormat(),
																										 $iLastOnline,
																										 "",
																										 $objMessageHtmlParser)));

					if($objPrivateMessage->getDestinationUserId() == $objActiveUser->getId()){
						$objPrivateMessage->setMessageRead();
					}
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(6));	// invalid msg id
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(6));		// invalid msg id
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));		// not loged in
	}
}
?>