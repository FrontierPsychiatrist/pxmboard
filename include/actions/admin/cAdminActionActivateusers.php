<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cNotification.php");
require_once(INCLUDEDIR."/cUserStates.php");
require_once(INCLUDEDIR."/cUser.php");
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
 * handles the user activation
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionActivateusers extends cAdminAction{

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

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin() && !$this->m_objConfig->useDirectRegistration()){

			$objNotification = new cNotification();
			$objNotification->loadDataById(1);
			$sRegistrationMailSubject = $objNotification->getMessage();
			$objNotification->loadDataById(2);
			$sRegistrationMailBody = $objNotification->getMessage();
			$objNotification->loadDataById(3);
			$sRegistrationDeclineMailSubject = $objNotification->getMessage();
			$objNotification->loadDataById(4);
			$sRegistrationDeclineMailBody = $objNotification->getMessage();

			$objUser = new cUser();

			$this->m_sOutput .= "<h4>activate / delete users</h4>\n";
			$this->m_sOutput .= "<h3>activated users</h3>\n";

			$iUserActiveStatus = cUserStates::userActive();
			$arrDeleteUsers = &$this->m_objInputHandler->getArrFormVar("del",TRUE,TRUE,TRUE,"intval");
			foreach($this->m_objInputHandler->getArrFormVar("act",TRUE,TRUE,TRUE,"intval") as $iUserId){
				if(!in_array($iUserId,$arrDeleteUsers)){

					$this->m_sOutput .= "$iUserId -> ";

					if($objUser->loadDataById($iUserId)){
						$this->m_sOutput .= "found -> ";
						$objUser->setStatus($iUserActiveStatus);
						$sPassword = $objUser->generatePassword();
						if($objUser->updateData()){
							$this->m_sOutput .= "activated -> ";

							if(@mail($objUser->getPrivateMail(),
									 $sRegistrationMailSubject,
									 str_replace(array("%password%","%nickname%"),array($sPassword,$objUser->getNickName()),$sRegistrationMailBody),
									 "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster())){

								$this->m_sOutput .= "mail sent<br>\n";
							}
							else $this->m_sOutput .= "<span id=\"e\"> could not send mail to ".htmlspecialchars($objUser->getPrivateMail())."</span><br>\n";
						}
						else $this->m_sOutput .= "<span id=\"e\"> could not activate user</span><br>\n";
					}
					else $this->m_sOutput .= "<span id=\"e\"> invalid userid</span><br>\n";
				}
			}

			$this->m_sOutput .= "<h3>deleted users</h3>\n";
			foreach($arrDeleteUsers as $iUserId){
				if($objUser->loadDataById($iUserId)){
					if($objUser->deleteData()){
						$this->m_sOutput .= "$iUserId -> deleted -> ";
						if(@mail($objUser->getPrivateMail(),
								 $sRegistrationDeclineMailSubject,
								 str_replace(array("%nickname%","%reason%"),array($objUser->getNickName(),$this->m_objInputHandler->getStringFormVar("r$iUserId","notification",TRUE,TRUE,"rtrim")),$sRegistrationDeclineMailBody),
								  "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster())){

							$this->m_sOutput .= "mail sent<br>\n";
						}
						else $this->m_sOutput .= "<span id=\"e\"> could not send mail to ".htmlspecialchars($objUser->getPrivateMail())."</span><br>\n";
					}
					else $this->m_sOutput .= "$iUserId -> <span id=\"e\">could not delete data</span><br>\n";
				}
				else $this->m_sOutput .= "$iUserId -> <span id=\"e\">user not found</span><br>\n";
			}
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>