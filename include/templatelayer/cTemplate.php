<?php
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
 * abstraction layer for output (interface)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:01:35 $
 * @version $Revision: 1.5 $
 */
 class cTemplate{

	var $m_sSkinDir;					// skin directory
	var $m_sTemplateName;				// name of the template
	var $m_sTemplateExtension;			// template file extension

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSkinDir skin directory
	 * @return void
	 */
	function __construct($sSkinDir){

		$this->m_sSkinDir = $sSkinDir;
		$this->m_sTemplateName = "";
		$this->m_sTemplateExtension = "";
	}

	/**
	 * set the name of the template
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sTemplateName name of the template
	 * @return void
	 */
	function setTemplateName($sTemplateName){
		$this->m_sTemplateName = $sTemplateName;
	}

	/**
	 * is the given template valid (is found)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sTemplateName name of the template
	 * @return boolean success / failure
	 */
	function isTemplateValid($sTemplateName){
		if(file_exists($this->m_sSkinDir."/".$sTemplateName.$this->m_sTemplateExtension)){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * add data to the template
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrData key - value pairs
	 * @return boolean success / failure
	 */
	function addData($arrData){
		return $this->_addDataRecursive($arrData,"");
	}

	/**
	 * add data to the template (internal recursive template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param array $arrData key - value pairs
	 * @param string $sSubst subst string for integer keys
	 * @return boolean success / failure
	 */
	function _addDataRecursive(&$arrData,$sSubst = ""){
		return TRUE;
	}

	/**
	 * get the parsed template
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string parsed template
	 */
	function getOutput(){
		return "";
	}
}
?>