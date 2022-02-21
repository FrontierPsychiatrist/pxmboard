<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cSkinList.php");
require_once(INCLUDEDIR."/cBoardList.php");
require_once(INCLUDEDIR."/cBoard.php");
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
 * displays the board edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.14 $
 */
class cAdminActionBoardform extends cAdminAction{

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

			$iBoardId = $this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE);

			$objBoard = new cBoard();

			if($iBoardId>0){
				if($objBoard->loadDataById($iBoardId)){

					$objBoard->updatePosition($this->m_objInputHandler->getIntFormVar("position",TRUE,TRUE,TRUE));

					$objBoard->loadModData();
					$this->m_sOutput .= "<script language=\"JavaScript\">\n";
					$this->m_sOutput .= "  function delbrd()\n  {\n";
					$this->m_sOutput .= "  	result = confirm(\"Remove board $iBoardId?\");\n";
					$this->m_sOutput .= "  	if(result == true) location.href=\"pxmboard.php?mode=admboarddelete&id=$iBoardId\"\n";
					$this->m_sOutput .= "  }\n</script>\n";
					$this->m_sOutput .= "<h4>edit board configuration</h4>\n";
				}
				else{
					$iBoardId = 0;
					$this->m_sOutput .= "<h4>add new board</h4>\n";
				}
			}
			else $this->m_sOutput .= "<h4>add new board</h4>\n";

			$this->m_sOutput .= "<table border=\"0\">\n<form action=\"pxmboard.php\" method=\"post\" onsubmit=\"return confirm('update configuration?')\">".$this->_getHiddenField("mode","admboardsave");
			$this->m_sOutput .= "\n<tr valign=\"top\"><td><table border=\"1\" id=\"c\" width=\"100%\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\">configuration</td></tr>\n";
			$this->m_sOutput .= "<tr><td>ID</td><td>$iBoardId".$this->_getHiddenField("id",$iBoardId)."</td></tr>\n";
			$this->m_sOutput .= $this->_getTextField("name",$this->m_objInputHandler->getInputSize("boardname"),$objBoard->getName(),"boardname");
			$this->m_sOutput .= $this->_getTextField("desc",$this->m_objInputHandler->getInputSize("boarddescription"),$objBoard->getDescription(),"description");
			$this->m_sOutput .= "<tr><td>last message</td><td>".(($objBoard->getLastMessageTimestamp()>0)?date($this->m_objConfig->getDateFormat(),($objBoard->getLastMessageTimestamp()+$this->m_objConfig->getTimeOffset()*3600)):0)."</td></tr>\n";
			$this->m_sOutput .= $this->_getCheckboxField("status",1,"active?",$objBoard->isActive());
			$this->m_sOutput .= $this->_getCheckboxField("style",1,"parse style?",$objBoard->parseStyle());
			$this->m_sOutput .= $this->_getCheckboxField("url",1,"parse url?",$objBoard->parseUrl());
			$this->m_sOutput .= $this->_getCheckboxField("pimg",1,"parse image?",$objBoard->parseImages());
			$this->m_sOutput .= $this->_getCheckboxField("repl",1,"do textreplacements?",$objBoard->doTextReplacements());
			$this->m_sOutput .= $this->_getTextField("date",5,$objBoard->getThreadListTimeSpan(),"timespan (days)");

			$sSortMode = $objBoard->getThreadListSortMode();
			$this->m_sOutput .= "<tr><td>sortmode</td><td><select name=\"sort\" size=\"1\">";
			$this->m_sOutput .= "<option value=\"thread\"".((strcasecmp($sSortMode,"thread")==0)?" selected":"").">thread</option>";
			$this->m_sOutput .= "<option value=\"last\"".((strcasecmp($sSortMode,"last")==0)?" selected":"").">last reply</option>";
			$this->m_sOutput .= "<option value=\"nickname\"".((strcasecmp($sSortMode,"nickname")==0)?" selected":"").">nickname</option>";
			$this->m_sOutput .= "<option value=\"subject\"".((strcasecmp($sSortMode,"subject")==0)?" selected":"").">subject</option>";
			$this->m_sOutput .= "<option value=\"replies\"".((strcasecmp($sSortMode,"replies")==0)?" selected":"").">replies</option>";
			$this->m_sOutput .= "<option value=\"views\"".((strcasecmp($sSortMode,"views")==0)?" selected":"").">views</option>";
			$this->m_sOutput .= "</select></td></tr>\n";

			$this->m_sOutput .= "</table></td><td><table border=\"1\" id=\"c\" width=\"100%\">\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\" id=\"h\"><b>moderators</b></td></tr>\n";
			$this->m_sOutput .= "<tr><td colspan=\"2\"><textarea cols=\"20\" rows=\"10\" name=\"mod\">";

			foreach($objBoard->getModerators() as $objModerator){
				$this->m_sOutput .= htmlspecialchars($objModerator->getNickName())."\n";
			}

			$this->m_sOutput .= "</textarea></td></tr>\n";
			$this->m_sOutput .= "</table></td></tr>\n";
			$this->m_sOutput .= "<tr valign=\"top\"><td><br>";
			if($iBoardId>0){
				$this->m_sOutput .= "<input type=\"button\" value=\"delete board\" ondblclick=\"delbrd()\"><br><br>";
				$this->m_sOutput .= "<font size=\"-1\" color=\"red\">doubleclick to delete this board<br>and corresponding threads</font>";
			}
			else{
				$this->m_sOutput .="&nbsp;";
			}
			$this->m_sOutput .= "</td><td align=\"center\"><br><input type=\"submit\" value=\"update data\">&nbsp;<input type=\"reset\" value=\"reset data\"></td></tr></form>";

			if($iBoardId>0){
				$this->m_sOutput .= "<tr><td colspan=\"2\"><table border=\"1\" id=\"c\" width=\"100%\">\n";
				$this->m_sOutput .= "<tr><td id=\"h\">change board position</td></tr>\n";

				$objParser = new cParser();	// dummy parser

				$objBoardList = new cBoardList();
				$objBoardList->loadBasicData();
				foreach($objBoardList->getDataArray($this->m_objConfig->getTimeOffset()*3600,$this->m_objConfig->getDateFormat(),0,$objParser) as $arrBoard){
					if($arrBoard["id"]==$iBoardId){
						$this->m_sOutput .= "<tr><td><b>".htmlspecialchars($arrBoard["name"])."</b></td></tr>";
					}
					else{
						$this->m_sOutput .= "<tr><td><a href=\"pxmboard.php?mode=admboardform&id=$iBoardId&position=".$arrBoard["position"]."\">".htmlspecialchars($arrBoard["name"])."</a></td></tr>";
					}
				}
				$this->m_sOutput .= "</table></td></tr>";
			}
			$this->m_sOutput .= "</table>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>