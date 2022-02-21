<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cUserProfile.php");
require_once(INCLUDEDIR."/cProfileConfig.php");
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
 * saves a user profile
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.7 $
 */
class cActionUserProfileSave extends cAction{

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

			$objProfileConfig = new cProfileConfig();
			$arrSlotList = &$objProfileConfig->getSlotList();

			$objUserProfile = new cUserProfile($arrSlotList);

			if($objUserProfile->loadDataById($objActiveUser->getId())){
				$objUserProfile->setCity($this->m_objInputHandler->getStringFormVar("city","city",TRUE,TRUE,"trim"));
				$objUserProfile->setPublicMail($this->m_objInputHandler->getStringFormVar("email","email",TRUE,TRUE,"trim"));
				$objUserProfile->setSignature($this->m_objInputHandler->getStringFormVar("signature","signature",TRUE,TRUE,"rtrim"));
				$objUserProfile->setFirstName($this->m_objInputHandler->getStringFormVar("fname","firstname",TRUE,TRUE,"trim"));
				$objUserProfile->setLastName($this->m_objInputHandler->getStringFormVar("lname","lastname",TRUE,TRUE,"trim"));

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

				$bSuccess = FALSE;

				if($objUserProfile->updateData()){
					if($this->m_objConfig->getMaxProfileImgSize()>0){		// file upload
						$objFileUpload = &$this->m_objInputHandler->getFileFormObject("pic");
						if($objFileUpload->isUploadedFile()){
							$arrAllowedImgTypes = $this->m_objConfig->getProfileImgTypes();

							if(($objFileUpload->getFileSize()<=$this->m_objConfig->getMaxProfileImgSize()) &&
							    in_array($objFileUpload->getFileType(),array_keys($arrAllowedImgTypes))){

								$arrImgSize = getimagesize($objFileUpload->getFileTmpName());
								if(($arrImgSize[0]>0) && ($arrImgSize[0]<=$this->m_objConfig->getMaxProfileImgWidth()) &&
								   ($arrImgSize[1]>0) && ($arrImgSize[1]<=$this->m_objConfig->getMaxProfileImgHeight())){

									$objUserProfile->addImage($this->m_objConfig->getProfileImgDirectory(),
															  $this->m_objConfig->getProfileImgDirectorySplit(),
															  $objFileUpload->getFileTmpName(),
															  $arrAllowedImgTypes[$objFileUpload->getFileType()]);
									$bSuccess = TRUE;
								}
								else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(15));// file upload error
							}
							else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(15));	// file upload error
						}
						else{
							if($this->m_objInputHandler->getIntFormVar("delpic",TRUE,TRUE)==1){
								$objUserProfile->deleteImage($this->m_objConfig->getProfileImgDirectory());
							}
							$bSuccess = TRUE;
						}
					}
					else $bSuccess = TRUE;

					if($bSuccess){
						$this->m_objTemplate = &$this->_getTemplateObject("userprofilesaveconfirm");
						$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
					}
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(8));	// could not insert data
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));// user id invalid
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
	}
}
?>