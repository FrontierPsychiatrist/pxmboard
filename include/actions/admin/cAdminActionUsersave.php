<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cProfileConfig.php");
require_once(INCLUDEDIR."/cUserAdmin.php");
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
 * save the user data
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.11 $
 */
class cAdminActionUsersave extends cAdminAction{

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

			$iUserId = $this->m_objInputHandler->getIntFormVar("usrid",TRUE,TRUE,TRUE);

			$this->m_sOutput .= "<h4>edit user data</h4>\n";

			if($iUserId>0){

				$objProfileConfig = new cProfileConfig();
				$arrSlotList = &$objProfileConfig->getSlotList();

				$objUser = new cUserAdmin($arrSlotList);

				if($objUser->loadDataById($iUserId)){
					$objUser->setNickName($this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,TRUE,"trim"));
					$objUser->setCity($this->m_objInputHandler->getStringFormVar("city","city",TRUE,TRUE,"trim"));
					$objUser->setPublicMail($this->m_objInputHandler->getStringFormVar("pmail","email",TRUE,TRUE,"trim"));
					$objUser->setPrivateMail($this->m_objInputHandler->getStringFormVar("prmail","email",TRUE,TRUE,"trim"));
					$objUser->setFirstName($this->m_objInputHandler->getStringFormVar("fname","firstname",TRUE,TRUE,"trim"));
					$objUser->setLastName($this->m_objInputHandler->getStringFormVar("lname","lastname",TRUE,TRUE,"trim"));
					$objUser->setSignature($this->m_objInputHandler->getStringFormVar("signature","signature",TRUE,TRUE,"rtrim"));
					$objUser->setHighlightUser($this->m_objInputHandler->getIntFormVar("high",TRUE,TRUE,TRUE));

					foreach($arrSlotList as $sKey=>$arrVal){
						if($arrVal[0]=='i'){
							$objUser->setAdditionalDataElement($sKey,$this->m_objInputHandler->getIntFormVar("profile_".$sKey,TRUE,TRUE,FALSE));
						}
						else{
							$sValue = $this->m_objInputHandler->getStringFormVar("profile_".$sKey,"",TRUE,TRUE,"trim");
							if(strlen($sValue)>$arrVal[1]){
								$sValue = substr($sValue,0,$arrVal[1]);
							}
							$objUser->setAdditionalDataElement($sKey,$sValue);
						}
					}

					$objUser->setStatus($this->m_objInputHandler->getIntFormVar("state",TRUE,TRUE,TRUE));
					$objUser->setPostAllowed($this->m_objInputHandler->getIntFormVar("post",TRUE,TRUE,TRUE));
					$objUser->setEditAllowed($this->m_objInputHandler->getIntFormVar("edit",TRUE,TRUE,TRUE));
					$objUser->setAdmin($this->m_objInputHandler->getIntFormVar("admin",TRUE,TRUE,TRUE));

					$objUser->setIsVisible($this->m_objInputHandler->getIntFormVar("visible",TRUE,TRUE,TRUE));
					$objUser->setSkinId($this->m_objInputHandler->getIntFormVar("skinid",TRUE,TRUE,TRUE));
					$objUser->setFrameSize($this->m_objInputHandler->getIntFormVar("tframe",TRUE,TRUE,TRUE),
										   $this->m_objInputHandler->getIntFormVar("bframe",TRUE,TRUE,TRUE));
					$objUser->setThreadListSortMode($this->m_objInputHandler->getStringFormVar("sort","sortmode",TRUE,TRUE,"trim"));
					$objUser->setTimeOffset($this->m_objInputHandler->getIntFormVar("toff",TRUE,TRUE));
					$objUser->setParseImages($this->m_objInputHandler->getIntFormVar("pimg",TRUE,TRUE,TRUE));
					$objUser->setDoTextReplacements($this->m_objInputHandler->getIntFormVar("repl",TRUE,TRUE,TRUE));
					$objUser->setSendPrivateMessageNotification($this->m_objInputHandler->getIntFormVar("privnot",TRUE,TRUE,TRUE));
					$objUser->setShowSignatures($this->m_objInputHandler->getIntFormVar("showsig",TRUE,TRUE,TRUE));

					$objUser->setModeratedBoardsById($this->m_objInputHandler->getArrFormVar("mod",TRUE,TRUE,TRUE,"intval"));

					if($objUser->updateData()){
						$objUser->updateModData();
						$this->m_sOutput .= "<h3>data saved</h3>";
					}
					else{
						$this->m_sOutput .= "<h3 id=\"e\">could not update data</h3>";
					}

					$sPassword1 = $this->m_objInputHandler->getStringFormVar("pass1","password",TRUE,TRUE,"trim");
					$sPassword2 = $this->m_objInputHandler->getStringFormVar("pass2","password",TRUE,TRUE,"trim");
					if(!empty($sPassword1) && !empty($sPassword2)){
						if($objUser->changePassword($sPassword1,$sPassword2)){
							$this->m_sOutput .= "<h3>password changed</h3>";
						}
						else{
							$this->m_sOutput .= "<h3 id=\"e\">could not change password</h3>";
						}
					}
					if($this->m_objInputHandler->getIntFormVar("delpic",TRUE,TRUE)==1){
						if($objUser->deleteImage($this->m_objConfig->getProfileImgDirectory())){
							$this->m_sOutput .= "<h3>picture removed</h3>";
						}
					}
				}
				else $this->m_sOutput .= "<h3 id=\"e\">invalid userid</h3>";
			}
			else $this->m_sOutput .= "<h3 id=\"e\">invalid userid</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>