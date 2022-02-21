<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cProfileConfig.php");
require_once(INCLUDEDIR."/cUserStates.php");
require_once(INCLUDEDIR."/cBoardList.php");
require_once(INCLUDEDIR."/cUserAdmin.php");
require_once(INCLUDEDIR."/cSkinList.php");
require_once(INCLUDEDIR."/parser/cParser.php");
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
 * displays the user edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.13 $
 */
class cAdminActionUserform extends cAdminAction{

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
				$arrSlotList = $objProfileConfig->getSlotList();

				$objUser = new cUserAdmin($arrSlotList);

				if($objUser->loadDataById($iUserId)){
					$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('update userdata?')\">".$this->_getHiddenField("mode","admusersave")."<table border=\"0\">\n";
					$this->m_sOutput .= "<tr valign=\"top\"><td><table border=\"1\" id=\"c\" width=\"100%\">\n";
					$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">profile</td></tr>\n";
					$this->m_sOutput .= "<tr><td>ID</td><td>$iUserId".$this->_getHiddenField("usrid",$iUserId)."</td></tr>\n";
					$this->m_sOutput .= $this->_getTextField("nick",$this->m_objInputHandler->getInputSize("nickname"),$objUser->getNickName(),"nickname");
					$this->m_sOutput .= $this->_getTextField("fname",$this->m_objInputHandler->getInputSize("firstname"),$objUser->getFirstName(),"firstname");
					$this->m_sOutput .= $this->_getTextField("lname",$this->m_objInputHandler->getInputSize("lastname"),$objUser->getLastName(),"lastname");
					$this->m_sOutput .= $this->_getTextField("city",$this->m_objInputHandler->getInputSize("city"),$objUser->getCity(),"city");
					$this->m_sOutput .= $this->_getTextField("pmail",$this->m_objInputHandler->getInputSize("email"),$objUser->getPublicMail(),"public mailadr");
					$this->m_sOutput .= $this->_getTextField("prmail",$this->m_objInputHandler->getInputSize("email"),$objUser->getPrivateMail(),"private mailadr");
					$this->m_sOutput .= "<tr><td>registration mailadr</td><td>".htmlspecialchars($objUser->getRegistrationMail())."</td></tr>\n";
					$this->m_sOutput .= "<tr><td>signature</td><td><textarea cols=\"20\" rows=\"3\" name=\"signature\">\n".htmlspecialchars($objUser->getSignature())."</textarea></td></tr>\n";

					foreach($arrSlotList as $sKey=>$arrVal){
						switch($arrVal[0]){
							case	'a'	:	$this->m_sOutput .= "<tr><td>".htmlspecialchars($sKey)."</td><td><textarea cols=\"20\" rows=\"3\" name=\"".htmlspecialchars("profile_".$sKey)."\">".htmlspecialchars($objUser->getAdditionalDataElement($sKey))."</textarea></td></tr>\n";
											break;
							case	's'	:	$this->m_sOutput .= $this->_getTextField("profile_".$sKey,$arrVal[1],$objUser->getAdditionalDataElement($sKey),$sKey);
											break;
							default		:	$this->m_sOutput .= $this->_getTextField("profile_".$sKey,10,$objUser->getAdditionalDataElement($sKey),$sKey);
											break;
						}
					}

					$sDateFormat = $this->m_objConfig->getDateFormat();
					$iTimeOffset = $this->m_objConfig->getTimeOffset()*3600;

					$this->m_sOutput .= $this->_getCheckboxField("delpic",1,"delete profile picture?");
					$this->m_sOutput .= "<tr><td>date of registration</td><td>".(($objUser->getRegistrationTimestamp()>0)?date($sDateFormat,($objUser->getRegistrationTimestamp()+$iTimeOffset)):0)."</td></tr>";
					$this->m_sOutput .= "<tr><td>last online</td><td>".(($objUser->getLastOnlineTimestamp()>0)?date($sDateFormat,($objUser->getLastOnlineTimestamp()+$iTimeOffset)):0)."</td></tr>\n";
					$this->m_sOutput .= "<tr><td>last profile edit</td><td>".(($objUser->getLastUpdateTimestamp()>0)?date($sDateFormat,($objUser->getLastUpdateTimestamp()+$iTimeOffset)):0)."</td></tr>\n";
					$this->m_sOutput .= "<tr><td>quantity of messages</td><td>".$objUser->getMessageQuantity()."</td></tr>\n";
					$this->m_sOutput .= "</table></td><td><table border=\"1\" id=c width=\"100%\">\n";
					$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\"><b>rights</b></td></tr>\n";

					$this->m_sOutput .= "<tr><td>state</td><td><select name=\"state\" size=\"1\">";
					foreach(cUserStates::getUserStates() as $iKey=>$sVal){
						$this->m_sOutput .= "<option value=\"".$iKey.(($objUser->getStatus()==$iKey)?"\" selected>":"\">").htmlspecialchars($sVal)."</option>";
					}
					$this->m_sOutput .= "</select></td></tr>\n";

					$this->m_sOutput .= $this->_getCheckboxField("post",1,"post?",$objUser->isPostAllowed());
					$this->m_sOutput .= $this->_getCheckboxField("edit",1,"edit?",$objUser->isEditAllowed());
					$this->m_sOutput .= $this->_getCheckboxField("admin",1,"administrator?",$objUser->isAdmin()," onclick=\"return confirm('change admin status?')\"");
					$this->m_sOutput .= $this->_getCheckboxField("high",1,"highlight user?",$objUser->highlightUser());

					$this->m_sOutput .= "<tr><td>moderator for</td><td>\n";
					$this->m_sOutput .= "<select name=\"mod[]\" size=\"4\" multiple>\n";
					$arrBoardIds = array();
					$objUser->loadModData();
					foreach($objUser->getModeratedBoards() as $objBoard){
						$arrBoardIds[] = $objBoard->getId();
					}

					$objParser = new cParser();	// dummy parser

					$objBoardList = new cBoardList();
					$objBoardList->loadBasicData();
					foreach($objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat(),0,$objParser) as $arrBoard){
						$this->m_sOutput .= "<option value=\"".$arrBoard["id"].(in_array($arrBoard["id"],$arrBoardIds)?"\" selected>":"\">").htmlspecialchars($arrBoard["name"])."</option>";
					}

