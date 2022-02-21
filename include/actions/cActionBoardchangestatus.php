<?php
require_once(INCLUDEDIR."/actions/cActionBoardlist.php");
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
 * change the status of a board
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.5 $
 */
class cActionBoardchangestatus extends cActionBoardlist{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		$objError = NULL;

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){
			$objBoard = new cBoard();
			if($objBoard->loadDataById($this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE))){
				$objBoard->updateIsActive(!$objBoard->isActive());
			}
		}
		else $objError = new cError(12);						// forbidden

		cActionBoardlist::performAction();

		if(is_object($objError)){
			$this->m_objTemplate->addData($dummy = array("error"=>$objError->getDataArray()));
		}
	}
}
?>