<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cSkinList.php");
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
 * displays the general config edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.10 $
 */
class cAdminActionConfigform extends cAdminAction{

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
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('update configuration?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admconfigsave\"><table border=\"1\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">general configuration</td></tr>\n";

			$this->m_sOutput .= "<tr><td>default skin</td><td><select name=\"skinid\" size=\"1\">\n";

			$arrAvailableTemplateEngines = &$this->m_objConfig->getAvailableTemplateEngines();
			$objSkinList = new cSkinList();
			foreach($objSkinList->getList() as $objSkin){
				$this->m_sOutput .= "<option value=\"".$objSkin->getId().(($this->m_objConfig->getDefaultSkinId() == $objSkin->getId())?"\" selected>":"\">").htmlspecialchars($objSkin->getName())."</option>";
			}
			$this->m_sOutput .= "</select></td></tr>\n";

			$this->m_sOutput .= $this->_getCheckboxField("banner","1","use dynamic banners?",$this->m_objConfig->useBanners());
			$this->m_sOutput .= $this->_getCheckboxField("quickpost","1","enable quickpost?",$this->m_objConfig->useQuickPost());
			$this->m_sOutput .= $this->_getCheckboxField("guestpost","1","allow guests to post messages?",$this->m_objConfig->useGuestPost());
			$this->m_sOutput .= $this->_getCheckboxField("signatures","1","enable signatures?",$this->m_objConfig->useSignatures(FALSE));
			$this->m_sOutput .= $this->_getCheckboxField("directregistration","1","enable direct registration?",$this->m_objConfig->useDirectRegistration());
			$this->m_sOutput .= $this->_getCheckboxField("uniquemail","1","unique registration mail adr?",$this->m_objConfig->uniqueRegistrationMails());
			$this->m_sOutput .= $this->_getCheckboxField("countviews","1","count thread views?",$this->m_objConfig->countViews());
			$this->m_sOutput .= $this->_getCheckboxField("parseurl","1","parse url tags in private messages?",$this->m_objConfig->parseUrl());
			$this->m_sOutput .= $this->_getCheckboxField("parsestyle","1","parse style tags in private messages?",$this->m_objConfig->parseStyle());
			$this->m_sOutput .= "<tr><td>date format (<a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_blank\">php style</a>)</td><td>";
			$this->m_sOutput .= $this->_getTextField("dateformat",$this->m_objInputHandler->getInputSize("dateformat"),$this->m_objConfig->getDateFormat())."</td></tr>";
			$this->m_sOutput .= $this->_getTextField("timeoffset",2,$this->m_objConfig->getTimeOffset(FALSE),"time offset (hours)");
			$this->m_sOutput .= $this->_getTextField("onlinetime",5,$this->m_objConfig->getOnlineTime(),"time for onlinelist (seconds; 0 = don't log online time)");
			$this->m_sOutput .= $this->_getTextField("threadsizelimit",5,$this->m_objConfig->getThreadSizeLimit(),"message limit per thread (0 = no limit)");
			$this->m_sOutput .= $this->_getTextField("userperpage",3,$this->m_objConfig->getUserPerPage(),"user per page (online, search & admin)");
			$this->m_sOutput .= $this->_getTextField("threadsperpage",3,$this->m_objConfig->getThreadsPerPage(),"threads per page (msg index)");
			$this->m_sOutput .= $this->_getTextField("messageheaderperpage",3,$this->m_objConfig->getMessageHeaderPerPage(),"messages per page (search)");
			$this->m_sOutput .= $this->_getTextField("messagesperpage",3,$this->m_objConfig->getMessagesPerPage(),"messages per page (flat mode)");
			$this->m_sOutput .= $this->_getTextField("privatemessagesperpage",3,$this->m_objConfig->getPrivateMessagesPerPage(),"private messages per page");
			$this->m_sOutput .= $this->_getTextField("mailwebmaster",$this->m_objInputHandler->getInputSize("email"),$this->m_objConfig->getMailWebmaster(),"mail webmaster");
			$this->m_sOutput .= $this->_getTextField("quotechar",$this->m_objInputHandler->getInputSize("character"),$this->m_objConfig->getQuoteChar(),"quote character");
			$this->m_sOutput .= $this->_getTextField("quotesubject",$this->m_objInputHandler->getInputSize("quotesubject"),$this->m_objConfig->getQuoteSubject(),"quote subject");
			$this->m_sOutput .= $this->_getTextField("skindir",$this->m_objInputHandler->getInputSize("directory"),$this->m_objConfig->getSkinDirectory(),"skin dir");
			$this->m_sOutput .= $this->_getTextField("imgsize",10,$this->m_objConfig->getMaxProfileImgSize(),"max profile img size (byte)");
			$this->m_sOutput .= $this->_getTextField("imgheight",5,$this->m_objConfig->getMaxProfileImgHeight(),"max profile img height (pixel)");
			$this->m_sOutput .= $this->_getTextField("imgwidth",5,$this->m_objConfig->getMaxProfileImgWidth(),"max profile img width (pixel)");
			$this->m_sOutput .= $this->_getTextField("imgdir",$this->m_objInputHandler->getInputSize("directory"),$this->m_objConfig->getProfileImgDirectory(),"profile img dir");
			$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"update data\">&nbsp;<input type=\"reset\" value=\"reset data\"></td></tr>\n";
			$this->m_sOutput .= "</table></form>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>