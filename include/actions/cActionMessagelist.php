<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cMessageList.php");
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
 * flat message view for a thread
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.7 $
 */
class cActionMessagelist extends cAction{

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

			if($iThreadId = $this->m_objInputHandler->getIntFormVar("thrdid",TRUE,TRUE,TRUE)){
				$objMessageList =  new cMessageList($objActiveBoard->getId(),$iThreadId);
				$objMessageList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getMessagesPerPage());

				$iLastOnlineTimestamp = 0;
				if($objActiveUser = &$this->m_objConfig->getActiveUser()){
					$iLastOnlineTimestamp = $objActiveUser->getLastOnlineTimestamp();
				}

				$this->m_objTemplate = &$this->_getTemplateObject("messagelistflat");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("previd"	=>$objMessageList->getPrevPageId(),
																		   			 "nextid"	=>$objMessageList->getNextPageId(),
																					 "curid"	=>$objMessageList->getCurPageId(),
																					 "count"	=>$objMessageList->getPageCount(),
																					 "thrdid"	=>$iThreadId)));

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
				$this->m_objTemplate->addData($dummy = array("msg"=>$objMessageList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																								  $this->m_objConfig->getDateFormat(),
																								  $iLastOnlineTimestamp,
																								  "",
																								  $objMessageHtmlParser)));
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(10));// missing thread id
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));		// missing board id
	}
}
?>