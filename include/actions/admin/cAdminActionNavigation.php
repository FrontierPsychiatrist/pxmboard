<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * displays the navigation for the admintool
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 19:07:18 $
 * @version $Revision: 1.9 $
 */
class cAdminActionNavigation extends cAdminAction{

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

		$this->m_sOutput .= $this->_getHead(FALSE);

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){
			if(strcasecmp($objDb->getDBType(),"MySQL")==0){
				$bIsMySql = TRUE;
			}
			else{
				$bIsMySql = FALSE;
			}

			$this->m_sOutput .=  "</center><br><h4><a href=\"pxmboard.php?mode=admintro\" target=\"content\">PXMBoard</a></h4>\n";
			$this->m_sOutput .= "configuration<br>\n";
			$this->m_sOutput .= "<ul><li><a href=\"pxmboard.php?mode=admconfigform\" target=\"content\">general</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admreplacementform\" target=\"content\">textreplacements</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admbadwordform\" target=\"content\">badwords</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admforbiddenmailform\" target=\"content\">forbidden mails</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admerrorform\" target=\"content\">errors</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admbannerform\" target=\"content\">banner</a></li>\n";
	   		$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admprofileform\" target=\"content\">profile</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admnotificationlist\" target=\"content\">notifications</a></li>\n";
			$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admskinlist\" target=\"content\">skins</a></li></ul>\n";
			$this->m_sOutput .= "usertool<br>\n";
			$this->m_sOutput .= "<ul><li><a href=\"pxmboard.php?mode=admuserlist\" target=\"content\">overview</a></li>\n";
			if(!$this->m_objConfig->useDirectRegistration()){
				$this->m_sOutput .= "<li><a href=\"pxmboard.php?mode=admactivateusersform\" target=\"content\">activation</a></li>\n";
			}
			$this->m_sOutput .= "</ul><a href=\"pxmboard.php?mode=admmessageform\" target=\"content\">messagetool</a><br><br>\n";
			if($bIsMySql){
				$this->m_sOutput .= "<a href=\"pxmboard.php?mode=admdbcleanform\" target=\"content\">clean db</a>\n";
			}
			$this->m_sOutput .= "<center><br><br><br><hr><br><span style=\"font-size:80%\">copyright <sup>&copy;</sup> (1998 - 2006)<br><a href=\"mailto:forum@torsten-rentsch.de\">Torsten Rentsch</a></span>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>