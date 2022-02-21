<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cUserStates.php");
require_once(INCLUDEDIR."/cUserConfig.php");
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
 * displays the frameset for the admintool
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 19:07:18 $
 * @version $Revision: 1.16 $
 */
class cAdminActionFrame extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if(!$this->m_objConfig->getActiveUser()){

			$sNickName = $this->m_objInputHandler->getStringFormVar("nick","nickname",TRUE,TRUE,"trim");
			$sPassword = $this->m_objInputHandler->getStringFormVar("pass","password",TRUE,TRUE,"trim");

			if(!empty($sNickName)){

				$objUser = new cUserConfig();

				if($objUser->loadDataByNickName($sNickName)){

					if($objUser->validatePassword($sPassword)){
						if($objUser->getStatus() == cUserStates::userActive()){
							$objSession = &$this->m_objConfig->getSession();

							$objSession->startSession();
							$objSession->setSessionVar("activeuser",$objUser);

							$this->m_objConfig->setActiveUser($objUser);
						}
						else{
							$this->m_sOutput .= $this->_getHead();
							$this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";
							$this->m_sOutput .= $this->_getFooter();
						}
					}
					else{
						$this->m_sOutput .= $this->_getHead();
						$this->m_sOutput .= "<h3 id=\"e\">password invalid</h3>";
						$this->m_sOutput .= $this->_getFooter();
					}
				}
				else{
					$this->m_sOutput .= $this->_getHead();
					$this->m_sOutput .= "<h3 id=\"e\">couldn't find user</h3>";
					$this->m_sOutput .= $this->_getFooter();
				}
			}
			else{
				$this->m_sOutput .= $this->_getHead();
				$this->m_sOutput .= "<table border=\"1\" id=\"c\"><form action=\"pxmboard.php\" method=\"post\">".$this->_getHiddenField("mode","admframe");
				$this->m_sOutput .= $this->_getTextField("nick",$this->m_objInputHandler->getInputSize("nickname"),"","nickname");
				$this->m_sOutput .= "<tr><td>password</td><td><input type=\"password\" name=\"pass\" size=\"".$this->m_objInputHandler->getInputSize("password")."\" maxlength=\"".$this->m_objInputHandler->getInputSize("password")."\"></td></tr>";
				$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"login\"></td></tr></form></table><br><br><br><br><br>\n";
				$this->m_sOutput .= "<center><br><br><br><br><table style=\"font-size:80%\"><tr><td align=\"center\">Version: ".trim(str_replace(array("Name:","\$"),"",'$Name: pxmboard-2-5-1 $'))."</td></tr>\n";
				$this->m_sOutput .= "<tr><td align=\"center\">copyright <sup>&copy;</sup> (1998 - 2006)</td></tr>";
				$this->m_sOutput .= "<tr><td align=\"center\"><a href=\"mailto:forum@torsten-rentsch.de\">Torsten Rentsch</a></td></tr></table>\n";
				$this->m_sOutput .= $this->_getFooter();
			}
		}

		if($objActiveUser = &$this->m_objConfig->getActiveUser()){
			if($objActiveUser->isAdmin()){
				$this->m_sOutput .= "<html>\n<head>\n<title>-= PXMBoard: Admintool =-</title>\n</head>\n";
				$this->m_sOutput .= "<frameset cols=\"180,*\" border=\"1\" framespacing=\"0\" frameborder=\"1\">\n";
				$this->m_sOutput .= "<frame src=\"pxmboard.php?mode=admnavigation\">\n";
				$this->m_sOutput .= "<frame src=\"pxmboard.php?mode=admintro\" name=\"content\">\n";
				$this->m_sOutput .= "</frameset>\n<body>\n</body>\n</html>";
			}
			else $this->m_sOutput .= "<html><body><h3 id=\"e\">forbidden</h3></body></html>";
		}
	}
}
?>