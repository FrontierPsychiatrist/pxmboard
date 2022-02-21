<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * save the general config
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.11 $
 */
class cAdminActionConfigsave extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		$this->m_sOutput .= $this->_getHead();

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){

			$this->m_sOutput .= "<h4>general configuration</h4>\n";

			$this->m_objConfig->setDefaultSkinId($this->m_objInputHandler->getIntFormVar("skinid",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUseBanners($this->m_objInputHandler->getIntFormVar("banner",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUseQuickPost($this->m_objInputHandler->getIntFormVar("quickpost",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUseGuestPost($this->m_objInputHandler->getIntFormVar("guestpost",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUseSignatures($this->m_objInputHandler->getIntFormVar("signatures",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUseDirectRegistration($this->m_objInputHandler->getIntFormVar("directregistration",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUniqueRegistrationMails($this->m_objInputHandler->getIntFormVar("uniquemail",TRUE,TRUE,TRUE));
			$this->m_objConfig->setCountViews($this->m_objInputHandler->getIntFormVar("countviews",TRUE,TRUE,TRUE));
			$this->m_objConfig->setParseUrl($this->m_objInputHandler->getIntFormVar("parseurl",TRUE,TRUE,TRUE));
			$this->m_objConfig->setParseStyle($this->m_objInputHandler->getIntFormVar("parsestyle",TRUE,TRUE,TRUE));

			$this->m_objConfig->setDateFormat($this->m_objInputHandler->getStringFormVar("dateformat","dateformat",TRUE,TRUE,"trim"));
			$this->m_objConfig->setTimeOffset($this->m_objInputHandler->getIntFormVar("timeoffset",TRUE,TRUE));
			$this->m_objConfig->setOnlineTime($this->m_objInputHandler->getIntFormVar("onlinetime",TRUE,TRUE,TRUE));
			$this->m_objConfig->setThreadSizeLimit($this->m_objInputHandler->getIntFormVar("threadsizelimit",TRUE,TRUE,TRUE));
			$this->m_objConfig->setUserPerPage($this->m_objInputHandler->getIntFormVar("userperpage",TRUE,TRUE,TRUE));
		  	$this->m_objConfig->setThreadsPerPage($this->m_objInputHandler->getIntFormVar("threadsperpage",TRUE,TRUE,TRUE));
			$this->m_objConfig->setMessageHeaderPerPage($this->m_objInputHandler->getIntFormVar("messageheaderperpage",TRUE,TRUE,TRUE));
			$this->m_objConfig->setMessagesPerPage($this->m_objInputHandler->getIntFormVar("messagesperpage",TRUE,TRUE,TRUE));
			$this->m_objConfig->setPrivateMessagesPerPage($this->m_objInputHandler->getIntFormVar("privatemessagesperpage",TRUE,TRUE,TRUE));

			$this->m_objConfig->setMailWebmaster($this->m_objInputHandler->getStringFormVar("mailwebmaster","email",TRUE,TRUE,"trim"));

			$this->m_objConfig->setQuoteChar($this->m_objInputHandler->getStringFormVar("quotechar","character",TRUE,TRUE,"trim"));
			$this->m_objConfig->setQuoteSubject($this->m_objInputHandler->getStringFormVar("quotesubject","quotesubject",TRUE,TRUE,"ltrim"));

			$this->m_objConfig->setSkinDirectory($this->m_objInputHandler->getStringFormVar("skindir","directory",TRUE,TRUE,"trim"));
			$this->m_objConfig->setMaxProfileImgSize($this->m_objInputHandler->getIntFormVar("imgsize",TRUE,TRUE,TRUE));
			$this->m_objConfig->setMaxProfileImgHeight($this->m_objInputHandler->getIntFormVar("imgheight",TRUE,TRUE,TRUE));
			$this->m_objConfig->setMaxProfileImgWidth($this->m_objInputHandler->getIntFormVar("imgwidth",TRUE,TRUE,TRUE));
			$this->m_objConfig->setProfileImgDirectory($this->m_objInputHandler->getStringFormVar("imgdir","directory",TRUE,TRUE,"trim"));

			if($this->m_objConfig->updateData()){
				$this->m_sOutput .=  "<h3>general configuration saved</h3>";
			}
			else{
				$this->m_sOutput .=  "<h3 id=\"e\">could not save general configuration</h3>";
			}
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>