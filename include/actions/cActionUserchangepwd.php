<?php
require_once(INCLUDEDIR."/actions/cAction.php");
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
 * change the password of an user
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.7 $
 */
class cActionUserchangepwd extends cAction{

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
			$sPassword1 = $this->m_objInputHandler->getStringFormVar("pwd","password",TRUE,TRUE,"trim");
			$sPassword2 = $this->m_objInputHandler->getStringFormVar("pwdc","password",TRUE,TRUE,"trim");
			if(!empty($sPassword1) && !empty($sPassword2)){
				if($objActiveUser->changePassword($sPassword1,$sPassword2)){

					$objSession = &$this->m_objConfig->getSession();

					if(strlen($objSession->getCookieVar("ticket"))>0){
						$objSession->setCookieVar("ticket","",$this->m_objConfig->getAccessTimestamp()-3600);
					}

					$this->m_objTemplate = &$this->_getTemplateObject("userchangepwdconfirm");
					$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(23));// pwd not valid
			}
			else{
				$this->m_objTemplate = &$this->_getTemplateObject("userchangepwdform");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
			}
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
	}
}
?>