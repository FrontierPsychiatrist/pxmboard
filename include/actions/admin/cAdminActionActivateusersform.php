<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * displays the form for user activation
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.8 $
 */
class cAdminActionActivateusersform extends cAdminAction{

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

		if(!$this->m_objConfig->useDirectRegistration() && $objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){

			$objNotification = new cNotification();
			$objNotification->loadDataById(5);

			$this->m_sOutput .= "\n<script language=\"JavaScript\">\n";
			$this->m_sOutput .= "  function updtext(id,state)\n  {\n";
			$this->m_sOutput .= "  	document.forms[0].elements['r'+id].value=(state)?\"".str_replace(array("\n","\r","\t"),array("\\n","\\r","\\t"),htmlspecialchars($objNotification->getMessage()))."\":\"\";\n";
			$this->m_sOutput .= "  }\n</script>\n";
			$this->m_sOutput .= "<h4>activate users</h4>\n";
			$this->m_sOutput .= "<table border=\"1\" id=\"c\"><form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('activate / delete useres?')\">".$this->_getHiddenField("mode","admactivateusers");
			$this->m_sOutput .= "<tr><td id=\"h\">nickname</td><td id=\"h\">first name</td><td id=\"h\">last name</td>";
			$this->m_sOutput .= "<td id=\"h\">private mail</td><td id=\"h\">date of registration</td><td id=\"h\">act</td><td id=\"h\">del</td><td id=\"h\">reason</td></tr>\n";

			if($objResultSet = &$objDb->executeQuery("SELECT u_id,u_nickname,u_registrationtstmp,u_firstname,u_lastname,u_privatemail FROM pxm_user WHERE u_status=".cUserStates::userNotActivated()." ORDER BY u_registrationtstmp DESC")){
				$sDateFormat = $this->m_objConfig->getDateFormat();
				$iTimeOffset = $this->m_objConfig->getTimeOffset()*3600;
				while($objResultRow = $objResultSet->getNextResultRowObject()){
					$this->m_sOutput .= "<tr><td><a href=\"pxmboard.php?mode=admuserform&usrid=".$objResultRow->u_id."\" TARGET=\"_blank\">".htmlspecialchars($objResultRow->u_nickname)."</a></td><td>".htmlspecialchars($objResultRow->u_firstname)."</td><td>";
					$this->m_sOutput .= htmlspecialchars($objResultRow->u_lastname)."</td><td>";

					$sPrivateMail = htmlspecialchars($objResultRow->u_privatemail);
					$arrPrivMail = explode("@",$sPrivateMail);
					if(sizeof($arrPrivMail)>1){
						$this->m_sOutput .= $arrPrivMail[0]."@<a href=\"http://www.".$arrPrivMail[1]."\" TARGET=\"_blank\">".$arrPrivMail[1]."</a>";
					}
					else $this->m_sOutput .= $sPrivateMail;

					$this->m_sOutput .= "</td><td align=\"right\">".(($objResultRow->u_registrationtstmp>0)?date($sDateFormat,($objResultRow->u_registrationtstmp+$iTimeOffset)):0)."</td>";
					$this->m_sOutput .= "<td bgcolor=\"lightgreen\">".$this->_getCheckboxField("act[]",$objResultRow->u_id,"",TRUE);
					$this->m_sOutput .= "</td><td bgcolor=\"red\"><input type=\"checkbox\" name=\"del[]\" value=\"".$objResultRow->u_id."\" onclick=\"updtext(".$objResultRow->u_id.",this.checked)\"></td><td>";
					$this->m_sOutput .= "<textarea name=\""."r".$objResultRow->u_id."\" rows=\"1\" cols=\"25\"></textarea></td></tr>\n";
				}
				$objResultSet->freeResult();
				$this->m_sOutput .=  "<tr id=\"h\"><td colspan=\"8\" align=\"center\"><input type=\"submit\"></td></tr></form>";
			}
			$this->m_sOutput .= "</table>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>