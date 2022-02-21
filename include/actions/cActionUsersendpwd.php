<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cUser.php");
require_once(INCLUDEDIR."/cNotification.php");
require_once(INCLUDEDIR."/cUserStates.php");
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
 * send the password to the user
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.8 $
 */
class cActionUsersendpwd extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if(!$objActiveUser = &$this->m_objConfig->getActiveUser()){

			$sPasswordKey = $this->m_objInputHandler->getStringFormVar("key","key",TRUE,TRUE,"trim");
			$sNickName = $this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,TRUE,"trim");
			$sEmail = $this->m_objInputHandler->getStringFormVar("email","email",TRUE,TRUE,"trim");

			if(!empty($sNickName)){
				$objUser = new cUser();
				if($objUser->loadDataByNickName($sNickName)){
					if($objUser->getStatus() == cUserStates::userActive()){
						if(strcasecmp($sEmail,$objUser->getPrivateMail())==0){

							$objNotification = new cNotification();
							$objNotification->loadDataById(6);
							$sPasswordMailSubject = $objNotification->getMessage();
							$objNotification->loadDataById(7);
							$sPasswordMailBody = $objNotification->getMessage();

							if(@mail($objUser->getPrivateMail(),
									 $sPasswordMailSubject,
									 str_replace(array("%key%","%nickname%"),array($objUser->createNewPasswordKey(),$objUser->getNickName()),$sPasswordMailBody),
									 "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster())){

								$this->m_objTemplate = &$this->_getTemplateObject("usersendpwdrequestconfirm");
								$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
								$this->m_objTemplate->addData($dummy = array("user"=>array("id"			=>$objUser->getId(),
																						   "nickname"	=>$objUser->getNickName(),
																						   "email"		=>$objUser->getPrivateMail())));
							}
							else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(16));// could not send email
						}
						else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(24));	// data does not match
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));		// forbidden
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(2));				// invalid nickname
			}
			else if(!empty($sPasswordKey)){
				$objUser = new cUser();
				if($objUser->loadDataByPasswordKey($sPasswordKey)){
					if($objUser->getStatus() == cUserStates::userActive()){

						$objNotification = new cNotification();
						$objNotification->loadDataById(8);
						$sPasswordMailSubject = $objNotification->getMessage();
						$objNotification->loadDataById(9);
						$sPasswordMailBody = $objNotification->getMessage();

						$sPassword = $objUser->generatePassword();
						if($objUser->updateData()){
							if(@mail($objUser->getPrivateMail(),
									 $sPasswordMailSubject,
									 str_replace(array("%password%","%nickname%"),array($sPassword,$objUser->getNickName()),$sPasswordMailBody),
									 "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster())){

								$this->m_objTemplate = &$this->_getTemplateObject("usersendpwdconfirm");
								$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
								$this->m_objTemplate->addData($dummy = array("user"=>array("id"			=>$objUser->getId(),
																						   "nickname"	=>$objUser->getNickName(),
																						   "email"		=>$objUser->getPrivateMail())));
							}
							else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(16));// could not send email
						}
						else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(8));	// could not insert data
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));	// forbidden
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));		// invalid userid
			}
			else{
				$this->m_objTemplate = &$this->_getTemplateObject("usersendpwdform");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
			}
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(11));	// already loged in
	}
}
?>