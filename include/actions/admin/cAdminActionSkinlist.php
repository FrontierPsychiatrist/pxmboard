<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cSkinList.php");
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
 * displays the list of skins
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.5 $
 */
class cAdminActionSkinlist extends cAdminAction{

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

			$this->m_sOutput .= "<h4>skin list</h4>\n";
			$this->m_sOutput .= "<table border=\"1\" id=\"c\"><tr><td id=\"h\">name</td><td id=\"h\">type</td><td id=\"h\">edit</td></tr>";

			$objSkinList = new cSkinList();

			foreach($objSkinList->getList() as $objSkin){
				$this->m_sOutput .= "<td>".htmlspecialchars($objSkin->getName())."</td>";
				if(array_intersect($this->m_objConfig->getAvailableTemplateEngines(),$objSkin->getSupportedTemplateEngines())){
					$this->m_sOutput .= "<td bgcolor=\"lightgreen\">";
				}
				else{
					$this->m_sOutput .= "<td bgcolor=\"red\">";
				}
				$this->m_sOutput .= htmlspecialchars(implode(",",$objSkin->getSupportedTemplateEngines()))."</td>";
				$this->m_sOutput .= "</td><td><a href=\"pxmboard.php?mode=admskinform&id=".$objSkin->getId()."\">edit</a></td></tr>\n";
			}
			$this->m_sOutput .= "</td></tr>\n</table>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>