<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cTextreplacementList.php");
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
 * displays the textreplacement edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.5 $
 */
class cAdminActionReplacementform extends cAdminAction{

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
			$this->m_sOutput .= "<h4>configuration</h4>\n";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('update configuration?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admreplacementsave\"><table border=\"1\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">textreplacements</td></tr>\n";
			$this->m_sOutput .= "<tr valign=\"top\"><td>string=>replacement<br>string2=>replacement2</td><td><textarea cols=\"30\" rows=\"30\" name=\"repl\">";

			$objTextreplacementList = new cTextreplacementList();
			$arrTextreplacementList = &$objTextreplacementList->getList();
			foreach($arrTextreplacementList["search"] as $iKey=>$sSearch){
				$this->m_sOutput .= htmlspecialchars($sSearch)."=>".htmlspecialchars($arrTextreplacementList["replace"][$iKey])."\n";
			}
			$this->m_sOutput .= "</textarea></td></tr>\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"update data\">&nbsp;<input type=\"reset\" value=\"reset data\"></td></tr>\n";
			$this->m_sOutput .= "</table></form>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>