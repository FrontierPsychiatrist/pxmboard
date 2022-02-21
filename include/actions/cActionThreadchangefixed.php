<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cThread.php");
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
 * change whether the thread is fixed on top of the threadlist or not
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.6 $
 */
class cActionThreadchangefixed extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if($objActiveBoard = &$this->m_objConfig->getActiveBoard()){

			$iBoardId = $objActiveBoard->getId();

			if($objActiveUser = &$this->m_objConfig->getActiveUser() && ($objActiveUser->isAdmin() || $objActiveUser->isModerator($iBoardId))){

				$objThread = new cThread();
				if($objThread->loadDataById($this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE),$iBoardId)){
					if($objThread->updateIsFixed(!$objThread->isFixed())){
						$this->m_objTemplate = &$this->_getTemplateObject("confirm");
						$this->m_objTemplate->addData($this->m_objConfig->getDataArray());
					}
				}
				else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(10));// invalid thread id
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(12));	// forbidden
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(5));			// missing board id
	}
}
?>