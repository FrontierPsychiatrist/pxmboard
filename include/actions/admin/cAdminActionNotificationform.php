<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cNotification.php");
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
 * displays the notification form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionNotificationform extends cAdminAction{

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

			$objNotification = new cNotification();

			if($objNotification->loadDataById($this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE))){

				$this->m_sOutput .= "<h4>notification configuration</h4>\n";

				$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('update configuration?')\">\n";
				$this->m_sOutput .= $this->_getHiddenField("mode","admnotificationsave").$this->_getHiddenField("id",$objNotification->getId());
				$this->m_sOutput .= "<table border=\"1\" id=\"c\">\n<tr><td id=\"h\">".htmlspecialchars($objNotification->getName())."</td></tr>\n";

				$this->m_sOutput .= "<tr><td>".nl2br(htmlspecialchars($objNotification->getDescription()))."</td></tr>\n";
				$this->m_sOutput .= "<tr><td><textarea cols=\"50\" rows=\"15\" name=\"message\">";
				$this->m_sOutput .= htmlspecialchars($objNotification->getMessage())."</textarea></td></tr>\n";

				$this->m_sOutput .= "<tr><td align=\"center\"><input type=\"submit\" value=\"update data\">&nbsp;";
				$this->m_sOutput .= "<input type=\"reset\" value=\"reset data\"></td></tr>\n";
				$this->m_sOutput .= "</table></form><br><br>note: ".$this->m_objInputHandler->getInputSize("notification")." chars allowed\n";
			}
			else $this->m_sOutput .= "<h3 id=\"e\">notification not found</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>