<?php
require_once(INCLUDEDIR."/actions/cActionBoardlist.php");
require_once(INCLUDEDIR."/cUserStates.php");
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
 * user login
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/20 18:57:10 $
 * @version $Revision: 1.9 $
 */
class cActionLogin extends cActionBoardlist{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
		$sNickName = $this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,FALSE,"trim");
		$sPassword = $this->m_objInputHandler->getStringFormVar("pass","password",TRUE,FALSE,"trim");
		$objError = NULL;

		if(!$this->m_objConfig->getActiveUser() && (!empty($sNickName))){

			$objUser = new cUserConfig();

			if($objUser->loadDataByNickName($sNickName)){
				if(!$objUser->validatePassword($sPassword)){
					$objError = new cError(3);				// invalid password
				}
			}
			else{
				$objError = new cError(2);					// user not found
			}
			if(!is_object($objError)){
				if($objUser->getStatus() == cUserStates::userActive()){

					$objSession = &$this->m_objConfig->getSession();
					$objSession->startSession();
					$objSession->setSessionVar("activeuser",$objUser);

					$this->m_objConfig->setActiveUser($objUser);
					$this->m_objConfig->initSkin();
				}
				else $objError = new cError(12);				// forbidden
			}
		}

		cActionBoardlist::performAction();

		if(is_object($objError)){
			$this->m_objTemplate->addData($dummy = array("error"=>$objError->getDataArray()));
		}
	}
}
?>