<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cBoard.php");
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
 * save the board data
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.7 $
 */
class cAdminActionBoardsave extends cAdminAction{

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
			if($objBoard->loadDataById($iBoardId)){
				$bInsert = FALSE;
			}
			else{
				$bInsert = TRUE;
			}

			$objBoard->setName($this->m_objInputHandler->getStringFormVar("name","boardname",TRUE,TRUE,"trim"));
			$objBoard->setDescription($this->m_objInputHandler->getStringFormVar("desc","boarddescription",TRUE,TRUE,"trim"));
			$objBoard->setIsActive($this->m_objInputHandler->getIntFormVar("status",TRUE,TRUE,TRUE));
			$objBoard->setThreadListTimeSpan($this->m_objInputHandler->getIntFormVar("date",TRUE,TRUE,TRUE));
			$objBoard->setThreadListSortMode($this->m_objInputHandler->getStringFormVar("sort","sortmode",TRUE,TRUE,"trim"));
			$objBoard->setParseStyle($this->m_objInputHandler->getIntFormVar("style",TRUE,TRUE,TRUE));
			$objBoard->setParseUrl($this->m_objInputHandler->getIntFormVar("url",TRUE,TRUE,TRUE));
			$objBoard->setParseImages($this->m_objInputHandler->getIntFormVar("pimg",TRUE,TRUE,TRUE));
			$objBoard->setDoTextReplacements($this->m_objInputHandler->getIntFormVar("repl",TRUE,TRUE,TRUE));

			$objBoard->setModeratorsByNickName(explode("\n",$this->m_objInputHandler->getStringFormVar("mod","",TRUE,TRUE,"trim")));

			if($bInsert){
				if($objBoard->insertData()){
					$objBoard->updateModData();
					$this->m_sOutput .= "<h3>data saved</h3>";
				}
				else{
					$this->m_sOutput .= "<h3 id=\"e\">could not insert data</h3>";
				}
			}
			else{
				if($objBoard->updateData()){
					$objBoard->updateModData();
					$this->m_sOutput .= "<h3>data saved</h3>";
				}
				else{
					$this->m_sOutput .= "<h3 id=\"e\">could not update data</h3>";
				}
			}
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>