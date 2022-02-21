<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cBannerList.php");
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
 * displays the banner edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionBannerform extends cAdminAction{

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
			if(!$this->m_objConfig->useBanners()){
				$this->m_sOutput .= "<span id=\"e\">Banners are disabled in general configuration!</span><br><br>\n";
			}
			$this->m_sOutput .= "<table border=\"0\">\n<tr>\n<td>";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('delete banners?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admbannerdelete\"><table border=\"1\" width=\"100%\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"8\" id=\"h\">delete banners</td></tr>\n";
			$this->m_sOutput .= "<tr><td id=\"h\">board</td><td id=\"h\">code</td><td id=\"h\">start</td><td id=\"h\">expiration</td><td id=\"h\">views</td><td id=\"h\">max v.</td><td id=\"h\">edit</td><td id=\"h\">del</td></tr>\n";

			$objBannerList = new cBannerList();

			foreach($objBannerList->getList() as $objBanner){
				$this->m_sOutput .= "<tr><td>".htmlspecialchars($objBanner->getBoardName())."</td><td align=\"center\">".nl2br(htmlspecialchars($objBanner->getBannerCode()));
				$this->m_sOutput .= "</td><td align=\"right\">".($objBanner->getStartTimestamp()>0?date("d.m.y",$objBanner->getStartTimestamp()):"-")."</td><td align=\"right\">".($objBanner->getEndTimestamp()>0?date("d.m.y",$objBanner->getEndTimestamp()):"-")."</td>";
				$this->m_sOutput .= "<td align=\"right\">".$objBanner->getViews()."</td><td align=\"right\">".$objBanner->getMaxViews()."</td>";
				$this->m_sOutput .= "<td><a href=\"pxmboard.php?mode=admbannereditform&id=".$objBanner->getId()."\">edit</a></td>";
				$this->m_sOutput .= "<td ".(( ($objBanner->getEndTimestamp()>0 && $objBanner->getEndTimestamp()<$this->m_objConfig->getAccessTimestamp()) || ($objBanner->getMaxViews()>0 && $objBanner->getMaxViews()<=$objBanner->getViews()) )?" bgcolor=\"red\"":"");
				$this->m_sOutput .= "align=\"center\"><input type=\"checkbox\" name=\"del[]\" value=\"".$objBanner->getId()."\"></td></tr>\n";
			}

			$this->m_sOutput .= "<tr><td colspan=\"8\" align=\"center\"><input type=\"submit\" value=\"delete\"></td></tr>\n";
			$this->m_sOutput .= "</table></form></td>\n</tr>\n<tr>\n<td><hr><br></td>\n</tr>\n<tr>\n<td>";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('add banner?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admbannersave\"><table border=\"1\" width=\"100%\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">add banner</td></tr>\n</tr><td>board</td><td><select name=\"board\" size=\"1\">";

			$objParser = new cParser();	// dummy parser

			$objBoardList = new cBoardList();
			$objBoardList->loadBasicData();
			$this->m_sOutput .= "<option value=\"0\">boardindex</option>\n<option value=\"-1\">boardindex & all boards</option>\n<option value=\"-2\">all boards</option>\n";
			foreach($objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat(),$this->m_objConfig->getAccessTimestamp(),$objParser) as $arrBoard){
				$this->m_sOutput .= "<option value=\"".$arrBoard["id"]."\">".htmlspecialchars($arrBoard["name"])."</option>\n";
			}

			$this->m_sOutput .= "</select></td></tr>\n";
			$this->m_sOutput .= "<tr><td>code</td><td><textarea name=\"code\" cols=\"40\" rows=\"4\"></textarea></td></tr>\n";
			$this->m_sOutput .= "<tr><td>start</td><td><select name=\"day\" size=\"1\">\n";

	 		$arrToday = getdate($this->m_objConfig->getAccessTimestamp());

			for($x=1;$x<32;$x++){
				$this->m_sOutput .= "<option value=\"$x\"".($arrToday['mday']==$x?" selected":"").">$x</option>\n";
			}

			$this->m_sOutput .= "</select> day <select name=\"month\" size=\"1\">\n";
			for($x=1;$x<13;$x++){
				$this->m_sOutput .= "<option value=\"$x\"".($arrToday['mon']==$x?" selected":"").">$x</option>\n";
			}

			$this->m_sOutput .= "</select> month <select name=\"year\" size=\"1\">\n";
			for($x = $arrToday['year'];$x<$arrToday['year']+3;$x++){
				$this->m_sOutput .= "<option value=\"$x\">$x</option>\n";
			}

			$this->m_sOutput .= "</select> year</td></tr>\n";
			$this->m_sOutput .= "<tr><td>active for</td><td><input type=\"text\" name=\"exp\" value=\"0\" size=\"3\"> days (0 = no limit)</td></tr>\n";
			$this->m_sOutput .= "<tr><td>max views</td><td><input type=\"text\" name=\"maxviews\" value=\"0\" size=\"3\"> (0 = no limit)</td></tr>\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"add\"></td></tr>\n";
			$this->m_sOutput .= "</table></form></td>\n</tr>\n</table>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>