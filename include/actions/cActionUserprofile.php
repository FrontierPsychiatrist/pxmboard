<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cUserProfile.php");
require_once(INCLUDEDIR."/cProfileConfig.php");
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
 * shows a user profile
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/06/18 14:36:18 $
 * @version $Revision: 1.6 $
 */
class cActionUserprofile extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
		$iIdUser = $this->m_objInputHandler->getIntFormVar("usrid",TRUE,TRUE,TRUE);

		if($iIdUser>0){

			$objProfileConfig = new cProfileConfig();

			$objUserProfile = new cUserProfile($objProfileConfig->getSlotList());

			if($objUserProfile->loadDataById($iIdUser)){

				$objParser = new cParser();	// dummy parser

				$this->m_objTemplate = &$this->_getTemplateObject("userprofile");
				$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("propicdir"=>$this->m_objConfig->getProfileImgDirectory())));
				$this->m_objTemplate->addData($dummy = array("user"=>$objUserProfile->getDataArray($this->m_objConfig->getTimeOffset()*3600,
																								   $this->m_objConfig->getDateFormat(),
																								   $objParser)));
			}
			else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));// invalid user id
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(20));	// invalid user id
	}
}
?>