					$this->m_sOutput .= "</table><br><br><table border=\"1\" id=\"c\" width=\"100%\">\n";
					$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\"><b>new password</b></td></tr>\n";
					$this->m_sOutput .= $this->_getPasswordField("pass1",$this->m_objInputHandler->getInputSize("password"),"password");
					$this->m_sOutput .= $this->_getPasswordField("pass2",$this->m_objInputHandler->getInputSize("password"),"repeat");

					$this->m_sOutput .= "</table><br><br><table border=\"1\" id=\"c\" width=\"100%\">\n";
					$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\"><b>configuration</b></td></tr>\n";
					$this->m_sOutput .= "<tr><td>skin</td><td><select name=\"skinid\" size=\"1\">";
					$this->m_sOutput .= "<option value=\"0\">default</option>";

					$arrAvailableTemplateEngines = &$this->m_objConfig->getAvailableTemplateEngines();
					$objSkinList = new cSkinList();
					foreach($objSkinList->getList() as $objSkin){
						if(array_intersect($arrAvailableTemplateEngines,$objSkin->getSupportedTemplateEngines())){
							$this->m_sOutput .= "<option value=\"".$objSkin->getId().(($objUser->getSkinId() == $objSkin->getId())?"\" selected>":"\">").htmlspecialchars($objSkin->getName())."</option>";
						}
					}

					$this->m_sOutput .= "</select></td></tr>\n";

					$this->m_sOutput .= $this->_getTextField("tframe",3,$objUser->getTopFrameSize(),"top frame");
					$this->m_sOutput .= $this->_getTextField("bframe",3,$objUser->getBottomFrameSize(),"bottom frame");

					$sSortMode = $objUser->getThreadListSortMode();
					$this->m_sOutput .= "<tr><td>sortmode</td><td><select name=\"sort\" size=\"1\">";
					$this->m_sOutput .= "<option value=\"thread\"".((strcasecmp($sSortMode,"thread")==0)?" selected":"").">thread</option>\n";
					$this->m_sOutput .= "<option value=\"last\"".((strcasecmp($sSortMode,"last")==0)?" selected":"").">last reply</option>\n";
					$this->m_sOutput .= "<option value=\"nickname\"".((strcasecmp($sSortMode,"nickname")==0)?" selected":"").">nickname</option>\n";
					$this->m_sOutput .= "<option value=\"subject\"".((strcasecmp($sSortMode,"subject")==0)?" selected":"").">subject</option>\n";
					$this->m_sOutput .= "<option value=\"replies\"".((strcasecmp($sSortMode,"replies")==0)?" selected":"").">replies</option>\n";
					$this->m_sOutput .= "<option value=\"views\"".((strcasecmp($sSortMode,"views")==0)?" selected":"").">views</option>\n";
					$this->m_sOutput .= "</select></td></tr>\n";


					$this->m_sOutput .= $this->_getTextField("toff",2,$objUser->getTimeOffset()*3600,"timeoffset");
					$this->m_sOutput .= $this->_getCheckboxField("pimg",1,"parse image?",$objUser->parseImages());
					$this->m_sOutput .= $this->_getCheckboxField("repl",1,"do textreplacements?",$objUser->doTextReplacements());
					$this->m_sOutput .= $this->_getCheckboxField("visible",1,"is visible?",$objUser->isVisible());
					$this->m_sOutput .= $this->_getCheckboxField("privnot",1,"private message notification?",$objUser->sendPrivateMessageNotification());
					$this->m_sOutput .= $this->_getCheckboxField("showsig",1,"show signatures?",$objUser->showSignatures());

					$this->m_sOutput .= "</table></td></tr>\n";
					$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><br><input type=\"submit\" value=\"update data\">&nbsp;<input type=\"reset\" value=\"reset data\"></td></tr></table></form>";
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