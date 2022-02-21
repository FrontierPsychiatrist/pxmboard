<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * displays the profile edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.6 $
 */
class cAdminActionProfileform extends cAdminAction{

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
			$this->m_sOutput .= "<h4>profile configuration</h4>\n";
			$this->m_sOutput .= "<span id=\"e\">Table structure will be altered!<br>\nBackup data before using this tool!!!</span><br><br>\n<table border=\"0\">\n<tr>\n<td>";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('delete profile fields?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admprofiledelete\"><table border=\"1\" width=\"100%\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"4\" id=\"h\">delete profile fields</td></tr>\n";
			$this->m_sOutput .= "<tr><td id=\"h\">name</td><td id=\"h\">type</td><td id=\"h\">length</td><td id=\"h\">del</td></tr>\n";

			$objProfileConfig = new cProfileConfig();

			foreach($objProfileConfig->getSlotList() as $sKey=>$arrVal){
				$this->m_sOutput .= "<tr><td>".htmlspecialchars($sKey)."</td><td align=\"center\">";
				switch($arrVal[0]){
					case "i"	:	$this->m_sOutput .= "integer";
									break;
					case "s"	:	$this->m_sOutput .= "string";
									break;
					case "a"	:	$this->m_sOutput .= "area";
									break;
					default		:	$this->m_sOutput .= "???";
									break;
				}
				$this->m_sOutput .= "</td><td align=\"right\">".intval($arrVal[1])."</td><td align=\"center\"><input type=\"checkbox\" name=\"del[]\" value=\"".htmlspecialchars($sKey)."\"></td></tr>\n";
			}

			$this->m_sOutput .= "<tr><td colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"delete\"></td></tr>\n";
			$this->m_sOutput .= "</table></form></td>\n</tr>\n<tr>\n<td><hr><br></td>\n</tr>\n<tr>\n<td>";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('add profile field?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admprofileadd\"><table border=\"1\" width=\"100%\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"3\" id=\"h\">add profile field</td></tr>\n";
			$this->m_sOutput .= "<tr><td id=\"h\">name</td><td id=\"h\">type</td><td id=\"h\">length</td></tr>\n";
			$this->m_sOutput .= "<tr><td><input type=\"text\" name=\"name\" size=\"10\" maxlength=\"10\"></td><td><select name=\"type\" size=\"1\">\n";
			$this->m_sOutput .= "<option value=\"i\" selected>integer</option>\n<option value=\"s\">string</option>\n<option value=\"a\">area</option>\n";
			$this->m_sOutput .= "</select></td><td><input type=\"text\" name=\"length\" value=\"20\" size=\"3\"></td></tr>\n";
			$this->m_sOutput .= "<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"add\"></td></tr>\n";
			$this->m_sOutput .= "</table></form></td>\n</tr>\n</table>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>