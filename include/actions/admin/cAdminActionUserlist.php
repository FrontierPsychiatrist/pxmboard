<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cUserAdminList.php");
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
 * displays a list of users
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.8 $
 */
class cAdminActionUserlist extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		global $objDb;

		$this->m_sOutput .= $this->_getHead();

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){
			$this->m_sOutput .= "<h4>user list</h4>\n";

			$arrUserStates = cUserStates::getUserStates();

			$iUserStateFilter = $this->m_objInputHandler->getIntFormVar("filter",TRUE,TRUE,TRUE);
			$sSortMode = $this->m_objInputHandler->getStringFormVar("sort","sortmode",TRUE,TRUE,"trim");
			if($this->m_objInputHandler->getStringFormVar("direction","sortdirection",TRUE,TRUE,"trim")=="ASC"){
				$sSortDirection = "ASC";
				$sNewSortDirection = "DESC";
			}
			else{
				$sSortDirection = "DESC";
				$sNewSortDirection = "ASC";
			}

			if(!in_array($iUserStateFilter,array_keys($arrUserStates))){
				$iUserStateFilter = 0;
			}

			$this->m_sOutput .= "<table border=\"1\" id=\"c\"><tr><td id=\"h\">nickname</td>";

			// sort modes
			$sSortAttribute = "";
			if($sSortMode=="regi"){
				$this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=regi&direction=$sNewSortDirection&filter=$iUserStateFilter\">date of registration <img src=\"images/admintool/".strtolower($sNewSortDirection).".gif\" border=\"0\"></a></td>";
				$sSortAttribute = "u_registrationtstmp";
			}
			else $this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=regi&direction=$sSortDirection&filter=$iUserStateFilter\">date of registration</a></td>";
			if($sSortMode=="profile"){
				$this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=profile&direction=$sNewSortDirection&filter=$iUserStateFilter\">last profile change <img src=\"images/admintool/".strtolower($sNewSortDirection).".gif\" border=\"0\"></a></td>";
				$sSortAttribute = "u_profilechangedtstmp";
			}
			else $this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=profile&direction=$sSortDirection&filter=$iUserStateFilter\">last profile change</a></td>";
			if($sSortMode=="online"){
				$this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=online&direction=$sNewSortDirection&filter=$iUserStateFilter\">last online <img src=\"images/admintool/".strtolower($sNewSortDirection).".gif\" border=\"0\"></a></td>";
				$sSortAttribute = "u_lastonlinetstmp";
			}
			else $this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=online&direction=$sSortDirection&filter=$iUserStateFilter\">last online</a></td>";

			if($sSortMode=="posts"){
				$this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=posts&direction=$sNewSortDirection&filter=$iUserStateFilter\">posts <img src=\"images/admintool/".strtolower($sNewSortDirection).".gif\" border=\"0\"></a></td>";
				$sSortAttribute = "u_msgquantity";
			}
			else $this->m_sOutput .= "<td id=\"h\"><a href=\"pxmboard.php?mode=admuserlist&sort=posts&direction=$sSortDirection&filter=$iUserStateFilter\">posts</a></td>";

			// filter
			$this->m_sOutput .= "<td id=\"h\"><form action=\"pxmboard.php\" method=\"get\">";
			$this->m_sOutput .= $this->_getHiddenField("mode","admuserlist").$this->_getHiddenField("sort",$sSortMode).$this->_getHiddenField("direction",$sSortDirection);
			$this->m_sOutput .= "<select name=\"filter\" size=\"1\" onchange=\"this.form.submit();\">";
			$this->m_sOutput .= "<option value=\"-1\">state</option>";
			foreach($arrUserStates as $iKey=>$sVal){
				$this->m_sOutput .= "<option value=\"".$iKey.(($iUserStateFilter == $iKey)?"\" selected>":"\">").htmlspecialchars($sVal)."</option>";
			}
			$this->m_sOutput .= "</select></form></td></tr>\n";

			// userlist
			$objUserAdminList = new cUserAdminList($iUserStateFilter,$sSortAttribute,$sSortDirection);
			$objUserAdminList->loadData($this->m_objInputHandler->getIntFormVar("page",TRUE,TRUE,TRUE),$this->m_objConfig->getUserPerPage());

			$sDateFormat = $this->m_objConfig->getDateFormat();
			$iTimeOffset = $this->m_objConfig->getTimeOffset()*3600;
			foreach($objUserAdminList->getDataArray() as $objUser){
				$this->m_sOutput .= "<tr><td><a href=\"pxmboard.php?mode=admuserform&usrid=".$objUser->getId()."\" TARGET=\"_blank\">";
				$this->m_sOutput .= htmlspecialchars($objUser->getNickName())."</a></td>";
				$this->m_sOutput .= "<td align=\"right\">".(($objUser->getRegistrationTimestamp()>0)?date($sDateFormat,($objUser->getRegistrationTimestamp()+$iTimeOffset)):0)."</td>";
				$this->m_sOutput .= "<td align=\"right\">".(($objUser->getLastUpdateTimestamp()>0)?date($sDateFormat,($objUser->getLastUpdateTimestamp()+$iTimeOffset)):0)."</td>";
				$this->m_sOutput .= "<td align=\"right\">".(($objUser->getLastOnlineTimestamp()>0)?date($sDateFormat,($objUser->getLastOnlineTimestamp()+$iTimeOffset)):0)."</td>";
				$this->m_sOutput .= "<td align=\"right\">".$objUser->getMessageQuantity()."</td>";
				if(in_array($objUser->getStatus(),array_keys($arrUserStates))){
					$this->m_sOutput .= "<td align=\"right\">".htmlspecialchars($arrUserStates[$objUser->getStatus()])."</td></tr>\n";
				}
				else{
					$this->m_sOutput .= "<td align=\"right\">".$objUser->getStatus()." ???</td></tr>\n";
				}
			}
			$this->m_sOutput .= "<tr id=\"h\"><td>";
			if($objUserAdminList->getPrevPageId()>0){
				$this->m_sOutput .= "<a href=\"pxmboard.php?mode=admuserlist&sort=".urlencode($sSortMode)."&direction=$sSortDirection&filter=$iUserStateFilter&page=".$objUserAdminList->getPrevPageId()."\">&lt;&lt;</a>";
			}
			$this->m_sOutput .= "</td><td colspan=\"4\"></td><td>";
			if($objUserAdminList->getNextPageId()>0){
				$this->m_sOutput .= "<a href=\"pxmboard.php?mode=admuserlist&sort=".urlencode($sSortMode)."&direction=$sSortDirection&filter=$iUserStateFilter&page=".$objUserAdminList->getNextPageId()."\">&gt;&gt;</a>";
			}
			$this->m_sOutput .= "</td></tr>\n</table>";

			if($objResultSet = &$objDb->executeQuery("SELECT u_status,count(*) AS usercount FROM pxm_user GROUP BY u_status ORDER BY u_status ASC")){
				$this->m_sOutput .= "<br><br><table border=\"1\" id=\"c\">\n";
				$this->m_sOutput .= "<tr id=\"h\"><td>user status</td><td>count</td></tr>";
				$iUserCount = 0;
				while($objResultRow = $objResultSet->getNextResultRowObject()){
					if(isset($arrUserStates[intval($objResultRow->u_status)])){
						$iUserCount += intval($objResultRow->usercount);
						$this->m_sOutput .= "<tr><td>".htmlspecialchars($arrUserStates[intval($objResultRow->u_status)])."</td><td>".intval($objResultRow->usercount)."</td></tr>\n";
					}
				}
				$this->m_sOutput .= "<tr id=\"h\"><td>overall</td><td>$iUserCount</td></tr>\n";
				$this->m_sOutput .= "</table>";
			}
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>