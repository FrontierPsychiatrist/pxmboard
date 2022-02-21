<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cBanner.php");
require_once(INCLUDEDIR."/cBoardList.php");
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
 * edit a banner
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionBannereditform extends cAdminAction{

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

			$this->m_sOutput .= "<h4>banner configuration</h4>\n";

			$objBanner = new cBanner();

			if($objBanner->loadDataById($this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE))){

				$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\">\n";
				$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admbannersave\"><input type=\"hidden\" name=\"id\" value=\"".$objBanner->getId()."\"><table border=\"1\" id=\"c\">\n";
				$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">edit banner</td></tr>\n</tr><td>board</td><td><select name=\"board\" size=\"1\">";

				$objParser = new cParser();	// dummy parser

				$objBoardList = new cBoardList();
				$objBoardList->loadBasicData();
				$iBannerBoardId = $objBanner->getBoardId();
				$this->m_sOutput .= "<option value=\"0\">boardindex</option>\n<option value=\"-1\">boardindex & all boards</option>\n<option value=\"-2\">all boards</option>\n";
				foreach($objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat(),$this->m_objConfig->getAccessTimestamp(),$objParser) as $arrBoard){
					$this->m_sOutput .= "<option value=\"".$arrBoard["id"]."\" ".($iBannerBoardId==$arrBoard["id"]?"selected":"").">".htmlspecialchars($arrBoard["name"])."</option>\n";
				}

				$this->m_sOutput .= "</select></td></tr>\n";
				$this->m_sOutput .= "<tr><td>code</td><td><textarea name=\"code\" cols=\"40\" rows=\"4\">".htmlspecialchars($objBanner->getBannerCode())."</textarea></td></tr>\n";
				$this->m_sOutput .= "<tr><td>start</td><td><select name=\"day\" size=\"1\">\n";

				$arrBannerDay = getdate($objBanner->getStartTimestamp());

				for($x=1;$x<32;$x++){
					$this->m_sOutput .= "<option value=\"$x\"".($arrBannerDay['mday']==$x?" selected":"").">$x</option>\n";
				}

				$this->m_sOutput .= "</select> day <select name=\"month\" size=\"1\">\n";
				for($x=1;$x<13;$x++){
					$this->m_sOutput .= "<option value=\"$x\"".($arrBannerDay['mon']==$x?" selected":"").">$x</option>\n";
				}

				$this->m_sOutput .= "</select> month <select name=\"year\" size=\"1\">\n";
				for($x = $arrBannerDay['year'];$x<$arrBannerDay['year']+3;$x++){
					$this->m_sOutput .= "<option value=\"$x\">$x</option>\n";
				}

				$this->m_sOutput .= "</select> year</td></tr>\n";
				$this->m_sOutput .= "<tr><td>active for</td><td><input type=\"text\" name=\"exp\" value=\"".($objBanner->getEndTimestamp()>0?($objBanner->getEndTimestamp()-$objBanner->getStartTimestamp())/86400:"0")."\" size=\"3\"> days (0 = no limit)</td></tr>\n";
				$this->m_sOutput .= "<tr><td>max views</td><td><input type=\"text\" name=\"maxviews\" value=\"".$objBanner->getMaxViews()."\" size=\"3\"> (0 = no limit)</td></tr>\n";
				$this->m_sOutput .= "<tr><td>views</td><td>".$objBanner->getViews()."</td></tr>\n";
				$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"submit\"> <input type=\"reset\" value=\"reset\"></td></tr>\n";
				$this->m_sOutput .= "</table></form>";
			}
			else $this->m_sOutput .= "<h3 id=\"e\">banner not found</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>