<?php
require_once(INCLUDEDIR."/actions/cAction.php");
require_once(INCLUDEDIR."/cSkinList.php");
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
 * shows the user config form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.6 $
 */
class cActionUserconfigform extends cAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		if($objActiveUser = $this->m_objConfig->getActiveUser()){

			$objSession = &$this->m_objConfig->getSession();

			$this->m_objTemplate = &$this->_getTemplateObject("userconfigform");
			$this->m_objTemplate->addData($this->m_objConfig->getDataArray(array("cookie"=>(strlen($objSession->getCookieVar("ticket"))>0)?"1":"0")));

			$arrSkinList = array();
			$objSkinList = new cSkinList();
			$arrAvailableTemplateEngines = &$this->m_objConfig->getAvailableTemplateEngines();
			foreach($objSkinList->getList() as $objSkin){
				if(array_intersect($arrAvailableTemplateEngines,$objSkin->getSupportedTemplateEngines())){
					$arrSkinList[] = array("id" => $objSkin->getId(),"name" => $objSkin->getName());
				}
			}
			$this->m_objTemplate->addData($dummy = array("skin" => $arrSkinList));

			if($objActiveUser->loadDataById($objActiveUser->getId())){					// refresh data
				$this->m_objTemplate->addData($dummy = array("user" => $objActiveUser->getDataArray(NULL, NULL, NULL)));
			}
		}
		else $this->m_objTemplate = &$this->_getErrorTemplateObject(new cError(22));	// not loged in
	}
}
?>