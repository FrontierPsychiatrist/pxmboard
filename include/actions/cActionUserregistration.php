<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cForbiddenMailList.php");
require_once(INCLUDEDIR."/cProfileConfig.php");
require_once(INCLUDEDIR."/cUserProfile.php");
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
 * registers a user
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.7 $
 */
class cActionUserregistration extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if(!$this->m_objConfig->getActiveUser()){
			$sNickName = $this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,TRUE,"trim");
			$sEmail = $this->m_objInputHandler->getStringFormVar("email","email",TRUE,TRUE,"trim");

			if(empty($sNickName) || empty($sEmail)){
				$this->m_objTemplate = &$this->_getTemplateObject("userregistrationform");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
			}
			else{
				$bSuccess = FALSE;

				$objForbiddenMailList = new cForbiddenMailList();
				$objProfileConfig = new cProfileConfig();
				$arrSlotList = &$objProfileConfig->getSlotList();

				$objUserProfile = new cUserProfile($arrSlotList);
				if($objUserProfile->setRegistrationMail($sEmail,$objForbiddenMailList->getList())){
					$objUserProfile->setNickName($sNickName);
					$objUserProfile->setCity($this->m_objInputHandler->getStringFormVar("city","city",TRUE,TRUE,"trim"));
					$objUserProfile->setPrivateMail($sEmail);
					$objUserProfile->setPublicMail($this->m_objInputHandler->getStringFormVar("pubemail","email",TRUE,TRUE,"trim"));
					$objUserProfile->setRegistrationTimestamp($this->m_objConfig->getAccessTimestamp());
					$objUserProfile->setSignature($this->m_objInputHandler->getStringFormVar("signature","signature",TRUE,TRUE,"rtrim"));
					$objUserProfile->setFirstName($this->m_objInputHandler->getStringFormVar("fname","firstname",TRUE,TRUE,"trim"));
					$objUserProfile->setLastName($this->m_objInputHandler->getStringFormVar("lname","lastname",TRUE,TRUE,"trim"));

					$sPassword = $objUserProfile->generatePassword();

					$objUserProfile->setStatus($this->m_objConfig->useDirectRegistration()?(cUserStates::userActive()):(cUserStates::userNotActivated()));

					if($objUserProfile->insertData($this->m_objConfig->uniqueRegistrationMails())){	// insert profiledata >>>
						foreach($arrSlotList as $sKey=>$arrVal){
							if($arrVal[0]=='i'){
								$objUserProfile->setAdditionalDataElement($sKey,$this->m_objInputHandler->getIntFormVar($sKey,TRUE,TRUE,FALSE));
							}
							else{
								$sValue = $this->m_objInputHandler->getStringFormVar($sKey,"",TRUE,TRUE,"trim");
								if(strlen($sValue)>$arrVal[1]){
									$sValue = substr($sValue,0,$arrVal[1]);
								}
								$objUserProfile->setAdditionalDataElement($sKey,$sValue);
							}
						}
						$objUserProfile->setLastUpdateTimestamp($this->m_objConfig->getAccessTimestamp());
						$objUserProfile->updateData();											// <<< insert profiledata

						if($this->m_objConfig->useDirectRegistration()){

							include_once(INCLUDEDIR."/cNotification.php");
							$objRegistrationMailSubject = new cNotification();
							$objRegistrationMailSubject->loadDataById(1);
							$objRegistrationMailBody = new cNotification();
							$objRegistrationMailBody->loadDataById(2);

							if(@mail($objUserProfile->getPrivateMail(),
									 $objRegistrationMailSubject->getMessage(),
									 str_replace(array("%password%","%nickname%"),array($sPassword,$objUserProfile->getNickName()),$objRegistrationMailBody->getMessage()),
									 "From: ".$this->m_objConfig->getMailWebmaster()."\nReply-To: ".$this->m_objConfig->getMailWebmaster())){

								$bSuccess = TRUE;
							}
							else{
								$this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(16));// could not send email
							}
						}
						else $bSuccess = TRUE;

						if($bSuccess){
							$this->m_objTemplate = &$this->_getTemplateObject("userregistrationconfirm");
							$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
							$this->m_objTemplate->addData($dummy = array("user"=>array("nickname"	=>$objUserProfile->getNickName(),
																					   "email"		=>$objUserProfile->getPrivateMail())));
						}
					}
					else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(25));// user already registered
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(21));	// invalid email
			}
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(11));			// already loged in
	}
}
?>