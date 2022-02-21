<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cSkin.php");
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
 * saves a user configuration
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.11 $
 */
class cActionUserconfigsave extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		// without reference so that userdata will be used for next login
		if($objActiveUser = $this->m_objConfig->getActiveUser()){

			$objSession = &$this->m_objConfig->getSession();
			if($this->m_objInputHandler->getIntFormVar("cookie",TRUE,TRUE)>0){
				$objSession->setCookieVar("ticket",$objActiveUser->createNewTicket(),$this->m_objConfig->getAccessTimestamp()+31536000);
			}
			else{
				if(strlen($objSession->getCookieVar("ticket"))>0){
					$objSession->setCookieVar("ticket","",$this->m_objConfig->getAccessTimestamp()-3600);
				}
			}

			$objActiveUser->setIsVisible($this->m_objInputHandler->getIntFormVar("visible",TRUE,TRUE,TRUE));
			$objSkin = new cSkin();
			if($objSkin->loadDataById($this->m_objInputHandler->getIntFormVar("skinid",TRUE,TRUE,TRUE))
				&& array_intersect($this->m_objConfig->getAvailableTemplateEngines(),$objSkin->getSupportedTemplateEngines())){
					$objActiveUser->setSkinId($objSkin->getId());
			}
			$objActiveUser->setFrameSize($this->m_objInputHandler->getIntFormVar("ft",TRUE,TRUE,TRUE),
										 $this->m_objInputHandler->getIntFormVar("fb",TRUE,TRUE,TRUE));
			$objActiveUser->setThreadListSortMode($this->m_objInputHandler->getStringFormVar("sort","sortmode",TRUE,TRUE,"trim"));
			$objActiveUser->setTimeOffset($this->m_objInputHandler->getIntFormVar("toff",TRUE,TRUE));
			$objActiveUser->setParseImages($this->m_objInputHandler->getIntFormVar("pimg",TRUE,TRUE,TRUE));
			$objActiveUser->setDoTextReplacements($this->m_objInputHandler->getIntFormVar("repl",TRUE,TRUE,TRUE));
			$objActiveUser->setPrivateMail($this->m_objInputHandler->getStringFormVar("email","email",TRUE,TRUE,"trim"));
			$objActiveUser->setSendPrivateMessageNotification($this->m_objInputHandler->getIntFormVar("privnotification",TRUE,TRUE,TRUE));
			$objActiveUser->setShowSignatures($this->m_objInputHandler->getIntFormVar("showsignatures",TRUE,TRUE,TRUE));

			if($objActiveUser->updateData()){
				$this->m_objTemplate = &$this->_getTemplateObject("userconfigsaveconfirm");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(8));	// could not insert data
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
	}
}
?>