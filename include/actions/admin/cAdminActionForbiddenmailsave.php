<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cForbiddenMailList.php");
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
 * save the forbidden mail adr.
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.5 $
 */
class cAdminActionForbiddenmailsave extends cAdminAction{

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
			$this->m_sOutput .= "<h4>save forbidden mails</h4>\n";

			$iMailLength = $this->m_objInputHandler->getInputSize("email");
			$arrForbiddenMails = array();
			foreach(explode("\n",$this->m_objInputHandler->getStringFormVar("forbmail","",TRUE,TRUE,"trim")) as $sForbiddenMail){
				$iForbiddenMailLength = strlen($sForbiddenMail);
				if($iForbiddenMailLength>0 && $iForbiddenMailLength<=$iMailLength){
					$arrForbiddenMails[] = $sForbiddenMail;
				}
			}

			$objForbiddenMail = new cForbiddenMailList();
			if($objForbiddenMail->updateList($arrForbiddenMails)){
				$this->m_sOutput .= "<h3>forbidden mail adr. saved</h3>";
			}
			else{
				$this->m_sOutput .= "<h3 id=\"e\">could not save forbidden mail adr. data</h3>";
			}
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>