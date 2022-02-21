<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * displays the message tool
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.9 $
 */
class cAdminActionMessageform extends cAdminAction{

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

			$objParser = new cParser();	// dummy parser

			$objBoardList = new cBoardList();
			$objBoardList->loadBasicData();
			$arrBoards = &$objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,
													  $this->m_objConfig->getDateFormat(),
													  0,
													  $objParser);

			$this->m_sOutput .= "<h4>messagetool</h4>\n";
			$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('delete messages?')\">\n";
			$this->m_sOutput .= "<input type=\"hidden\" name=\"mode\" value=\"admmessagesdelete\"><table border=\"1\" id=\"c\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">delete threads / messages</td></tr>\n";
			$this->m_sOutput .= "<tr><td rowspan=\"".(sizeof($arrBoards)+1)."\" align=\"center\">delete threads &amp; messages in </td><td>";

			foreach($arrBoards as $arrVal) {
				$this->m_sOutput .= "<input type=\"checkbox\" name=\"brds[]\" value=\"".$arrVal["id"]."\" checked> ".htmlspecialchars($arrVal["name"])."</td></tr>\n<tr><td>";
			}

			$this->m_sOutput .= "<input type=\"checkbox\" name=\"priv\" value=\"1\" checked> <i>private messages</i></td></tr>\n";

			$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\">not in use for <input type=\"text\" name=\"date\" value=\"30\" size=\"5\"> day(s)</td></tr>\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"delete\"></td></tr>\n";
			$this->m_sOutput .= "</table></form>";
			$this->m_sOutput .= "<br><br><table border=\"1\" id=\"c\">\n";
			$this->m_sOutput .= "<tr id=\"h\"><td>board</td><td>first message</td><td>last message</td><td>count</td><td>per day</td></tr>";

			$sDateFormat = $this->m_objConfig->getDateFormat();
			$iTimeOffset = $this->m_objConfig->getTimeOffset()*3600;

			// public messages
			if($objResultSet = &$objDb->executeQuery("SELECT b_name,count(*) AS msgcount,min(m_tstmp) AS minmsg,max(m_tstmp) AS maxmsg FROM pxm_message,pxm_thread,pxm_board WHERE m_threadid=t_id AND t_boardid=b_id AND t_fixed=0 GROUP BY b_id ORDER BY b_name")){
				$iMsgCount = 0;
				$iAverage = 0;
				$iMsgFirst = -1;
				$iMsgLast = -1;
				while($objResultRow = $objResultSet->getNextResultRowObject()){
					$iTmpMsgCount = intval($objResultRow->msgcount);
					$iTmpMsgFirst = intval($objResultRow->minmsg);
					$iTmpMsgLast = intval($objResultRow->maxmsg);
					$iTmpTimeSpan = $iTmpMsgLast-$iTmpMsgFirst;
					$iTmpAverage = (($iTmpTimeSpan>=86400)?round(($iTmpMsgCount*86400)/$iTmpTimeSpan,3):$iTmpMsgCount);
					$iAverage += $iTmpAverage;
					$iMsgCount += $iTmpMsgCount;
					if($iMsgFirst<0 || $iTmpMsgFirst<$iMsgFirst){
						$iMsgFirst = $iTmpMsgFirst;
					}
					if($iMsgLast<0 || $iTmpMsgLast>$iMsgLast){
						$iMsgLast = $iTmpMsgLast;
					}
					$this->m_sOutput .= "<tr><td>".htmlspecialchars($objResultRow->b_name)."</td>";
					$this->m_sOutput .= "<td>".(($iTmpMsgFirst>0)?date($sDateFormat,($iTmpMsgFirst+$iTimeOffset)):0)."</td>";
					$this->m_sOutput .= "<td>".(($iTmpMsgLast>0)?date($sDateFormat,($iTmpMsgLast+$iTimeOffset)):0)."</td>";
					$this->m_sOutput .= "<td>".$iTmpMsgCount."</td><td>".$iTmpAverage."</td></tr>\n";
				}
			}

			// private messages
			if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS msgcount,min(p_tstmp) AS minmsg,max(p_tstmp) AS maxmsg FROM pxm_priv_message")){
				if($objResultRow = $objResultSet->getNextResultRowObject()){
					$iTmpMsgCount = intval($objResultRow->msgcount);
					$iTmpMsgFirst = intval($objResultRow->minmsg);
					$iTmpMsgLast = intval($objResultRow->maxmsg);
					$iTmpTimeSpan = $iTmpMsgLast-$iTmpMsgFirst;
					$iTmpAverage = (($iTmpTimeSpan>=86400)?round(($iTmpMsgCount*86400)/$iTmpTimeSpan,3):$iTmpMsgCount);
					$iAverage += $iTmpAverage;
					$iMsgCount += $iTmpMsgCount;
					$this->m_sOutput .= "<tr><td><i>private messages</i></td>";
					$this->m_sOutput .= "<td>".(($iTmpMsgFirst>0)?date($sDateFormat,($iTmpMsgFirst+$iTimeOffset)):0)."</td>";
					$this->m_sOutput .= "<td>".(($iTmpMsgLast>0)?date($sDateFormat,($iTmpMsgLast+$iTimeOffset)):0)."</td>";
					$this->m_sOutput .= "<td>".$iTmpMsgCount."</td><td>".$iTmpAverage."</td></tr>\n";
				}
			}
			$this->m_sOutput .= "<tr id=\"h\"><td>overall</td>";
			$this->m_sOutput .= "<td>".(($iMsgFirst>0)?date($sDateFormat,($iMsgFirst+$iTimeOffset)):0)."</td>";
			$this->m_sOutput .= "<td>".(($iMsgLast>0)?date($sDateFormat,($iMsgLast+$iTimeOffset)):0)."</td>";
			$this->m_sOutput .= "<td>".$iMsgCount."</td><td>".$iAverage."</td></tr>\n";
			$this->m_sOutput .= "</table><br><br>note: fixed threads are ignored\n";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>