<?php
require_once(INCLUDEDIR."/cError.php");
require_once(INCLUDEDIR."/cInputHandler.php");
require_once(INCLUDEDIR."/templatelayer/cTemplateFactory.php");
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
 * base class for the board actions
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:28 $
 * @version $Revision: 1.9 $
 */
 class cAction{

	var $m_objConfig;
	var $m_objTemplate;
	var $m_objInputHandler;

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objConfig configuration data of the board
	 * @return void
	 */
	function __construct(&$objConfig){
		$this->m_objConfig = &$objConfig;
		$this->m_objTemplate = NULL;
		$this->m_objInputHandler = new cInputHandler();
	}

	/**
	 * login the user by ticket
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function loginUserByTicket(){
		if(!$this->m_objConfig->getActiveUser()){
			$objSession = &$this->m_objConfig->getSession();
			if($sLoginTicket = $objSession->getCookieVar("ticket")){
				include_once(INCLUDEDIR."/cUserConfig.php");
				include_once(INCLUDEDIR."/cUserStates.php");
				$objUser = new cUserConfig();
				if($objUser->loadDataByTicket($sLoginTicket) && ($objUser->getStatus() == cUserStates::userActive())){
					$objSession->startSession();
					$objSession->setSessionVar("activeuser",$objUser);
					$this->m_objConfig->setActiveUser($objUser);
				}
				else{
					$objSession->setCookieVar("ticket","",$this->m_objConfig->getAccessTimestamp()-3600);
				}
			}
		}
	}
	
	/**
	 * get the banner code for a board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @return array banner code
	 */
	function &getBannerCode($iIdBoard = 0){
		$arrBannerCode = array();
		if($this->m_objConfig->useBanners()){
			include_once(INCLUDEDIR."/cBanner.php");
			$objBanner = new cBanner();
			$objBanner->loadRandomData($this->m_objConfig->getAccessTimestamp(),$iIdBoard);
			$arrBannerCode = array("banner"=>$objBanner->getDataArray());
		}
		return $arrBannerCode;
	}

	/**
	 * do the pre actions (manipulate GET and POST data etc.)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function doPreActions(){
	}

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){
	}

	/**
	 * do the post actions (what should happen after performin the action?)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function doPostActions(){
	}

	/**
	 * get the output of this action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string output of this action
	 */
	function getOutput(){
		if(is_object($this->m_objTemplate)){
			return $this->m_objTemplate->getOutput();
		}
		else{
			return "Overwrite getOutput() for actions that don't use templates";
		}
	}

	/**
	 * get the template object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sTemplateName name of the template
	 * @return object template
	 */
	function &_getTemplateObject($sTemplateName){
		$objSkin = &$this->m_objConfig->getActiveSkin();
		$objTemplate = cTemplateFactory::getTemplateObject($this->m_objConfig->getActiveTemplateEngine(),$this->m_objConfig->getSkinDirectory().$objSkin->getDirectory());
		$objTemplate->setTemplateName($sTemplateName);
		return $objTemplate;
	}

	/**
	 * get the error template object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objError error object
	 * @return object template
	 */
	function &_getErrorTemplateObject($objError){
		$objSkin = &$this->m_objConfig->getActiveSkin();
		$objTemplate = cTemplateFactory::getTemplateObject($this->m_objConfig->getActiveTemplateEngine(),$this->m_objConfig->getSkinDirectory().$objSkin->getDirectory());
		$sTemplateName = "error-".strtolower(get_class($this));
		if(!$objTemplate->isTemplateValid($sTemplateName)){
			$sTemplateName = "error";
		}
		$objTemplate->setTemplateName($sTemplateName);
		$objTemplate->addData($this->m_objConfig->getDataArray());
		$objTemplate->addData($dummy = array("error"=>$objError->getDataArray()));
		return $objTemplate;
	}
}
?>